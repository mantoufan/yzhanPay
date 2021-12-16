<?php
namespace controller;

use controller\common\ReactAdmin;
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
            'password' => AuthService::AuthPasswordEncode($params['password']),
        ));
    }
    public function update($id)
    {
        $params = getPosts();
        parent::update($id, array(
            'name' => $params['name'],
            'password' => AuthService::AuthPasswordEncode($params['password']),
        ));
    }
}
