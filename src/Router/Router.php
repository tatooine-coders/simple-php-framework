<?php
namespace TC\Router;

use TC\Lib\Config;
use TC\Lib\Hash;
use TC\Lib\Str;

/**
 * This file is part of the Simple PHP Framework
 *
 * Application router
 *
 * @category Router
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class Router
{

    /**
     * Current controller
     * @var string
     */
    static protected $_controller = null;

    /**
     * Current action
     * @var string
     */
    static protected $_action = null;

    /**
     * List of named parameters
     * @var array
     */
    static protected $_params = [];

    /**
     * Initializes the route from passed parameters
     *
     * @return void
     */
    public static function init()
    {
        $route = explode('?', str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']));
        $tmpPath = explode('/', $route[0]);
        $routePath = [];
        // Cleaning path
        foreach ($tmpPath as $pathElement) {
            if ($pathElement != '') {
                $routePath[] = $pathElement;
            }
        }

        // Controller
        if (isset($routePath[0]) && $routePath[0] !== '') {
            self::$_controller = $routePath[0];
            if (isset($routePath[1])) {
                self::$_action = $routePath[1];
            } else {
                self::$_action = Config::get('defaultRoute.action');
            }
        } else {
            self::$_controller = Config::get('defaultRoute.controller');
            self::$_action = Config::get('defaultRoute.action');
        }

        // Parameters
        self::$_params = $_POST + $_GET; // Precedence: post over get
    }

    /**
     * Returns the route as an array like [controller, page, params]
     *
     * @return array Route parameters
     */
    public static function getRoute()
    {
        return [
            'controller' => self::$_controller,
            'page' => self::$_action,
            'params' => self::$_params,
        ];
    }

    /**
     * Returns a parameter
     *
     * @param string $param Parameter to get
     *
     * @return mixed Parameter value
     */
    public static function getParam($param)
    {
        if (isset(self::$_params[$param])) {
            return self::$_params[$param];
        } else {
            return null;
        }
    }

    /**
     * Returns the current action
     *
     * @return string
     */
    public static function getAction()
    {
        return self::$_action;
    }

    /**
     * Returns the current controller name
     *
     * @return string
     */
    public static function getController()
    {
        return self::$_controller;
    }

    /**
     * Executes the action
     *
     * @return void
     */
    public static function executeAction()
    {
        // Check for controller
        $controllerPath = 'app/Controller/' . Str::controllerName(self::$_controller).'.php';

        if (file_exists($controllerPath)) {
            require_once($controllerPath);
            $controllerName = '\\App\\Controller\\' . self::$_controller . 'Controller';
            $controller = new $controllerName;
            // Check for action
            if (method_exists($controller, self::$_action)) {
                $action = self::$_action;
                $controller->$action();
            } else {
                die('404 - Action not found');
            }
        } else {
            die('404 - Controller not found (path: ' . $controllerPath . ')');
        }
    }
}
