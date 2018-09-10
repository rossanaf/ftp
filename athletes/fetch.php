<?php

	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	
	function get_total_all_records(){
		include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
		$stmt = $db->prepare("SELECT * FROM athletes");
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $stmt->rowCount();
	}

	$order_column = array("athletes.athlete_pos","athletes.athlete_chip","length(athletes.athlete_bib),athletes.athlete_bib","athletes.athlete_name","athletes.athlete_sex","athletes.athlete_category","teams.team_name","athletes.athlete_t0","athletes.athlete_t1","athletes.athlete_t2","athletes.athlete_t3","athletes.athlete_t4","athletes.athlete_t5","athletes.athlete_race_id","athletes.athlete_finishtime");

	$output = array();

	$query = "SELECT athletes.*, teams.team_name FROM athletes LEFT JOIN teams ON athletes.athlete_team_id=teams.team_id ";

	if (strlen($_POST["search"]["value"]) > 0){
		$query .= 'WHERE LOWER (teams.team_name) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR LOWER (athletes.athlete_name) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR athletes.athlete_chip LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR LOWER (athletes.athlete_category) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR LOWER (athletes.athlete_finishtime) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR athletes.athlete_bib LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR LOWER (athletes.athlete_race_id) LIKE "%'.$_POST["search"]["value"].'%" ';
	} 

	if(isset($_POST["order"])) {
	    $query .= 'ORDER BY '.$order_column[$_POST["order"]["0"]["column"]].' '.$_POST["order"]["0"]["dir"].' ';
	} else {
	    $query .= 'ORDER BY athletes.athlete_started DESC, athletes.athlete_pos DESC, athletes.athlete_t5 DESC, athletes.athlete_t4 DESC, athletes.athlete_t3 DESC, athletes.athlete_t2 DESC, athletes.athlete_t1 DESC, athletes.athlete_t0, athletes.athlete_race_id, LENGTH(athlete_bib), athletes.athlete_bib, athletes.athlete_sex, athletes.athlete_name ';
	}

	if($_POST["length"] != -1){
		$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}

	$stmt = $db->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll();
	$data = array();
	$filtered_rows = $stmt->rowCount();

	foreach($result as $row){              
		$sub_array = array();
	    if($row["athlete_pos"]=="9999")
	        $sub_array[] = "";
	    else
		    $sub_array[] = $row["athlete_pos"];
		$sub_array[] = $row["athlete_chip"];
	    $sub_array[] = $row["athlete_bib"];
		$sub_array[] = $row["athlete_name"];
	    $sub_array[] = $row["athlete_sex"];
		$sub_array[] = $row["athlete_category"];
		$sub_array[] = $row["team_name"];
		$sub_array[] = $row["athlete_t0"];
		$sub_array[] = $row["athlete_t1"];
		$sub_array[] = $row["athlete_t2"];
		$sub_array[] = $row["athlete_t3"];
		$sub_array[] = $row["athlete_t4"];
		$sub_array[] = $row["athlete_t5"];
		$sub_array[] = '<a href="/races">'."Prova ".$row["athlete_race_id"].'</a>';
	    if(($row["athlete_finishtime"]=="DNF") || ($row["athlete_finishtime"]=="DNS") || ($row["athlete_finishtime"]=="DSQ") || ($row["athlete_finishtime"]=="LAP") || ($row["athlete_finishtime"]=="chkin") || ($row["athlete_finishtime"]=="validar"))
	        $sub_array[] = $row["athlete_finishtime"];    
	    else
	        $sub_array[] = "";
		$sub_array[] = '<button type="button" name="update" id="'.$row["athlete_id"].'" class="btn btn-success btn-xs update"><i class="fa fa-pencil-square-o"></button>';
		$sub_array[] = '<button type="button" name="delete" id="'.$row["athlete_id"].'" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>';

		$data[] = $sub_array;
	}

	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"		=> 	$filtered_rows,
		"recordsFiltered"	=>	get_total_all_records(),
		"data"				=>	$data
	);

	//print_r($output);
	echo json_encode($output);

?>