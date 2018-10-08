<?php
  include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  require('fpdf.php');

  class PDF extends FPDF {
    // Page header
    function Header() {
      include_once($_SERVER['DOCUMENT_ROOT']."/functions/PDFs/pdfHeader.php");
      pdfHeader_H($this, $_GET['race_id'], 'M', 'Classificações Absolutos Masculinos', 3);
    }
    // Page footer
    function Footer()
    {
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
  $pdf->AddPage('L','A4');
  $pdf->SetFont('Times','',8);
  $pdf->SetTextColor(0);
  $pdf->SetFillColor(244,244,244);
  $pos = 1;
  $fill = false;
  //TEMPOS DOS GUNS
  $race_id = $_GET['race_id'];
  $querygun = $db->prepare("SELECT race_type, race_gun_m, race_relay FROM races WHERE race_id = ? LIMIT 1");
  $querygun->execute([$race_id]);
  $rowrace = $querygun->fetch();
  //**** TEMPOS DE QUEM TERMINOU ****//
  $query = $db->prepare("SELECT athlete_totaltime, athlete_finishtime, athlete_chip, athlete_t0 FROM athletes WHERE athletes.athlete_started >= 5 AND athletes.athlete_race_id = ? AND athletes.athlete_sex = 'M'");
  $query->execute([$race_id]);
  $rows = $query->fetchAll();
  foreach ($rows as $row) {
    if ($rowrace['race_type'] === 'crind' || $rowrace['race_relay'] === 'X') $racegun = $row['athlete_t0'];
    else $racegun = $rowrace['race_gun_m'];
    $athlete_totaltime = gmdate('H:i:s', strtotime($row['athlete_finishtime'])-strtotime($racegun));
    $query = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_chip = ?");
    $query->execute([$athlete_totaltime, $row['athlete_chip']]);    
  }
  // todos os que terminaram a prova, athlete_started = 5
  $query = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= '5' AND athletes.athlete_race_id = ? AND athletes.athlete_sex = 'M' ORDER BY athlete_totaltime ASC");
  $query->execute([$race_id]);
  $rows = $query->fetchAll();
  foreach ($rows as $row) {
    $pdf->SetX(16);
    if ($pos == 1) $timeFirst = $row['athlete_totaltime'];
    $pdf->Cell(8,4,$pos,1,0,'C',$fill);
    $pdf->Cell(14,4,$row['athlete_license'],1,0,'C',$fill);
    $pdf->Cell(12,4,$row['athlete_bib'],1,0,'C',$fill);
    $pdf->Cell(50,4,utf8_decode($row['athlete_name']),1,0,'L',$fill);
    $pdf->Cell(10,4,$row['athlete_category'],1,0,'C',$fill);
    $pdf->Cell(66,4,utf8_decode($row['team_name']),1,0,'L',$fill);
    if ($rowrace['race_type'] == 'crind' || $rowrace['race_relay'] === 'X') $racegun = $row['athlete_t0'];
    else $racegun = $rowrace['race_gun_m'];
    if($row['athlete_t1']=="-") $pdf->Cell(20,4,"-",1,0,'C',$fill);
    else $pdf->Cell(20,4,utf8_decode(gmdate('H:i:s',strtotime($row['athlete_t1']) - strtotime($racegun))),1,0,'C',$fill);
    if(($row['athlete_t3']=="-") || ($row['athlete_t1']=="-")) $pdf->Cell(20,4,"-",1,0,'C',$fill);
    else $pdf->Cell(20,4,utf8_decode(gmdate('H:i:s',strtotime($row['athlete_t3']) - strtotime($row['athlete_t1']))),1,0,'C',$fill);
    if(($row['athlete_t5']=="-") || ($row['athlete_t3']=="-")) $pdf->Cell(20,4,"-",1,0,'C',$fill);
    else $pdf->Cell(20,4,utf8_decode(gmdate('H:i:s',strtotime($row['athlete_t5']) - strtotime($row['athlete_t3']))),1,0,'C',$fill);
    $time=strtotime($row['athlete_finishtime'])-strtotime($racegun); 
    $pdf->Cell(20,4,gmdate('H:i:s', $time),1,0,'C',$fill);
    $timeDiff=strtotime($row['athlete_totaltime'])-strtotime($timeFirst);
    $pdf->Cell(20,4,gmdate('H:i:s',$timeDiff),1,1,'C',$fill);
    $fill = !$fill;
    $pos++;
  }
  // **** PENALIZAÇÕES, time = DSQ / DNF / DNS
  $penalty = array("DSQ", "DNF", "DNS", "LAP");
  for($i=0;$i<count($penalty);$i++) {
    $query = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_race_id = ? AND athletes.athlete_finishtime = ? AND athletes.athlete_sex = 'M' ORDER BY athletes.athlete_started DESC");
    $query->execute([$race_id, $penalty[$i]]);
    $rows = $query->fetchAll();
    foreach ($rows as $row) {           
      $pdf->SetX(16);
      $pdf->Cell(8,4,$row['athlete_finishtime'],1,0,'C',$fill);
      $pdf->Cell(14,4,$row['athlete_license'],1,0,'C',$fill);
      $pdf->Cell(12,4,$row['athlete_bib'],1,0,'C',$fill);
      $pdf->Cell(50,4,utf8_decode($row['athlete_name']),1,0,'L',$fill);
      $pdf->Cell(10,4,$row['athlete_category'],1,0,'C',$fill);
      $pdf->Cell(66,4,utf8_decode($row['team_name']),1,0,'L',$fill);
      if ($rowrace['race_type'] == 'crind') $racegun = $row['athlete_t0'];
      else $racegun = $rowrace['race_gun_m'];
      if($row['athlete_t1']=="-") $pdf->Cell(20,4,"-",1,0,'C',$fill);
      else $pdf->Cell(20,4,utf8_decode(gmdate('H:i:s',strtotime($row['athlete_t1']) - strtotime($racegun))),1,0,'C',$fill);
      if(($row['athlete_t3']=="-") || ($row['athlete_t1']=="-")) $pdf->Cell(20,4,"-",1,0,'C',$fill);
      else $pdf->Cell(20,4,utf8_decode(gmdate('H:i:s',strtotime($row['athlete_t3']) - strtotime($row['athlete_t1']))),1,0,'C',$fill);
      if(($row['athlete_t5']=="-") || ($row['athlete_t3']=="-")) $pdf->Cell(20,4,"-",1,0,'C',$fill);
      else $pdf->Cell(20,4,utf8_decode(gmdate('H:i:s',strtotime($row['athlete_t5']) - strtotime($row['athlete_t3']))),1,0,'C',$fill);
      $pdf->Cell(20,4,$row['athlete_finishtime'],1,1,'C',$fill);
      $fill=!$fill;
      $pos++;
    }
  }
  $pdf->Output();
?>