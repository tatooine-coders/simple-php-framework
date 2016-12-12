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

    /**
     * Test of the controllerName()method
     *
     * @return void
     */
    public function testControllerName()
    {
        $this->assertEquals('UsersCollection', Str::collectionName('users'));
        $this->assertEquals('App\\Model\\Collection\\UsersCollection', Str::collectionName('users', true));
    }

    /**
     * Test of the collectionName() method
     *
     * @return void
     */
    public function testCollectionName()
    {
        $this->assertEquals('UsersCollection', Str::collectionName('users'));
        $this->assertEquals('App\\Model\\Collection\\UsersCollection', Str::collectionName('users', true));
    }

    /**
     * Test of the entityName() method
     *
     * @return void
     */
    public function testEntityName()
    {
        $this->assertEquals('UserEntity', Str::entityName('users'));
        $this->assertEquals('App\\Model\\Entity\\UserEntity', Str::entityName('users', true));
    }

    /**
     * Test of the pluralize() method
     *
     * @return void
     */
    public function testPluralize()
    {
        $this->assertEquals('people', Str::pluralize('Person'));
        $this->assertEquals('people', Str::pluralize('People'));
        $this->assertEquals('cats', Str::pluralize('cat'));
    }

    /**
     * Test of the singularize() method
     *
     * @return void
     */
    public function testSingularize()
    {
        $this->assertEquals('cat', Str::singularize('cats'));
        $this->assertEquals('cat', Str::singularize('cat'));
        $this->assertEquals('person', Str::singularize('people'));
        $this->assertEquals('person', Str::singularize('person'));
    }

    /**
     * Test for the prettify() method
     *
     * @return void
     */
    public function testPrettify()
    {
        $this->assertEquals('some text', Str::prettify('some_text'));
        $this->assertEquals('Some other text', Str::prettify('some_other_text', true));
    }
}
