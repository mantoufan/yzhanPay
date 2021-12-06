<?php
namespace controller;

use service\AuthService;
use service\DbService;

class User extends Common
{
    public function __construct()
    {
        parent::__construct();
        AuthService::AuthCheck();
    }
    public function getList()
    {
        $params = getGets();
        $data = DbService::DbList('user', array(
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
        outPut(DbService::DbGet('user', array(
            'field' => array('id', 'name', 'permission'),
            'where' => array(
                'id' => $id,
            ),
        )));
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
        $id = DbService::DbCreate('user', array(
            'data' => array(
                'name' => $params['name'],
                'password' => AuthService::AuthPasswordEncode($params['password']),
            ),
        ));
        outPut(array('id' => $id));
    }
    public function update($id)
    {
        $rowsNum = DbService::DbUpdate('user', array(
            'data' => array(
                'name' => $params['name'],
                'password' => AuthService::AuthPasswordEncode($params['password']),
            ),
            'where' => array(
                'id' => $id,
            ),
        ));
        outPut(array('rowsNum' => $rowsNum));
    }
    public function delete($id)
    {
        outPut(DbService::DbDelete('user', array(
            'where' => array(
                'id' => $id,
            ),
        )));
    }
}
