<?php
namespace TC\Lib;

use TC\Lib\Log;

/**
 * This file is part of the Simple PHP Framework
 *
 * Simple log class used during development
 *
 * @category Library
 * @package  TC
 * @author   Alexandre Daspe <alexandre.daspe@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
abstract class Logger
{

    /**
     * Array of Log objects
     * @var array
     */
    protected static $_logger = [];

    /**
     * Get the list of all logs (Array or Object)
     *
     * @param bool $isArray If set to true, returns an array of arrays instead
     * of an array of Log
     *
     * @return array
     */
    public static function getLogger($isArray = false)
    {

        if ($isArray == true) {
            $listeLogArray = [];
            foreach (self::$_logger as $obj_log) {
                array_push($listeLogArray, $obj_log->getAll());
            }
            return $listeLogArray;
        } else {
            return self::$_logger;
        }
    }

    /**
     * Add a log in the log list
     *
     * @param string $type    Message type
     * @param string $message Message
     *
     * @return void
     */
    public static function addLog($type, $message)
    {
        self::$_logger[] = new Log($type, $message);
    }
}
