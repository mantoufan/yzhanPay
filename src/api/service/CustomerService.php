<?php
namespace service;

use service\DbService;

class CustomerService
{
    public static function Create($params)
    {
        return DbService::Create('customer', $params);
    }

    public static function GetById($id)
    {
        return DbService::Get('customer', array(
            'field' => array('id', 'name', 'description', 'email', 'area_code', 'phone', 'add_time', 'update_time', 'app_id'),
            'where' => array(
                'id' => $id,
            ),
        ));
    }

    public static function UpdateById($id, $data)
    {
        return DbService::Update('customer', array(
            'data' => $data,
            'where' => array(
                'id' => $id,
            ),
        ));
    }
}
