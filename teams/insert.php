<?php
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
	
	if(isset($_POST["operation"]))
	{
		if($_POST["operation"] == "Add")
		{
			$stmt = $db->prepare("
				INSERT INTO teams (team_name) VALUES (:name)
			");
			$result = $stmt->execute(
				array(
					':name'		=>	$_POST["name"],
				)
			);
			if (!empty($result))
			{
				echo 'Novo Clube Inserido!';
			}
		}

		if($_POST["operation"] == "Edit")
		{
	        $stmt = $db->prepare(
				"UPDATE teams SET team_name = :name WHERE team_id = :id"
			);
	        $result = $stmt->execute(
				array(
	                ':name' => $_POST["name"],
					':id'	=>	$_POST["user_id"]
				)
			);
			if(!empty($result)){
				echo 'Clube atualizado!';
			}
		}
	}
?>