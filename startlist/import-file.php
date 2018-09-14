<?php 
  include($_SERVER['DOCUMENT_ROOT']."/functions/PHPExcel/IOFactory.php");
  include_once($_SERVER['DOCUMENT_ROOT']."/html/header.php");
  include($_SERVER['DOCUMENT_ROOT'].'/functions/getTeams.php');
  if (isset($_POST['prova_id'])) {
    // print_r($_POST['prova_id']);
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
        if ((stripos($rowData[0][7],'federado') === false) && (stripos($rowData[0][7],'individual') === false) && (stripos($rowData[0][7],'estafeta') === false)) {
          if (!in_array(strtolower($rowData[0][7]), $teams)) {
            $teams[$teamIndex] = strtolower($rowData[0][7]);
            $teamId = $teamIndex;
            $stmt = $db->prepare("INSERT INTO teams(team_id, team_name) VALUES (:id, :name)");
            $stmt->execute(array(
              ':id' => $teamId, 
              ':name' => $rowData[0][7]
            )); 
            $teamIndex++;
          } else {
            $teamId = array_search(strtolower($rowData[0][7]), $teams);
          }
        } elseif (stripos($rowData[0][7],'federado') > -1) {
          $teamId = 1;
        } elseif  (stripos($rowData[0][7],'individual') > -1) {
          $teamId = 2;
        } elseif  (stripos($rowData[0][7],'estafeta') > -1) {
          $teamId = 3;
        } 
        // GET RACE
        if (!in_array($rowData[0][8], $races)) {
          $races[$raceIndex] = $rowData[0][8];
          $raceId = $raceIndex;
          if ($_POST['prova_id'] === 'mxrelay') {
            $raceName = 'Estafetas Mistas';
            $raceType = 'relay';
            $raceRelay = 'X';
          } elseif ($_POST['prova_id'] === 'relay') {
            $raceName = 'Estafetas';
            $raceType = 'relay';
            $raceRelay = 'Y';
          } else {
            $raceName = $rowData[0][8];
            $raceType = $_POST['prova_id'];
            $raceRelay = '-';
          }
          $stmt = $db->prepare("INSERT INTO races(race_id, race_name, race_type, race_relay) VALUES (:id, :name, :type, :relay)");
          $stmt->execute(array(
            ':id' => $raceId, 
            ':name' => $raceName,
            ':type' => $raceType,
            ':relay' => $raceRelay
          ));
          $raceIndex++;
        } else {
          $raceId = array_search($rowData[0][8], $races);
        }
        $query = "INSERT INTO athletes (athlete_chip, athlete_license, athlete_bib, athlete_name, athlete_sex, athlete_category, athlete_team_id, athlete_race_id) VALUES (:chip, :license, :bib, :name, :sex, :category, :team, :race)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(
          ':chip' => $rowData[0][3], 
          ':license' => $rowData[0][0],
          ':bib' => $rowData[0][1],
          ':name' => $rowData[0][4],
          ':sex' => $rowData[0][6],
          ':category' => $rowData[0][2],
          ':team' => $teamId,
          ':race' => $raceId
          // ':dob' => gmdate("d-m-Y", $UNIX_DATE)
          // ':dob' => $UNIX_DATE
        ));
        // $stmt = $db->prepare("INSERT INTO live (live_chip, live_bib, live_firstname, live_sex, live_category, live_team, live_race) VALUES (:chip, :bib, :name, :sex, :category, :team, :race)");
        // $stmt->execute(array(
        //   ':chip' => $rowData[0][3], 
        //   ':bib' => $rowData[0][1],
        //   ':name' => $rowData[0][4],
        //   ':sex' => $rowData[0][6],
        //   ':category' => $rowData[0][2],
        //   ':team' => $rowData[0][7],
        //   ':race' => $raceId
        // ));
        //-----------------------------------
        // COL |  ID  |     DESCRIPTION     |
        //-----|------|---------------------|
        //  A  |   0  |  LICENSE FTP        |
        //  B  |   1  |  BIB                |
        //  C  |   2  |  CATEGORY, ESCALAO  |
        //  D  |   3  |  CHIP               |
        //  E  |   4  |  FULL NAME          |
        //  F  |   5  |  BIRTHDATE          |
        //  G  |   6  |  relay             |
        //  H  |   7  |  TEAM, COUNTRY      |
        //  I  |   8  |  RACE DESIGNATION   |
        //     |      |  FIRST NAME         |
        //  J  |   9  |  GUN TIME           |
        //     |      |  LAST NAME          |
        // ----------------------------------
      }
      print_r('continuar leitura do ficheiro excel, race = '.$raceIndex); 
    } else {
      print_r('not an excel file');
    }
  }
?>