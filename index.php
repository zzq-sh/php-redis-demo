<?php

$redis = RedisTool::instance();

$str = "黄晓明";

echo mb_substr($str, 0, 0 + 1
);exit;
echo $str[0];exit;
//phpinfo();exit;
$redis = new Redis();

$host = '127.0.0.1';
$port = 6379;

try {
    $redis->connect($host, $port);

//    $ret = $redis->zAdd('test_sorted_set', 1, 't1', 2, 't2');
//    $ret = $redis->zRange('test_sorted_set', 0, -1, 1);
//    $ret
//    var_dump($ret);

    $ret = $redis->zRangeByScore('salary', '1500', 5000, ['WITHSCORES' => false]);
    var_dump($ret);

} catch (Exception $ex) {
    var_dump($ex->getMessage());

}
