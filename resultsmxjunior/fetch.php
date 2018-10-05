<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db-lx.php");
	function get_total_all_records() {
		include ($_SERVER['DOCUMENT_ROOT']."/includes/db-lx.php");
		$stmt = $db->query('SELECT * FROM live WHERE live_category="JUNIOR"');
		$result = $stmt->fetchAll();
		return $stmt->rowCount();
	}
  $output = array();
  $query = 'SELECT * FROM live LEFT JOIN teams ON live.live_team_id=teams.team_id WHERE live_category="JUNIOR" AND live_license=4 ';
   $query .= 'ORDER BY live_started DESC, live_pos, live_finishtime, live_t4, live_t3, live_t2, live_t1,LENGTH(live_bib), live_bib ';
	$stmt = $db->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll();
	$data = array();
	$filtered_rows = $stmt->rowCount();
	foreach($result as $row) {
    $i = 1;
    $stmtLegs = $db->prepare('SELECT live_finishtime FROM live WHERE live_bib=? AND live_category="JUNIOR"');
    $stmtLegs->execute([$row['live_bib']]);
    $legs = $stmtLegs->fetchAll();
    foreach ($legs as $leg) {
      if ($i == 1) {
        $leg1 = $leg['live_finishtime']; 
        if (($leg['live_finishtime']) === ('time')) $leg1 = '';
      } elseif ($i == 2) {
        $leg2 = $leg['live_finishtime']; 
        if (($leg['live_finishtime']) === ('time')) $leg2 = '';
      } elseif ($i == 3) {
        $leg3 = $leg['live_finishtime']; 
        if (($leg['live_finishtime']) === ('time')) $leg3 = '';
      } elseif ($i == 4) {
        $leg4 = $leg['live_finishtime']; 
        if (($leg['live_finishtime']) === ('time')) $leg4 = '';
      }
      $i++;
    }
    $sub_array = array();
    $flag="<img src='/images/flags/".$row['team_country'].".png' width='18px'>";
    $t0 = $row["live_t0"];
    if ($row["live_t0"] === 'time') $t0 = '';
    if ($row["live_pos"] == 9999) {
      $pos = "";
      if ($row["live_t0"] !== 'time')
        $pos = $row["live_t0"];
    } else
      $pos = $row["live_pos"];
    $sub_array[] = '<button title="Guns" type="button" name="guns" id="'.$row['live_bib'].'" class="guns"><img width="20px" src="../images/details_open.png"></button>';
    $sub_array[] = '<b>'.$pos.'</b>';
    $sub_array[] = $row["team_name"];
    $sub_array[] = $flag.' '.$row["team_country"];
    $sub_array[] = $row["live_bib"];
    $sub_array[] = $leg1;
    $sub_array[] = $leg2;
    $sub_array[] = $leg3;
    $sub_array[] = $leg4;
    $sub_array[] = '<b>'.$t0.'</b>';
    $data[] = $sub_array;
  }
  $output = array(
    "draw"              =>  intval($_POST["draw"]),
    "recordsTotal"      =>  $filtered_rows,
    "recordsFiltered"   =>  get_total_all_records(),
    "data"              =>  $data
  );
    // echo $output();
  echo json_encode($output);
?>