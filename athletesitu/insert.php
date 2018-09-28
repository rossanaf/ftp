<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include ($_SERVER['DOCUMENT_ROOT']."/functions/isTime.php");
	// INICIALIZAR VARIÁVEIS
  $time = $_POST["time"] ?? '';
  $pos = $_POST["pos"] ?? '';
  $run = $_POST["run"] ?? '';
	$started = 0;
	$has_times = 0;
	$stmt_race = $db->prepare("SELECT race_type, race_gun_m, race_relay, race_live, race_id FROM races WHERE race_id = ?  LIMIT 1");
	$stmt_race->execute([$_POST["race"]]);
	$race = $stmt_race->fetch();
	if(isset($_POST["operation"])) {
		if($_POST["operation"] === "Add") {
			$stmt = $db->prepare("INSERT INTO athletes (athlete_chip, athlete_bib, athlete_name, athlete_sex, athlete_team_id, athlete_arrive_order, athlete_race_id) VALUES (:chip, :bib, :name, :sex, :team, :arriveOrder, :race)
			");
			$result = $stmt->execute(array(
				':chip'	=>	$_POST["chip"],
				':bib'	=>	$_POST["dorsal"],
				':name'		=>	$_POST["name"],
				':license'		=>	$_POST["licenca"],
				':sex'		=>	$_POST["sexo"],
				':category'		=>	$_POST["escalao"],
				':team'		=>	$_POST["clube"],
				':arriveOrder' => $_POST['ordem'],
				':race'		=>	$_POST["race"]
			));
		}
		if($_POST["operation"] === "Edit") {
			$posat = $_POST['ordem'];
			$bib = $_POST['dorsal'];
			if ($race['race_type'] === 'iturelay') {
				// EDITAR TEMPO DA PROVA DE ESTAFETAS
      	// $posat = $_POST['arriveOrder'];
      	// $bib = $_POST['dorsal'];
        if ($time === 'noDNS') {
        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime=?, athlete_pos=9999, athlete_started=0, athlete_t0="-", athlete_t1="-", athlete_t2="-", athlete_t3="-", athlete_t4="-", athlete_t5="-", athlete_totaltime="-", athlete_totaltime="-" WHERE athlete_bib=?');
        	$stmt->execute([$time, $bib]);
        	$stmtLive = $db->prepare('UPDATE live SET live_finishtime="DNS", live_pos=9999, live_started=0, live_t0="time", live_t1="time", live_t2="time", live_t3="time", live_t4="time", live_t5="time" WHERE live_bib=?');
        	$stmtLive->execute([$bib]);
        } else {
        	// SE COLOCAR EM CODIGO CHKIN (INICIO DE PROVA), RETIRA A PENALIZAÇÃO, PARA PERMITIR COLOCAR TEMPOS OU LER TEMPOS DE TIMES
        	if ($time === "nochkin") {
	        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime=?, athlete_pos=9999, athlete_started=0, athlete_t0="-", athlete_t1="-", athlete_t2="-", athlete_t3="-", athlete_t4="-", athlete_t5="-", athlete_totaltime="-", athlete_totaltime="-" WHERE athlete_bib=?');
	        	$stmt->execute([$time, $bib]);
	        	$stmtLive = $db->prepare('UPDATE live SET live_finishtime="time", live_pos=9999, live_started=0, live_t0="time", live_t1="time", live_t2="time", live_t3="time", live_t4="time", live_t5="time" WHERE live_bib=?');
	        	$stmtLive->execute([$bib]);
	        }
	        // IGUAL TRIATLO
        	if ($_POST['ordem'] == 1) {
        		$gun = $race['race_gun_m']; 
        	} else {
        		// LE O GUN DO ATLETA ANTERIOR
        		$stmt = $db->prepare('SELECT athlete_t0 FROM athletes WHERE athlete_bib=? AND athlete_arrive_order=? LIMIT 1');
        		$stmt->execute([$bib, $posat-1]);
        		$stmtResult = $stmt->fetch();
        		$gun = $stmtResult['athlete_t0'];
        	}
        	$live_swim = "time";
          $live_t1 = "time";
          $live_bike = "time";
          $live_t2 = "time";
          $live_run = "time";
          $finishtime = "time";
          $t0 = $gun;
          $swim = isTime($_POST['swim']);
          if ($swim !== "-") {
          	$live_swim = gmdate('H:i:s', strtotime($_POST['swim']) - strtotime($gun));
            $finishtime = $live_swim;
            $started = 1; $has_times = 1;
          }
          $t1 = isTime($_POST['t1']);
          if($t1 !== "-") {
            $started=2; $has_times = 1;
            $finishtime = gmdate('H:i:s', strtotime($_POST['t1']) - strtotime($gun));
            if ($swim !=="-") $live_t1 = gmdate('H:i:s', strtotime($_POST['t1']) - strtotime($_POST['swim']));
          }
          $bike = isTime($_POST['bike']);
          if($bike !== "-") {
              $started=3; $has_times = 1;
              $finishtime = gmdate('H:i:s', strtotime($_POST['bike']) - strtotime($gun));
              if ($t1 !== "-") $live_bike = gmdate('H:i:s', strtotime($_POST['bike']) - strtotime($_POST['t1']));
          }
          $t2 = isTime($_POST['t2']);
          if($t2 !== "-") {
            $started=4; $has_times = 1;
            $finishtime = gmdate('H:i:s', strtotime($_POST['t2']) - strtotime($gun));
            if ($bike !== "-") $live_t2 = gmdate('H:i:s', strtotime($_POST['t2']) - strtotime($_POST['bike']));
          }
        	// ESTAFETAS TEM APENAS O TEMPO T5
        	$run = isTime($_POST['run']);
        	if (($time==="DNF") || ($time==="DNS") || ($time==="chkin") || ($time==="DSQ") || ($time==="LAP")) {
          	$run = "-"; 
            $total = '-';
          } else {
          	$time = $run;
          }
          if ($run !== "-") {
						$started=5; $has_times = 0; $time = $run;
						$finishtime = gmdate('H:i:s', strtotime($_POST['run']) - strtotime($gun));
            if ($t2 !== "-") $live_run = gmdate('H:i:s', strtotime($_POST['run']) - strtotime($_POST['t2']));
					}
          if (($time==="DNF") || ($time==="DSQ") || ($time==="LAP")) {
        		$has_times = 0;
        		$finishtime = $time;
        	}
          if ($started !== 5) $pos = 9999;
          if ($has_times == 1) $time = '-';      
          $stmt = $db->prepare("UPDATE athletes SET athlete_chip=:chip, athlete_pos=:pos, athlete_bib=:dorsal, athlete_name=:name, athlete_sex=:sexo, athlete_team_id=:clube, athlete_t0=:t0, athlete_t1=:swim, athlete_t2=:t1, athlete_t3=:bike, athlete_t4=:t2, athlete_t5=:run, athlete_race_id=:race, athlete_finishtime=:time, athlete_started=:started, athlete_totaltime='-' WHERE athlete_id=:id"
          );
          $result = $stmt->execute(array(
            ':pos'  =>  $pos,
            ':chip' => $_POST["chip"],
            ':dorsal' =>  $_POST["dorsal"],
            ':name'   =>  $_POST["name"],
            ':sexo'   =>  $_POST["sexo"],
            ':clube'    =>  $_POST["clube"],
            ':t0'   =>  $t0,
            ':swim'   =>  $swim,
            ':t1'   =>  $t1,
            ':bike'   =>  $bike,
            ':t2'   =>  $t2,
            ':run'    =>  $run,
            ':race'   =>  $_POST["race"],
            ':time'   =>  $time,
            ':started'    =>  $started,
            ':id' =>  $_POST["user_id"]
          ));
          if ($race['race_live'] == 1) {
            $stmt_live = $db->prepare("UPDATE live SET live_chip = :chip, live_bib = :dorsal, live_team_id = :clube, live_t1 = :swim, live_t2 = :t1, live_t3 = :bike, live_t4 = :t2, live_t5 = :run, live_finishtime = :finishtime, live_started = :started, live_sex=:sex, live_pos=:pos WHERE live_id = :id"
            );
		        $stmt_live->execute(array(
							':id'	=>	$_POST["user_id"],
              ':chip' => $_POST["chip"],
							':dorsal'	=>	$_POST["dorsal"],
							':clube'		=>	$_POST["clube"],
              ':swim'		=>	$live_swim,
							':t1'		=>	$live_t1,
							':bike'		=>	$live_bike,
							':t2'		=>	$live_t2,
							':run'		=>	$live_run,
							':finishtime'		=>	$finishtime,
							':started' => $started,
              ':sex' => $_POST["sexo"],
              ':pos' => $pos
						));
			    }
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
     //      if ($run !== '-') {            
					// 	if ($posat === "A") {
     //          $total = gmdate('H:i:s', strtotime($run) - strtotime($gun));
					// 		$stmt = $db->prepare('UPDATE athletes SET athlete_t0=?, athlete_t5=?, athlete_finishtime="-", athlete_pos=9999, athlete_totaltime=? WHERE athlete_bib=?');
		   //      	$stmt->execute([$gun, $run, $total, $bib."A"]);
		   //      	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime="-", athlete_pos=9999, athlete_t0=? WHERE athlete_bib=?');
		   //      	$stmt->execute([$run, $bib."B"]);
		   //      	// PESQUISA SE #B TEM TEMPO FINAL E ATUALIZA
     //          $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib=? LIMIT 1');
     //          $stmt_b->execute([$bib.'B']);
     //          $b = $stmt_b->fetch();
     //          if ($b['athlete_t5'] !== '-') {
     //            $total = gmdate('H:i:s', strtotime($b['athlete_t5']) - strtotime($run));
     //            $stmt = $db->prepare('UPDATE athletes SET athlete_totaltime = ? WHERE athlete_bib = ?');
     //            $stmt->execute([$total, $bib.'B']);
     //          }
     //          // PESQUISA SE #C TEM TEMPO FINAL E ATUALIZA PARA 'EM PROVA'
     //          $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //          $stmt_b->execute([$bib.'C']);
     //          $b = $stmt_b->fetch();
     //          if ($b['athlete_t5'] === '-') {
     //            $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
     //            $stmt->execute([$bib.'C']);
     //          }
     //          if ($race['race_relay'] === 'X') {
     //          	// PESQUISA SE #D TEM TEMPO FINAL E ATUALIZA PARA 'EM PROVA'
     //            $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //            $stmt_b->execute([$bib.'D']);
     //            $b = $stmt_b->fetch();
     //            if ($b['athlete_t5'] === '-') {
     //              $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
     //              $stmt->execute([$bib.'D']);
     //            }
     //          }
					// 	} elseif ($posat === "B") {
					// 		if ($t0 === '-') {
					// 			$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_pos = 9999, athlete_finishtime = "-" WHERE athlete_bib = ?';
					// 			$stmt = $db->prepare($query);
					// 			$stmt->execute([$run, $bib."B"]);
					// 		} else {
					// 			$total = gmdate('H:i:s', strtotime($run) - strtotime($t0));
					// 			$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = "-", athlete_pos = 9999, athlete_totaltime = ? WHERE athlete_bib = ?';
					// 			$stmt = $db->prepare($query);
			  //       	$stmt->execute([$run, $total, $bib."B"]);
					// 		}
					// 		$stmt_next = $db->prepare('UPDATE athletes SET athlete_pos = 9999, athlete_t0 = ? WHERE athlete_bib = ?');
			  //       $stmt_next->execute([$run, $bib."C"]);
			  //       // PESQUISA SE #A TEM TEMPO FINAL E ATUALIZA PARA EM PROVA E COLOCA GUN
     //          $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //          $stmt_b->execute([$bib.'A']);
     //          $b = $stmt_b->fetch();
     //          if ($b['athlete_t5'] === '-') {
     //              $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-", athlete_t0=? WHERE athlete_bib = ?');
     //              $stmt->execute([$gun,$bib.'A']);
     //          }
			  //       // PESQUISA SE #C TEM TEMPO FINAL E ATUALIZA
     //          $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //          $stmt_b->execute([$bib.'C']);
     //          $b = $stmt_b->fetch();
     //          if ($b['athlete_t5'] !== '-') {
     //            $total = gmdate('H:i:s', strtotime($b['athlete_t5']) - strtotime($run));
     //            $stmt = $db->prepare('UPDATE athletes SET athlete_totaltime = ? WHERE athlete_bib = ?');
     //            $stmt->execute([$total, $bib.'C']);
     //          }
     //          if ($race['race_relay'] === 'X') {
     //          	// PESQUISA SE #D TEM TEMPO FINAL E ATUALIZA PARA 'EM PROVA'
     //            $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //            $stmt_b->execute([$bib.'D']);
     //            $b = $stmt_b->fetch();
     //            if ($b['athlete_t5'] === '-') {
     //              $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
     //              $stmt->execute([$bib.'D']);
     //            }
     //          }
					// 	} elseif ($posat === "C") {
					// 		if ($race['race_relay'] === 'X') {
					// 			if ($t0 === '-') {
					// 				$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_pos = 9999, athlete_finishtime = "-" WHERE athlete_bib = ?';
					// 				$stmt = $db->prepare($query);
					// 				$stmt->execute([$run, $bib."C"]);
					// 			} else {
					// 				$total = gmdate('H:i:s', strtotime($run) - strtotime($t0));
					// 				$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = "-", athlete_pos = 9999, athlete_totaltime = ? WHERE athlete_bib = ?';
					// 				$stmt = $db->prepare($query);
				 //        	$stmt->execute([$run, $total, $bib."C"]);
					// 			}
					// 			// PESQUISA SE #D TEM TEMPO FINAL E ATUALIZA
     //            $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //            $stmt_b->execute([$bib.'D']);
     //            $b = $stmt_b->fetch();
     //            if ($b['athlete_t5'] !== '-') {
     //              $total = gmdate('H:i:s', strtotime($b['athlete_t5']) - strtotime($run));
     //              $stmt = $db->prepare('UPDATE athletes SET athlete_totaltime = ? WHERE athlete_bib = ?');
     //              $stmt->execute([$total, $bib.'D']);
     //            }
					// 		} elseif ($race['race_relay'] === 'Y') {
					// 			$total_equipa = gmdate('H:i:s', strtotime($run) - strtotime($gun));
					// 			if ($t0 === '-') {
					// 				$query = 'UPDATE athletes SET athlete_t5=?, athlete_started=5, athlete_finishtime=?, athlete_pos=9999 WHERE athlete_bib=?';
					// 				$stmt = $db->prepare($query);
					// 				$stmt->execute([$run, $total_equipa, $bib."C"]);
					// 			} else {
				 //        	$total = gmdate('H:i:s', strtotime($run) - strtotime($t0));
					// 				// $total_equipa = gmdate('H:i:s', strtotime($run) - strtotime($gun));
					// 				$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = ?, athlete_started = 5, athlete_pos = 9999, athlete_totaltime = ? WHERE athlete_bib = ?';
					// 				$stmt = $db->prepare($query);
				 //        	$stmt->execute([$run, $total_equipa, $total, $bib."C"]);
					// 			}
					// 		}
					// 		// PESQUISA SE #A TEM TEMPO FINAL E ATUALIZA PARA EM PROVA E COLOCA GUN
     //          $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //          $stmt_b->execute([$bib.'A']);
     //          $b = $stmt_b->fetch();
     //          if ($b['athlete_t5'] === '-') {
     //            $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime="-", athlete_t0=? WHERE athlete_bib=?');
     //            $stmt->execute([$gun,$bib.'A']);
     //          }
     //          // PESQUISA SE #B TEM TEMPO FINAL E ATUALIZA PARA EM PROVA
     //          $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //          $stmt_b->execute([$bib.'B']);
     //          $b = $stmt_b->fetch();
     //          if ($b['athlete_t5'] === '-') {
     //            $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
     //            $stmt->execute([$bib.'B']);
     //          }
					// 	} elseif ($posat === "D") {
					// 		$total_equipa = gmdate('H:i:s', strtotime($run) - strtotime($gun));
					// 		if ($t0 === '-') {
					// 			$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_started = 5, athlete_finishtime = ?, athlete_pos = 9999 WHERE athlete_bib = ?';
					// 			$stmt = $db->prepare($query);
					// 			$stmt->execute([$run, $total_equipa, $bib."D"]);
					// 		} else {
			  //       	$total = gmdate('H:i:s', strtotime($run) - strtotime($t0));
					// 			// $total_equipa = gmdate('H:i:s', strtotime($run) - strtotime($gun));
					// 			$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = ?, athlete_started = 5, athlete_pos = 9999, athlete_totaltime = ? WHERE athlete_bib = ?';
					// 			$stmt = $db->prepare($query);
			  //       	$stmt->execute([$run, $total_equipa, $total, $bib."D"]);
					// 		}
					// 		// PESQUISA SE #A TEM TEMPO FINAL E ATUALIZA PARA EM PROVA E COLOCA GUN
     //          $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //          $stmt_b->execute([$bib.'A']);
     //          $b = $stmt_b->fetch();
     //          if ($b['athlete_t5'] === '-') {
     //            $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-", athlete_t0=? WHERE athlete_bib = ?');
     //            $stmt->execute([$gun,$bib.'A']);
     //          }
     //          // PESQUISA SE #B TEM TEMPO FINAL E ATUALIZA PARA EM PROVA
     //          $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //          $stmt_b->execute([$bib.'B']);
     //          $b = $stmt_b->fetch();
     //          if ($b['athlete_t5'] === '-') {
     //            $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
     //            $stmt->execute([$bib.'B']);
     //          }
     //          // PESQUISA SE #B TEM TEMPO FINAL E ATUALIZA PARA EM PROVA
     //          $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
     //          $stmt_b->execute([$bib.'C']);
     //          $b = $stmt_b->fetch();
     //          if ($b['athlete_t5'] === '-') {
     //            $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
     //            $stmt->execute([$bib.'C']);
     //          }
					// 	}
					// } elseif ($run === '-') {
					// 	if ($posat === "A") {
					// 		$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-", athlete_t5 = "-" WHERE athlete_bib = ?');
		   //      	$stmt->execute([$bib."A"]);
		   //      	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-", athlete_t0 = "-" WHERE athlete_bib = ?');
		   //      	$stmt->execute([$bib."B"]);
					// 	} elseif ($posat == "B") {
					// 		$query = 'UPDATE athletes SET athlete_t5 = "-" WHERE athlete_bib = ?';
					// 		$stmt = $db->prepare($query);
		   //      	$stmt->execute([$bib."B"]);
					// 		$stmt_next = $db->prepare('UPDATE athletes SET athlete_t0 = "-" WHERE athlete_bib = ?');
		   //      	$stmt_next->execute([$bib."C"]);
					// 	} elseif ($posat === "C") {
					// 		$query = 'UPDATE athletes SET athlete_t5 = "-", athlete_started = 0, athlete_pos = 9999 WHERE athlete_bib = ?';
					// 		$stmt = $db->prepare($query);
		   //      	$stmt->execute([$bib."C"]);
					// 	}
					// }
					// if (($time === 'DNF') || ($time === 'DSQ') || ($time === 'LAP')) {
						if ($posat === "A") {
							$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0, athlete_t5="-", athlete_totaltime="-" WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."A"]);
		        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0, athlete_t5="-", athlete_t0="-", athlete_totaltime="-" WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."B"]);
		        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_t5 = "-", athlete_pos = 9999, athlete_totaltime = "-", athlete_started = 0, athlete_t0="-" WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."C"]);
						} elseif ($posat === "B") {
							$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0 WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."A"]);
		        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0, athlete_t5="-", athlete_totaltime="-" WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."B"]);
		        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_t5 = "-", athlete_pos = 9999, athlete_totaltime = "-", athlete_started = 0, athlete_t0="-" WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."C"]);
						} elseif ($posat === "C") {
							$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0 WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."A"]);
		        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0 WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."B"]);
		        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_t5 = "-", athlete_pos = 9999, athlete_totaltime = "-", athlete_started = 0 WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."C"]);
						}
						// $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0 WHERE athlete_bib = ?');
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////						
			   //      	$stmt->execute([$time, $bib."A"]);
			   //      	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0 WHERE athlete_bib = ?');
			   //      	$stmt->execute([$time, $bib."B"]);
			   //      	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_t5 = "-", athlete_pos = 9999, athlete_totaltime = "-", athlete_started = 0 WHERE athlete_bib = ?');
			   //      	$stmt->execute([$time, $bib."C"]);
			        	// if ($posat == 'C')
			        	// {
			        	// 	$chip = $_POST["chip"];
			        	// } else {
			        	// 	$del_chip = $db->prepare("SELECT athlete_chip FROM athletes WHERE athlete_bib = ? LIMIT 1");
			        	// 	$del_chip->execute([$bib."C"]);
			        	// 	$to_del_chip = $del_chip->fetch();
			        	// 	$chip = $to_del_chip['athlete_chip'];
			        	// }
			        	// $del_t5 = $db->prepare("DELETE FROM times WHERE Chip=? AND Location='TimeT5'");
	           //  		$del_t5->execute([$chip]);
					}
					// FALTA QUANDO ESTIVER PREENCHIDO O TEMPO DE META EM VEZ DA HORA DO DIA
				}
				$stmt = $db->prepare('UPDATE athletes SET athlete_chip = :chip, athlete_bib = :dorsal, athlete_name = :name, athlete_sex = :sexo, athlete_team_id = :clube WHERE athlete_id = :id');
				$stmt->execute(array(
          ':chip' => $_POST["chip"],
					':dorsal'	=>	$_POST["dorsal"],
					':name'		=>	$_POST["name"],
          ':sexo'		=>	$_POST["sexo"],
					':clube'		=>	$_POST["clube"],
					':id'	=>	$_POST["user_id"]
				));
			}
		}
  }
  if ($run !== '-') {
		//**** atualizar coluna 'pos' conforme tempo total, para validar com registo de meta
	  $pos = 1;
    $queryathletes = $db->query("SELECT athlete_chip FROM athletes WHERE athlete_started = 5 ORDER BY athlete_t5");
    $athletes = $queryathletes->fetchAll(); 
    foreach ($athletes as $athlete) {
      $updateathletes = $db->prepare("UPDATE athletes SET athlete_pos = ? WHERE athlete_chip = ?");
      $updateathletes->execute([$pos, $athlete['athlete_chip']]);
      $pos++;
    }
    include_once($_SERVER['DOCUMENT_ROOT']."/functions/times-processing.php");
    processLivePositions($_POST["sexo"], $race['race_id'], $db);
	}
?>