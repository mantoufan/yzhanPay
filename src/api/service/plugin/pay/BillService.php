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
        if ($billing_cycles_first['tenure_type'] === 'TRIAL') {
            $_amount = 0;
        } else {
            $_amount = $billing_cycles_first['pricing_scheme']['fixed_price']['value'];
        }
        $_currency = $billing_cycles_first['pricing_scheme']['fixed_price']['currency_code'];
        return array(
            'amount' => $_amount,
            'currency' => $_currency,
        );
    }
}
