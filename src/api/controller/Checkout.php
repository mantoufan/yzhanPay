<?php
namespace controller;

use common\base\Common;
use service\ChannelService;

class Checkout extends Common
{
    public function channelList()
    {
        $channelList = ChannelService::ChannelList();
        $this->export(array('body' => $channelList));
    }
}
