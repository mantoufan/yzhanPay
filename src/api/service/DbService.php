<?php
namespace service;

use model\Db;

class DbService
{

    public static function Get($table, $params = array())
    {
        return empty($params['join']) ?
        Db::get($table, $params['field'], $params['where']) :
        Db::get($table, $params['join'], $params['field'], $params['where']);
    }

    public static function GetAll($table, $params = array())
    {
        return array(
            'total' => Db::count($table),
            'data' => empty($params['join']) ?
            Db::select($table, $params['field'], $params['where']) :
            Db::select($table, $params['join'], $params['field'], $params['where']),
        );
    }

    public static function Create($table, $params = array())
    {
        Db::insert($table, $params['data']);
        return Db::id();
    }

    public static function Update($table, $params = array())
    {
        return Db::update($table, $params['data'], $params['where']);
    }

    public static function Delete($table, $params = array())
    {
        return Db::delete($table, $params['where']);
    }

    public static function Action($action)
    {
        return Db::action($action);
    }

    public static function Log()
    {
        return Db::log();
    }

    public static function Raw($str)
    {
        return Db::raw($str);
    }
}
