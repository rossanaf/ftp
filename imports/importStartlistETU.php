<?php

    include_once ($_SERVER['DOCUMENT_ROOT']."/html/header.php");
    include($_SERVER['DOCUMENT_ROOT']."/functions/PHPExcel/IOFactory.php");

    if(isset($_POST['importSubmit']))
    {

        //Use whatever path to an Excel file you need.
        $inputFileName = $_FILES['file']['tmp_name'];

        try 
        {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

        
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $stmt->prepare("TRUNCATE TABLE athletes")->execute();
        $stmt->prepare("TRUNCATE TABLE live")->execute();

        for ($row = 2; $row <= $highestRow; $row++) 
        { 
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
            
    		$query = "INSERT INTO athletes (athlete_chip, athlete_bib, athlete_firstname, athlete_lastname, athlete_team, athlete_sex) VALUES (:chip, :bib, :firstname, :lastname, :team, :sex)";

    		$stmt = $db->prepare($query);
    		$stmt->execute(
    			array(
    				':chip' => $rowData[0][1], 
                    ':bib' => $rowData[0][0],
    				':firstname' => $rowData[0][2],
                    ':lastname' => $rowData[0][3],
    				':team' => $rowData[0][4],
                    ':sex' => $rowData[0][5]
    		));

            $query_live = "INSERT INTO live (live_chip, live_bib, live_firstname, live_lastname, live_team) VALUES (:chip, :bib, :firstname, :lastname, :team)";

            $stmt_live = $db->prepare($query_live);
            $stmt_live->execute(
                array(
                    ':chip' => $rowData[0][1], 
                    ':bib' => $rowData[0][0],
                    ':firstname' => $rowData[0][2],
                    ':lastname' => $rowData[0][3],
                    ':team' => $rowData[0][4]
            ));
    	}

    }  

    header("location:/athletesetu");

?>