<?php

namespace Jw\Support\Tool;

use Jw\Support\Exceptions\InvalidArgumentException;

class StringTool
{

    const UTF8_CHINESE_PATTERN = '/[\x{4e00}-\x{9fff}\x{f900}-\x{faff}]/u';
    const UTF8_SYMBOL_PATTERN = '/[\x{ff00}-\x{ffef}\x{2000}-\x{206F}]/u';

    /** count only chinese words
     * @param string $str
     * @return int
     */
    public static function str_utf8_chinese_word_count($str = "")
    {
        $str = preg_replace(self::UTF8_SYMBOL_PATTERN, "", $str);
        return preg_match_all(self::UTF8_CHINESE_PATTERN, $str, $arr);
    }

    /** count both chinese and english
     * @param string $str
     * @return int
     */
    public static function str_utf8_mix_word_count($str = "")
    {
        $str = preg_replace(self::UTF8_SYMBOL_PATTERN, "", $str);
        return self::str_utf8_chinese_word_count($str) + str_word_count(preg_replace(self::UTF8_CHINESE_PATTERN, "", $str));
    }

    /**
     * 不同的类型的变量对应的视觉代码
     * @param $param
     * @return string
     * @throws InvalidArgumentException
     * @Author jiaWen.chen
     */
    public static function valueView($param)
    {
        if (is_string($param)) {
            return '"' . $param . '"';
        } elseif (is_bool($param)) {
            return $param ? 'true' : 'false';
        } elseif (is_null($param)) {
            return 'null';
        } elseif (is_numeric($param)) {
            return $param;
        }

        throw new InvalidArgumentException('参数格式异常，只接受标量');
    }
}