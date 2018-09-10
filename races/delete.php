<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	if(isset($_POST["user_id"]))
	{
		$tables = array("athletes", "races", "live");
		$columns = array("athlete_race_id", "race_id", "live_race");
		for ($i=0; $i < count($tables); $i++) { 
			$query = "DELETE FROM ".$tables[$i]." WHERE ".$columns[$i]." = '".$_POST["user_id"]."'";
			$stmt = $db->prepare($query);
			$stmt->execute();

			$stmt = $db->prepare("SELECT race_type FROM races WHERE race_id = ? LIMIT 1");
			$stmt->execute([$_POST["user_id"]]);
			$race = $stmt->fetch();
			if ($race['race_type'] == "jovem")
			{
				$query = "TRUNCATE gunshots; TRUNCATE youthraces";
				$stmt = $db->prepare($query);
				$stmt->execute();
			}
		}
	}
	//echo "<script>window.location.href='/races';</script>";
?>