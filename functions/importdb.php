<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  if(!empty($_FILES['file']['name'])) {
    if(is_uploaded_file($_FILES['file']['tmp_name'])) {
  	  $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
      fgetcsv($csvFile);
      $stmt = $db->prepare("truncate athletes; truncate gunshots; truncate races; truncate teams; truncate youthraces; truncate live; truncate times; truncate results; truncate chips;");
      $stmt->execute();
      while(($line = fgetcsv($csvFile)) !== FALSE) {
        if ($line[0] == 0) {
          $stmt = $db->prepare("INSERT INTO athletes(athlete_pos, athlete_chip, athlete_license, athlete_bib, athlete_name, athlete_firstname, athlete_lastname, athlete_sex, athlete_dob, athlete_category, athlete_team_id, athlete_t0, athlete_t1, athlete_t2, athlete_t3, athlete_t4, athlete_t5, athlete_finishtime, athlete_totaltime, athlete_race_id, athlete_started, athlete_xtras, athlete_arrive_order) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,  ?)");
          $stmt->execute([$line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8], $line[9], $line[10], $line[11], $line[12], $line[13], $line[14], $line[15], $line[16], $line[17], $line[18], $line[19], $line[20], $line[21], $line[22], $line[23]]);
        } elseif ($line[0] == 1) {
          $stmt = $db->prepare("INSERT INTO gunshots(gunshot_benf, gunshot_benm, gunshot_inff, gunshot_infm, gunshot_inif, gunshot_inim, gunshot_juvf, gunshot_juvm, gunshot_race_id) VALUES (?,?,?,?,?,?,?,?,?)");
          $stmt->execute([$line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8], $line[9]]);
        } elseif ($line[0] == 2) {
          $stmt = $db->prepare("INSERT INTO races(race_id, race_name, race_namepdf, race_ranking, race_segment1, race_distsegment1, race_segment2, race_distsegment2, race_segment3, race_distsegment3, race_date, race_location, race_gun_f, race_gun_m, race_type, race_relay, race_live) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
          $stmt->execute([$line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8], $line[9], $line[10], $line[11], $line[12], $line[13], $line[14], $line[15], $line[16], $line[17]]);
        } elseif ($line[0] == 3) {
          $stmt = $db->prepare("INSERT INTO teams(team_id,team_name) VALUES (?,?)");
          $stmt->execute([$line[1], $line[2]]);
        } elseif ($line[0] == 4) {
          $stmt = $db->prepare("INSERT INTO youthraces(youthrace_race_id, youthrace_name, youthrace_namepdf, youthrace_ranking, youthrace_s1_ben, youthrace_d1_ben, youthrace_s2_ben, youthrace_d2_ben, youthrace_s3_ben, youthrace_d3_ben, youthrace_s1_inf, youthrace_d1_inf, youthrace_s2_inf, youthrace_d2_inf, youthrace_s3_inf, youthrace_d3_inf, youthrace_s1_ini, youthrace_d1_ini, youthrace_s2_ini, youthrace_d2_ini, youthrace_s3_ini, youthrace_d3_ini, youthrace_s1_juv, youthrace_d1_juv, youthrace_s2_juv, youthrace_d2_juv, youthrace_s3_juv, youthrace_d3_juv, youthrace_date, youthrace_location) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
          $stmt->execute([$line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8], $line[9], $line[10], $line[11], $line[12], $line[13], $line[14], $line[15], $line[16], $line[17], $line[18], $line[19], $line[20], $line[21], $line[22], $line[23], $line[24], $line[25], $line[26], $line[27], $line[28], $line[29], $line[30]]);
        } elseif ($line[0] == 5) {
          $stmt = $db->prepare("INSERT INTO live(live_pos, live_chip, live_license, live_bib, live_firstname, live_lastname, live_sex, live_category, live_team_id, live_t1, live_t2, live_t3, live_t4, live_t5, live_finishtime, live_race, live_started) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
          $stmt->execute([$line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8], $line[9], $line[10], $line[11], $line[12], $line[13], $line[14], $line[15], $line[16], $line[17]]);
        } elseif ($line[0] == 6) {
          $stmt = $db->prepare("INSERT INTO times(Chip, ChipTime, Location) VALUES (?,?,?)");
          $stmt->execute([$line[1], $line[2], $line[3]]);
        }
      }
      fclose($csvFile);
		  $qstring = '?status=succ';
    } else $qstring = '?status=err';
  } else $qstring = '?status=invalid_file';
  header("location:/");
?>