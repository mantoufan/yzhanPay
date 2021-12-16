<?php
namespace controller;

use controller\common\ReactAdmin;

class Plugin extends ReactAdmin
{
    protected $table = 'plugin';
    protected $fields = array('id', 'display_name', 'name', 'payment', 'input', 'author', 'link');
}
