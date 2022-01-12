<?php
namespace service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use service\DbService;
use \Expection;

class QueueService extends LoggerService
{
    const CONTROLLER_NAME = 'Queue';
    const MAX_RETRY_TIMES = 9;

    public function add($params = array())
    {
        $params['controller'] = self::CONTROLLER_NAME;
        $params['queue_status'] = QUEUE_STATUS['PENDING'];
        return $this->log($params);
    }

    public static function Run($params = array('limit' => 5))
    {
        $_limit = empty($params['limit']) ? 5 : $params['limit'];
        $result = DbService::GetAll('log', array(
            'field' => array('id', 'path', 'action', 'payload', 'method', 'user_id', 'app_id', 'queue_expect', 'queue_timeout', 'queue_retry_times'),
            'where' => array(
                'controller' => self::CONTROLLER_NAME,
                'status' => QUEUE_STATUS['PENDING'],
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
            $user_id = $data['user_id'];
            $app_id = $data['app_id'];
            $queue_expect = $data['queue_expect'];
            $queue_timeout = empty($data['queue_timeout']) ? 3 : $data['queue_timeout'];
            $queue_retry_times = $data['queue_retry_times'];
            try {
                $response = $client->request($action, $path, array(
                    'form_params' => json_decode($payload, true),
                    'timeout' => $queue_timeout,
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
                        'queue_status' => $contents === $expect ? QUEUE_STATUS['SUCCEED'] : QUEUE_STATUS['FAIL'],
                        'res_body' => $contents,
                    ), array(
                        'id' => $id,
                    ));
                    if ($contents !== $expect) {
                        if ($times < self::MAX_RETRY_TIMES) {
                            $db->insert('log', array(
                                'method' => $method,
                                'path' => $path,
                                'action' => $action,
                                'payload' => $payload,
                                'expect' => $expect,
                                'controller' => self::CONTROLLER_NAME,
                                'user_id' => $user_id,
                                'app_id' => $app_id,
                                'queue_status' => QUEUE_STATUS['PENDING'],
                                'queue_timeout' => $queue_timeout,
                                'queue_retry_times' => $queue_retry_times + 1,
                            ));
                        }
                    }
                } catch (\Expection $e) {
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
