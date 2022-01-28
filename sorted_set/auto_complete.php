<?php


class auto_complete
{

    /**
     * @var Redis
     */
    private $redis;

    /**
     * auto_complete constructor.
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function feed($content, $weight = 1) {
        /**
         * 根据用户输入的内容构建自动补全的结果，
         * 其中content参数为内容本身，可选的weight参数用于指定内容的权重值
         */

        $len = mb_strlen($content, 'UTF-8');

        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($content, 0, $i + 1, 'UTF-8');
            $key = 'auto_complete::' . $char;
            $this->redis->zIncrBy($key, $weight, $content);
        }
    }

    public function hint($prefix, $count) {
        $key = 'auto_complete::' . $prefix;
        return $this->redis->zRevRange($key, 0, $count -1);
    }


}

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$ac = new auto_complete($redis);
//$ac->feed("黄健宏", 30);
//$ac->feed('黄健强', 3000);
//$ac->feed('黄晓明', 5000);
//$ac->feed('张三', 2500);
//$ac->feed('李四', 1700);

print_r($ac->hint('黄健', 1000));

