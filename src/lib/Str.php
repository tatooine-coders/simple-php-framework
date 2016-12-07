<?php
namespace TC\Lib;

/**
 * Description of Str
 *
 * @author mtancoigne
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
     * @param boolean $ucfirst Flag to define if the first letter should be uppercased
     *
     * @return string the camelized string
     */
    public static function camelize($str, $ucfirst = false)
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
    public static function underscore($str)
    {
        $arr = preg_split('/(?=[A-Z])/', $str, -1, PREG_SPLIT_NO_EMPTY);
        $out = join('_', $arr);
        return strtolower($out);
    }

    /**
     * Returns a controller classname
     *
     * @param string  $table     Table name
     * @param boolean $namespace Flag to prefix the classname by App\Controller
     *                           namespace
     * @return string
     */
    public static function controllerName($table, $namespace = false)
    {
        $out = self::camelize(self::pluralize($table) . '_controller', true);
        if ($namespace) {
            $out = 'App\\Controller\\' . $out;
        }
        return $out;
    }

    /**
     * Returns an entity classname
     *
     * @param string  $table     Table name
     * @param boolean $namespace Flag to prefix the classname by App\Model\Entity
     *                           namespace
     *
     * @return string
     */
    public static function entityName($table, $namespace = false)
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
     * @param boolean $namespace Flag to prefix the classname by App\Model\Collection
     *                           namespace
     *
     * @return string
     */
    public static function collectionName($table, $namespace = false)
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
    public static function singularize($str)
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
    public static function pluralize($str)
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
}
