<?php
$tabulation = "    ";
$objects = require_once 'objects.php';
//browse all objects
foreach ($objects as $object) {
    $file = 'app/Model/'.ucfirst($object['objectName']).'Model.php';

    //create the class
    $current = "<?php\nclass ".ucfirst($object['objectName'])."Model {\n";

    //create the attributes
    foreach ($object['attributes'] as $key => $value) {
        $current .= str_repeat($tabulation, 1).'protected $_'.$value.";\n";
    }

    //create the "getList" method
    $current .= "\n".str_repeat($tabulation, 1).'public static function getList(PDO $db) {'."\n";
    $current .= str_repeat($tabulation, 2).'$query = "SELECT * FROM '.$object['objectName'].'";'."\n";
    $current .= str_repeat($tabulation, 2).'$statement = $db->prepare($query);'."\n";
    $current .= str_repeat($tabulation, 2).'$statement->execute();'."\n";
    $current .= str_repeat($tabulation, 2).'$statement->setFetchMode(PDO::FETCH_OBJ);'."\n";
    $current .= str_repeat($tabulation, 2).'$'.$object['objectName'].' = array()'.";\n";
    $current .= str_repeat($tabulation, 2).'$row = $statement->fetch()'.";\n";
    $current .= str_repeat($tabulation, 2)."do {\n";
    $current .= str_repeat($tabulation, 3).'$object = new '.ucfirst($object['objectName']).'Model();'."\n";
    foreach ($object['attributes'] as $value) {
            $current .= str_repeat($tabulation, 3).'$object->set'.ucfirst($value).'($row->'.$value.");\n";
    }
    $current .= str_repeat($tabulation, 3).'$'.$object['objectName'].'[] = $object'.";\n";
    $current .= str_repeat($tabulation, 2).'} while (!empty($row = $statement->fetch()))'.";\n";
    $current .= str_repeat($tabulation, 2).'return ($'.$object['objectName'].");\n";
    $current .= str_repeat($tabulation, 1)."}\n\n";

    //create the "getObject" method
    $current .= str_repeat($tabulation, 1).'public static function getObject(PDO $db, $id) {'."\n";
    $current .= str_repeat($tabulation, 2).'$query = "SELECT * FROM '.$object['objectName'].' WHERE id = :id";'."\n";
    $current .= str_repeat($tabulation, 2).'$statement = $db->prepare($query);'."\n";
    $current .= str_repeat($tabulation, 2).'$statement->bindValue('."'id'".', $id, PDO::PARAM_INT);'."\n";
    $current .= str_repeat($tabulation, 2).'$statement->execute();'."\n";
    $current .= str_repeat($tabulation, 2).'$statement->setFetchMode(PDO::FETCH_OBJ);'."\n";
    $current .= str_repeat($tabulation, 2).'$object = null;'."\n";
    $current .= str_repeat($tabulation, 2).'if (!empty($row = $statement->fetch())) {'."\n";
    $current .= str_repeat($tabulation, 3).'$object = new '.ucfirst($object['objectName']).'Model();'."\n";
    foreach ($object['attributes'] as $value) {
            $current .= str_repeat($tabulation, 3).'$object->set'.ucfirst($value).'($row->'.$value.");\n";
    }
    $current .= str_repeat($tabulation, 2)."}\n";
    $current .= str_repeat($tabulation, 2).'return ($object);'."\n";
    $current .= str_repeat($tabulation, 1)."}\n\n";

    //create the constructor
    $current .= "\n".str_repeat($tabulation, 1)."public function __construct(";

    //add the constructor parameters
    $index = 0;
    foreach ($object['attributes'] as $key => $value) {
        $current .= '$'.$value;
        $index++;
        if ($index < sizeof($object['attributes'])) {
            $current .= ", ";
        }
    }
    $current .= ") {\n";

    //hydrate the attributes
    foreach ($object['attributes'] as $value) {
        $current .= str_repeat($tabulation, 2).'$this'."->set".ucfirst($value)."($".$value.");\n";
    }
    $current .= str_repeat($tabulation, 1)."}\n\n";

    //create the "exists" method
    $current .= str_repeat($tabulation, 1).'public function exists(PDO $db) {'."\n";
    $current .= str_repeat($tabulation, 2).'$query = "SELECT * FROM '.$object['objectName'].' WHERE id = :id LIMIT 1";'."\n";
    $current .= str_repeat($tabulation, 2).'$statement = $db->prepare($query);'."\n";
    $current .= str_repeat($tabulation, 2).'$statement->bindValue('."'id'".', $this->getId(), PDO::PARAM_INT);'."\n";
    $current .= str_repeat($tabulation, 2).'$statement->execute();'."\n";
    $current .= str_repeat($tabulation, 2).'$statement->setFetchMode(PDO::FETCH_OBJ);'."\n";
    $current .= str_repeat($tabulation, 2).'return ($statement);'."\n";
    $current .= str_repeat($tabulation, 1)."}\n\n";

    //create the "load" method
    $current .= str_repeat($tabulation, 1).'public function load(PDO $db) {'."\n";
    $current .= str_repeat($tabulation, 2).'$statement = $this->exists($db);'."\n";  
    $current .= str_repeat($tabulation, 2).'if (!empty($row = $statement->fetch()))'." {\n";
    foreach ($object['attributes'] as $value) {
            $current .= str_repeat($tabulation, 3).'$this->set'.ucfirst($value).'($row->'.$value.");\n";
    }
    $current .= str_repeat($tabulation, 2)."}\n";
    $current .= str_repeat($tabulation, 1)."}\n\n";

    //create the "add" method
    $current .= str_repeat($tabulation, 1).'public function add(PDO $db) {'."\n";
    $current .= str_repeat($tabulation, 2).'$statement = $this->exists($db);'."\n";  
    $current .= str_repeat($tabulation, 2).'if (empty($row = $statement->fetch()))'." {\n";
    $current .= str_repeat($tabulation, 3).'$query = "INSERT INTO '.$object['objectName'].' ('.implode(", ", $object['attributes']).') VALUES (:'.implode(", :", $object['attributes']).')";'."\n";
    $current .= str_repeat($tabulation, 3).'$statement=$db->prepare($query);'."\n";
    foreach ($object['attributes'] as $value) {
        $current .= str_repeat($tabulation, 3).'$statement->bindValue(\''.$value.'\', $this->get'.ucfirst($value).'(), PDO::PARAM_STR);'."\n";
    }
    $current .= str_repeat($tabulation, 2)."}\n";
    $current .= str_repeat($tabulation, 1)."}\n\n";

    //create the "update" method
    $index = 0;
    $current .= str_repeat($tabulation, 1).'public function update(PDO $db) {'."\n";
    $current .= str_repeat($tabulation, 2).'$statement = $this->exists($db);'."\n";  
    $current .= str_repeat($tabulation, 2).'if (empty($row = $statement->fetch()))'." {\n";
    $current .= str_repeat($tabulation, 3).'$query = "UPDATE '.$object['objectName'].' SET ';
    foreach ($object['attributes'] as $value) {
        $current .= $value.' = :'.$value;
        $index++;
        if ($index < sizeof($object['attributes'])) {
            $current .= ", ";
        }
    }
    $current .= "\n".str_repeat($tabulation, 3)."WHERE id = :id\";\n";
    $current .= str_repeat($tabulation, 3).'$statement=$db->prepare($query);'."\n";
    foreach ($object['attributes'] as $value) {
        $current .= str_repeat($tabulation, 3).'$statement->bindValue(\''.$value.'\', $this->get'.ucfirst($value).'(), PDO::PARAM_STR);'."\n";
    }
    $current .= str_repeat($tabulation, 3).'$statement->execute();'."\n";
    $current .= str_repeat($tabulation, 2)."}\n";
    $current .= str_repeat($tabulation, 1)."}\n\n";

    //create the "delete" method
    $current .= str_repeat($tabulation, 1).'public function delete(PDO $db) {'."\n";
    $current .= str_repeat($tabulation, 2).'$statement = $this->exists($db);'."\n";
    $current .= str_repeat($tabulation, 2).'if (!empty($row = $statement->fetch()))'." {\n";
    $current .= str_repeat($tabulation, 3).'$query = "DELETE FROM '.$object['objectName'].' WHERE id = :id";'."\n";
    $current .= str_repeat($tabulation, 3).'$statement=$db->prepare($query);'."\n";
    $current .= str_repeat($tabulation, 3).'$statement->bindValue(\'id\', $this->getId(), PDO::PARAM_STR);'."\n";
    $current .= str_repeat($tabulation, 3).'$statement->execute();'."\n";
    $current .= str_repeat($tabulation, 2)."}\n";
    $current .= str_repeat($tabulation, 1)."}\n\n";

    

    //create the "getters" and "setters"
    foreach ($object['attributes'] as $value) {
        $current .= str_repeat($tabulation, 1)."public function get".ucfirst($value)."() {\n";
        $current .= str_repeat($tabulation, 2)."return (".'$this->_'.$value.");\n";
        $current .= str_repeat($tabulation, 1)."}\n";

        $current .= str_repeat($tabulation, 1)."public function set".ucfirst($value)."($".$value.") {\n";
        $current .= str_repeat($tabulation, 2).'$this->_'.$value." = $".$value.";\n";
        $current .= str_repeat($tabulation, 1)."}\n";
    }

    //close the php file
$current .= "}\n?>";
file_put_contents($file, $current);
}
?>