<?php

namespace Jw\Support\Tool;

class DataStructure
{
    /**
     * 对象转数组
     * @param $param
     * @return array
     * @Author jiaWen.chen
     */
    public static function toArray($param): array
    {
        return json_decode(json_encode($param), true);
    }

    /**
     * 数组转对象
     * @param $param
     * @return mixed
     * @Author jiaWen.chen
     */
    public static function toObject($param)
    {
        return json_decode(json_encode($param), false);
    }


    /** 对json序列化的字符串进行添加或修改某个值
     * @param $key
     * @param $value
     * @param $obj
     * @return string
     */
    public static function jsonString_set(string $key, $value, string $obj)
    {
        $obj = json_decode($obj, true);

        return json_encode(self::array_set($key, $value, $obj));
    }

    /**
     * 设置多维数组的的值
     * @param string $key
     * @param $value
     * @param array $obj
     * @return array|string
     * @Author jiaWen.chen
     */
    public static function array_set(string $key, $value, array $obj)
    {
        if (is_null($key)) {
            return json_encode($obj);
        }
        $keys = explode('.', $key);
        $tmp = &$obj;
        while (count($keys) > 0) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($tmp[$key]) || !is_array($tmp[$key])) {
                $tmp[$key] = [];
            }

            $tmp = &$tmp[$key];
        }
        $tmp = $value;

        return $obj;
    }

    /**
     * 合并对象
     * @param array ...$param
     * @return array|mixed
     * @Author jiaWen.chen
     */
    public static function object_merge(...$param)
    {
        $response = [];
        foreach ($param as $item) {
            $response = self::toObject(array_merge(self::toArray($response), self::toArray($item)));
        }

        return $response;
    }
}