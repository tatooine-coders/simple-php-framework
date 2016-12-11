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
    public static function nl(int $tabs = 0, string $content = null, int $nbNl = 1)
    {
        $tabSize = '    ';
        $nl = "\n";

        return str_repeat($tabSize, $tabs) . $content . str_repeat($nl, $nbNl);
    }

    /**
     * Returns a list of $strings suffixed with $suffixes and whitespaces to align
     * them
     *
     * @param array   $strings  List of strings
     * @param array   $suffixes List of suffixes strings
     * @param bool $addSpace If set to true, a space will be added after the
     *                          longest string
     *
     * @return array
     */
    public static function equalizeLength(array $strings, array $suffixes = [], bool $addSpace = false)
    {
        $maxLength = max(array_map('strlen', $strings));

        foreach ($strings as $index => $string) {
            if (isset($suffixes[$index]) || $addSpace) {
                $strings[$index] .= str_repeat(' ', $maxLength-strlen($string)+($addSpace?1:0));
                if (isset($suffixes[$index])) {
                    $strings[$index] .= $suffixes[$index];
                }
            }
        }
        return $strings;
    }
}
