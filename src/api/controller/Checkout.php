<?php
namespace controller;

use common\base\Common;
use service\AppService;
use service\ChannelService;

class Checkout extends Common
{
    public function appInfo()
    {
        $params = getGets();
        $_app_id = $params['app_id'];
        $data = AppService::Get(array(
            'field' => array('display_name'),
            'where' => array(
                'app_id' => $_app_id,
            ),
        ));
        $this->export(array('body' => array(
            'app' => array(
                'display_name' => $data['display_name'],
            ),
            'channel_list' => $this->channelList(),
        )));
    }

    public function channelList()
    {
        $params = getGets();
        $_app_id = $params['appp_id'];
        $_env = $params['env'];
        $_ability = $params['ability'];
        $channel_list = ChannelService::GetAll(array(
            'field' => array('id', 'display_name', 'ability'),
            'where' => array(
                'active' => 1,
                'app_id' => $_app_id,
                'env' => $_env,
                'ability' => $_ability,
            ),
        ));
        if (!empty($_ability)) {
            $channel_list = array_map(function ($channel) use ($_ability) {
                $channel['ability'] = $_ability;
                return $channel;
            }, $channel_list);
        }
        return $channel_list;
    }
}
