<?php
namespace TC\Model;

use TC\Lib\Config;

/**
 * Database class
 */
class Database
{

    private static $_dbName = '';
    private static $_dbHost = '';
    private static $_dbUsername = '';
    private static $_dbUserPassword = '';
    private static $_dbPort = null;
    private static $_db = null;

    public function __construct()
    {
        die('Init function is not allowed');
    }

    public static function connect()
    {
        $dbConfig = Config::get('db');
        self::$_dbName = $dbConfig['name'];
        self::$_dbHost = $dbConfig['host'];
        self::$_dbUsername = $dbConfig['user'];
        self::$_dbUserPassword = $dbConfig['pass'];
        self::$_dbPort = $dbConfig['port'];

        if (!is_null(self::$_dbPort)) {
            $portString = ';port=' . self::$_dbPort;
        }

        // One connection through whole application
        if (null == self::$_db) {
            try {
                self::$_db = new PDO("mysql:host=" . self::$_dbHost . ";" . "dbname=" . self::$_dbName . $portString, self::$_dbUsername, self::$_dbUserPassword);
                self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        return self::$_db;
    }

    public static function disconnect()
    {
        self::$_db = null;
    }
}

?>