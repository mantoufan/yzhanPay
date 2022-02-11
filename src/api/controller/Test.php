<?php
namespace controller;

use common\base\Common;

class Test extends Common
{
    public function returnUrl()
    {
        $this->export(array(
            'header' => array('Content-Type' => 'text/plain'),
            'body' => json_encode(getParams(), JSON_PRETTY_PRINT),
        ));
    }

    public function cancelUrl()
    {
        $this->export(array(
            'header' => array('Content-Type' => 'text/plain'),
            'body' => json_encode(getParams(), JSON_PRETTY_PRINT),
        ));
    }

    public function notifyUrl()
    {
        $this->export(array(
            'body' => 'succeed',
        ));
    }
}
