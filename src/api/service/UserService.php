<?php
namespace service;

use model\Db;
use service\AuthService;

class UserService
{
    public static function UserCheckLogin()
    {
        $user = AuthService::AuthDecode();
        if (empty($user['id'])) {
            header('status: 403');
            exit;
        }
        return $user;
    }
    public static function UserGet($params = array())
    {
        return Db::get('user', $params['field'], $params['where']);
    }
    public static function UserList($params = array())
    {
        return array(
            'total' => Db::count('user'),
            'data' => Db::select('user', $params['field'], $params['where']),
        );
    }
}
