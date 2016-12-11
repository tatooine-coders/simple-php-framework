<?php
namespace TC\Lib;

/**
 * This file is part of the Simple PHP Framework
 *
 * String utilities
 *
 * @category Lib
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class Str
{

    /**
     * List of plural=>singular exceptions
     * @var array
     */
    protected static $pluralExceptions = [
        'people' => 'person',
    ];

    /**
     * Camelizes an underscored string
     *
     * @param string  $str     String to convert
     * @param bool $ucfirst Flag to define if the first letter should be uppercased
     *
     * @return string the camelized string
     */
    public static function camelize(string $str, bool $ucfirst = false)
    {
        $arr = explode('_', $str);
        $out = null;
        foreach ($arr as $s) {
            $out .= ucfirst($s);
        }
        if ($ucfirst) {
            $out = ucfirst($out);
        } else {
            $out = lcfirst($out);
        }
        return $out;
    }

    /**
     * Converts a camelcased string to a underscored one.
     *
     * @param string $str The camelized string
     *
     * @return string The underscored string
     */
    public static function underscore(string $str)
    {
        $arr = preg_split('/(?=[A-Z])/', $str, -1, PREG_SPLIT_NO_EMPTY);
        $out = join('_', $arr);
        return strtolower($out);
    }

    /**
     * Returns a controller classname
     *
     * @param string  $name      Controller name
     * @param bool $namespace Flag to prefix the classname by App\Controller
     *                           namespace
     * @return string
     */
    public static function controllerName(string $name, bool $namespace = false)
    {
        $out = self::camelize($name . '_controller', true);
        if ($namespace) {
            $out = 'App\\Controller\\' . $out;
        }
        return $out;
    }

    /**
     * Returns an entity classname
     *
     * @param string  $table     Table name
     * @param bool $namespace Flag to prefix the classname by App\Model\Entity
     *                           namespace
     *
     * @return string
     */
    public static function entityName(string $table, bool $namespace = false)
    {
        $out = self::camelize(self::singularize($table) . '_entity', true);
        if ($namespace) {
            $out = 'App\\Model\\Entity\\' . $out;
        }

        return $out;
    }

    /**
     * Returns a collection classname
     *
     * @param string  $table     Table name
     * @param bool $namespace Flag to prefix the classname by App\Model\Collection
     *                           namespace
     *
     * @return string
     */
    public static function collectionName(string $table, bool $namespace = false)
    {
        $out = self::camelize(self::pluralize($table) . '_collection', true);
        if ($namespace) {
            $out = 'App\\Model\\Collection\\' . $out;
        }

        return $out;
    }

    /**
     * Returns the lowercase, singular form of a word
     *
     * @param string $str String to convert
     *
     * @return string
     */
    public static function singularize(string $str)
    {
        $str = strtolower($str);
        if (key_exists($str, self::$pluralExceptions)) {
            return self::$pluralExceptions[$str];
        } elseif (strlen($str) > 1) {
            return rtrim($str, "s");
        }
        return $str;
    }

    /**
     * Returns the lowercase, plural form of a word
     *
     * @param string $str String to convert
     *
     * @return string
     */
    public static function pluralize(string $str)
    {
        $str = strtolower($str);
        $key = array_search($str, self::$pluralExceptions);
        if ($key !== false) {
            return $key;
        } elseif (substr($str, -1) != 's' && !key_exists($str, self::$pluralExceptions)) {
            return $str . 's';
        }
        return $str;
    }

    /**
     * Returns a pretty string to be displayed in views: underscores are replaced
     * by spaces
     *
     * @param string $str     Underscored string
     * @param bool   $ucfirst Wether or not to capitalize the first letter
     *
     * @return string
     */
    public static function prettify(string $str, bool $ucfirst = false)
    {
        $out = strtolower(str_replace('_', ' ', $str));
        if ($ucfirst) {
            $out = ucfirst($out);
        }
        return $out;
    }
}
