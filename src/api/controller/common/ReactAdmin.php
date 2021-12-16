<?php

namespace controller\common;

use service\DbService;
use service\AuthService;

class ReactAdmin extends Common
{
    protected $table = '';
    protected $fields = array();

    public function __construct()
    {
        parent::__construct();
        AuthService::AuthCheck();
    }

    public function getOne($id)
    {
        outPut(DbService::DbGet($this->table, array(
            'field' => $this->fields,
            'where' => array(
                'id' => $id,
            ),
        )));
    }

    public function getList()
    {
        $params = getGets();
        $where = array(
            'ORDER' => array($params['_sort'] => $params['_order']),
            'LIMIT' => $params['_start'] . ',' . ($params['_end'] - $params['_start']),
        );
        $data = DbService::DbList($this->table, array(
            'field' => $this->fields,
            'where' => $where,
        ));
        header('X-Total-Count:' . $data['total']);
        outPut($data['data']);
    }

    public function getMany()
    {
        // TODO
    }

    public function getManyReference()
    {
        // TODO
    }

    public function create()
    {
        $params = getPosts();
        $id = DbService::DbCreate($this->table, array(
            'data' => $params,
        ));
        outPut(array('id' => $id));
    }

    public function update($id)
    {
        $data = getPosts();
        $rowsNum = DbService::DbUpdate($this->table, array(
            'data' => $data,
            'where' => array(
                'id' => $id,
            ),
        ));
        outPut(array('rowsNum' => $rowsNum));
    }

    public function delete($id)
    {
        outPut(DbService::DbDelete($this->table, array(
            'where' => array(
                'id' => $id,
            ),
        )));
    }
}
