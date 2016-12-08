<?php
namespace TC\Lib;

use TC\Lib\Hash;

/**
 * This file is part of the Simple PHP Framework
 *
 * Configuration handler
 *
 * @category Library
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class Config extends Hash
{

    /**
     * Configuration array
     * @var array mixed
     */
    static protected $_config = [];

    /**
     * Default constructor
     *
     * @return void
     */
    public function __construct()
    {
        die('This class should never be instanciated. Please call it statically');
    }

    /**
     * Loads the configuration file
     *
     * @param string $file File to load
     *
     * @return void
     */
    public static function load($file)
    {
        if (file_exists($file)) {
            self::$_config += require_once($file);
        } else {
            die('File ' . $file . ' does not exist');
        }
    }

    /**
     * Returns the value associated to the passed key
     *
     * @param string $param The key to get.
     *
     * @return mixed
     */
    public static function get($param)
    {
        return parent::getValue(self::$_config, $param);
    }

    /**
     * Overwrites a configuration value
     *
     * @param string $path  Path of keys like 'some.key.to.set'
     * @param mixed  $value New value to assign
     * @return void
     */
    public static function set($path, $value)
    {
        return parent::setValue(self::$_config, $path, $value);
    }

    /**
     * Merges new parameters to the array of current params.
     *
     * @param array $newparams New parameters to merge with the current ones
     *
     * @return void
     */
    public static function merge($newparams)
    {
        self::$_config += $newparams;
    }
}
