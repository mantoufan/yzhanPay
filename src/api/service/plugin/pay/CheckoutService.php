<?php
namespace service\plugin\pay;

use service\AppService;
use service\AuthService;
use service\DbService;
use service\QueueService;

class CheckoutService
{
    public static function GetReturnUrl($params)
    {
        $data = self::GetTradeParams($params);
        if (empty($data)) {
            return null;
        }
        $return_url = $data['return_url'];
        $params = $data['params'];
        return $return_url . '?' . http_build_query($params);
    }

    public static function getNotifyParams($params)
    {
        $data = self::GetTradeParams($params);
        if (empty($data)) {
            return null;
        }

        $notify_url = $data['notify_url'];
        $params = $data['params'];
        $opts = array(
            'http' => array(
                'method' => 'POST',
                'timeout' => 60,
            ),
        );
        $app_id = $params['app_id'];
        $user_id = AppService::GetUserIdByAppId($app_id);
        $queueService = new QueueService();
        $queueService->add(array(
            'method' => 'notify',
            'path' => $notify_url,
            'action' => 'POST',
            'payload' => $params,
            'timeout' => 5,
            'expect' => 'success',
            'user_id' => $user_id,
            'app_id' => $app_id,
        ));

        $trade_no = $params['trade_no'];
        $api_trade_no = $params['api_trade_no'];
        $trade_status = $params['trade_status'];
        DbService::Update('trade', array(
            'data' => array('api_trade_no' => $api_trade_no, 'status' => $trade_status, 'notify_status' => $contents === 'success' ? 1 : 0, 'notify_time' => date('Y-m-d H:i:s')),
            'where' => array(
                'trade_no' => $trade_no,
            ),
        ));
        return $params;
    }

    private static function GetTradeParams($params)
    {
        $_trade_status = $params['trade_status'];
        $_out_trade_no = $params['out_trade_no'];
        $_api_trade_no = $params['trade_no'];
        $_total_amount = $params['total_amount'];

        $data = DbService::Get('trade', array(
            'field' => array('app_id', 'channel_id', 'trade_no', 'out_trade_no', 'subject', 'body', 'return_url', 'notify_url'),
            'where' => array(
                'trade_no' => $_out_trade_no,
            ),
        ));

        if (empty($data)) {
            return null;
        }

        $app_id = $data['app_id'];
        $channel_id = $data['channel_id'];
        $trade_no = $data['trade_no'];
        $out_trade_no = $data['out_trade_no'];
        $subject = $data['subject'];
        $body = $data['body'];
        $return_url = $data['return_url'];
        $notify_url = $data['notify_url'];

        $params = array(
            'app_id' => $app_id,
            'channel_id' => $channel_id,
            'trade_no' => $trade_no,
            'out_trade_no' => $out_trade_no,
            'api_trade_no' => $_api_trade_no,
            'total_amount' => $_total_amount,
            'subject' => $subject,
            'body' => $body,
            'trade_status' => $_trade_status,
        );

        $params['sign'] = AuthService::Sign($params);

        return array(
            'return_url' => $data['return_url'],
            'notify_url' => $data['notify_url'],
            'params' => $params,
        );
    }
}
