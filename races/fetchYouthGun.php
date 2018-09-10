<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	if(isset($_POST["gun_id"])){
		$output = array();
		$query = "SELECT * FROM gunshots WHERE gunshot_race_id = '".$_POST['gun_id']."' LIMIT 1";
		$stmt = $db->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$output["gunbenf"] = $row["gunshot_benf"];
			$output["gunbenm"] = $row["gunshot_benm"];
			$output["guninif"] = $row["gunshot_inif"];
			$output["guninim"] = $row["gunshot_inim"];
			$output["guninff"] = $row["gunshot_inff"];
			$output["guninfm"] = $row["gunshot_infm"];
			$output["gunjuvf"] = $row["gunshot_juvf"];
			$output["gunjuvm"] = $row["gunshot_juvm"];
		}
		echo json_encode($output);
	}
?>