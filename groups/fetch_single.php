<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	if(isset($_POST["user_id"]))
	{
		$output = array();
		$stmt = $db->prepare(
			"SELECT * FROM groups WHERE group_id = ".$_POST['user_id']." LIMIT 1"
		);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$output["name"] = $row["group_name"];
		}
		echo json_encode($output);
	}
?>