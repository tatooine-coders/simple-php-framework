<?php
/**
 * This is the app entry point
 */

 use TC\Lib\Config;
 use TC\Router\Router;

/*
 * Includes
 */
require_once('vendor/autoload.php');

/*
 * Loads the configuration
 */

Config::load();
/*
 * Check routing
 */
Router::init();
Router::executeAction();
