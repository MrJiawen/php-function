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

    /**
     * 数据可视化操作
     * @param $param
     * @param bool $haveQuotation
     * @param int $indent
     * @param string $symbol
     * @param bool $isPHP
     * @return string
     * @internal param bool $isPhp
     * @Author jiaWen.chen
     */
    public static function getJsonView($param, bool $haveQuotation = true, int $indent = 4, string $symbol = ":", bool $isPHP = false)
    {
        $response = self::_getJsonView($param, $haveQuotation, $indent, $symbol, $isPHP);
        return implode("\n", $response);
    }

    /**
     * 数据可视化操作
     * @param $param
     * @param bool $haveQuotation
     * @param int $indent
     * @param string $symbol
     * @param bool $isPHP
     * @return array
     * @internal param bool $isPhp
     * @Author jiaWen.chen
     */
    private static function _getJsonView($param, bool $haveQuotation, int $indent, string $symbol, bool $isPHP)
    {
        // 1. 定义变量
        $param = self::toObject($param);
        $response = [];
        $indentStr = '';
        for ($i = 0; $i < $indent; $i++) {
            $indentStr .= ' ';
        }

        // 2. 判断是对象还是数组
        if (is_array($param) || $isPHP) {
            $response[] = "[";
        } else {
            $response[] = "{";
        }
        foreach ($param as $key => $value) {
            // 3. 子元素是否为 复合数据类型
            if (is_array($value) || is_object($value)) {
                $view = self::_getJsonView($value, $haveQuotation, $indent, $symbol, $isPHP);
                if (is_object($param)) {
                    $view[0] = "\"$key\"$symbol " . $view[0];
                }
                $view = array_map(function ($item) use ($indentStr) {
                    return $indentStr . $item;
                }, $view);
                $view[count($view) - 1] .= ',';
                $response = array_merge($response, $view);
            } else if (is_array($param)) {
                // 4. 是否为数组
                $response[] = $indentStr . StringTool::valueView($value) . ',';
            } else if (is_object($param)) {
                // 5. 是否为对象
                $response[] = $indentStr . "\"$key\"{$symbol} " . StringTool::valueView($value) . ",";
            }
        }
        // 6. 去除最后一个元素的逗号
        $response[count($response) - 1] = trim($response[count($response) - 1], ',');
        // 2. 判断是对象还是数组
        if (is_array($param) || $isPHP) {
            $response[] = "]";
        } else {
            $response[] = "}";
        }
        return $response;
    }
}