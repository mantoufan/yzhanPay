<?php
namespace controller;

use common\base\Common;
use service\QueueService;

class Queue extends Common
{
    public function run($limit)
    {
        $data = QueueService::Run(
            array(
                'limit' => $limit,
            )
        );
        $total = $data['total'];
        $this->export(array(
            'body' => 'Run ' . $total . ' task',
        ));
    }
}
