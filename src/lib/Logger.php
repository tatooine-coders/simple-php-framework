<?php

namespace TC\Lib;
use TC\Lib\Log;

abstract class Logger
{
    protected static $_logger = array();

    /**
     * Get the list of all logs (Array or Object)
     * @param bool $isArray
     * @return array
     */
    public static function getLogger($isArray = false)
    {

        if ($isArray == true) {
            $listeLogArray=array();
            foreach(self::$_logger as $obj_log){

                array_push($listeLogArray, $obj_log->getAll());
            }
            return $listeLogArray;

        }else{
            return self::$_logger;
        }
    }

    /**
     * Add a log in the log list
     * @param $type
     * @param $message
     */
    public static function addLog($type, $message)
    {
        self::$_logger[] = new Log($type, $message);

    }

}
