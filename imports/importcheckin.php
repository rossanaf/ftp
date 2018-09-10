<?php
//load the database configuration file
//header('Content-Type: text/html; charset=UTF-8');
include_once ($_SERVER['DOCUMENT_ROOT']."/html/header.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/html/nav.php");

include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

//mb_internal_encoding("UTF8");
//mysqli_set_charset("utf8");

if(isset($_POST['importSubmit'])){
    
    //validate whether uploaded file is a csv file
    $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            //open uploaded csv file with read only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            //skip first line
            fgetcsv($csvFile);
            
            //parse data from csv file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE)
            {
                // print_r($line);
                // echo "<br>";
                $stmt_chip = $db->prepare("SELECT athlete_id FROM athletes WHERE athlete_chip = ? LIMIT 1");
                $stmt_chip->execute([$line[0]]);
                $row = $stmt_chip->fetch();
                if(! $row) {
                    //echo "nao existe ".$line[0]."<br>";
                    $stmt = $db->prepare("INSERT INTO athletes (athlete_chip, athlete_finishtime) VALUES (?, 'validar')");
                    $stmt->execute([$line[0]]);
                } else {
                    $stmt = $db->prepare("UPDATE athletes SET athlete_finishtime = '-' WHERE athlete_chip = ?");
                    $stmt->execute([$line[0]]);
                }
            }
            
            fclose($csvFile);
			$qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }

}

include_once ($_SERVER['DOCUMENT_ROOT']."/html/footer.php");

?>