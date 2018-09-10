<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  include ($_SERVER['DOCUMENT_ROOT']."/functions/isTime.php");

	$gunbenf = isTime($_POST["gunbenf"]);
	$gunbenm = isTime($_POST["gunbenm"]);
	$guninff = isTime($_POST["guninff"]);
	$guninfm = isTime($_POST["guninfm"]);
	$guninif = isTime($_POST["guninif"]);
	$guninim = isTime($_POST["guninim"]);
	$gunjuvf = isTime($_POST["gunjuvf"]);
	$gunjuvm = isTime($_POST["gunjuvm"]);
  $stmt = $db->prepare("UPDATE gunshots SET gunshot_benf = :benf, gunshot_benm = :benm, gunshot_inff = :inff, gunshot_infm = :infm, gunshot_inif = :inif, gunshot_inim = :inim, gunshot_juvf = :juvf, gunshot_juvm = :juvm WHERE gunshot_race_id = :id"
	);
  $result = $stmt->execute(array(
    'benf' => $gunbenf,
    'benm' => $gunbenm,
    'inff' => $guninff,
    'infm' => $guninfm,
    'inif' => $guninif,
    'inim' => $guninim,
    'juvf' => $gunjuvf,
    'juvm' => $gunjuvm,
		':id'	=>	$_POST["gun_id"]
		));
	if(!empty($result)) echo 'Dados do atleta atualizados!';
?>