<?php
namespace service\plugin\pay;

use service\DbService;

class TokenService
{
    public static function Create($data)
    {
        return DbService::Create('token', array('data' => $data));
    }

    public static function Get($params)
    {
        $data = DbService::Get('token', array(
            'field' => $params['field'] ?? array('id', 'access_token', 'access_token_expiry_time', 'refresh_token', 'refresh_token_expiry_time', 'auth_state', 'trade_no'),
            'where' => $params['where'],
        ));
        if (!empty($params['field'])) {
            return $data;
        }
        $now = time();
        $access_token_expiry_time = date($data['access_token_expiry_time']);
        $refresh_token_expiry_time = date($data['refresh_token_expiry_time']);
        if ($now < $access_token_expiry_time) {
            $access_token = $data['access_token'];
        } else if ($now < $refresh_token_expiry_time) {
            $refresh_token = $data['refresh_token'];
        }
        return array(
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
        );
    }

    public static function Update($params)
    {
        return DbService::Update('token', array(
            'data' => $params['data'],
            'where' => $params['where'],
        ));
    }
}
