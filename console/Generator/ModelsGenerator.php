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
 * Model generator
 *
 * @category Generators
 * @package  TC
 * @author   Hamid Kellali <hamid.kellali@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
abstract class ModelsGenerator extends Generator
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
     * Returns a suffix string to set in class Docblock declaration
     *
     * @param array $value Type of column with configuration values
     *
     * @return string
     */
    protected static function getSuffix(array $value)
    {
        if ($value['isPrimary']) {
            $suffix = " Primary key";
        } elseif ($value['isForeignKey']) {
            $suffix = " Foreign key from " . Str::pluralize($value['isForeignKey']['table']);
        } else {
            $suffix = null;
        }
        return $suffix;
    }

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
     * Generates entities classes for given table list
     *
     * @param array $tables List of table names
     *
     * @return void
     */
    protected static function entities(array $tables = [])
    {
        $tables = self::getTables();

        foreach ($tables as $table => $attributes) {
            self::entity($table, $attributes);
        }
    }

    /**
     * Generates collections classes for given table list
     *
     * @param array $tables List of table names
     *
     * @return void
     */
    protected static function collections(array $tables = [])
    {
        $tables = self::getTables();

        foreach ($tables as $table => $attributes) {
            self::collection($table, $attributes);
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
    protected static function entity(string $table, array $attributes)
    {
        echo Console::nl('> Generating ' . Str::entityName($table), 1, 'info');

        $stringList = null;
        $fieldsList = [];
        $paramsListArray = [];
        foreach ($attributes as $attribute => $value) {
            $paramsListArray['names'][] = $attribute;
            $paramsListArray['suffixes'][] = self::getSuffix($value);
            $paramsListArray['types'][] = $value['type'];

            $stringList .= File::nl(2, "'" . $attribute . "',", 1);
            $fieldsList[$attribute] = $attribute;
        }

        $folder = 'app/Model/Entity/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        $tableName = ucfirst(Str::singularize($table));
        $file = $folder . Str::entityname($table) . '.php';
        if (!file_exists($file) || self::$_flags['force']) {
            /*
             * Create the class declaration
             */
            $current = File::nl(0, '<?php', 1)
                . File::nl(0, 'namespace App\Model\Entity;', 2)
                . File::nl(0, 'use TC\Model\Entity\Entity;', 1)
                . File::nl(0, 'use TC\Lib\DB;', 2);

            $current .= File::nl(1, "/**", 1)
                . File::nl(1, " * " . Str::camelize(Str::singularize($table), true) . " entity", 1)
                . File::nl(1, " *", 1);

            $paramsTmp = File::equalizeLength($paramsListArray['names'], $paramsListArray['suffixes']);
            $types = File::equalizeLength($paramsListArray['types'], [], true);

            foreach ($paramsTmp as $index => $value) {
                $current .= File::nl(1, " * @property " . $types[$index] . $value, 1);
            }
            $current .= File::nl(1, " * ", 1);
            foreach ($attributes as $attribute => $value) {
                if ($value['isForeignKey'] != null) {
                    $stringToAdd = Str::entityName(Str::singularize($value['isForeignKey']['table']), true)
                        . " $" . ucfirst($value['isForeignKey']['table']);
                    $current .= File::nl(1, " * @property " . $stringToAdd, 1);
                }
            }
            $current .= File::nl(1, " * ", 1)
                . File::nl(1, " * @category Model", 1)
                . File::nl(1, " * @package  App", 1)
                . File::nl(1, " * @author   Your Name <your@ema.il>", 1)
                . File::nl(1, " * @license  http://www.opensource.org/licenses/mit-license.php MIT License", 1)
                . File::nl(1, " * @link     https://github.com/tatooine-coders/simple-php-framework/", 1)
                . File::nl(1, " */", 1)
                . File::nl(0, 'class ' . Str::entityName($tableName) . ' extends Entity', 1)
                . File::nl(0, '{', 1);

            $current .= File::nl(1)
                . File::nl(1, "/**", 1)
                . File::nl(1, " * Table name", 1)
                . File::nl(1, " * @var string", 1)
                . File::nl(1, " */", 1)
                . File::nl(1, 'protected $_tableName = \'' . $table . '\';', 1);

            $current .= File::nl(1)
                . File::nl(1, "/**", 1)
                . File::nl(1, " * List of table fields", 1)
                . File::nl(1, " * @var array", 1)
                . File::nl(1, " */", 1)
                . File::nl(1, 'protected $_fields = [', 1)
                . $stringList;

            $current .= File::nl(1, '];', 1);
            $hasForeignKeys = false;
            foreach ($attributes as $attribute => $value) {
                if ($value['isPrimary']) {
                    $current .= File::nl(1)
                        . File::nl(1, "/**", 1)
                        . File::nl(1, " * Primary key field", 1)
                        . File::nl(1, " * @var string", 1)
                        . File::nl(1, " */", 1)
                        . File::nl(1, "protected \$_primaryKey = '" . $attribute . "';", 1);
                }
                if ($value['isForeignKey']) {
                    $hasForeignKeys = true;
                }
            }
            if ($hasForeignKeys) {
                $current .= File::nl(1)
                    . File::nl(1, "/**", 1)
                    . File::nl(1, " * List of foreign keys", 1)
                    . File::nl(1, " * @var array", 1)
                    . File::nl(1, " */", 1)
                    . File::nl(1, "protected \$_foreignKeys = [", 1);
                foreach ($attributes as $attribute => $value) {
                    if ($value['isForeignKey']) {
                        $current .= File::nl(2, "'" . $attribute . "' => [", 1)
                            . File::nl(3, "'table' => '" . Str::pluralize($value['isForeignKey']['table']) . "',", 1)
                            . File::nl(3, "'field' => '" . $value['isForeignKey']['field'] . "',", 1)
                            . File::nl(2, "],", 1);
                    }
                }
                $current .= File::nl(1, "];", 1);
            }

            //close the php file
            $current .= "}\n";
            file_put_contents($file, $current);
        } else {
            $errmess = '>>> Can\'t write file "' . $file . '" because it already exists.';
            echo Console::nl($errmess, 2, 'warning');
        }
    }

    /**
     * Generates collection class for a given table
     *
     * @param string $table      Table name
     * @param array  $attributes Array of attributes
     *
     * @return void
     */
    protected static function collection(string $table, array $attributes)
    {
        echo Console::nl('> Generating ' . Str::collectionName($table), 1, 'info');

        $folder = 'app/Model/Collection/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        $file = $folder . Str::collectionName($table) . '.php';
        $tableName = ucfirst($table);
        $file = $folder . $tableName . 'Collection.php';
        if (!file_exists($file) || self::$_flags['force']) {
            /*
             * Create the class declaration
             */
            $current = File::nl(0, '<?php', 1)
                . File::nl(0, 'namespace App\Model\Collection;', 2)
                . File::nl(0, 'use TC\Model\Collection\Collection;', 1)
                . File::nl(0, 'use TC\Lib\DB;', 2);

            $current .= File::nl(1, "/**", 1)
                . File::nl(1, " * " . Str::camelize(Str::pluralize($table), true) . " collection", 1);
            $current .= File::nl(1, " * ", 1)
                . File::nl(1, " * @category Model", 1)
                . File::nl(1, " * @package  App", 1)
                . File::nl(1, " * @author   Your Name <your@ema.il>", 1)
                . File::nl(1, " * @license  http://www.opensource.org/licenses/mit-license.php MIT License", 1)
                . File::nl(1, " * @link     https://github.com/tatooine-coders/simple-php-framework/", 1)
                . File::nl(1, " */", 1)
                . File::nl(0, 'class ' . ucfirst($tableName) . 'Collection extends Collection{', 1);
            $current .= File::nl(1)
                . File::nl(1, "/**", 1)
                . File::nl(1, " * Table name", 1)
                . File::nl(1, " * @var string", 1)
                . File::nl(1, " */", 1);
            $current .= File::nl(1, 'protected $_table = \'' . $table . '\';', 1);

            //close the php file
            $current .= "}\n";
            file_put_contents($file, $current);
        } else {
            $errmess = '>>> Can\'t write file "' . $file . '" because it already exists.';
            echo Console::nl($errmess, 2, 'warning');
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
    public static function generate(string $action = null)
    {
        if (count(self::$_parameters) > 0 || self::$_flags['all']) {
            switch ($action) {
                case 'all':
                    echo Console::title('Generating entities and collections...');
                    self::collections();
                    self::entities();
                    self::dumpautoload();
                    break;
                case 'collections':
                    echo Console::title('Generating collections...');
                    self::collections();
                    self::dumpautoload();
                    break;
                case 'entities':
                    echo Console::title('Generating entities...');
                    self::entities();
                    self::dumpautoload();
                    break;
                default:
                    Console::quit('Unrecognized action: "' . $action . '"');
            }
        } else {
            Console::quit(
                'You should provide at least one table name, or use the "--all" flag.' .
                "\n" . 'Check the help for more informations.'
            );
        }
    }
}
