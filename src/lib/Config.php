<?php
namespace TC\Lib;

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
class Config
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
        if (isset(self::$_config[$param])) {
            return self::$_config[$param];
        } else {
            return null;
        }
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
