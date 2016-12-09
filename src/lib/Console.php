<?php
namespace TC\Lib;

use TC\Lib\File;

/**
 * This file is part of the Simple PHP Framework
 *
 * Console utilities
 *
 * @category Lib
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class Console
{

    /**
     * List of ANSI color codes to use for text display
     * @var array
     */
    protected static $txtColors = [
        "default" => "\e[39m",
        "black" => "\e[30m",
        "red" => "\e[31m",
        "green" => "\e[32m",
        "yellow" => "\e[33m",
        "blue" => "\e[34m",
        "magenta" => "\e[35m",
        "cyan" => "\e[36m",
        "white" => "\e[97m",
        "light_gray" => "\e[37m",
        "dark_gray" => "\e[90m",
        "light_red" => "\e[91m",
        "light_green" => "\e[92m",
        "light_yellow" => "\e[93m",
        "light_blue" => "\e[94m",
        "light_magenta" => "\e[95m",
        "light_cyan" => "\e[96m",
    ];

    /**
     * Returns a text wrapped in the given color
     *
     * @param string $text  Text to wrap
     * @param string $color Color name from $txtColors
     *
     * @return string
     */
    public static function color($text, $color)
    {
        return self::$txtColors[$color] . $text . self::$txtColors['default'];
    }

    /**
     * Returns a console header
     *
     * @return string
     */
    public static function greeter()
    {
        $out = File::nl(0, 'Welcome to the SPF console')
            . File::nl(0, '==========================')
            . File::nl(0, '~ 2016 ~ Tatooine Coders ~', 2);
        return self::color($out, 'green');
    }

    /**
     * Returns a red text prefixed with 'Error: '
     *
     * @param string $text Text to return
     *
     * @return string
     */
    public static function error($text)
    {
        return self::color('Error: ' . $text, 'red');
    }

    /**
     * Returns a blue text prefixed with 'Info: '
     *
     * @param string $text Text to return
     *
     * @return string
     */
    public static function info($text)
    {
        return self::color('Info: ' . $text, 'blue');
    }

    /**
     * Returns a yellow text prefixed with 'Warning: '
     *
     * @param string $text Text to return
     *
     * @return string
     */
    public static function warning($text)
    {
        return self::color('Warning: ' . $text, 'yellow');
    }

    /**
     * Displays a help message
     *
     * @return string
     */
    public static function help()
    {
        // Coding standards are voluntarily ignored on the help message as lines
        // are often longer than 120 chars
        //
        // @codingStandardsIgnoreStart
        return File::nl(0, 'Help:')
            . File::nl(0, '=====')
            //--- Begin Generators
            . File::nl(1, self::color('- generate:', 'cyan'))
            //Models
            . File::nl(2, self::color('models [all|collections|entities] [table1, table2,...] --force --all', 'light_cyan'))
            . File::nl(3, 'Generates collections and entities')
            // Controllers
            . File::nl(2, self::color('controllers [model1, model2,...] --force --all', 'light_cyan'))
            . File::nl(3, 'Generates controllers')
            // All
            . File::nl(2, self::color('all [model1, model2,...] --force --all', 'light_cyan'))
            . File::nl(3, 'Generates entities, collections and controllers')
            //--- Begin Help
            . File::nl(1, self::color('- help', 'cyan'))
            . File::nl(3, 'Shows this error message');
        // @codingStandardsIgnoreEnd
    }
}
