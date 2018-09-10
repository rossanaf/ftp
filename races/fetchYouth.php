<?php
	include ($_SERVER['DOCUMENT_ROOT'].'/includes/db.php');
  if(isset($_POST['youthid'])) {
		$output = array();
		$query = "SELECT * FROM youthraces WHERE youthrace_race_id = '".$_POST['youthid']."' LIMIT 1";
		$stmt = $db->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) {
			$output["youth_id"] = $row["youthrace_race_id"];
			$output["youthname"] = $row["youthrace_name"];
			$output["youthnamepdf"] = $row["youthrace_namepdf"];
			$output["youthranking"] = $row["youthrace_ranking"];
			$output["youths1ben"] = $row["youthrace_s1_ben"];
			$output["youthd1ben"] = $row["youthrace_d1_ben"];
			$output["youths2ben"] = $row["youthrace_s2_ben"];
			$output["youthd2ben"] = $row["youthrace_d2_ben"];
			$output["youths3ben"] = $row["youthrace_s3_ben"];
			$output["youthd3ben"] = $row["youthrace_d3_ben"];
			$output["youths1inf"] = $row["youthrace_s1_inf"];
			$output["youthd1inf"] = $row["youthrace_d1_inf"];
			$output["youths2inf"] = $row["youthrace_s2_inf"];
			$output["youthd2inf"] = $row["youthrace_d2_inf"];
			$output["youths3inf"] = $row["youthrace_s3_inf"];
			$output["youthd3inf"] = $row["youthrace_d3_inf"];
			$output["youths1ini"] = $row["youthrace_s1_ini"];
			$output["youthd1ini"] = $row["youthrace_d1_ini"];
			$output["youths2ini"] = $row["youthrace_s2_ini"];
			$output["youthd2ini"] = $row["youthrace_d2_ini"];
			$output["youths3ini"] = $row["youthrace_s3_ini"];
			$output["youthd3ini"] = $row["youthrace_d3_ini"];
			$output["youths1juv"] = $row["youthrace_s1_juv"];
			$output["youthd1juv"] = $row["youthrace_d1_juv"];
			$output["youths2juv"] = $row["youthrace_s2_juv"];
			$output["youthd2juv"] = $row["youthrace_d2_juv"];
			$output["youths3juv"] = $row["youthrace_s3_juv"];
			$output["youthd3juv"] = $row["youthrace_d3_juv"];
			$output["youthdate"] = $row["youthrace_date"];
			$output["youthlocation"] = $row["youthrace_location"];
		}
		echo json_encode($output);
	}
?>