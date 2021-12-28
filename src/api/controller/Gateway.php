<?php
namespace controller;

use common\base\Trade;
use service\AuthService;
use service\ChannelService;
use service\DbService;
use service\TradeService;

class Gateway extends Trade
{
    public function sumbit()
    {
        $params = getParams();
        $_app_id = $params['app_id'];
        $_channel_id = $params['channel_id'];
        $channel = ChannelService::GetById($_channel_id);
        $channel_active = $channel['active'];
        unset($params['channel_id']);
        $data = DbService::Get('app', array(
            'field' => array('app_key'),
            'where' => array(
                'app_id' => $_app_id,
            ),
        ));
        $app_key = $data['app_key'];
        if (empty($channel_active) || (0 && !AuthService::SignCheck($params, $app_key))) {
            $this->export(array('status' => 403));
        }
        $_request_time = $params['request_time'];
        $_out_trade_no = $params['out_trade_no'];
        $_total_amount = $params['total_amount'];
        $_currency = $params['currency'];
        $_return_url = $params['return_url'];
        $_notify_url = $params['notify_url'];
        $_cancel_url = $params['cancel_url'];
        $_ability = $params['ability'];
        $_products = $params['products'];

        if (empty($_products)) {
            $this->export(array('status' => 403));
        } else {
            $_products = json_decode($_products, true);
            $data = $this->getProducts($_products, $_app_id, $_ability === 'subscribe');
            $total_amount = $data['total_amount'];
            $params['subject'] = $data['subject'];
            $params['body'] = $data['body'];
            $params['products'] = $data['products'];
        }

        $params['trade_nos'] = array();
        $total = $_ability === 'subscribe' ? count($params['products']) : 1;
        for ($i = 0; $i < $total; $i++) {
            if ($_ability === 'subscribe') {
                $_subject = $params['products'][$i]['name'];
                $_body = $params['products'][$i]['description'];
                $_product_id = $params['products'][$i]['id'];
                $_plan_id = $params['products'][$i]['plan']['id'];
                $_customer_id = $params['products'][$i]['customer']['id'];
            } else {
                $_subject = $params['subject'];
                $_body = $params['body'];
                $_product_id = $_plan_id = $_customer_id = null;
            }
            $params['trade_nos'][] = TradeService::Create('trade', array(
                'data' => array(
                    'out_trade_no' => $_out_trade_no,
                    'subject' => $_subject,
                    'total_amount' => $total_amount,
                    'currency' => $_currency,
                    'request_time' => $_request_time,
                    'return_url' => $_return_url,
                    'notify_url' => $_notify_url,
                    'cancel_url' => $_cancel_url,
                    'body' => $_body,
                    'status' => 'WAIT_BUYER_PAY',
                    'notify_status' => 0,
                    'channel_id' => $_channel_id,
                    'app_id' => $_app_id,
                    'product_id' => $_product_id,
                    'plan_id' => $_plan_id,
                    'customer_id' => $_customer_id,
                ),
            ));
        }
        $params['trade_no'] = $params['trade_nos'][0];
        $channel_plugin = $channel['plugin'];
        $plugin_class_name = 'plugins\\' . $channel_plugin . '\\' . ucfirst($channel_plugin);
        $gateway = new $plugin_class_name($_channel_id);
        if ($params['ability'] === 'checkout') {
            $gateway->checkout($_channel_id, $params);
        } else {
            $gateway->subscribe($_channel_id, $params);
        }
    }

}
