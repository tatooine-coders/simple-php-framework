<?php
/**
 * This file is part of the Simple PHP Framework
 *
 * Model generator
 *
 * This should be broken in multiple files/classes to be cleaner
 *
 * @category Generators
 * @package  TC
 * @author   Hamid Kellali <hamid.kellali@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
$force = (isset($argv[1]) && $argv[1] == 'force');
require_once 'vendor/autoload.php';

use TC\Model\Database;
use TC\Lib\Config;
use TC\Lib\File;

Config::load('config.php');
$allAttributes = [];
$db = Database::connect();
$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='" . Config::get('db')['name'] . "'";
$statement = $db->prepare($query);
$statement->execute();
$statement->setFetchMode(PDO::FETCH_NUM);
$tables = [];
$row = $statement->fetch();
do {
    $table = $row[0];
    $tables[] = $table;
} while (!empty($row = $statement->fetch()));

$index = 0;
$folder = 'app/Model/';

foreach ($tables as $table) {
    $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS "
        . "WHERE TABLE_SCHEMA = '" . Config::get('db')['name'] . "' AND TABLE_NAME = '" . $table . "'";
    $statement = $db->prepare($query);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_NUM);
    $row = $statement->fetch();
    $tableAttributes = [];
    do {
        $tableAttributes[] = $row[0];
    } while (!empty($row = $statement->fetch()));
    $allAttributes[$table] = $tableAttributes;
}
$tabulation = "    ";

//browse all attributes
foreach ($allAttributes as $table => $attributes) {
    $file = $folder . ucfirst($table) . 'Model.php';
    if (!file_exists($file) || $force) {
        /*
         * Create the class declaration
         */
        $current = File::nl(0, '<?php', 1)
            . File::nl(0, 'namespace App\Model;', 2)
            . File::nl(0, 'use TC\Model;', 2)
            . File::nl(0, 'class ' . ucfirst($table) . 'Model extends Model{', 1);

        /*
         * Create the attributes
         */
        foreach ($attributes as $attribute) {
            $current .= File::nl(1, '/**', 1)
                . File::nl(1, ' * @var <type> $_' . $attribute . '<description>', 1)
                . File::nl(1, ' */', 1)
                . File::nl(1, 'protected $_' . $attribute . ';', 2);
        }

        /*
         * Create the "getList" method
         */
        $setters = null;
        foreach ($attributes as $attribute) {
            $setters .= File::nl(3, '$object->set' . ucfirst($attribute) . '($row->' . $attribute . ');', 1);
        }
        $current .= File::nl(1, '/**', 1)
            . File::nl(1, ' * Base getter that returns all the objects', 1)
            . File::nl(1, ' * ', 1)
            . File::nl(1, ' * @return array An array of objects', 1)
            . File::nl(1, ' */', 1)
            . File::nl(1, 'public static function getList(PDO $db) {', 1)
            . File::nl(2, '$query = \'SELECT * FROM ' . $table . '\';', 1)
            . File::nl(2, '$statement = $db->prepare($query);', 1)
            . File::nl(2, '$statement->execute();', 1)
            . File::nl(2, '$statement->setFetchMode(PDO::FETCH_OBJ);', 1)
            . File::nl(2, '$' . $table . ' = array();', 1)
            . File::nl(2, '$row = $statement->fetch();', 2)
            . File::nl(2, 'do {', 1)
            . File::nl(3, '$object = new ' . ucfirst($table) . 'Model();', 1)
            . $setters
            . File::nl(3, '$' . $table . '[] = $object;', 1)
            . File::nl(2, '} while (!empty($row = $statement->fetch()));', 2)
            . File::nl(2, 'return ($' . $table . ');', 1)
            . File::nl(1, '}', 2)
            . File::nl(2, '', 1);

        /*
         * Create the "getObject" method
         */
        $attributesForGetObj = null;
        foreach ($attributes as $attribute) {
            $str = '$object->set' . ucfirst($attribute) . '($row->' . $attribute . ');';
            $attributesForGetObj .= File::nl(3, $str, 1);
        }
        $current .= File::nl(1, '/**', 1)
            . File::nl(1, ' * Returns an entity with the given id', 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @param integer $id The id to fetch', 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @return '. ucfirst($table).'Model The object', 1)
            . File::nl(1, ' */', 1)
            . File::nl(1, 'public static function getObject(PDO $db, $id) {', 1)
            . File::nl(2, '$query = "SELECT * FROM `' . $table . '` WHERE id = :id";', 1)
            . File::nl(2, '$statement = $db->prepare($query);', 1)
            . File::nl(2, '$statement->bindattribute(\'id\', $id, PDO::PARAM_INT);', 1)
            . File::nl(2, '$statement->execute();', 1)
            . File::nl(2, '$statement->setFetchMode(PDO::FETCH_OBJ);', 1)
            . File::nl(2, '$object = null;', 1)
            . File::nl(2, 'if (!empty($row = $statement->fetch())) {', 1)
            . File::nl(3, '$object = new ' . ucfirst($table) . 'Model();', 1)
            . $attributesForGetObj
            . File::nl(2, '}', 2)
            . File::nl(2, 'return ($object);', 1)
            . File::nl(1, '}', 2);

        /*
         * Create the constructor
         */
        $paramList = [];
        $hydratation = null;
        // Preparing hydratation and attribute list
        foreach ($attributes as $attribute) {
            $paramList[] = '$' . $attribute;
            $hydratation .= File::nl(2, '$this' . "->set" . ucfirst($attribute) . '($' . $attribute . ');', 1);
        }

        $current .= File::nl(1, 'public function __construct(' . implode(',', $paramList) . ') {', 1)
            . $hydratation
            . File::nl(1, '}', 2);

        /*
         * Create the "exists" method
         */

        $current .= File::nl(1, 'public function exists(PDO $db) {', 1)
            . File::nl(2, '$query = "SELECT * FROM ' . $table . ' WHERE id = :id LIMIT 1";', 1)
            . File::nl(2, '$statement = $db->prepare($query);', 1)
            . File::nl(2, '$statement->bindattribute(' . "'id'" . ', $this->getId(), PDO::PARAM_INT);', 1)
            . File::nl(2, '$statement->execute();', 1)
            . File::nl(2, '$statement->setFetchMode(PDO::FETCH_OBJ);', 2)
            . File::nl(2, 'return ($statement);', 1)
            . File::nl(1, '}', 2);


        $setAttributes = null; // used in "load"
        $bindAttributes = null; // used in "add" and "update"
        $attributesForUpdate = []; // used in "update"
        foreach ($attributes as $attribute) {
            $setAttributes .= File::nl(3, '$this->set' . ucfirst($attribute) . '($row->' . $attribute . ');', 1);
            $str = '$statement->bindattribute(\''
                . $attribute . '\', $this->get'
                . ucfirst($attribute)
                . '(), PDO::PARAM_STR);';
            $bindAttributes .= File::nl(3, $str, 1);
            $attributesForUpdate[] = File::nl(4, $attribute . ' = :' . $attribute, 0);
        }

        /*
         * Create the "load" method
         */
        $current .= File::nl(1, 'public function load(PDO $db) {', 1)
            . File::nl(2, '$statement = $this->exists($db);', 1)
            . File::nl(2, 'if (!empty($row = $statement->fetch())){', 1)
            . $setAttributes
            . File::nl(2, '}', 1)
            . File::nl(1, '}', 2);

        /*
         * Create the "add" method
         */
        $str = '$query = "INSERT INTO ' . $table
            . ' (' . implode(", ", $attributes) . ') '
            . 'attributeS (:' . implode(", :", $attributes) . ')";';
        $current .= File::nl(1, 'public function add(PDO $db) {', 1)
            . File::nl(2, '$statement = $this->exists($db);', 1)
            . File::nl(2, 'if (empty($row = $statement->fetch())) {', 1)
            . File::nl(3, $str, 1)
            . File::nl(2, '$statement=$db->prepare($query);', 1)
            . $bindAttributes
            . File::nl(2, '}', 1)
            . File::nl(1, '}', 2);

        /*
         * Create the "update" method
         */
        $current .= File::nl(1, 'public function update(PDO $db) {', 1)
            . File::nl(2, '$statement = $this->exists($db);', 1)
            . File::nl(2, 'if (empty($row = $statement->fetch())){', 1)
            . File::nl(3, '$query = "UPDATE ' . $table . ' SET ', 1)
            . implode(", \n", $attributesForUpdate) . "\n"
            . File::nl(3, 'WHERE id = :id";', 1)
            . File::nl(3, '$statement=$db->prepare($query);', 1)
            . $bindAttributes
            . File::nl(3, '$statement->execute();', 1)
            . File::nl(2, '}', 1)
            . File::nl(1, '}', 2);

        /*
         * Create the "delete" method
         */
        $current .= File::nl(1, 'public function delete(PDO $db) {', 1)
            . File::nl(2, '$statement = $this->exists($db);', 1)
            . File::nl(2, 'if (!empty($row = $statement->fetch())){', 1)
            . File::nl(3, '$query = "DELETE FROM ' . $table . ' WHERE id = :id";', 1)
            . File::nl(3, '$statement=$db->prepare($query);', 1)
            . File::nl(3, '$statement->bindattribute(\'id\', $this->getId(), PDO::PARAM_STR);', 1)
            . File::nl(3, '$statement->execute();', 1)
            . File::nl(2, '}', 1)
            . File::nl(1, '}', 2);



        /*
         * Create the "getters" and "setters"
         */
        foreach ($attributes as $attribute) {
            $current .= File::nl(1, 'public function get' . ucfirst($attribute) . '() {', 1)
                . File::nl(2, 'return ($this->_' . $attribute . ');', 1)
                . File::nl(1, '}', 2);

            $current .= File::nl(1, 'public function set' . ucfirst($attribute) . '($' . $attribute . ') {', 1)
                . File::nl(2, '$this->_' . $attribute . ' = $' . $attribute . ';', 1)
                . File::nl(1, '}', 2);
        }

        //close the php file
        $current .= "}\n";
        file_put_contents($file, $current);
    } else {
        echo 'Can\'t write file "' . $file . '" because it already exists (in "' . $folder . '")';
    }
}
