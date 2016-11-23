<?php
/**
 * This is the app entry point
 */

/*
 * Includes
 */
require_once('includes.php');

/*
 * Loads the configuration
 */

Config::load();
/*
 * Check routing
 */
Router::init();
Router::executeAction();
