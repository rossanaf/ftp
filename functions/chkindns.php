<?php
	include_once ($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	include_once ($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	// PROVAS COM LIVE
	$stmt = $db->prepare("UPDATE athletes INNER JOIN live ON athletes.athlete_chip = live.live_chip SET athletes.athlete_finishtime = 'DNS', live.live_finishtime = 'DNS' WHERE athletes.athlete_finishtime = 'chkin'");
	$stmt->execute();

	$stmt = $db->prepare("UPDATE athletes INNER JOIN live ON athletes.athlete_chip = live.live_chip SET athlete_finishtime = 'DNF', live.live_finishtime = 'DNF' WHERE athlete_finishtime = '-' AND athlete_started < 5");
	$stmt->execute();

	// PROVAS SEM LIVE
	$stmt = $db->prepare("UPDATE athletes SET athlete_finishtime = 'DNS' WHERE athlete_finishtime = 'chkin'");
	$stmt->execute();

	$stmt = $db->prepare("UPDATE athletes SET athlete_finishtime = 'DNF' WHERE athlete_finishtime = '-' AND athlete_started < 5");
	$stmt->execute();
?>