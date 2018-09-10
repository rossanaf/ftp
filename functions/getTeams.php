<?php
	function getTeams() {
    include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
    // VALIDAR TABELA FEDERADOS, SE ESTIVER VAZIA, FAZER TRUNCATE DE TEAMS
    $stmt = $db->prepare('SELECT ftpathlete_id FROM ftpathletes LIMIT 1');
    $stmt->execute();
    $stmt2 = $db->prepare('SELECT race_id FROM races LIMIT 1');
    $stmt2->execute();
    if (($stmt->rowCount() == 0) && ($stmt2->rowCount() == 0)) {
      $stmtTeam = $db->prepare('TRUNCATE teams');
      $stmtTeam->execute();
      $stmt = $db->prepare("INSERT INTO teams(team_id, team_name) VALUES (1, 'Não Federado'), (2, 'Individual'), (3, 'Estafeta')");
      $stmt->execute();
      $teams[1] = 'Não Federado';
      $teams[2] = 'Individual';
      $teams[3] = 'Estafeta';
    } else {
      // VALIDAR SE A TABELA TEAMS TEM DADOS
  		$stmt = $db->prepare('SELECT team_id, team_name FROM teams ORDER BY team_id ASC');
  		$stmt->execute();
  		if ($stmt->rowCount() > 0) {
  			// CARREGA PARA ARRAY CONTEUDO DA TABELA TEAMS
  			$rows = $stmt->fetchAll();
  			foreach ($rows as $row) {
  					$teams[$row['team_id']] = $row['team_name'];
  			}
  		} 
    }
    return(array_map('strtolower', $teams));
	}
?>