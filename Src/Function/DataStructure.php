<?php

/** 对象转数组
 * @param $obj
 * @return mixed
 */
function toArray($obj)
{
    return json_decode(json_encode($obj), true);
}

/** 数组转对象
 * @param $array
 * @return mixed
 */
function toObject($array)
{
    return json_decode(json_encode($array));
}

/** 对json序列化的字符串进行添加或修改某个值
 * @param $key
 * @param $value
 * @param $obj
 * @return string
 */
function jsonString_add($key, $value, $obj)
{
    $obj = json_decode($obj, true);
    $operate = &$obj;
    $key = explode('.', $key);

    for ($i = 0; $i < count($key); $i++) {
        if (isset($operate[$key[$i]]) && is_array($operate[$key[$i]]) && ($i != count($key) - 1)) {
            $operate = &$operate[$key[$i]];
        } else if (!isset($operate[$key[$i]]) && ($i != count($key) - 1)) {
            $operate[$key[$i]] = array();
            $operate = &$operate[$key[$i]];
        } else {
            $operate[$key[$i]] = $value;
        }
    }

    return json_encode($obj);
}

/** 对json序列化的字符串进行删除
 * @param $key
 * @param $obj
 * @return string
 */
function jsonString_del($key, $obj)
{
    $obj = json_decode($obj, true);
    $operate = &$obj;
    $key = explode('.', $key);

    for ($i = 0; $i < count($key); $i++) {
        if (isset($operate[$key[$i]]) && is_array($operate[$key[$i]]) && ($i != count($key) - 1)) {
            $operate = &$operate[$key[$i]];
        } else if (!isset($operate[$key[$i]]) && ($i != count($key) - 1)) {
            simpleError('jsonString_del() param is abnormal，please check "$key" param !!!', __FILE__, __LINE__);
        } else {
            unset($operate[$key[$i]]);
        }
    }

    return json_encode($obj);
}

/** 合并对象
 * @param array ...$param
 * @return array|mixed
 */
function object_merge(...$param)
{
    $return = [];
    foreach ($param as $item)
        $return = toObject(array_merge(toArray($return), toArray($item)));

    return $return;
}
