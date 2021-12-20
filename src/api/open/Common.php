<?php
namespace open;

use service\AuthService;
use service\DbService;

class Common extends common\base\Common
{
    public function __construct()
    {
        parent::__construct();
        if ($this->check()) {
            $this->export(array(
                'body' => array(
                    'status' => 403,
                ),
                'disableLogger' => true,
            ));
        }
    }

    private function check()
    {
        $params = getParams();
        $_app_id = $params['app_id'];
        $results = DbService::DbGet('app', array(
            'field' => array('app_key'),
            'where' => array(
                'app_id' => $_app_id,
            ),
        ));
        $app_key = $results['app_key'];
        return AuthService::AuthSignCheck($params, $app_key);
    }

    public function __destruct()
    {

    }
}
