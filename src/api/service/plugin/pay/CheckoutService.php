<?php
namespace service\plugin\pay;

use service\AppService;
use service\AuthService;
use service\DbService;
use service\QueueService;

class CheckoutService
{
    public static function GetReturnUrl($channel_id, $params)
    {
        $data = self::GetTradeParams($channel_id, $params);
        if (empty($data)) {
            return null;
        }
        $return_url = $data['return_url'];
        $params = $data['params'];
        return $return_url . '?' . http_build_query($params);
    }

    public static function getNotifyParams($channel_id, $params)
    {
        $data = self::GetTradeParams($channel_id, $params);
        if (empty($data)) {
            return null;
        }

        $notify_url = $data['notify_url'];
        $params = $data['params'];
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
        $api_customer_id = $params['api_customer_id'];
        $trade_status = $params['trade_status'];
        DbService::Update('trade', array(
            'data' => array('api_trade_no' => $api_trade_no, 'api_customer_id' => $api_customer_id, 'status' => $trade_status, 'notify_status' => 0),
            'where' => array(
                'trade_no' => $trade_no,
            ),
        ));
        return $params;
    }

    private static function GetTradeParams($channel_id, $params)
    {
        $_trade_status = $params['trade_status'];

        $_api_trade_no = $params['api_trade_no'];
        $_api_customer_id = $params['api_customer_id'];

        if (!empty($params['trade_no'])) {
            $where = array('trade_no' => $params['trade_no'], 'channel_id' => $channel_id);
        } else {
            $where = array('api_trade_no' => $params['api_trade_no'], 'channel_id' => $channel_id);
        }

        $data = DbService::Get('trade', array(
            'field' => array('app_id', 'total_amount', 'currency', 'channel_id', 'trade_no', 'out_trade_no', 'subject', 'body', 'return_url', 'notify_url'),
            'where' => $where,
        ));

        if (empty($data)) {
            return null;
        }

        $app_id = $data['app_id'];
        $channel_id = $data['channel_id'];
        $trade_no = $data['trade_no'];
        $out_trade_no = $data['out_trade_no'];
        $total_amount = $data['total_amount'];
        $currency = $data['currency'];
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
            'api_customer_id' => $_api_customer_id,
            'total_amount' => $total_amount,
            'currency' => $currency,
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
