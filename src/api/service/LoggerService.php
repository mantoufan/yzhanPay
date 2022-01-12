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

    public function log($params = array(
        'controller' => '',
        'method' => '',
        'path' => '',
        'action' => '',
        'user_id' => '',
        'app_id' => '',
    )) {
        if (!empty($params['payload'])) {
            $params['payload'] = self::stringify($params['payload']);
        }
        return $this->id = DbService::Create('log', array(
            'data' => $params,
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
