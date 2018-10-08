<?php
  include($_SERVER['DOCUMENT_ROOT'].'/includes/db.php');
  $queryraces = $db->query("SELECT race_id, race_gun_m, race_gun_f, race_type, race_relay FROM races");
  $races = $queryraces->fetchAll();
  foreach ($races as $race) {
    // X -> ESTAFETAS MISTAS 
    // Y -> ESTAFETAS POR GENERO
    if ($race['race_relay'] === 'X') $gender = 'X';
    else $gender = 'Y';
    // LEITURA E CALCULO DOS TEMPOS PARA ESTAFETAS
    if (($race['race_type'] === 'relay') || ($race['race_type'] === 'jEstf')) {
      // LER TEMPOS PRIMEIRO
      $querytime = $db->prepare("SELECT Chip, ChipTime FROM times WHERE Location='TimeT5' ORDER BY ChipTime ASC");
      $querytime->execute();
      $times = $querytime->fetchAll();
      foreach ($times as $timeRelay) {
        $queryathletes = $db->prepare("SELECT athlete_t0, athlete_t5, athlete_totaltime, athlete_finishtime, athlete_bib, athlete_sex FROM athletes where athlete_race_id = ? AND athlete_chip = ? LIMIT 1");
        $queryathletes->execute([$race['race_id'], $timeRelay['Chip']]);
        $athlete = $queryathletes->fetch();
        if (($athlete['athlete_finishtime'] !== 'DNS') && ($athlete['athlete_finishtime'] !== 'DSQ') && ($athlete['athlete_finishtime'] !== 'DNF')) {
          $posat = substr($athlete['athlete_bib'], -1);
          $bib = rtrim($athlete['athlete_bib'],$posat);
          list($date, $time) = explode (" ", $timeRelay['ChipTime']);
          if (($athlete['athlete_sex'] === 'F') && ($race['race_relay'] === 'Y')) $gun = $race['race_gun_f'];
          elseif (($athlete['athlete_sex'] === 'M') && ($race['race_relay'] === 'Y')) $gun = $race['race_gun_m'];
          // SUBTRAIR 2 HORAS AO TEMPO ENVIADO PELO MYLAPS
          $time = gmdate('H:i:s', strtotime($time)-strtotime('01:00:00'));
          // TEMPO DA ESTAFETA #A
          if ($posat === 'A') {
            if ($athlete['athlete_t5'] === '-') {
              // A
              $time_relay = gmdate('H:i:s', strtotime($time) - strtotime($gun));
              $stmt = $db->prepare('UPDATE athletes SET athlete_t0=?, athlete_t5=?, athlete_finishtime="-", athlete_totaltime=? WHERE athlete_bib=?');
              $stmt->execute([$gun, $time, $time_relay, $bib.'A']);
              // B
              $stmt = $db->prepare('UPDATE athletes SET athlete_t0 = ?, athlete_finishtime = "-" WHERE athlete_bib = ?');
            	$stmt->execute([$time, $bib.'B']);
              // PESQUISA SE #B TEM TEMPO FINAL E ATUALIZA
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'B']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] !== '-') {
                $total = gmdate('H:i:s', strtotime($b['athlete_t5']) - strtotime($time));
                $stmt = $db->prepare('UPDATE athletes SET athlete_totaltime=? WHERE athlete_bib=?');
                $stmt->execute([$total, $bib.'B']);
              }
            }
          }
          // TEMPO DA ESTAFETA #B
          if ($posat === 'B') {
            if ($athlete['athlete_t5'] === '-') {
              // B
              if ($athlete['athlete_t0'] === '-') {
                $query = 'UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = "-" WHERE athlete_bib = ?';
                $stmt = $db->prepare($query);
                $stmt->execute([$time, $bib."B"]);
              } else {
                $total = gmdate('H:i:s', strtotime($time) - strtotime($athlete['athlete_t0']));
                $query = 'UPDATE athletes SET athlete_t5=?, athlete_finishtime=?, athlete_totaltime=? WHERE athlete_bib=?';
                $stmt = $db->prepare($query);
                $stmt->execute([$time, '-', $total, $bib."B"]);
              }
              // C
              $stmt_next = $db->prepare('UPDATE athletes SET athlete_pos=9999, athlete_t0=? WHERE athlete_bib=?');
              $stmt_next->execute([$time, $bib."C"]);
              // PESQUISA SE #C TEM TEMPO FINAL E ATUALIZA
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib=? LIMIT 1');
              $stmt_b->execute([$bib.'C']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] !== '-') {
                $total = gmdate('H:i:s', strtotime($b['athlete_t5']) - strtotime($time));
                $stmt = $db->prepare('UPDATE athletes SET athlete_totaltime=? WHERE athlete_bib=?');
                $stmt->execute([$total, $bib.'C']);
              }
            }
          }
          // TEMPO DA ESTAFETA #C 
          // ESTAFETAS MISTAS
          if (($posat === 'C') && ($gender === 'X')) {
            // IGUAL AO #B NAS ESTAFETAS EQUIPAS
            if ($athlete['athlete_t5'] === '-') {
              // C
              if ($athlete['athlete_t0'] === '-') {
                $query = 'UPDATE athletes SET athlete_t5=?, athlete_finishtime=? WHERE athlete_bib=?';
                $stmt = $db->prepare($query);
                $stmt->execute([$time, '-', $bib.'C']);
              } else {
                $total = gmdate('H:i:s', strtotime($time) - strtotime($athlete['athlete_t0']));
                $query = 'UPDATE athletes SET athlete_t5=?, athlete_finishtime=?, athlete_totaltime=? WHERE athlete_bib=?';
                $stmt = $db->prepare($query);
                $stmt->execute([$time, '-', $total, $bib.'C']);
              }
              // D
              $stmt_next = $db->prepare('UPDATE athletes SET athlete_pos=9999, athlete_t0=?, athlete_finishtime="-" WHERE athlete_bib=?');
              $stmt_next->execute([$time, $bib."D"]);
              // PESQUISA SE #D TEM TEMPO FINAL E ATUALIZA
              $stmt_b = $db->prepare('SELECT athlete_t5 FROM athletes WHERE athlete_bib = ? LIMIT 1');
              $stmt_b->execute([$bib.'D']);
              $b = $stmt_b->fetch();
              if ($b['athlete_t5'] !== '-') {
                $total = gmdate('H:i:s', strtotime($b['athlete_t5']) - strtotime($time));
                $stmt = $db->prepare('UPDATE athletes SET athlete_totaltime=? WHERE athlete_bib=?');
                $stmt->execute([$total, $bib.'D']);
              }
            }
          } 
          // TEMPO DA ESTAFETA #C 
          // ESTAFETAS POR GENERO
          elseif (($posat === 'C') && ($gender === 'Y')) {
            if ($athlete['athlete_t5'] === '-') {
              if ($athlete['athlete_t0'] === '-') {
                $total_equipa = gmdate('H:i:s', strtotime($time) - strtotime($gun));
                $stmt = $db->prepare('UPDATE athletes SET athlete_t5=?, athlete_finishtime=?, athlete_started=5 WHERE athlete_bib=?');
                $stmt->execute([$time, $total_equipa, $bib."C"]);
              } else {
                $total = gmdate('H:i:s', strtotime($time) - strtotime($athlete['athlete_t0']));
                $total_equipa = gmdate('H:i:s', strtotime($time) - strtotime($gun));
                $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime="-", athlete_t5=?, athlete_finishtime=?, athlete_totaltime=?, athlete_started=5 WHERE athlete_bib=?');
                $stmt->execute([$time, $total_equipa, $total, $bib."C"]);
              }
            }
          }
          // TEMPO DA ESTAFETA #D
          if (($posat == "D") && ($gender == "X")) {
            if ($athlete['athlete_t0'] == '-') {
              $total_equipa = gmdate('H:i:s', strtotime($time) - strtotime($gun));
              $stmt = $db->prepare('UPDATE athletes SET athlete_t5 = ?, athlete_finishtime = ?, athlete_started = 5 WHERE athlete_bib = ?');
              $stmt->execute([$time, $total_equipa, $bib."D"]);
            } else {
              $total = gmdate('H:i:s', strtotime($time) - strtotime($athlete['athlete_t0']));
              $total_equipa = gmdate('H:i:s', strtotime($time) - strtotime($gun));
              $stmt = $db->prepare('UPDATE athletes SET athlete_finishtime = "-", athlete_t5 = ?, athlete_finishtime = ?, athlete_totaltime = ?, athlete_started = 5 WHERE athlete_bib = ?');
              $stmt->execute([$time, $total_equipa, $total, $bib."D"]);
            }
          }
        }
      }
    }
  }
?>