<?php
namespace TC\Test\Lib;

use PHPUnit\Framework\TestCase;
use TC\Lib\Console;

/**
 * This file is part of the Simple PHP Framework
 *
 * Console class tests
 *
 * @category Test
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class ConsoleTest extends TestCase
{

    /**
     * Test for the Console::color() method
     *
     * @return void
     */
    public function testColor()
    {
        $this->assertEquals("\e[34mtest\e[39m", Console::color('test', 'blue'));
        $this->assertEquals("\e[34msome\ntest\e[39m", Console::color("some\ntest", 'blue'));
    }

    /**
     * Test for the Console::error() method
     *
     * @return void
     */
    public function testError()
    {
        $this->assertEquals("\e[31mError message\e[39m", Console::error('Error message'));
    }

    /**
     * Test for the Console::greeter() method
     *
     * @return void
     */
    public function testGreeter()
    {
        // How to test that ?
        $this->markTestIncomplete();
    }

    /**
     * Test for the Console::help() method
     *
     * @return void
     */
    public function testHelp()
    {
        $this->assertStringStartsWith('Help', Console::help());

        $this->markTestIncomplete();
    }

    /**
     * Test for the Console::info() method
     *
     * @return void
     */
    public function testInfo()
    {
        $this->assertEquals("\e[34mInfo message\e[39m", Console::info('Info message'));
    }

    /**
     * Test for the Console::ml() method
     *
     * @return void
     */
    public function testMl()
    {
        $paragraph = ['Some line', 'Some new line', 'Another one'];

        // Three lines, nothing fancy
        $assertion = "Some line\nSome new line\nAnother one\n";
        $this->assertEquals($assertion, Console::ml($paragraph));

        // Three lines, no indent, info message
        $assertion = "\e[34mSome line\e[39m\n\e[34mSome new line\e[39m\n\e[34mAnother one\e[39m\n";
        $this->assertEquals($assertion, Console::ml($paragraph, 0, 'info'));

        // Three lines, 1 indent, info message
        $assertion = "  \e[34mSome line\e[39m\n  \e[34mSome new line\e[39m\n  \e[34mAnother one\e[39m\n";
        $this->assertEquals($assertion, Console::ml($paragraph, 1, 'info'));
    }

    /**
     * Test for the Console::nl() method
     *
     * @return void
     */
    public function testNl()
    {
        $line = 'Some line';

        // Nothing fancy
        $assertion = "Some line\n";
        $this->assertEquals($assertion, Console::nl($line));

        // No indent, info message
        $assertion = "\e[34mSome line\e[39m\n";
        $this->assertEquals($assertion, Console::nl($line, 0, 'info'));

        // 1 indent, info message
        $assertion = "  \e[34mSome line\e[39m\n";
        $this->assertEquals($assertion, Console::nl($line, 1, 'info'));

        // Line contains \n and \t symbols:
        $line = "Some line\nWith others\n\t\tAnd tabs";
        $assertion = "  \e[34mSome line\e[39m\n  \e[34mWith others\e[39m\n  \e[34m    And tabs\e[39m\n";
        $this->assertEquals($assertion, Console::nl($line, 1, 'info'));
    }
    /**
     * Test for the Console::quit() method
     *
     * @return void
     */
    public function testQuit()
    {
        $this->markTestIncomplete();
    }

    /**
     * Test for the Console::title() method
     *
     * @return void
     */
    public function testTitle()
    {
        $this->assertEquals("\n\e[34mTitle\e[39m\n\e[34m-----\e[39m\n", Console::title('Title'));
        $this->assertEquals("\n\e[34mTitle\e[39m\n\e[34m=====\e[39m\n", Console::title('Title', '='));
    }

    /**
     * Test for the Console::warning() method
     *
     * @return void
     */
    public function testWarning()
    {
        $this->assertEquals("\e[33mWarning message\e[39m", Console::warning('Warning message'));
    }
}
