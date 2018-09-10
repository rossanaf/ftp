<?php
//DB details
$dbHost = 'pdb10.awardspace.net';
//$dbHost = '52.233.136.76';
$dbUsername = '1178746_eliminar';
$dbPassword = 'teste1234';
//$dbName = 'eurocup';
$dbName = '1178746_eliminar';
//header("Content-type: text/html; charset=utf-8");

//Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
mysqli_set_charset( $db, 'utf8');

if ($db->connect_error) {
    die("Unable to connect database: " . $db->connect_error);
}

?>