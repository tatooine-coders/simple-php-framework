<?php
namespace TC\Lib;

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
class DB
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
     * @return void
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
                    . "dbname=" . self::$_dbName . $portString, self::$_dbUsername, self::$_dbUserPassword
                );
                self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
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

    /**
     * Returns the connection object
     * 
     * @return PDO PDO object
     */
    public static function c()
    {
        if (is_null(self::$_db)) {
            self::connect();
        }

        return self::$_db;
    }

    public static function getTablesNames() {
        $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='" . Config::get('db')['name'] . "'";
        $statement = DB::c()->prepare($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_NUM);
        $tables = [];
        while (!empty($row = $statement->fetch())) {
            $tables[] = $row[0];
        }
        return $tables;
    }

    public static function getTablesColumns($tables) {
        $tab = [];
        foreach ($tables as $table) {
            $tab[$table] = self::getTableColumns($table);
        }
        return $tab;
    }

    public static function getTableColumns($table) {
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS "
            . "WHERE TABLE_SCHEMA = '" . Config::get('db')['name'] . "' AND TABLE_NAME = '" 
            . $table . "'";
        $statement = DB::c()->prepare($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_NUM);
        $tableColumns = [];
        while (!empty($row = $statement->fetch())) {
            $tableColumns[] = $row[0];
        }

        return $tableColumns;
    }
}
