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

    /**
     * Log constructor.
     * @param $type
     * @param $message
     */
    public function __construct($type, $message)
    {
        $this->_type = $type;
        $this->_message = $message;
        $this->_date = time();
    }

    /**
     * Get type
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get message
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * get date
     * @return int
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * get all atributes in an array
     * @return array
     */
    public function getAll(){

        $log = ['type' => $this->getType(),'message' => $this->getMessage(),'date' => $this->getDate()];
        return $log;
    }

}