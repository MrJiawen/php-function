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
    public static function put(string $file, string $content): int
    {
        if (!self::exists(dirname($file))) {
            self::mkDir(dirname($file));
        }
        return file_put_contents($file, $content);
    }

    /**
     * 创建一个超级追加写
     * @param string $file
     * @param string $content
     * @return int
     * @Author jiaWen.chen
     */
    public static function append(string $file, string $content): int
    {
        if (!self::exists(dirname($file))) {
            self::mkDir(dirname($file));
        }
        return file_put_contents($file, $content, FILE_APPEND);
    }

    /**
     * 获取一个目录下的文件 (不包括文件夹)
     * @param string $path
     * @param string|null $filter
     * @return array
     * @Author jiaWen.chen
     */
    public static function getFiles(string $path, string $filter = null): array
    {
        if (!self::exists($path)) {
            return [];
        }
        $files = array_filter(scandir($path), function ($item) use ($path, $filter) {
            if ($item == '.' || $item == '..' || is_dir($path . '/' . $item)) {
                return false;
            }
            if (!empty($filter) && (pathinfo($item)['extension'] != trim($filter, '.'))) {
                return false;
            }
            return true;
        });

        $files = array_map(function ($item) use ($path) {
            return $path . '/' . $item;
        }, $files);

        return $files;
    }

    /**
     * 获取一个目录下的所有的文件递归 (不包括文件夹)
     * @param string $path
     * @param string|null $filter
     * @return array
     * @Author jiaWen.chen
     */
    public static function getAllFiles(string $path, string $filter = null): array
    {
        if (!self::exists($path)) {
            return [];
        }
        $files = [];
        array_map(function ($item) use ($path, $filter, &$files) {
            if ($item == '.' || $item == '..') {
                return false;
            }
            if (is_dir($path . '/' . $item)) {
                $files = array_merge($files, self::getAllFiles($path . '/' . $item, $filter));
                return true;
            }
            if (!empty($filter) && (pathinfo($item)['extension'] != trim($filter, '.'))) {

                return false;
            }
            $files[] = $path . '/' . $item;
            return true;
        }, scandir($path));

        return $files;
    }
}

