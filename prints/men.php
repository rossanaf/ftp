<?php
  include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  require('fpdf.php');
  class PDF extends FPDF {
    // Page header
    function Header() {
      include_once($_SERVER['DOCUMENT_ROOT']."/functions/PDFs/pdfHeader.php");
      pdfHeader_V($this, $_GET['race_id'], 'M', 'Results MEN');
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
  $pdf = new PDF('P','mm','A4');
  $pdf->AliasNbPages();
  $pdf->AddPage('P','A4');
  $pdf->SetFont('Times','',10);
  $pdf->SetTextColor(0);
  $pdf->SetFillColor(244,244,244);
  $pos = 1;
  $fill = false;
  //TEMPOS DOS GUNS
  $race_id = $_GET['race_id'];
  $querygun = $db->prepare("SELECT race_gun_m FROM races WHERE race_id = ? LIMIT 1");
  $querygun->execute([$race_id]);
  $rowrace = $querygun->fetch();
  $stmtFinishers = $db->prepare('SELECT live_bib,team_country,live_t1,live_t2,live_t3,live_t4,live_t5, live_finishtime,live_firstname,live_lastname FROM live LEFT JOIN teams ON live_team_id=team_id WHERE live_started=5 AND live_race=? AND live_sex=? ORDER BY live_finishtime ASC');
  $stmtFinishers->execute([$race_id,'M']);
  $finishers = $stmtFinishers->fetchAll();
  foreach ($finishers as $finisher) {
    $pdf->SetFont('Times','',9);
    $pdf->SetX(10);
    $bib = $finisher['live_bib'];
    if ($pos == 1) $timeFirst = $finisher['live_finishtime'];
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(6,6,$pos,1,0,'C',$fill);
    $pdf->SetFont('Times','',9);
    $pdf->Cell(42,6,utf8_decode($finisher['live_firstname'].' '.$finisher['live_lastname']),1,0,'L',$fill);
    $pdf->Cell(10,6,utf8_decode($finisher['team_country']),1,0,'C',$fill);
    $pdf->Cell(6,6,$bib,1,0,'C',$fill);
    if($finisher['live_t1'] === 'time') 
      $t1 = '00:00:00';
    else $t1 = $finisher['live_t1'];
    $pdf->Cell(18,6,$t1,1,0,'C',$fill);
    if($finisher['live_t2'] === 'time') 
      $t2 = '00:00:00';
    else $t2 = $finisher['live_t2'];
    $pdf->Cell(18,6,$t2,1,0,'C',$fill);
    if($finisher['live_t3'] === 'time') 
      $t3 = '00:00:00';
    else $t3 = $finisher['live_t3'];
    $pdf->Cell(18,6,$t3,1,0,'C',$fill);
    if($finisher['live_t4'] === 'time') 
      $t4 = '00:00:00';
    else $t4 = $finisher['live_t4'];
    $pdf->Cell(18,6,$t4,1,0,'C',$fill);
    if($finisher['live_t5'] === 'time') 
      $t5 = '00:00:00';
    else $t5 = $finisher['live_t5'];
    $pdf->Cell(18,6,$t5,1,0,'C',$fill);
    if($finisher['live_finishtime'] === 'time') 
      $finishTime = '00:00:00';
    else $finishTime = $finisher['live_finishtime'];
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(18,6,$finishTime,1,0,'C',$fill);
    $pdf->SetFont('Times','',9);
    $timeDiff = strtotime($finisher['live_finishtime'])-strtotime($timeFirst);
    $pdf->Cell(18,6,date('H:i:s',$timeDiff),1,1,'C',$fill);
    $pos++;
    $fill = !$fill;
  }
  // **** PENALIZAÇÕES, time = DSQ / DNF / DNS / LAP
  $penalty = array('LAP','DSQ','DNF','DNS');
  for($i=0;$i<count($penalty);$i++) {
    $stmtnNonFinishers = $db->prepare('SELECT live_bib,team_country,live_t1,live_t2,live_t3,live_t4,live_t5, live_finishtime,live_firstname,live_lastname FROM live LEFT JOIN teams ON live_team_id=team_id WHERE live_race=? AND live_sex=? AND live_finistime=? ORDER BY live_started DESC, live_finishtime ASC');
    $stmtNonFinishers->execute([$race_id,'M',$penalty[$i]]);
    $nonFinishers = $stmtNonFinishers->fetchAll();
    foreach ($nonFinishers as $nonFinisher) {
      echo "aqui";
    }
  }
  $pdf->Output();
?>