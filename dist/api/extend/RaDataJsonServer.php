<?php
namespace extend;

class RaDataJsonServer
{
    public static function getMethod()
    {
        $method = '';
        $pathAr = explode('/', getPath());
        if (count($pathAr) > 1) {
            $method = end($pathAr);
            if (!empty($method) && !is_numeric($method)) {
                return '';
            }
        }
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if (isset($_GET['_sort'])) {
                    $method = 'getList';
                } elseif (isset($_GET['author_id'])) {
                    $method = 'getManyReference';
                } elseif (isset($_GET['id'])) {
                    $method = 'getMany';
                } else {
                    $method = 'getOne';
                }
                break;
            case 'POST':
                $method = 'create';
                break;
            case 'PUT':
                $method = 'update';
                break;
            case 'DELETE':
                $method = 'delete';
                break;
        }
        return $method;
    }
}
