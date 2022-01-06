<?php
namespace service\plugin;

use service\ChannelService;

class PluginService
{
    public static function GetChannelConfig($channel_id)
    {
        $channel = ChannelService::GetById($channel_id);
        return empty($channel) ? null : array_merge(array('env' => $channel['env']), $channel['config']);
    }

    private static function GetDir()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/gateway'));
    }

    public static function GetReturnUrl($plugin_name, $channel_id, $is_subscribe = false)
    {
        return self::getDir() . '/plugins/' . $plugin_name . '/sync' . ($is_subscribe ? '-subscribe' : '') . '/' . $channel_id;
    }

    public static function GetNotifyUrl($plugin_name, $channel_id)
    {
        return self::getDir() . '/plugins/' . $plugin_name . '/async/' . $channel_id;
    }

    public static function GetCancelUrl($plugin_name, $channel_id)
    {
        return self::getDir() . '/plugins/' . $plugin_name . '/cancel/' . $channel_id;
    }
}
