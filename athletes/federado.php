<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	
	if(isset($_POST["licenca_id"]))
	{
		$output = array();
		$stmt = $db->prepare(
			"SELECT * FROM ftpathletes LEFT JOIN teams ON ftpathletes.ftpathlete_team_id=teams.team_id 
			WHERE ftpathlete_license = ".$_POST['licenca_id']."
			LIMIT 1"
		);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$output["chip"] = $row["ftpathlete_chip"];
			$output["name"] = $row["ftpathlete_name"];
			$output["dorsal"] = $row["ftpathlete_bib"];
			$output["sexo"] = $row["ftpathlete_sex"];
			$output["escalao"] = $row["ftpathlete_category"];
			$output["clube"] = $row["team_id"];
		}
		echo json_encode($output);
	}
?>