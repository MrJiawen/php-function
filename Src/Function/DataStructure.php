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

/**
 * 对象集合按照音序排序
 * @param $handle
 * @param $target
 * @param string $oder
 * @return array
 * @Author jiaWen.chen
 */
function sortByPinyin($handle, $target, $oder = 'asc')
{
    if (!is_array($handle)) {
        simpleError('$handle must be array parameter !!!', __FILE__, __LINE__);
    }

    $tmp = [];
    $pinyin = new Overtrue\Pinyin\Pinyin();
    switch (gettype(array_values($handle)[0])) {
        case 'object':
            foreach ($handle as $key => $value) {
                $tmp[$key] = implode('', $pinyin->convert($value->$target));
            }
            break;
        case 'array':
            foreach ($handle as $key => $value) {
                $tmp[$key] = implode('', $pinyin->convert($value[$target]));
            }
            break;
        default :
            simpleError('element in $handle must be object or array ', __FILE__, __LINE__);
    }
    $oder == 'asc' ? asort($tmp) : arsort($tmp);
    $response = [];
    foreach ($tmp as $key => $value) {
        $response[] = $handle[$key];
    }
    return $response;
}
