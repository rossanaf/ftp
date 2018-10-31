<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	if(isset($_POST["time_id"]))
	{
		//validate whether uploaded file is a csv file
	    $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
	    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
	        if(is_uploaded_file($_FILES['file']['tmp_name'])){
	        	//variable with the Time to add times
				$timer = $_POST["time_id"];
	            //open uploaded csv file with read only mode
	            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
	            //skip first line
	            fgetcsv($csvFile);	            
	            //parse data from csv file line by line
	            while(($line = fgetcsv($csvFile)) !== FALSE)
	            {
	                $stmt_chip = $db->prepare("SELECT athlete_started FROM athletes WHERE athlete_chip = ? LIMIT 1");
	                $stmt_chip->execute([$line[0]]);
	                $row = $stmt_chip->fetch();
	                if($row) {
	                	$started=$row['athlete_started'];
	                	if ($started<$timer[1]) 
	                	{
	                		$started=$timer[1];
	                	}
	                	if ($timer=="T5") 
	                		$query = "UPDATE athletes SET athlete_started = ".$started.", athlete_".$timer." = '".str_replace(' ', '',$line[1])."', athlete_finishtime = '".str_replace(' ', '',$line[1])."' WHERE athlete_chip = '".$line[0]."'";
	                	else 
	                		$query = "UPDATE athletes SET athlete_started = ".$started.", athlete_".$timer." = '".str_replace(' ', '',$line[1])."' WHERE athlete_chip = '".$line[0]."'";
	                	echo $query;
	                	$stmt = $db->prepare($query);
	                	$stmt->execute();
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
?> 