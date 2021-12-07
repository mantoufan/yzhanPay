<?php
namespace service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use service\ConfigService;

class AuthService
{
    public static function AuthPasswordEncode($password)
    {
        $CONFIG = ConfigService::ConfigList();
        return md5($password . $CONFIG['md5_salt']);
    }
    public static function AuthEncode($params = array())
    {
        $CONFIG = ConfigService::ConfigList();
        return JWT::encode(array(
            'id' => $params['id'],
            'name' => $params['name'],
        ), $CONFIG['jwt_key'], 'HS256');
    }
    public static function AuthDecode()
    {
        $auth = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        $CONFIG = ConfigService::ConfigList();
        if (empty($auth)) {
            return array();
        }
        return (array) JWT::decode($auth, new Key($CONFIG['jwt_key'], 'HS256'));
    }
    public static function AuthCheck()
    {
        $user = self::AuthDecode();
        if (empty($user['id'])) {
            header('status: 403');
            exit;
        }
        return $user;
    }
    public static function AuthSign($params, $secret_key = 'bc3a4d13e427ee95f24cb65f24501208a6e0d8be'){
        $params = array_filter($params);
        ksort($params);
        return md5(urldecode(http_build_query($params)) . $secret_key);
    }
}
