<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	include($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
    $query = "SELECT race_name, race_id FROM races ORDER BY race_id ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if (!$result) echo "<script type='text/javascript'>alert('\\nNão há provas abertas!\\n\\nOpções:\\n    1. Importar uma nova Startlist;\\n    2. Criar Provas.')</script>";
    
    foreach ($result as $row) 
    {
        echo '
        <div class="container" id="updatesContainer">
            <h4>Prova '.$row["race_name"].'</h4>
        	<form id="race_form" action="../functions/tables.php" method="post">
                <input type="submit" name="action" class="btn btn-primary action" value="Backup - '.$row["race_id"].'" />
        		<input type="submit" name="action" class="btn btn-info action" value="Restore - '.$row["race_id"].'" />
                <input type="submit" name="action" class="btn btn-danger" value="Eliminar - '.$row["race_id"].'" />
            </form>
        </div>
        ';
    }
?>