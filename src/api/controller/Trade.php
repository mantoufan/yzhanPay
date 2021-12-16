<?php

namespace controller;

use controller\common\ReactAdmin;

class Trade extends ReactAdmin
{
    protected $table = 'trade';
    protected $fields = array('id', 'trade_no', 'out_trade_no', 'api_trade_no', 'status', 'user_id', 'app_id', 'subject', 'total_amount', 'timestamp', 'return_url', 'notify_url', 'body', 'notify_status');
}
