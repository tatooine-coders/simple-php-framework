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
     * Indentation pattern
     */
    const INDENT = '  ';

    /**
     * Returns a text wrapped in the given color
     *
     * @param string $text  Text to wrap
     * @param string $color Color name from $txtColors
     *
     * @return string
     */
    public static function color(string $text, string $color)
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
        $out = "\n"
            . File::nl(0, '          +----------------------------+')
            . File::nl(0, '          | Welcome to the SPF console |')
            . File::nl(0, '          ==============================')
            . File::nl(0, '          | ~ 2016 ~ Tatooine Coders ~ |')
            . File::nl(0, '          +----------------------------+', 2);
        return self::color($out, 'green');
    }

    /**
     * Returns a red text prefixed with 'Error: '
     *
     * @param string $text Text to return
     *
     * @return string
     */
    public static function error(string $text)
    {
        return self::color($text, 'red');
    }

    /**
     * Returns a blue text prefixed with 'Info: '
     *
     * @param string $text Text to return
     *
     * @return string
     */
    public static function info(string $text)
    {
        return self::color($text, 'blue');
    }

    /**
     * Returns a yellow text prefixed with 'Warning: '
     *
     * @param string $text Text to return
     *
     * @return string
     */
    public static function warning(string $text)
    {
        return self::color($text, 'yellow');
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
            // Views
            . File::nl(2, self::color('views model [view1, view2,...] --force --all', 'light_cyan'))
            . File::nl(3, 'Generates view for the given actions and table model')
            // All
            . File::nl(2, self::color('all [model1, model2,...] --force --all', 'light_cyan'))
            . File::nl(3, 'Generates entities, collections and controllers')
            //--- Begin Help
            . File::nl(1, self::color('- help', 'cyan'))
            . File::nl(3, 'Shows this error message');
        // @codingStandardsIgnoreEnd
    }

    /**
     * Prints an error message and quits
     *
     * @param string $message  Text to display
     * @param bool   $showHelp Set to true to display the help message
     *
     * @return void
     */
    public static function quit(string $message, bool $showHelp = true)
    {
        // Check for longest string in message
        $maxLength = max(array_map('strlen', preg_split('/\n/', $message)));

        // Dispays the string
        echo "\n";
        echo self::error(File::nl(0, str_repeat('-', $maxLength)));
        echo self::error(File::nl(0, $message));
        echo self::error(File::nl(0, str_repeat('-', $maxLength), 2));
        if ($showHelp) {
            echo self::help();
        }
        die;
    }

    /**
     * Creates an underlined title
     *
     * @param string $name      Title
     * @param string $underline Character used
     *
     * @return string
     */
    public static function title(string $name, string $underline = '-')
    {
        return "\n"
            . File::nl(0, self::color($name, 'blue'))
            . File::nl(0, self::color(str_repeat($underline, strlen($name)), 'blue'));
    }

    /**
     * Indents a text with a given depth
     *
     * @param string|array $text  Text with line breaks or array of lines
     * @param int      $depth Indentation depth
     *
     * @return string
     */
    public static function indent(string $text, int $depth = 0)
    {
        if (!is_array($text)) {
            $text = preg_split('/\n/', $text);
        }
        foreach ($text as $k => $v) {
            $text[$k] = str_repeat(self::INDENT, $depth) . $v;
        }
        return implode("\n", $text);
    }
}
