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
 * Views generator
 *
 * @category Generators
 * @package  TC
 * @author   Hamid Kellali <hamid.kellali@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
abstract class ViewsGenerator extends Generator
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
     * List of tables with their configuration
     * @var array
     */
    protected static $_tableList = [];

    /**
     * Instances of each entities;
     * @var array
     */
    protected static $_entities = [];

    /**
     * Returns the list of tables to generate with their columns
     *
     * @param string $table Table name
     *
     * @return array
     */
    protected static function getTable($table)
    {
        // If --all and no table specified, get all tables
        if (self::$_flags['all'] && empty($table)) {
            return DB::getTablesColumns(DB::getTablesNames());
        } else {
            return DB::getTablesColumns([$table]);
        }
    }

    /**
     * Generates entities classes for given table list
     *
     * @param string $targetTable Table name
     *
     * @return void
     */
    protected static function views($targetTable)
    {
        self::$_tableList = self::getTable($targetTable);

        foreach (self::$_tableList as $table => $attributes) {
            if (count(self::$_tableList)>1) {
                echo Console::info(File::nl(0, 'Table ' . Str::entityName($table)));
            }
            $entity = Str::entityName($table, true);
            // Check entity existence
            if (class_exists($entity)) {
                self::$_entities[$table] = new $entity;
                // Create wanted views
                foreach (self::$_parameters as $view) {
                    if (in_array($view, ['index', 'add', 'update', 'view'])) {
                        self::$view($table, $attributes);
                    } else {
                        Console::warning(
                            File::nl(
                                2,
                                'Sorry, we can\'t build the '
                                . $view . ' view for table ' . $table
                            )
                        );
                    }
                }
            } else {
                Console::warning(
                    File::nl(
                        0,
                        'Entity ' . $entity
                        . ' not found. Skipping the views for this table.'
                    )
                );
            }
        }
    }

    /**
     * Generates Index view for a given table
     *
     * @param string $table      Table name
     * @param array  $attributes Array of attributes
     *
     * @return void
     */
    protected static function index($table, $attributes)
    {
        echo Console::info(
            File::nl(
                0,
                Console::indent(
                    '> Generating "index" view for '
                    . Str::entityName($table),
                    1
                )
            )
        );

        // Destination
        $folder = 'app/View/' . Str::camelize($table, true) . '/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        // File path
        $file = $folder . 'index.php';
        // List of attributes without their config
        $attributesList = array_keys($attributes);

        if (!file_exists($file) || self::$_flags['force']) {
            $current = File::nl(
                0,
                '<a href="/' . $table . '/add">New '
                . Str::prettify(Str::singularize($table)) . '</a>'
            )
                . File::nl(0, '<table border="1">')
                . File::nl(1, '<thead>');
            foreach ($attributesList as $attribute) {
                $current .= File::nl(2, '<th>' . Str::prettify($attribute) . '</th>');
            }
            $current .= File::nl(2, '<th>Actions</th>');
            $current .= File::nl(1, '</thead>')
                . File::nl(1, '<tbody>')
                . File::nl(
                    2,
                    '<?php foreach($' . $table . ' as $'
                    . Str::singularize($table) . ') : ?>'
                )
                . File::nl(3, '<tr>');
            foreach ($attributesList as $attribute) {
                $current .= File::nl(
                    4,
                    '<td><?php echo $'
                    . Str::singularize($table) . '->'
                    . $attribute . ' ?></td>'
                );
            }

            $current .= File::nl(4, '<td>')
                . File::nl(
                    5,
                    '<a href="/' . $table . '/view?'
                    . self::$_entities[$table]->getPrimary() . '=<?php echo $'
                    . Str::singularize($table) . '->'
                    . self::$_entities[$table]->getPrimary() . '?>">View</a>'
                )
                . File::nl(
                    5,
                    '<a href="/' . $table . '/update?'
                    . self::$_entities[$table]->getPrimary() . '=<?php echo $'
                    . Str::singularize($table) . '->'
                    . self::$_entities[$table]->getPrimary() . '?>">Update</a>'
                )
                . File::nl(
                    5,
                    '<a href="/' . $table . '/delete?'
                    . self::$_entities[$table]->getPrimary() . '=<?php echo $'
                    . Str::singularize($table) . '->'
                    . self::$_entities[$table]->getPrimary() . '?>">Delete</a>'
                )
                . File::nl(4, '</td>')
                . File::nl(3, '</tr>')
                . File::nl(2, '<?php endforeach; ?>')
                . File::nl(1, '</tbody>')
                . File::nl(0, '<table>');

            file_put_contents($file, $current);
        } else {
            echo Console::warning(
                File::nl(
                    0,
                    Console::indent(
                        '>>> Can\'t write file "'
                        . Str::singularize($file) . '" because it already exists (in "'
                        . $folder . '")',
                        2
                    )
                )
            );
        }
    }

    /**
     * Generates Add view for a given table
     *
     * @param string $table      Table name
     * @param array  $attributes Array of attributes
     *
     * @return void
     */
    protected static function add($table, $attributes)
    {
        echo Console::info(
            File::nl(
                0,
                Console::indent(
                    '> Generating "add" view for '
                    . Str::entityName($table),
                    1
                )
            )
        );

        // Destination
        $folder = 'app/View/' . Str::camelize($table, true) . '/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        // File path
        $file = $folder . 'add.php';
        // List of attributes without their config
        $attributesList = array_keys($attributes);

        if (!file_exists($file) || self::$_flags['force']) {
            $current = File::nl(0, '<a href="/' . $table . '">List ' . Str::prettify($table) . '</a>')
                . File::nl(0, '<form action="" method="POST">');

            foreach ($attributesList as $attribute) {
                $current .= File::nl(1, '<label for="">' . Str::prettify($attribute) . '')
                    . File::nl(
                        2,
                        '<input type="text" name="' . $attribute . '" id="'
                        . $table . '_' . $attribute . '" />'
                    )
                    . File::nl(1, '</label>')
                    . File::nl(1, '<br/>', 2);
            }

            $current .= File::nl(0, '<input type="hidden" name="spf_form_method" value="POST">')
                . File::nl(0, '<input type="submit" value="Add">')
                . File::nl(0, '</form>');
            file_put_contents($file, $current);
        } else {
            echo Console::warning(
                File::nl(
                    0,
                    Console::indent(
                        '>>> Can\'t write file "'
                        . Str::singularize($file)
                        . '" because it already exists (in "'
                        . $folder . '")',
                        2
                    )
                )
            );
        }
    }

    /**
     * Generates Update view for a given table
     *
     * @param string $table      Table name
     * @param array  $attributes Array of attributes
     *
     * @return void
     */
    protected static function update($table, $attributes)
    {
        echo Console::info(
            File::nl(
                0,
                Console::indent(
                    '> Generating "update" view for '
                    . Str::entityName($table),
                    1
                )
            )
        );

        // Destination
        $folder = 'app/View/' . Str::camelize($table, true) . '/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        // File path
        $file = $folder . 'update.php';
        // List of attributes without their config
        $attributesList = array_keys($attributes);

        if (!file_exists($file) || self::$_flags['force']) {
            $current = File::nl(0, '<a href="/' . $table . '">List ' . Str::prettify($table) . '</a>')
                . File::nl(0, '<form action="" method="POST">');

            foreach ($attributesList as $attribute) {
                $current .= File::nl(1, '<label for="">' . Str::prettify($attribute) . '')
                    . File::nl(
                        2,
                        '<input type="text" name="' . $attribute . '" id="'
                        . $table . '_' . $attribute . '" value="<?php echo $'
                        . Str::singularize($table) . '->' . $attribute . ' ?>"/>'
                    )
                    . File::nl(1, '</label>')
                    . File::nl(1, '<br/>', 2);
            }

            $current .= File::nl(0, '<input type="hidden" name="spf_form_method" value="POST">')
                . File::nl(0, '<input type="submit" value="Update">')
                . File::nl(0, '</form>');
            file_put_contents($file, $current);
        } else {
            echo Console::warning(
                File::nl(
                    0,
                    Console::indent(
                        '>>> Can\'t write file "'
                        . Str::singularize($file) . '" because it already exists (in "' . $folder . '")',
                        2
                    )
                )
            );
        }
    }

    /**
     * Generates View view for a given table
     *
     * @param string $table      Table name
     * @param array  $attributes Array of attributes
     *
     * @return void
     */
    protected static function view($table, $attributes)
    {
        echo Console::info(
            File::nl(
                0,
                Console::indent(
                    '> Generating "view" view for '
                    . Str::entityName($table),
                    1
                )
            )
        );

        // Destination
        $folder = 'app/View/' . Str::camelize($table, true) . '/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        // File path
        $file = $folder . 'view.php';
        // List of attributes without their config
        $attributesList = array_keys($attributes);

        if (!file_exists($file) || self::$_flags['force']) {
            $current = File::nl(0, '<a href="/' . $table . '">List ' . Str::prettify($table) . '</a>')
                . File::nl(0, '<dl>');
            foreach ($attributesList as $attribute) {
                $current .= File::nl(1, '<dt>' . Str::prettify($attribute) . '</dt>')
                    . File::nl(
                        1,
                        '<dd><?php echo $' . Str::singularize($table)
                        . '->' . $attribute . ' ?></dd>'
                    );
            }
            $current .= File::nl(0, '</dl>')
                . File::nl(
                    0,
                    '<a href="/' . $table . '/update?'
                    . self::$_entities[$table]->getPrimary() . '=<?php echo $'
                    . Str::singularize($table) . '->'
                    . self::$_entities[$table]->getPrimary() . '?>">Update</a>'
                )
                . File::nl(
                    0,
                    '<a href="/' . $table . '/delete?'
                    . self::$_entities[$table]->getPrimary() . '=<?php echo $'
                    . Str::singularize($table) . '->'
                    . self::$_entities[$table]->getPrimary() . '?>">Delete</a>'
                );

            file_put_contents($file, $current);
        } else {
            echo Console::warning(
                File::nl(
                    0,
                    Console::indent(
                        '>>> Can\'t write file "' . Str::singularize($file)
                        . '" because it already exists (in "' . $folder . '")',
                        2
                    )
                )
            );
        }
    }

    /**
     * Main method to be called by spf.php. This method will call the different
     * generator using the different actions.
     *
     * @param string $action Table name
     *
     * @return void
     */
    public static function generate($action = null)
    {
        // Checking for action: it's a parameter in our case
//        if (!empty($action)) {
//            // Add $action to the list of parameters
//            self::$_parameters[] = $action;
//        }
        // Checking for --all flag: overides the given parameters
        if (self::$_flags['all']) {
            self::$_parameters = ['index', 'view', 'add', 'update'];
        }

        if (!empty($action) || self::$_flags['all']) {
            if (count(self::$_parameters) > 0 || self::$_flags['all']) {
                echo Console::title('Generating views...');
                self::views($action);
            } else {
                Console::quit(
                    'You should provide at least one view name, or use the "--all" flag.'
                    . "\n" . 'Check the help for more informations.'
                );
            }
        } else {
            Console::quit(
                'You should provide at least one table name, or use the "--all" flag.'
                . "\n" . 'Check the help for more informations.'
            );
        }
    }
}
