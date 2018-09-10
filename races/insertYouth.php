<?php
	print_r($_POST);
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

	// INSERIR NAS TABELAS YOUTHRACES E RACES (POR UMA QUESTÃO DE APARENCIA)
	$stmt = $db->prepare("UPDATE youthraces SET youthrace_namepdf=?,youthrace_ranking=?,youthrace_s1_ben=?,youthrace_d1_ben=?,youthrace_d2_ben=?,youthrace_d3_ben=?,youthrace_s1_inf=?,youthrace_d1_inf=?,youthrace_d2_inf=?,youthrace_d3_inf=?,youthrace_s1_ini=?,youthrace_d1_ini=?,youthrace_d2_ini=?,youthrace_d3_ini=?,youthrace_s1_juv=?,youthrace_d1_juv=?,youthrace_d2_juv=?,youthrace_d3_juv=?,youthrace_date=?,youthrace_location=? WHERE youthrace_race_id=?");
	$stmt->execute([
		$_POST["youthnamepdf"],
		$_POST["youthranking"],
		$_POST["youths1ben"],
		$_POST["youthd1ben"],
		$_POST["youthd2ben"],
		$_POST["youthd3ben"],
		$_POST["youths1inf"],
		$_POST["youthd1inf"],
		$_POST["youthd2inf"],
		$_POST["youthd3inf"],
		$_POST["youths1ini"],
		$_POST["youthd1ini"],
		$_POST["youthd2ini"],
		$_POST["youthd3ini"],
		$_POST["youths1juv"],
		$_POST["youthd1juv"],
		$_POST["youthd2juv"],
		$_POST["youthd3juv"],
		$_POST["youthdate"],
		$_POST["youthlocation"],
		$_POST["youthid"]
	]);
	$stmt = $db->prepare("UPDATE races SET race_namepdf = :namepdf, race_ranking = :ranking, race_segment1 = :segment1, race_date = :date, race_location = :location WHERE race_id = :id");
    $result = $stmt->execute(array(
        ':ranking'	=>	$_POST["youthranking"],
        ':namepdf' => $_POST["youthnamepdf"],
		':segment1'		=>	$_POST["youths1ben"],
        ':date'		=>	$_POST["youthdate"],
        ':location'		=>	$_POST["youthlocation"],
        ':id'	=>	$_POST["youthid"]
		));
?>