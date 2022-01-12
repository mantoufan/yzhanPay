<?php
namespace service\plugin\pay;

use service\PlanService;
use service\TradeService;

class BillService
{
    public static function UpdateTrade($params)
    {
        return TradeService::Update(array(
            'data' => $params['data'],
            'where' => $params['where'],
        ));
    }

    public static function GetTrade($params)
    {
        return TradeService::Get(array(
            'field' => $params['field'],
            'where' => $params['where'],
        ));
    }

    public static function GetPlan($param)
    {
        return PlanService::Get(array(
            'field' => $params['field'],
            'where' => $params['where'],
        ));
    }

    public static function GetBillingCyclesFirst($billing_cycles)
    {
        $_billing_cycles_first = $billing_cycles[0];
        if ($_billing_cycles_first['tenure_type'] === 'TRIAL') {
            $_amount = 0;
        } else {
            $_amount = $_billing_cycles_first['pricing_scheme']['fixed_price']['value'];
        }
        $_currency = $_billing_cycles_first['pricing_scheme']['fixed_price']['currency_code'];
        return array(
            'amount' => $_amount,
            'currency' => $_currency,
        );
    }

    public static function GetBillingCyclesInterval($billing_cycles, $subscription_start_time, $check_time)
    {
        $time = strtotime($subscription_start_time);
        foreach ($billing_cycles as $billing_cycle) {
            $total_cycles = $billing_cycle['total_cycles'];
            $frequency = $billing_cycle['frequency'];
            $pricing_scheme = $billing_cycle['pricing_scheme'];
            $value = $pricing_scheme['fixed_price']['value'];
            $currency_code = $pricing_scheme['fixed_price']['currency_code'];
            $interval_count = $frequency['interval_count'];
            $interval_unit = $frequency['interval_unit'];
            for ($i = 0; $i < $total_cycles; $i++) {
                $time = strtotime('+ ' . $interval_count . ' ' . $interval_unit, $time);
                if ($time < $check_time) {
                    return array(
                        'amount' => $value,
                        'currency' => $currency_code,
                    );
                }
            }
        }
        return null;
    }
}
