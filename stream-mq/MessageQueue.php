<?php

class MessageQueue {

    private $redis;
    private $streamKey;

    public function __construct(Redis $redis, $streamKey)
    {
        $this->redis = $redis;
        $this->streamKey = $streamKey;
    }

    public function addMessage($messageArr) {
        return $this->redis->xAdd($this->streamKey, '*', $messageArr);
    }

    public function getMessage($messageId) {
        $resp = $this->redis->xRange($this->streamKey, $messageId, $messageId, 1);
        if (isset($resp) && isset($resp[$messageId])) {
            return $resp[$messageId];
        }

        return null;
    }

    public function removeMessage($messageId) {
        return $this->redis->xDel($this->streamKey, [$messageId]);
    }

    public function len() {
        return $this->redis->xLen($this->streamKey);
    }

    public function getByRange($startId, $endId, $maxItem = 10) {
        return $this->redis->xRange($this->streamKey, $startId, $endId, $maxItem);
    }

    public function iterate($startId, $maxItem) {
        $arrStreams = [
            $this->streamKey => $startId
        ];
        $reply = $this->redis->xRead($arrStreams, $maxItem);

    }

    public function reconstructMessageList($messageList) {
        // 为了让
    }
}

var_dump([[
    '12' =>0
]]);

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
//
$arrStreams = [
    'temp-stream' => '0-0',
    'mini-stream' => '0-0',
];
$resp = $redis->xRead($arrStreams, 1, 100);
var_dump($resp);exit;
////echo $resp['mini-stream']['1647182973078-0']['k5'];
//var_dump($resp);
//$resp = $redis->xAdd('temp-stream', '*', [
//    'k1' => 'v1',
//    'k2' => 'v2'
//]);

$resp = $redis->xRange('mini-stream', '-', '+');

var_dump($resp);
