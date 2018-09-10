<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	
	function isTime($time){
	    if((strlen($time)==6) || (strlen($time)==8)){
	        if(strlen($time)==6){
	            $final_time="";
	            for($i=0;$i<6;$i++){
	                if(($i==2) || ($i==4)) 
	                    $final_time.=":";
	                $final_time.=$time[$i];
	            }
	            list($h,$m,$s)=explode(":",$final_time);
	            if(($h<24) && ($m<60) && ($s<60))
	                return $final_time;
	            else
	                return "-";
	        }else return $time;
	    }else
	        return "-";
	}
	// INICIALIZAR VARIÁVEIS
	$time = $_POST["time"] ?? '';
	$pos = $_POST["pos"] ?? '';

	$started = 0;
	// $live_pos = $pos;
	// $update_pos = 0;

	if(isset($_POST["operation"]))
	{
		if($_POST["operation"] == "Add")
		{
			$stmt = $db->prepare("
				INSERT INTO athletes (athlete_chip, athlete_bib, athlete_name, athlete_sex, athlete_category, athlete_race_id) VALUES (:chip, :bib, :name, :sex, :category, :race)
			");
			$result = $stmt->execute(
				array(
					':chip'	=>	$_POST["chip"],
					':bib'	=>	$_POST["dorsal"],
					':name'		=>	$_POST["firstname"].' '.$_POST["lastname"],
					':sex'		=>	$_POST["sexo"],
					':category'		=>	$_POST["clube"],
					':race'		=>	$_POST["race"]
				));
			$stmt_live = $db->prepare("
				INSERT INTO live (live_chip, live_bib, live_firstname, live_lastname, live_sex, live_category, live_race) VALUES (:chip, :bib, :firstname, :lastname, :sex, :category, :race)
			");
			$stmt_live->execute(
				array(
					':chip'	=>	$_POST["chip"],
					':bib'	=>	$_POST["dorsal"],
					':firstname'		=>	$_POST["firstname"],
					':lastname'		=>	$_POST["lastname"],
					':sex' => $_POST["sexo"],
					':category'		=>	$_POST["clube"],
					':race'		=>	$_POST["race"]
				));
		}
		if($_POST["operation"] == "Edit")
		{
	        // EDITAR TEMPO E ATUALIZAR A TABELA LIVE
			// LER GUN PARA CALCULAR OS TEMPOS
			$live_swim = "time";
			$live_t1 = "time";
			$live_bike = "time";
			$live_t2 = "time";
			$live_run = "time";
			$finishtime = "time";
	        if ($time == "DNS")
	        {
	        	$swim="-"; $live_swim = "time";
	            $t1="-"; $live_t1 = "time";
	            $bike="-"; $live_bike = "time";
	            $t2="-"; $live_t2 = "time";
	            $run="-"; $live_run = "time";
	            $finishtime = "time";
	            $pos=9999;
	            $time = $time;
	            $finishtime = $time;
	            $live_pos = -1;
	        } else {
	        	$stmt_race = $db->prepare("SELECT race_type, race_gun FROM races WHERE race_id = ?  LIMIT 1");
				$stmt_race->execute([$_POST["race"]]);
				$race = $stmt_race->fetch();
				$row_gun = $race['race_gun'];
	        	// $time = '-';
	        	$swim = isTime($_POST['swim']);
	            if($swim !== "-")
	            {
	                $live_swim = gmdate('H:i:s', strtotime($_POST['swim']) - strtotime($row_gun));
	                $finishtime = $live_swim;
	                $started = 1;
	            }
	            $t1 = isTime($_POST['t1']);
	            if($t1 !== "-")
	            {
	                $started=2;
	                $finishtime = gmdate('H:i:s', strtotime($_POST['t1']) - strtotime($row_gun));
	                if ($swim !=="-")
	                	$live_t1 = gmdate('H:i:s', strtotime($_POST['t1']) - strtotime($_POST['swim']));
	            }
	            $bike = isTime($_POST['bike']);
	            if($bike !== "-")
	            {
	                $started=3;
	                $finishtime = gmdate('H:i:s', strtotime($_POST['bike']) - strtotime($row_gun));
	                if ($t1 !== "-")
	                	$live_bike = gmdate('H:i:s', strtotime($_POST['bike']) - strtotime($_POST['t1']));
	            }
	            $t2 = isTime($_POST['t2']);
	            if($t2 !== "-")
	            {
	                $started=4;
	                $finishtime = gmdate('H:i:s', strtotime($_POST['t2']) - strtotime($row_gun));
	                if ($bike !== "-")
	                	$live_t2 = gmdate('H:i:s', strtotime($_POST['t2']) - strtotime($_POST['bike']));
	            }
	            $run = isTime($_POST['run']);
	            if (($run !== "-") && ($time !== "DNF"))
	            {
					$started=5;
					$finishtime = gmdate('H:i:s', strtotime($_POST['run']) - strtotime($row_gun));
	                if ($t2 !== "-")
	                	$live_run = gmdate('H:i:s', strtotime($_POST['run']) - strtotime($_POST['t2']));
				} else {
	            	$total = isTime($_POST['time']);
	            	if ($total !== "-") 
	            	{
						$epoch = strtotime($row_gun)+strtotime($total);
					    $dt = new DateTime("@$epoch");
					    $run = $dt->format('H:i:s');
					    $live_run = gmdate('H:i:s', strtotime($run) - strtotime($_POST['bike']));
					    $finishtime = gmdate('H:i:s', strtotime($run) - strtotime($row_gun));
					    $started = 5;
	            	}
	            }
	            if(($time=="DNF") || ($time=="LAP") || ($time == "DSQ")) 
	            {
	                $time = $time;
	                $finishtime = $time;
	                $run = "-";
	                $live_pos = -1;
	                // $reorganize = true;
	                // if ($time != "DNF")
                	// {
                	// 	$started = 0;
                	// } 
	            } else
	                $time = $run;
	            if($started!==5)
	                $pos = 9999;
	        }
	        // if ($started > 0) 
	        // 	$time = '-';

	        $stmt = $db->prepare(
				"UPDATE athletes SET athlete_chip = :chip, athlete_pos = :pos, athlete_bib = :dorsal, athlete_name = :name, athlete_sex = :sexo, athlete_category = :clube, athlete_t1 = :swim, athlete_t2 = :t1, athlete_t3 = :bike, athlete_t4 = :t2, athlete_t5 = :run, athlete_race_id = :race, athlete_finishtime = :time, athlete_started = :started, athlete_totaltime = '-' WHERE athlete_id = :id"
			);
	        $result = $stmt->execute(
				array(
	                ':chip' => $_POST["chip"],
	                ':pos'	=>	$pos,
					':dorsal'	=>	$_POST["dorsal"],
					':name'		=>	$_POST["firstname"].' '.$_POST["lastname"],
	                ':sexo'		=>	$_POST["sexo"],
					':clube'		=>	$_POST["clube"],
					':swim'		=>	$swim,
					':t1'		=>	$t1,
					':bike'		=>	$bike,
					':t2'		=>	$t2,
					':run'		=>	$run,
					':race'		=>	$_POST["race"],
					':time'		=>	$time,
					':started'		=>	$started,
					':id'	=>	$_POST["user_id"]	
				)
			);
			// O ID SERÁ SEMPRE O MESMO PORQUE É FEITO O TRUNCATE ANTES DE IMPORTAR TABELAS, A PRIMEIRA VEZ, FORCAR NO IMPORT
			$stmt_live = $db->prepare(
				"UPDATE live SET live_pos = :live_pos, live_chip = :chip, live_bib = :dorsal, live_firstname = :firstname,  live_lastname = :lastname, live_category = :clube, live_t1 = :swim, live_t2 = :t1, live_t3 = :bike, live_t4 = :t2, live_t5 = :run, live_finishtime = :finishtime, live_started = :started WHERE live_id = :id"
			);
	        $stmt_live->execute(
				array(
					':live_pos'	=>	$live_pos,
					':id'	=>	$_POST["user_id"],
	                ':chip' => $_POST["chip"],
					':dorsal'	=>	$_POST["dorsal"],
					':firstname'		=>	$_POST["firstname"],
					':lastname'		=>	$_POST["lastname"],
	                ':clube'		=>	$_POST["clube"],
	                ':swim'		=>	$live_swim,
					':t1'		=>	$live_t1,
					':bike'		=>	$live_bike,
					':t2'		=>	$live_t2,
					':run'		=>	$live_run,
					':finishtime'		=>	$finishtime,
					':started' => $started
				)
			);
		}
	}
	//**** atualizar coluna 'pos' conforme tempo total, para validar com registo de meta
    $query = "SELECT athlete_id FROM athletes WHERE athlete_started = '5' ORDER BY athlete_finishtime";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $pos = 1;
    foreach($result as $row)
    {
        $stmt = $db->prepare("UPDATE athletes SET athlete_pos = :pos WHERE athlete_id = :id");
        $result = $stmt->execute(
        array(
	        ':id'	=>	$row["athlete_id"],
	        ':pos'	=>	$pos
	    ));
        $pos++;
    }
    // POSICAO DA TABELA LIVE ATULIZADA POR GENERO - M
    $pos = 1;
    $queryathletes = $db->query("SELECT live_id FROM live WHERE live_started = 5 AND live_sex = 'M' ORDER BY live_finishtime");
    $athletes = $queryathletes->fetchAll();
    foreach ($athletes as $athlete) 
    {
        $updatelive = $db->prepare("UPDATE live SET live_pos = ? WHERE live_id = ?");
        $updatelive->execute([$pos, $athlete['live_id']]);
        $pos ++; 
    }
    // POSICAO DA TABELA LIVE ATULIZADA POR GENERO  F
    $pos = 1;
    $queryathletes = $db->query("SELECT live_id FROM live WHERE live_started = 5 AND live_sex = 'F' ORDER BY live_finishtime");
    $athletes = $queryathletes->fetchAll();
    foreach ($athletes as $athlete) 
    {
        $updatelive = $db->prepare("UPDATE live SET live_pos = ? WHERE live_id = ?");
        $updatelive->execute([$pos, $athlete['live_id']]);
        $pos ++; 
    }
?>