<?php
namespace common\base;

use service\AuthService;
use service\LoggerService;

class Common
{
    protected $table = 'common';
    protected $logger = 0;

    public function __construct()
    {
        $this->logger = new LoggerService();
        $_path = $_SERVER['REQUEST_URI'];
        $_action = $_SERVER['REQUEST_METHOD'];
        $_payload = $_action !== 'GET' ? getPosts() : '';
        $_user_id = AuthService::GetUserId();
        $this->logger->log(array(
            'path' => $_path,
            'action' => $_action,
            'payload' => $_payload,
            'user_id' => $_user_id,
            'controller' => get_class($this),
            'method' => $GLOBALS['method'],
        ));
        unset($GLOBALS['method']);
    }

    protected function export($params = array('status' => 200, 'header' => '', 'body' => '', 'app_id' => '', 'disableLogger' => false))
    {
        $status = $params['status'];
        if (empty($status)) {
            $status = 200;
        }
        if ($status !== 200) {
            header('status:' . $status);
        }

        $header = $params['header'];
        if (is_array($header)) {
            foreach ($header as $k => $v) {
                header($k . ': ' . $v);
            }
        }
        $body = is_array($params['body']) ? json_encode($params['body']) : $params['body'];
        $app_id = $params['app_id'];
        if (empty($params['disableLogger'])) {
            $this->logger->res(array(
                'status' => $status,
                'header' => $header,
                'body' => $body,
                'app_id' => $app_id,
            ));
        }
        die($body);
    }
}
