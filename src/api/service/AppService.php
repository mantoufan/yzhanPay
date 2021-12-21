<?php
namespace service;

use service\DbService;

class AppService
{
    public static function getUserIdByAppId($app_id)
    {
        $data = DbService::DbGet('app', array(
            'field' => array('user_id'),
            'where' => array(
                'app_id' => $app_id,
            ),
        ));
        return $data['user_id'];
    }
}
