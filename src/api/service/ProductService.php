<?php
namespace service;

use service\DbService;

class ProductService
{
    public static function Create($params)
    {
        return DbService::Create('product', $params);
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
