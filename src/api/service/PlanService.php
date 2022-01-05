<?php
namespace service;

use service\DbService;

class PlanService
{
    public static function Create($params)
    {
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
        return DbService::Update('plan', array(
            'data' => $data,
            'where' => array(
                'id' => $id,
            ),
        ));
    }
}
