<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/11/2016
 * Time: 14:12
 */
class Log
{
    protected $_type;
    protected $_message;
    protected $_date;

    private function __construct($type, $message)
    {
        $this->_type = $type;
        $this->_message = $message;
        $this->_date = time();
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getMessage()
    {
        return $this->_message;
    }

    public function getDate()
    {
        return $this->_date;
    }
    public function getAll(){

        $log = ['type' => $this->getType(),'message' => $this->getMessage(),'date' => $this->getDate()];
        return $log;
    }
}