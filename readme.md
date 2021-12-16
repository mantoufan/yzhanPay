# Brief

This is a payment gateway project, different payment methods will be provided to the application in a unified interface

# Structure

## api Directory

All background related interfaces will be provided through the ap

## demo-web Directory

All front-end registration and login pages will be placed here

# Demo

https://www.loom.com/share/dea0ace38fe04bb98893a1b92f7637d4

# Run

```shell
npm start
```

# API Doc

## Checkout API

##### Brief description

- Checkout Gateway

##### Request URL

- ` /checkout?app_id=2021122412586601&out_trade_no=2015032001016669&subject=title&total_amount=0.01&return_url=http://www.baidu.com&notify_url=http://www.google.com&timestamp=2021-12-26 22:03:60&body=description&sign=44b125501d3833a7c293a03fe0f5ce3c`

##### Method

- GET

##### Parameter

| Parameter name | Required | Type   | Max | Explain                                                                                                                                    | Example                                                |
| :------------- | :------- | :----- | --- | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------ |
| app_id         | Yes      | string | 32  | Gateway assigns the app's ID                                                                                                               | JS Web is 2021122412586601                             |
| out_trade_no   | Yes      | string | 64  | 1. Unique in app <br>2. Will be returned to `notify_url` `return_url`                                                                      | 2021120914015695184890                                 |
| subject        | Yes      | string | 256 | Title                                                                                                                                      | Amazon Plus / year                                     |
| total_amount   | Yes      | price  | 9   | [0.01, 100000000]                                                                                                                          | 88.88                                                  |
| return_url     | Yes      | string | 256 | Sync callback address                                                                                                                      |                                                        |
| notify_url     | Yes      | string | 256 | Asynchronous callback address                                                                                                              |                                                        |
| timestamp      | Yes      | string | 19  | yyyy-MM-dd HH:mm:ss                                                                                                                        | 2021-12-26 22:03:60                                    |
| sign           | Yes      | string | 344 | See remark below for details                                                                                                               |                                                        |
| body           | No       | string | 128 | 1. Will be displayed as a transaction description in the merchant's and user's pc billing details<br>2. Will be returned in the notify_url | 1 year membership package with plug-in and web support |

##### Return example

![](http://doc.os120.com/server/index.php?s=/api/attachment/visitFile/sign/dc89276df968596c7c360fb8262f8b29)

##### Remark

How to create a sign：

1. Arrange `keyName` of all parameters (Except for `sign` ) in dictionary order, build a string
2. Put `secret_key` (JS Web is `bc3a4d13e427ee95f24cb65f24501208a6e0d8be`) at the end
3. Generate `md5` value as sign

```php
public static function AuthSign($params, $secret_key = 'bc3a4d13e427ee95f24cb65f24501208a6e0d8be'){
  $params = array_filter($params);
  ksort($params);
  return md5(urldecode(http_build_query($params)) . $secret_key);
}
```

## Sign Check API

##### Brief description

- Sign Check API

##### Request URL

- `/api/auth/sign?app_id=1&out_trade_no=2015032001016669&subject=title&total_amount=0.01&return_url=http://www.baidu.com¬ify_url=http://www.google.com×tamp=2021-12-26 22:03:60&body=description&sign=44b125501d3833a7c293a03f`

##### Method

- GET

##### Parameter

| Parameter name | Required | Type   | Max | Explain                                                                                                                                    | Example                                                |
| :------------- | :------- | :----- | --- | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------ |
| app_id         | Yes      | string | 32  | Gateway assigns the app's ID                                                                                                               | JS Web is 2021122412586601                             |
| out_trade_no   | Yes      | string | 64  | 1. Unique in app <br>2. Will be returned to `notify_url` `return_url`                                                                      | 2021120914015695184890                                 |
| subject        | Yes      | string | 256 | Title                                                                                                                                      | Amazon Plus / year                                     |
| total_amount   | Yes      | price  | 9   | [0.01, 100000000]                                                                                                                          | 88.88                                                  |
| return_url     | Yes      | string | 256 | Sync callback address                                                                                                                      |                                                        |
| notify_url     | Yes      | string | 256 | Asynchronous callback address                                                                                                              |                                                        |
| timestamp      | Yes      | string | 19  | yyyy-MM-dd HH:mm:ss                                                                                                                        | 2021-12-26 22:03:60                                    |
| sign           | Yes      | string | 344 | See remark below for details                                                                                                               |                                                        |
| body           | No       | string | 128 | 1. Will be displayed as a transaction description in the merchant's and user's pc billing details<br>2. Will be returned in the notify_url | 1 year membership package with plug-in and web support |

##### Return example

```
{
  "sign":"44b125501d3833a7c293a03fe0f5ce3c", // You send
  "trueSign":"44b125501d3833a7c293a03fe0f5ce3c", // System generate
  "isMatch":true // Is two sign match
}
```

##### Return parameter description

| Parameter name | Type    | Explain              |
| :------------- | :------ | -------------------- |
| sign           | string  | User send sign       |
| trueSign       | string  | System generate sign |
| isMatch        | boolean | Is two sign match    |

## Return Sync Notify

##### Brief description

- Return Sync Notify API
- After the payment is completed, the user will jump immediately

##### Request URL

- `{return_url}?app_id=1&trade_no=&out_trade_no=2015032001016669&subject=title&total_amount=0.01&pay_channel=AlipayChinaYM&sign=&body=description`

##### Method

- GET

##### Return parameter description

| Parameter name | Type   | Explain                 | Example                                  |
| :------------- | :----- | :---------------------- | ---------------------------------------- |
| app_id         | string | APP ID                  | JS Web is 2021122412586601               |
| trade_no       | string | Trade No                | Created from China pay gateway           |
| out_trade_no   | string | Out Trade No            | Transfered from Application              |
| subject        | string | Subject                 | Title                                    |
| total_amount   | price  | Total Amount            | Total Price                              |
| pay_channel    | string | Name of payment channel | Alipay JS (Defined in the open platform) |
|                |        |                         |

## Bill List

##### Brief description

- Bill List API

##### Request URL

- ` /api/open/bill/list?app_id=2021122412586601&trade_no=2021120914015695184890,2021120914034363633587&sign=sign`

##### Method

- POST, GET

##### Parameter

| Parameter name | Required | Type   | Explain                   |
| :------------- | :------- | :----- | ------------------------- |
| app_id         | Yes      | string | APP ID                    |
| trade_no       | Yes      | string | Trade No (split by comma) |
| sign           | Yes      | string | Sign (md5)                |

##### Return example

```
{
 "code": 200,
 "msg": "success",
 "data": [
   {
     "id": "1",
     "trade_no": "2021120914015695184890",
     "out_trade_no": "",
     "api_trade_no": null,
     "status": "WAIT_BUYER_PAY",
     "user_id": "1",
     "app_id": "2021122412586601",
     "subject": "",
     "total_amount": "828.00",
     "timestamp": "0000-00-00 00:00:00",
     "return_url": "",
     "notify_url": "",
     "body": null,
     "notify_status": "0",
     "notify_time": null
   },
   {
     "id": "2",
     "trade_no": "2021120914034363633587",
     "out_trade_no": "",
     "api_trade_no": null,
     "status": "WAIT_BUYER_PAY",
     "user_id": "1",
     "app_id": "2021122412586601",
     "subject": "",
     "total_amount": "828.00",
     "timestamp": "0000-00-00 00:00:00",
     "return_url": "",
     "notify_url": "",
     "body": null,
     "notify_status": "0",
     "notify_time": null
   }
 ]
}

```

##### Return parameter description

| Parameter name | Type | Explain     |
| :------------- | :--- | ----------- |
| code           | int  | Status Code |
| msg            | int  | Message     |
| data           | int  | Array List  |
