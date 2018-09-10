<?php

	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	if(isset($_POST["user_id"]))
	{
		$stmt = $db->prepare("SELECT athlete_chip FROM athletes WHERE athlete_id = :id");
		$stmt->execute(
			array(
				':id' => $_POST["user_id"]
			)
		);
		$chip = $stmt->fetch();
		
		$stmt = $db->prepare("DELETE FROM athletes WHERE athlete_id = :id");
		$stmt->execute(
			array(
				':id' => $_POST["user_id"]
			)
		);

		$stmt_live = $db->prepare("DELETE FROM live WHERE live_chip = :chip");
		$result = $stmt_live->execute(
			array(
				':chip'	=> $chip['athlete_chip']
			)
		);
		if(!empty($result))
		{
			echo 'Athlete Deleted';
		}
	}

?>