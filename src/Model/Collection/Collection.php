<?php
namespace TC\Model\Collection;

use TC\Lib\DB;
use PDO;
use IteratorAggregate;
use ArrayIterator;

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

    protected $_table = null;
    protected $_data = [];

    public function fetchAll()
    {
        $query = 'SELECT * FROM ' . $this->_table;
        $statement = DB::c()->prepare($query);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_OBJ);
        // Entity name
        $entity = 'App\\Model\\Entity\\' . ucfirst($this->_table) . 'Entity';

        while (!empty($row = $statement->fetch())) {
            $this->_data[] = new $entity($row);
        };
    }

    public function getIterator()
    {
        return new ArrayIterator($this->_data);
    }

    public function saveAll()
    {
        
    }

    public function deleteAll()
    {
        
    }
}
