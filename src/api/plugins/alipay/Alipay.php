<?php
namespace plugins\alipay;

use Omnipay\Omnipay;
use plugins\alipay\service\AlipayService;
use service\ChannelService;

class Alipay
{
    public function getGateway($channel_id)
    {
        list($channel_config, $global_config) = ChannelService::ChannelConfig($channel_id);
        $app_id = $channel_config['app_id'];
        $private_key = $channel_config['private_key'];
        $public_key = $channel_config['public_key'];
        $sign_type = $channel_config['sign_type'];
        $is_sandbox = $channel_config['is_sandbox'];
        $API_URL = $global_config['API_URL'];
        $gateway = Omnipay::create('Alipay_AopPage');
        $gateway->setSignType($sign_type);
        $gateway->setAppId($app_id);
        $gateway->setPrivateKey($private_key);
        $gateway->setAlipayPublicKey($public_key);
        $gateway->setReturnUrl($API_URL . '/plugins/alipay/sync/' . $channel_id);
        $gateway->setNotifyUrl($API_URL . '/plugins/alipay/async/' . $channel_id);
        if ($is_sandbox) {
            $gateway->sandbox();
        }
        return $gateway;
    }

    public function submit($channel_id, $params)
    {
        $gateway = $this->getGateway($channel_id);
        $response = $gateway->purchase()->setBizContent([
            'subject' => $params['subject'],
            'out_trade_no' => $params['trade_no'],
            'total_amount' => $params['total_amount'],
            'body' => $params['body'],
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ])->send();
        header('Location: ' . $response->getRedirectUrl());
    }

    public function sync($channel_id)
    {
        $gateway = $this->getGateway($channel_id);
        $request = $gateway->completePurchase();
        $request->setParams(array_merge($_POST, $_GET));
        $params = $request->getParams();
        AlipayService::sync($params);
    }

    public function async($channel_id)
    {
        $gateway = $this->getGateway($channel_id);
        $request = $gateway->completePurchase();
        $request->setParams(array_merge($_POST, $_GET));
        $params = $request->getParams();
        try {
            $response = $request->send();
            if ($response->isPaid()) {
                $params['trade_status'] = 'TRADE_SUCCESS';
                $body = 'success';
            } else {
                $params['trade_status'] = 'WAIT_BUYER_PAY';
                $body = 'fail';
            }
        } catch (Exception $e) {
            $params['trade_status'] = 'TRADE_CLOSED';
            $body = 'fail';
        }
        AlipayService::async($params);
        die($body);
    }
}
