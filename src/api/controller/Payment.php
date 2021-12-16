<?php

namespace controller;

use controller\common\ReactAdmin;

class Payment extends ReactAdmin
{
    protected $table = 'payment';
    protected $fields = array('id', 'display_name', 'name');
}
