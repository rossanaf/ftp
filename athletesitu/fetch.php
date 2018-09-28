<?php

	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	
	function get_total_all_records(){
		include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
		$stmt = $db->prepare("SELECT * FROM athletes");
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $stmt->rowCount();
	}

	$order_column = array("athlete_pos","athlete_chip","athlete_bib","athlete_name","athlete_sex","team_name","athlete_t1","athlete_t2","athlete_t3","athlete_t4","athlete_t5","athlete_race_id","athlete_finishtime");

	$output = array();

	$query = "SELECT athletes.*, teams.team_name FROM athletes LEFT JOIN teams ON athletes.athlete_team_id=teams.team_id ";

	if(isset($_POST["search"]["value"])){
    $query .= 'WHERE LOWER (teams.team_name) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR LOWER (athlete_category) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR LOWER (athlete_name) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR athlete_chip LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR LOWER (athlete_finishtime) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR athlete_bib LIKE "%'.$_POST["search"]["value"].'%" ';
	}

	if(isset($_POST["order"])){
	    $query .= 'ORDER BY '.$order_column[$_POST["order"]["0"]["column"]].' '.$_POST["order"]["0"]["dir"].' ';
	}
	else{
	    $query .= 'ORDER BY athletes.athlete_started DESC, athletes.athlete_pos DESC, athletes.athlete_finishtime DESC, athletes.athlete_t4 DESC, athletes.athlete_t3 DESC, athletes.athlete_t2 DESC, athletes.athlete_t1 DESC, athletes.athlete_race_id, LENGTH(athlete_bib), athletes.athlete_bib, athlete_arrive_order, athletes.athlete_sex, athletes.athlete_name ';
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
    $sub_array[] = $row["athlete_bib"].'.'.$row["athlete_arrive_order"];
		$sub_array[] = $row["athlete_name"];
		$sub_array[] = $row["athlete_sex"];
		$sub_array[] = $row["team_name"];
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
		$sub_array[] = '<button type="button" name="update" id="'.$row["athlete_id"].'" class="btn btn-success btn-xs update"><i class="fa fa-eye"></button>';
		$sub_array[] = '<button type="button" name="delete" id="'.$row["athlete_id"].'" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>';

		$data[] = $sub_array;
	}

	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"		=> 	$filtered_rows,
		"recordsFiltered"	=>	get_total_all_records(),
		"data"				=>	$data
	);

	echo json_encode($output);

?>