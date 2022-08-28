<?php
namespace plugins\alipayglobal;

use common\base\Common;
use Mantoufan\model\CustomerBelongsTo;
use Mantoufan\model\GrantType;
use Mantoufan\model\ScopeType;
use Mantoufan\model\TerminalType;
use Mantoufan\tool\IdTool;
use service\plugin\pay\BillService;
use service\plugin\pay\NotifyService;
use service\plugin\pay\TokenService;
use service\plugin\PluginService;

class Alipayglobal extends Common
{
    public function getGateway($channel_id)
    {
        $config = PluginService::GetChannelConfig($channel_id);
        if (empty($config)) {
            $this->export(array('status' => 403));
        }
        $gateway = new \Mantoufan\AliPayGlobal(array(
            'client_id' => $config['app_id'],
            'endpoint_area' => 'ASIA',
            'merchantPrivateKey' => $config['private_key'],
            'alipayPublicKey' => $config['public_key'],
            'is_sandbox' => $config['env'] === 'sandbox',
        ));
        return $gateway;
    }

    public function checkout($channel_id, $params)
    {
        $gateway = $this->getGateway($channel_id);
        $result = $gateway->payCashier(array(
            'customer_belongs_to' => CustomerBelongsTo::ALIPAY_CN, // *
            'notify_url' => PluginService::GetNotifyUrl('alipay', $channel_id),
            'return_url' => $this->getReturnUrl($params['trade_no']),
            'amount' => array(
                'currency' => $params['currency'],
                'value' => $params['total_amount'] * 100,
            ),
            'order' => array(
                'id' => $params['trade_no'],
                'desc' => $params['body'],
                'extend_info' => array(
                    'china_extra_trans_info' => array(
                        'business_type' => 'MEMBERSHIP',
                    ),
                ),
            ),
            'payment_request_id' => $params['trade_no'],
            'settlement_strategy' => array(
                'currency' => 'USD',
            ),
            'terminal_type' => TerminalType::WEB, // *
            'os_type' => null,
        ));

        $this->export(array(
            'status' => 302,
            'header' => array(
                'Location' => $result->normalUrl,
            ),
        ));
    }

    private function getReturnUrl($trade_no)
    {
        $params = getParams();
        $return_url = NotifyService::GetReturnUrl(NotifyService::GetNotifyParams(array(
            'where' => array(
                'trade_no' => $trade_no,
            ),
        )));
        return $return_url;
    }

    public function async($channel_id)
    {
        $gateway = $this->getGateway($channel_id);
        $params = getParams();
        $notify = $gateway->getNotify();
        $rsqBody = $notify->getRsqBody();
        if ($rsqBody->notifyType === 'PAYMENT_RESULT') {
            $params['out_trade_no'] = $rsqBody->result->paymentRequestId;
            $params['trade_no'] = $rsqBody->result->paymentId;
            if ($rsqBody->result->resultStatus === 'S') {
                $params['trade_status'] = TRADE_STATUS['CHECKOUT_SUCCEED'];
                ob_start();
                $notify->sendNotifyResponseWithRSA();
                $body = ob_get_contents();
                ob_end_clean();
            } else {
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
            $app_id = NotifyService::NotifyReturnAppId(
                NotifyService::GetNotifyParams(array(
                    'where' => array(
                        'trade_no' => $params['out_trade_no'],
                    ),
                ))
            );
        } else {
            $body = 'Other NotifyType: ' . $rsqBody->notifyType;
            $app_id = null;
        }

        $this->export(array(
            'body' => $body,
            'app_id' => $app_id,
        ));
    }

    private function updateTokenWithResult($result, $where)
    {
        $access_token = $result->accessToken;
        $access_token_expiry_time = $result->accessTokenExpiryTime;
        $refresh_token = $result->refreshToken;
        $refresh_token_expiry_time = $result->refreshTokenExpiryTime;
        TokenService::Update(array(
            'data' => array(
                'access_token' => $access_token,
                'access_token_expiry_time' => $access_token_expiry_time,
                'refresh_token' => $refresh_token,
                'refresh_token_expiry_time' => $refresh_token_expiry_time,
            ),
            'where' => $where,
        ));
    }

    private function updateAccessToken($channel_id, $refresh_token)
    {
        $gateway = $this->getGateway($channel_id);
        $result = $gateway->authApplyToken(array(
            'grant_type' => GrantType::REFRESH_TOKEN,
            'customer_belongs_to' => CustomerBelongsTo::ALIPAY_CN,
            'auth_code' => null,
            'refresh_token' => $refresh_token,
        ));
        $this->updateTokenWithResult($result, array(
            'channel_id' => $channel_id,
            'refresh_token' => $refresh_token,
        ));
        return $result->accessToken;
    }

    private function consult($channel_id, $customer_id, $trade_no)
    {
        $gateway = $this->getGateway($channel_id);
        $auth_state = IdTool::CreateAuthState();
        $data = TokenService::Get(array(
            'where' => array(
                'channel_id' => $channel_id,
                'customer_id' => $customer_id,
            ),
        ));
        if ($data['access_token']) {
            TokenService::Update(array(
                'data' => array(
                    'trade_no' => $trade_no,
                    'auth_state' => $auth_state,
                ),
                'where' => array(
                    'channel_id' => $channel_id,
                    'customer_id' => $customer_id,
                ),
            ));
        } else {
            TokenService::Create(array(
                'channel_id' => $channel_id,
                'customer_id' => $customer_id,
                'trade_no' => $trade_no,
                'auth_state' => $auth_state,
            ));
        }
        $result = $gateway->authConsult(array(
            'customer_belongs_to' => CustomerBelongsTo::ALIPAY_CN, // *
            'auth_client_id' => null,
            'auth_redirect_url' => PluginService::GetReturnUrl('alipayglobal', $channel_id, true), // *
            'scopes' => array(ScopeType::AGREEMENT_PAY), // *
            'auth_state' => $auth_state, // *
            'terminal_type' => TerminalType::WEB, // *
            'os_type' => null,
        ));
        header('Location: ' . $result->normalUrl);
    }

    private function getAccessToken($channel_id, $customer_id)
    {
        $data = TokenService::Get(array(
            'where' => array(
                'channel_id' => $channel_id,
                'customer_id' => $customer_id,
            ),
        ));
        $access_token = '';
        if (!empty($data['refresh_token'])) {
            $access_token = $this->updateAccessToken($channel_id, $data['refresh_token']);
        } else if (!empty($data['access_token'])) {
            $access_token = $data['access_token'];
        }
        return $access_token;
    }

    private function createSubscription($trade_no)
    {
        $where = array('trade_no' => $trade_no);
        BillService::UpdateTrade(array(
            'data' => array(
                'status' => TRADE_STATUS['SUBSCRIPTION_WAIT_REMIND'],
                'subscription_start_time' => date('Y-m-d H:i:s'),
            ),
            'where' => $where,
        ));
        $return_url = NotifyService::GetReturnUrl(NotifyService::GetNotifyParams(array(
            'where' => $where,
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

    public function subscribe($channel_id, $params)
    {
        $customer_id = $params['customer']['id'];
        $access_token = $this->getAccessToken($channel_id, $customer_id);
        if ($access_token) {
            $this->createSubscription($params['trade_no']);
        } else {
            $this->consult($channel_id, $customer_id, $params['trade_no']);
        }
    }

    public function syncSubscribe($channel_id)
    {
        $gateway = $this->getGateway($channel_id);
        $params = getGets();
        $auth_code = $_GET['authCode'] ?? '';
        $auth_state = $_GET['authState'] ?? '';
        $result = $gateway->authApplyToken(array(
            'grant_type' => GrantType::AUTHORIZATION_CODE,
            'customer_belongs_to' => CustomerBelongsTo::ALIPAY_CN,
            'auth_code' => $auth_code,
            'refresh_token' => null,
        ));
        $this->updateTokenWithResult($result, array(
            'channel_id' => $channel_id,
            'auth_state' => $auth_state,
        ));
        $trade_no = TokenService::Get(array(
            'field' => 'trade_no',
            'where' => array(
                'channel_id' => $channel_id,
                'auth_state' => $auth_state,
            ),
        ));
        $this->createSubscription($trade_no);
    }

    public function charge($channel_id, $params = array('trade_no' => 0, 'customer_id' => 0, 'subscription_id' => 0, 'note' => '', 'amount' => 0, 'currency' => ''))
    {
        $gateway = $this->getGateway($channel_id);
        $customer_id = $params['customer_id'];
        $data = TokenService::Get(array(
            'where' => array(
                'channel_id' => $channel_id,
                'customer_id' => $customer_id,
            ),
        ));
        $trade_status = TRADE_STATUS['SUBSCRIPTION_CHARGE_FAILED'];
        $access_token = $this->getAccessToken($channel_id, $customer_id);
        if ($access_token) {
            $result = $gateway->payAgreement(array(
                'notify_url' => setQueryParams($currentUrl, array('type' => 'notify')),
                'return_url' => setQueryParams($currentUrl, array('type' => 'return')),
                'amount' => array(
                    'currency' => 'USD',
                    'value' => '1',
                ),
                'order' => array(
                    'id' => null,
                    'desc' => 'Order Desc',
                    'extend_info' => array(
                        'china_extra_trans_info' => array(
                            'business_type' => 'MEMBERSHIP',
                        ),
                    ),
                ),
                'payment_request_id' => null,
                'payment_method' => array(
                    'payment_method_type' => CustomerBelongsTo::ALIPAY_CN, // *
                    'payment_method_id' => $access_token, // *
                ),
                'settlement_strategy' => array(
                    'currency' => 'USD',
                ),
                'terminal_type' => TerminalType::WEB, // *
            ));
            if ($result->result->resultStatus === 'S') {
                $params['trade_status'] = TRADE_STATUS['SUBSCRIPTION_CHARGE_SUCCEED'];
            }
        }
        $where = array(
            'trade_no' => $params['trade_no'],
        );
        BillService::UpdateTrade(array(
            'data' => array('status' => $trade_status),
            'where' => $where,
        ));
        NotifyService::Notify(NotifyService::GetNotifyParams(array(
            'where' => $where,
        )));
        $this->export(array(
            'status' => 200,
            'body' => 'trade_status: ' . $params['trade_status'] . ';access_token: ' . $access_token,
        ));
    }
}
