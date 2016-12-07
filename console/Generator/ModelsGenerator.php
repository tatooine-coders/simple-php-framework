<?php
use TC\Lib\File;
use TC\Lib\Str;
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
    public static function generateEntities($tables) {
        $folder = 'app/Model/Entity/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        foreach ($tables as $table => $attributes) {
            $tableName = ucfirst(Str::singularize($table));
            $file = $folder . $tableName . 'Entity.php';
            if (!file_exists($file) || self::$force) {
                /*
                 * Create the class declaration
                 */
                $current = File::nl(0, '<?php', 1)
                    . File::nl(0, 'namespace App\Model\Entity;', 2)
                    . File::nl(0, 'use TC\Model\Entity\Entity;', 1)
                    . File::nl(0, 'use TC\Lib\DB;', 2)
                    . File::nl(0, 'class ' . Str::entityName($tableName) . ' extends Entity', 1)
                    . File::nl(0, '{', 1);

                $current .= File::nl(1)
                . File::nl(1, "/**", 1)
                . File::nl(1, "* Table name", 1)
                . File::nl(1, "* @var string", 1)
                . File::nl(1, "*/", 1)
                . File::nl(1, 'protected $_tableName = \''.$table.'\';', 1);

                $current .= File::nl(1)
                . File::nl(1, "/**", 1)
                . File::nl(1, "* List of table fields", 1)
                . File::nl(1, "* @var array", 1)
                . File::nl(1, "*/", 1)
                . File::nl(1, 'protected $_fields = [', 1);
                foreach ($attributes as $attribute => $value) {
                    $current .= File::nl(2, "'".$attribute."',", 1);
                }
                $current .= File::nl(1, '];', 1);
                $hasForeignKeys = false;
                foreach ($attributes as $attribute => $value) {
                    if ($value['isPrimary']) {
                        $current .= File::nl(1)
                        . File::nl(1, "/**", 1)
                        . File::nl(1, "* Primary key field", 1)
                        . File::nl(1, "* @var string", 1)
                        . File::nl(1, "*/", 1)
                        . File::nl(1, "protected \$_primaryKey = '".$attribute."';", 1);
                    }
                    if ($value['isForeignKey']) {
                        $hasForeignKeys = true;
                    }
                }
                if ($hasForeignKeys) {
                    $current .= File::nl(1)
                    . File::nl(1, "/**", 1)
                    . File::nl(1, "* List of foreign keys", 1)
                    . File::nl(1, "* @var array", 1)
                    . File::nl(1, "*/", 1)
                    . File::nl(1, "protected \$_foreignKeys = [", 1);
                    foreach ($attributes as $attribute => $value) {
                        if ($value['isForeignKey']) {
                            $current .= File::nl(2, "'".$attribute."' => [", 1);
                            $current .= File::nl(3, "'table' => '".Str::pluralize($value['isForeignKey']['table'])."',", 1);
                            $current .= File::nl(3, "'field' => '".$value['isForeignKey']['field']."',", 1);
                            $current .= File::nl(2, "],", 1);
                        }
                    }
                    $current .= File::nl(1, "];", 1);
                }
            
                //close the php file
                $current .= "}\n";
                file_put_contents($file, $current);
            } else {
                echo 'Can\'t write file "' . $file . '" because it already exists (in "' . $folder . '")';
            }
        }
    }

    public static function generateCollections($tables) {
        $folder = 'app/Model/Collection/';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        foreach ($tables as $table => $attributes) {
            $tableName = ucfirst($table);
            $file = $folder . $tableName . 'Collection.php';
            if (!file_exists($file) || self::$force) {
                /*
                 * Create the class declaration
                 */
                $current = File::nl(0, '<?php', 1)
                    . File::nl(0, 'namespace App\Model\Collection;', 2)
                    . File::nl(0, 'use TC\Model\Collection\Collection;', 1)
                    . File::nl(0, 'use TC\Lib\DB;', 2)
                    . File::nl(0, 'class ' . ucfirst($tableName) . 'Collection extends Collection{', 1);

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
