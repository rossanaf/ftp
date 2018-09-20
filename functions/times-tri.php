<?php 
  include_once($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include_once($_SERVER['DOCUMENT_ROOT']."/functions/times-processing.php");
  $queryraces = $db->query("SELECT race_id, race_gun_f, race_type FROM races WHERE race_gun_f != '-'");
  $races = $queryraces->fetchAll();
  foreach ($races as $race) {
    // LEITURA E CALCULO DOS TEMPOS PARA DUATLO E TRIATLOS E AQUATLOS
    if (($race['race_type']==='triatlo') || ($race['race_type']==='crind') || ($race['race_type']==='itu')  || ($race['race_type']==='aquathlon') || ($race['race_type']==='cre')) {
      // $gun = $race['race_gun_f'];
      // $gender = 'F';
      print_r('chama funcao para processar tempos femininos <br>');
      processTriathlonTimes($race['race_gun_f'], 'F', $race['race_id'], $race['race_type'], $race['race_live'], $db);
    } 
  }
  $queryraces = $db->query("SELECT race_id, race_gun_m, race_type FROM races WHERE race_gun_m != '-'");
  $races = $queryraces->fetchAll();
  foreach ($races as $race) {
    // LEITURA E CALCULO DOS TEMPOS PARA DUATLO E TRIATLOS E AQUATLOS
    if (($race['race_type']==='triatlo') || ($race['race_type']==='crind') || ($race['race_type']==='itu')  || ($race['race_type']==='aquathlon') || ($race['race_type']==='cre')) {
      $gun = $race['race_gun_m'];
      $gender = 'M';
      print_r('chama funcao para processar tempos masculinos <br>');
      processTriathlonTimes($race['race_gun_m'], 'M', $race['race_id'], $race['race_type'], $race['race_live'], $db);
    } 
  }
  // POSICAO DA TABELA LIVE ATUALIZADA POR GENERO - M
  $pos = 1;
  $queryathletes = $db->query("SELECT live_id FROM live WHERE live_started = 5 AND live_sex = 'M' ORDER BY live_finishtime");
  $athletes = $queryathletes->fetchAll();
  foreach ($athletes as $athlete) {
    $updatelive = $db->prepare("UPDATE live SET live_pos = ? WHERE live_id = ?");
    $updatelive->execute([$pos, $athlete['live_id']]);
    $pos ++; 
  }
  // POSICAO DA TABELA LIVE ATULIZADA POR GENERO  F
  $pos = 1;
  $queryathletes = $db->query("SELECT live_id FROM live WHERE live_started = 5 AND live_sex = 'F' ORDER BY live_finishtime");
  $athletes = $queryathletes->fetchAll();
  foreach ($athletes as $athlete) {
    $updatelive = $db->prepare("UPDATE live SET live_pos = ? WHERE live_id = ?");
    $updatelive->execute([$pos, $athlete['live_id']]);
    $pos ++; 
  }
?>