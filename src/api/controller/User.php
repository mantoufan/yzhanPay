<?php
namespace controller;

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
