<?php

/**
 * Class TimeLine
 * 时间线示例
 */
class TimeLine
{
    private $redis;

    private $key;

    public function __construct($key, Redis $redis)
    {
        $this->redis = $redis;
//        $this->redis = new Redis();
//        $this->redis->connect('127.0.0.1', 6379);

        $this->key = $key;
    }

    public function add($item, $time) {
        return $this->redis->zAdd($this->key, [], $time, $item);
    }

    public function remove($item) {
        return $this->redis->zRem($this->key, $item);
    }

    public function count() {
        return $this->redis->zCard($this->key);
    }

    public function pagging($number, $count, $withTime = false) {
        $start = ($number - 1) * $count;
        $end = $number * $count - 1;

        return $this->redis->zRevRange($this->key, $start, $end, $withTime);
    }

    public function fetchByTimeRange($minTime, $maxTime, $number, $count, $withTime = false) {
        $startIndex = ($number - 1) * $count;
        var_dump($startIndex);
        var_dump($count);exit;
        return $this->redis->zRevRangeByScore($this->key, $maxTime, $minTime, ['WITHSCORES' => $withTime, "LIMIT {$startIndex} {$count}"]);
    }

}


$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$key = 'blog_timeline';

$blogs = new TimeLine($key, $redis);

//echo $blogs->remove('blog_title 89');
//var_dump($blogs->count());
//var_dump($blogs->pagging(2, 5, true));
$maxTime = 1642220427;
var_dump($blogs->fetchByTimeRange(0, $maxTime, 1, 5));





