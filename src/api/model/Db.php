<?php
namespace model;

use Medoo\Medoo;

class Db
{
    public static function connect($config = array())
    {
        return new Medoo(array_merge(include 'common/database.php', $config));
    }

    public static function __callStatic($funName, $arguments)
    {
        return call_user_func_array(array(self::connect(), $funName), $arguments);
    }
}
