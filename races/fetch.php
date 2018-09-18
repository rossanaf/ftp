<?php

	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include ($_SERVER['DOCUMENT_ROOT']."/functions/getTotalRecords.php");	

	$output = array();

	$query = "SELECT * FROM races ";

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
	    $sub_array[] = $row["race_id"];
		$sub_array[] = $row["race_name"];
	    $sub_array[] = $row["race_namepdf"];
		$sub_array[] = $row["race_ranking"];
	    $sub_array[] = $row["race_segment1"];
		$sub_array[] = $row["race_distsegment1"];
		$sub_array[] = $row["race_segment2"];
		$sub_array[] = $row["race_distsegment2"];
		$sub_array[] = $row["race_segment3"];
		$sub_array[] = $row["race_distsegment3"];
		$sub_array[] = $row["race_date"];
		$sub_array[] = $row["race_location"];
    // $sub_array[] = $row["race_live"];
    if ($row["race_live"] == 1) $sub_array[] = '<center><img width="40px" src="../images/check.png"></center>';
    else $sub_array[] =' ';
		if (($row['race_type'] == "jovem") || ($row['race_type'] == "jovemaq")){
			$sub_array[] = '<center><button title="Guns PJ" type="button" name="gunsYouth" id="'.$row["race_id"].'" class="gunsYouth"><img width="30px" src="../images/horn.jpg"></button></center>';
			$sub_array[] = '<button title="Editar Prova" type="button" name="youthhupdate" id="'.$row["race_id"].'" class="btn btn-info btn-xs youthupdate"><i class="fa fa-pencil"></i></button>';
		} elseif (($row['race_type'] === 'relay') && ($row['race_relay'] === 'M')) {
			$sub_array[] = $row["race_gun_m"];
			$sub_array[] = '<button title="editar prova" type="button" name="update" id="'.$row["race_id"].'" class="btn btn-info btn-xs update"><i class="fa fa-pencil"></i></button>';
		} else {
      $sub_array[] = '<center><button title="Guns" type="button" name="guns" id="'.$row["race_id"].'" class="guns"><img width="30px" src="../images/horn.jpg"></button></center>';
      $sub_array[] = '<button title="Editar Prova" type="button" name="update" id="'.$row["race_id"].'" class="btn btn-info btn-xs update"><i class="fa fa-pencil"></i></button>';
    }
		$sub_array[] = '<a href="csv_athletes.php?race_id='.$row['race_id'].'">Download Atletas</a>';
		$sub_array[] = '<a href="csv_teams.php?race_id='.$row['race_id'].'">Download Clubes</a>';
		$sub_array[] = '<button title="eliminar prova" type="button" name="delete" id="'.$row["race_id"].'" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></button>';
		$data[] = $sub_array;
	}

	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"		=> 	$filtered_rows,
		"recordsFiltered"	=>	get_total_all_records('races'),
		"data"				=>	$data
	);

	echo json_encode($output);
?>