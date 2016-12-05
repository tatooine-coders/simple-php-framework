<?php
namespace TC\Model\Entity;

use TC\Lib\Database;


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
    protected $data = [];

    /**
     * Primary key name
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Default constructor
     *
     * @param array $data Arrays of keys=>data
     */
    public function __construct($data = [])
    {
        $this->set($data);
    }

    /**
     * Populates the array of data
     *
     * @param type $data
     *
     * @return void
     */
    public function set($data = [])
    {
        if (!empty($data)) {
            foreach ($this->data as $field => $value) {
                $this->__set($field, $value);
            }
        }
    }

    /**
     * Fetches an entity in DB and populates $fields
     *
     * @param type $id Id of the entity to get
     */
    public function fetch($id)
    {
    }

    /**
     * Saves or updates the row in DB
     *
     * @return void
     */
    public function save()
    {
        // Check for id

        // Save or update
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
        return $this->data[$field];
    }

    /**
     * Generic setter
     *
     * @param string $field
     * @param mixed  $value
     */
    public function __set($field, $value)
    {
        if (key_exists($this->data, $field)) {
            $this->data[$field] = $value;
        }
    }
}
