<?php 
  function processLiveTimes($chip, $t1, $t2, $t3, $t4, $t5, $db) {
    // MELHORAR CODIGO PARA NAO VIR SEMPRE AQUI
    // SO PODE CORRER SE TABELA TIME NAO TIVER TEMPOS
    $stmtLive = $db->prepare('SELECT live_t2, live_t3, live_t4, live_t5 FROM live WHERE live_chip=? LIMIT 1');
    $stmtLive->execute([$chip]);
    $liveAthlete = $stmtLive->fetch();
    if($liveAthlete['live_t2'] == 'time') {
      if ($t1 !== '-' && $t2 !== '-') {
        $segmentTime = gmdate('H:i:s', strtotime($t2)-strtotime($t1));
        $stmt = $db->prepare("UPDATE live SET live_t2=? WHERE live_chip=?");
        $stmt->execute([$segmentTime, $chip]);
      } 
    }
    if($liveAthlete['live_t3'] === 'time') {
      if ($t2 !== '-' && $t3 !== '-') {
        $segmentTime = gmdate('H:i:s', strtotime($t3)-strtotime($t2));
        $stmt = $db->prepare("UPDATE live SET live_t3=? WHERE live_chip=?");
        $stmt->execute([$segmentTime, $chip]);
      }
    }
    if($liveAthlete['live_t4'] === 'time') {
      if ($t3 !== '-' && $t4 !== '-') {
        $segmentTime = gmdate('H:i:s', strtotime($t4)-strtotime($t3));
        $stmt = $db->prepare("UPDATE live SET live_t4=? WHERE live_chip=?");
        $stmt->execute([$segmentTime, $chip]);
      }
    }
    if($liveAthlete['live_t5'] === 'time') {
      if ($t4 !== '-' && $t5 !== '-') {
        $segmentTime = gmdate('H:i:s', strtotime($t5)-strtotime($t4));
        $stmt = $db->prepare("UPDATE live SET live_t5=? WHERE live_chip=?");
        $stmt->execute([$segmentTime, $chip]);
      }
    }
  }

  function processLivePositions($gender, $raceId, $db) {
    $pos = 1;
    $stmt = $db->prepare('SELECT live_id FROM live WHERE live_started=5 AND live_sex=? AND live_race=? ORDER BY live_finishtime ASC');
    $stmt->execute([$gender, $raceId]);
    $athletes = $stmt->fetchAll();
    foreach ($athletes as $athlete) {
      $stmtUpdate = $db->prepare('UPDATE live SET live_pos=? WHERE live_id=?');
      $stmtUpdate->execute([$pos, $athlete['live_id']]);
      $pos++;
    }
  }

  function processTriathlonTimes($gun, $gender, $raceId, $raceType, $raceRelay, $live, $db) {
    $thisAthlete = 'A1';
    $livePositionsF = 0;
    $livePositionsM = 0;
    $queryathletes = $db->prepare("SELECT athlete_bib, athlete_t0, athlete_t1, athlete_t2, athlete_t3, athlete_t4, athlete_t5, athlete_finishtime, athlete_started, ChipTime, Location, Chip FROM athletes INNER JOIN times ON athletes.athlete_chip = times.Chip where athlete_race_id = ? AND athlete_sex = ? ORDER BY Chip, ChipTime ASC");
    $queryathletes->execute([$raceId, $gender]);
    $athletes = $queryathletes->fetchAll();
    foreach ($athletes as $athlete) {
      // PARA AS PARTIDAS POR VAGAS, MESMO QUE HAJA GUN NA TABELA RACES, TEM DE HAVER T0 PARA CADA ATLETA
      if($athlete['athlete_t0'] !== '-') {
        if (($athlete['athlete_finishtime'] !== 'LAP') && ($athlete['athlete_finishtime'] !== 'DNS') && ($athlete['athlete_finishtime'] !== 'DSQ') && ($athlete['athlete_finishtime'] !== 'DNF')) {
          if($athlete['Chip'] !== $thisAthlete) {
            if($thisAthlete !== 'A1' && $live == 1) {
              processLiveTimes($thisAthlete, $t1, $t2, $t3, $t4, $t5, $db);
            }
            $t1 = $athlete['athlete_t1'];
            $t2 = $athlete['athlete_t2'];
            $t3 = $athlete['athlete_t3'];
            $t4 = $athlete['athlete_t4'];
            $t5 = $athlete['athlete_t5'];
          }
          $started = $athlete['athlete_started'];
          list($date, $chipTime) = explode (" ", $athlete['ChipTime']);
          // SUBTRAIR UMA HORA AO TEMPO ENVIADO PELO MYLAPS
          // $chipTime = gmdate('H:i:s', strtotime($chipTime)+strtotime('01:00:00'));
          if ($raceType === 'crind' || $raceRelay === 'X') {
            $total = gmdate('H:i:s', strtotime($chipTime)-strtotime($athlete['athlete_t0']));
          } else {
            $total = gmdate('H:i:s', strtotime($chipTime)-strtotime($gun));
          }
          $location = $athlete['Location']; 
          if ($location === 'TimeT5')  {
            if ($livePositionsF === 0 && $gender === 'F') {
              $livePositionsF = 1;
            }
            if ($livePositionsM === 0 & $gender === 'M') {
              $livePositionsM = 1;
            }
          }
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
          }
          // **** Tempo T4 ****//
          if($location === 'TimeT4' && $t4 === '-') {
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
          }
          //**** Tempo T5 / Meta ****//
          if($location === "TimeT5" && $t5 === "-") {
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
          }
          // ELIMINAR TEMPO DA TABELA TIMES, MOVER PARA UMA TABELA PARALELA
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
    }
    if ($live == 1) {
      if ($thisAthlete !== 'A1') {
        processLiveTimes($thisAthlete, $t1, $t2, $t3, $t4, $t5, $db);
      }
      if ($livePositionsF === 1) {
        processLivePositions('F', $raceId, $db);
      } 
      if ($livePositionsM === 1) {
        processLivePositions('M', $raceId, $db);
      } 
    }
  }

  function processLivePositionsMxRelay($raceId, $db) {
    $stmt = $db->query('SELECT live_bib FROM live WHERE live_license=4 AND live_started=5 ORDER BY live_t0');
    $finishers = $stmt->fetchAll();
    $pos=1;
    foreach ($finishers as $finisher) {
      $stmtUpdate = $db->prepare('UPDATE live SET live_pos=? WHERE live_bib=?');
      $stmtUpdate->execute([$pos, $finisher['live_bib']]);
      $pos++;
    }
  }

  function processLiveTimesMxRelay($db) {
    // MELHORAR CODIGO PARA NAO VIR SEMPRE AQUI
    // SO PODE CORRER SE TABELA TIME NAO TIVER TEMPOS
    $stmt = $db->query('SELECT athlete_t1, athlete_t2, athlete_t3, athlete_t4, athlete_t5, athlete_chip FROM athletes WHERE athlete_started>0');
    $athletes = $stmt->fetchAll();
    foreach ($athletes as $athlete) {
      $t1 = $athlete['athlete_t1'];
      $t2 = $athlete['athlete_t2'];
      $t3 = $athlete['athlete_t3'];
      $t4 = $athlete['athlete_t4'];
      $t5 = $athlete['athlete_t5'];
      $chip = $athlete['athlete_chip'];
      $stmtLive = $db->prepare('SELECT live_t2, live_t3, live_t4, live_t5 FROM live WHERE live_chip=? LIMIT 1');
      $stmtLive->execute([$chip]);
      $liveAthlete = $stmtLive->fetch();
      if($liveAthlete['live_t2'] == 'time') {
        if ($t1 !== '-' && $t2 !== '-') {
          $segmentTime = gmdate('H:i:s', strtotime($t2)-strtotime($t1));
          $stmt = $db->prepare("UPDATE live SET live_t2=? WHERE live_chip=?");
          $stmt->execute([$segmentTime, $chip]);
        } 
      }
      if($liveAthlete['live_t3'] === 'time') {
        if ($t2 !== '-' && $t3 !== '-') {
          $segmentTime = gmdate('H:i:s', strtotime($t3)-strtotime($t2));
          $stmt = $db->prepare("UPDATE live SET live_t3=? WHERE live_chip=?");
          $stmt->execute([$segmentTime, $chip]);
        }
      }
      if($liveAthlete['live_t4'] === 'time') {
        if ($t3 !== '-' && $t4 !== '-') {
          $segmentTime = gmdate('H:i:s', strtotime($t4)-strtotime($t3));
          $stmt = $db->prepare("UPDATE live SET live_t4=? WHERE live_chip=?");
          $stmt->execute([$segmentTime, $chip]);
        }
      }
      if($liveAthlete['live_t5'] === 'time') {
        if ($t4 !== '-' && $t5 !== '-') {
          $segmentTime = gmdate('H:i:s', strtotime($t5)-strtotime($t4));
          $stmt = $db->prepare("UPDATE live SET live_t5=? WHERE live_chip=?");
          $stmt->execute([$segmentTime, $chip]);
        }
      }
    }
  }

  function processTriathlonTimesMxRelay($raceId, $raceType, $live, $gun, $db) {
    $thisAthlete = 'A1';
    $livePositions = 0;
    $queryathletes = $db->prepare("SELECT athlete_t1, athlete_t2, athlete_t3, athlete_t4, athlete_t5, athlete_finishtime, athlete_started, athlete_bib, athlete_arrive_order, ChipTime, Location, Chip FROM athletes INNER JOIN times ON athletes.athlete_chip=times.Chip where athlete_race_id=? ORDER BY ChipTime ASC");
    $queryathletes->execute([$raceId]);
    $athletes = $queryathletes->fetchAll();
    foreach ($athletes as $athlete) {
      if (($athlete['athlete_finishtime'] !== 'LAP') && ($athlete['athlete_finishtime'] !== 'DNS') && ($athlete['athlete_finishtime'] !== 'DSQ') && ($athlete['athlete_finishtime'] !== 'DNF')) {
        $t1 = $athlete['athlete_t1'];
        $t2 = $athlete['athlete_t2'];
        $t3 = $athlete['athlete_t3'];
        $t4 = $athlete['athlete_t4'];
        $t5 = $athlete['athlete_t5'];
        $bib = $athlete['athlete_bib'];
        $curAthleteOrder = $athlete['athlete_arrive_order'];
        $nextOrder = $curAthleteOrder + 1;
        $started = $athlete['athlete_started'];
        list($date, $chipTime) = explode (" ", $athlete['ChipTime']);
        // SUBTRAIR UMA HORA AO TEMPO ENVIADO PELO MYLAPS
        // $chipTime = gmdate('H:i:s', strtotime($chipTime)+strtotime('01:00:00'));
        $stmtT0 = $db->prepare('SELECT athlete_t0 FROM athletes WHERE athlete_chip=? LIMIT 1');
        $stmtT0->execute([$athlete['Chip']]);
        $t0 = $stmtT0->fetch();
        if ($t0['athlete_t0'] === '-') {
          $total = 'time';
        } else {
          $total = gmdate('H:i:s', strtotime($chipTime)-strtotime($t0['athlete_t0']));
        }
        $location = $athlete['Location']; 
        if ($location === 'TimeT5' && $livePositions == 0)  {
          $livePositions = 1;
        }
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
        }
        // **** Tempo T4 ****//
        if($location === 'TimeT4' && $t4 === '-') {
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
        }
        //**** Tempo T5 / Meta ****//
        if($location === "TimeT5" && $t5 === "-") {
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
          if($raceType === 'iturelay') {
            $stmtUpdatePreviousT0 = $db->prepare("UPDATE athletes SET athlete_t0=? WHERE athlete_bib=? AND athlete_arrive_order=?");
            $stmtUpdatePreviousT0->execute([$t5, $bib, $nextOrder]);
          }
        }
      }
      $thisAthlete = $athlete['Chip']; 
    }
    if ($live == 1) {
      processLiveTimesMxRelay($db);
      // CODIGO REPETIDO
      $stmtFinisher = $db->prepare('SELECT live_bib, athlete_t5 FROM live JOIN athletes ON live_chip=athlete_chip WHERE live_license=4 AND live_started=5 AND live_race=?');
      $stmtFinisher->execute([$raceId]);
      $finishers = $stmtFinisher->fetchAll();
      foreach ($finishers as $finisher) {
        $bib = $finisher['live_bib'];
        $teamTotalTime = gmdate('H:i:s', strtotime($finisher['athlete_t5']) - strtotime($gun));   
        $stmtUpdate = $db->prepare('UPDATE live SET live_t0=? WHERE live_bib=?');
        $stmtUpdate->execute([$teamTotalTime, $bib]);
      }
      // FIM CODIGO REPETIDO
      if ($livePositions == 1) {
        processLivePositionsMxRelay($raceId, $db);
      } 
    }
  }

  function processTriathlonTimesRelayPt($raceId, $raceType, $live, $gun, $gender, $db) {
    $thisAthlete = 'A1';
    $livePositions = 0;
    $queryathletes = $db->prepare("SELECT athlete_t1, athlete_t2, athlete_t3, athlete_t4, athlete_t5, athlete_finishtime, athlete_started, athlete_bib, athlete_arrive_order, ChipTime, Location, Chip FROM athletes INNER JOIN times ON athletes.athlete_chip=times.Chip where athlete_race_id=? ORDER BY ChipTime ASC");
    $queryathletes->execute([$raceId]);
    $athletes = $queryathletes->fetchAll();
    foreach ($athletes as $athlete) {
      if (($athlete['athlete_finishtime'] !== 'LAP') && ($athlete['athlete_finishtime'] !== 'DNS') && ($athlete['athlete_finishtime'] !== 'DSQ') && ($athlete['athlete_finishtime'] !== 'DNF')) {
        $t1 = $athlete['athlete_t1'];
        $t2 = $athlete['athlete_t2'];
        $t3 = $athlete['athlete_t3'];
        $t4 = $athlete['athlete_t4'];
        $t5 = $athlete['athlete_t5'];
        $bib = $athlete['athlete_bib'];
        $curAthleteOrder = $athlete['athlete_arrive_order'];
        $nextOrder = $curAthleteOrder + 1;
        $started = $athlete['athlete_started'];
        list($date, $chipTime) = explode (" ", $athlete['ChipTime']);
        // SUBTRAIR UMA HORA AO TEMPO ENVIADO PELO MYLAPS
        // $chipTime = gmdate('H:i:s', strtotime($chipTime)+strtotime('01:00:00'));
        $stmtT0 = $db->prepare('SELECT athlete_t0 FROM athletes WHERE athlete_chip=? LIMIT 1');
        $stmtT0->execute([$athlete['Chip']]);
        $t0 = $stmtT0->fetch();
        if ($t0['athlete_t0'] === '-') {
          $total = 'time';
        } else {
          $total = gmdate('H:i:s', strtotime($chipTime)-strtotime($t0['athlete_t0']));
        }
        $location = $athlete['Location']; 
        if ($location === 'TimeT5' && $livePositions == 0)  {
          $livePositions = 1;
        }
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
        }
        // **** Tempo T4 ****//
        if($location === 'TimeT4' && $t4 === '-') {
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
        }
        //**** Tempo T5 / Meta ****//
        if($location === "TimeT5" && $t5 === "-") {
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
          if($raceType === 'iturelay') {
            $stmtUpdatePreviousT0 = $db->prepare("UPDATE athletes SET athlete_t0=? WHERE athlete_bib=? AND athlete_arrive_order=?");
            $stmtUpdatePreviousT0->execute([$t5, $bib, $nextOrder]);
          }
        }
      }
      $thisAthlete = $athlete['Chip']; 
    }
    if ($live == 1) {
      processLiveTimesMxRelay($db);
      // CODIGO REPETIDO
      $stmtFinisher = $db->prepare('SELECT live_bib, athlete_t5 FROM live JOIN athletes ON live_chip=athlete_chip WHERE live_license=4 AND live_started=5 AND live_race=?');
      $stmtFinisher->execute([$race['race_id']]);
      $finishers = $stmtFinisher->fetchAll();
      foreach ($finishers as $finisher) {
        $bib = $finisher['live_bib'];
        $teamTotalTime = gmdate('H:i:s', strtotime($finisher['athlete_t5']) - strtotime($race['race_gun_m']));   
        $stmtUpdate = $db->prepare('UPDATE live SET live_t0=? WHERE live_bib=?');
        $stmtUpdate->execute([$teamTotalTime, $bib]);
      }
      // FIM CODIGO REPETIDO
      if ($livePositions == 1) {
        processLivePositionsMxRelay($raceId, $db);
      } 
    }
  }
?>