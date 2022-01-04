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
use PayPalSubscriptionsSdk\Subscriptions\SubscriptionsCreateRequest;
use service\DbService;
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
                    'status' => $params['status'],
                    'description' => $params['description'],
                    'billing_cycles' => array(
                        'frequency' => array(
                            'interval_unit' => $params['interval_unit'],
                            'interval_count' => $params['interval_count'],
                        ),
                        'pricing_scheme' => array(
                            'fixed_price' => array(
                                'value' => $params['amount'],
                                'currency_code' => $params['currency'],
                            ),
                        ),
                    ),
                    'payment_preferences' => array(
                        'auto_bill_outstanding' => !!$params['auto_renew'],
                    ),
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
                DbService::Update('trade', array(
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
        $products = $params['products'];
        $plans = array();
        $customers = array();
        $products = array_map(function ($product) use (&$plans, &$customers) {
            $plans[] = $this->getParamsByType($product['plan'], 'plan');
            $customers[] = $this->getParamsByType($product['customer'], 'customer');
            unset($product['plan']);
            unset($product['customer']);
            return $this->getParamsByType($product, 'product');
        }, $products);
        $res_products = $this->createProducts($gateway, $products);
        $plans = array_map(function ($res_product, $plan) {
            $plan['product_id'] = $res_product->result->id;
            return $plan;
        }, $res_products, $plans);
        var_dump('plans', $plans);
        exit;
        $res_plans = $this->createPlans($gateway, $plans);
        var_dump('res_plans', $res_plans);
        $subscriptions = array();
        array_map(function ($res_plan, $customer) use (&$subscriptions) {
            $subscriptions[] = array(
                'plan_id' => $res_plan->result->id,
                'subscriber' => $customer,
            );
        }, $res_plans, $customers);
        $this->createSubscriptions($gateway, $subscriptions);
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

    public function createProducts($gateway, $products)
    {
        $res = array();
        foreach ($products as $product) {
            $request = new ProductsCreateRequest();
            $request->setData($product);
            try {
                $res[] = $gateway->execute($request);
            } catch (HttpException $e) {
                $res[] = array(
                    'statusCode' => $e->statusCode,
                    'message' => $e->getMessage(),
                );
            }
        }
        return $res;
    }

    public function createPlans($gateway, $plans)
    {
        $res = array();
        foreach ($plans as $plan) {
            $request = new PlansCreateRequest();
            $request->setData($plan);
            try {
                $res[] = $gateway->execute($request);
            } catch (HttpException $e) {
                $res[] = array(
                    'statusCode' => $e->statusCode,
                    'message' => $e->getMessage(),
                );
            }
        }
        return $res;
    }

    public function createSubscriptions($gateway, $subscriptions)
    {
        $res = array();
        foreach ($subscriptions as $subscription) {
            $request = new SubscriptionsCreateRequest();
            $request->setData($subscription);
            try {
                $res[] = $gateway->execute($request);
            } catch (HttpException $e) {
                $res[] = array(
                    'statusCode' => $e->statusCode,
                    'message' => $e->getMessage(),
                );
            }
        }
        return $res;
    }
}
