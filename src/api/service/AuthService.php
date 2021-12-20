<?php
namespace service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use service\ConfigService;

class AuthService
{
    public static function AuthPasswordEncode($password)
    {
        $CONFIG = ConfigService::ConfigGet();
        return md5($password . $CONFIG['MD5_SALT']);
    }

    public static function AuthEncode($params = array())
    {
        $CONFIG = ConfigService::ConfigGet();
        return JWT::encode(array(
            'id' => $params['id'],
            'name' => $params['name'],
        ), $CONFIG['JWT_KEY'], 'HS256');
    }

    public static function AuthDecode()
    {
        $auth = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        $CONFIG = ConfigService::ConfigGet();
        if (empty($auth)) {
            return array();
        }
        return (array) JWT::decode($auth, new Key($CONFIG['JWT_KEY'], 'HS256'));
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

    public static function AuthSign($params, $app_key = 'bc3a4d13e427ee95f24cb65f24501208a6e0d8be')
    {
        $params = array_filter($params);
        ksort($params);
        urldecode(http_build_query($params));
        return md5(urldecode(http_build_query($params)) . $app_key);
    }

    public static function AuthSignCheck($params, $app_key = 'bc3a4d13e427ee95f24cb65f24501208a6e0d8be')
    {
        $_sign = $params['sign'];
        unset($params['sign']);
        $sign = self::AuthSign($params, $app_key);
        return $_sign === $sign;
    }

    public static function AuthGetUserId()
    {
        $user = self::AuthDecode();
        return !empty($user['id']) ? $user['id'] : null;
    }
}
