<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	$stmt = $db->prepare("SELECT race_location FROM races LIMIT 1");
	$stmt->execute();
	$local = $stmt->fetch();
	
	// NOME DO FICHEIRO IGUAL AO LOCAL DO EVENTO
	$filename = $local['race_location']."_backup";
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=".$filename.".sql");
	$output = fopen("php://output", "w");
	$line = array("Este ficheiro é propriedade da Federação Portuguesa de Triatlo");
	fputcsv($output, $line);
	//BACKUP TABELA ATHLETES
	$stmt = $db->prepare("SELECT * FROM athletes");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach ($result as $row) {
		$line = array(0, $row['athlete_pos'], $row['athlete_chip'], $row['athlete_license'], $row['athlete_bib'], $row['athlete_name'], $row['athlete_firstname'], $row['athlete_lastname'], $row['athlete_sex'], $row['athlete_dob'], $row['athlete_category'], $row['athlete_team_id'], $row['athlete_t0'], $row['athlete_t1'], $row['athlete_t2'], $row['athlete_t3'], $row['athlete_t4'], $row['athlete_t5'], $row['athlete_finishtime'], $row['athlete_totaltime'], $row['athlete_race_id'], $row['athlete_started'], $row['athlete_xtras'], $row['athlete_arrive_order']);
		fputcsv($output, $line);
	}
	//BACKUP TABELA GUNSHOTS
	$stmt = $db->prepare("SELECT * FROM gunshots");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach ($result as $row) {
		$line = array(1, $row['gunshot_benf'], $row['gunshot_benm'], $row['gunshot_inff'], $row['gunshot_infm'], $row['gunshot_inif'], $row['gunshot_inim'], $row['gunshot_juvf'], $row['gunshot_juvm'], $row['gunshot_race_id']);
		fputcsv($output, $line);
	}
	//BACKUP TABELA RACES
	$stmt = $db->prepare("SELECT * FROM races");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach ($result as $row) {
		$line = array(2, $row['race_id'], $row['race_name'], $row['race_namepdf'], $row['race_ranking'], $row['race_segment1'], $row['race_distsegment1'], $row['race_segment2'], $row['race_distsegment2'], $row['race_segment3'], $row['race_distsegment3'], $row['race_date'], $row['race_location'], $row['race_gun_f'], $row['race_gun_m'], $row['race_type'], $row['race_relay'], $row['race_live']);
		fputcsv($output, $line);
	}
	//BACKUP TABELA TEAMS
	$stmt = $db->prepare("SELECT * FROM teams");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach ($result as $row) {
		$line = array(3, $row['team_id'], $row['team_name']);
		fputcsv($output, $line);
	}
	//BACKUP TABELA YOUTHRACES
	$stmt = $db->prepare("SELECT * FROM youthraces");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach ($result as $row) {
		$line = array(4, $row['youthrace_race_id'], $row['youthrace_name'], $row['youthrace_namepdf'], $row['youthrace_ranking'], $row['youthrace_s1_ben'], $row['youthrace_d1_ben'], $row['youthrace_s2_ben'], $row['youthrace_d2_ben'], $row['youthrace_s3_ben'], $row['youthrace_d3_ben'], $row['youthrace_s1_inf'], $row['youthrace_d1_inf'], $row['youthrace_s2_inf'], $row['youthrace_d2_inf'], $row['youthrace_s3_inf'], $row['youthrace_d3_inf'], $row['youthrace_s1_ini'], $row['youthrace_d1_ini'], $row['youthrace_s2_ini'], $row['youthrace_d2_ini'], $row['youthrace_s3_ini'], $row['youthrace_d3_ini'], $row['youthrace_s1_juv'], $row['youthrace_d1_juv'], $row['youthrace_s2_juv'], $row['youthrace_d2_juv'], $row['youthrace_s3_juv'], $row['youthrace_d3_juv'], $row['youthrace_date'], $row['youthrace_location']);
		fputcsv($output, $line);
	}
	//BACKUP TABELA LIVE
	$stmt = $db->prepare("SELECT * FROM live");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach ($result as $row) {
		$line = array(5, $row['live_pos'], $row['live_chip'], $row['live_license'], $row['live_bib'], $row['live_firstname'], $row['live_lastname'], $row['live_sex'], $row['live_category'], $row['live_team_id'], $row['live_t1'], $row['live_t2'], $row['live_t3'], $row['live_t4'], $row['live_t5'], $row['live_finishtime'], $row['live_race'], $row['live_started']);
		fputcsv($output, $line);
	}
	//BACKUP TABELA TIMES
	$stmt = $db->prepare("SELECT * FROM times");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach ($result as $row) {
		$line = array(6, $row['Chip'], $row['ChipTime'], $row['Location']);
		fputcsv($output, $line);
	}
	fclose($output);
	exit;
?>