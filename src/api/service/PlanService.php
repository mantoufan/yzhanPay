<?php
namespace service;

use service\DbService;

class PlanService
{
    public static function Create($params)
    {
        $params['billing_cycles'] = json_encode(self::SortASCBillingCycles($params['billing_cycles']), true);
        return DbService::Create('plan', $params);
    }

    public static function GetById($id)
    {
        return DbService::Get('plan', array(
            'field' => array('id', 'name', 'description', 'status', 'billing_cycles', 'payment_preferences', 'add_time', 'update_time', 'app_id'),
            'where' => array(
                'id' => $id,
            ),
        ));
    }

    public static function UpdateById($id, $data)
    {
        if (!empty($data['billing_cycles'])) {
            $data['billing_cycles'] = json_encode(self::SortASCBillingCycles($data['billing_cycles']), true);
        }
        return DbService::Update('plan', array(
            'data' => $data,
            'where' => array(
                'id' => $id,
            ),
        ));
    }

    public static function SortASCBillingCycles($billing_cycles)
    {
        array_multisort(array_column($billing_cycles, 'sequence'), SORT_ASC, $billing_cycles);
        return $billing_cycles;
    }
}
