<?php
namespace service;

use AuthService;
use model\Db;

class UserService
{
    public static function UserCheckLogin()
    {
        $user = AuthService::AuthDecode();
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
