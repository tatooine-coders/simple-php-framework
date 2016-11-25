<?php
$force = (isset($argv[1]) && $argv[1] == 'force');
require_once 'vendor/autoload.php';
use TC\Model\Database;
use TC\Lib\Config;
Config::load('config.php');
$allAttributes = array();
$db = Database::connect();
$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='".Config::get('db')['name']."'";
$statement = $db->prepare($query);
$statement->execute();
$statement->setFetchMode(PDO::FETCH_NUM);
$tables = array();
$row = $statement->fetch();
do {
    $table = $row[0];
    $tables[] = $table;
} while (!empty($row = $statement->fetch()));

$index = 0;
$folder = 'app/Model/';

foreach ($tables as $table) {
	$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".Config::get('db')['name']."' AND TABLE_NAME = '".$table."'";
	$statement = $db->prepare($query);
	$statement->execute();
	$statement->setFetchMode(PDO::FETCH_NUM);
	$row = $statement->fetch();
	$tableAttributes = [];
	do {
	    $tableAttributes[] = $row[0];
	} while (!empty($row = $statement->fetch()));
	$allAttributes[$table] = $tableAttributes;
}
$tabulation = "    ";
//browse all attributes
foreach ($allAttributes as $table => $attributes) {
    $file = $folder.ucfirst($table).'Model.php';
    if (!file_exists($file) || $force) {
	    //create the class
	    $current = "<?php\n"
	        . "namespace App\Model;\n\n"
	        . "use TC\Model;\n\n"
	        . "\nclass ".ucfirst($table)."Model extends Model{\n";

	    //create the attributes
	    foreach ($attributes as $attribute) {
	        $current .= str_repeat($tabulation, 1).'protected $_'.$attribute.";\n";
	    }

	    //create the "getList" method
	    $current .= "\n".str_repeat($tabulation, 1).'public static function getList(PDO $db) {'."\n";
	    $current .= str_repeat($tabulation, 2).'$query = "SELECT * FROM '.$table.'";'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement = $db->prepare($query);'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement->execute();'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement->setFetchMode(PDO::FETCH_OBJ);'."\n";
	    $current .= str_repeat($tabulation, 2).'$'.$table.' = array()'.";\n";
	    $current .= str_repeat($tabulation, 2).'$row = $statement->fetch()'.";\n";
	    $current .= str_repeat($tabulation, 2)."do {\n";
	    $current .= str_repeat($tabulation, 3).'$object = new '.ucfirst($table).'Model();'."\n";
	    foreach ($attributes as $attribute) {
	            $current .= str_repeat($tabulation, 3).'$object->set'.ucfirst($attribute).'($row->'.$attribute.");\n";
	    }
	    $current .= str_repeat($tabulation, 3).'$'.$table.'[] = $object'.";\n";
	    $current .= str_repeat($tabulation, 2).'} while (!empty($row = $statement->fetch()))'.";\n";
	    $current .= str_repeat($tabulation, 2).'return ($'.$table.");\n";
	    $current .= str_repeat($tabulation, 1)."}\n\n";

	    //create the "getObject" method
	    $current .= str_repeat($tabulation, 1).'public static function getObject(PDO $db, $id) {'."\n";
	    $current .= str_repeat($tabulation, 2).'$query = "SELECT * FROM '.$table.' WHERE id = :id";'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement = $db->prepare($query);'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement->bindattribute('."'id'".', $id, PDO::PARAM_INT);'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement->execute();'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement->setFetchMode(PDO::FETCH_OBJ);'."\n";
	    $current .= str_repeat($tabulation, 2).'$object = null;'."\n";
	    $current .= str_repeat($tabulation, 2).'if (!empty($row = $statement->fetch())) {'."\n";
	    $current .= str_repeat($tabulation, 3).'$object = new '.ucfirst($table).'Model();'."\n";
	    foreach ($attributes as $attribute) {
	            $current .= str_repeat($tabulation, 3).'$object->set'.ucfirst($attribute).'($row->'.$attribute.");\n";
	    }
	    $current .= str_repeat($tabulation, 2)."}\n";
	    $current .= str_repeat($tabulation, 2).'return ($object);'."\n";
	    $current .= str_repeat($tabulation, 1)."}\n\n";

	    //create the constructor
	    $current .= "\n".str_repeat($tabulation, 1)."public function __construct(";

	    //add the constructor parameters
	    $index = 0;
	    foreach ($attributes as $attribute) {
	        $current .= '$'.$attribute;
	        $index++;
	        if ($index < sizeof($attributes)) {
	            $current .= ", ";
	        }
	    }
	    $current .= ") {\n";

	    //hydrate the attributes
	    foreach ($attributes as $attribute) {
	        $current .= str_repeat($tabulation, 2).'$this'."->set".ucfirst($attribute)."($".$attribute.");\n";
	    }
	    $current .= str_repeat($tabulation, 1)."}\n\n";

	    //create the "exists" method
	    $current .= str_repeat($tabulation, 1).'public function exists(PDO $db) {'."\n";
	    $current .= str_repeat($tabulation, 2).'$query = "SELECT * FROM '.$table.' WHERE id = :id LIMIT 1";'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement = $db->prepare($query);'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement->bindattribute('."'id'".', $this->getId(), PDO::PARAM_INT);'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement->execute();'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement->setFetchMode(PDO::FETCH_OBJ);'."\n";
	    $current .= str_repeat($tabulation, 2).'return ($statement);'."\n";
	    $current .= str_repeat($tabulation, 1)."}\n\n";

	    //create the "load" method
	    $current .= str_repeat($tabulation, 1).'public function load(PDO $db) {'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement = $this->exists($db);'."\n";
	    $current .= str_repeat($tabulation, 2).'if (!empty($row = $statement->fetch()))'." {\n";
	    foreach ($attributes as $attribute) {
	            $current .= str_repeat($tabulation, 3).'$this->set'.ucfirst($attribute).'($row->'.$attribute.");\n";
	    }
	    $current .= str_repeat($tabulation, 2)."}\n";
	    $current .= str_repeat($tabulation, 1)."}\n\n";

	    //create the "add" method
	    $current .= str_repeat($tabulation, 1).'public function add(PDO $db) {'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement = $this->exists($db);'."\n";
	    $current .= str_repeat($tabulation, 2).'if (empty($row = $statement->fetch()))'." {\n";
	    $current .= str_repeat($tabulation, 3).'$query = "INSERT INTO '.$table.' ('.implode(", ", $attributes).') attributeS (:'.implode(", :", $attributes).')";'."\n";
	    $current .= str_repeat($tabulation, 3).'$statement=$db->prepare($query);'."\n";
	    foreach ($attributes as $attribute) {
	        $current .= str_repeat($tabulation, 3).'$statement->bindattribute(\''.$attribute.'\', $this->get'.ucfirst($attribute).'(), PDO::PARAM_STR);'."\n";
	    }
	    $current .= str_repeat($tabulation, 2)."}\n";
	    $current .= str_repeat($tabulation, 1)."}\n\n";

	    //create the "update" method
	    $index = 0;
	    $current .= str_repeat($tabulation, 1).'public function update(PDO $db) {'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement = $this->exists($db);'."\n";
	    $current .= str_repeat($tabulation, 2).'if (empty($row = $statement->fetch()))'." {\n";
	    $current .= str_repeat($tabulation, 3).'$query = "UPDATE '.$table.' SET ';
	    foreach ($attributes as $attribute) {
	        $current .= $attribute.' = :'.$attribute;
	        $index++;
	        if ($index < sizeof($attributes)) {
	            $current .= ", ";
	        }
	    }
	    $current .= "\n".str_repeat($tabulation, 3)."WHERE id = :id\";\n";
	    $current .= str_repeat($tabulation, 3).'$statement=$db->prepare($query);'."\n";
	    foreach ($attributes as $attribute) {
	        $current .= str_repeat($tabulation, 3).'$statement->bindattribute(\''.$attribute.'\', $this->get'.ucfirst($attribute).'(), PDO::PARAM_STR);'."\n";
	    }
	    $current .= str_repeat($tabulation, 3).'$statement->execute();'."\n";
	    $current .= str_repeat($tabulation, 2)."}\n";
	    $current .= str_repeat($tabulation, 1)."}\n\n";

	    //create the "delete" method
	    $current .= str_repeat($tabulation, 1).'public function delete(PDO $db) {'."\n";
	    $current .= str_repeat($tabulation, 2).'$statement = $this->exists($db);'."\n";
	    $current .= str_repeat($tabulation, 2).'if (!empty($row = $statement->fetch()))'." {\n";
	    $current .= str_repeat($tabulation, 3).'$query = "DELETE FROM '.$table.' WHERE id = :id";'."\n";
	    $current .= str_repeat($tabulation, 3).'$statement=$db->prepare($query);'."\n";
	    $current .= str_repeat($tabulation, 3).'$statement->bindattribute(\'id\', $this->getId(), PDO::PARAM_STR);'."\n";
	    $current .= str_repeat($tabulation, 3).'$statement->execute();'."\n";
	    $current .= str_repeat($tabulation, 2)."}\n";
	    $current .= str_repeat($tabulation, 1)."}\n\n";



	    //create the "getters" and "setters"
	    foreach ($attributes as $attribute) {
	        $current .= str_repeat($tabulation, 1)."public function get".ucfirst($attribute)."() {\n";
	        $current .= str_repeat($tabulation, 2)."return (".'$this->_'.$attribute.");\n";
	        $current .= str_repeat($tabulation, 1)."}\n";

	        $current .= str_repeat($tabulation, 1)."public function set".ucfirst($attribute)."($".$attribute.") {\n";
	        $current .= str_repeat($tabulation, 2).'$this->_'.$attribute." = $".$attribute.";\n";
	        $current .= str_repeat($tabulation, 1)."}\n";
	    }

	    //close the php file
		$current .= "}\n?>";
		file_put_contents($file, $current);
	} else {
		echo "Can't write file ".ucfirst($table)."Model.php because it already exists (in ".$folder.") \n";
	}
}
?>