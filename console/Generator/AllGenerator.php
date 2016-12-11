<?php
namespace TC\Console\Generator;

use TC\Console\Generator\Generator;
use TC\Lib\Console;
use TC\Lib\File;

/**
 * This file is part of the Simple PHP Framework
 *
 * Generates MVC files
 *
 * @category Generators
 * @package  TC
 * @author   Hamid Kellali <hamid.kellali@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
abstract class AllGenerator extends Generator
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
     * Main method to be called by spf.php. This method will call the different
     * generator using the different actions.
     *
     * @param string $action Action to perform
     *
     * @return void
     */
    public static function generate(string $action = null)
    {
        echo Console::title('Generating the MVC files');

        // Checking for action
        if (!empty($action)) {
            // Add $action to the list of parameters
            self::$_parameters[] = $action;
        } elseif (!self::$_flags['all'] && empty($action)) {
            Console::quit(
                'You should provide at least one table name, or use the "--all" flag.'
                . "\n" . 'Check the help for more informations.'
            );
        }
        // Models
        ModelsGenerator::init(self::$_parameters, self::$_passedFlags);
        ModelsGenerator::generate('all');

        // Controllers
        ControllersGenerator::init(self::$_parameters, self::$_passedFlags);
        ControllersGenerator::generate($action);

        // Forcing --all flag for views (this flag forces all the views, but not
        // all the tables)
        $flagsForViews=self::$_passedFlags;
        if (!in_array('all', $flagsForViews)) {
            $flagsForViews[]='all';
        }
        ViewsGenerator::init([], $flagsForViews);
        ViewsGenerator::generate($action);
    }
}
