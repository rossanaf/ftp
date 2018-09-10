<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	$time = $_POST["time"] ?? '';
	$pos = $_POST["pos"] ?? '';

	$started = 0;
	$update_pos = 0;

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

	if(isset($_POST["operation"]))
	{
		if($_POST["operation"] == "Add")
		{
			$stmt = $db->prepare("
				INSERT INTO athletes (athlete_chip, athlete_bib, athlete_name, athlete_sex, athlete_category,  athlete_race_id) VALUES (:chip, :bib, :firstname, :lastname, :sex, :team, :race)
			");
			$result = $stmt->execute(
				array(
					':chip'	=>	$_POST["chip"],
					':bib'	=>	$_POST["dorsal"],
					':firstname'		=>	$_POST["firstname"],
					':sex'		=>	$_POST["sexo"],
					':category'		=>	$_POST["clube"],
					':race'		=>	$_POST["race"]
				)
			);

			$stmt_live = $db->prepare("
				INSERT INTO live (live_chip, live_bib, live_firstname, live_lastname, live_category, live_sex) VALUES (:chip, :bib, :firstname, :lastname, :team, :sex)
			");
			$result_live = $stmt_live->execute(
				array(
					':chip'	=> $_POST["chip"],
					':bib' => $_POST["dorsal"],
					':firstname' =>	$_POST["firstname"],
					':lastname' => $_POST["lastname"],
					':team' => $_POST["clube"],
					':sex' => $_POST["sexo"]
				)
			);


			if (!empty($result))
			{
				echo 'Atleta Inserido!';
			}
		}
	
		if($_POST["operation"] == "Edit")
		{
			// EDITAR TEMPO E ATUALIZAR A TABELA LIVE
			// LER GUN PARA CALCULAR OS TEMPOS
			$stmt_gun = $db->prepare("SELECT gunshot_starttime FROM gunshots LIMIT 1");
			$stmt_gun -> execute();
			$row_gun = $stmt_gun -> fetch();

			$live_swim = "time";
			$live_t1 = "time";
			$live_bike = "time";
			$live_t2 = "time";
			$live_run = "time";

	        if($time == "DSQ")
	        {
	            $swim="-"; $live_swim = "time";
	            $t1="-"; $live_t1 = "time";
	            $bike="-"; $live_bike = "time";
	            $t2="-"; $live_t2 = "time";
	            $run="-"; $live_run = "time";
	            $started=0;
	            $pos=9999;
	            $time = $time;
	        } else {
	            $swim = isTime($swim = $_POST['swim']);
	            if($swim!="-")
	            {
	                $live_swim = gmdate('H:i:s', strtotime($_POST['swim']) - strtotime($row_gun['gunshot_starttime']));
	                $finishtime = $live_swim;
	                $started = 1;
	                
	            }
	            $t1 = isTime($_POST['t1']);
	            if($t1!="-")
	            {
	                $started = 2;
	                $finishtime = gmdate('H:i:s', strtotime($_POST['t1']) - strtotime($row_gun['gunshot_starttime']));
	                if ($swim != "-")
	                	$live_t1 = gmdate('H:i:s', strtotime($_POST['t1']) - strtotime($_POST['swim']));
	            }
	            $bike = isTime($_POST['bike']);
	            if($bike!="-")
	            {
	                $started = 3;
	                $finishtime = gmdate('H:i:s', strtotime($_POST['bike']) - strtotime($row_gun['gunshot_starttime']));
	                if ($t1 != "-")
	                	$live_bike = gmdate('H:i:s', strtotime($_POST['bike']) - strtotime($_POST['t1']));
	            }
	            $t2 = isTime($_POST['t2']);
	            if($t2!="-")
	            {
	                $started = 4;
	                $finishtime = gmdate('H:i:s', strtotime($_POST['t2']) - strtotime($row_gun['gunshot_starttime']));
	                if ($t2 != "-")
	                	$live_t2 = gmdate('H:i:s', strtotime($_POST['t2']) - strtotime($_POST['bike']));
	            }
	            $run = isTime($_POST['run']);
	            if($run!="-")
	            {
	                $started = 5;
	                $finishtime = gmdate('H:i:s', strtotime($_POST['run']) - strtotime($row_gun['gunshot_starttime']));
	                if ($t2 != "-")
	                	$live_run = gmdate('H:i:s', strtotime($_POST['run']) - strtotime($_POST['t2']));
	            }
	            if(($time=="DNF") || ($time=="DNS") || ($time=="LAP") || ($time=="chkin"))
	            {
	                $time = $time;
	                $started = 0;
	                echo "<script>alert(' chega aqui ');</script>"; //chega aqui porque é igual a chkin
	                $run = "-";
	            } else {
	                $time=$run;
	            }
	            if($started!==5)
	                $pos = 9999;
	        }
	        
			$stmt = $db->prepare(
				"UPDATE athletes SET athlete_chip = :chip, athlete_pos = :pos, athlete_bib = :dorsal, athlete_firstname = :firstname, athlete_lastname = :lastname, athlete_sex = :sexo, athlete_team = :clube, athlete_t1 = :swim, athlete_t2 = :t1, athlete_t3 = :bike, athlete_t4 = :t2, athlete_t5 = :run, athlete_race_id = :race, athlete_finishtime = :time, athlete_started = :started, athlete_totaltime = '-' WHERE athlete_id = :id"
			);
	        $result = $stmt->execute(
				array(
	                ':pos'	=>	$pos,
	                ':chip' => $_POST["chip"],
					':dorsal'	=>	$_POST["dorsal"],
					':firstname'		=>	$_POST["firstname"],
	                ':lastname'		=>	$_POST["lastname"],
					':sexo'		=>	$_POST["sexo"],
					':clube'		=>	$_POST["clube"],
					':swim'		=>	$swim,
					':t1'		=>	$t1,
					':bike'		=>	$bike,
					':t2'		=>	$t2,
					':run'		=>	$run,
					':race'		=>	$_POST["race"],
					':time'		=>	$time,
					':started' => $started,
					':id'	=>	$_POST["user_id"]
				)
			);

	        // O CHIP SERÁ SEMPRE O MESMO PORQUE É FEITO O TRUNCATE ANTES DE IMPORTAR TABELAS, A PRIMEIRA VEZ
			$stmt_live = $db->prepare(
				"UPDATE live SET live_chip = :chip, live_bib = :dorsal, live_firstname = :firstname, live_lastname = :lastname, live_team = :clube, live_t1 = :swim, live_t2 = :t1, live_t3 = :bike, live_t4 = :t2, live_t5 = :run, live_finishtime = :finishtime, live_started = :started WHERE live_id = :id"
			);
	        $stmt_live->execute(
				array(
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
			if(!empty($result))
			{
				echo 'Dados do atleta atualizados!';
			}
		}
	}

	if($started==5){
	    //**** atualizar coluna 'pos' conforme tempo total, para validar com registo de meta
	    $query = "SELECT athlete_id, athlete_finishtime FROM athletes WHERE athlete_started >= '5' ORDER BY athlete_finishtime";
	    $stmt = $db->prepare($query);
	    $stmt->execute();
	    $result = $stmt->fetchAll();
	    $pos = 1;
	    foreach($result as $row){
	    	$stmt_live = $db->prepare("UPDATE live SET live_pos = :pos WHERE live_id = :id");
	    	$stmt_live->execute(
		        array(
			        ':id'	=>	$row["athlete_id"],
			        ':pos'	=>	$pos
			    ));
	        $stmt = $db->prepare("UPDATE athletes SET athlete_pos = :pos WHERE athlete_id = :id");
	        $result = $stmt->execute(
		        array(
			        ':id'	=>	$row["athlete_id"],
			        ':pos'	=>	$pos
			    ));
	        $pos++;
	    }
	}

?>