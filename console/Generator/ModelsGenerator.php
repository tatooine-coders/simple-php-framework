<?php
use TC\Lib\File;
use TC\Console\Generator\Generator;

/**
 * This file is part of the Simple PHP Framework
 *
 * Model generator
 *
 * This should be broken in multiple files/classes to be cleaner
 *
 * @category Generators
 * @package  TC
 * @author   Hamid Kellali <hamid.kellali@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/tatooine-coders/simple-php-framework/
 */
class ModelsGenerator extends Generator
{
    public $tables = [];

    public function setTables(Array $tables) {
        $this->tables = $tables;
    }
        
    public function generateEntities() {
        $folder = 'app/Model/Entity/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        foreach ($this->tables as $table => $attributes) {
            $file = $folder . ucfirst($table) . 'Entity.php';
            if (!file_exists($file) || $this->force) {
                /*
                 * Create the class declaration
                 */
                $current = File::nl(0, '<?php', 1)
                    . File::nl(0, 'namespace App\Model\Entity;', 2)
                    . File::nl(0, 'use TC\Model\Entity\Entity;', 1)
                    . File::nl(0, 'use TC\Lib\DB;', 2)
                    . File::nl(0, 'class ' . ucfirst($table) . 'Entity extends Entity', 1)
                    . File::nl(0, '{', 1);

                $current .= File::nl(1, 'protected $_tableName = \''.$table.'\';', 1);

                $attributesStr = implode("',\n        '", $attributes);

                $current .= File::nl(1, 'protected $_fields = [', 1);
                $current .= File::nl(2, "'".$attributesStr."'", 1);
                $current .= File::nl(1, '];', 1);



                
                //close the php file
                $current .= "}\n";
                file_put_contents($file, $current);
            } else {
                echo 'Can\'t write file "' . $file . '" because it already exists (in "' . $folder . '")';
            }
        }
    }

    public function generateCollections() {
        $folder = 'app/Model/Collection/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        foreach ($this->tables as $table => $attributes) {
            $file = $folder . ucfirst($table) . 'Collection.php';
            if (!file_exists($file) || $this->force) {
                /*
                 * Create the class declaration
                 */
                $current = File::nl(0, '<?php', 1)
                    . File::nl(0, 'namespace App\Model\Collection;', 2)
                    . File::nl(0, 'use TC\Model\Collection\Collection;', 1)
                    . File::nl(0, 'use TC\Lib\DB;', 2)
                    . File::nl(0, 'class ' . ucfirst($table) . 'Collection extends Collection{', 1);

                $current .= File::nl(1, 'protected $_table = \''.$table.'\';', 1);
                
                //close the php file
                $current .= "}\n";
                file_put_contents($file, $current);
            } else {
                echo 'Can\'t write file "' . $file . '" because it already exists (in "' . $folder . '")';
            }
        }
    }
}
