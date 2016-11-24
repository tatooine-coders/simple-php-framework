<?php
//add the inclusions
$file = 'dynamicIncludes'.'.php';
$current = "<?php\n";
foreach ($objects as $object) {
    $current .= "include_once 'app/Model/".ucfirst($object['objectName'])."Model.php';\n";   
}
file_put_contents($file, $current);
?>