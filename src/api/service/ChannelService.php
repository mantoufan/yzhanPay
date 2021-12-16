<?php
namespace service;

use service\ChannelService;
use service\ConfigService;
use service\DbService;

class ChannelService
{
    public static function ChannelGet($channel_id)
    {
        $data = DbService::DbGet('channel', array(
            'field' => array('config', 'plugin', 'client', 'enabled'),
            'where' => array('id' => $channel_id),
        ));
        $config = json_decode($data['config'], true);
        return array(
            'client' => $data['client'],
            'plugin' => $data['plugin'],
            'config' => empty($config) ? array() : $config,
            'enabled' => $data['enabled'],
        );
    }

    public static function ChannelList()
    {
        $data = DbService::DbList('channel', array(
            'field' => array('id', 'display_name'),
            'where' => array('enabled' => 1),
        ));
        return $data['data'];
    }

    public static function ChannelConfig($channel_id)
    {
        $channel = ChannelService::ChannelGet($channel_id);
        return array(
            'channel_config' => $channel['config'],
            'global_config' => ConfigService::ConfigGet(),
        );
    }
}
