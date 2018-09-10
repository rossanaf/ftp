<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	if(isset($_POST["user_id"]))
	{
		$output = array();
		$stmt = $db->prepare(
			"SELECT * FROM teams WHERE team_id = ".$_POST['user_id']." LIMIT 1"
		);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$output["name"] = $row["team_name"];
		}
		echo json_encode($output);
	}
?>