<?php

namespace controller\admin;

use common\base\ReactAdmin;

class Channel extends ReactAdmin
{
    protected $table = 'channel';
    protected $fields = array('id', 'display_name', 'plugin', 'config', 'client', 'enabled');
}
