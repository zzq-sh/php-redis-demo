<?php


class UniqueCounter
{
    /**
     * @var Redis
     */
    private $redis;

    private $key;

    public function __construct($redis, $key)
    {
        $this->redis = $redis;
        $this->key = $key;
    }


    /**
     * 对给定的元素进行计数
     * @param $item
     * @return bool
     */
    public function countIn($item) {
        return $this->redis->pfAdd($this->key, [$item]);
    }

    /**
     * 返回计数器的值
     * @return int
     */
    public function getResult() {
        return $this->redis->pfCount($this->key);
    }

}

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// 创建一个唯一IP计数器
$counter = new UniqueCounter($redis, 'unique-ip-counter');
echo "开始计数" . PHP_EOL;
echo $counter->countIn('1.1.1.1') . PHP_EOL;
echo $counter->countIn('2.2.2.2') . PHP_EOL;
echo $counter->countIn('3.3.3.3') . PHP_EOL;

echo "计数结果:";
echo $counter->getResult() . PHP_EOL;

echo $counter->countIn('3.3.3.3') . PHP_EOL;


echo "计数结果:";
echo $counter->getResult() . PHP_EOL;

/**
 * 与集合相比，使用HyperLogLog实现的唯一计数器并不会因为计数元素的增多而变大，
 * 因此它无论是对10W个，100W个，还是1000W个IP唯一进行计数，计数器消耗的内存数量都不会发生变化。12KB
 * 同等情况下，比使用集合去实现唯一计数器所需的内存要少的多。
 */





