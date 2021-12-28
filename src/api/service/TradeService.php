<?php
namespace service;

use service\DbService;

class TradeService
{
    public static function CreateNo()
    {
        list($ms) = explode(' ', microtime());
        return date('YmdHis') . ($ms * 1000000) . rand(00, 99);
    }

    public static function Create($params = array('data' => array()))
    {
        $trade_no = self::CreateNo();
        DbService::Create('trade', array(
            'data' => array_merge($params['data'], array(
                'trade_no' => $trade_no,
            )),
        ));
        return $trade_no;
    }
}
