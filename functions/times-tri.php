<?php 
  include_once($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include_once($_SERVER['DOCUMENT_ROOT']."/functions/times-processing.php");
  $queryraces = $db->query("SELECT race_id, race_gun_f, race_type, race_live FROM races WHERE race_gun_f != '-'");
  $races = $queryraces->fetchAll();
  foreach ($races as $race) {
    // LEITURA E CALCULO DOS TEMPOS PARA DUATLO E TRIATLOS E AQUATLOS
    if (($race['race_type']==='triatlo') || ($race['race_type']==='crind') || ($race['race_type']==='itu')  || ($race['race_type']==='aquathlon') || ($race['race_type']==='cre')) {
      processTriathlonTimes($race['race_gun_f'], 'F', $race['race_id'], $race['race_type'], $race['race_live'], $db);
    } 
  }
  $queryraces = $db->query("SELECT race_id, race_gun_m, race_type, race_live FROM races WHERE race_gun_m != '-'");
  $races = $queryraces->fetchAll();
  foreach ($races as $race) {
    // LEITURA E CALCULO DOS TEMPOS PARA DUATLO E TRIATLOS E AQUATLOS
    if (($race['race_type']==='triatlo') || ($race['race_type']==='crind') || ($race['race_type']==='itu')  || ($race['race_type']==='aquathlon') || ($race['race_type']==='cre')) {
      processTriathlonTimes($race['race_gun_m'], 'M', $race['race_id'], $race['race_type'], $race['race_live'], $db);
    } 
  }
?>