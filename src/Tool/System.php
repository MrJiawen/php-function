<?php

namespace Jw\Support\Tool;

class System
{
    /**
     * 计算系统消耗 初始化标签
     */
    public static function computeSystemResource_init()
    {
        global $computeSystemResourceTime;
        $computeSystemResourceTime = microtime(true);;
    }

    /**
     * 得到系统消耗信息
     * @param bool $isString
     * @return array|string
     * @internal param bool $isPrint
     */
    public static function computeSystemResource_getInfo(bool $isString = true)
    {
        global $computeSystemResourceTime;

        $nowTime = microtime(true);;

        // 使用的时常和内存消耗
        $useTime = sprintf('%0.4f', $nowTime - $computeSystemResourceTime);
        $useMemory = sprintf('%0.2f', memory_get_usage() / 1024 / 1024);

        if ($isString) {
            return " 内存消耗为：{$useMemory}MB，用时：{$useTime} 秒";
        } else {
            return ['memory' => $useMemory, 'time' => $useTime];
        }
    }
}




