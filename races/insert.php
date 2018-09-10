<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include ($_SERVER['DOCUMENT_ROOT']."/functions/isTime.php");

	if(isset($_POST["operation"])){
    // so corre GUN se tipo de prova for crelogio ou estafeta mista
		if($_POST["operation"] == "Edit"){
      $stmt = $db->prepare("UPDATE races SET race_namepdf = :namepdf, race_ranking = :ranking, race_segment1 = :segment1, race_distsegment1 = :distsegment1, race_distsegment2 = :distsegment2, race_distsegment3 = :distsegment3, race_date = :date, race_location = :location WHERE race_id = :id"
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
        ':id'	=>	$_POST["user_id"]
			));
			if(!empty($result)){
				echo 'Dados do atleta atualizados!';
			}
		}
	}
?>