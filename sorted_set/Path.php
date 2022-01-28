<?php

/**
 * Class Path
 * 商品推荐示例
 */
class Path
{
    /**
     * @var Redis
     */
    private $redis;

    /**
     * Path constructor.
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    private function makeRecordKey($origin) {
        return sprintf('forward_to_record::%s', $origin);
    }

    public function forwardTo($origin, $destination) {
        $key = $this->makeRecordKey($origin);
        $this->redis->zIncrBy($key, 1, $destination);
    }

    public function paggingRecord($origin, $number, $count, $withTime = false) {
        /**
         * 按照每页count个目的计算
         * 从起点origin的访问记录中取出位于第number页的访问记录，
         *
         * 其中所有访问记录均按照访问次数从多到少进行排列。
         * 如果可选的withTime = true，那么将具体的访问次数也一并返回
         */

        $key = $this->makeRecordKey($origin);
        $startIndex = ($number - 1) * $count;
        $endIndex = $number * $count - 1;

        return $this->redis->zRevRange($key, $startIndex, $endIndex, $withTime);
    }
}


$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$seeAlso = new Path($redis);
//$seeAlso->forwardTo('book1', 'book2');
//$seeAlso->forwardTo('book1', 'book2');
//$seeAlso->forwardTo('book1', 'book2');
//
//$seeAlso->forwardTo('book1', 'book3');
//$seeAlso->forwardTo('book1', 'book4');
//$seeAlso->forwardTo('book1', 'book5');
//
//$seeAlso->forwardTo('book1', 'book6');
//$seeAlso->forwardTo('book1', 'book6');

// 展示顾客在看了book1之后，最经常看的其他书

$resp = $seeAlso->paggingRecord('book1', 1, 5);
var_dump($resp);

// 将查看的次数也列出来。
$resp = $seeAlso->paggingRecord('book1', 1, 5, true);
var_dump($resp);




