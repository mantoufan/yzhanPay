<?php
namespace controller;

use common\base\Common;
use service\AuthService;
use service\DbService;

class Auth extends Common
{
    public function login()
    {
        $POST = getPosts();
        $_name = $POST['username'];
        $_password = $POST['password'];
        $data = DbService::DbGet('user', array(
            'field' => array('id', 'name'),
            'where' => array(
                'name' => $_name,
                'password' => AuthService::AuthPasswordEncode($_password),
            ),
        ));
        if (empty($data['id'])) {
            $this->export(array('status' => 403));
        }
        $this->export(array(
            'body' => array('auth' => AuthService::AuthEncode(array(
                'id' => $data['id'],
                'name' => $data['name'],
            ))),
        ));
    }

    public function sign($params = array())
    {
        $params = getGets();
        $_sign = $params['sign'];
        unset($params['sign']);
        $_true_sign = AuthService::AuthSign($params);
        $this->export(array('body' => array('sign' => $_sign, 'trueSign' => $_true_sign, 'isMatch' => $_sign === $_true_sign)));
    }
}
