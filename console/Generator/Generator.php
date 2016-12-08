<?php
namespace TC\Console\Generator;

use TC\Lib\Config;

/**
 * This file is part of the Simple PHP Framework
 *
 * Model generator
 *
 * This should be broken in multiple files/classes to be cleaner
 *
 * @category Generators
 * @package  TC
 * @author   Hamid Kellali <hamid.kellali@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class Generator
{

    /**
     * List of flags like --force
     * @var array
     */
    protected static $_flags = [];

    /**
     * List of parameters
     * @var array
     */
    protected static $_parameters = [];

    /**
     * Initializes the class parameters and flags
     *
     * @param array $parameters List of parameters
     * @param array $flags      List of flags
     *
     * @return void
     */
    public static function init($parameters, $flags)
    {
        foreach ($flags as $flag) {
            if (key_exists($flag, static::$_flags)) {
                static::$_flags[$flag] = !static::$_flags[$flag];
            } else {
                echo "Note: Unknown flag $flag. This will be ignored\n";
            }
        }

        static::$_parameters = $parameters;
    }
}
