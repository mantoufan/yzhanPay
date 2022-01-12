<?php
namespace service\plugin\pay;

use service\AppService;
use service\AuthService;
use service\QueueService;
use service\TradeService;

class NotifyService
{
    public static function GetNotifyParams($params = array('where' => array()))
    {
        $data = TradeService::Get(array(
            'field' => array('status', 'app_id', 'total_amount', 'currency', 'channel_id', 'trade_no', 'out_trade_no', 'subject', 'body', 'subscription_id', 'customer_id', 'return_url', 'notify_url', 'cancel_url'),
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
            'total_amount' => $data['total_amount'],
            'currency' => $data['currency'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'status' => $data['status'],
        );
        if (!empty($data['subscription_id'])) {
            $params['subscription_id'] = $data['subscription_id'];
        }
        if (!empty($data['customer_id'])) {
            $params['customer_id'] = $data['customer_id'];
        }
        $params['sign'] = AuthService::Sign($params);
        return array(
            'return_url' => $data['return_url'],
            'notify_url' => $data['notify_url'],
            'cancel_url' => $data['cancel_url'],
            'params' => $params,
        );
    }

    public static function GetReturnUrl($notify_params)
    {
        $return_url = $notify_params['return_url'];
        $params = $notify_params['params'];
        return $return_url . '?' . http_build_query($params);
    }

    public static function GetCancelUrl($notify_params)
    {
        return $notify_params['cancel_url'];
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
            'user_id' => $user_id,
            'app_id' => $app_id,
            'queue_expect' => 'succeed',
            'queue_timeout' => 5,
        ));
    }

    public static function NotifyReturnAppId($notify_params)
    {
        return self::Notify($notify_params) ? $notify_params['params']['app_id'] : null;
    }
}
