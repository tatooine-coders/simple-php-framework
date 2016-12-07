<?php
namespace TC\Lib;

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
     * Returns a value of an array for the given path
     *
     * @param array  $array Array in wich to search
     * @param string $path  Path like 'some.key.to.get'
     *
     * @return mixed|null Returns null if key does not exists
     */
    public static function getValue(array $array, $path)
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
     * Sets a value in the given array
     *
     * @param array  $array     Target array
     * @param string $path      Path like 'some.key.to.set'
     * @param mixed  $value     Value to set
     * @param bool   $overwrite Flag to averwrite existing content or to append new values instead
     *                          values instead
     *
     * @return array The new array
     */
    public static function setValue($array, $path, $value, $overwrite = true)
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
     * Creates a path of multidimensionnal arrays and assign a value to it
     *
     * @param array $path  Array on wich to perform the
     * @param mixed $value Value to assign
     *
     * @return array The new array
     */
    protected static function createPath(array $path, $value)
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
