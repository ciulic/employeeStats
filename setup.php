<?php
error_reporting(E_ALL ^ E_NOTICE);

include('defines.php');
include_once('inc/functions.php');

spl_autoload_extensions('.php');
spl_autoload_register('classAutoLoader');
?>

