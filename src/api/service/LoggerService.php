<?php
namespace service;

use service\AuthService;
use service\DbService;

class LoggerService
{
    private $id = 0;

    private static function stringify($params)
    {
        return is_array($params) ? (empty($params) ? '' : json_encode($params)) : $params;
    }

    private static function parse($string)
    {
        return json_decode($string, true);
    }

    public function log($params = array())
    {
        $_controller = $params['controller'];
        $_method = $params['method'];
        $_path = $_SERVER['REQUEST_URI'];
        $_action = $_SERVER['REQUEST_METHOD'];
        $_payload = self::stringify($_action !== 'GET' ? getPosts() : '');
        $_user_id = AuthService::AuthGetUserId();
        $this->id = DbService::DbCreate('log', array(
            'data' => array(
                'path' => $_path,
                'action' => $_action,
                'controller' => $_controller,
                'method' => $_method,
                'payload' => $_payload,
                'user_id' => $_user_id,
            ),
        ));
    }

    public function res($params = array('status' => 200, 'header' => '', 'body' => ''))
    {
        $id = $this->id;
        DbService::DbUpdate('log', array(
            'data' => array(
                'res_status' => $params['status'],
                'res_header' => self::stringify($params['header']),
                'res_body' => self::stringify($params['body']),
            ),
            'where' => array(
                'id' => $id,
            ),
        ));

    }
}
