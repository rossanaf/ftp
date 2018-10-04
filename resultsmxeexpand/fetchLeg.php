<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

  $output = array();
  $query = 'SELECT * FROM live LEFT JOIN teams ON live.live_team_id=teams.team_id WHERE live_bib='.$_POST['raceId'].' AND live_license=4 LIMIT 1';

	$stmt = $db->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll();
	$data = array();
	$filtered_rows = $stmt->rowCount();
	foreach($result as $row) {   
    $t1 = $row["live_t1"];
    $t2 = $row["live_t2"];
    $t3 = $row["live_t3"];
    $t4 = $row["live_t4"];
    $t5 = $row["live_t5"];
    $live_finishtime = $row["live_finishtime"];
    if (($row["live_t1"]) === ("time")) $t1 = "";
    if (($row["live_t2"]) === ("time")) $t2 = "";
    if (($row["live_t3"]) === ("time")) $t3 = "";
    if (($row["live_t4"]) === ("time")) $t4 = "";
    if (($row["live_t5"]) === ("time")) $t5 = "";
    if (($row["live_finishtime"]) === ("time")) $live_finishtime = "";
    $output['name1'] = $row['live_firstname'];
    $output['lastname1'] = $row['live_lastname'];
    $output['t11'] = $t1;
    $output['t12'] = $t2;
    $output['t13'] = $t3;
    $output['t14'] = $t4;
    $output['t15'] = $t5;
    $output['leg1'] = $live_finishtime;
    $output['teamFlag'] = "<img src='/images/flags/".$row['team_country'].".png' width='18px'> ".$row['team_country']."/".$row['team_name'];

  }
  echo json_encode($output);
?>