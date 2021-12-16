<?php
namespace controller;

use controller\common\Common;
use service\ChannelService;

class Checkout extends Common
{
    public static function channelList()
    {
        $channelList = ChannelService::ChannelList();
        outPut($channelList);
    }
}
