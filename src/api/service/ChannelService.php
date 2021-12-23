<?php
namespace service;

use service\DbService;

class ChannelService
{
    public static function GetById($channel_id)
    {
        return DbService::Get('channel', array(
            'field' => array('config [JSON]', 'plugin', 'client', 'enabled'),
            'where' => array('id' => $channel_id),
        ));
    }

    public static function ListEnabled()
    {
        $data = DbService::GetAll('channel', array(
            'field' => array('id', 'display_name'),
            'where' => array('enabled' => 1),
        ));
        return $data['data'];
    }
}
