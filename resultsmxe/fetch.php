<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	function get_total_all_records($raceId) {
		include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
		$stmt = $db->prepare('SELECT * FROM live WHERE live_race=?');
		$stmt->execute([$raceId]);
		$result = $stmt->fetchAll();
		return $stmt->rowCount();
	}
  $order_column = array("live_pos","live_bib","live_firstname","live_team","live_country","live_t1","live_t2","live_t3","live_t4","live_t5","live_finishtime", "live_teamTime");
  $output = array();
  $query = 'SELECT * FROM live LEFT JOIN teams ON live.live_team_id=teams.team_id WHERE live_race='.$_POST['raceId'].' ';
  if (strlen($_POST["search"]["value"]) > 0) {
    $query .= 'AND (';
    $query .= 'LOWER (live_firstname) LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR LOWER (team_name) LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= ') ';
	} 
  if(isset($_POST["order"])) {
	    $query .= 'ORDER BY '.$order_column[$_POST["order"]["0"]["column"]].' '.$_POST["order"]["0"]["dir"].' ';
	} else {
	    $query .= 'ORDER BY live_started DESC, live_pos, live_finishtime, live_t4, live_t3, live_t2, live_t1,LENGTH(live_bib), live_bib ';
	}
  $stmt = $db->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll();
	$data = array();
	$filtered_rows = $stmt->rowCount();
	foreach($result as $row) {   
    $sub_array = array();
    $live_t1 = $row["live_t1"];
    $live_t2 = $row["live_t2"];
    $live_t3 = $row["live_t3"];
    $live_t4 = $row["live_t4"];
    $live_t5 = $row["live_t5"];
    $t0 = $row["live_t0"];
    $live_finishtime = $row["live_finishtime"];
    $flag="<img src='/images/flags/".$row['team_country'].".png' width='18px'>";
    if (($row["live_t1"]) === ("time")) $live_t1 = "";
    if (($row["live_t2"]) === ("time")) $live_t2 = "";
    if (($row["live_t3"]) === ("time")) $live_t3 = "";
    if (($row["live_t4"]) === ("time")) $live_t4 = "";
    if (($row["live_t5"]) === ("time")) $live_t5 = "";
    if (($row["live_t0"]) === ("time")) $t0 = "";
    if (($row["live_finishtime"]) === ("time")) $live_finishtime = "";
    if ($row["live_pos"] == 9999) {
      $pos = "";
      if ($row["live_t0"] !== 'time')
        $pos = $row["live_t0"];
    } else
      $pos = $row["live_pos"];
    $sub_array[] = $pos;
    $sub_array[] = $row["live_firstname"].' '.$row["live_lastname"];
    $sub_array[] = $row["team_name"];
    $sub_array[] = $flag.' '.$row["team_country"];
    $sub_array[] = $row["live_bib"];
    $sub_array[] = $live_t1;
    $sub_array[] = $live_t2;
    $sub_array[] = $live_t3;
    $sub_array[] = $live_t4;
    $sub_array[] = $live_t5;
    if(($row["live_finishtime"]=="DNF") || ($row["live_finishtime"]=="DNS") || ($row["live_finishtime"]=="DSQ") || ($row["live_finishtime"]=="LAP"))
      $sub_array[] = $row["live_finishtime"];
    else 
      $sub_array[] = $live_finishtime;
    $sub_array[] = $t0;
    $data[] = $sub_array;
  }
  $output = array(
    "draw"              =>  intval($_POST["draw"]),
    "recordsTotal"      =>  $filtered_rows,
    "recordsFiltered"   =>  get_total_all_records($_POST['raceId']),
    "data"              =>  $data
  );
    // echo $output();
  echo json_encode($output);
?>