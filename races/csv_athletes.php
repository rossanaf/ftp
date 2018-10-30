<?php
	$race_id = $_GET['race_id'];
	$filename = "geral".$race_id;
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=".$filename.".csv");
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  $stmtRace = $db->prepare('SELECT race_gun_m, race_gun_f, race_type FROM races WHERE race_id=?');
  $stmtRace->execute([$race_id]);
  $resultGun = $stmtRace->fetch();
  if ($resultGun['race_type'] === 'iturelay') {
    $output = fopen("php://output", "w");
    fputcsv($output, array('First Name', 'Last Name', 'Country', 'Leg','Swim', 'T1', 'Bike', 'T2', 'Run', 'Total Time'));
    $stmt = $db->prepare("SELECT * FROM live JOIN teams ON live_team_id=team_id WHERE live_race=? ORDER BY live_t0, live_started DESC, live_finishtime");
    $stmt->execute([$race_id]);
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
     $t1 = $row['live_t1'];
     if ($row['live_t1'] == 'time') $t1 = '00:00:00';
     $t2 = $row['live_t2'];
     if ($row['live_t2'] == 'time') $t2 = '00:00:00';
     $t3 = $row['live_t3'];
     if ($row['live_t3'] == 'time') $t3 = '00:00:00';
     $t4 = $row['live_t4'];
     if ($row['live_t4'] == 'time') $t4 = '00:00:00';
     $t5 = $row['live_t5'];
     if ($row['live_t5'] == 'time') $t5 = '00:00:00';
     $finishTime = $row['live_finishtime'];
     if ($row['live_finishtime'] == 'time') $finishTime = '00:00:00';
     $line = array($row['live_firstname'], $row['live_lastname'], $row['team_country'], $row['live_license'], $t1, $t2, $t3, $t4, $t5, $finishTime);
     fputcsv($output, $line);
    }
    fclose($output);
  } elseif ($resultGun['race_type'] === 'itu') {
    $output = fopen("php://output", "w");
    fputcsv($output, array('Gender', 'First Name', 'Last Name', 'Country', 'Start No', 'Time', 'Swim', 'T1', 'Bike', 'T2', 'Run'));
    $stmt = $db->prepare("SELECT * FROM live JOIN teams ON live_team_id=team_id WHERE live_race=? ORDER BY live_sex,live_t0,live_started DESC,live_finishtime");
    $stmt->execute([$race_id]);
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
     $t1 = $row['live_t1'];
     if ($row['live_t1'] == 'time') $t1 = '00:00:00';
     $t2 = $row['live_t2'];
     if ($row['live_t2'] == 'time') $t2 = '00:00:00';
     $t3 = $row['live_t3'];
     if ($row['live_t3'] == 'time') $t3 = '00:00:00';
     $t4 = $row['live_t4'];
     if ($row['live_t4'] == 'time') $t4 = '00:00:00';
     $t5 = $row['live_t5'];
     if ($row['live_t5'] == 'time') $t5 = '00:00:00';
     $finishTime = $row['live_finishtime'];
     if ($row['live_finishtime'] == 'time') $finishTime = '00:00:00';
     $line = array($row['live_sex'], $row['live_firstname'], $row['live_lastname'], $row['team_country'], $row['live_bib'], $finishTime, $t1, $t2, $t3, $t4, $t5);
     fputcsv($output, $line);
    }
    fclose($output);
  } else {
    $output = fopen("php://output", "w");
    fputcsv($output, array('Licença', 'Chip', 'Gen.', 'Dorsal','Nome', 'Escalao', 'Clube', 'T1', 'T2', 'T3', 'T4', 'T5', 'Pen.', 'Tempo Final'));
    //**** TEMPOS DE QUEM TERMINOU ****//
    $query = $db->prepare("SELECT athletes.athlete_sex, athletes.athlete_finishtime, athletes.athlete_totaltime, athletes.athlete_chip, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= '5' AND athlete_race_id = ?");
    $query->execute([$race_id]);
    $rows = $query->fetchAll();
    foreach ($rows as $row) {
      if ($row['athlete_sex'] === 'M') $gun = $resultGun['race_gun_m'];
      elseif ($row['athlete_sex'] === 'F') $gun = $resultGun['race_gun_f'];

      $athlete_totaltime = gmdate('H:i:s', strtotime($row['athlete_finishtime'])-strtotime($gun));
      $query = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_chip = ?");
      $query->execute([$athlete_totaltime, $row['athlete_chip']]);
    }
    
    $stmt = $db->prepare("SELECT athlete_license, athlete_chip, athlete_sex, athlete_name, team_name, athlete_t1, athlete_t2, athlete_t3, athlete_t4, athlete_t5, athlete_finishtime, athlete_totaltime, athlete_category, athlete_bib FROM athletes LEFT JOIN teams ON athletes.athlete_team_id = teams.team_id WHERE athlete_race_id = ? ORDER BY athlete_started DESC, athlete_finishtime");
    $stmt->execute([$race_id]);
    $result = $stmt->fetchAll();

  	foreach ($result as $row) 
  	{
      if ($row['athlete_sex'] === 'M') $gun = $resultGun['race_gun_m'];
      elseif ($row['athlete_sex'] === 'F') $gun = $resultGun['race_gun_f'];

      if ($row['athlete_t1']=="-") $t1 = '-';
      else $t1 = utf8_decode(gmdate('H:i:s',strtotime($row['athlete_t1']) - strtotime($gun)));
      if (($row['athlete_t3']=="-")  || ($row['athlete_t1']=="-")) $t3 = '-';
      else $t3 = utf8_decode(gmdate('H:i:s',strtotime($row['athlete_t3']) - strtotime($row['athlete_t1'])));
      if(($row['athlete_t5']=="-") || ($row['athlete_t3']=="-")) $t5 = '-';
      else $t5 = utf8_decode(gmdate('H:i:s',strtotime($row['athlete_t5']) - strtotime($row['athlete_t3'])));

      // Exportar 3 Tempos
      // $line = array($row['athlete_license'], $row['athlete_chip'], $row['athlete_sex'], $row['athlete_bib'], $row['athlete_name'], $row['athlete_category'], $row['team_name'], $t1, $t3, $t5, $row['athlete_finishtime'], $row['athlete_totaltime']);

      // Exportar 5 tempos
  		$line = array($row['athlete_license'], $row['athlete_chip'], $row['athlete_sex'], $row['athlete_bib'], $row['athlete_name'], $row['athlete_category'], $row['team_name'], $row['athlete_t1'], $row['athlete_t2'], $row['athlete_t3'], $row['athlete_t4'], $row['athlete_t5'], $row['athlete_finishtime'], $row['athlete_totaltime']);
  		fputcsv($output, $line);
  	}
  	fclose($output);
  }
	// $output = fopen("php://output", "w");

	// fputcsv($output, array('Pos', 'Dorsal', 'Licença', 'Chip', 'Nome', 'Gen.', 'Escalão', 'Clube', 'Natação', 'T1', 'Ciclismo', 'T2', 'Corrida', 'Total'));

	// $stmt = $db->prepare("SELECT * FROM live JOIN athletes ON live_bib = athlete_bib WHERE live.live_race = ? ORDER BY live_sex, live_started DESC, live_finishtime");
	// $stmt->execute([$race_id]);
	// $result = $stmt->fetchAll();

	// foreach ($result as $row) 
	// {
	// 	$t1 = $row['live_t1'];
	// 	if ($row['live_t1'] == 'time') $t1 = '00:00:00';
	// 	$t2 = $row['live_t2'];
	// 	if ($row['live_t2'] == 'time') $t2 = '00:00:00';
	// 	$t3 = $row['live_t3'];
	// 	if ($row['live_t3'] == 'time') $t3 = '00:00:00';
	// 	$t4 = $row['live_t4'];
	// 	if ($row['live_t4'] == 'time') $t4 = '00:00:00';
	// 	$t5 = $row['live_t5'];
	// 	if ($row['live_t5'] == 'time') $t5 = '00:00:00';
	// 	$line = array($row['live_pos'], $row['live_bib'], $row['athlete_license'], $row['athlete_chip'], $row['live_firstname'], $row['live_sex'], $row['live_category'], $row['live_team'], $t1, $t2, $t3, $t4, $t5, $row['live_finishtime']);
	// 	fputcsv($output, $line);
	// }

	// fclose($output);

	exit;
?>