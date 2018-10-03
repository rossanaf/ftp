<?php 
  include_once ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  $teste = $db->query('SELECT * FROM live');
  $results = $teste->fetchAll();
  $liveData = array(); 
  foreach ($results as $result) {
    $liveData[] = $result;
  }
  echo json_encode($liveData);
?>