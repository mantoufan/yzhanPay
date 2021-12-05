<?php
namespace service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use model\Db;
use service\ConfigService;

class UserService
{
    public static function UserEnAuth($param = array())
    {
        $CONFIG = ConfigService::ConfigList();
        return JWT::encode(array(
            'id' => $data['id'],
            'name' => $data['name'],
        ), $CONFIG['jwt_key'], 'HS256');
    }
    public static function UserDeAuth()
    {
        $auth = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        $CONFIG = ConfigService::ConfigList();
        return (array) JWT::decode($auth, new Key($CONFIG['jwt_key'], 'HS256'));
    }
    public static function UserCheckLogin()
    {
        $user = self::UserDeAuth();
        if (empty($user['id'])) {
            header('status: 403');
        }
        return $user;
    }
    public static function UserGet($params = array())
    {
        return Db::get('user', $params['field'], $params['where']);
    }
    public static function UserList()
    {

    }
}
