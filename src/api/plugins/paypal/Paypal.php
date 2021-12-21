<?php
namespace plugins\paypal;

use Omnipay\Omnipay;
use service\ChannelService;
use plugins\paypal\service\PaypalService;

class Paypal
{
    public function getGateway($channel_id)
    {
        $configs = ChannelService::ChannelConfig($channel_id);
        $global_config = $configs['global_config'];
        $channel_config = $configs['channel_config'];
        $API_URL = $global_config['API_URL'];
        $client_id = $channel_config['client_id'];
        $secret = $channel_config['secret'];
        $is_sandbox = $channel_config['is_sandbox'];
        $public_key = $channel_config['public_key'];
        $sign_type = $channel_config['sign_type'];
        
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