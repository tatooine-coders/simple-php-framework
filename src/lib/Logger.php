<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/11/2016
 * Time: 12:56
 */
abstract class Logger
{
    protected static $_logger = array();


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

    public static function addLog($type, $message)
    {

        self::$_logger[] = new Log($type, $message);

    }

}