<?php
namespace controller;

use service\UserService;

class Users extends Common
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
                'ORDER' => array($params['sort'] => $params['order']),
                'LIMIT' => $params['start'] . ',' . ($params['end'] - $params['start']),
            ),
        ));
        header('X-Total-Count:' . $data['total']);
        outPut($data['data']);
    }
    public function getOne()
    {

    }
    public function getMany()
    {

    }
    public function getManyReference()
    {
    }
    public function create()
    {
    }
    public function update()
    {
    }
    public function delete()
    {
    }
}
