<?php
namespace controller;

use service\AuthService;
use service\UserService;

class User extends Common
{
    public function __construct()
    {
        parent::__construct();
        UserService::UserCheckLogin();
    }
    public function getList()
    {
        $params = getGets();
        $data = UserService::UserList(array(
            'field' => array('id', 'name', 'permission'),
            'where' => array(
                'ORDER' => array($params['_sort'] => $params['_order']),
                'LIMIT' => $params['_start'] . ',' . ($params['_end'] - $params['_start']),
            ),
        ));
        header('X-Total-Count:' . $data['total']);
        outPut($data['data']);
    }
    public function getOne($id)
    {
        $data = UserService::UserGet(array(
            'field' => array('id', 'name', 'permission'),
            'where' => array(
                'id' => $id,
            ),
        ));
    }
    public function getMany()
    {

    }
    public function getManyReference()
    {
    }
    public function create()
    {
        $params = getPost();
        $data = UserService::UserCreate(array(
            'data' => array(
                'name' => $params['name'],
                'password' => AuthService::AuthPasswordEncode($params['password']),
            ),
        ));
    }
    public function update($id)
    {
        $data = UserService::UserUpdate(array(
            'data' => array(
                'name' => $params['name'],
                'password' => AuthService::AuthPasswordEncode($params['password']),
            ),
            'where' => array(
                'id' => $id,
            ),
        ));
    }
    public function delete($id)
    {
        $data = UserService::UserDelete(array(
            'where' => array(
                'id' => $id,
            ),
        ));
    }
}
