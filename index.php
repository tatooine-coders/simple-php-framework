<?php

/**
 * This is the app entry point
 */
use TC\Lib\Config;
use TC\Router\Router;

/*
 * Autoloader
 */
require_once('vendor/autoload.php');

/*
 * Load the configuration
 */
Config::load();

/*
 * Find the action and execute it
 */
Router::init();
var_dump(Router::getRoute());
Router::executeAction();
