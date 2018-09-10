<?php

include 'dbConfig.php';
include 'header.php';

//**** FEMININOS ****//
$query_gun = mysqli_query($db,"SELECT time FROM gunshot WHERE race = 'cnce-fem' LIMIT 1");
$row_gun = mysqli_fetch_array($query_gun);

$query_atletas = mysqli_query($db,"SELECT * FROM estafetas WHERE sexo = 'F'");

if(mysqli_num_rows($query_atletas)>0){
	while ($row_atletas = mysqli_fetch_array($query_atletas)){
		$query_mylaps = mysqli_query($db,"SELECT * FROM Times WHERE Chip = '".$row_atletas['chip']."' AND LapRaw = 1");
		if(mysqli_num_rows($query_mylaps)>0){
			while ($row_mylaps = mysqli_fetch_array($query_mylaps)){
				
				// **** Tempo Estafeta "A" ****//
                if($row_atletas['started'] == "A"){
                    mysqli_query($db, "UPDATE estafetas SET start_time = '".$row_gun['time']."' WHERE chip = '".$row_mylaps['Chip']."'");
                    if($row_mylaps['Location']=="Timeestafet" && $row_atletas['end_time']=="-"){
                        list($date, $time) = explode (" ", $row_mylaps['ChipTime']);
                        mysqli_query($db, "UPDATE estafetas SET end_time = '".$time."', started = '5' WHERE chip = '".$row_mylaps['Chip']."'");
                        mysqli_query($db, "UPDATE estafetas SET start_time = '".$time."' WHERE dorsal = '".$row_atletas['dorsal']."' AND started = 'B'");
                    }
                }
				
				// **** Tempo Estafeta "B" ****//
                if($row_atletas['started'] == "B"){
                    if($row_mylaps['Location']=="Timeestafet" && $row_atletas['end_time']=="-"){
                        list($date, $time) = explode (" ", $row_mylaps['ChipTime']);
                        mysqli_query($db, "UPDATE estafetas SET end_time = '".$time."', started = '5' WHERE chip = '".$row_mylaps['Chip']."'");
                        mysqli_query($db, "UPDATE estafetas SET start_time = '".$time."' WHERE dorsal = '".$row_atletas['dorsal']."' AND started = 'C'");
                    }
                }
				
				// **** Tempo Estafeta "C" ****//
                if($row_atletas['started'] == "C"){
                    if($row_mylaps['Location']=="Timeestafet" && $row_atletas['end_time']=="-"){
                        list($date, $time) = explode (" ", $row_mylaps['ChipTime']);
						$time_relay = gmdate('H:i:s', strtotime($time) - strtotime($row_gun['time']));
                        mysqli_query($db, "UPDATE estafetas SET end_time = '".$time."', time = '".$time_relay."', started = '5' WHERE chip = '".$row_mylaps['Chip']."'");
                    }
                }
            }
		}
	}
}

//**** MASCULINOS ****//
$query_gun = mysqli_query($db,"SELECT time FROM gunshot WHERE race = 'cnce-mas' LIMIT 1");
$row_gun = mysqli_fetch_array($query_gun);

$query_atletas = mysqli_query($db,"SELECT * FROM estafetas WHERE sexo = 'M'");

if(mysqli_num_rows($query_atletas)>0){
	while ($row_atletas = mysqli_fetch_array($query_atletas)){
		$query_mylaps = mysqli_query($db,"SELECT * FROM Times WHERE Chip = '".$row_atletas['chip']."' AND LapRaw = 1");
		if(mysqli_num_rows($query_mylaps)>0){
			while ($row_mylaps = mysqli_fetch_array($query_mylaps)){
				
				// **** Tempo Estafeta "A" ****//
                if($row_atletas['started'] == "A"){
                    mysqli_query($db, "UPDATE estafetas SET start_time = '".$row_gun['time']."' WHERE chip = '".$row_mylaps['Chip']."'");
                    if($row_mylaps['Location']=="Timeestafet" && $row_atletas['end_time']=="-"){
                        list($date, $time) = explode (" ", $row_mylaps['ChipTime']);
                        mysqli_query($db, "UPDATE estafetas SET end_time = '".$time."', started = '5' WHERE chip = '".$row_mylaps['Chip']."'");
                        mysqli_query($db, "UPDATE estafetas SET start_time = '".$time."' WHERE dorsal = '".$row_atletas['dorsal']."' AND started = 'B'");
                    }
                }
				
				// **** Tempo Estafeta "B" ****//
                if($row_atletas['started'] == "B"){
                    if($row_mylaps['Location']=="Timeestafet" && $row_atletas['end_time']=="-"){
                        list($date, $time) = explode (" ", $row_mylaps['ChipTime']);
                        mysqli_query($db, "UPDATE estafetas SET end_time = '".$time."', started = '5' WHERE chip = '".$row_mylaps['Chip']."'");
                        mysqli_query($db, "UPDATE estafetas SET start_time = '".$time."' WHERE dorsal = '".$row_atletas['dorsal']."' AND started = 'C'");
                    }
                }
				
				// **** Tempo Estafeta "C" ****//
                if($row_atletas['started'] == "C"){
                    if($row_mylaps['Location']=="Timeestafet" && $row_atletas['end_time']=="-"){
                        list($date, $time) = explode (" ", $row_mylaps['ChipTime']);
                        $time_relay = gmdate('H:i:s', strtotime($time) - strtotime($row_gun['time']));
                        mysqli_query($db, "UPDATE estafetas SET end_time = '".$time."', time = '".$time_relay."', started = '5' WHERE chip = '".$row_mylaps['Chip']."'");
                    }
                }
            }
		}
	}
}
?>