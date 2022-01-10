<?php
namespace controller;

use common\base\Trade;
use service\AuthService;
use service\ChannelService;
use service\DbService;
use service\plugin\pay\BillService;
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
        $_return_url = $params['return_url'];
        $_notify_url = $params['notify_url'];
        $_cancel_url = $params['cancel_url'];
        $_ability = $params['ability'];
        $_product = $params['product'];
        $_plan = $params['plan'];
        $_customer = $params['customer'];

        if (empty($_product)) {
            $this->export(array('status' => 403));
        } else {
            $_product = json_decode($_product, true);
            $_subject = $params['subject'] = $_product['name'];
            $_body = $params['body'] = $_product['description'];
        }

        if ($_ability === 'subscribe') {
            $params['product'] = $this->getProduct($_product, $_app_id, $_ability !== 'subscribe');
            $_product_id = $params['product']['id'];
            $params['plan'] = $this->getPlan(json_decode($_plan, true), $_app_id, $_ability !== 'subscribe');
            $_plan_id = $params['plan']['id'];
            $params['customer'] = $this->getCustomer(json_decode($_customer, true), $_app_id, $_ability !== 'subscribe');
            $_customer_id = $params['customer']['id'];
            $billing_cycles_first = BillService::GetBillingCyclesFirst($params['plan']['billing_cycles']);
            $_total_amount = $billing_cycles_first['amount'];
            $_currency = $billing_cycles_first['currency'];
        } else {
            $_total_amount = $params['total_amount'];
            $_currency = $params['currency'];
            $_product_id = $_plan_id = $_customer_id = null;
        }

        $params['trade_no'] = TradeService::Create(array(
            'data' => array(
                'out_trade_no' => $_out_trade_no,
                'subject' => $_subject,
                'total_amount' => $_total_amount,
                'currency' => $_currency,
                'request_time' => $_request_time,
                'return_url' => $_return_url,
                'notify_url' => $_notify_url,
                'cancel_url' => $_cancel_url,
                'body' => $_body,
                'status' => TRADE_STATUS['CREATED'],
                'notify_status' => 0,
                'channel_id' => $_channel_id,
                'app_id' => $_app_id,
                'product_id' => $_product_id,
                'plan_id' => $_plan_id,
                'customer_id' => $_customer_id,
            ),
        ));
        $gateway = ChannelService::GetGateway($channel['plugin']);
        if ($params['ability'] === 'checkout') {
            $gateway->checkout($_channel_id, $params);
        } else {
            $gateway->subscribe($_channel_id, $params);
        }
    }

}
