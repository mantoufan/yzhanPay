<?php
namespace controller\admin;

use common\base\ReactAdmin;

class Menu extends ReactAdmin
{
    protected $table = 'menu';
    protected $fields = array('id', 'display_name', 'name', 'path');
}
