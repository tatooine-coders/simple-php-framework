<?php
namespace TC\Controller;

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
     * Action that should be executed before the action
     *
     * @return void
     */
    public function beforeAction()
    {
    }

    /**
     * Action that should be executed after the action
     *
     * @return void
     */
    public function afterAction()
    {
    }
}
