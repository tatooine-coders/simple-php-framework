<?php
/**
 * Configuration file for the app
 */
return [
    // Default route:
    'defaultRoute' => [
        'controller' => 'Default',
        'action' => 'index',
    ],
    'db' => [
        'host' => '',
        'port' => null,
        'name' => '', // Database name
        'user' => '',
        'password' => '',
    ],
];
