<?php
//ADAPTADO COM FUNCAO E A FUNCIONAR

  include_once ($_SERVER['DOCUMENT_ROOT']."/html/header.php");
  include($_SERVER['DOCUMENT_ROOT']."/functions/PHPExcel/IOFactory.php");
  include($_SERVER['DOCUMENT_ROOT'].'/functions/getTeams.php');

  if(isset($_POST['prova_id'])) {
    // Use whatever path to an Excel file you need.
    $inputFileName = $_FILES['file']['tmp_name'];
    try {
      $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
      $objReader = PHPExcel_IOFactory::createReader($inputFileType);
      $objPHPExcel = $objReader->load($inputFileName);
    } catch (Exception $e) {
      die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . 
      $e->getMessage());
    }
    
    $races = array();
    $race_index = 1;
    // VALIDAR SE A TABELA RACES TEM DADOS
    // LER ID DA ULTIMA PROVA E CONTINUAR SEQUENCIALMENTE
    $stmt = $db->prepare("SELECT race_id FROM races ORDER BY race_id DESC LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        $race_index = $row['race_id'] + 1;
    }
    $stmt = $db->prepare('INSERT INTO races(race_id, race_name, race_type) VALUES (:id, :name, :type)');
    $stmt->execute(array(
      ':id' => $race_index, 
      ':name' => 'CR Equipas',
      ':type' => 'cre'
    ));
    
    $stmt = $db->prepare("TRUNCATE chips; TRUNCATE times; TRUNCATE results; TRUNCATE markers;");
    $stmt->execute();

    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $teams = getTeams();
    $teamIndex = count($teams)+1;

    for ($row = 2; $row <= $highestRow; $row++) { 
      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
      if ($rowData[0][6] === 'F') $team = str_replace(' - FEM ','',$rowData[0][5]);
      elseif ($rowData[0][6] === 'M') $team = str_replace(' - MASC ','',$rowData[0][5]);
      $creTeam = substr($team, -1);
      $team = substr($team, 0, -1);
      if (!in_array(strtolower($team), $teams)) {
        $teams[$teamIndex] = $team;
        $teamId = $teamIndex;
        $stmt = $db->prepare("INSERT INTO teams(team_id, team_name) VALUES (:id, :name)");
        $stmt->execute(array(
          ':id' => $teamId, 
          ':name' => $team
        )); 
        $teamIndex++;
      } else {
        $teamId = array_search(strtolower($team), $teams);
      }
      $t0 = date('H:i:s', PHPExcel_Shared_Date::ExcelToPHP($rowData[0][0]));
      $query = "INSERT INTO athletes (athlete_chip, athlete_license, athlete_bib, athlete_name, athlete_sex, athlete_category, athlete_team_id, athlete_race_id, athlete_xtras, athlete_t0, athlete_arrive_order) VALUES (:chip, :license, :bib, :name, :sex, :category, :team, :race, :xtra, :t0, :arriveOrder)";
      $stmt = $db->prepare($query);
      $stmt->execute(array(
        ':chip' => $rowData[0][3], 
        ':license' => $rowData[0][0],
        ':bib' => $rowData[0][1].$creTeam,
        ':name' => $rowData[0][4],
        ':sex' => $rowData[0][6],
        ':category' => $rowData[0][7],
        ':team' => $teamId,
        ':race' => $race_index,
        ':xtra' => $creTeam,
        ':t0' => $t0,
        ':arriveOrder' => 0
      ));
    }
  }
?>