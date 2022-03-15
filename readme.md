# Introduction
Simple and efficient multi-user, multi-language converged payment system. Provides cash payment and Auto Debit via one URL, supports Alipay China, Alipay Global and Paypal, etc., it can be extended through plug-ins. Admin Panel can view logs, transaction flow, configuration.  

简洁、高效的多用户、多语言的聚合支付系统。 通过一个 URL 提供现金付款和订阅支付（Auto Debit），支持支付宝中国，支付宝国际版和 Paypal 等，可通过插件扩展。Admin panel 可查看日志、交易流水，配置。

Suitable for cashier, multi-channel collection, multi-channel settlement, four-party payment, payment load balancing and other scenarios.

适合收银台、多渠道收款、多渠道结算、四方支付、支付负载均衡等场景。
## What is Converged Payment System
![What is Converged payment system](https://s2.loli.net/2022/03/15/uO5fxFjhVrJstLc.jpg)

# License Agreement
The system shall not be used for any purposes prohibited by laws of mainland China, Hong Kong or the United States.   
The system shall not be used for commercial purposes without permission.


本系统不得用于中国大陆、香港、美国法律所禁止的用途。  
未经许可，不得用于商业用途。

# Demo
## Checkout Page
[Checkout Page](https://p.yzhan.co/checkout?app_id=2021122412586601&request_time=2021-12-15%2002:38:44&notify_url=https://p.yzhan.co/api/test/notify-url&cancel_url=https://p.yzhan.co/api/test/cancel-url&return_url=https://p.yzhan.co/api/test/return-url&product={%22name%22:%22Product%20Name%22,%22description%22:%22Product%20Description%22,%22type%22:%22DIGITAL%22,%22category%22:%22AC_REFRIGERATION_REPAIR%22,%22image_url%22:%22https://image.com/url%22,%22url%22:%22https://url.com/url%22,%22status%22:%22ACTIVE%22,%22list%22:[{%22name%22:%22Prodcut%20Name%201%22,%22description%22:%22Product%20Description%201%22},{%22name%22:%22Prodcut%20Name%202%22,%22description%22:%22Product%20Description%202%22}]}&channel={%22env%22:%22sandbox%22,%22ability%22:%22%22}&out_trade_no=20211215023844979652579806&total_amount=0.01&currency=USD&plan={%22name%22:%22Plan%20Name%22,%22description%22:%22Plan%20Description%22,%22status%22:%22ACTIVE%22,%22billing_cycles%22:[{%22frequency%22:{%22interval_unit%22:%22day%22,%22interval_count%22:1},%22pricing_scheme%22:{%22fixed_price%22:{%22value%22:%220.01%22,%22currency_code%22:%22USD%22}},%22tenure_type%22:%22REGULAR%22,%22sequence%22:1,%22total_cycles%22:0}],%22payment_preferences%22:{%22auto_bill_outstanding%22:true}}&customer={%22name%22:{%22first_name%22:%20%22Chao%22,%20%22last_name%22:%20%22Wang%22},%22description%22:%22Customer%20Description%22,%22email%22:%22m126@126.com%22}&sign=56b7c590b414f03e33fade6df14d02e7)  

### ScreenShoot
- PC
![Checkout Page PC Screenshoot](https://s2.loli.net/2022/03/15/RMhjyimEkq9eF7X.jpg)  
- Mobile
![Checkout Page Mobile Screenshoot](https://s2.loli.net/2022/03/15/9RlQKr7bgEVYWB2.jpg)  

## Admin Panel
[Admin](https://p.yzhan.co/login)  
Account: `admin`  
Password: `yzhan`  

### ScreenShoot
![Admin Panel](https://s2.loli.net/2022/03/15/S7bHNIvwBslrK1f.jpg)  


# Install
## Step 1 Import SQL 
```shell
source install.sql
```

## Step 2 Update Your Channel Config
-  Get your `app_id` `client_id` `secret` `key` from Alipay China / Alipay Global / Paypal
- Find `yzhan_channel`, update `config` field

## Step 3 Install Dependency
```shell
# Front-End: Enter Project Root Directory / 
npm install
# Back-End: Eneter /src/api/
composer require
```

## Step 4 Add scheduled task
Add the URL below to the scheduled task
```
{Your Website Url}/api/run/{Maximum number of tasks per run}
```
Examples of setting scheduled tasks:  
- Use BT panel  
![Run task on BT panel](https://s2.loli.net/2022/03/15/rviHMXOZpb4VnKJ.jpg)
- Use Linux crontab
```shell
crontab -e # Edit Crontab
*/1 * * * * wget {Your Website Url}/api/run/{Maximum number of tasks per run} # wget / curl URL every 1 minute
```
# Document
## How to Use
Cash payment and Subscription payment are combined into one API:  
[API Document](https://documenter.getpostman.com/view/8955262/UVsJy7kC)  

## How to add a new Plugin
### Step 1 Create a Plugin in Admin Panel
![Create a Plugin in Admin Panel](https://s2.loli.net/2022/03/15/rRXbAvdjacJpGti.jpg)  
### Step 2 Create a Json to describe the Plug-in  
```json
// JSON doesn't support comments. 
// Please delete comments when copying
{
	"name": "paypal", // Plugin Name
	"version": "1.0.0", // Plugin Version
	"displayname": "Paypal", // Plugin Display Name
	"type": "pay", // Plugin Type, Optional: pay / theme / language
	"payment": "depositcard,paypal", // Payment provided by the plug-in to the user
	"ability": "checkout,subscription", // Payment ability provided by plugin
	"author": "mantoufan", // Plugin author
	"link": "https://github.com/mantoufan", // Plugin Website URL
	"input": [ // Configurable items provided by plugin, it can be configured in Admin Panel
		{ "type":"text", "label":"Client ID", "name":"client_id" },
		{ "type":"text", "label":"Secret", "name":"secret" },
		{ "type":"checkout", "label":"Sandbox", "name":"is_sandbox" }
	],
	"composer": {
		"require": { // Dependent third-party components
			"paypal/paypal-checkout-sdk": "^1.0",
			"andreimosman/paypal-subscriptions-sdk": "^1.0"
		}
	}
}
```
### Step 3 Create a Plugin folder
- Save JSON as config.json
- Put it into new folder
- Rename folder name with plugin name
### Step 4 Implement abstract class
- Create a php named plugin name
- Implement abstract class in it
```php
abstract class MyPaymentPlugin {
  public function getGateway($channel_id) // Get the configuration and initialize the gateway
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
  abstract public function checkout($channel_id, $params); // Single Payment -> Pay Channel Cashier
  abstract public function sync($channel_id); // Process sync notifications received by checkout / subscribe
  abstract public function async($channel_id); // Process async notifications received by checkout / subscribe
  abstract public function cancel($channel_id); // Process user cancel, paypal only
  abstract public function subscribe($channel_id, $params); // Subscribe Payment -> Pay Channel Agreement or Auth Page
  abstract public function syncSubscribe($channel_id); // Process sync notifications received by subscribe
  abstract public function asyncSubscribe($channel_id); // Process async notifications received by subscribe
  abstract public function charge($channel_id, $params = array('trade_no' => 0, 'customer_id' => 0, 'subscription_id' => 0, 'note' => '', 'amount' => 0, 'currency' => ''));
  // Subscribe Payment -> Initiate the request to deduct the fee, Alipay Global Only
}
```
# Flow
## State Machine
### All Trade Status in System
```php
const TRADE_STATUS = array(
    'CREATED' => 'CREATED',
    'CHECKOUT_SUCCEED' => 'CHECKOUT_SUCCEED',
    'CHECKOUT_FAIL' => 'CHECKOUT_FAIL',
    'SUBSCRIPTION_WAIT_REMIND' => 'SUBSCRIPTION_WAIT_REMIND',
    'SUBSCRIPTION_WAIT_CHARGE' => 'SUBSCRIPTION_WAIT_CHARGE',
    'SUBSCRIPTION_CHARGE_SUCCEED' => 'SUBSCRIPTION_CHARGE_SUCCEED',
    'SUBSCRIPTION_CHARGE_FAIL' => 'SUBSCRIPTION_CHARGE_FAIL',
    'CLOSED' => 'CLOSED',
);
```
### State Flow of Single Payment / Cash payment
![State of Single Payment / Cash payment](https://s2.loli.net/2022/03/15/47wo3rjBSKHxYhX.jpg)
### State Flow of Subscription Payment
![State of Subscription Payment](https://s2.loli.net/2022/03/15/QgO7JpoicK5jv1I.jpg)

## Architecture Diagram

![Architecture Diagram](https://s2.loli.net/2022/03/15/7vVMbhrOuZfGNTS.jpg)

## Sequence Diagram
### Loop Queue 

#### Auto Debit and Reminder 

![Auto Debit and Reminder Loop Queue](https://s2.loli.net/2022/03/15/4RM68xDASWwsXfB.png)

#### Notity

![Notity Loop Queue](https://s2.loli.net/2022/03/15/AegrvN9hqO2Kt3z.png)

### Subscription Flow
[View big Picture about Subscription Flow](https://s2.loli.net/2022/03/15/CjRvVPz1BrqcOYt.png)
![Subscription Flow](https://s2.loli.net/2022/03/15/CjRvVPz1BrqcOYt.png)