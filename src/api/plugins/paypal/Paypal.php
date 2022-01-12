<?php
namespace plugins\paypal;

use common\base\Common;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalSubscriptionsSdk\Catalog\ProductsCreateRequest;
use PayPalSubscriptionsSdk\Plans\PlansCreateRequest;
use PayPalSubscriptionsSdk\Subscriptions\SubscriptionsCaptureRequest;
use PayPalSubscriptionsSdk\Subscriptions\SubscriptionsCreateRequest;
use service\plugin\pay\BillService;
use service\plugin\pay\NotifyService;
use service\plugin\PluginService;
use \Exception;

class Paypal extends Common
{
    public function getGateway($channel_id)
    {
        $config = PluginService::GetChannelConfig($channel_id);
        if (empty($config)) {
            $this->export(array('status' => 403));
        }
        if ($config['env'] === 'sandbox') {
            $environment = new SandboxEnvironment($config['client_id'], $config['secret']);
        } else {
            $environment = new ProductionEnvironment($config['client_id'], $config['secret']);
        }
        return new PayPalHttpClient($environment);
    }

    public function getParamsByType($params, $type)
    {
        switch ($type) {
            case 'product':
                return array(
                    'name' => $params['name'],
                    'description' => $params['description'],
                    'type' => $params['type'],
                    'category' => $params['category'],
                    'image_url' => $params['image_url'],
                    'home_url' => $params['url'],
                );
            case 'plan':
                return array(
                    'name' => $params['name'],
                    'description' => $params['description'],
                    'status' => $params['status'],
                    'billing_cycles' => $params['billing_cycles'],
                    'payment_preferences' => $params['payment_preferences'],
                );
            case 'customer':
                return array(
                    'name' => array('given_name' => $params['name']['first_name'], 'surname' => $params['name']['last_name']),
                    'email_address' => $params['email'],
                );
        }
    }

    public function checkout($channel_id, $params)
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
                'cancel_url' => PluginService::GetCancelUrl('paypal', $channel_id),
                'return_url' => PluginService::GetReturnUrl('paypal', $channel_id),
            ),
        );
        try {
            $response = $gateway->execute($request);
            if ($response->statusCode === 201) {
                BillService::UpdateTrade(array(
                    'data' => array('api_trade_no' => $response->result->id),
                    'where' => array('trade_no' => $params['trade_no'], 'app_id' => $params['app_id']),
                ));
                $links = $response->result->links;
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

    public function subscribe($channel_id, $params)
    {
        $gateway = $this->getGateway($channel_id);
        $product = $this->getParamsByType($params['product'], 'product');
        $plan = $this->getParamsByType($params['plan'], 'plan');
        $customer = $this->getParamsByType($params['customer'], 'customer');
        $res_product = $this->createProduct($gateway, $product);
        $plan['product_id'] = $res_product->result->id;
        $res_plan = $this->createPlan($gateway, $plan);
        $api_plan_id = $res_plan->result->id;
        $subscription = array(
            'plan_id' => $api_plan_id,
            'subscriber' => $customer,
            'application_context' => array(
                'cancel_url' => PluginService::GetCancelUrl('paypal', $channel_id),
                'return_url' => PluginService::GetReturnUrl('paypal', $channel_id, true),
            ),
        );
        $response = $this->createSubscription($gateway, $subscription);
        if ($response->statusCode === 201) {
            $api_subscription_id = $response->result->id;
            $api_customer_id = $response->result->subscriber->payer_id;
            BillService::UpdateTrade(array(
                'data' => array(
                    'api_subscription_id' => $api_subscription_id,
                    'api_plan_id' => $api_plan_id,
                    'api_customer_id' => $api_customer_id,
                    'api_product_id' => $plan['product_id'],
                ),
                'where' => array('trade_no' => $params['trade_no'], 'app_id' => $params['app_id']),
            ));
            $link = arrayFind($response->result->links, function ($link) {
                return $link->rel === 'approve';
            });
            $return_url = $link->href;
        }
        if (empty($return_url)) {
            $this->export(array('status' => 403));
        }
        $this->export(array(
            'status' => 302,
            'header' => array(
                'Location' => $return_url,
            ),
            'body' => $body,
        ));
    }

    public function cancel($channel_id)
    {
        $params = getGets();
        $where = array(
            'channel_id' => $channel_id,
        );
        if (!empty($params['subscription_id'])) {
            $where['api_subscription_id'] = $params['subscription_id'];
        } else {
            $where['api_trade_no'] = $params['token'];
        }
        $cancel_url = NotifyService::GetCancelUrl(NotifyService::GetNotifyParams(array(
            'where' => $where,
        )));
        BillService::UpdateTrade(array(
            'data' => array(
                'status' => TRADE_STATUS['CLOSED'],
            ),
            'where' => array(
                'api_trade_no' => $_token,
                'channel_id' => $channel_id,
            ),
        ));
        $this->export(array(
            'status' => 302,
            'header' => array(
                'Location' => $cancel_url,
            ),
        ));
    }

    public function sync($channel_id)
    {
        $gateway = $this->getGateway($channel_id);
        $params = getGets();
        $_token = $params['token'];
        $_PayerID = $params['PayerID'];
        $request = new OrdersCaptureRequest($_token);
        $request->prefer('return=representation');
        try {
            $response = $gateway->execute($request);
            if ($response->result->status === 'COMPLETED') {
                $params['trade_status'] = TRADE_STATUS['CHECKOUT_SUCCEED'];
                $body = '';
            } else {
                $params['trade_status'] = TRADE_STATUS['CREATED'];
                $body = $response->result;
            }
        } catch (HttpException $e) {
            $params['trade_status'] = TRADE_STATUS['CHECKOUT_FAIL'];
            $body = $e->getMessage();
        }
        BillService::UpdateTrade(array(
            'data' => array(
                'api_customer_id' => $_PayerID,
                'status' => $params['trade_status'],
            ),
            'where' => array(
                'api_trade_no' => $_token,
                'channel_id' => $channel_id,
            ),
        ));
        $return_url = NotifyService::GetReturnUrl(NotifyService::GetNotifyParams(array(
            'where' => array(
                'api_trade_no' => $_token,
                'channel_id' => $channel_id,
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
            'body' => $body,
        ));
    }

    public function syncSubscribe($channel_id)
    {
        $gateway = $this->getGateway($channel_id);
        $params = getGets();
        $_subscription_id = $params['subscription_id'];
        BillService::UpdateTrade(array(
            'data' => array('status' => TRADE_STATUS['SUBSCRIPTION_WAIT_REMIND']),
            'where' => array(
                'api_subscription_id' => $_subscription_id,
                'channel_id' => $channel_id,
            ),
        ));
        $return_url = NotifyService::GetReturnUrl(NotifyService::GetNotifyParams(array(
            'where' => array(
                'api_subscription_id' => $_subscription_id,
                'channel_id' => $channel_id,
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
            'body' => $body,
        ));
    }

    public function createProduct($gateway, $product)
    {
        $request = new ProductsCreateRequest();
        $request->setData($product);
        try {
            return $gateway->execute($request);
        } catch (HttpException $e) {
            return array(
                'statusCode' => $e->statusCode,
                'message' => $e->getMessage(),
            );
        }
    }

    public function createPlan($gateway, $plan)
    {
        $request = new PlansCreateRequest();
        $request->setData($plan);
        try {
            return $gateway->execute($request);
        } catch (HttpException $e) {
            return array(
                'statusCode' => $e->statusCode,
                'message' => $e->getMessage(),
            );
        }
    }

    public function createSubscription($gateway, $subscription)
    {
        $request = new SubscriptionsCreateRequest();
        $request->setData($subscription);
        try {
            return $gateway->execute($request);
        } catch (HttpException $e) {
            return array(
                'statusCode' => $e->statusCode,
                'message' => $e->getMessage(),
            );
        }
    }

    public function async($channel_id)
    {
        try {
            $this->checkSign();
        } catch (\Exception $e) {
            $this->export(array(
                'status' => 403,
                'body' => array(
                    'code' => 403,
                    'msg' => $e->getMessage(),
                ),
            ));
        }
        $gateway = $this->getGateway($channel_id);
        $_params = getPosts();
        $_event_type = $_params['event_type'];
        switch ($_event_type) {
            case 'PAYMENT.SALE.COMPLETED':
                $where['channel_id'] = $channel_id;
                if (!empty($params['resource']['billing_agreement_id'])) {
                    $where['api_subscription_id'] = $params['resource']['billing_agreement_id'];
                } else {
                    $where['api_trade_no'] = $params['resource']['id'];
                }
                BillService::UpdateTrade(array(
                    'data' => array(
                        'status' => TRADE_STATUS['SUBSCRIPTION_CHARGE_SUCCEED'],
                        'subscription_start_time' => date('Y-m-d H:i:s'),
                    ),
                    'where' => $where,
                ));
                $this->export(array(
                    'status' => 200,
                    'body' => 'success',
                    'app_id' => NotiFyService::NotifyReturnAppId(
                        NotifyService::GetNoitfyParams(array('where' => $where))
                    ),
                ));
                break;
        }

    }

    public function checkSign()
    {
        $headers = getallheaders();
        $body = @file_get_contents('php://input');
        if (empty($body)) {
            $verifyResult = 0;
        } else {
            $json = json_decode($body);
            $sigString = $headers['Paypal-Transmission-Id'] . '|' . $headers['Paypal-Transmission-Time'] . '|' . $json->id . '|' . crc32($body);
            $pubKey = openssl_pkey_get_public(file_get_contents($headers['PAYPAL-CERT-URL']));
            $details = openssl_pkey_get_details($pubKey);
            $verifyResult = openssl_verify($sigString, base64_decode($headers['PAYPAL-TRANSMISSION-SIG']), $details['key'], 'sha256WithRSAEncryption');
        }
        if ($verifyResult === 0) {
            throw new Exception('signature incorrect');
        } elseif ($verifyResult === -1) {
            throw new Exception('error checking signature');
        }
        return true;
    }

    /**
    public function charge($channel_id, $params = array('subscription_id' => 0, 'note' => '', 'amount' => 0, 'currency' => ''))
    {
    $gateway = $this->getGateway($channel_id);
    $request = new SubscriptionsCaptureRequest($params['subscription_id']);
    $request->setData(array(
    'note' => $params['note'],
    'capture_type' => 'OUTSTANDING_BALANCE',
    'amount' => [
    'currency_code' => $params['currency'],
    'value' => $params['amount'],
    ],
    ));
    try {
    $gateway->execute($request);
    $trade_status = TRADE_STATUS['SUBSCRIPTION_CHARGE_SUCCESS'];
    } catch (HttpException $e) {
    $trade_status = TRADE_STATUS['SUBSCRIPTION_CHARGE_FAILED'];
    }
    BillService::UpdateTrade(array(
    'data' => array('status' => $trade_status),
    'where' => array(
    'api_subscription_id' => $params['subscription_id'],
    'channel_id' => $channel_id,
    ),
    ));
    NotifyService::Notify(NotifyService::GetNotifyParams(array(
    'where' => array(
    'api_subscription_id' => $params['subscription_id'],
    'channel_id' => $channel_id,
    ),
    )));
    }*/
}
