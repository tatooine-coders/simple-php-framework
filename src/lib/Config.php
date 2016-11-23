<?php

/**
 * Configuration utility.
 *
 * this class should always be called statically
 */
class Config
{

    /**
     * Configuration array
     * @var array mixed
     */
    static protected $_config = [];

    // getters and setters below
    public static function load()
    {
        self::$_config = require_once ('config.php');
    }

    public static function get($param){
        if(isset(self::$_config[$param])){
            return self::$_config[$param];
        }else{
            return null;
        }
    }


}
