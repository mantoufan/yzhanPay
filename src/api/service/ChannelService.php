<?php
namespace service;

use service\DbService;

class ChannelService
{
    public static function GetById($channel_id)
    {
        return DbService::Get('channel', array(
            'field' => array('config [JSON]', 'plugin', 'client', 'active'),
            'where' => array('id' => $channel_id),
        ));
    }

    public static function ListActive()
    {
        $data = DbService::GetAll('channel', array(
            'field' => array('id', 'display_name'),
            'where' => array('active' => 1),
        ));
        return $data['data'];
    }
}
