<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	$stmt = $db->prepare("TRUNCATE athletes; TRUNCATE chips; TRUNCATE cjovem; TRUNCATE gunshots; TRUNCATE races; TRUNCATE results; TRUNCATE times; TRUNCATE youthraces; TRUNCATE live;");
	$stmt->execute();
	header("location:/");
?>