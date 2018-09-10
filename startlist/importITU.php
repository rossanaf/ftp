<?php

    //load the database configuration file
    //header('Content-Type: text/html; charset=UTF-8');

    include_once ($_SERVER['DOCUMENT_ROOT']."/html/header.php");
    include($_SERVER['DOCUMENT_ROOT']."/functions/PHPExcel/IOFactory.php");

    $stmt = $db->prepare("TRUNCATE athletes; TRUNCATE chips; TRUNCATE cjovem; TRUNCATE gunshots; TRUNCATE races; TRUNCATE results; TRUNCATE times; TRUNCATE youthraces; TRUNCATE live;");
    $stmt->execute();

    if(isset($_POST['prova_id']))
    {

        //Use whatever path to an Excel file you need.
        $inputFileName = $_FILES['file']['tmp_name'];
        $race_index = 1;
        $i = 0;
        try 
        {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
            foreach ($objPHPExcel->getAllSheets() as $sheet) 
            {
                $sheet = $objPHPExcel->getSheet($i);
                if (stripos($sheet->getTitle(),'women') > -1) 
                {
                    $gender = 'F';
                } else {
                    $gender = 'M';
                }
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                // echo $sheet->getTitle()."-".$highestRow.'-'.$highestColumn.'<br>';
                $race_id = "Prova".$race_index;
                for ($row = 2; $row <= $highestRow; $row++) 
                {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
                    $query = "INSERT INTO athletes (athlete_chip, athlete_category, athlete_bib, athlete_name, athlete_sex, athlete_race_id) VALUES (:chip, :cat, :bib, :name, :sex, :race)";
                    $stmt = $db->prepare($query);
                    $stmt->execute(
                        array(
                            ':chip' => $rowData[0][1], 
                            ':cat' => $rowData[0][4],
                            ':bib' => $rowData[0][0],
                            ':name' => $rowData[0][2].' '.$rowData[0][3],
                            ':sex' => $gender,
                            ':race' => $race_id
                            // ':dob' => gmdate("d-m-Y", $UNIX_DATE)
                            // ':dob' => $UNIX_DATE
                        ));
                    $stmt = $db->prepare("INSERT INTO live (live_chip, live_bib, live_firstname, live_lastname, live_sex, live_category, live_race) VALUES (:chip, :bib, :firstname, :lastname, :sex, :category, :race)");
                    $stmt->execute(
                        array(
                            ':chip' => $rowData[0][1], 
                            ':bib' => $rowData[0][0],
                            ':firstname' => $rowData[0][2],
                            ':lastname' => $rowData[0][3],
                            ':sex' => $gender,
                            ':category' => $rowData[0][4],
                            ':race' => $race_id
                        ));
                    // echo $rowData[0][0].'<br>';
                }
                $stmt = $db->prepare("INSERT INTO races(race_id, race_name, race_type, race_gender) VALUES (:id, :name, :type, :gender)");
                $stmt->execute(
                    array(
                        ':id' => $race_id, 
                        ':name' => $sheet->getTitle(),
                        ':type' => $_POST['prova_id'],
                        ':gender' => $gender
                        ));
                $race_index++;
                $i++;
            }
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . 
            $e->getMessage());
        }
    }
?>