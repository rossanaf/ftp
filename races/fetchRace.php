<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	if(isset($_POST["user_id"])){
		$output = array();
		$query = "SELECT * FROM races WHERE race_id = '".$_POST['user_id']."' LIMIT 1";
		$stmt = $db->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$output["id"] = $row["race_id"];
			$output["name"] = $row["race_name"];
			$output["namepdf"] = $row["race_namepdf"];
			$output["ranking"] = $row["race_ranking"];
			$output["segment1"] = $row["race_segment1"];
			$output["distsegment1"] = $row["race_distsegment1"];
			$output["segment2"] = $row["race_segment2"];
			$output["distsegment2"] = $row["race_distsegment2"];
			$output["segment3"] = $row["race_segment3"];
			$output["distsegment3"] = $row["race_distsegment3"];
			$output["date"] = $row["race_date"];
			$output["location"] = $row["race_location"];
		}
		echo json_encode($output);
	}
?>