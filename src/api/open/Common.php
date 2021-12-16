<?php
namespace open;

use service\AuthService;
use service\ConfigService;
use service\DbService;

class Common
{
    private function check()
    {
        $params = getParams();
        $_app_id = $params['app_id'];
        $results = DbService::DbGet('app',array(
            'field' => array('app_key'),
            'where' => array(
                'app_id' => $_app_id,
            ),
        ));
        $app_key = $results['app_key'];
        return AuthService::AuthSignCheck($params, $app_key);
    }

    public function __construct()
    {
        if ($this->check()) {
            header('status: 403');
            exit();
        }
        ConfigService::ConfigInit();
    }

    public function __destruct()
    {

    }
}
