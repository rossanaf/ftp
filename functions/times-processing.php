<?php 
  function processLiveTimes() {
  }

  function processTriathlonTimes($gun, $gender, $raceId, $raceType, $live, $db) {
    $t1 = '-';
    $t2 = '-';
    $t3 = '-';
    $t4 = '-';
    $t5 = '-';    
    $thisAthlete = 'A0';
    $queryathletes = $db->prepare("SELECT athlete_t0, athlete_t1, athlete_t2, athlete_t3, athlete_t4, athlete_t5, athlete_finishtime, athlete_started, ChipTime, Location, Chip FROM athletes INNER JOIN times ON athletes.athlete_chip = times.Chip where athlete_race_id = ? AND athlete_sex = ? ORDER BY Chip, ChipTime ASC");
    $queryathletes->execute([$raceId, $gender]);
    $athletes = $queryathletes->fetchAll();
    foreach ($athletes as $athlete) {
      if (($athlete['athlete_finishtime'] !== 'LAP') && ($athlete['athlete_finishtime'] !== 'DNS') && ($athlete['athlete_finishtime'] !== 'DSQ') && ($athlete['athlete_finishtime'] !== 'DNF')) {
        if($athlete['Chip'] !== $thisAthlete) {
          $t1 = $athlete['athlete_t1'];
          $t2 = $athlete['athlete_t2'];
          $t3 = $athlete['athlete_t3'];
          $t4 = $athlete['athlete_t4'];
          $t5 = $athlete['athlete_t5'];
        }
        $started = $athlete['athlete_started'];
        print_r($athlete['ChipTime']. ' - '.$athlete['Location'].'<br>');
        list($date, $chipTime) = explode (" ", $athlete['ChipTime']);
        print_r(' t1='.$t1.'<br> t2='.$t2.'<br> t3='.$t3.'<br> t4='.$t4.'<br> t5='.$t5.'<br> ChipTime='.$chipTime.'<br>');
        // SUBTRAIR UMA HORA AO TEMPO ENVIADO PELO MYLAPS
        // $chipTime = gmdate('H:i:s', strtotime($chipTime)+strtotime('01:00:00'));
        if ($raceType === 'crind') {
          $total = gmdate('H:i:s', strtotime($chipTime)-strtotime($athlete['athlete_t0']));
        } else {
          $total = gmdate('H:i:s', strtotime($chipTime)-strtotime($gun));
        }
        $location = $athlete['Location']; 
    //           /*
    //           $started = $athlete['athlete_started'];
    //           PODE NAO SER NECESSÁRIA ESTA VALIDAÇAO; SÓ CRIND TEM T0 E STARTED = -1
    //           if ($raceType == 'crind')
    //           {
    //               if ($chipTimelap['Location'] == "TimeT0")
    //               {
    //                   // echo '*'.$athlete['athlete_name']."<br>";
    //                   $stmtTimes = $db->prepare("UPDATE athletes SET athlete_t0 = ? WHERE athlete_chip = ?");
    //                   $stmtTimes->execute([$chipTime, $athlete['Chip']]);
    //                   if ($athlete['athlete_started'] == -1)
    //                   {
    //                       $stmtTimes = $db->prepare("UPDATE athletes SET athlete_started = 0 WHERE athlete_chip = ?");
    //                       $stmtTimes->execute([$athlete['Chip']]);
    //                       $started = 0;
    //                   }
    //                   // $chipTime = gmdate('H:i:s', strtotime($chipTime)-strtotime($gun)); 
    //                   // $stmt = $db->prepare("UPDATE live SET live_t0 = '".$chipTime."', live_finishtime = '".$total."', live_started = '".$started."' WHERE live_chip = '".$athlete['Chip']."'");
    //                   // $stmt->execute();
    //               }
    //           } */
        // **** Tempo T1 ****//  
        if($location === 'TimeT1' && $t1 === '-') {
          $stmtTimes = $db->prepare('
            UPDATE athletes, live 
            SET athlete_t1=:chipTime, live_t1=:total
            WHERE athlete_chip=:chip AND live_chip=:chip
          ');
          $stmtTimes->execute(array(
            ':chipTime' => $chipTime, 
            ':total' => $total,
            ':chip' => $athlete['Chip']
          ));
          $t1 = $chipTime;
          if($athlete['athlete_started'] == 0) {
            $started = 1;
            $stmtTimes = $db->prepare('
              UPDATE athletes, live 
              SET athlete_started=:started, athlete_finishtime=:emProva , live_started=:started, live_finishtime=:total
              WHERE athlete_chip=:chip AND live_chip=:chip
            ');
            $stmtTimes->execute(array(
              ':started' => $started,
              ':total' => $total,
              ':chip' => $athlete['Chip'],
              ':emProva' => '-'
            ));
          }
          // if ($raceType == 'crind') {
          //   $chipTime = gmdate('H:i:s', strtotime($chipTime)-strtotime($athlete['athlete_t0']));
          // } 
        }
        // **** Tempo T2 ****//
        if($location === 'TimeT2' && $t2 === '-') {
          $stmtTimes = $db->prepare("UPDATE athletes SET athlete_t2=? WHERE athlete_chip=?");
          $stmtTimes->execute([$chipTime, $athlete['Chip']]);
          $t2 = $chipTime;
          if($started <= 1) {
            $started = 2;
            $stmtTimes = $db->prepare('
              UPDATE athletes, live 
              SET athlete_started=:started, athlete_finishtime=:emProva , live_started=:started, live_finishtime=:total
              WHERE athlete_chip=:chip AND live_chip=:chip
            ');
            $stmtTimes->execute(array(
              ':started' => $started,
              ':total' => $total,
              ':chip' => $athlete['Chip'],
              ':emProva' => '-'
            ));
          }
          if ($t1 !== '-') {
            print_r('entra calculo segmento t2 '.$chipTime.' - '.$t1.'<br>');
            $segmentTime = gmdate('H:i:s', strtotime($chipTime)-strtotime($t1));
            $stmt = $db->prepare("UPDATE live SET live_t2=? WHERE live_chip=?");
            $stmt->execute([$segmentTime, $athlete['Chip']]);
          } 
        }
        // **** Tempo T3 ****//
        if($location === 'TimeT3' && $t3 === '-') {
          $stmtTimes = $db->prepare("UPDATE athletes SET athlete_t3=? WHERE athlete_chip=?");
          $stmtTimes->execute([$chipTime, $athlete['Chip']]);
          $t3 = $chipTime;
          if($started <= 2) {
            $started = 3;
            $stmtTimes = $db->prepare('
              UPDATE athletes, live 
              SET athlete_started=:started, athlete_finishtime=:emProva , live_started=:started, live_finishtime=:total
              WHERE athlete_chip=:chip AND live_chip=:chip
            ');
            $stmtTimes->execute(array(
              ':started' => $started,
              ':total' => $total,
              ':chip' => $athlete['Chip'],
              ':emProva' => '-'
            ));
          }
          if ($t2 !== '-') {
            print_r('entra calculo segmento t3 '.$chipTime.' - '.$t2.'<br>');
            $segmentTime = gmdate('H:i:s', strtotime($chipTime)-strtotime($t2));
            $stmt = $db->prepare("UPDATE live SET live_t3=? WHERE live_chip=?");
            $stmt->execute([$segmentTime, $athlete['Chip']]);
          } 
        }
        // **** Tempo T4 ****//
        if($location === 'TimeT4' && $t4 === '-') {
          print_r('t4 <br>');
          $stmtTimes = $db->prepare("UPDATE athletes SET athlete_t4=? WHERE athlete_chip=?");
          $stmtTimes->execute([$chipTime, $athlete['Chip']]);
          $t4 = $chipTime;
          if($started <= 3) {
            $started = 4;
            $stmtTimes = $db->prepare('
              UPDATE athletes, live 
              SET athlete_started=:started, athlete_finishtime=:emProva , live_started=:started, live_finishtime=:total
              WHERE athlete_chip=:chip AND live_chip=:chip
            ');
            $stmtTimes->execute(array(
              ':started' => $started,
              ':total' => $total,
              ':chip' => $athlete['Chip'],
              ':emProva' => '-'
            ));
          }
          if ($t3 !== '-') {
            print_r('entra calculo segmento t4 '.$chipTime.' - '.$t3.'<br>');
            $segmentTime = gmdate('H:i:s', strtotime($chipTime)-strtotime($t3));
            $stmt = $db->prepare("UPDATE live SET live_t4=? WHERE live_chip=?");
            $stmt->execute([$segmentTime, $athlete['Chip']]);
          } 
        }
        //**** Tempo T5 / Meta ****//
        if($location === "TimeT5" && $t5 === "-") {
          print_r('t5 <br>');
          $stmtTimes = $db->prepare("
            UPDATE athletes, live
            SET athlete_t5=:chipTime, athlete_finishtime=:chipTime, athlete_started=:started, athlete_totaltime=:total, live_started=:started, live_finishtime=:total 
            WHERE athlete_chip = :chip AND live_chip=:chip
          ");
          $stmtTimes->execute(array(
            ':chipTime' => $chipTime,
            ':started' => 5, 
            ':chip' => $athlete['Chip'],
            ':total' => $total
          ));
          $t5 = $chipTime;
          if ($t4 !== '-') {
            print_r('entra calculo segmento t5 '.$chipTime.' - '.$t4.'<br>');
            $segmentTime = gmdate('H:i:s', strtotime($chipTime)-strtotime($t4));
            $stmt = $db->prepare("UPDATE live SET live_t5=? WHERE live_chip=?");
            $stmt->execute([$segmentTime, $athlete['Chip']]);
          }             
        }
    //     // if ($raceType !== 'crind')
    //     // {
    //     //     $queryathletes = $db->prepare("SELECT athlete_id, athlete_finishtime FROM athletes WHERE athlete_started = 5 AND athlete_race_id = ?");
    //     //     $queryathletes->execute([$race['race_id']]);
    //     //     $athletes = $queryathletes->fetchAll();
    //     //     foreach ($athletes as $athlete) 
    //     //     {
    //     //         $ttotal = gmdate('H:i:s', strtotime($athlete['athlete_finishtime'])-strtotime($gun));
    //     //         $updateathletes = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_id = ?");
    //     //         $updateathletes->execute([$ttotal, $athlete['athlete_id']]);
    //     //     }
    //     // }
    //   }
      }
      $thisAthlete = $athlete['Chip']; 
    }
  // POSICAO DA TABELA LIVE ATUALIZADA POR GENERO - M
  // $pos = 1;
  // $queryathletes = $db->query("SELECT live_id FROM live WHERE live_started = 5 AND live_sex = 'M' ORDER BY live_finishtime");
  // $athletes = $queryathletes->fetchAll();
  // foreach ($athletes as $athlete) {
  //   $updatelive = $db->prepare("UPDATE live SET live_pos = ? WHERE live_id = ?");
  //   $updatelive->execute([$pos, $athlete['live_id']]);
  //   $pos ++; 
  // }
  // // POSICAO DA TABELA LIVE ATULIZADA POR GENERO  F
  // $pos = 1;
  // $queryathletes = $db->query("SELECT live_id FROM live WHERE live_started = 5 AND live_sex = 'F' ORDER BY live_finishtime");
  // $athletes = $queryathletes->fetchAll();
  // foreach ($athletes as $athlete) {
  //   $updatelive = $db->prepare("UPDATE live SET live_pos = ? WHERE live_id = ?");
  //   $updatelive->execute([$pos, $athlete['live_id']]);
  //   $pos ++; 
  }
?>