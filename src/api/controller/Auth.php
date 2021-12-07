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

    public function sign($params = array()) {
      $params = getGets();
      $_sign = $params['sign'];
      unset($params['sign']);
      $_true_sign = AuthService::AuthSign($params);
      Output(array('sign' => $_sign,'trueSign' => $_true_sign, 'isMatch' => $_sign === $_true_sign));
    }
}
