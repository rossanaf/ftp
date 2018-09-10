<?php
//DB details
$dbHost = 'localhost';
//$dbHost = '52.233.136.76';
$dbUsername = 'root';
$dbPassword = '';
//$dbName = 'eurocup';
$dbName = 'scoring';
//header("Content-type: text/html; charset=utf-8");

//Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
mysqli_set_charset( $db, 'utf8');

if ($db->connect_error) {
    die("Unable to connect database: " . $db->connect_error);
}

?>