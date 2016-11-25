<?php
namespace TC\Router;

use TC\Lib\Config;

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
     * @todo We should use filter_input() instead of accessing $_GET/$_POST
     *
     * @return void
     */
    public static function init()
    {
        // Controller
        if (!empty($_GET['c'])) {
            self::$_controller = ucfirst($_GET['c']);
            unset($_GET['c']);
        } elseif (!empty($_POST['c'])) {
            self::$_controller = ucfirst($_POST['c']);
            unset($_POST['c']);
        } else {
            self::$_controller = Config::get('defaultRoute')['controller'];
        }

        // Action
        if (!empty($_GET['a'])) {
            self::$_action = $_GET['a'];
            unset($_GET['a']);
        } elseif (!empty($_POST['a'])) {
            self::$_action = $_POST['a'];
            unset($_POST['a']);
        } else {
            self::$_action = Config::get('defaultRoute')['action'];
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
        $controllerPath = 'app/Controller/' . self::$_controller . 'Controller.php';

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
