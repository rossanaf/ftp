<?php 
  function processTriathlonTimes($gun, $gender) {
    include_once($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
    // $queryraces = $db->query("SELECT race_id, race_gun_f, race_type FROM races WHERE race_gun_f != '-'");
    // $races = $queryraces->fetchAll();
    // LEITURA E CALCULO DOS TEMPOS PARA DUATLO E TRIATLOS E AQUATLOS
    print_r('eu processo os tempos para as tabelas atletas e live <br>');

    // if (($race['race_type']==='triatlo') || ($race['race_type']==='crind') || ($race['race_type']==='itu')  || ($race['race_type']==='aquathlon') || ($race['race_type']==='cre')) {
    //   $guns = array($race['race_gun_f'], $race['race_gun_m']);
    //   $genders = array('F', 'M');
    //   for ($i=0; $i<2; $i++) {
    //     $gun = $guns[$i];
    //     $queryathletes = $db->prepare("SELECT * FROM athletes INNER JOIN times ON athletes.athlete_chip = times.Chip where athlete_race_id = ? AND athlete_sex = ? ORDER BY ChipTime ASC");
    //     $queryathletes->execute([$race['race_id'], $genders[$i]]);
    //     $athletes = $queryathletes->fetchAll();
    //     foreach ($athletes as $athlete) {
    //       if (($athlete['athlete_finishtime'] !== 'LAP') && ($athlete['athlete_finishtime'] !== 'DNS') && ($athlete['athlete_finishtime'] !== 'DSQ') && ($athlete['athlete_finishtime'] !== 'DNF')) {
    //         $t1 = $athlete['athlete_t1'];
    //         $t2 = $athlete['athlete_t2'];
    //         $t3 = $athlete['athlete_t3'];
    //         $t4 = $athlete['athlete_t4'];
    //         $t5 = $athlete['athlete_t5'];
    //         $started = $athlete['athlete_started'];
    //         list($date, $time) = explode (" ", $athlete['ChipTime']);
    //         // SUBTRAIR UMA HORA AO TEMPO ENVIADO PELO MYLAPS
    //         $time = gmdate('H:i:s', strtotime($time)-strtotime('02:00:00'));
    //         if ($race['race_type'] == 'crind') {
    //           $total = gmdate('H:i:s', strtotime($time)-strtotime($athlete['athlete_t0']));
    //         } else {
    //           $total = gmdate('H:i:s', strtotime($time)-strtotime($gun));
    //         }
    //         $location = $athlete['Location']; 
    //           /*
    //           $started = $athlete['athlete_started'];
    //           PODE NAO SER NECESSÁRIA ESTA VALIDAÇAO; SÓ CRIND TEM T0 E STARTED = -1
    //           if ($race['race_type'] == 'crind')
    //           {
    //               if ($timelap['Location'] == "TimeT0")
    //               {
    //                   // echo '*'.$athlete['athlete_name']."<br>";
    //                   $querytimes = $db->prepare("UPDATE athletes SET athlete_t0 = ? WHERE athlete_chip = ?");
    //                   $querytimes->execute([$time, $athlete['Chip']]);
    //                   if ($athlete['athlete_started'] == -1)
    //                   {
    //                       $querytimes = $db->prepare("UPDATE athletes SET athlete_started = 0 WHERE athlete_chip = ?");
    //                       $querytimes->execute([$athlete['Chip']]);
    //                       $started = 0;
    //                   }
    //                   // $time = gmdate('H:i:s', strtotime($time)-strtotime($gun)); 
    //                   // $stmt = $db->prepare("UPDATE live SET live_t0 = '".$time."', live_finishtime = '".$total."', live_started = '".$started."' WHERE live_chip = '".$athlete['Chip']."'");
    //                   // $stmt->execute();
    //               }
    //           } */
    //         // **** Tempo T1 ****//
    //         if($location=="TimeT1" && $t1=="-") {
    //           $querytimes = $db->prepare("UPDATE athletes SET athlete_t1 = ? WHERE athlete_chip = ?");
    //           $querytimes->execute([$time, $athlete['Chip']]);
    //           $t1 = $time;
    //           if($athlete['athlete_started']==0) {
    //             $querytimes = $db->prepare("UPDATE athletes SET athlete_started = 1, athlete_finishtime = ? WHERE athlete_chip = ?");
    //             $querytimes->execute(['-', $athlete['Chip']]);
    //             $started = 1;
    //           }
    //           if ($race['race_type'] == 'crind') {
    //             $time = gmdate('H:i:s', strtotime($time)-strtotime($athlete['athlete_t0']));
    //           } 
    //           $stmt = $db->prepare("UPDATE live SET live_t1 = ?, live_finishtime = ?, live_started = ? WHERE live_chip = ?");
    //           $stmt->execute([$total, $total, $started, $athlete['Chip']]);
    //         }
    //         // **** Tempo T2 ****//
    //         if($location=="TimeT2" && $t2=="-") {
    //           $querytimes = $db->prepare("UPDATE athletes SET athlete_t2 = ? WHERE athlete_chip = ?");
    //           $querytimes->execute([$time, $athlete['Chip']]);
    //           $t2 = $time;
    //           if($started <= 1) {
    //             $querytimes = $db->prepare("UPDATE athletes SET athlete_started = 2, athlete_finishtime = ? WHERE athlete_chip = ?");
    //             $querytimes->execute(['-', $athlete['Chip']]);
    //             $started = 2;
    //           }
    //           if ($t1 !== "-") {
    //             $segment = gmdate('H:i:s', strtotime($t2)-strtotime($t1));
    //             $stmt = $db->prepare("UPDATE live SET live_t2 = ?, live_finishtime = ?, live_started = ? WHERE live_chip = ?");
    //             $stmt->execute([$segment, $total, $started, $athlete['Chip']]);
    //           } 
    //         }
    //         // **** Tempo T3 ****//
    //         if($location=="TimeT3" && $athlete['athlete_t3']=="-") {
    //           $querytimes = $db->prepare("UPDATE athletes SET athlete_t3 = ? WHERE athlete_chip = ?");
    //           $querytimes->execute([$time, $athlete['Chip']]);
    //           $t3 = $time;
    //           if($started<=2) {
    //             $querytimes = $db->prepare("UPDATE athletes SET athlete_started = 3, athlete_finishtime = ? WHERE athlete_chip = ?");
    //             $querytimes->execute(['-', $athlete['Chip']]);
    //             $started = 3;
    //           }
    //           if ($t2!=="-") {
    //             $segment = gmdate('H:i:s', strtotime($t3)-strtotime($t2));
    //             $stmt = $db->prepare("UPDATE live SET live_t3 = ?, live_finishtime = ?, live_started = ? WHERE live_chip = ?");
    //             $stmt->execute([$segment, $total, $started, $athlete['Chip']]);
    //           }
    //         }
    //         // **** Tempo T4 ****//
    //         if($location=="TimeT4" && $athlete['athlete_t4']=="-") {
    //           $querytimes = $db->prepare("UPDATE athletes SET athlete_t4 = ? WHERE athlete_chip = ?");
    //           $querytimes->execute([$time, $athlete['Chip']]);
    //           $t4 = $time;
    //           if($started<=3) {
    //             $querytimes = $db->prepare("UPDATE athletes SET athlete_started = 4, athlete_finishtime = ? WHERE athlete_chip = ?");
    //             $querytimes->execute(['-', $athlete['Chip']]);
    //             $started = 4;
    //           }
    //           if ($t3!=="-") {
    //             $segment = gmdate('H:i:s', strtotime($t4)-strtotime($t3));
    //             $stmt = $db->prepare("UPDATE live SET live_t4 = ?, live_finishtime = ?, live_started = ? WHERE live_chip = ?");
    //             $stmt->execute([$segment, $total, $started, $athlete['Chip']]);
    //           }
    //         }
    //         //**** Tempo T5 / Meta ****//
    //         if($location=="TimeT5" && $athlete['athlete_t5']=="-") {
    //           $querytimes = $db->prepare("UPDATE athletes SET athlete_t5 = :time1, athlete_finishtime = :time2, athlete_started = 5, athlete_totaltime = :total WHERE athlete_chip = :chip");
    //           $querytimes->execute(array(':time1' => $time,
    //             ':time2' => $time, 
    //             ':chip' => $athlete['Chip'],
    //             ':total' => $total
    //           ));
    //           $t5 = $time;
    //           if ($t4!=="-") {
    //             $segment = gmdate('H:i:s', strtotime($t5)-strtotime($t4));
    //             $stmt = $db->prepare("UPDATE live SET live_t5 = ?, live_finishtime = ?, live_started = 5 WHERE live_chip = ?");
    //             $stmt->execute([$segment, $total, $athlete['Chip']]);
    //           } else {
    //             // echo $total.' '.$started.' here OK <br>';
    //             // $time = gmdate('H:i:s', strtotime($time)-strtotime($t4));
    //             // $stmt = $db->prepare("UPDATE live SET live_t5 = '".$time."', live_finishtime = '".$total."', live_started = 5 WHERE live_chip = '".$athlete['Chip']."'");
    //             $stmt = $db->prepare("UPDATE live SET live_finishtime = ?, live_started = 5 WHERE live_chip = ?");
    //             $stmt->execute([$total, $athlete['Chip']]);
    //           }
    //         }
    //       }               
    //     }
    //     // if ($race['race_type'] !== 'crind')
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
    // } 
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
  // }
?>