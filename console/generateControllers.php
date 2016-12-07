<?php
/**
 * This file is part of the Simple PHP Framework
 *
 * Controller generator
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

use TC\Lib\DB;
use TC\Lib\Config;
use TC\Lib\File;
use TC\Lib\Str;

Config::load('config.php');
$allAttributes = [];
$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='" . Config::get('db')['name'] . "'";
$statement = DB::c()->prepare($query);
$statement->execute();
$statement->setFetchMode(PDO::FETCH_NUM);
$tables = [];
$row = $statement->fetch();
do {
    $table = $row[0];
    $tables[] = $table;
} while (!empty($row = $statement->fetch()));

$index = 0;
$folder = 'app/Controller/';



foreach ($tables as $table) {

    /*
     * Get DB infos
     */
    $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS "
        . "WHERE TABLE_SCHEMA = '" . Config::get('db')['name'] . "' AND TABLE_NAME = '" . $table . "'";
    $statement = DB::c()->prepare($query);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_NUM);
    $row = $statement->fetch();
    $tableAttributes = [];
    do {
        $tableAttributes[] = $row[0];
    } while (!empty($row = $statement->fetch()));
    $allAttributes[$table] = $tableAttributes;
}

/*
 * Browse all attributes
 */
foreach ($allAttributes as $table => $attributes) {
    /*
     * Define some values to be used in script
     */
    $entityName = Str::entityName($table);
    /**
     * @var TC\Model\Entity
     */
    $entityFullName = Str::entityName($table, true);
    $collectionName = Str::collectionName($table);
    $collectionFullName = Str::collectionName($table, true);
    $controllerName = Str::controllerName($table);
    $singularName = Str::singularize($table);
    $pluralName = Str::pluralize($table);


    /**
     * @var TC\Model\Entity
     */
    $entity = new $entityFullName;
    $entityPk=$entity->getPrimary();
    
    /**
     * @var TC\Model\Collection $collection
     */
    $collection = new $collectionFullName;

    $file = $folder . Str::controllerName($table).'.php';
    if (!file_exists($file) || $force) {
        /*
         * Create the class declaration
         */
        $current = File::nl(0, '<?php', 1)
            . File::nl(0, 'namespace App\\Controller;', 2)
            . File::nl(0, 'use TC\\Controller\\Controller;', 1)
            . File::nl(0, 'use ' . $collectionFullName . ';', 1)
            . File::nl(0, 'use ' . $entityFullName . ';', 2)
            . File::nl(0, '/**', 1)
            . File::nl(0, ' * This class contains the actions for the '.$table.' controller', 1)
            . File::nl(0, ' *', 1)
            . File::nl(0, ' * @category Controller', 1)
            . File::nl(0, ' * @package  App', 1)
            . File::nl(0, ' * @author   Your Name <your@ema.il>', 1)
            . File::nl(0, ' * @license  http://www.opensource.org/licenses/mit-license.php MIT License', 1)
            . File::nl(0, ' * @link     https://github.com/tatooine-coders/simple-php-framework/', 1)
            . File::nl(0, ' *', 1)
            . File::nl(0, ' */', 1)
            . File::nl(0, 'class ' . $controllerName . ' extends Controller', 1)
            . File::nl(0, '{', 1);

        /*
         * Index method
         */
        $current .= File::nl(1, '/**', 1)
            . File::nl(1, ' * Fetches all the '.$table.' records', 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @return void', 1)
            . File::nl(1, ' */', 1)
            . File::nl(1, 'public function index()', 1)
            . File::nl(1, '{', 1)
            . File::nl(2, '$' . $pluralName . ' = new ' . $collectionName . ';', 1)
            . File::nl(2, '$' . $pluralName . '->fetchAll();', 1)
            . File::nl(2, 'var_dump($' . $pluralName . ');', 1)
            . File::nl(1, '}', 2);

        /*
         * View method
         */
        $current .= File::nl(1, '/**', 1)
            . File::nl(1, ' * Fetches a record with a given '.$entityPk, 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @param integer $'.$entityPk.' Row id', 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @return void', 1)
            . File::nl(1, ' */', 1)
            . File::nl(1, 'public function view($id)', 1)
            . File::nl(1, '{', 1)
            . File::nl(2, '$' . $singularName . ' = new ' . $entityName . ';', 1)
            . File::nl(2, '$' . $singularName . '->fetch($'.$entityPk.');', 1)
            . File::nl(2, 'if (!is_null($' . $singularName . '->'.$entityPk.')) {', 1)
            . File::nl(3, 'die(\'' . $singularName . ' not found.\');', 1)
            . File::nl(2, '} else {', 1)
            . File::nl(3, 'var_dump($' . $singularName . ');', 1)
            . File::nl(2, '}', 1)
            . File::nl(1, '}', 2);

        /*
         * Add method
         */
        $current .= File::nl(1, '/**', 1)
            . File::nl(1, ' * Creates a record and saves it in db', 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @return void', 1)
            . File::nl(1, ' */', 1)
            . File::nl(1, 'public function add()', 1)
            . File::nl(1, '{', 1)
            . File::nl(2, 'die(\'Add() is not implemented\');', 1)
            . File::nl(1, '}', 2);
        /*
         * Update method
         */
        $current .= File::nl(1, '/**', 1)
            . File::nl(1, ' * Updates a record with a given '.$entityPk, 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @param integer $'.$entityPk.' Row id', 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @return void', 1)
            . File::nl(1, ' */', 1)
            . File::nl(1, 'public function update($'.$entityPk.')', 1)
            . File::nl(1, '{', 1)
            . File::nl(2, 'die(\'Update() is not implemented\');', 1)
            . File::nl(1, '}', 2);
        /*
         * Delete method
         */
        $current .= File::nl(1, '/**', 1)
            . File::nl(1, ' * Deletes a record with a given '.$entityPk, 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @param integer $'.$entityPk.' Row id', 1)
            . File::nl(1, ' *', 1)
            . File::nl(1, ' * @return void', 1)
            . File::nl(1, ' */', 1)
            . File::nl(1, 'public function delete($'.$entityPk.')', 1)
            . File::nl(1, '{', 1)
            . File::nl(2, '$' . $singularName . ' = new ' . $entityName . ';', 1)
            . File::nl(2, '$' . $singularName . '->fetch($'.$entityPk.');', 1)
            . File::nl(2, 'if (!is_null($' . $singularName . '->'.$entityPk.')) {', 1)
            . File::nl(3, 'die(\'' . $singularName . ' not found.\');', 1)
            . File::nl(2, '} else {', 1)
            . File::nl(3, '$' . $singularName . '->delete();', 1)
            . File::nl(3, 'die(\'' . $singularName . ' was successfully deleted.\');', 1)
            . File::nl(2, '}', 1)
            . File::nl(1, '}', 1);
        $current .= "}\n";
        file_put_contents($file, $current);
    } else {
        echo 'Can\'t write file "' . $file . '" because it already exists (in "' . $folder . '")';
    }
}
