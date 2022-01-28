<?php


class RedisTool
{
    public static function instance() {
        $redis = new Redis();
        return $redis->connect('127.0.0.1', 6379);
    }
}