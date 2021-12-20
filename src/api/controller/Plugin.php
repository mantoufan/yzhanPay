<?php
namespace controller;

use common\base\ReactAdmin;

class Plugin extends ReactAdmin
{
    protected $table = 'plugin';
    protected $fields = array('id', 'display_name', 'name', 'payment', 'input', 'author', 'link');
}
