<?php
namespace controller;

use Firebase\JWT\JWT;
use Medoo\Medoo;
use service\ConfigService;

class Authenticate extends Common
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkLogin()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $_name = $_POST['username'];
        $_password = $_POST['password'];
        $CONFIG = ConfigService::ConfigList();
        $DB = new Medoo($CONFIG['database']);
        $data = $DB->get('user', array(
            'id',
            'name',
        ), array(
            'name' => $_name,
            'password' => md5($_password . $CONFIG['md5_salt']),
        ));
        if (empty($data['id'])) {
            return header('Status: 403');
        }

        EchoJson(array('auth' => JWT::encode(array(
            'id' => $data['id'],
            'name' => $data['name'],
        ), $CONFIG['jwt_key'], 'HS256')));

    }
}
