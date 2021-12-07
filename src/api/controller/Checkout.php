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
		$str = 'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCAWLu7eVjz3Q9GoJIOSfJqTtOwMUX+bZHrv7wjP+naxq6UufuLWd8NGVFdLEyFR5c3xVcW42sY5fHLqS6CHeOY2qSWhkPKi87PVVb5xbmK/oicEiHDXwsdfc7+5m+/frc+xOMPux+fyr/74EXBpM+4dMdThUEDIq4oqWmsn3vSHe9EbdnPtSuXD0I8HyPxRz58Uso5Yl+TC8bitkeCvhnLSkyhoDhgmVkp4cjnbW61A+LUEedWro7VMIu+2OuI4BtWjwBcORzNLyFLn5jBN400XJwEMJORfY5nmTKFi0rLMJVP1RWXw8iHv5V/DL8JBNwSYr9sVj3KYsbpQ5E3p9bhAgMBAAECggEACuZ+Wze9JEj8CSWOAgSpv/md5PLqXOd1Yy5PjjbZZ6lEHoGFKZqiZPxneqBOh2tDHot2EA2UhPLSjFd8CbT8JXk8TURt8X/aOqWm02PFlFZ1x7uKfotN6F1M/T0Y9IyQh5Y1Kprb3rhbgcrUYdPbiHDylNdWZCvH2tA4l16cJ4Ygaw+2rJVkHuzCZz8VChe7yCvPWcS7i5aBz4YwIjphEVrpydhQlitWEWlFL4oXIqMaEG0yVKKtCjC4QLH1nMB+SOOLIvNOTYBprejoYVM87/QXWMbyJbOX53jY1wQa/2xuK1ZCouBFf6oRDSXVD7w3HG+Gr/aCWl+/tqJJDfvgAQKBgQDFct7eCgd6tLrc/Dd1dnDTO7X3g1VbKCKgrwGzrbUHodoH3JKMWT5MwoJr9n7+ptDAFaQ+/vRC1UDxlNv9YZSfbXp/edVsw8t2mPwq5lUsfRrSPLYpoHEs6UxODZWkatXoDbhsgMAyOw2knjrQWmQ84H6pzWBWmbGJtZtlIYcBYQKBgQCmaAsWERrY4koIKJBfaK4586NgTHIkgaEPHJ3fVZwZQdDk+fDzwaINMoKom/f5LLqCJ/i1C2yVLn4R3biR25WrfwbjYTgewICuLbCpc7E+oDaBh4ihGqNxFQhZBcKYsSUotzEVNuRLVUUWWmLk848a+sLq3tYnLC4nSo/PWeFFgQKBgHnMOVSIpUJ5OAfXgbJwxHpZDA/JsR6RLIMoUYlv7wrtOVy+IJx49KhPGDrXDFGzv3OuJepCRZTwjaY4aFfuGMsbsoPuOMxmHx1ik7M28HWIGsJzdv9InGfS5iID2TpaOOdzhz9PUL/rk6fnf2pFSC4RYbEHIpVpK45CO8BvpSMhAoGARVVQWS9jSj5urhuIm9gXz5mN1s/DNyaznoJD3QvkcDmV+fGRzV4+UNVczze9CBr00soou/Y4lae7a2JARrWBFOVmT1LweQ+oDeqHkvLbRMaoLyvzZ3yb4L/srHrT657TZrV9Q+ONFz49/ORIFDDOzWTx1b5m6Adma4SLis9eJwECgYBOiMqMGXjkf6+tvHtcc2GHVeWEfuq4hK8BUpqPVN7bdt7eR65CMpEo8kwvvKFORbk9ETUZafp1LeVrbH0ok0oLfhxJBLqOwY4B2nHxoz19hhNM4Dev9FCuKd77nTkvUqOQdtHcXiSwY8X9sExcq7xBWgxiZIZKNPDcZtqZLWZgUg==';
		$str = wordwrap($str, 64, "\n", true);
		$private_key = "-----BEGIN RSA PRIVATE KEY-----\n$str-----END RSA PRIVATE KEY-----\n";
		$gateway->setPrivateKey($private_key);
		$str = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiXeQ5YZylA2hfSkio+XYBbuUO+/76yepjMdZdn3/FhgRIKF2LkQiUAqObcMj/OZq4yyDbCwrN2cddoc57LLxKc9B/2maiCaVIcOP/1fcHq/Nrmc6K+shdZ1eMQpZS+JzOLam+ae9/p7FZPSaa2aUzsOMPm8aDZV1zL2TTmeuDLfg3lNqyWPpFxy5V+lmusnpn6GoH4io6nyauDwQs1pGdA3kuFh0+xKp68MJcl9awfH8QsYG0hXAe3VPcp9IB7oI560aBaIsRQP+getuxnTGwTJChS0Y4i8m36JB0YAJIjHs/3dKxElwkUYlXPgNHYnMeK+vPIYRyfhRE5yc1NkwpwIDAQAB';
		$str = wordwrap($str, 64, "\n", true);
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