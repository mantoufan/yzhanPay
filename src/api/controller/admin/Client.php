<?php
namespace controller\admin;

use common\base\ReactAdmin;

class Client extends ReactAdmin
{
    protected $table = 'client';
    protected $fields = array('id', 'display_name', 'name');
}
