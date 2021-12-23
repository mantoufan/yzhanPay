<?php
namespace controller\admin;

use common\base\ReactAdmin;
use service\AuthService;

class User extends ReactAdmin
{
    protected $table = 'user';
    protected $fields = array('id', 'name', 'permission');

    public function create()
    {
        $params = getPosts();
        parent::create(array(
            'name' => $params['name'],
            'password' => AuthService::PasswordEncode($params['password']),
        ));
    }
    public function update($id)
    {
        $params = getPosts();
        parent::update($id, array(
            'name' => $params['name'],
            'password' => AuthService::PasswordEncode($params['password']),
        ));
    }
}
