<?php
namespace controller;

use Omnipay\Omnipay;
use service\AuthService;
use service\DbService;

class Checkout extends Common
{
	public function Sumbit() {
		$params = getPosts();
		$_subject = $params['subject'];
		$_body = $params['body'];
		$_timestamp = $params['timestamp'];
		$_out_trade_no = $params['out_trade_no'];
		$_total_amount = $params['total_amount'];
		$_return_url = $params['return_url'];
		$_notify_url = $params['notify_url'];

		$gateway = Omnipay::create('Alipay_AopPage');
		$gateway->setSignType('RSA2'); // RSA/RSA2/MD5. Use certificate mode must set RSA2
		$gateway->setAppId('2021003100645552');
		$str = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqys4gr8PcpsX6eErThms0Vwqfu3YqRuiKE+J9wVqfO45z0S4U/R6FbMByeUoUOUIiaxd0oOU3ZrDcPIwKJF/j/7bYOB0Uc/Nrma1a6rkfIVZZVjZC7bjd1ruURS+TVGtmHLtx9bGwsojhjqL9z5Wd0dhIWtiBz2IfT7+nyZBSUVgGOyhf8MvAAyLvSmMR3xsguho/GNlo2BypuNPZYdkv4CuC/FWPNjzLIuwuEUBO1vc1v4whju27EiSubBkzS1Pol9ntlUq/ejuXaI0uW/z50iTm8dhbfCPRDEVqcycLnAVvSXi5hsq/M9RUOASj5xze2WXAv4jlNWXCrfQNCv7kQIDAQAB';
		$str = chunk_split($str, 64, "\n");
		$private_key = "-----BEGIN RSA PRIVATE KEY-----\n$str-----END RSA PRIVATE KEY-----\n";
		$gateway->setPrivateKey($private_key);
		$str = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiXeQ5YZylA2hfSkio+XYBbuUO+/76yepjMdZdn3/FhgRIKF2LkQiUAqObcMj/OZq4yyDbCwrN2cddoc57LLxKc9B/2maiCaVIcOP/1fcHq/Nrmc6K+shdZ1eMQpZS+JzOLam+ae9/p7FZPSaa2aUzsOMPm8aDZV1zL2TTmeuDLfg3lNqyWPpFxy5V+lmusnpn6GoH4io6nyauDwQs1pGdA3kuFh0+xKp68MJcl9awfH8QsYG0hXAe3VPcp9IB7oI560aBaIsRQP+getuxnTGwTJChS0Y4i8m36JB0YAJIjHs/3dKxElwkUYlXPgNHYnMeK+vPIYRyfhRE5yc1NkwpwIDAQAB';
		$str = chunk_split($str, 64, "\n");
		$public_key = "-----BEGIN PUBLIC KEY-----\n$str-----END PUBLIC KEY-----\n";
		$gateway->setAlipayPublicKey($public_key); // Need not set this when used certificate mode
		$gateway->setReturnUrl($_return_url);
		$gateway->setNotifyUrl($_notify_url);
		
		/**
		 * @var AopTradePagePayResponse $response
		 */
		$response = $gateway->purchase()->setBizContent([
				'subject'      => $_subject,
				'out_trade_no' => $_out_trade_no,
				'total_amount' => $_total_amount,
				'body' => $_body,
				'product_code' => 'FAST_INSTANT_TRADE_PAY'
		])->send();
		
		$url = $response->getRedirectUrl();
	}
}
?>