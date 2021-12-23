<?php
namespace service;

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
        $_path = $params['path'];
        $_action = $params['action'];
        $_payload = self::stringify($params['payload']);
        $_user_id = $params['user_id'];
        $_app_id = $params['app_id'];
        $_expect = $params['expect'];
        $_status = $params['status'];
        $_timeout = $params['timeout'];
        $this->id = DbService::Create('log', array(
            'data' => array(
                'path' => $_path,
                'action' => $_action,
                'controller' => $_controller,
                'method' => $_method,
                'payload' => $_payload,
                'user_id' => $_user_id,
                'app_id' => $_app_id,
                'expect' => $_expect,
                'status' => $_status,
                'timeout' => $_timeout,
            ),
        ));
    }

    public function res($params = array('status' => 200, 'header' => '', 'body' => ''))
    {
        $id = $this->id;
        DbService::Update('log', array(
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
