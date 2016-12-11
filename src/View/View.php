<?php
namespace TC\View;

/**
 * This file is part of the Simple PHP Framework
 *
 * Default view class
 *
 * @category View
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class View
{

    /**
     * Loads the view
     *
     * @param string $template  Path to /app/View/<path>.php
     * @param array  $variables Array of variable=>value
     *
     * @return void
     */
    public function render($template, $variables)
    {
        extract($variables);
        // Create path
        $filename = './app/View/' . $template . '.php';
        if (file_exists($filename)) {
            // Make the variables available
            include $filename;
        } else {
            die('View ' . $filename . ' not found');
        }
    }
}
