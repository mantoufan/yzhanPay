<?php
namespace controller;

use common\base\Common;
use service\ChannelService;
use service\ConfigService;
use service\DbService;
use service\NotificationService;
use service\plugin\pay\BillService;
use service\TplService;
use service\TradeService;

class Bill extends Common
{
    public function check()
    {
        $bills = DbService::GetAll('trade', array(
            'join' => array('[>]plan' => ['plan_id' => 'id'], '[>]channel' => ['channel_id' => 'id'], '[>]customer' => ['customer_id' => 'id']),
            'field' => array('plan.billing_cycles [JSON]', 'plan.name', 'trade.plan_start_time', 'channel.plugin', 'trade.status', 'trade.plan_id', 'trade.channel_id', 'trade.api_subscription_id', 'trade.trade_no', 'customer.email', 'customer.first_name', 'customer.last_name'),
            'where' => array(
                'trade.status' => array('SUBSCRIPTION_WAIT_REMIND', 'SUBSCRIPTION_WAIT_CHARGE'),
            ),
        ));
        if (empty($bills['data'])) {
            return false;
        }
        $now = time();
        foreach ($bills['data'] as $bill) {
            $interval = BillService::GetBillingCyclesInterval(
                $bill['billing_cycles'],
                $bill['plan_start_time'],
                $bill['status'] === TRADE_STATUS['SUBSCRIPTION_WAIT_REMIND'] ? $now + ADVANCE_REMINDER_TIME : $now + ADVANCE_REMINDER_TIME
            );
            if ($interval) {
                $tplParams = array(
                    'user.first_name' => $bill['first_name'],
                    'user.last_name' => $bill['last_name'],
                    'bill.amount' => $interval['amount'],
                    'bill.currency' => $interval['currency'],
                );
                switch ($bill['status']) {
                    case TRADE_STATUS['SUBSCRIPTION_WAIT_REMIND']:
                        $config = ConfigService::Get();
                        if (!empty($bill['email'])) {
                            NotificationService::email(array(
                                'smtp' => array(
                                    'host' => $config['NOTIFICATION']['MAIL']['SMTP']['HOST'],
                                    'username' => $config['NOTIFICATION']['MAIL']['SMTP']['USERNAME'],
                                    'password' => $config['NOTIFICATION']['MAIL']['SMTP']['PASSWORD'],
                                    'port' => $config['NOTIFICATION']['MAIL']['SMTP']['PORT'],
                                ),
                                'from' => array(
                                    'mail' => $config['NOTIFICATION']['MAIL']['FROM']['MAIL'],
                                    'name' => $config['NOTIFICATION']['MAIL']['FROM']['NAME'],
                                ),
                                'send' => array(
                                    'mail' => $bill['email'],
                                    'name' => $bill['first_name'] . $bill['last_name'],
                                ),
                                'subject' => TplService::parse($config['NOTIFICATION']['MAIL']['TPL']['AUTO_CHARGE']['SUBJECT'], $tplParams),
                                'body' => TplService::parse($config['NOTIFICATION']['MAIL']['TPL']['AUTO_CHARGE']['BODY'], $tplParams),
                            ));
                        }
                        if (!empty($bill['phone'])) {
                            NotificationService::sms(array(
                                'service_provider' => 'aliyun',
                                'options' => array(
                                    'accessKeyId' => $config['NOTIFICATION']['SMS']['ALIYUN']['ACCESS_KEY_ID'],
                                    'accessSecret' => $config['NOTIFICATION']['SMS']['ALIYUN']['ACCESS_SECRET'],
                                    'RegionId' => $config['NOTIFICATION']['SMS']['ALIYUN']['REGION_ID'],
                                    'PhoneNumbers' => $bill['phone'],
                                    'SignName' => $config['NOTIFICATION']['SMS']['ALIYUN']['SIGN_NAME'],
                                    'TemplateCode' => $config['NOTIFICATION']['SMS']['ALIYUN']['TPL']['AUTO_CHARGE']['TEMPLATE_CODE'],
                                    'TemplateParam' => $tplParams,
                                ),
                            ));
                        }
                        TradeService::Update(array(
                            'data' => array(
                                'status' => TRADE_STATUS['SUBSCRIPTION_WAIT_CHARGE'],
                            ),
                            'where' => array(
                                'trade_no' => $bill['trade_no'],
                            ),
                        ));
                        break;
                    case TRADE_STATUS['SUBSCRIPTION_WAIT_CHARGE']:
                        $gateway = ChannelService::GetGateway($bill['plugin']);
                        $gateway->charge($bill['channel_id'], array(
                            'subscription_id' => $bill['api_subscription_id'],
                            'note' => $bill['name'],
                            'amount' => $interval['amount'],
                            'currency' => $interval['currency'],
                        ));
                        break;
                }
            }
        }
    }
}
