<?php
	
	$conf = include($_SERVER['DOCUMENT_ROOT']."/config.php");
	
	try {
	    $db = new PDO("mysql:host=$conf->host;dbname=$conf->name", $conf->user, $conf->pass);
	    // set the PDO error mode to exception
	    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $db->query("SET NAMES 'utf8'");
	    $db->query("SET time_zone='+01:00';");
	} catch(PDOException $e) {
	    die($e->getMessage());
	}
	
?>