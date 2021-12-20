<?php

namespace controller;

use common\base\ReactAdmin;

class Payment extends ReactAdmin
{
    protected $table = 'payment';
    protected $fields = array('id', 'display_name', 'name');
}
