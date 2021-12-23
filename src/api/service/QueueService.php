<?php
namespace service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use service\DbService;

class QueueService extends LoggerService
{
    const CONTROLLER_NAME = 'Queue';
    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 2;
    const MAX_TIMES = 9;
    public function add($params = array())
    {
        $params['controller'] = self::CONTROLLER_NAME;
        $params['status'] = self::STATUS_PENDING;
        $this->log($params);
    }

    public static function Run($params = array('limit' => 5))
    {
        $_limit = empty($params['limit']) ? 5 : $params['limit'];
        $result = DbService::GetAll('log', array(
            'field' => array('id', 'path', 'action', 'payload', 'method', 'expect', 'user_id', 'app_id', 'timeout', 'times'),
            'where' => array(
                'controller' => self::CONTROLLER_NAME,
                'status' => self::STATUS_PENDING,
            ),
            'option' => array(
                "LIMIT" => $_limit,
            ),
        ));
        $dataList = $result['data'];

        $client = new Client(array('verify' => false));
        foreach ($dataList as $data) {
            $id = $data['id'];
            $path = $data['path'];
            $action = $data['action'];
            $payload = $data['payload'];
            $method = $data['method'];
            $expect = $data['expect'];
            $user_id = $data['user_id'];
            $app_id = $data['app_id'];
            $timeout = empty($data['timeout']) ? 3 : $data['timeout'];
            $times = $data['times'];
            try {
                $response = $client->request($action, $path, array(
                    'form_params' => json_decode($payload, true),
                    'timeout' => $timeout,
                ));
                $contents = $response->getBody()->getContents();
            } catch (ConnectException $e) {
                $contents = json_encode($e->getHandlerContext(), true);
            } catch (RequestException $e) {
                $contents = Psr7\Message::toString($e->getResponse());
            }
            DbService::Action(function ($db) use ($contents, $expect, $id, $times, $method, $path, $action, $payload, $user_id, $app_id, $timeout) {
                try {
                    $db->update('log', array(
                        'status' => $contents === $expect ? self::STATUS_SUCCESS : self::STATUS_FAIL,
                        'res_body' => $contents,
                    ), array(
                        'id' => $id,
                    ));
                    if ($contents !== $expect) {
                        if ($times < self::MAX_TIMES) {
                            $db->insert('log', array(
                                'method' => $method,
                                'path' => $path,
                                'action' => $action,
                                'payload' => $payload,
                                'expect' => $expect,
                                'controller' => self::CONTROLLER_NAME,
                                'status' => self::STATUS_PENDING,
                                'user_id' => $user_id,
                                'app_id' => $app_id,
                                'timeout' => $timeout,
                                'times' => $times + 1,
                            ));
                        }
                    }
                } catch (Expection $e) {
                    return false;
                }
            });
        }
        return array(
            'total' => count($dataList),
            'data' => $dataList,
        );
    }
}
