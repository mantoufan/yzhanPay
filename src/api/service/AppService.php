<?php
namespace service;

use service\DbService;

class AppService
{
    public static function CreateId()
    {
        list($ms) = explode(' ', microtime());
        return date('YmdHis') . floor($ms * 100);
    }

    public static function GetUserIdByAppId($app_id)
    {
        $data = DbService::Get('app', array(
            'field' => array('user_id'),
            'where' => array(
                'app_id' => $app_id,
            ),
        ));
        return $data['user_id'];
    }

    public static function Get($params)
    {
        return DbService::Get('app', array(
            'field' => $params['field'],
            'where' => $params['where'],
        ));
    }
}
