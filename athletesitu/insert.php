<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include ($_SERVER['DOCUMENT_ROOT']."/functions/isTime.php");
	// INICIALIZAR VARIÁVEIS
  $time = $_POST['time'] ?? '';
  $pos = $_POST["pos"] ?? '';
	$started = 0;
	$has_times = 0;
	$stmt_race = $db->prepare("SELECT race_type, race_gun_m, race_gun_f, race_relay, race_live, race_id FROM races WHERE race_id = ?  LIMIT 1");
	$stmt_race->execute([$_POST["race"]]);
	$race = $stmt_race->fetch();
	if(isset($_POST["operation"])) {
		if($_POST["operation"] === "Add") {
			$stmt = $db->prepare("INSERT INTO athletes (athlete_chip, athlete_license, athlete_bib, athlete_name, athlete_sex, athlete_category, athlete_team_id,  athlete_race_id) VALUES (:chip, :license, :bib, :name, :sex, :category, :team, :race)
			");
			$result = $stmt->execute(array(
				':chip'	=>	$_POST["chip"],
				':bib'	=>	$_POST["dorsal"],
				':name'		=>	$_POST["name"],
				':license'		=>	$_POST["licenca"],
				':sex'		=>	$_POST["sexo"],
				':category'		=>	$_POST["escalao"],
				':team'		=>	$_POST["clube"],
				':race'		=>	$_POST["race"]
			));
			/* *****************************
			PROCURAR O NOME DA EQUIPA E VER SE É CONTRARRELOGIO
			SE FOR CONTRARRELOGIO TEM DE COLOCAR STARTED = -1
			****************************** */
			$stmt_live = $db->prepare("INSERT INTO live (live_chip, live_bib, live_firstname, live_team, live_sex, live_category) VALUES (:chip, :bib, :firstname, :team, :sex, :category)
			");
			$stmt_live->execute(array(
				':chip'	=>	$_POST["chip"],
				':bib'	=>	$_POST["dorsal"],
				':firstname'		=>	$_POST["name"],
				':team'		=>	$team['team_name'],
				':sex' => $_POST["sexo"],
				':category'		=>	$_POST["escalao"]
			));
		}
		if($_POST["operation"] === "Edit") {
			if (($race['race_type'] === 'relay') || ($race['race_type'] === 'jEstf')) {
				// EDITAR TEMPO DA PROVA DE ESTAFETAS
      	$posat = substr($_POST["dorsal"], -1);
      	$bib = rtrim($_POST["dorsal"],$posat);
        if ($time === "DNS") {
        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0, athlete_t0="-", athlete_t5="-", athlete_totaltime = "-", athlete_totaltime = "-" WHERE athlete_bib = ?');
        	$stmt->execute([$time, $bib."A"]);
        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0, athlete_t0="-", athlete_t5="-", athlete_totaltime = "-" WHERE athlete_bib = ?');
        	$stmt->execute([$time, $bib."B"]);
        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_t5 = "-", athlete_pos = 9999, athlete_totaltime = "-", athlete_started = 0, athlete_t0="-" WHERE athlete_bib = ?');
        	$stmt->execute([$time, $bib."C"]);
        	if ($race['race_relay'] == 'X') {
        		$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_t5 = "-", athlete_pos = 9999, athlete_totaltime = "-", athlete_started = 0, athlete_t0="-" WHERE athlete_bib = ?');
        		$stmt->execute([$time, $bib."D"]);
        	}
        } else {
        	// SE COLOCAR EM CODIGO CHKIN (INICIO DE PROVA), RETIRA A PENALIZAÇÃO, PARA PERMITIR COLOCAR TEMPOS OU LER TEMPOS DE TIMES
        	if ($time === "chkin") {
	        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0, athlete_t0="-", athlete_t5="-", athlete_totaltime = "-", athlete_totaltime = "-" WHERE athlete_bib = ?');
	        	$stmt->execute([$time, $bib."A"]);
	        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_pos = 9999, athlete_started=0, athlete_t0="-", athlete_t5="-", athlete_totaltime = "-" WHERE athlete_bib = ?');
	        	$stmt->execute([$time, $bib."B"]);
	        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_t5 = "-", athlete_pos = 9999, athlete_totaltime = "-", athlete_started = 0, athlete_t0="-" WHERE athlete_bib = ?');
	        	$stmt->execute([$time, $bib."C"]);
	        	if ($race['race_relay'] === 'X') {
	        		$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = ?, athlete_t5 = "-", athlete_pos = 9999, athlete_totaltime = "-", athlete_started = 0, athlete_t0="-" WHERE athlete_bib = ?');
		        	$stmt->execute([$time, $bib."D"]);
	        	}
	        }
        	// ESTAFETAS TEM APENAS O TEMPO T5
        	$run = isTime($_POST['run']);
        	$t0 = $_POST['t0'];
          if ($run !== '-') {
            if ($_POST['sexo'] === 'F') $gun = $race['race_gun_f'];
            elseif ($_POST['sexo'] === 'M') $gun = $race['race_gun_m'];
						if ($posat === "A") {
              $total = gmdate('H:i:s', strtotime($run) - strtotime($gun));
							$stmt = $db->prepare('UPDATE athletes SET athlete_t0=?, athlete_t5=?, athlete_finishtime="-", athlete_pos=9999, athlete_totaltime=? WHERE athlete_bib=?');
		        	$stmt->execute([$gun, $run, $total, $bib."A"]);
		        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime="-", athlete_pos=9999, athlete_t0=? WHERE athlete_bib=?');
		        	$stmt->execute([$run, $bib."B"]);
		        	// PESQUISA SE #B TEM TEMPO FINAL E ATUALIZA
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib=? LIMIT 1');
              $stmt_b->execute([$bib.'B']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] !== '-') {
                $total = gmdate('H:i:s', strtotime($b['athlete_t5']) - strtotime($run));
                $stmt = $db->prepare('UPDATE athletes SET athlete_totaltime = ? WHERE athlete_bib = ?');
                $stmt->execute([$total, $bib.'B']);
              }
              // PESQUISA SE #C TEM TEMPO FINAL E ATUALIZA PARA 'EM PROVA'
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'C']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] === '-') {
                $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
                $stmt->execute([$bib.'C']);
              }
              if ($race['race_relay'] === 'X') {
              	// PESQUISA SE #D TEM TEMPO FINAL E ATUALIZA PARA 'EM PROVA'
                $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
                $stmt_b->execute([$bib.'D']);
                $b = $stmt_b->fetch();
                if ($b['athlete_t5'] === '-') {
                  $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
                  $stmt->execute([$bib.'D']);
                }
              }
						} elseif ($posat === "B") {
							if ($t0 === '-') {
								$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_pos = 9999, athlete_finishtime = "-" WHERE athlete_bib = ?';
								$stmt = $db->prepare($query);
								$stmt->execute([$run, $bib."B"]);
							} else {
								$total = gmdate('H:i:s', strtotime($run) - strtotime($t0));
								$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = "-", athlete_pos = 9999, athlete_totaltime = ? WHERE athlete_bib = ?';
								$stmt = $db->prepare($query);
			        	$stmt->execute([$run, $total, $bib."B"]);
							}
							$stmt_next = $db->prepare('UPDATE athletes SET athlete_pos = 9999, athlete_t0 = ? WHERE athlete_bib = ?');
			        $stmt_next->execute([$run, $bib."C"]);
			        // PESQUISA SE #A TEM TEMPO FINAL E ATUALIZA PARA EM PROVA E COLOCA GUN
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'A']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] === '-') {
                  $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-", athlete_t0=? WHERE athlete_bib = ?');
                  $stmt->execute([$gun,$bib.'A']);
              }
			        // PESQUISA SE #C TEM TEMPO FINAL E ATUALIZA
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'C']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] !== '-') {
                $total = gmdate('H:i:s', strtotime($b['athlete_t5']) - strtotime($run));
                $stmt = $db->prepare('UPDATE athletes SET athlete_totaltime = ? WHERE athlete_bib = ?');
                $stmt->execute([$total, $bib.'C']);
              }
              if ($race['race_relay'] === 'X') {
              	// PESQUISA SE #D TEM TEMPO FINAL E ATUALIZA PARA 'EM PROVA'
                $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
                $stmt_b->execute([$bib.'D']);
                $b = $stmt_b->fetch();
                if ($b['athlete_t5'] === '-') {
                  $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
                  $stmt->execute([$bib.'D']);
                }
              }
						} elseif ($posat === "C") {
							if ($race['race_relay'] === 'X') {
								if ($t0 === '-') {
									$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_pos = 9999, athlete_finishtime = "-" WHERE athlete_bib = ?';
									$stmt = $db->prepare($query);
									$stmt->execute([$run, $bib."C"]);
								} else {
									$total = gmdate('H:i:s', strtotime($run) - strtotime($t0));
									$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = "-", athlete_pos = 9999, athlete_totaltime = ? WHERE athlete_bib = ?';
									$stmt = $db->prepare($query);
				        	$stmt->execute([$run, $total, $bib."C"]);
								}
								// PESQUISA SE #D TEM TEMPO FINAL E ATUALIZA
                $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
                $stmt_b->execute([$bib.'D']);
                $b = $stmt_b->fetch();
                if ($b['athlete_t5'] !== '-') {
                  $total = gmdate('H:i:s', strtotime($b['athlete_t5']) - strtotime($run));
                  $stmt = $db->prepare('UPDATE athletes SET athlete_totaltime = ? WHERE athlete_bib = ?');
                  $stmt->execute([$total, $bib.'D']);
                }
							} elseif ($race['race_relay'] === 'Y') {
								$total_equipa = gmdate('H:i:s', strtotime($run) - strtotime($gun));
								if ($t0 === '-') {
									$query = 'UPDATE athletes SET athlete_t5=?, athlete_started=5, athlete_finishtime=?, athlete_pos=9999 WHERE athlete_bib=?';
									$stmt = $db->prepare($query);
									$stmt->execute([$run, $total_equipa, $bib."C"]);
								} else {
				        	$total = gmdate('H:i:s', strtotime($run) - strtotime($t0));
									// $total_equipa = gmdate('H:i:s', strtotime($run) - strtotime($gun));
									$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = ?, athlete_started = 5, athlete_pos = 9999, athlete_totaltime = ? WHERE athlete_bib = ?';
									$stmt = $db->prepare($query);
				        	$stmt->execute([$run, $total_equipa, $total, $bib."C"]);
								}
							}
							// PESQUISA SE #A TEM TEMPO FINAL E ATUALIZA PARA EM PROVA E COLOCA GUN
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'A']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] === '-') {
                $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime="-", athlete_t0=? WHERE athlete_bib=?');
                $stmt->execute([$gun,$bib.'A']);
              }
              // PESQUISA SE #B TEM TEMPO FINAL E ATUALIZA PARA EM PROVA
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'B']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] === '-') {
                $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
                $stmt->execute([$bib.'B']);
              }
						} elseif ($posat === "D") {
							$total_equipa = gmdate('H:i:s', strtotime($run) - strtotime($gun));
							if ($t0 === '-') {
								$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_started = 5, athlete_finishtime = ?, athlete_pos = 9999 WHERE athlete_bib = ?';
								$stmt = $db->prepare($query);
								$stmt->execute([$run, $total_equipa, $bib."D"]);
							} else {
			        	$total = gmdate('H:i:s', strtotime($run) - strtotime($t0));
								// $total_equipa = gmdate('H:i:s', strtotime($run) - strtotime($gun));
								$query = 'UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = ?, athlete_started = 5, athlete_pos = 9999, athlete_totaltime = ? WHERE athlete_bib = ?';
								$stmt = $db->prepare($query);
			        	$stmt->execute([$run, $total_equipa, $total, $bib."D"]);
							}
							// PESQUISA SE #A TEM TEMPO FINAL E ATUALIZA PARA EM PROVA E COLOCA GUN
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'A']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] === '-') {
                $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-", athlete_t0=? WHERE athlete_bib = ?');
                $stmt->execute([$gun,$bib.'A']);
              }
              // PESQUISA SE #B TEM TEMPO FINAL E ATUALIZA PARA EM PROVA
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'B']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] === '-') {
                $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
                $stmt->execute([$bib.'B']);
              }
              // PESQUISA SE #B TEM TEMPO FINAL E ATUALIZA PARA EM PROVA
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'C']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] === '-') {
                $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-" WHERE athlete_bib = ?');
                $stmt->execute([$bib.'C']);
              }
						}
					} elseif ($run === '-') {
						if ($posat === "A") {
							$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-", athlete_t5 = "-" WHERE athlete_bib = ?');
		        	$stmt->execute([$bib."A"]);
		        	$stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-", athlete_t0 = "-" WHERE athlete_bib = ?');
		        	$stmt->execute([$bib."B"]);
						} elseif ($posat == "B") {
							$query = 'UPDATE athletes SET athlete_t5 = "-" WHERE athlete_bib = ?';
							$stmt = $db->prepare($query);
		        	$stmt->execute([$bib."B"]);
							$stmt_next = $db->prepare('UPDATE athletes SET athlete_t0 = "-" WHERE athlete_bib = ?');
		        	$stmt_next->execute([$bib."C"]);
						} elseif ($posat === "C") {
							$query = 'UPDATE athletes SET athlete_t5 = "-", athlete_started = 0, athlete_pos = 9999 WHERE athlete_bib = ?';
							$stmt = $db->prepare($query);
		        	$stmt->execute([$bib."C"]);
						}
					}
					if (($time === 'DNF') || ($time === 'DSQ') || ($time === 'LAP')) {
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
				$stmt = $db->prepare('UPDATE athletes SET athlete_chip = :chip, athlete_bib = :dorsal, athlete_name = :name, athlete_license = :licenca, athlete_sex = :sexo, athlete_category = :escalao, athlete_team_id = :clube WHERE athlete_id = :id');
				$stmt->execute(array(
          ':chip' => $_POST["chip"],
					':dorsal'	=>	$_POST["dorsal"],
					':name'		=>	$_POST["name"],
          ':licenca'		=>	$_POST["licenca"],
					':sexo'		=>	$_POST["sexo"],
					':escalao'		=>	$_POST["escalao"],
					':clube'		=>	$_POST["clube"],
					':id'	=>	$_POST["user_id"]
				));
			} else {
		    // EDITAR TEMPO E ATUALIZAR A TABELA LIVE
				// LER GUN PARA CALCULAR OS TEMPOS
        if ($time === "DNS") {
        	$swim="-"; $live_swim = 'time';
          $t1="-"; $live_t1 = 'time';
          $bike="-"; $live_bike = 'time';
          $t2="-"; $live_t2 = 'time';
          $run="-"; $live_run = 'time';
          $finishtime = $time;
          $t0 = '-';
          $started=0;
          $pos=9999;
          $stmt = $db->prepare("UPDATE athletes SET athlete_chip=:chip, athlete_pos=:pos, athlete_bib=:dorsal, athlete_name=:name, athlete_license=:licenca, athlete_sex=:sexo, athlete_category=:escalao, athlete_team_id=:clube, athlete_t0=:t0, athlete_t1=:swim, athlete_t2=:t1, athlete_t3=:bike, athlete_t4=:t2, athlete_t5=:run, athlete_race_id=:race, athlete_finishtime=:time, athlete_started=:started, athlete_totaltime='-' WHERE athlete_id=:id"
          );
          $result = $stmt->execute(array(
            ':pos'  =>  $pos,
            ':chip' => $_POST["chip"],
            ':dorsal' =>  $_POST["dorsal"],
            ':name'   =>  $_POST["name"],
            ':licenca'    =>  $_POST["licenca"],
            ':sexo'   =>  $_POST["sexo"],
            ':escalao'    =>  $_POST["escalao"],
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
          if (($race['race_type'] === 'crind') || ($race['race_type'] === 'cre') || ($race['race_relay'] === 'X')) {
            $stmt_live = $db->prepare("UPDATE live SET live_chip = :chip, live_bib = :dorsal, live_firstname = :firstname, live_team_id = :clube, live_t1 = :swim, live_t2 = :t1, live_t3 = :bike, live_t4 = :t2, live_t5 = :run, live_finishtime = :finishtime, live_started = :started, live_sex=:sex, live_pos=:pos WHERE live_id = :id"
            );
            $stmt_live->execute(array(
              ':id' =>  $_POST["user_id"],
              ':chip' => $_POST["chip"],
              ':dorsal' =>  $_POST["dorsal"],
              ':firstname'    =>  $_POST["name"],
              ':clube'    =>  $_POST["clube"],
              ':swim'   =>  $live_swim,
              ':t1'   =>  $live_t1,
              ':bike'   =>  $live_bike,
              ':t2'   =>  $live_t2,
              ':run'    =>  $live_run,
              ':finishtime'   =>  $finishtime,
              ':started' => $started,
              ':sex' => $_POST["sexo"],
              ':pos' => $pos
            ));
          }
        } else {
          // RACE_RELAY = X => PROVA COM PARTIDAS POR VAGAS
          if (($race['race_type'] === 'crind') || ($race['race_type'] === 'cre') || ($race['race_relay'] === 'X')) 
          	$gun = isTime($_POST['t0']);
          elseif ($_POST['sexo'] === 'F') 
            $gun = $race['race_gun_f'];
          elseif ($_POST['sexo'] === 'M') 
            $gun = $race['race_gun_m'];
          $live_swim = 'time';
          $live_t1 = 'time';
          $live_bike = 'time';
          $live_t2 = 'time';
          $live_run = 'time';
          $finishtime = 'time';
          $t0 = $gun;
        	// $t0 = isTime($_POST['t0']);
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
          $run = isTime($_POST['run']);
          // $total = isTime($_POST['totaltime']);
          // if (($run !== "-") && ($time !== "DNF") && ($time !== "DSQ"))
          if (($time==="DNF") || ($time==="DNS") || ($time==="chkin") || ($time==="DSQ") || ($time==="LAP")) {
          	// $time = $time;
        		// $has_times = 0;
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
       //    else {
       //    	if ($total !== "-") {
       //    		# Calcula a hora de passagem em T5, pelo tempo total de meta
       //      	if ($race['race_type'] === "jovem") {
							// 	$stmt = $db->prepare("SELECT gunshot_".strtolower($_POST["escalao"]).strtolower($_POST["sexo"])." FROM gunshots WHERE gunshot_race_id = ?");
       //          $stmt->execute([$race_id]);
       //          $rowrace = $stmt->fetch();
						 //    $gun = $rowrace["gunshot_".strtolower($_POST["escalao"]).strtolower($_POST["sexo"])];
							// }
							// $epoch = strtotime($gun)+strtotime($total);
							// $dt = new DateTime("@$epoch");
					  //   $run = $dt->format('H:i:s');
					  //   $run = gmdate('H:i:s', $epoch);
					  //   $time = $run;
					  //   if ($t2 !== "-") $live_run = gmdate('H:i:s', strtotime($run) - strtotime($t2));
					  //   $finishtime = $total;
					  //   $started = 5;
       //    	}
          // }
          if (($time==="DNF") || ($time==="DSQ") || ($time==="LAP")) {
        		$has_times = 0;
        		$finishtime = $time;
        		// ELIMINA, CASO EXISTA, O TEMPO T5 DA TABELA TIMES
        		// $del_t5 = $db->prepare("DELETE FROM times WHERE Chip=? AND Location='TimeT5'");
        		// $del_t5->execute([$_POST["chip"]]);
        	}
          if($started!==5) $pos = 9999;
          if ($has_times == 1) $time = '-';
          // if ($run !== '-') $time = $run;    
          if ($race['race_type'] === 'triatlo' && $race['race_relay'] === '-') {
            $t0 = '-';
          }      
          $stmt = $db->prepare("UPDATE athletes SET athlete_chip = :chip, athlete_pos = :pos, athlete_bib = :dorsal, athlete_name = :name, athlete_license = :licenca, athlete_sex = :sexo, athlete_category = :escalao, athlete_team_id = :clube, athlete_t0 = :t0, athlete_t1 = :swim, athlete_t2 = :t1, athlete_t3 = :bike, athlete_t4 = :t2, athlete_t5 = :run, athlete_race_id = :race, athlete_finishtime = :time, athlete_started = :started, athlete_totaltime = '-' WHERE athlete_id = :id"
          );
          $result = $stmt->execute(array(
            ':pos'  =>  $pos,
            ':chip' => $_POST["chip"],
            ':dorsal' =>  $_POST["dorsal"],
            ':name'   =>  $_POST["name"],
            ':licenca'    =>  $_POST["licenca"],
            ':sexo'   =>  $_POST["sexo"],
            ':escalao'    =>  $_POST["escalao"],
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
          if (($race['race_type'] === 'triatlo' || $race['race_type'] === 'itu') && $race['race_live'] == 1) {
            include_once($_SERVER['DOCUMENT_ROOT']."/functions/times-processing.php");
            processLiveTimes($_POST["chip"], $swim, $t1, $bike, $t2, $run, $db);
            // O ID SERÁ SEMPRE O MESMO PORQUE É FEITO O TRUNCATE ANTES DE IMPORTAR TABELAS, A PRIMEIRA VEZ, FORCAR NO IMPORT
            $stmt_live = $db->prepare("UPDATE live SET live_chip = :chip, live_bib = :dorsal, live_firstname = :firstname, live_team_id = :clube, live_t1 = :swim, live_t2 = :t1, live_t3 = :bike, live_t4 = :t2, live_t5 = :run, live_finishtime = :finishtime, live_started = :started, live_sex=:sex, live_pos=:pos WHERE live_id = :id"
            );
		        $stmt_live->execute(array(
							':id'	=>	$_POST["user_id"],
              ':chip' => $_POST["chip"],
							':dorsal'	=>	$_POST["dorsal"],
							':firstname'		=>	$_POST["name"],
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
				  if(!empty($result)) echo 'Dados do atleta atualizados!';
        }
			}
		}
  }
  // if ($run !== '-') {
		// //**** atualizar coluna 'pos' conforme tempo total, para validar com registo de meta
	 //  $pos = 1;
  //   $queryathletes = $db->query("SELECT athlete_chip FROM athletes WHERE athlete_started = 5 ORDER BY athlete_t5");
  //   $athletes = $queryathletes->fetchAll(); 
  //   foreach ($athletes as $athlete) {
  //     $updateathletes = $db->prepare("UPDATE athletes SET athlete_pos = ? WHERE athlete_chip = ?");
  //     $updateathletes->execute([$pos, $athlete['athlete_chip']]);
  //     $pos++;
  //   }
  	include($_SERVER['DOCUMENT_ROOT']."/functions/times-all.php");
    include_once($_SERVER['DOCUMENT_ROOT']."/functions/times-processing.php");
    processLivePositions($_POST["sexo"], $race['race_id'], $db);
	// }
?>