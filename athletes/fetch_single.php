<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	if(isset($_POST["user_id"]))
	{
		$output = array();
		$stmt = $db->prepare(
			"SELECT *, teams.team_name FROM athletes LEFT JOIN teams ON athlete_team_id=teams.team_id WHERE athlete_id = ".$_POST['user_id']." LIMIT 1"
		);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$output["chip"] = $row["athlete_chip"];
			$output["bib"] = $row["athlete_bib"];
			$output["license"] = $row["athlete_license"];
			$output["name"] = $row["athlete_name"];
			$output["sex"] = $row["athlete_sex"];
			$output["category"] = $row["athlete_category"];
			$output["team"] = $row["team_id"];
			$output["race"] = $row["athlete_race_id"];
			$output["t0"] = $row["athlete_t0"];
			$output["t1"] = $row["athlete_t1"];
			$output["t2"] = $row["athlete_t2"];
			$output["t3"] = $row["athlete_t3"];
			$output["t4"] = $row["athlete_t4"];
			$output["t5"] = $row["athlete_t5"];
			$output["totaltime"] = $row["athlete_totaltime"];
	        $output["finishtime"] = $row["athlete_finishtime"];
		}
		echo json_encode($output);
	}
?>