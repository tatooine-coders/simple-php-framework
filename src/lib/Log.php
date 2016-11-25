<?php
namespace TC\Lib;

/**
 * This file is part of the Simple PHP Framework
 *
 * Log object
 *
 * @category Library
 * @package  TC
 * @author   Alexandre Daspe <alexandre.daspe@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class Log
{

    /**
     * Short string to define the message type
     * @var string
     */
    protected $_type;

    /**
     * The log message
     * @var type
     */
    protected $_message;

    /**
     * Time at wich the message has been added to the log
     * @var integer
     */
    protected $_date;

    /**
     * Log constructor.
     *
     * @param string $type    Message type
     * @param string $message Message to log
     *
     * @return void
     */
    public function __construct($type, $message)
    {
        $this->_type = $type;
        $this->_message = $message;
        $this->_date = time();
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * Get date
     *
     * @return integer
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * Get all atributes in an array
     *
     * @return array
     */
    public function getAll()
    {
        $log = ['type' => $this->getType(), 'message' => $this->getMessage(), 'date' => $this->getDate()];

        return $log;
    }
}
