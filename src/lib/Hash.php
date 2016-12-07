<?php
namespace TC\lib;
/**
 * This file is part of the Simple PHP Framework
 *
 * Hasher
 *
 * @category Library
 * @package  TC
 * @author   Alexandre Daspe <alexandre.daspe@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
abstract class Hash
{
    /**
     * @param array $array
     * @param string $path
     * @return array|null
     */
    public static function get(array $array, $path)
    {
        $pathHash = explode('.', $path);
        foreach ($pathHash as $chunk) {
            if (isset($array[$chunk])) {
                $array = $array[$chunk];
            } else {
                return null;
            }
        }
        return $array;
    }

    /**
     * @param array $array
     * @param string $path
     * @param string $value
     * @param bool $overwrite
     * @return array
     */
    public static function set($array, $path, $value, $overwrite = true)
    {
        $cheminHash = explode('.', $path);
        $newPath = self::createPath($cheminHash, $value);
        if ($overwrite) {
            return array_merge($array, $newPath);
        } else {
            return array_merge_recursive($array, $newPath);
        }

    }

    /**
     * @param array $path
     * @param $value
     * @return array
     */
    protected static function createPath(Array $path, $value)
    {
        $out = [];
        if (count($path) === 0) {
            return $value;
        } else {
            $chunk = array_shift($path);
            $out[$chunk] = self::createPath($path, $value);
        }
        return $out;
    }
}