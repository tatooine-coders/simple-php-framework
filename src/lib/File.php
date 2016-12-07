<?php
namespace TC\Lib;

/**
 * This file is part of the Simple PHP Framework
 *
 * File handler
 *
 * @category Library
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class File
{

    /**
     * Creates a line of text indented with a given number of tabs and followed
     * by a given number of ne lines
     *
     * @param integer $tabs    Number of indents for the line
     * @param string  $content Line content
     * @param integer $nbNl    Number of new lines after this line
     *
     * @return string a line of text
     */
    public static function nl($tabs = 0, $content = null, $nbNl = 1)
    {
        $tabSize = '    ';
        $nl = "\n";

        return str_repeat($tabSize, $tabs) . $content . str_repeat($nl, $nbNl);
    }

    public static function equalizeLength(Array $strings, Array $suffixes = [], $addSpace = true) {
        $maxLength = max(array_map('strlen', $strings));

        foreach ($strings as $k=>$string) {
            $strings[$k] .= str_repeat(' ', $maxLength-strlen($string)+($addSpace?1:0));
            if(isset($suffixes[$k]))
            {
                $strings[$k] .= $suffixes[$k];
            }
        }

        var_dump($strings);
        return $strings;
    }
}
