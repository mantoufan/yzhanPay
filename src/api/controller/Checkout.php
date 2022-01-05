<?php
namespace controller;

use common\base\Trade;
use service\AppService;
use service\ChannelService;

class Checkout extends Trade
{
    public function appInfo()
    {
        $params = getGets();
        $_app_id = $params['app_id'];
        $_currency = $params['currency'];
        $data = AppService::Get(array(
            'field' => array('display_name'),
            'where' => array(
                'app_id' => $_app_id,
            ),
        ));
        $_product = json_decode($params['product'], true);
        $this->export(array('body' => array(
            'app' => array(
                'display_name' => $data['display_name'],
            ),
            'channel_list' => $this->channelList(),
            'product' => $this->getProduct($_product, $_app_id, true),
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
