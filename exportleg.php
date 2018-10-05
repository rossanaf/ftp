<?php 
  include_once ($_SERVER['DOCUMENT_ROOT']."/includes/db-lx.php");
  $teste = $db->query('SELECT live_bib, live_license, live_firstname, live_lastname, live_t1, live_t2, live_t3, live_t4, live_t5, live_finishtime, team_name, team_country, live_category FROM live JOIN teams ON live_team_id=team_id ORDER BY live_race, live_bib, live_license');
  $results = $teste->fetchAll();
  $jsonData = array(); 
  foreach ($results as $row) {
    $liveData['teamStartNo'] = $row['live_bib'];
    if ($row['live_license'] == 9999) $liveData['leg'] = ' ';
    else $liveData['leg'] = $row['live_license'];
    $liveData['firstName'] = $row['live_firstname'];
    $liveData['lastName'] = $row['live_lastname'];
    $liveData['team'] = $row['team_name'];
    $liveData['country'] = $row['team_country'];
    if ($row['live_t1'] === 'time') $liveData['swim'] = '00:00:00';
    else $liveData['swim'] = $row['live_t1'];
    if ($row['live_t2'] === 'time') $liveData['t1'] = '00:00:00';
    else $liveData['t1'] = $row['live_t2'];
    if ($row['live_t3'] === 'time') $liveData['bike'] = '00:00:00';
    else $liveData['bike'] = $row['live_t3'];
    if ($row['live_t4'] === 'time') $liveData['t2'] = '00:00:00';
    else $liveData['t2'] = $row['live_t4'];
    if ($row['live_t5'] === 'time') $liveData['run'] = '00:00:00';
    else $liveData['run'] = $row['live_t5'];
    if ($row['live_finishtime'] === 'time') $liveData['timeLeg'] = '00:00:00';
    else $liveData['timeLeg'] = $row['live_finishtime'];
    $liveData['prova'] = $row['live_category'];
    array_push($jsonData, $liveData);
  }
  echo json_encode($jsonData);
?>