<?php
namespace model;

use Medoo\Medoo;

class Db
{
    public static $db = null;
    public static function connect()
    {
        return new Medoo(include 'common/database.php');
    }

    public static function __callStatic($funName, $arguments)
    {
        if (empty(self::$db)) {
            self::$db = self::connect();
        }

        return call_user_func_array(array(self::$db, $funName), $arguments);
    }
}
