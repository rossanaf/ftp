<?php
    include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

    $queryraces = $db->query("SELECT race_id, race_gun FROM races");
    $races = $queryraces->fetchAll();
    foreach ($races as $race) 
    {
        $queryathletes = $db->prepare("SELECT * FROM athletes WHERE athlete_race_id = ?");
        $queryathletes->execute([$race['race_id']]);
        $athletes = $queryathletes->fetchAll();
        foreach ($athletes as $athlete) 
        {
            if($athlete['athlete_finishtime']!=="DSQ")
            {
                $querymylaps = $db->prepare("SELECT * FROM times WHERE Chip = ? AND LapRaw = 1 ORDER BY Millisecs");
                $querymylaps->execute([$athlete['athlete_chip']]);
                $times = $querymylaps->fetchAll();
                foreach ($times as $timelap) 
                {
                    list($date, $time) = explode (" ", $timelap['ChipTime']);
                    //$time = gmdate('H:i:s', strtotime($time)-strtotime("01:00:00"));
                    $total = gmdate('H:i:s', strtotime($time)-strtotime($race['race_gun']));
                    $location = $timelap['Location']; 
                    $started = $athlete['athlete_started'];
                        
                    // **** Tempo Natação ****//
                    if($timelap['Location']=="TimeT1" && $athlete['athlete_t1']=="-")
                    {
                        $querytimes = $db->prepare("UPDATE athletes SET athlete_t1 = ? WHERE athlete_chip = ?");
                        $querytimes->execute([$time, $timelap['Chip']]);
                        if($athlete['athlete_started']==0)
                        {
                            $querytimes = $db->prepare("UPDATE athletes SET athlete_started = 1 WHERE athlete_chip = ?");
                            $querytimes->execute([$timelap['Chip']]);
                            //$started = 1;
                        }
                        // $time = gmdate('H:i:s', strtotime($time)-strtotime($race['race_gun'])); 
                        // mysqli_query($db, "UPDATE live SET live_t1 = '".$time."', live_finishtime = '".$total."', live_started = '".$started."' WHERE live_chip = '".$timelap['Chip']."'");
                    }

                    // **** Tempo Transição 1 ****//
                    if($timelap['Location']=="TimeT2" && $athlete['athlete_t2']=="-")
                    {
                        $querytimes = $db->prepare("UPDATE athletes SET athlete_t2 = ? WHERE athlete_chip = ?");
                        $querytimes->execute([$time, $timelap['Chip']]);
                        if($athlete['athlete_started']<=1)
                        {
                            $querytimes = $db->prepare("UPDATE athletes SET athlete_started = 2 WHERE athlete_chip = ?");
                            $querytimes->execute([$timelap['Chip']]);
                            // $started = 2;
                        }
                        // if ($athlete['athlete_t1']!=="-")
                        // {
                        //     $time = gmdate('H:i:s', strtotime($time)-strtotime($athlete['athlete_t1']));
                        //     mysqli_query($db, "UPDATE live SET live_t2 = '".$time."', live_finishtime = '".$total."', live_started = '".$started."' WHERE live_chip = '".$timelap['Chip']."'");
                        // } 
                    }
                    // **** Tempo Ciclismo ****//
                    if($timelap['Location']=="TimeT3" && $athlete['athlete_t3']=="-")
                    {
                        $querytimes = $db->prepare("UPDATE athletes SET athlete_t3 = ? WHERE athlete_chip = ?");
                        $querytimes->execute([$time, $timelap['Chip']]);
                        if($athlete['athlete_started']<=2)
                        {
                            $querytimes = $db->prepare("UPDATE athletes SET athlete_started = 3 WHERE athlete_chip = ?");
                            $querytimes->execute([$timelap['Chip']]);
                            //$started = 3;
                        }
                        // if ($athlete['athlete_t2']!=="-") 
                        // {
                        //     $time = gmdate('H:i:s', strtotime($time)-strtotime($athlete['athlete_t2']));
                        //     mysqli_query($db, "UPDATE live SET live_t3 = '".$time."', live_finishtime = '".$total."', live_started = '".$started."' WHERE live_chip = '".$timelap['Chip']."'");
                        // }
                    }
                    // **** Tempo Transição 2 ****//
                    if($timelap['Location']=="TimeT4" && $athlete['athlete_t4']=="-")
                    {
                        $querytimes = $db->prepare("UPDATE athletes SET athlete_t4 = ? WHERE athlete_chip = ?");
                        $querytimes->execute([$time, $timelap['Chip']]);
                        if($athlete['athlete_started']<=3)
                        {
                            $querytimes = $db->prepare("UPDATE athletes SET athlete_started = 4 WHERE athlete_chip = ?");
                            $querytimes->execute([$timelap['Chip']]);
                            //$started = 4;
                        }
                        // if ($athlete['athlete_t3']!=="-") 
                        // {
                        //     $time = gmdate('H:i:s', strtotime($time)-strtotime($athlete['athlete_t3']));
                        //     mysqli_query($db, "UPDATE live SET live_t4 = '".$time."', live_finishtime = '".$total."', live_started = '".$started."' WHERE live_chip = '".$timelap['Chip']."'");
                        // }
                    }
                    //**** Tempo Corrida/Meta ****//
                    if($timelap['Location']=="TimeT5" && $athlete['athlete_t5']=="-")
                    {
                        //$ttotal = gmdate('H:i:s', strtotime($time)-strtotime($race['race_gun']));
                        //echo $total." ".$time."<br>";
                        $querytimes = $db->prepare("UPDATE athletes SET athlete_t5 = :time, athlete_finishtime = :time, athlete_started = 5 WHERE athlete_chip = :chip");
                        $querytimes->execute(
                            array(':time' => $time, 
                                ':chip' => $timelap['Chip'],
                            ));
                        // if ($athlete['athlete_t4']!=="-") 
                        // {
                        //     $time = gmdate('H:i:s', strtotime($time)-strtotime($athlete['athlete_t4']));
                        //     mysqli_query($db, "UPDATE live SET live_t5 = '".$time."', live_finishtime = '".$ttotal."', live_started = 5 WHERE live_chip = '".$timelap['Chip']."'");
                        // } else {
                        //     mysqli_query($db, "UPDATE live SET live_finishtime = '".$ttotal."', live_started = 5 WHERE live_chip = '".$timelap['Chip']."'");
                        // }
                    }
                }
            }
        }

        $queryathletes = $db->prepare("SELECT athlete_id, athlete_finishtime FROM athletes WHERE athlete_started = 5 AND athlete_race_id = ?");
        $queryathletes->execute([$race['race_id']]);
        $athletes = $queryathletes->fetchAll();
        foreach ($athletes as $athlete) 
        {
            $ttotal = gmdate('H:i:s', strtotime($athlete['athlete_finishtime'])-strtotime($race['race_gun']));
            $updateathletes = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_id = ?");
            $updateathletes->execute([$ttotal, $athlete['athlete_id']]);
            // $updatelive = $db->prepare("UPDATE live SET live_pos = ? WHERE live_chip = ?");
            // $updatelive->execute([$pos, $athlete['athlete_chip']]);
        }
    }

    $pos = 1;
    $queryathletes = $db->query("SELECT athlete_id, athlete_finishtime FROM athletes WHERE athlete_started = 5 ORDER BY athlete_finishtime");
    $athletes = $queryathletes->fetchAll();
    foreach ($athletes as $athlete) {
        $updateathletes = $db->prepare("UPDATE athletes SET athlete_pos = ? WHERE athlete_id = ?");
        $updateathletes->execute([$pos, $athlete['athlete_id']]);
        // $updatelive = $db->prepare("UPDATE live SET live_pos = ? WHERE live_chip = ?");
        // $updatelive->execute([$pos, $athlete['athlete_chip']]);
        $pos ++; 
    }
?>