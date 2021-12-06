<?php
namespace controller;

use service\AuthService;
use service\DbService;

class Auth extends Common
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $POST = getPosts();
        $_name = $post['username'];
        $_password = $post['password'];
        $data = DbService::DbGet(array(
            'field' => array('id', 'name'),
            'where' => array(
                'name' => $_name,
                'password' => AuthService::AuthPasswordEncode($_password),
            ),
        ));
        if (empty($data['id'])) {
            return header('Status: 403');
        }
        Output(array('auth' => AuthService::AuthEncode(array(
            'id' => $data['id'],
            'name' => $data['name'],
        ))));
    }
}
