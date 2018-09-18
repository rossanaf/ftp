<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	if(isset($_POST["user_id"]))
	{
		$output = array();
		$stmt = $db->prepare(
			"SELECT *, teams.team_name FROM athletes LEFT JOIN teams ON athlete_team_id=teams.team_id WHERE athlete_id = ".$_POST['user_id']." LIMIT 1"
		);
		$stmt->execute();
		$rowAthlete = $stmt->fetch();
    $stmtGun = $db->prepare('SELECT race_gun_m, race_gun_f FROM races WHERE race_id=?');
    $stmtGun->execute([$rowAthlete['athlete_race_id']]);
    $rowGun = $stmtGun->fetch();
    if ($rowAthlete['athlete_sex']==='F') $gun = $rowGun['race_gun_f'];
    else $gun = $rowGun['race_gun_m'];
    if($gun==='-') {
      t1 = 0;
      t2 = 0;
      t3 = 0;
      t4 = 0;
      t5 = 0;
    }

	 //  $output["chip"] = $rowAthlete["athlete_chip"];
		// $output["bib"] = $rowAthlete["athlete_bib"];
		// $output["license"] = $rowAthlete["athlete_license"];
		// $output["name"] = $rowAthlete["athlete_name"];
		// $output["sex"] = $rowAthlete["athlete_sex"];
		// $output["category"] = $rowAthlete["athlete_category"];
		// $output["team"] = $rowAthlete["team_id"];
		// $output["race"] = $rowAthlete["athlete_race_id"];
		// $output["t0"] = $rowAthlete["athlete_t0"];
		// $output["t1"] = $rowAthlete["athlete_t1"];
		// $output["t2"] = $rowAthlete["athlete_t2"];
		// $output["t3"] = $rowAthlete["athlete_t3"];
		// $output["t4"] = $rowAthlete["athlete_t4"];
		// $output["t5"] = $rowAthlete["athlete_t5"];
		// $output["totaltime"] = $rowAthlete["athlete_totaltime"];
  //   $output["finishtime"] = $rowAthlete["athlete_finishtime"];
		// echo json_encode($output);
	}
?>