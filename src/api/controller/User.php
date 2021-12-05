<?php
namespace controller;

use Firebase\JWT\JWT;
use service\ConfigService;
use service\UserService;

class User extends Common
{
    public function __construct()
    {
        parent::__construct();
    }

    public function authenticate()
    {
        $POST = getPosts();
        $_name = $POST['username'];
        $_password = $POST['password'];
        $CONFIG = ConfigService::ConfigList();
        $data = UserService::UserGet(array(
            'field' => array('id', 'name'),
            'where' => array(
                'name' => $_name,
                'password' => md5($_password . $CONFIG['md5_salt']),
            ),
        ));
        if (empty($data['id'])) {
            return header('Status: 403');
        }
        Output(array('auth' => JWT::encode(array(
            'id' => $data['id'],
            'name' => $data['name'],
        ), $CONFIG['jwt_key'], 'HS256')));
    }
}
