<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include ($_SERVER['DOCUMENT_ROOT']."/functions/isTime.php");

	if(isset($_POST["operation"])){
    // so corre GUN se tipo de prova for crelogio ou estafeta mista
    if ($_POST['live'] == 'on') {
      $live = '1';
      // QUANDO LIGA LIVE DEVERA ATUALIZAR TODOS OS TEMPOS DA TABELA LIVE, A PARTIR DOS TEMPOS DE ATLETAS     
    }
    else {
      $live = '0';
      // $stmtLive = $db->prepare('UPDATE live SET live_t1="time", live_t2="time", live_t3="time", live_t4="time", live_t5="time", live_finishtime="time", live_pos=9999, live_started=0 WHERE live_race=?');
      // $stmtLive->execute([$_POST["user_id"]]);
    }
		if($_POST["operation"] == "Edit"){
      $stmt = $db->prepare("UPDATE races SET race_namepdf = :namepdf, race_ranking = :ranking, race_segment1 = :segment1, race_distsegment1 = :distsegment1, race_distsegment2 = :distsegment2, race_distsegment3 = :distsegment3, race_date = :date, race_location = :location, race_live = :live WHERE race_id = :id"
      );
      $result = $stmt->execute(array(
        ':ranking'	=>	$_POST["ranking"],
        ':namepdf' => $_POST["namepdf"],
				':segment1'		=>	$_POST["segment1"],
        ':distsegment1'		=>	$_POST["distsegment1"],
        ':distsegment2'		=>	$_POST["distsegment2"],
        ':distsegment3'		=>	$_POST["distsegment3"],
        ':date'		=>	$_POST["date"],
        ':location'		=>	$_POST["location"],
        ':id'	=>	$_POST["user_id"],
        ':live' => $live
			));
			if(!empty($result)){
				echo 'Dados do atleta atualizados!';
			}
		}
	}
?>