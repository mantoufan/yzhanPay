<?php
namespace plugins\alipay;

use common\base\Common;
use Omnipay\Omnipay;
use service\plugin\pay\CheckoutService;
use service\plugin\PluginService;

class Alipay extends Common
{
    public function getGateway($channel_id)
    {
        $config = PluginService::GetChannelConfig($channel_id);
        if (empty($config)) {
            $this->export(array('status' => 403));
        }
        $gateway = Omnipay::create('Alipay_AopPage');
        $gateway->setAppId($config['app_id']);
        $gateway->setPrivateKey($config['private_key']);
        $gateway->setAlipayPublicKey($config['public_key']);
        $gateway->setSignType($config['sign_type']);
        $gateway->setReturnUrl(PluginService::GetReturnUrl('alipay', $channel_id));
        $gateway->setNotifyUrl(PluginService::GetNotifyUrl('alipay', $channel_id));
        if ($config['env'] === 'sandbox') {
            $gateway->sandbox();
        }
        return $gateway;
    }

    public function checkout($channel_id, $params)
    {
        $gateway = $this->getGateway($channel_id);
        $response = $gateway->purchase()->setBizContent([
            'subject' => $params['subject'],
            'out_trade_no' => $params['trade_no'],
            'total_amount' => $params['total_amount'],
            'body' => $params['body'],
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ])->send();
        $this->export(array(
            'status' => 302,
            'header' => array(
                'Location' => $response->getRedirectUrl(),
            ),
        ));
    }

    public function sync($channel_id)
    {
        $gateway = $this->getGateway($channel_id);
        $request = $gateway->completePurchase();
        $request->setParams(array_merge($_POST, $_GET));
        $params = $request->getParams();
        $_out_trade_no = $params['out_trade_no'];
        unset($params['out_trade_no']);
        $_trade_no = $params['trade_no'];
        $params['trade_no'] = $_out_trade_no;
        $params['api_trade_no'] = $_trade_no;
        $return_url = CheckoutService::GetReturnUrl($channel_id, $params);
        if (empty($return_url)) {
            $this->export(array('status' => 403));
        }
        $this->export(array(
            'status' => 302,
            'header' => array(
                'Location' => $return_url,
            ),
        ));
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
                $params['trade_status'] = TRADE_STATUS['TRADE_SUCCESS'];
                $body = 'success';
            } else {
                $params['trade_status'] = TRADE_STATUS['WAIT_BUYER_PAY'];
                $body = 'fail';
            }
        } catch (Exception $e) {
            $params['trade_status'] = TRADE_STATUS['TRADE_CLOSED'];
            $body = 'fail';
        }
        $_out_trade_no = $params['out_trade_no'];
        unset($params['out_trade_no']);
        $_trade_no = $params['trade_no'];
        $params['trade_no'] = $_out_trade_no;
        $params['api_trade_no'] = $_trade_no;
        $params = CheckoutService::getNotifyParams($channel_id, $params);
        $app_id = empty($params) ? 0 : $params['app_id'];
        $this->export(array(
            'body' => $body,
            'app_id' => $app_id,
        ));
    }
}
