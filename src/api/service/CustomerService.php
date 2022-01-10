<?php
namespace service;

use service\DbService;

class CustomerService
{
    public static function Create($data)
    {
        return DbService::Create('customer', array('data' => $data));
    }

    public static function GetById($id)
    {
        return DbService::Get('customer', array(
            'field' => array('id', 'first_name', 'last_name', 'description', 'email', 'add_time', 'update_time', 'app_id'),
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
