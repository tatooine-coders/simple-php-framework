<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/11/2016
 * Time: 12:56
 */
class Logger
{
    protected static $_logger = array();


    public static function getLogger()
    {
        return self::$_logger;
    }

    public function AddLog(array $log)
    {
        array_push(self::$_logger, $log);
    }
}