<?php
namespace TC\Test\Lib;

use PHPUnit\Framework\TestCase;
use TC\Lib\Logger;

/**
 * This file is part of the Simple PHP Framework
 *
 * Logger class tests
 *
 * @category Test
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class LoggerTest extends TestCase
{

    /**
     * This testes the getLogger() and addLog() methods
     *
     * @return void
     */
    public function testGet()
    {
        // Create some random logs
        Logger::addLog('error', 'error message');
        Logger::addLog('info', 'info message');

        $result = \TC\Lib\Logger::getLogger(true);

        // Assert
        $this->assertEquals('error message', $result[0]['message']);
    }
}
