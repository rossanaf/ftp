<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	if(isset($_POST["time_id"]))
	{
		$timer = $_POST["time_id"];
		$query_all = "SELECT athlete_id, athlete_started, athlete_t1, athlete_t2, athlete_t3, athlete_t4, athlete_t5 FROM athletes WHERE athlete_".strtolower($timer)." != '-'";
		$stmt_all = $db->prepare($query_all);
		$stmt_all->execute();
		$result_all = $stmt_all->fetchAll();
		foreach($result_all as $row){
			if (($timer == "T1") && ($row['athlete_started'] == 1)) 
			{
                $started=0;
			} elseif (($timer == "T2") && ($row['athlete_started'] == 2)) {
                if ($row['athlete_t1'] != "-")
	                $started=1;
	            else
	            	$started=0;
            } elseif (($timer == "T3") && ($row['athlete_started'] == 3)) {
                if ($row['athlete_t2'] != "-")
	                $started=2;
	            elseif ($row['athlete_t1'] != "-")
	            	$started=1;
	            else
	            	$started=0;
            } elseif (($timer == "T4") && ($row['athlete_started'] == 4)) {
                if ($row['athlete_t3'] != "-")
	                $started=3;
                elseif ($row['athlete_t2'] != "-")
	                $started=2;
	            elseif ($row['athlete_t1'] != "-")
	            	$started=1;
	            else
	            	$started=0;
            } elseif (($timer == "T5") && ($row['athlete_started'] == 5)) {
                if ($row['athlete_t3'] != "-")
	                $started=3;
                elseif ($row['athlete_t2'] != "-")
	                $started=2;
	            elseif ($row['athlete_t1'] != "-")
	            	$started=1;
	            else
	            	$started=0;
            } else {
            	$started = $row['athlete_started'];
            }
            if ($timer == "T5") 
            {
				$query = "UPDATE athletes SET athlete_pos=9999,athlete_t5='-',athlete_finishtime='-',athlete_totaltime='-',athlete_started=".$started." WHERE athlete_id=".$row['athlete_id'];
				$query_live = "UPDATE live SET live_pos=9999,live_t5='time',live_finishtime='time',live_started=".$started." WHERE live_id=".$row['athlete_id'];
            }
			else
			{
				$query = "UPDATE athletes SET athlete_".strtolower($timer)."='-',athlete_started=".$started." WHERE athlete_id=".$row['athlete_id'];
				$query_live = "UPDATE live SET live_".strtolower($timer)."='time', live_started=".$started." WHERE live_id=".$row['athlete_id'];
			}
			$stmt = $db->prepare($query);
			$stmt->execute();
			$stmt_live = $db->prepare($query_live);
			$stmt_live->execute();
		}
		$query = "DELETE FROM times WHERE Location='Time".$timer."'";
		//echo ($query);
		$stmt = $db->prepare($query);
		$stmt->execute();	

		if(!empty($result_all))
		{
			echo 'Tempo Eliminado';
		}

	}
?>