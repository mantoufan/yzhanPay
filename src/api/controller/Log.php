<?php

namespace controller;

use controller\common\ReactAdmin;

class Log extends ReactAdmin
{
    protected $table = 'log';
    protected $fields = array('id', 'path', 'method', 'controller', 'action', 'payload', 'user_id', 'add_time');
}
