<?php
namespace service\plugin\pay;

use service\AppService;
use service\AuthService;
use service\QueueService;
use service\TradeService;

class NotifyService
{
    public static function GetNoitfyParams($params = array('where' => array()))
    {
        $data = TradeService::Get(array(
            'field' => array('app_id', 'total_amount', 'currency', 'channel_id', 'trade_no', 'out_trade_no', 'subject', 'body', 'plan_id', 'return_url', 'notify_url'),
            'where' => $params['where'],
        ));
        if (empty($data)) {
            return null;
        }
        $params = array(
            'app_id' => $data['app_id'],
            'channel_id' => $data['channel_id'],
            'trade_no' => $data['trade_no'],
            'out_trade_no' => $data['out_trade_no'],
            'api_trade_no' => $data['api_trade_no'],
            'total_amount' => $data['total_amount'],
            'currency' => $data['currency'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'trade_status' => $data['trade_status'],
        );
        $params['sign'] = AuthService::Sign($params);
        return array(
            'return_url' => $data['return_url'],
            'notify_url' => $data['notify_url'],
            'params' => $params,
        );
    }

    public static function GetReturnUrl($notify_params)
    {
        $return_url = $notify_params['return_url'];
        $params = $notify_params['params'];
        return $return_url . '?' . http_build_query($params);
    }

    public static function Notify($notify_params)
    {
        $notify_url = $notify_params['notify_url'];
        $params = $notify_params['params'];
        $app_id = $params['app_id'];
        $user_id = AppService::GetUserIdByAppId($app_id);
        $queueService = new QueueService();
        return $queueService->add(array(
            'method' => 'notify',
            'path' => $notify_url,
            'action' => 'POST',
            'payload' => $params,
            'timeout' => 5,
            'expect' => 'success',
            'user_id' => $user_id,
            'app_id' => $app_id,
        ));
    }
}
