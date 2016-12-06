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
     * Camelizes an underscored string
     * 
     * @param string $str String to convert
     * @param boolean $ucfirst Flag to define if the first letter should be uppercased
     * 
     * @return string the camelized string
     */
    static public function camelize($str, $ucfirst = false)
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
    static public function underscore($str)
    {
        $arr = preg_split('/(?=[A-Z])/', $str, -1, PREG_SPLIT_NO_EMPTY);
        $out = join('_', $arr);
        return strtolower($out);
    }

    /**
     * Returns a controller classname
     * 
     * @param string $table Table name
     * @param boolean $namespace Flag to prefix the classname by App\Controller 
     *                           namespace
     * @return string
     */
    static public function controllerName($table, $namespace = false)
    {
        $out = self::camelize($table . '_controller', true);
        if ($namespace) {
            $out = 'App\\Controller\\' . $out;
        }
        return $out;
    }

    /**
     * Returns an entity classname
     * 
     * @param string $table Table name
     * @param boolean $namespace Flag to prefix the classname by App\Model\Entity 
     *                           namespace
     * 
     * @return string
     */
    static public function entityName($table, $namespace = false)
    {
        $out = self::camelize($table . '_entity', true);
        if ($namespace) {
            $out = 'App\\Model\\Entity\\' . $out;
        }

        return $out;
    }

    /**
     * Returns a collection classname
     * 
     * @param string $table Table name
     * @param boolean $namespace Flag to prefix the classname by App\Model\Collection 
     *                           namespace
     * 
     * @return string
     */
    static public function collectionName($table, $namespace = false)
    {
        $out = self::camelize($table . '_collection', true);
        if ($namespace) {
            $out = 'App\\Model\\Collection\\' . $out;
        }

        return $out;
    }
}
