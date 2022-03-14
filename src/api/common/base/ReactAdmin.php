<?php

namespace common\base;

use service\AuthService;
use service\DbService;

class ReactAdmin extends Common
{
    protected $table = '';
    protected $fields = array();

    public function __construct()
    {
        parent::__construct();
        AuthService::Check();
    }

    public function getOne($id)
    {
        $this->export(array(
            'body' => DbService::Get($this->table, array(
                'field' => $this->fields,
                'where' => array(
                    'id' => $id,
                ),
            )),
            'disableLogger' => true,
        ));
    }

    public function getList()
    {
        $params = getGets();
        $where = array(
            'ORDER' => array($params['_sort'] => $params['_order']),
            'LIMIT' => array($params['_start'], $params['_end'] - $params['_start']),
        );
        $data = DbService::GetAll($this->table, array(
            'field' => $this->fields,
            'where' => $where,
        ));
        header('X-Total-Count:' . $data['total']);
        $this->export(array(
            'body' => $data['data'],
            'disableLogger' => true,
        ));
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
        $id = DbService::Create($this->table, array(
            'data' => $params,
        ));
        $this->export(array(
            'body' => array('id' => $id),
        ));
    }

    public function update($id)
    {
        $data = getPosts();
        $rowsNum = DbService::Update($this->table, array(
            'data' => $data,
            'where' => array(
                'id' => $id,
            ),
        ));
        $this->export(array(
            'body' => array('rowsNum' => $rowsNum),
        ));
    }

    public function delete($id)
    {
        $this->export(array(
            'body' => DbService::Delete($this->table, array(
                'where' => array(
                    'id' => $id,
                ),
            )),
        ));
    }
}
