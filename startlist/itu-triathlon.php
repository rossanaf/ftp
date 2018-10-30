<?php 
  include($_SERVER['DOCUMENT_ROOT']."/functions/PHPExcel/IOFactory.php");
  include_once($_SERVER['DOCUMENT_ROOT']."/html/header.php");
  include($_SERVER['DOCUMENT_ROOT'].'/functions/getTeams.php');
  if (isset($_POST['prova_id'])) {
    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    if ($extension === 'xls' || $extension === 'xlsx') {
      $fileName = $_FILES['file']['tmp_name'];
      try {
        $fileType = PHPExcel_IOFactory::identify($fileName);
        $objReader = PHPExcel_IOFactory::createReader($fileType);
        $objPHPExcel = $objReader->load($fileName);
      } catch (Exception $e) {
        print_r('error loading file');
      }
      $races = array();
      // VALIDAR SE A TABELA RACES TEM DADOS
      // LER ID DA ULTIMA PROVA E CONTINUAR SEQUENCIALMENTE
      $stmt = $db->prepare("SELECT race_id FROM races ORDER BY race_id DESC LIMIT 1");
      $stmt->execute();
      $row = $stmt->fetch();
      if ($row) {
        $raceIndex = $row['race_id'] + 1;
      } else {
        $raceIndex = 1;
        // ELIMINAR TEMPOS DE PROVAS ANTERIORES
        $stmt = $db->prepare("TRUNCATE chips; TRUNCATE times; TRUNCATE results; TRUNCATE markers;");
        $stmt->execute();
      }
      $sheet = $objPHPExcel->getSheet(0);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn();
      $teams = getTeams();
      $teamIndex = count($teams)+1;
      for ($row = 2; $row <= $highestRow; $row++) {
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
        // GET TEAM      
        if (!in_array(strtolower($rowData[0][5]), $teams)) {
          $teams[$teamIndex] = strtolower($rowData[0][5]);
          $teamId = $teamIndex;
          $stmt = $db->prepare("INSERT INTO teams(team_id, team_country) VALUES (:id, :country)");
          $stmt->execute(array(
            ':id' => $teamId, 
            ':country' => $rowData[0][5]
          )); 
          $teamIndex++;
        } else {
          $teamId = array_search(strtolower($rowData[0][5]), $teams);
        }
        // GET RACE
        if (!in_array($rowData[0][6], $races)) {
          $races[$raceIndex] = $rowData[0][6];
          $raceId = $raceIndex;
          $stmt = $db->prepare("INSERT INTO races(race_id, race_name, race_type, race_live) VALUES (:id, :name, :type, 1)");
          $stmt->execute(array(
            ':id' => $raceId, 
            ':name' => $rowData[0][6],
            ':type' => 'itu'
          ));
          $raceIndex++;
        } else {
          $raceId = array_search($rowData[0][6], $races);
        }
        // USAR CAMPO ATHLETE_ARRIVE_ORDER PARA COLOCAR A ORDEM DE CADA ATLETA NA EQUIPA
        $query = "INSERT INTO athletes (athlete_chip, athlete_bib, athlete_name, athlete_firstname, athlete_lastname, athlete_sex, athlete_team_id, athlete_race_id, athlete_category) VALUES (:chip, :bib, :name, :sex, :team, :race, :cat)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(
          ':chip' => $rowData[0][0], 
          ':bib' => $rowData[0][1],
          ':name' => $rowData[0][2].' '.$rowData[0][3],
          ':firstname' => $rowData[0][2],
          ':lastname' => $rowData[0][3],
          ':sex' => $rowData[0][4],
          ':team' => $teamId,
          ':race' => $raceId,
          ':cat' => $rowData[0][6]
          // ':dob' => gmdate("d-m-Y", $UNIX_DATE)
          // ':dob' => $UNIX_DATE
        ));
        // USE FIELD LIVE_LICENSE FOR ATHLETE TEAM ORDER
        $stmt = $db->prepare("
          INSERT INTO live (live_chip, live_bib, live_firstname, live_lastname, live_sex, live_team_id, live_race, live_category) 
          VALUES (:chip, :bib, :firstname, :lastname, :sex, :team, :race, :cat)
        ");
        $stmt->execute(array(
          ':chip' => $rowData[0][0], 
          ':bib' => $rowData[0][1],
          ':firstname' => $rowData[0][2],
          ':lastname' => $rowData[0][3],
          ':sex' => $rowData[0][4],
          ':team' => $teamId,
          ':race' => $raceId,
          ':cat' => $rowData[0][6]
        ));
      }
      print_r('continuar leitura do ficheiro excel, race = '.$raceIndex); 
    } else {
      print_r('not an excel file');
    }
  }
?>