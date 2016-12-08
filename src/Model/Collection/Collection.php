<?php
namespace TC\Model\Collection;

use TC\Lib\DB;
use PDO;
use IteratorAggregate;
use ArrayIterator;
use TC\Lib\Str;

/**
 * This file is part of the Simple PHP Framework
 *
 * Default collection
 *
 * @category Model
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class Collection implements IteratorAggregate
{

    /**
     * Table name
     * @var string
     */
    protected $_table = null;

    /**
     * List of entities
     * @var array
     */
    protected $_data = [];

    /**
     * Fetches all the entities in db
     *
     * @return void
     */
    public function fetchAll()
    {
        $query = 'SELECT * FROM ' . $this->_table;
        $statement = DB::c()->prepare($query);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_OBJ);
        // Entity name
        $entity = Str::entityName($this->_table, true);

        while (!empty($row = $statement->fetch())) {
            $this->_data[] = new $entity($row);
        };
    }

    /**
     * Makes the class iterable with while/foreach
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_data);
    }

    /**
     * Saves all the loaded entities
     *
     * @return void
     */
    public function saveAll()
    {
    }

    /**
     * Deletes all the loaded entities
     *
     * @return void
     */
    public function deleteAll()
    {
    }
}
