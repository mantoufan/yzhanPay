<?php
namespace common\base;

use service\LoggerService;

class Common
{
    protected $table = 'common';
    protected $logger = 0;

    public function __construct()
    {
        $this->logger = new LoggerService();
        $this->logger->log(array(
            'controller' => get_class($this),
            'method' => $GLOBALS['method'],
        ));
        unset($GLOBALS['method']);
    }

    protected function export($params = array('status' => 200, 'header' => '', 'body' => '', 'disableLogger' => false))
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
        if (empty($params['disableLogger'])) {
            $this->logger->res(array(
                'status' => $status,
                'header' => $header,
                'body' => $body,
            ));
        }
        die($body);
    }
}
