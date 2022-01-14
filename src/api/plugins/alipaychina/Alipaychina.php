<?php
namespace plugins\alipaychina;

use common\base\Common;
use Omnipay\Omnipay;
use service\plugin\pay\BillService;
use service\plugin\pay\NotifyService;
use service\plugin\PluginService;

class Alipaychina extends Common
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
        BillService::UpdateTrade(array(
            'data' => array(
                'api_trade_no' => $params['trade_no'],
            ),
            'where' => array(
                'trade_no' => $params['out_trade_no'],
                'channel_id' => $channel_id,
            ),
        ));
        $return_url = NotifyService::GetReturnUrl(NotifyService::GetNotifyParams(array(
            'where' => array(
                'trade_no' => $params['out_trade_no'],
            ),
        )));
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
                $params['trade_status'] = TRADE_STATUS['CHECKOUT_SUCCEED'];
                $body = 'success';
            } else {
                $params['trade_status'] = TRADE_STATUS['CREATED'];
                $body = 'fail';
            }
        } catch (Exception $e) {
            $params['trade_status'] = TRADE_STATUS['CHECKOUT_FAIL'];
            $body = 'fail';
        }
        BillService::UpdateTrade(array(
            'data' => array(
                'api_trade_no' => $params['trade_no'],
                'status' => $params['trade_status'],
            ),
            'where' => array(
                'trade_no' => $params['out_trade_no'],
                'channel_id' => $channel_id,
            ),
        ));
        $this->export(array(
            'body' => $body,
            'app_id' => NotifyService::NotifyReturnAppId(
                NotifyService::GetNotifyParams(array(
                    'where' => array(
                        'trade_no' => $params['out_trade_no'],
                    ),
                ))
            ),
        ));
    }
}
