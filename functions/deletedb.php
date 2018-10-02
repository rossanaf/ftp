<?php
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php"); 
	if (loginClass::checkLoginState($db)) {
		include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
		// include_once ($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
		$stmt = $db->prepare("TRUNCATE athletes; TRUNCATE chips; TRUNCATE cjovem; TRUNCATE gunshots; TRUNCATE races; TRUNCATE results; TRUNCATE times; TRUNCATE youthraces; TRUNCATE live;");
		$stmt->execute();
		header("location:/");
	} else {
		include_once ($_SERVER['DOCUMENT_ROOT']."/html/guest.php");
	}
?>