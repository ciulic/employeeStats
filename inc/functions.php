<?php
function classAutoLoader($class) {
    $classPath = 'inc/classes/'.$class.'.class.php';
    if (file_exists($classPath)) {
        include($classPath);
        if (!class_exists($class)) die($class.' CLASS doesn\'t exist');
    } else {
        echo $classPath.' FILE doesn\'t exist.';
    }
}
?>