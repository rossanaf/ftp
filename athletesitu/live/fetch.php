<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	$stmt = $db->prepare("SELECT Chip, ChipTime, Location FROM times ORDER BY Millisecs DESC LIMIT 5");
	$stmt->execute();
    $times = $stmt->fetchAll();
    $data = array();
	$filtered_rows = 0;
    foreach ($times as $time) 
    {
    	$stmt_live = $db->prepare("SELECT live_firstname, live_lastname, live_bib, live_race, live_category FROM live WHERE live_chip = ?");
    	$stmt_live->execute([$time['Chip']]);
    	$live = $stmt_live->fetch();
		$stmt_gunrace = $db->prepare("SELECT race_gun FROM races WHERE race_id = ?");
		$stmt_gunrace->execute([$live['live_race']]);
		$gunrace = $stmt_gunrace->fetch();
		list($date, $timing) = explode (" ", $time['ChipTime']);
		$race_gun = gmdate('H:i:s', (strtotime($gunrace['race_gun']) + strtotime('01:00:00')));
        $timing = gmdate('H:i:s', (strtotime($timing) - strtotime($gunrace['race_gun'])));
        // $timing = gmdate('H:i:s', (strtotime($timing) - strtotime('01:00:00')));
        $flag="<img src='/images/flags/".$live['live_category'].".png' width='18px'>";           
		$sub_array = array();
		$sub_array[] = $live["live_bib"];
		$sub_array[] = $live["live_firstname"].' '.$live['live_lastname'];
		$sub_array[] = $flag.' '.$live["live_category"];
		if ($time["Location"] == 'TimeT1')
			$sub_array[] = 'Swim';
		elseif ($time["Location"] == 'TimeT2')
			$sub_array[] = 'T1';
		elseif ($time["Location"] == 'TimeT3')
			$sub_array[] = 'Bike';
		elseif ($time["Location"] == 'TimeT4')
			$sub_array[] = 'T2';
		elseif ($time["Location"] == 'TimeT5')
			$sub_array[] = 'Run';
		$sub_array[] = $timing;
		$data[] = $sub_array;
	}
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"data"				=>	$data
	);
	echo json_encode($output);
?>