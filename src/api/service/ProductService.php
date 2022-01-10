<?php
namespace service;

use service\DbService;

class ProductService
{
    public static function Create($data)
    {
        return DbService::Create('product', array('data' => $data));
    }

    public static function GetById($id)
    {
        return DbService::Get('product', array(
            'field' => array('id', 'name', 'description', 'type', 'category', 'image_url', 'url', 'add_time', 'update_time', 'app_id', 'list'),
            'where' => array(
                'id' => $id,
            ),
        ));
    }

    public static function UpdateById($id, $data)
    {
        return DbService::Update('product', array(
            'data' => $data,
            'where' => array(
                'id' => $id,
            ),
        ));
    }
}
