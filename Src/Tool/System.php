<?php

/**
 * 返回的信息
 * @param $code
 * @param null $msg
 * @param null $data
 * @return array
 */
function statusMsg($code, $msg = null, $data = null)
{
    if (empty($msg)) {
        $responseConfig = Config('statusCode');
        $exist = array_key_exists($code, (array)$responseConfig);

        if (!$exist)
            simpleError('状态码 ' . $code . ' 未定义', __FILE__, __LINE__);

        return ['ServerNo' => $code, 'ServerMsg' => $responseConfig[$code], 'ServerData' => $data];
    } else {
        return ['ServerNo' => $code, 'ServerMsg' => $msg, 'ServerData' => $data];
    }
}

/**
 *  自定义错误异常抛出函数
 * @param $message                              错误信息
 * @param $filename                             文件名
 * @param $lineno                               行号
 * @param int $severity 错误级别    error,warning
 * @param int $code default
 * @param null $previous default
 * @throws \Psy\Exception\ErrorException
 */
function simpleError($message, $filename, $lineno, $severity = 2, $code = 0, $previous = null)
{
    switch ($severity) {
        case 'warning':
            $severity = 2;
            break;
        case 'error':
        default:
            $severity = 1;
    }
    throw new Psy\Exception\ErrorException($message, $code, $severity, $filename, $lineno, $previous);
}

/**
 * 计算系统消耗 初始化标签
 */
function computeSystemResource_init()
{
    global $computeSystemResourceTime;
    $computeSystemResourceTime = microtime(true);;
}

/**
 * 得到系统消耗信息
 * @param bool $isPrint
 * @return array|string
 */
function computeSystemResource_getInfo($isPrint = true)
{
    global $computeSystemResourceTime;

    $nowTime = microtime(true);;

    // 使用的时常和内存消耗
    $useTime = sprintf('%0.4f', $nowTime - $computeSystemResourceTime);
    $useMemory = sprintf('%0.2f', memory_get_usage() / 1024 / 1024);

    if ($isPrint) {
        return " 内存消耗为：{$useMemory}MB，用时：{$useTime} 秒";
    } else {
        return ['memory' => $useMemory, 'time' => $useTime];
    }
}
