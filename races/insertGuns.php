<?php
  include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include ($_SERVER['DOCUMENT_ROOT']."/functions/isTime.php");

  if(isset($_POST["operation"])){
    // so corre GUN se tipo de prova for crelogio ou estafeta mista
    $raceGunWmn = isTime($_POST["gunwmn"]);
    $raceGunMen = isTime($_POST["gunmen"]);
    $stmt = $db->prepare("UPDATE races SET race_gun_f =  :wmn, race_gun_m =  :men WHERE race_id = :id"
    );
    $result = $stmt->execute(array(
      ':wmn' => $raceGunWmn,
      ':men' => $raceGunMen,
      ':id' =>  $_POST["gun_id"]
    )); 
    if(!empty($result)){
      echo 'Dados do atleta atualizados!';
    }
  }
?>