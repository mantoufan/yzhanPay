<?php
namespace plugins\paypal;

use common\base\Common;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use service\plugin\pay\CheckoutService;
use service\plugin\PluginService;

class Paypal extends Common
{
    public function getGateway($channel_id)
    {
        $config = PluginService::GetChannelConfig($channel_id);
        if (empty($config)) {
            $this->export(array('status' => 403));
        }
        if ($config['is_sandbox']) {
            $environment = new SandboxEnvironment($config['client_id'], $config['secret']);
        } else {
            $environment = new ProductionEnvironment($config['client_id'], $config['secret']);
        }
        return new PayPalHttpClient($environment);
    }

    public function submit($channel_id, $params)
    {
        $gateway = $this->getGateway($channel_id);
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = array(
            "intent" => 'CAPTURE',
            "purchase_units" => array(array(
                'reference_id' => $params['trade_no'],
                'amount' => array(
                    'value' => $params['total_amount'],
                    'currency_code' => 'USD',
                ),
                'item' => [
                    array(
                        0 => array(
                            'name' => $params['subject'],
                            'description' => $params['body'],
                        ),
                    ),
                ],
            )),
            'application_context' => array(
                'cancel_url' => PluginService::GetCancelUrl('alipay', $channel_id),
                'return_url' => PluginService::GetReturnUrl('alipay', $channel_id),
            ),
        );
        try {
            $response = $gateway->execute($request);
            if ($response->statusCode === 201) {
                $links = $response->result->links;
                var_dump($links);
                $approve_link = $links[1]->href;
                $this->export(array(
                    'status' => 302,
                    'header' => array(
                        'Location' => $approve_link,
                    ),
                ));
            }
        } catch (HttpException $ex) {
            echo $ex->statusCode;
            print_r($ex->getMessage());
        }
    }

    public function sync($channel_id)
    {
        $gateway = $this->getGateway($channel_id);
        $request = $gateway->completePurchase();
        $request->setParams(array_merge($_POST, $_GET));
        $params = $request->getParams();
        $return_url = CheckoutService::GetReturnUrl($params);
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
        $params = CheckoutService::getNotifyParams($params);
        $app_id = empty($params) ? 0 : $params['app_id'];
        $this->export(array(
            'body' => $body,
            'app_id' => $app_id,
        ));
    }
}
