<?php
namespace service;

use model\Db;

class DbService
{

    public static function DbGet($table, $params = array())
    {
        return Db::get($table, $params['field'], $params['where']);
    }
    public static function DbList($table, $params = array())
    {
        return array(
            'total' => Db::count($table),
            'data' => Db::select($table, $params['field'], $params['where']),
        );
    }
    public static function DbCreate($table, $params = array())
    {
        Db::insert($table, $params['data']);
        return Db::id();
    }
    public static function DbUpdate($table, $params = array())
    {
        return Db::update($table, $params['data'], $params['where']);
    }
    public static function DbDelete($table, $params = array())
    {
        return Db::delete($table, $params['where']);
    }
}
