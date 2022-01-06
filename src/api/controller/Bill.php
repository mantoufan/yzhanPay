<?php
namespace controller;

use common\base\Common;
use service\BillService;
use service\DbService;

class Bill extends Common
{
    public function check()
    {
        $bills = DbService::GetAll(array(
            'join' => array('[>]plan' => ['id' => 'plan_id'], '[>]channel' => ['id' => 'channel_id']),
            'field' => array('plan.billing_cycles [JSON]', 'plan.name', 'trade.plan_start_time', 'channel.plugin', 'trade.status', 'trade.plan_id', 'trade.channel_id', 'trade.api_subscription_id'),
            'where' => array(
                'status' => array('SUBSCRIPTION_WAIT_REMIND', 'SUBSCRIPTION_WAIT_CHARGE'),
            ),
        ));
        $now = time();
        foreach ($bills as $bill) {
            $interval = BillService::GetBillingCyclesInterval(
                $bill['billing_cycles'],
                $bill['plan_start_time'],
                $bill['status'] === TRADE_STATUS['SUBSCRIPTION_WAIT_REMIND'] ? $now - ADVANCE_REMINDER_TIME : $now
            );
            if ($interval) {
                switch ($bill['status']) {
                    case TRADE_STATUS['SUBSCRIPTION_WAIT_REMIND']:
                        // 通知用户
                        $next_status = TRADE_STATUS['SUBSCRIPTION_WAIT_CHARGE'];
                        break;
                    case TRADE_STATUS['SUBSCRIPTION_WAIT_CHARGE']:
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
