<?php

    //load the database configuration file
    //header('Content-Type: text/html; charset=UTF-8');

    include_once ($_SERVER['DOCUMENT_ROOT']."/html/header.php");
    include($_SERVER['DOCUMENT_ROOT']."/functions/PHPExcel/IOFactory.php");

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

        $teams = array();
        $team_index = 1;
        $races = array();
        $race_index = 1;

        // VALIDAR SE A TABELA TEAMS TEM DADOS
        $stmt = $db->prepare("SELECT team_id, team_name FROM teams ORDER BY team_id ASC");
        $stmt->execute();
        if ($stmt->rowCount() > 0)
        {
            // CARREGA PARA ARRAY CONTEUDO DA TABELA TEAMS
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) 
            {
                $teams[$row['team_id']] = $row['team_name'];
            }
            $team_index = $row['team_id'] + 1;
        } else {
            $stmt = $db->prepare("INSERT INTO teams(team_id, team_name) VALUES (1000, 'Não Federado')");
            $stmt->execute();
            $stmt = $db->prepare("INSERT INTO teams(team_id, team_name) VALUES (1001, 'Individual')");
            $stmt->execute();
            $stmt = $db->prepare("INSERT INTO teams(team_id, team_name) VALUES (1002, 'Estafeta')");
            $stmt->execute();
        }

        // VALIDAR SE A TABELA RACES TEM DADOS
        // LER ID DA ULTIMA PROVA E CONTINUAR SEQUENCIALMENTE
        $stmt = $db->prepare("SELECT race_id FROM races ORDER BY race_id DESC LIMIT 1");
        $stmt->execute();
        $rowrace = $stmt->fetch();
        if ($rowrace) 
        {
            $race_index = $rowrace['race_id'] + 1;
        }
        $race_index_fem = $race_index;
        $race_index_masc = $race_index+1;
        $stmt = $db->prepare("INSERT INTO races(race_id, race_name, race_type, race_segment2, race_segment1, race_gender) VALUES (:id1, :name1, :type1, 'n.a.', 'Natação', 'F'), (:id2, :name2, :type2, 'n.a.', 'Natação', 'M')");
        $stmt->execute(
            array(
                ':id1' => $race_index_fem,
                'name1' => 'Estafetas Fem.',
                ':type1' => 'relay',
                ':id2' => $race_index_masc,
                'name2' => 'Estafetas Masc.',
                ':type2' => 'relay',
        ));

        $stmt = $db->prepare("TRUNCATE chips; TRUNCATE times; TRUNCATE results;");
        $stmt->execute();

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        for ($row = 2; $row <= $highestRow; $row++) 
        {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
            if ($rowData[0][6] == 'M')
            {
                $race_id = $race_index_masc;
            }
            elseif ($rowData[0][6] == 'F')
            {
                $race_id = $race_index_fem;
            }
            if ((stripos($rowData[0][7],'federado') === false) && (stripos($rowData[0][7],'individual') === false) && (stripos($rowData[0][7],'estafeta') === false)) 
            {
                if (!in_array($rowData[0][7], $teams)) 
                {
                    $teams[$team_index] = $rowData[0][7];
                    $team_id = $team_index;
                    $stmt = $db->prepare("INSERT INTO teams(team_id, team_name) VALUES (:id, :name)");
                    $stmt->execute(
                        array(
                            ':id' => $team_id, 
                            ':name' => $rowData[0][7]
                            ));
                    $team_index++;
                } else {
                    $team_id = array_search($rowData[0][7], $teams);
                }
            } elseif (stripos($rowData[0][7],'federado') > -1) {
                $team_id = 1000;
            } elseif (stripos($rowData[0][7],'individual') > -1) {
                $team_id = 1001;
            } elseif (stripos($rowData[0][7],'estafeta') > -1) {
                $team_id = 1002;
            } 
            $query = "INSERT INTO athletes (athlete_chip, athlete_license, athlete_bib, athlete_name, athlete_sex, athlete_category, athlete_team_id, athlete_race_id, athlete_rly_gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                    $rowData[0][3], 
                    $rowData[0][0],
                    $rowData[0][1],
                    $rowData[0][4],
                    $rowData[0][6],
                    $rowData[0][2],
                    $team_id,
                    $race_id,
                    $rowData[0][8]
                ]);
        }            
        $stmt = $db->prepare("SELECT race_id FROM races");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) 
        {
            $stmt_athlete = $db->prepare("SELECT athlete_sex FROM athletes WHERE athlete_race_id = ? GROUP BY athlete_sex");
            $stmt_athlete->execute([$row['race_id']]);  
            if ($stmt_athlete->rowCount() == 1)
            {
                $athlete = $stmt_athlete->fetch();
                $stmt_race = $db->prepare("UPDATE races SET race_gender = ? WHERE  race_id = ?");
                $stmt_race->execute([$athlete['athlete_sex'],$row['race_id']]);
            } else {
                $stmt_race = $db->prepare("UPDATE races SET race_gender = 'X' WHERE  race_id = ?");
                $stmt_race->execute([$row['race_id']]);
            }
        }
    }
?>	