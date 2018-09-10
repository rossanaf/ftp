<?php
//ADAPTADO COM FUNCAO E A FUNCIONAR

  //load the database configuration file
  //header('Content-Type: text/html; charset=UTF-8');
  include_once ($_SERVER['DOCUMENT_ROOT']."/html/header.php");
  include($_SERVER['DOCUMENT_ROOT']."/functions/PHPExcel/IOFactory.php");
  include($_SERVER['DOCUMENT_ROOT'].'/functions/getTeams.php');

  if(isset($_POST['prova_id'])){
    //Use whatever path to an Excel file you need.
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
    $teams = getTeams();
    $teamIndex = count($teams)+1;

    // VALIDAR SE A TABELA RACES TEM DADOS
    // LER ID DA ULTIMA PROVA E CONTINUAR SEQUENCIALMENTE
    $stmt = $db->prepare("SELECT race_id FROM races ORDER BY race_id DESC LIMIT 1");
    $stmt->execute();
    $rowrace = $stmt->fetch();
    if ($rowrace) $race_index = $rowrace['race_id'] + 1;
    $stmt = $db->prepare("INSERT INTO races(race_id, race_name, race_type, race_relay) VALUES (:id, :name, :type, :type_rly)");
    $stmt->execute(array(
      ':id' => $race_index,
      ':name' => 'Estafetas',
      ':type' => 'relay',
      ':type_rly' => 'Y'
    ));

    $stmt = $db->prepare("TRUNCATE chips; TRUNCATE times; TRUNCATE results; TRUNCATE markers;");
    $stmt->execute();

    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    for ($row = 2; $row <= $highestRow; $row++) {
      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
      if ((stripos($rowData[0][7],'federado') === false) && (stripos($rowData[0][7],'individual') === false) && (stripos($rowData[0][7],'estafeta') === false)) {
        if (!in_array(strtolower($rowData[0][7]), $teams)) {
          $teams[$teamIndex] = strtolower($rowData[0][7]);
          $teamId = $teamIndex;
          $stmt = $db->prepare("INSERT INTO teams(team_id, team_name) VALUES (:id, :name)");
          $stmt->execute(
            array(
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
      $query = "INSERT INTO athletes (athlete_chip, athlete_license, athlete_bib, athlete_name, athlete_sex, athlete_category, athlete_team_id, athlete_race_id, athlete_rly_cat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $db->prepare($query);
      $stmt->execute([
        $rowData[0][3], 
        $rowData[0][0],
        $rowData[0][1],
        $rowData[0][4],
        $rowData[0][6],
        $rowData[0][2],
        $teamId,
        $rowData[0][6],
        $rowData[0][8]
      ]);
    }
  }
?>	