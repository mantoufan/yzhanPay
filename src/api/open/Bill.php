<?php
namespace open;

use service\DbService;

class Bill extends Common
{
    function list() {
        $params = getParams();
        $_app_id = $params['app_id'];
        $_trade_no = $params['trade_no'];
        $result = DbService::DbList('trade', array(
            'field' => '*',
            'where' => array(
                'app_id' => $_app_id,
                'trade_no' => explode(',', $_trade_no),
            ),
        ));
        $this->export(array(
            'body' => array(
                'code' => 200,
                'msg' => 'success',
                'data' => $result['data'],
            ),
            'disableLogger' => true,
        ));
    }
}
