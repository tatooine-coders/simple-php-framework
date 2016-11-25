<?php
/**
 * Database class
 */ 
class Database {
    private static $_dbName = '' ;
    private static $_dbHost = 'localhost' ;
    private static $_dbUsername = 'root';
    private static $_dbUserPassword = '1511';
     
    private static $_db  = null;
     
    public function __construct() {
        die('Init function is not allowed');
    }
     
    public static function connect() {
       // One connection through whole application
        if ( null == self::$_db ) {     
            try {
                self::$_db =  new PDO( "mysql:host=".self::$_dbHost.";"."dbname=".self::$_dbName, self::$_dbUsername, self::$_dbUserPassword);
                self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                die($e->getMessage()); 
            }
        }
       return self::$_db;
    }
     
    public static function disconnect() {
        self::$_db = null;
    }

    public function setDbName($dbName) {
        self::$_dbName = $dbName;
    }
}
?>