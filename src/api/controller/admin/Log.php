<?php

namespace controller\admin;

use common\base\ReactAdmin;

class Log extends ReactAdmin
{
    protected $table = 'log';
    protected $fields = array('id', 'path', 'action', 'controller', 'method', 'payload', 'res_status', 'res_header', 'res_body', 'user_id', 'add_time');
}
