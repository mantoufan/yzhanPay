<?php

namespace controller\admin;

use common\base\ReactAdmin;

class Channel extends ReactAdmin
{
    protected $table = 'channel';
    protected $fields = array('id', 'app_id', 'display_name', 'payment', 'plugin', 'env', 'ability', 'config', 'client', 'active');
}
