<?php
  include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include ($_SERVER['DOCUMENT_ROOT']."/functions/isTime.php");

  if(isset($_POST["operation"])){
    print_r($_POST);
    // so corre GUN se tipo de prova for crelogio ou estafeta mista
    $raceGunWmn = isTime($_POST["gunwmn"]);
    $raceGunMen = isTime($_POST["gunmen"]);
    $stmt = $db->prepare("UPDATE races SET race_gun_f=:wmn, race_gun_m=:men WHERE race_id=:id");
    $result = $stmt->execute(array(
      ':wmn' => $raceGunWmn,
      ':men' => $raceGunMen,
      ':id' =>  $_POST["gun_id"]
    ));
    $stmt = $db->prepare('SELECT race_type FROM races WHERE race_id=? LIMIT 1');
    $stmt->execute([$_POST["gun_id"]]);
    $raceType = $stmt->fetch();
    if($raceType['race_type'] === 'iturelay') {
      $stmt = $db->prepare('UPDATE athletes SET athlete_t0=? WHERE athlete_arrive_order=1 AND athlete_race_id=?');
      $stmt->execute([$raceGunMen, $_POST["gun_id"]]);
    }
    if(!empty($result)){
      echo 'Dados do atleta atualizados!';
    }
  }
?>