<?php
namespace TC\Controller;

use TC\View\View;

/**
 * This file is part of the Simple PHP Framework
 *
 * Default controller
 *
 * @category Controller
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
abstract class Controller
{

    /**
     * View object
     * @var View
     */
    public $View = null;

    /**
     * Variables to be passed to the view
     * @var array
     */
    public $viewVars = [];

    /**
     * Template to display
     * @var string
     */
    public $template = null;

    /**
     * Constructor
     * Instanciates the view
     * 
     * @return void
     */
    public function __construct()
    {
        $this->View = new View();
    }

    /**
     * Action that should be executed before the action
     *
     * @return void
     */
    public function beforeAction()
    {
       // Generic logic here 
    }

    /**
     * Action that should be executed after the action
     *
     * @return void
     */
    public function afterAction()
    {
        // Generic logic here
    }

    /**
     * Sets a variable available in view.
     * 
     * @param string $variable Variable name
     * @param mixed $value Variable content
     * 
     * @return void
     */
    public function set($variable, $value)
    {
        $this->viewVars[$variable] = $value;
    }

    /**
     * Calls the renderer and pass it the params
     * 
     * @return void
     */
    public function render()
    {
        $this->View->render($this->template, $this->viewVars);
    }
}
