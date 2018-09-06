<?php

namespace Jw\Support\Tool;

class FileTool
{
    /**
     * 查看一个目录或者一个文件是否存在
     * @param string $path
     * @return bool
     * @Author jiaWen.chen
     */
    public static function exists(string $path)
    {
        return file_exists($path);
    }

    /**
     * 递归创建一个文件夹
     * @param string $path
     * @Author jiaWen.chen
     */
    public static function mkDir(string $path)
    {
        $dirArray = explode('/', $path);
        $path = '/';
        foreach ($dirArray as $item) {
            $path .= '/' . $item;
            if (!self::exists($path)) {
                mkdir($path);
            }
        }
    }

    /**
     * 创建一个超级写
     * @param string $file
     * @param string $content
     * @return int
     * @Author jiaWen.chen
     */
    public static function put(string $file,string $content):int
    {
        if(!self::exists(dirname($file))){
            self::mkDir(dirname($file));
        }
       return file_put_contents($file,$content);
    }

    /**
     * 创建一个超级追加写
     * @param string $file
     * @param string $content
     * @return int
     * @Author jiaWen.chen
     */
    public static function append(string $file,string $content):int
    {
        if(!self::exists(dirname($file))){
            self::mkDir(dirname($file));
        }
        return file_put_contents($file,$content,FILE_APPEND);
    }
}

