<?php

	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	if(isset($_POST["user_id"]))
	{
		$stmt = $db->prepare("DELETE FROM teams WHERE team_id = :id");
		$result = $stmt->execute(
			array(
				':id' => $_POST["user_id"]
			)
		);
		if(!empty($result))
		{
			echo 'Clube Eliminado';
		}

	}

?>