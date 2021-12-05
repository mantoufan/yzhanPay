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
