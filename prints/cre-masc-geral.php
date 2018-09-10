<?php
  include_once($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  require('fpdf.php');

  class PDF extends FPDF {
    // Page header
    function Header() {
      include_once($_SERVER['DOCUMENT_ROOT']."/functions/PDFs/pdfHeader.php");
      pdfHeader_V($this, $_GET['race_id'], 'M', 'Classificações Geral Masculinos');
    }
    // Page footer
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

  // Instanciation of inherited class
  $pdf = new PDF();
  $pdf->AliasNbPages();
  $pdf->AddPage('P','A4');
  $pdf->SetFont('Times','',9);
  $pdf->SetTextColor(0);
  $pdf->SetFillColor(244,244,244);
  $pos = 1;
  //TEMPOS DOS GUNS
  $race_id = $_GET['race_id'];
  $querygun = $db->prepare("SELECT race_type, race_gun_m FROM races WHERE race_id = ? LIMIT 1");
  $querygun->execute([$race_id]);
  $rowrace = $querygun->fetch();
  //**** TEMPOS DE QUEM TERMINOU ****//
  $query = $db->prepare("SELECT * FROM athletes WHERE athlete_started >= 5 AND athlete_race_id = ? AND athlete_sex = 'M' ORDER BY athlete_t5 ASC");
  $query->execute([$race_id]);
  $rows = $query->fetchAll();
  $queryArriveOrder = $db->prepare('UPDATE athletes SET athlete_arrive_order=? WHERE athlete_sex=?');
  $queryArriveOrder->execute([0, 'M']);
  foreach ($rows as $row) {
    $racegun = $row['athlete_t0'];
    $finishTime = gmdate('H:i:s', strtotime($row['athlete_t5'])-strtotime($racegun));
    // VER QUANTOS JA CHEGARAM AH META DA MESMA EQUIPA
    $order = $db->prepare('SELECT athlete_arrive_order FROM athletes WHERE athlete_team_id=? AND athlete_xtras=? AND athlete_sex=?  AND athlete_race_id=? LIMIT 1');
    $order->execute([$row['athlete_team_id'], $row['athlete_xtras'], 'M', $race_id]);
    $rowOrder = $order->fetch();
    $arriveOrder = $rowOrder['athlete_arrive_order'] + 1;
    if ($arriveOrder === 3) {
      // ATUALIZA TOTALTIME, EH ESTE O TEMPO QUE CONTA
      $totalTime = $finishTime;
    } else {
      $totalTime = '-';
    }
    $query = $db->prepare("UPDATE athletes SET athlete_finishtime=?, athlete_totaltime=? WHERE athlete_chip=?");
    $query->execute([$finishTime, $totalTime, $row['athlete_chip']]);
    $query = $db->prepare("UPDATE athletes SET athlete_arrive_order=? WHERE athlete_team_id=? AND athlete_xtras=? AND athlete_sex=?");
    $query->execute([$arriveOrder, $row['athlete_team_id'], $row['athlete_xtras'], 'M']);
  }
  // LE APENAS AS EQUIPAS QUE OS 3 PRIMEIROS CHEGARAM A META
  $query = $db->prepare('SELECT athlete_team_id, athlete_xtras FROM athletes WHERE athlete_started>=5 AND athlete_race_id=? AND athlete_sex=? AND athlete_totaltime<>? AND athlete_race_id=? ORDER BY athlete_totaltime ASC');
  $query->execute([$race_id, 'M', '-', $race_id]);
  $teams = $query->fetchAll();
  $pos = 1;
  foreach ($teams as $team) {
    $posTeam = 1;
    $query = $db->prepare('SELECT * FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athlete_team_id=? AND athlete_xtras=? AND athlete_sex=? AND athlete_started=5 AND athlete_race_id=? ORDER BY athlete_finishtime ASC');
    $query->execute([$team['athlete_team_id'], $team['athlete_xtras'], 'M', $race_id]);
    $athletes = $query->fetchAll();
    foreach ($athletes as $athlete) {
      if ($posTeam === 1) $pdf->Cell(6,5,$pos,'L, T, R',0,'C',0);
      else $pdf->Cell(6,5,'','L, R',0,'C',0);
      $pdf->Cell(12,5,$athlete['athlete_license'],1,0,'C',0);
      $pdf->Cell(10,5,$athlete['athlete_bib'],1,0,'C',0);
      $pdf->Cell(42,5,utf8_decode($athlete['athlete_name']),1,0,'L',0);
      $pdf->Cell(10,5,$athlete['athlete_category'],1,0,'C',0);
      $pdf->Cell(56,5,utf8_decode($athlete['team_name']),1,0,'L',0);
      $pdf->Cell(18,5,$athlete['athlete_finishtime'],1,0,'C',0);
      if ($posTeam === 3) {
          $pdf->Cell(18,5,$athlete['athlete_totaltime'],'R',0,'C',0);
        } elseif ($posTeam === 1) $pdf->Cell(18,5,'','T, R',0,'C',0);
        else $pdf->Cell(18,5,'','R',0,'C',0);
        if ($pos === 1) {
          if ($posTeam === 3) {
            $pdf->Cell(18,5,"-",'R',1,'C',0);
            $time_winner = $athlete['athlete_totaltime'];
          } else $pdf->Cell(18,5,'','R',1,'C',0);
        } else {
          if ($posTeam === 3) {
            $time = strtotime($athlete['athlete_totaltime']) - strtotime($time_winner);
            $pdf->Cell(18,5,gmdate('H:i:s', $time),'R',1,'C',0);
          } elseif ($posTeam === 1) $pdf->Cell(18,5,'','T, R',1,'C',0);
          else $pdf->Cell(18,5,'','R',1,'C',0);
        }
      $posTeam++;
    }
    // **** PENALIZAÇÕES, time = DSQ / DNF / DNS
    $penalty = array("DSQ", "DNF", "DNS", "LAP");
    for($i=0;$i<count($penalty);$i++) {
      $query = $db->prepare('SELECT * FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athlete_team_id=? AND athlete_xtras=? AND athlete_sex=? AND athlete_finishtime=? AND athlete_race_id=?');
      $query->execute([$team['athlete_team_id'], $team['athlete_xtras'], 'M', $penalty[$i], $race_id]);
      $athletes = $query->fetchAll();
      foreach ($athletes as $athlete) {
        if ($posTeam === 1) $pdf->Cell(6,5,'','L, T, R',0,'C',0);
        else $pdf->Cell(6,5,'','L, R',0,'C',0);
        $pdf->Cell(12,5,$athlete['athlete_license'],1,0,'C',0);
        $pdf->Cell(10,5,$athlete['athlete_bib'],1,0,'C',0);
        $pdf->Cell(42,5,utf8_decode($athlete['athlete_name']),1,0,'L',0);
        $pdf->Cell(10,5,$athlete['athlete_category'],1,0,'C',0);
        $pdf->Cell(56,5,utf8_decode($athlete['team_name']),1,0,'L',0);
        $pdf->Cell(18,5,$athlete['athlete_finishtime'],1,0,'C',0);
        if ($posTeam === 1) {
          $pdf->Cell(18,5,'','T, R',0,'C',0);
          $pdf->Cell(18,5,'','R',1,'C',0);
        } else {
          $pdf->Cell(18,5,'','L, R',0,'C',0);
          $pdf->Cell(18,5,'','R',1,'C',0);
        }
      }
    }
    $pdf->Cell(190,5,'','T',0,'C',0);
    $pdf->Ln(1);
    $pos++;
  }
  // TODAS AS EQUIPAS QUE NAO COMPLETARAM A PROVA
  $query = $db->prepare('SELECT DISTINCT athlete_team_id, athlete_xtras FROM athletes WHERE athlete_race_id=? AND athlete_sex=? AND athlete_arrive_order<? ORDER BY athlete_started DESC, athlete_finishtime ASC');
  $query->execute([$race_id, 'M', 3]);
  $teams = $query->fetchAll();
  foreach ($teams as $team) {
    $query = $db->prepare('SELECT * FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athlete_team_id=? AND athlete_xtras=? AND athlete_sex=? ORDER BY athlete_started DESC, athlete_finishtime ASC');
    $query->execute([$team['athlete_team_id'], $team['athlete_xtras'], 'M']);
    $athletes = $query->fetchAll();
    foreach ($athletes as $athlete) {
      $pdf->Cell(6,5,'','R',0,'C',0);
      $pdf->Cell(12,5,$athlete['athlete_license'],1,0,'C',0);
      $pdf->Cell(10,5,$athlete['athlete_bib'],1,0,'C',0);
      $pdf->Cell(42,5,utf8_decode($athlete['athlete_name']),1,0,'L',0);
      $pdf->Cell(10,5,$athlete['athlete_category'],1,0,'C',0);
      $pdf->Cell(56,5,utf8_decode($athlete['team_name']),1,0,'L',0);
      $pdf->Cell(18,5,$athlete['athlete_finishtime'],1,1,'C',0);
    }
    $pdf->Ln(1);
  }
  $pdf->Output();
?>