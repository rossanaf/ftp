<?php 
	// APLICA-SE A TODAS AS PROVAS, COLOCAR A POSIÇÃO DE CHEGADA À META POR TEMPO DECRESCENTE
  $pos = 1;
  $queryathletes = $db->query("SELECT athlete_id FROM athletes WHERE athlete_started = 5 ORDER BY athlete_t5");
  $athletes = $queryathletes->fetchAll();
  foreach ($athletes as $athlete) {
    $updateathletes = $db->prepare("UPDATE athletes SET athlete_pos = ? WHERE athlete_id = ?");
    $updateathletes->execute([$pos, $athlete['athlete_id']]);
    $pos++;
  }
  // echo $_SERVER['REMOTE_ADDR'];
  // ELIMINAR CONTEUDO DA TABELA TIMES EXCETO ULTIMOS 30 REGISTOS
  // $stmt = $db->prepare('DELETE FROM times
  //     WHERE MilliSecs <= (
  //         SELECT MilliSecs
  //             FROM (
  //                 SELECT MilliSecs
  //                 FROM times
  //                 ORDER BY MilliSecs DESC
  //                 LIMIT 1 OFFSET 30
  //             ) foo
  //         )'
  //     );
  // $stmt->execute();
?>