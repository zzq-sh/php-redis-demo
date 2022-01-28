<?php


class DuplicateChecker
{

    /**
     * @var Redis
     */
    private $redis;
    /**
     * @var string
     */
    private $key;

    /**
     * DuplicateChecker constructor.
     * @param Redis $redis
     * @param string $key
     */
    public function __construct(Redis $redis, $key)
    {
        $this->redis = $redis;
        $this->key = $key;
    }

    /**
     * 在信息重复时返回True,未重复时返回False
     * @param $content
     */
    public function isDuplicated($content) {
        return 0 == $this->redis->pfAdd($this->key, [$content]);
    }

    /**
     * 返回检查器已经检查过的非重复信息数量
     */
    public function uniqueCount() {
        return $this->redis->pfCount($this->key);
    }

}



$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$key = 'duplicate-message-checker';

$checker = new DuplicateChecker($redis, $key);

/**
 * 输入一些非重复的信息
 */
echo $checker->isDuplicated("hello world!") . PHP_EOL;

echo $checker->isDuplicated("good morning!") . PHP_EOL;

echo $checker->isDuplicated("bye bye") . PHP_EOL;

/**
 * 查看目前非重复的信息数量
 */
echo  "查看目前非重复的信息数量:" . PHP_EOL;
echo $checker->uniqueCount() . PHP_EOL;

echo "发现重复信息：" . PHP_EOL;
echo $checker->isDuplicated("hello world!") . PHP_EOL;




