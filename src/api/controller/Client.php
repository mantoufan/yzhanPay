<?php
namespace controller;

use controller\common\ReactAdmin;

class Client extends ReactAdmin
{
    protected $table = 'client';
    protected $fields = array('id', 'display_name', 'name');
}
