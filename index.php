<?php

/**
 * This is the app entry point
 */
use TC\Lib\Config;
use TC\Router\Router;
use TC\Lib\DB;

/*
 * Autoloader
 */
require_once('vendor/autoload.php');

/*
 * Load the configuration
 */
Config::load('config.php');

DB::connect();

/*
 * Find the action and execute it
 */
Router::init();
// var_dump(Router::getRoute());
Router::executeAction();
