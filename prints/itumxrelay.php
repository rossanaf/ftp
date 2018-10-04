<?php
  include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  require('fpdf.php');
  class PDF extends FPDF {
    // Page header
    function Header() {
      include_once($_SERVER['DOCUMENT_ROOT']."/functions/PDFs/pdfHeader.php");
      pdfHeaderItuMxRelay($this, $_GET['race_id']);
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
  $stmtFinishers = $db->prepare('SELECT live_bib, team_name, team_country, live_t0 FROM live LEFT JOIN teams ON live_team_id=team_id WHERE live_started=5 AND live_license=4 AND live_race=? ORDER BY live_t0 ASC');
  $stmtFinishers->execute([$race_id]);
  $finishers = $stmtFinishers->fetchAll();
  foreach ($finishers as $finisher) {
    $pdf->SetFont('Times','',10);
    $bib = $finisher['live_bib'];
    if ($pos == 1) $timeFirst = $finisher['live_t0'];
    $pdf->SetX(10);
    $pdf->Cell(8,5,$pos,1,0,'C',$fill);
    $pdf->Cell(46,5,utf8_decode($finisher['team_name']),1,0,'C',$fill);
    $pdf->Cell(14,5,utf8_decode($finisher['team_country']),1,0,'C',$fill);
    $pdf->Cell(6,5,$bib,1,0,'C',$fill);
    $query = $db->prepare("SELECT live_finishtime FROM live WHERE live_bib=? AND live_race=? ORDER BY live_license ASC");
    $query->execute([$bib, $race_id]);
    $rows = $query->fetchAll();
    foreach ($rows as $row) {
      if($row['live_finishtime'] === 'time') $finishTime = '00:00:00';
      else $finishTime = $row['live_finishtime'];
      $pdf->Cell(20,5,$finishTime,1,0,'C',$fill);
    }
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(20,5,date('H:i:s',strtotime($finisher['live_t0'])),1,0,'C',$fill);
    $timeDiff=strtotime($finisher['live_t0'])-strtotime($timeFirst);
    $pdf->SetFont('Times','',10);
    $pdf->Cell(20,5,gmdate('H:i:s',$timeDiff),1,0,'C',$fill);
    $pos++;
    $pdf->Ln();
    $pdf->SetFont('Times','',9);
    // IMPRIME ATLETAS DE CADA EQUIPA & TEMPOS
    $stmtAthletes = $db->prepare('SELECT live_t1, live_t2, live_t3, live_t4, live_t5, live_finishtime, live_license, live_firstname, live_lastname FROM live WHERE live_bib=? AND live_race=?');
    $stmtAthletes->execute([$finisher['live_bib'], $race_id]);
    $athletes = $stmtAthletes->fetchAll();
    $pdf->SetX(16);
    $header = array('Athlete',' ','Swim', 'T1', 'Bike','T2','Run','Time');
    $w = array(46, 4, 16, 16, 16, 16, 16, 16, 16); // menos 2+2+4+10+2 = 20
    for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
    $pdf->Ln();
    foreach ($athletes as $athlete) {
      $pdf->SetX(16);
      $pdf->Cell(46,5,utf8_decode($athlete['live_firstname'].' '.$athlete['live_lastname']),1,0,'L',$fill);
      $pdf->Cell(4,5,$athlete['live_license'],1,0,'L',$fill);
      if($athlete['live_t1'] === 'time') 
        $t1 = '00:00:00';
      else $t1 = $athlete['live_t1'];
      $pdf->Cell(16,5,$t1,1,0,'C',$fill);
      if($athlete['live_t2'] === 'time') 
        $t2 = '00:00:00';
      else $t2 = $athlete['live_t2'];
      $pdf->Cell(16,5,$t2,1,0,'C',$fill);
      if($athlete['live_t3'] === 'time') 
        $t3 = '00:00:00';
      else $t3 = $athlete['live_t3'];
      $pdf->Cell(16,5,$t3,1,0,'C',$fill);
      if($athlete['live_t4'] === 'time') 
        $t4 = '00:00:00';
      else $t4 = $athlete['live_t4'];
      $pdf->Cell(16,5,$t4,1,0,'C',$fill);
      if($athlete['live_t5'] === 'time') 
        $t5 = '00:00:00';
      else $t5 = $athlete['live_t5'];
      $pdf->Cell(16,5,$t5,1,0,'C',$fill);
      if($athlete['live_finishtime'] === 'time') 
        $finishTime = '00:00:00';
      else $finishTime = $athlete['live_finishtime'];
      $pdf->SetFont('Times','B',8);
      $pdf->Cell(16,5,$finishTime,1,1,'C',$fill);
      $pdf->SetFont('Times','',9);
    }
    $pdf->Ln(1);
  }
  // **** PENALIZAÇÕES, time = DSQ / DNF / DNS / LAP
  $penalty = array('LAP','DSQ','DNF','DNS');
  for($i=0;$i<4;$i++) {
    $stmtFinishers = $db->prepare('SELECT live_bib, team_name, team_country, live_t0 FROM live LEFT JOIN teams ON live_team_id=team_id WHERE live_t0=? AND live_license=4 AND live_race=? ORDER BY live_t0, live_started ASC');
    $stmtFinishers->execute([$penalty[$i], $race_id]);
    $finishers = $stmtFinishers->fetchAll();
    foreach ($finishers as $finisher) {
      $pdf->SetFont('Times','',10);
      $bib = $finisher['live_bib'];
      $pdf->SetX(10);
      $pdf->Cell(8,5,$finisher['live_t0'],1,0,'C',$fill);
      $pdf->Cell(46,5,utf8_decode($finisher['team_name']),1,0,'C',$fill);
      $pdf->Cell(14,5,utf8_decode($finisher['team_country']),1,0,'C',$fill);
      $pdf->Cell(6,5,$bib,1,0,'C',$fill);
      $query = $db->prepare("SELECT live_finishtime FROM live WHERE live_bib=? AND live_race=? ORDER BY live_license ASC");
      $query->execute([$bib, $race_id]);
      $rows = $query->fetchAll();
      foreach ($rows as $row) {
        if($row['live_finishtime'] === 'time') $finishTime = '00:00:00';
        else $finishTime = $row['live_finishtime'];
        $pdf->Cell(20,5,$finishTime,1,0,'C',$fill);
      }
      $pdf->SetFont('Times','B',9);
      $pdf->Cell(20,5,$finisher['live_t0'],1,0,'C',$fill);
      $pdf->SetFont('Times','',9);
      // $timeDiff=strtotime($finisher['live_t0'])-strtotime($timeFirst);
      // $pdf->Cell(20,5,gmdate('H:i:s',$timeDiff),1,0,'C',$fill);
      $pdf->Ln();
      $pdf->SetFont('Times','',9);
      // IMPRIME ATLETAS DE CADA EQUIPA & TEMPOS
      $stmtAthletes = $db->prepare('SELECT live_t1, live_t2, live_t3, live_t4, live_t5, live_finishtime, live_license, live_firstname, live_lastname FROM live WHERE live_bib=? AND live_race=?');
      $stmtAthletes->execute([$finisher['live_bib'], $race_id]);
      $athletes = $stmtAthletes->fetchAll();
      $pdf->SetX(16);
      $header = array('Athlete',' ','Swim', 'T1', 'Bike','T2','Run','Time');
      $w = array(46, 4, 16, 16, 16, 16, 16, 16, 16); // menos 2+2+4+10+2 = 20
      for($j=0;$j<count($header);$j++)
        $pdf->Cell($w[$j],5,utf8_decode($header[$j]),1,0,'C',true);
      $pdf->Ln();
      foreach ($athletes as $athlete) {
        $pdf->SetX(16);
        $pdf->Cell(46,5,utf8_decode($athlete['live_firstname'].' '.$athlete['live_lastname']),1,0,'L',$fill);
        $pdf->Cell(4,5,$athlete['live_license'],1,0,'L',$fill);
        if($athlete['live_t1'] === 'time') 
          $t1 = '00:00:00';
        else $t1 = $athlete['live_t1'];
        $pdf->Cell(16,5,$t1,1,0,'C',$fill);
        if($athlete['live_t2'] === 'time') 
          $t2 = '00:00:00';
        else $t2 = $athlete['live_t2'];
        $pdf->Cell(16,5,$t2,1,0,'C',$fill);
        if($athlete['live_t3'] === 'time') 
          $t3 = '00:00:00';
        else $t3 = $athlete['live_t3'];
        $pdf->Cell(16,5,$t3,1,0,'C',$fill);
        if($athlete['live_t4'] === 'time') 
          $t4 = '00:00:00';
        else $t4 = $athlete['live_t4'];
        $pdf->Cell(16,5,$t4,1,0,'C',$fill);
        if($athlete['live_t5'] === 'time') 
          $t5 = '00:00:00';
        else $t5 = $athlete['live_t5'];
        $pdf->Cell(16,5,$t5,1,0,'C',$fill);
        if($athlete['live_finishtime'] === 'time') 
          $finishTime = '00:00:00';
        else $finishTime = $athlete['live_finishtime'];
        $pdf->SetFont('Times','B',8);
        $pdf->Cell(16,5,$finishTime,1,0,'C',$fill);
        $pdf->SetFont('Times','',9);
        $pdf->Ln();
      }
    }
    $pdf->Ln(1);
  }
  $pdf->Output();
?>