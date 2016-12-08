<?php
namespace TC\Lib;

use TC\Lib\File;

/**
 * Description of Console
 *
 * @author mtancoigne
 */
class Console
{

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

    public static function color($text, $color)
    {
        return self::$txtColors[$color] . $text . self::$txtColors['default'];
    }

    public static function greeter()
    {
        return self::color(File::nl(0, 'Welcome to the SPF console')
                . File::nl(0, '==========================')
                . File::nl(0, '~ 2016 ~ Tatooine Coders ~', 2), 'green');
    }

    public static function error($text)
    {
        return self::color('Error: ' . $text, 'red');
    }

    public static function info($text)
    {
        return self::color('Info: ' . $text, 'blue');
    }

    public static function warning($text)
    {
        return self::color('Warning: ' . $text, 'yellow');
    }

    public static function help()
    {
        return File::nl(0, 'Help:')
            . File::nl(0, '=====')
            . File::nl(1, '- generate:')
            . File::nl(2, 'models [all|collections|entities] [model1, model2,...] --force --all')
            . File::nl(3, 'Generates collections and entities')
            . File::nl(1, '- help')
            . File::nl(3, 'Shows this error message');
    }
}
