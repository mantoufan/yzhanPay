<?php
namespace controller;

use common\base\Common;
use service\AuthService;
use service\ChannelService;
use service\DbService;
use service\TradeService;

class Gateway extends Common
{
    public function sumbit()
    {
        $params = getPosts();
        $_app_id = $params['app_id'];
        $_channel_id = $params['channel_id'];
        $channel = ChannelService::GetById($_channel_id);
        $channel_enabled = $channel['enabled'];
        unset($params['channel_id']);
        $data = DbService::Get('app', array(
            'field' => array('app_key'),
            'where' => array(
                'app_id' => $_app_id,
            ),
        ));
        $app_key = $data['app_key'];
        if (empty($channel_enabled) || !AuthService::SignCheck($params, $app_key)) {
            header('status: 403');
            exit('403');
        }
        $_subject = $params['subject'];
        $_body = $params['body'];
        $_timestamp = $params['timestamp'];
        $_out_trade_no = $params['out_trade_no'];
        $_total_amount = $params['total_amount'];
        $_return_url = $params['return_url'];
        $_notify_url = $params['notify_url'];

        $trade_no = TradeService::CreateNo();
        $params['trade_no'] = $trade_no;
        DbService::Create('trade', array(
            'data' => array(
                'trade_no' => $trade_no,
                'out_trade_no' => $_out_trade_no,
                'subject' => $_subject,
                'total_amount' => $_total_amount,
                'timestamp' => $_timestamp,
                'return_url' => $_return_url,
                'notify_url' => $_notify_url,
                'body' => $_body,
                'status' => 'WAIT_BUYER_PAY',
                'notify_status' => 0,
                'channel_id' => $_channel_id,
                'app_id' => $_app_id,
            ),
        ));
        $channel_plugin = $channel['plugin'];
        $plugin_class_name = 'plugins\\' . $channel_plugin . '\\' . ucfirst($channel_plugin);
        $gateway = new $plugin_class_name($_channel_id);
        $gateway->submit($_channel_id, $params);
    }
}
