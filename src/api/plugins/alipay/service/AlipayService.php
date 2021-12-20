<?php
namespace plugins\alipay\service;

use GuzzleHttp\Client;
use service\AuthService;
use service\DbService;

class AlipayService
{
    private static function parseParams($params)
    {
        $_trade_status = $params['trade_status'];
        $_out_trade_no = $params['out_trade_no'];
        $_api_trade_no = $params['trade_no'];
        $_total_amount = $params['total_amount'];

        $data = DbService::DbGet('trade', array(
            'field' => array('app_id', 'channel_id', 'trade_no', 'out_trade_no', 'subject', 'body', 'return_url', 'notify_url'),
            'where' => array(
                'trade_no' => $_out_trade_no,
            ),
        ));

        if (!empty($data)) {
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

            $params['sign'] = AuthService::AuthSign($params);
        } else {
            $data = array();
        }

        return array(
            'return_url' => $data['return_url'],
            'notify_url' => $data['notify_url'],
            'params' => $params,
        );
    }

    public static function sync($params)
    {
        $data = self::parseParams($params);
        $return_url = $data['return_url'];
        $params = $data['params'];
        return $return_url . '?' . http_build_query($params);
    }

    public static function async($params)
    {
        $data = self::parseParams($params);
        $notify_url = $data['notify_url'];
        $params = $data['params'];
        $opts = array(
            'http' => array(
                'method' => 'POST',
                'timeout' => 60,
            ),
        );
        $client = new Client(array('verify' => false));
        $response = $client->request('POST', $notify_url, array(
            'form_params' => $params,
        ));
        $contents = $response->getBody()->getContents();
        $trade_no = $params['trade_no'];
        $api_trade_no = $params['api_trade_no'];
        $trade_status = $params['trade_status'];
        DbService::DbUpdate('trade', array(
            'data' => array('api_trade_no' => $api_trade_no, 'status' => $trade_status, 'notify_status' => $contents === 'success' ? 1 : 0, 'notify_time' => date('Y-m-d H:i:s')),
            'where' => array(
                'trade_no' => $trade_no,
            ),
        ));
    }
}
