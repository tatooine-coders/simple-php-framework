<?php
namespace TC\Console\Generator;

use TC\Console\Generator\Generator;
use TC\Lib\Console;
use TC\Lib\DB;
use TC\Lib\File;
use TC\Lib\Str;

/**
 * This file is part of the Simple PHP Framework
 *
 * Controllers generator
 *
 * @category Generators
 * @package  TC
 * @author   Hamid Kellali <hamid.kellali@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
abstract class ControllersGenerator extends Generator
{

    /**
     * Flag list
     * @var array
     */
    protected static $_flags = [
        'force' => false,
        'all' => false,
    ];

    /**
     * List of parameters tables to use
     * @var array
     */
    protected static $_parameters = [];

    /**
     * Returns the list of tables to generate with their columns
     *
     * @return array
     */
    protected static function getTables()
    {
        if (self::$_flags['all']) {
            return DB::getTablesColumns(DB::getTablesNames());
        } else {
            return DB::getTablesColumns(self::$_parameters);
        }
    }

    /**
     * Generates controllers classes for given table list
     *
     * @param array $tables List of table names
     *
     * @return void
     */
    protected static function controllers($tables = [])
    {
        $tables = self::getTables();

        foreach ($tables as $table => $attributes) {
            self::controller($table, $attributes);
        }
    }

    /**
     * Generates entity class for a given table
     *
     * @param string $table      Table name
     * @param array  $attributes Array of attributes
     *
     * @return void
     */
    protected static function controller($table, $attributes)
    {
        echo Console::info(File::nl(0, '  > Generating ' . Str::controllerName($table)));

        // Entity name
        $entityName = Str::entityName($table);
        // Entity name with namespace
        $entityFullName = Str::entityName($table, true);
        // Collection name
        $collectionName = Str::collectionName($table);
        // Collection name with namespace
        $collectionFullName = Str::collectionName($table, true);
        // Controller name
        $controllerName = Str::controllerName($table);
        // Singular form of the table name
        $singularName = Str::singularize($table);
        // Plural form of the table name
        $pluralName = Str::pluralize($table);
        // Entity object
        $entity = new $entityFullName;
        // Entity primary key
        $entityPk = $entity->getPrimary();
        // Connection object
        $collection = new $collectionFullName;
        // Output folder
        $folder = 'app/Controller/';
        // Output file name
        $file = $folder . Str::controllerName($table) . '.php';
        if (!file_exists($file) || self::$_flags['force']) {
            /*
             * Create the class declaration
             */
            $current = File::nl(0, '<?php', 1)
                . File::nl(0, 'namespace App\\Controller;', 2)
                . File::nl(0, 'use TC\\Controller\\Controller;', 1)
                . File::nl(0, 'use TC\\Router\\Router;', 1)
                . File::nl(0, 'use ' . $collectionFullName . ';', 1)
                . File::nl(0, 'use ' . $entityFullName . ';', 2)
                . File::nl(0, '/**', 1)
                . File::nl(0, ' * This class contains the actions for the ' . $table . ' controller', 1)
                . File::nl(0, ' *', 1)
                . File::nl(0, ' * @category Controller', 1)
                . File::nl(0, ' * @package  App', 1)
                . File::nl(0, ' * @author   Your Name <your@ema.il>', 1)
                . File::nl(0, ' * @license  http://www.opensource.org/licenses/mit-license.php MIT License', 1)
                . File::nl(0, ' * @link     https://github.com/tatooine-coders/simple-php-framework/', 1)
                . File::nl(0, ' */', 1)
                . File::nl(0, 'class ' . $controllerName . ' extends Controller', 1)
                . File::nl(0, '{', 1);

            /*
             * Index method
             */
            $current .= File::nl(1, '/**', 1)
                . File::nl(1, ' * Fetches all the ' . $table . ' records', 1)
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
                . File::nl(1, ' * Fetches a record with a given ' . $entityPk, 1)
                . File::nl(1, ' *', 1)
                . File::nl(1, ' * @return void', 1)
                . File::nl(1, ' */', 1)
                . File::nl(1, 'public function view()', 1)
                . File::nl(1, '{', 1)
                . File::nl(2, '$' . $singularName . ' = new ' . $entityName . ';', 1)
                . File::nl(2, '$' . $entityPk . ' = Router::getParam(\'' . $entityPk . '\');', 1)
                . File::nl(2, '$' . $singularName . '->fetch($' . $entityPk . ');', 1)
                . File::nl(2, 'if (!is_null($' . $singularName . '->' . $entityPk . ')) {', 1)
                . File::nl(3, 'var_dump($' . $singularName . ');', 1)
                . File::nl(2, '} else {', 1)
                . File::nl(3, 'die(\'' . $singularName . ' not found.\');', 1)
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
                . File::nl(1, ' * Updates a record with a given ' . $entityPk, 1)
                . File::nl(1, ' *', 1)
                . File::nl(1, ' * @return void', 1)
                . File::nl(1, ' */', 1)
                . File::nl(1, 'public function update()', 1)
                . File::nl(1, '{', 1)
                . File::nl(2, '$' . $entityPk . ' = Router::getParam(\'' . $entityPk . '\');', 1)
                . File::nl(2, 'die(\'Update() is not implemented\');', 1)
                . File::nl(1, '}', 2);
            /*
             * Delete method
             */
            $current .= File::nl(1, '/**', 1)
                . File::nl(1, ' * Deletes a record with a given ' . $entityPk, 1)
                . File::nl(1, ' *', 1)
                . File::nl(1, ' * @return void', 1)
                . File::nl(1, ' */', 1)
                . File::nl(1, 'public function delete()', 1)
                . File::nl(1, '{', 1)
                . File::nl(2, '$' . $singularName . ' = new ' . $entityName . ';', 1)
                . File::nl(2, '$' . $entityPk . ' = Router::getParam(\'' . $entityPk . '\');', 1)
                . File::nl(2, '$' . $singularName . '->fetch($' . $entityPk . ');', 1)
                . File::nl(2, 'if (!is_null($' . $singularName . '->' . $entityPk . ')) {', 1)
                . File::nl(3, 'die(\'' . $singularName . ' not found.\');', 1)
                . File::nl(2, '} else {', 1)
                . File::nl(3, '$' . $singularName . '->delete();', 1)
                . File::nl(3, 'die(\'' . $singularName . ' was successfully deleted.\');', 1)
                . File::nl(2, '}', 1)
                . File::nl(1, '}', 1);
            $current .= "}\n";
            file_put_contents($file, $current);
        } else {
            echo Console::warning(
                File::nl(0, 'Can\'t write file "' . $file . '" because it already exists (in "' . $folder . '")')
            );
        }
    }

    /**
     * Main method to be called by spf.php. This method will call the different
     * generator using the different actions.
     *
     * @param string $action Action to perform
     *
     * @return void
     */
    public static function generate($action = null)
    {
        // Check for a flag as action
        if (in_array($action, ['--force', '--all'])) {
            self::$_flags[ltrim($action, '--')] = true;
            $action = null;
        } elseif (!empty($action)) {
            // Add $action to the list of parameters
            self::$_parameters[] = $action;
        }

        // Generate the controllers
        if (count(self::$_parameters) > 0 || self::$_flags['force']) {
            self::controllers();
        } else {
            echo File::nl(0, Console::error('Nothing to do'));
            echo Console::help();
            die();
        }
        
        self::dumpautoload();
    }
}
