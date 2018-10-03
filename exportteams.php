<?php 
  include_once ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  $teste = $db->prepare('SELECT live_pos, live_t0, team_name, team_country, live_bib, live_category, live_bib FROM live JOIN teams ON live_team_id=team_id WHERE live_license=? AND live_started=? AND live_category=? ORDER BY live_pos, live_started, live_bib, live_license');
  $teste->execute([4, 5, 'ELITE']);
  $results = $teste->fetchAll();
  $jsonData = array(); 
  foreach ($results as $result) {
    $liveData['pos'] = $result['live_pos'];
    $liveData['team'] = $result['team_name'];
    $liveData['country'] = $result['team_country'];
    $liveData['teamStartNo'] = $result['live_bib'];
    $liveData['timeTeam'] = $result['live_t0'];
    $query = $db->prepare("SELECT live_finishtime FROM live WHERE live_bib=? AND live_category=? ORDER BY live_license ASC");
    $query->execute([$result['live_bib'], 'ELITE']);
    $rows = $query->fetchAll();
    $i = 1;
    foreach ($rows as $row) {
      if ($row['live_finishtime'] === 'time') $liveData['leg'.$i] = '00:00:00';
      else $liveData['leg'.$i] = $row['live_finishtime'];
      $i++;
    }
    $liveData['prova'] = $result['live_category'];
    array_push($jsonData, $liveData);
  }
  $teste = $db->prepare('SELECT live_bib, live_pos, live_t0, team_name, team_country, live_bib, live_category FROM live JOIN teams ON live_team_id=team_id WHERE live_license=? AND live_started=? AND live_category=? ORDER BY live_pos, live_started, live_bib, live_license');
  $teste->execute([4, 5, 'JUNIOR']);
  $results = $teste->fetchAll();
  // $jsonData = array(); 
  foreach ($results as $result) {
    $liveData['pos'] = $result['live_pos'];
    $liveData['team'] = $result['team_name'];
    $liveData['country'] = $result['team_country'];
    $liveData['teamStartNo'] = $result['live_bib'];
    $liveData['timeTeam'] = $result['live_t0'];
    $query = $db->prepare("SELECT live_finishtime FROM live WHERE live_bib=? AND live_category=? ORDER BY live_license ASC");
    $query->execute([$result['live_bib'], 'JUNIOR']);
    $rows = $query->fetchAll();
    $i = 1;
    foreach ($rows as $row) {
      if ($row['live_finishtime'] === 'time') $liveData['leg'.$i] = '00:00:00';
      else $liveData['leg'.$i] = $row['live_finishtime'];
      $i++;
    }
    $liveData['prova'] = $result['live_category'];
    array_push($jsonData, $liveData);
  }
  echo json_encode($jsonData);
?>