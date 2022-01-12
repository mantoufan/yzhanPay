<?php
namespace controller;

use common\base\Common;

class Test extends Common
{
    public function returnUrl()
    {
        $this->export(array(
            'body' => getParams(),
        ));
    }

    public function cancelUrl()
    {
        $this->export(array(
            'body' => getParams(),
        ));
    }

    public function notifyUrl()
    {
        $this->export(array(
            'body' => getParams(),
        ));
    }
}
