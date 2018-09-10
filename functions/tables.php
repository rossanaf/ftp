<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	list($action, $race)=explode(" - ",$_POST['action']);

	if ($action=="Eliminar")
	{
		$stmt = $db->prepare("SELECT * FROM athletes");
		$stmt->execute();
		$result = $stmt->fetchAll();
		if ($result == 1) {
			echo TRUNCATE;
		} 
		else
		{
			$stmt = $db->prepare("DELETE FROM races WHERE race_id = :race");
			$result = $stmt->execute(
				array(
					':race' => $race
				)
			);

			print_r($result);

			$stmt = $db->prepare("DELETE FROM athletes WHERE athlete_race_id = :race");
			$stmt->execute(
				array(
					':race' => $race
				)
			);

			$stmt = $db->prepare("DELETE FROM gunshots WHERE gunshot_race_id = :race");
			$stmt->execute(
				array(
					':race' => $race
				)
			);
		}

		//header("location:../dbtables/index.php");
	}

	// $output = "";
	// if ($_POST['action']=="Eliminar") 
	// {
	// 	$query = "TRUNCATE ".$_POST['table'];
	// 	$stmt = $db->prepare($query);
	// 	$stmt->execute();
	// 	header("location:../dbtables/index.php");
	// } 
	// elseif ($_POST['action']=="Backup" && $_POST['table']=="athletes") 
	// {
	// 	header('Content-Type: text/csv; charset=utf-8');
	// 	header('Content-Disposition: attachment; filename=athletes.csv');
	// 	// Table headers
	// 	$output = "athlete_id,athlete_pos,athlete_chip,athlete_license,athlete_bib,athlete_name,athlete_firstname,athlete_lastname,athlete_sex,athlete_dob,athlete_category,athlete_team_id,athlete_t1,athlete_t2,athlete_t3,athlete_t4,athlete_t5,athlete_finishtime,athlete_totaltime,athlete_race_id,athlete_started\n";
	// 	$query = "SELECT * FROM athletes ORDER BY athlete_id ASC";
	// 	$stmt = $db->prepare($query);
	// 	$stmt->execute();
	// 	$result = $stmt->fetchAll();
	// 	foreach ($result as $row) {
	// 		$output .= $row['athlete_id'].",".$row['athlete_pos'].",".$row['athlete_chip'].",".$row['athlete_license'].",".$row['athlete_name'].",".$row['athlete_firstname'].",".$row['athlete_lastname'].",".$row['athlete_sex'].",".$row['athlete_dob'].",".$row['athlete_category'].",".$row['athlete_team_id'].",".$row['athlete_t1'].",".$row['athlete_t1'].",".$row['athlete_t2'].",".$row['athlete_t3'].",".$row['athlete_t4'].",".$row['athlete_t5'].",".$row['athlete_finishtime'].",".$row['athlete_totaltime'].",".$row['athlete_race_id'].",".$row['athlete_started']."\n";
	// 	}
	// 	echo $output;
	// 	exit;
	// }
	// elseif ($_POST['action']=="Backup" && $_POST['table']=="times") 
	// {
	// 	header('Content-Type: text/csv; charset=utf-8');
	// 	header('Content-Disposition: attachment; filename=times.csv');
	// 	// Table headers
	// 	$output = "Chip,ChipTime,ChipType,PC,Reader,Antenna,MilliSecs,Location,LapRaw\n";
	// 	$query = "SELECT * FROM `times` ORDER BY ChipTime ASC";
	// 	$stmt = $db->prepare($query);
	// 	$stmt->execute();
	// 	$result = $stmt->fetchAll();
	// 	foreach ($result as $row) {
	// 		$output .= $row['Chip'].",".$row['ChipTime'].",".$row['ChipType'].",".$row['PC'].",".$row['Reader'].",".$row['Antenna'].",".$row['MilliSecs'].",".$row['Location'].",".$row['LapRaw']."\n";
	// 	}
	// 	echo $output;
	// 	exit;
	// }

	// $query = "SELECT * INTO OUTFILE '../../htdocs/backups/bk_".$_POST['table']."_".$agora.".sql' CHARACTER SET utf16 FROM ".$_POST['table'];
	//echo $action.$race;
?>