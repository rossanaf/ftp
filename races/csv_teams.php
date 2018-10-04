<?php
	$race_id = $_GET['race_id'];
	$filename = "equipas".$race_id;
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=".$filename.".csv");
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	$querygun = $db->prepare("SELECT race_type FROM races WHERE race_id = ? LIMIT 1");
	$querygun->execute([$race_id]);
	$rowrace = $querygun->fetch();
	if ($rowrace['race_type'] == 'triatlo') {
		$output = fopen("php://output", "w");
		fputcsv($output, array('Clube', 'Tempo Total', 'Género'));
		$clube = array();
		// $query = $db->query("TRUNCATE teamresults");

		//TEMPOS DOS GUNS
		//$race_id = $_GET['race_id'];
		$querygun = $db->prepare("SELECT race_gun FROM races WHERE race_id = ? LIMIT 1");
		$querygun->execute([$race_id]);
		$rowrace = $querygun->fetch();

		$sex = array('F','M');
		for ($j=0; $j < 2; $j++) {
			$query = $db->query("TRUNCATE teamresults"); 
			// **** BUSCA OS TRES PRIMEIROS DE CADA EQUIPA **** //
			$queryteams = $db->prepare("SELECT athlete_team_id FROM athletes WHERE athlete_started >= '5' AND athlete_race_id = ? AND athlete_sex = ? GROUP BY athlete_team_id HAVING COUNT(*) > 2");
			$queryteams->execute([$race_id, $sex[$j]]);
			$teams = $queryteams->fetchAll();

			foreach ($teams as $row_clubes) 
			{
				if(($row_clubes['athlete_team_id']!=1000) && ($row_clubes['athlete_team_id']!=1001) && ($row_clubes['athlete_team_id']!=1002)) 
			    {
			    	$querytimes = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id = teams.team_id WHERE athlete_team_id = ? AND athlete_started >= 5 AND athlete_race_id = ? AND athlete_sex = ? ORDER BY athlete_finishtime LIMIT 3");
			        $querytimes->execute([$row_clubes['athlete_team_id'], $race_id, $sex[$j]]);
			        $timestable = $querytimes->fetchAll();
					$i=1;
					foreach ($timestable as $row_tempos) {
						$tempo_individual = strtotime($row_tempos['athlete_finishtime']) - strtotime($rowrace['race_gun']);
						if($i==1)
			                $teamresult_teamtime = $tempo_individual;
			            else
			                $teamresult_teamtime = $tempo_individual + $teamresult_teamtime;
			            $results = $db->prepare("INSERT INTO teamresults (teamresult_bib, teamresult_finishtime, teamresult_team,  teamresult_license, teamresult_name, teamresult_category, teamresult_validate, teamresult_teamtime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			            $results->execute([$row_tempos['athlete_bib'], gmdate('H:i:s',$tempo_individual), $row_tempos['team_name'], $row_tempos['athlete_license'], $row_tempos['athlete_name'], $row_tempos['athlete_category'], $i, gmdate('H:i:s',$teamresult_teamtime)]);
						$i++;
					}
				}
			}

			// **** ORDENAR DO PRIMEIRO PARA O SEGUNDO E MANDAR PARA PDF **** //
			// Instanciation of inherited class
			$stmt = $db->query("SELECT teamresult_team, teamresult_teamtime FROM teamresults WHERE teamresult_validate = '3' ORDER BY teamresult_teamtime ASC");
			$stmt->execute();
			$result = $stmt->fetchAll();

			foreach ($result as $row) 
			{
			    $line = array($row['teamresult_team'], $row['teamresult_teamtime'], $sex[$j]);
			    fputcsv($output, $line);
			}
		}
	} elseif ($rowrace['race_type'] == 'jovem') {
		$output = fopen("php://output", "w");
		fputcsv($output, array('Clube', '# Atletas', 'Pontos'));

		$stmt = $db->prepare("SELECT clubesj.atletas, clubesj.pontos, teams.team_name FROM clubesj INNER JOIN teams ON clubesj.clube = teams.team_id ORDER BY clubesj.pontos DESC");
		$stmt->execute();
		$teams = $stmt->fetchAll();
		foreach ($teams as $row) 
		{
			$line = array($row['team_name'], $row['atletas'], $row['pontos']);
		    fputcsv($output, $line);
		}
	} elseif ($rowrace['race_type'] == 'iturelay') {
    $output = fopen("php://output", "w");
    fputcsv($output, array('Team', 'Country', 'Start No','Leg 1', 'Leg 2', 'Leg 3', 'Leg 4', 'Leg 5', 'Position', 'Total Time'));
    $stmt = $db->prepare("SELECT * FROM live JOIN teams ON live_team_id=team_id WHERE live_race=? AND live_license=4 ORDER BY live_t0, live_started DESC, live_finishtime");
    $stmt->execute([$race_id]);
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
      $stmtLegs = $db->prepare('SELECT * FROM live WHERE live_race=? AND live_bib+? ORDER BY live_license');
      $stmtLegs->execute([$race_id, $row['live_bib']]);
      $legs = $stmtLegs->fetchAll();
      $legsTime = array();
      $i = 0;
      foreach ($legs as $leg) {
        $finishTime = $row['live_finishtime'];
        if ($row['live_finishtime'] == 'time') $finishTime = '00:00:00';
        $legsTime[$i] = $finishTime;
        $i++;
      }
      $line = array($row['team_name'], $row['team_country'], $row['live_bib'], $legsTime[0], $legsTime[1], $legsTime[2], $legsTime[3], $row['live_t0']);
      fputcsv($output, $line);
    }
  }
	fclose($output);
	exit;
?>