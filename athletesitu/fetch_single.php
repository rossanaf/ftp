<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	if(isset($_POST["user_id"]))
	{
		$output = array();
		$stmt = $db->prepare(
			"SELECT * FROM athletes 
			WHERE athlete_id = '".$_POST["user_id"]."' 
			LIMIT 1"
		);
		$stmt->execute();
		$row = $stmt->fetch();
		$stmt_live = $db->prepare(
			"SELECT * FROM live 
			WHERE live_id = '".$_POST["user_id"]."' 
			LIMIT 1"
		);
		$stmt_live->execute();
		$row_live = $stmt_live->fetch();
		$output["chip"] = $row["athlete_chip"];
		$output["bib"] = $row["athlete_bib"];
		$output["firstname"] = $row_live["live_firstname"];
		$output["lastname"] = $row_live["live_lastname"];
		$output["sex"] = $row["athlete_sex"];
		$output["team"] = $row["athlete_category"];
		$output["race"] = $row["athlete_race_id"];
		$output["t1"] = $row["athlete_t1"];
		$output["t2"] = $row["athlete_t2"];
		$output["t3"] = $row["athlete_t3"];
		$output["t4"] = $row["athlete_t4"];
		$output["t5"] = $row["athlete_t5"];
        $output["finishtime"] = $row["athlete_finishtime"];
		echo json_encode($output);
	}
?>