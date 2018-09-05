<?php

namespace Jw\Support\Tool;

class ArrayTool
{
    /**
     * 查看元素是否都存在于数组中
     * @param array $object
     * @param array $values
     * @return bool
     * @internal param array $value
     * @internal param array $array
     */
    public static function array_values_exists(Array $values, Array $object): bool
    {
        return self::array_keys_exists($values, array_flip($object));
    }

    /**
     * 查看元素是否存在于数组键中
     * @param array $keys
     * @param array $object
     * @return bool
     * @Author jiaWen.chen
     */
    public static function array_keys_exists(Array $keys, Array $object): bool
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $object))
                return false;
        }
        return true;
    }
}

