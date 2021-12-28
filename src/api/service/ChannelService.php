<?php
namespace service;

use service\DbService;

class ChannelService
{
    public static function GetById($channel_id)
    {
        return DbService::Get('channel', array(
            'field' => array('config [JSON]', 'plugin', 'client', 'active', 'env'),
            'where' => array('id' => $channel_id),
        ));
    }

    public static function GetAll($params = array())
    {
        if (!empty($params['where'])) {
            if (!empty($params['where']['ability'])) {
                $params['where']['ability'] = DbService::Raw('FIND_IN_SET(\'' . $params['where']['ability'] . '\', `ability`)');
            }
        }
        $data = DbService::GetAll('channel', array(
            'field' => $params['field'],
            'where' => array_filter($params['where']),
        ));
        return $data['data'];
    }
}
