<?php
  include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  require('fpdf.php');

  class PDF extends FPDF {
    function Header() {
      include_once($_SERVER['DOCUMENT_ROOT']."/functions/PDFs/pdfHeader.php");
      pdfHeader_V($this, $_GET['race_id'], 'F', 'Classificações Equipas Femininas');
    }// Page footer
    function Footer() {
        $this->SetDrawColor(255,214,0);
        $this->Line(0,285,80,285);
        $this->SetDrawColor(0,110,38);
        $this->Line(80,285,150,285);
        $this->SetDrawColor(166,16,8);
        $this->Line(150,285,210,285);
        // Position at 1.0 cm from bottom
        $this->SetXY(10,-15);
        // Arial italic 8
        $this->SetFont('Times','',7);
        // Page number
        $this->Cell(0,10,utf8_decode("© Federação de Triatlo de Portugal"),0,0,'L');
        $this->Cell(0,10,utf8_decode("Página ").$this->PageNo().'/{nb}',0,0,'R');
    }
  }

  $clube = array();
  $db->query("TRUNCATE teamresults");
  //TEMPOS DOS GUNS
  $race_id = $_GET['race_id'];
  $querygun = $db->prepare("SELECT race_type, race_gun_m, race_relay FROM races WHERE race_id = ? LIMIT 1");
  $querygun->execute([$race_id]);
  $rowrace = $querygun->fetch();
  //**** TEMPOS DE QUEM TERMINOU ****//
  $query = $db->prepare("SELECT athlete_totaltime, athlete_finishtime, athlete_chip, athlete_t0 FROM athletes WHERE athletes.athlete_started >= 5 AND athletes.athlete_race_id = ? AND athletes.athlete_sex = 'F'");
    $query->execute([$race_id]);
    $rows = $query->fetchAll();
    foreach ($rows as $row) {
      if ($rowrace['race_type'] === 'crind' || $rowrace['race_relay'] === 'X') $racegun = $row['athlete_t0'];
    else $racegun = $rowrace['race_gun_m'];
    $athlete_totaltime = gmdate('H:i:s', strtotime($row['athlete_finishtime'])-strtotime($racegun));
    $query = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_chip = ?");
    $query->execute([$athlete_totaltime, $row['athlete_chip']]);
  }
  // **** BUSCA OS TRES PRIMEIROS DE CADA EQUIPA **** //
  $queryteams = $db->prepare("SELECT athlete_team_id, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athlete_started >= '5' AND athlete_race_id = ? AND athlete_sex = 'F' GROUP BY athlete_team_id HAVING COUNT(*) > 2");
  $queryteams->execute([$race_id]);
  $teams = $queryteams->fetchAll();
  foreach ($teams as $row_clubes) {
    if (strpos('X'.$row_clubes['team_name'],'EST ')!==false)
     $estrangeira = true; 
    else $estrangeira = false;
    if (($row_clubes['athlete_team_id'] != 1) && ($row_clubes['athlete_team_id'] != 2) && ($row_clubes['athlete_team_id'] != 3) && ($estrangeira === false)) {
      $querytimes = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id = teams.team_id WHERE athlete_team_id = ? AND athlete_started >= 5 AND athlete_race_id = ? AND athlete_sex = 'F' ORDER BY athlete_totaltime LIMIT 3");
      $querytimes->execute([$row_clubes['athlete_team_id'], $race_id]);
      $timestable = $querytimes->fetchAll();
      $i=1;
      foreach ($timestable as $row_tempos) {
        if ($rowrace['race_type'] == 'crind') $racegun = $row_tempos['athlete_t0'];
        else $racegun = $rowrace['race_gun_m']; 
        $tempo_individual = strtotime($row_tempos['athlete_finishtime'])-strtotime($racegun);
        if($i==1) $teamresult_teamtime = $tempo_individual;
        else $teamresult_teamtime = $tempo_individual + $teamresult_teamtime;
        $results = $db->prepare("INSERT INTO teamresults (teamresult_bib, teamresult_finishtime, teamresult_team,  teamresult_license, teamresult_name, teamresult_category, teamresult_validate, teamresult_teamtime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $results->execute([$row_tempos['athlete_bib'], gmdate('H:i:s',$tempo_individual), $row_tempos['team_name'], $row_tempos['athlete_license'], $row_tempos['athlete_name'], $row_tempos['athlete_category'], $i, gmdate('H:i:s',$teamresult_teamtime)]);
        $i++;
      }
    }
  }
  // **** ORDENAR DO PRIMEIRO PARA O SEGUNDO E MANDAR PARA PDF **** //
  $pdf = new PDF();
  $pdf->AliasNbPages();
  $pdf->AddPage('P','A4');
  // Equipas - 3 melhores
  $pdf->SetFont('Times','',9);
  $pdf->SetTextColor(0);
  $pdf->SetFillColor(244,244,244);
  $pos = 1;
  $fill = false;
  $query_tempos = $db->query("SELECT teamresult_team FROM teamresults WHERE teamresult_validate = '3' ORDER BY teamresult_teamtime");
  $tempos = $query_tempos->execute();
  foreach ($query_tempos as $row_tempos) {
    // Percorre apenas as equipas, ordem tempos totais da equipa
    $query_clubes = $db->prepare("SELECT * FROM teamresults WHERE teamresult_team = ? ORDER BY teamresult_validate");
    $query_clubes->execute([$row_tempos['teamresult_team']]);
    // Percorre toda a tabela
    foreach ($query_clubes as $row_clubes) {
      if ($row_clubes['teamresult_validate'] == 2) {
        $pdf->Cell(6,5,$pos,'L,R',0,'C',$fill);
      } elseif ($row_clubes['teamresult_validate'] == 3) {
        $pdf->Cell(6,5,'','L,R,B',0,'C',$fill);
      } elseif ($row_clubes['teamresult_validate'] == 1) {
        $pdf->Cell(6,5,'','L,T,R',0,'C',$fill);
      } 
      if($row_clubes['teamresult_validate'] == 3) {
        if($pos == 1) $first_time = $row_clubes['teamresult_teamtime'];
        $diff = strtotime($row_clubes['teamresult_teamtime'])-strtotime($first_time);
        $teamresult_teamtime = $row_clubes['teamresult_teamtime'];
      }
      $pdf->Cell(14,5,$row_clubes['teamresult_license'],1,0,'C',$fill);
      $pdf->Cell(12,5,$row_clubes['teamresult_bib'],1,0,'C',$fill);
      $pdf->Cell(40,5,utf8_decode($row_clubes['teamresult_name']),1,0,'L',$fill);
      $pdf->Cell(10,5,$row_clubes['teamresult_category'],1,0,'C',$fill);
      $pdf->Cell(52,5,utf8_decode($row_clubes['teamresult_team']),1,0,'L',$fill);
      $pdf->Cell(19,5,utf8_decode($row_clubes['teamresult_finishtime']),1,0,'C',$fill);
      if ($row_clubes['teamresult_validate'] == 2) {
        $pdf->Cell(19,5,'','L,R',0,'C',$fill);
        $pdf->Cell(18,5,'','L,R',1,'C',$fill);
      } elseif ($row_clubes['teamresult_validate'] == 3) {
        $pdf->Cell(19,5,$teamresult_teamtime,'L,R,B',0,'C',$fill);
        $pdf->Cell(18,5,gmdate('H:i:s', $diff),'L,R,B',1,'C',$fill);
      } elseif ($row_clubes['teamresult_validate'] == 1) {
        $pdf->Cell(19,5,'','L,T,R',0,'C',$fill);
        $pdf->Cell(18,5,'','L,T,R',1,'C',$fill);
      }
    }
    $fill=!$fill;
    $pos++;
    $pdf->Ln(0.4);
  }
  $pdf->Output();
?>