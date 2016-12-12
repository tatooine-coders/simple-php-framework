<?php
require_once 'vendor/autoload.php';

use TC\Lib\Config;
use TC\Lib\Console;
use TC\Lib\File;
use TC\Lib\Hash;
use TC\Lib\Str;

/*
 * Loading the site configuration
 */
Config::load('config.php');

/**
 * Class and action like action:class (ie: generate:models)
 * @var array $actionAndController
 */
$actionAndController = explode(':', Hash::getValue($argv, 1));

/**
 * Class type to load (ie: generate)
 * @var string $class
 */
$class = Hash::getValue($actionAndController, 0);

/**
 * Action to perform (ie: models)
 * @var string $action
 */
$action = Hash::getValue($actionAndController, 1);

/**
 * Determines what to do
 * @var $action
 */
$target = Hash::getValue($argv, 2);

/**
 * List of flags (ie: --force)
 * @var array $flags
 */
$flags = [];

/**
 * List of params (ie: users)
 * @var array $params
 */
$params = [];

/*
 * Welcome text
 */
echo Console::greeter();

/*
 * Gets the params and flags
 */
for ($i = 3; $i < $argc; $i++) {
    if (preg_match('/--(.*)/', $argv[$i])) {
        $flags[] = ltrim($argv[$i], '--');
    } else {
        $params[] = $argv[$i];
    }
}

// Checks if $action is a flag:
$trimmedTarget = ltrim($target, '--');
if ($trimmedTarget != $target) {
    $target = '';
    $flags[] = $trimmedTarget;
}

if (!is_null($class) && !is_null($action)) {
    switch (strtolower($class)) {
        case 'generate':
            $controllerName = 'TC\\Console\\Generator\\' . Str::camelize($action . '_generator', true);
            break;
        default:
            Console::quit('There is no shell group for "' . $class . '". Check the help for more informations.');
    }
    if (class_exists($controllerName)) {
        $controllerName::init($params, $flags);
        $controllerName::generate($target);
    } else {
        Console::quit(
            'There is no shell for "' . $controllerName . '".'
            . "\n" . 'Check the help for more informations.'
        );
    }
} else {
    Console::quit('No action or shell specified. Check the help for more informations.');
}
