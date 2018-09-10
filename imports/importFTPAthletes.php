<?php
  include_once ($_SERVER['DOCUMENT_ROOT']."/html/header.php");
  include($_SERVER['DOCUMENT_ROOT']."/functions/PHPExcel/IOFactory.php");
  include($_SERVER['DOCUMENT_ROOT']."/functions/getAgeGroup.php");
  include($_SERVER['DOCUMENT_ROOT']."/functions/getTeams.php");
    
  if(isset($_POST['importSubmit'])){
    //Use whatever path to an Excel file you need.
    $inputFileName = $_FILES['file']['tmp_name'];
    try {
      $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
      $objReader = PHPExcel_IOFactory::createReader($inputFileType)->setDelimiter(";");
      $objPHPExcel = $objReader->load($inputFileName);
    } catch (Exception $e) {
      die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . 
      $e->getMessage());
    }

    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    // $highestColumn = $sheet->getHighestColumn();
    $stmt = $db->prepare("TRUNCATE ftpathletes; TRUNCATE teams;");
    $stmt->execute();
    $teams = getTeams();
    $teamIndex = 4;


    for ($row = 2; $row <= $highestRow; $row++){ 
      $rowData = $sheet->rangeToArray('C' . $row . ':' . 'O' . $row, null, true, false);
      // Colunas para tabela FEDERADOS 
      // 0 - chip / 1 - dorsal / 2 - licenca / 3 - equipa / 6 - nome / 7 - data nascimento / 12 - genero
      // COMPARA COM TABELA 'TEAMS'
      if ($rowData[0][3] == '') {
        $teamId = 9999;
      } elseif ((stripos($rowData[0][3],'federado') === false) && (stripos($rowData[0][3],'individual') === false)) {
        if (!in_array($rowData[0][3], $teams)) {
          $teams[$teamIndex] = $rowData[0][3];
          $teamId = $teamIndex;
          $stmt = $db->prepare("INSERT INTO teams(team_id, team_name) VALUES (:id, :name)");
          $stmt->execute(
            array(
              ':id' => $teamId, 
              ':name' => $rowData[0][3]
              )); 
          $teamIndex++;
        } else {
          $teamId = array_search($rowData[0][3], $teams);
        }
      } elseif (stripos($rowData[0][3],'federado') > -1) {
        $teamId = 1;
      } elseif  (stripos($rowData[0][3],'individual') > -1) {
        $teamId = 2;
      }

      // CALCULA O ESCALÃO - 'AGE GROUP'
      $dob = strtotime($rowData[0][7]);
      $ageGroup = getAgeGroup($dob);

      $query = "INSERT INTO ftpathletes (ftpathlete_chip, ftpathlete_license, ftpathlete_bib, ftpathlete_name, ftpathlete_sex, ftpathlete_category, ftpathlete_team_id) VALUES (:chip, :license, :bib, :name, :sex, :category, :team)";
      $stmt = $db->prepare($query);
      $stmt->execute(array(
        ':chip' => $rowData[0][0], 
        ':license' => $rowData[0][2],
        ':bib' => $rowData[0][1],
        ':name' => $rowData[0][6],
        ':sex' => $rowData[0][11],
        ':category' => $ageGroup,
        ':team' => $teamId
      ));
    }
  }  
  header("location:/");
?>