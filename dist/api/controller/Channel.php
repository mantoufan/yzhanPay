<?php
namespace controller;

use service\AuthService;
use service\DbService;

class Channel extends Common
{
    public function __construct()
    {
        parent::__construct();
        AuthService::AuthCheck();
    }
    public function getList()
    {
        $params = getGets();
        $data = DbService::DbList('channel', array(
            'field' => array('id'),
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
        outPut(DbService::DbGet('channel', array(
            'field' => array('id'),
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
        $id = DbService::DbCreate('channel', array(
            'data' => array(
                'name' => $params['name'],
            ),
        ));
        outPut(array('id' => $id));
    }
    public function update($id)
    {
        $rowsNum = DbService::DbUpdate('channel', array(
            'data' => array(
                'name' => $params['name'],
            ),
            'where' => array(
                'id' => $id,
            ),
        ));
        outPut(array('rowsNum' => $rowsNum));
    }
    public function delete($id)
    {
        outPut(DbService::DbDelete('channel', array(
            'where' => array(
                'id' => $id,
            ),
        )));
    }
}
