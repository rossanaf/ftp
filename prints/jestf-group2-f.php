<?php
  //load the database configuration file
  include($_SERVER['DOCUMENT_ROOT'].'/includes/db.php');
  require('fpdf.php');

  class PDF extends FPDF {
    // Page header
    function Header() {
      include_once($_SERVER['DOCUMENT_ROOT']."/functions/PDFs/pdfHeader.php");
      pdfHeader_V($this, $_GET['race_id'], 'F', 'Classificações Iniciados / Juvenis Femininos');
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
      $this->Cell(0,10,utf8_decode('© Federação de Triatlo de Portugal'),0,0,'L');
      $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'R');
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
  $querygun = $db->prepare("SELECT race_gun_f FROM races WHERE race_id=? LIMIT 1");
  $querygun->execute([$race_id]);
  $rowGun = $querygun->fetch();
  
  $query = $db->prepare('SELECT athlete_team_id, athlete_bib FROM athletes WHERE athlete_started>=4 AND athlete_race_id=? AND athlete_sex=? ORDER BY athlete_finishtime ASC');
  $query->execute([$race_id, 'F']);
  $rows = $query->fetchAll();
  foreach ($rows as $row) {
    $posat = substr($row['athlete_bib'], -1);
    $bib = rtrim($row['athlete_bib'],$posat);
    $stmt = $db->prepare("SELECT * FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athlete_bib LIKE ? ORDER BY athlete_bib ASC");
    $stmt->execute([$bib.'_']);
    $team = $stmt->fetchAll();
    foreach ($team as $relay) {
      $team[$pos] = $relay['athlete_team_id'];
      $relayPos = substr($relay['athlete_bib'], -1);
      if ($relayPos === 'A') $pdf->Cell(8,5,'','L, T, R',0,'C',0);
      elseif ($relayPos === 'B') $pdf->Cell(8,5,$pos,'L, R',0,'C',0);
      elseif ($relayPos === 'C') $pdf->Cell(8,5,'','L, R, B',0,'C',0);
      $pdf->Cell(12,5,$relay['athlete_license'],1,0,'C',0);
      $pdf->Cell(10,5,$relay['athlete_bib'],1,0,'C',0);
      $pdf->Cell(40,5,utf8_decode($relay['athlete_name']),1,0,'L',0);
      $pdf->Cell(10,5,$relay['athlete_category'],1,0,'C',0);
      $pdf->Cell(56,5,utf8_decode($relay['team_name']),1,0,'L',0);
      $pdf->Cell(18,5,$relay['athlete_totaltime'],1,0,'C',0);
      if ($relayPos === 'A') $pdf->Cell(18,5,'','L, T, R',0,'C',0);
      if ($relayPos === 'B') $pdf->Cell(18,5,'','L, R',0,'C',0);
      elseif ($relayPos === 'C') $pdf->Cell(18,5,$relay['athlete_finishtime'],'L,R,B',0,'C',0);
      if ($relayPos === 'A') $pdf->Cell(18,5,'','L, T, R',1,'C',0);
      if ($relayPos === 'B') $pdf->Cell(18,5,'','L, R',1,'C',0);
      elseif ($relayPos === 'C') {
        if ($pos === 1) {
          $relay_first = $relay['athlete_finishtime'];
          $diff = '-';
        } else {
          $diff = gmdate('H:i:s', strtotime($relay['athlete_finishtime'])-strtotime($relay_first));
        }
        $pdf->Cell(18,5,$diff,'L,R,B',1,'C',0);
        $pdf->Ln(1);
        $pos++;        
      }
    }
  }
  //**** PENALIZAÇÕES, time = DSQ / DNF / DNS
  $penalty = array("DSQ", "DNF", "DNS", "LAP");
  for($i=0;$i<count($penalty);$i++) {
    $query = $db->prepare("SELECT athlete_team_id, athlete_bib FROM athletes WHERE athlete_finishtime=? AND athlete_race_id=? AND athlete_sex='F' AND athlete_bib LIKE '%C' ORDER BY athlete_started DESC");
    $query->execute([$penalty[$i], $race_id]);
    $rows = $query->fetchAll();
    foreach ($rows as $row) {
      $posat = substr($row['athlete_bib'], -1);
      $bib = rtrim($row['athlete_bib'],$posat);
      $stmt = $db->prepare("SELECT * FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athlete_bib LIKE ? ORDER BY athlete_bib ASC");
      $stmt->execute([$bib.'_']);
      $team = $stmt->fetchAll();
      foreach ($team as $relay) {
        $relayPos = substr($relay['athlete_bib'], -1);
        $pdf->Cell(8,5,$relay['athlete_finishtime'],1,0,'C',0);
        $pdf->Cell(12,5,$relay['athlete_license'],1,0,'C',0);
        $pdf->Cell(10,5,$relay['athlete_bib'],1,0,'C',0);
        $pdf->Cell(40,5,utf8_decode($relay['athlete_name']),1,0,'L',0);
        $pdf->Cell(10,5,$relay['athlete_category'],1,0,'C',0);
        $pdf->Cell(56,5,utf8_decode($relay['team_name']),1,0,'L',0);
        $pdf->Cell(18,5,$relay['athlete_totaltime'],1,0,'C',0);
        $pdf->Cell(18,5,$relay['athlete_finishtime'],1,1,'C',0);
        if ($relayPos === 'C') $pdf->Ln(1);
      }
    }
  }
  $pdf->Output();
?>