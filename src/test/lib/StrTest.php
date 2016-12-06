<?php
namespace TC\Test\Lib;

use PHPUnit\Framework\TestCase;
use TC\Lib\Str;

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
class StrTest extends TestCase
{

    /**
     * This testes the camelize() method
     *
     * @return void
     */
    public function testCamelize()
    {
        $this->assertEquals('SomeString', Str::camelize('some_string', true));
        $this->assertEquals('anotherLongString', Str::camelize('another_long_string', false));
    }

    /**
     * This testes the underscore() method
     *
     * @return void
     */
    public function testUnderscore()
    {
        $this->assertEquals('some_string', Str::underscore('SomeString'));
        $this->assertEquals('this_is_another_string', Str::underscore('thisIsAnotherString'));
    }

    public function testCollectionName()
    {
        $this->assertEquals('UsersCollection', Str::collectionName('users'));
        $this->assertEquals('App\\Model\\Collection\\UsersCollection', Str::collectionName('users', true));
    }
}
