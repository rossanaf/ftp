<?php 
	include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  $queryraces = $db->query("SELECT race_id, race_type FROM races");
  $races = $queryraces->fetchAll();
  foreach ($races as $race) {
  	if (($race['race_type'] == 'jovem') || ($race['race_type'] == 'estJ')) {
      // LEITURA E CALCULO DOS TEMPOS PARA PROVAS JOVENS
      // echo 'jovens <br>';
      $cat = array('BEN', 'INF', 'INI', 'JUV', 'BEN', 'INF', 'INI', 'JUV');
      $gen = array('benf', 'inff', 'inif', 'juvf', 'benm', 'infm', 'inim', 'juvm');
      $sex = array('F', 'F', 'F', 'F', 'M', 'M', 'M', 'M');
      for ($i = 0; $i < 8; $i++) {
        $stmt = $db->prepare("SELECT gunshot_".$gen[$i]." FROM gunshots WHERE gunshot_race_id = ?");
        $stmt->execute([$race['race_id']]);
        $guns = $stmt->fetch();
        $gun = $guns['gunshot_'.$gen[$i]];
        $queryathletes = $db->prepare("SELECT * FROM athletes WHERE athlete_race_id = ? AND athlete_sex = ? AND athlete_category = ?");
        $queryathletes->execute([$race['race_id'], $sex[$i], $cat[$i]]);
        $athletes = $queryathletes->fetchAll();
        foreach ($athletes as $athlete) {
          if (($athlete['athlete_finishtime'] !== 'DNS') && ($athlete['athlete_finishtime'] !== 'DSQ') && ($athlete['athlete_finishtime'] !== 'DNF')) {
            $querymylaps = $db->prepare("SELECT * FROM times WHERE Chip = ? ORDER BY ChipTime");
            $querymylaps->execute([$athlete['athlete_chip']]);
            $times = $querymylaps->fetchAll();
            foreach ($times as $timelap) {
              list($date, $time) = explode (" ", $timelap['ChipTime']);
              // SUBTRAIR UMA HORA AO TEMPO ENVIADO PELO MYLAPS
				      $time = gmdate('H:i:s', strtotime($time)-strtotime("02:00:00"));
              $total = gmdate('H:i:s', strtotime($time)-strtotime($gun));
              $location = $timelap['Location']; 
              $started = $athlete['athlete_started'];
              //**** Como os Jovens é apenas 1 Tempo, apenas pesquisa nos T5 ****//
              if($timelap['Location'] == "TimeT5" && $athlete['athlete_t5'] == "-") {
                $querytimes = $db->prepare("UPDATE athletes SET athlete_t5 = :time, athlete_finishtime = :time, athlete_started = 5, athlete_totaltime = :total WHERE athlete_chip = :chip");
                $querytimes->execute(array(
                  ':time' => $time, 
                  ':total' => $total,
                  ':chip' => $timelap['Chip'],
                ));
              }
            }
          }
        }
        // $queryathletes = $db->prepare("SELECT athlete_id, athlete_finishtime FROM athletes WHERE athlete_started = 5 AND athlete_race_id = ? AND athlete_sex = ? AND athlete_category = ?");
        // $queryathletes->execute([$race['race_id'], $sex[$i], $cat[$i]]);
        // $athletes = $queryathletes->fetchAll();
        // foreach ($athletes as $athlete) {
        //   $ttotal = gmdate('H:i:s', strtotime($athlete['athlete_finishtime'])-strtotime($gun));
        //   $updateathletes = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_id = ?");
        //   $updateathletes->execute([$ttotal, $athlete['athlete_id']]);
        // }
      }
    }
  }
?>