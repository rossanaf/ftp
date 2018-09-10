<?php
  include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  if(isset($_POST["gun_id"])){
    $output = array();
    $query = "SELECT race_gun_m, race_gun_f FROM races WHERE race_id = '".$_POST['gun_id']."' LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach($result as $row){
      $output["gunmen"] = $row["race_gun_m"];
      $output["gunwmn"] = $row["race_gun_f"];
    }
    echo json_encode($output);
  }
?>