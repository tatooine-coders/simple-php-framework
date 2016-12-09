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
    public static function generate($action = null)
    {

        // Check for a flag as action
        if (in_array($action, ['--force', '--all'])) {
            self::$_flags[ltrim($action, '--')] = true;
            $action = null;
        } elseif (!empty($action)) {
            // Add $action to the list of parameters
            self::$_parameters[] = $action;
        } else {
            echo File::nl(0, Console::error('Nothing to do'));
            echo Console::help();
            die();
        }
//        var_dump(['action' => $action, 'p' => self::$_parameters, 'f' => self::$_flags]);
        ModelsGenerator::init(self::$_parameters, self::$_passedFlags);
        ModelsGenerator::generate('all');
        ControllersGenerator::init(self::$_parameters, self::$_passedFlags);
        ControllersGenerator::generate(null);
    }
}
