<?php
namespace TC\Model\Entity;

use TC\Lib\DB;
use PDO;

/**
 * This file is part of the Simple PHP Framework
 *
 * Default entity
 *
 * @category Model
 * @package  TC
 * @author   Manuel Tancoigne <m.tancoigne@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class Entity
{

    /**
     * Fields data
     *
     * @var mixed
     */
    protected $_data = [];
    protected $_fields = [];

    /**
     * Primary key name
     * @var string
     */
    protected $_primaryKey = 'id';

    /**
     * Table name
     * @var string
     */
    protected $_tableName = null;

    /**
     * State of the current entity
     * 
     * @var boolean 
     */
    protected $_isModified = false;

    /**
     * Fetches an entity in DB and populates $fields
     *
     * @param type $id Id of the entity to get
     */
    public function fetch($id)
    {
        $query = "SELECT * FROM `" . $this->_tableName . "` WHERE " . $this->_primaryKey . " = :id";
        $statement = DB::c()->prepare($query);
        $statement->bindParam('id', $id);

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $row = $statement->fetch();
        if (!empty($row)) {
            $this->set($row, true);
            $this->_isModified = false;
        } else {
            die('Invalid id');
        }
    }

    /**
     * Saves or updates the row in DB
     *
     * @return void
     */
    public function save()
    {
        $fieldList = $this->_fields;
        unset($fieldList[array_search($this->_primaryKey, $fieldList)]);

        if (empty($this->__get($this->_primaryKey))) {
            // Fields list

            $fields = join(',', $fieldList);

            $attributes = join(',', array_map(function($e) {
                    return ':' . $e;
                }, $fieldList));
            $query = "INSERT INTO " . $this->_tableName . ""
                . " (" . $fields . ") VALUES (" . $attributes . ")";
            $statement = DB::c()->prepare($query);

            foreach ($fieldList as $field) {
                $statement->bindParam($field, $this->_data[$field]);
            }

            $statement->execute();
        } elseif ($this->_isModified) {
            $list = join(', ', array_map(function($e) {
                    return $e . '= :' . $e;
                }, $fieldList));
            $query = "UPDATE " . $this->_tableName . " SET " . $list . " WHERE " . $this->_primaryKey . ' = :' . $this->_primaryKey;
            $statement = DB::c()->prepare($query);
            foreach ($fieldList as $field) {
                $statement->bindParam($field, $this->_data[$field]);
            }
            $statement->bindParam($this->_primaryKey, $this->_data[$this->_primaryKey]);
            $statement->execute();
        }
    }

    public function getData()
    {
        return $this->_data;
    }

    /**
     * Default constructor
     *
     * @param array $data Arrays of keys=>data
     */
    public function __construct($data = [])
    {
        $this->set($data, true);
    }

    /**
     * Populates the array of data
     *
     * @param type $data
     *
     * @return void
     */
    public function set($data = [], $initial = false)
    {
        if (!empty($data)) {
            foreach ($data as $field => $value) {
                if ($field != $this->_primaryKey || $initial) {
                    $this->__set($field, $value);
                }
            }
        }
    }

    /**
     * Generic setter
     *
     * @param string $field
     * @param mixed  $value
     */
    public function __set($field, $value)
    {
        if (array_search($field, $this->_fields) !== false) {
            if ($this->__get($field) != $value) {
                $this->_isModified = true;
            }
            $this->_data[$field] = $value;
        }
    }

    /**
     * Generic getter
     *
     * @param string $field field to check
     *
     * @return mixed
     */
    public function __get($field)
    {
        if (key_exists($field, $this->_data)) {
            return $this->_data[$field];
        } else {
            return null;
        }
    }

    public function delete()
    {
        if (!empty($this->__get($this->_primaryKey))) {
            $query = "DELETE FROM " . $this->_tableName . " WHERE " . $this->_primaryKey . "=:" . $this->_primaryKey;

            $statement = DB::c()->prepare($query);
            $statement->bindParam($this->_primaryKey, $this->_data[$this->_primaryKey]);
            $statement->execute();
            $this->_data[$this->_primaryKey] = null;
        }
    }
}
