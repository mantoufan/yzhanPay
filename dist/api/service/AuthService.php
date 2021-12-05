<?php
namespace service;

use Firebase\JWT\JWT;
use service\ConfigService;

class AuthService
{
    public static function AuthEncode($param = array())
    {
        $CONFIG = ConfigService::ConfigList();
        return JWT::encode(array(
            'id' => $data['id'],
            'name' => $data['name'],
        ), $CONFIG['jwt_key'], 'HS256');
    }
    public static function AuthDecode()
    {
        $auth = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        $CONFIG = ConfigService::ConfigList();
        return (array) JWT::decode($auth, new Key($CONFIG['jwt_key'], 'HS256'));
    }
}
