<?php
namespace service;

use model\Db;

class UserService
{
    public static function UserGet($param = array())
    {
        return Db::get('user', $param['field'], $param['where']);
    }
}
