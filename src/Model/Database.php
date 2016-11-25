<?php

namespace TC\Model;

use PDO;
use TC\Lib\Config;

/**
 * This file is part of the Simple PHP Framework
 *
 * Database connection manager
 *
 * @category Models
 * @package  TC
 * @author   Hamid Kellali <hamid.kellali@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class Database
{

    /**
     * Database name
     *
     * @var string
     */
    private static $_dbName = '';

    /**
     * Database server
     *
     * @var string
     */
    private static $_dbHost = '';

    /**
     * Username to connect to server
     *
     * @var string
     */
    private static $_dbUsername = '';

    /**
     * User password
     *
     * @var string
     */
    private static $_dbUserPassword = '';

    /**
     * Database port
     *
     * @var integer
     */
    private static $_dbPort = null;

    /**
     * Connection
     *
     * @var PDO
     */
    private static $_db = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        die('Init function is not allowed');
    }

    /**
     * Connects to the database and create the $_db object
     *
     * @return PDO PDO object
     */
    public static function connect()
    {
        $dbConfig = Config::get('db');
        self::$_dbName = $dbConfig['name'];
        self::$_dbHost = $dbConfig['host'];
        self::$_dbUsername = $dbConfig['user'];
        self::$_dbUserPassword = $dbConfig['pass'];
        self::$_dbPort = $dbConfig['port'];

        $portString = null;
        if (!is_null(self::$_dbPort)) {
            $portString = ';port=' . self::$_dbPort;
        }

        // One connection through whole application
        if (null == self::$_db) {
            try {
                self::$_db = new PDO(
                    "mysql:host=" . self::$_dbHost . ";"
                    . "dbname=" . self::$_dbName . $portString,
                    self::$_dbUsername,
                    self::$_dbUserPassword
                );
                self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        return self::$_db;
    }

    /**
     * Closes the connection
     *
     * @return void
     */
    public static function disconnect()
    {
        self::$_db = null;
    }
}
