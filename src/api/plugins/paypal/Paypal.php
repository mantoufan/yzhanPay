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
use service\plugin\pay\CheckoutService;
use service\plugin\PluginService;
use service\PlanService;

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
                    'name' => array('given_name' => $params['first_name'], 'surname' => $params['last_name']),
                    'email_address' => $params['email'],
                    'phone' => $params['phone'],
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
                TradeService::Update(array(
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
        $subscription = array(
            'plan_id' => $res_plan->result->id,
            'subscriber' => $customer,
            'application_context' => array(
                'cancel_url' => PluginService::GetCancelUrl('paypal', $channel_id),
                'return_url' => PluginService::GetReturnUrl('paypal', $channel_id, true),
            ),
        );
        $response = $this->createSubscription($gateway, $subscription);
        if ($response->statusCode === 201) {
            $api_subscription_id = $response->result->id;
            $api_plan_id = $response->result->plan_id;
            $api_customer_id = $response->result->subscriber->payer_id;
            TradeService::Update(array(
                'data' => array('api_subscription_id' => $api_subscription_id, 'api_plan_id' => $api_plan_id, 'api_customer_id' => $api_customer_id),
                'where' => array('trade_no' => $params['trade_no'], 'app_id' => $params['app_id']),
            ));
            $link = arrayFind($res_subscription->result->links, function ($link) {
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

    public function sync($channel_id)
    {
        $gateway = $this->getGateway($channel_id);
        $params = getGets();
        $_token = $params['token'];
        $_PayerID = $params['PayerID'];
        unset($params['token']);
        unset($params['PayerID']);
        $request = new OrdersCaptureRequest($_token);
        $request->prefer('return=representation');
        try {
            $response = $gateway->execute($request);
            if ($response->result->status === 'COMPLETED') {
                $params['trade_status'] = TRADE_STATUS['TRADE_SUCCESS'];
                $body = '';
            } else {
                $params['trade_status'] = TRADE_STATUS['TRADE_CLOSED'];
                $body = $response->result;
            }
        } catch (HttpException $e) {
            $params['trade_status'] = TRADE_STATUS['TRADE_CLOSED'];
            $body = $e->getMessage();
        }
        $params['api_trade_no'] = $_token;
        $params['api_customer_id'] = $_PayerID;
        CheckoutService::getNotifyParams($channel_id, $params);
        $return_url = CheckoutService::GetReturnUrl($channel_id, $params);
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
        $_token = $params['token'];
        TradeService::Update(array(
            'data' => array('api_trade_no' => $_token),
            'where' => array('api_subscription_id' => $_subscription_id, 'channel_id' => $channel_id),
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

    public function captureSubscription($gateway, $subscription_id, $plan_id)
    {
        PlanService::GetById($plan_id);
        $extraCharge = array(
            'note' => 'Charging as the balance reached the limit',
            'capture_type' => 'OUTSTANDING_BALANCE',
            'amount' => [
                'currency_code' => 'USD',
                'value' => '100',
            ],
        );

        $request = new SubscriptionsCaptureRequest($subscription_id);
        $request->setData($extraCharge);

        SubscriptionsCaptureRequest();
    }
}
