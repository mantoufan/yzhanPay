<?php
namespace controller;

use service\AuthService;
use service\DbService;

class Checkout extends Common
{
	public function Sumbit() {
		$params = getPosts();
		$_return_url = $params['return_url'];
		$_notify_url = $params['notify_url'];
		$gateway = Omnipay::create('Alipay_AopPage');
		$gateway->setSignType('RSA2'); // RSA/RSA2/MD5. Use certificate mode must set RSA2
		$gateway->setAppId('the_app_id');
		$gateway->setPrivateKey('the_app_private_key');
		$gateway->setAlipayPublicKey('the_alipay_public_key'); // Need not set this when used certificate mode
		$gateway->setReturnUrl($_return_url);
		$gateway->setNotifyUrl($_notify_url);
		
		/**
		 * @var AopTradePagePayResponse $response
		 */
		$response = $gateway->purchase()->setBizContent([
				'subject'      => 'test',
				'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
				'total_amount' => '0.01',
				'product_code' => 'FAST_INSTANT_TRADE_PAY',
		])->send();
		
		$url = $response->getRedirectUrl();
	}
}
?>