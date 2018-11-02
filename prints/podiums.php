<?php
//load the database configuration file
include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
require('fpdf.php');

class PDF extends FPDF{
  // Page header
  function Header(){
    //load the database configuration file
    include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
    // Logo
    $this->Image('../images/ftp_logo.png',10,5,54);
    // Times bold 15
    $this->SetFont('Times','B',14);
    // Move to the right
    $this->SetX(100);
    // Title
    $this->SetDrawColor(255,214,0);
    $this->Line(0,34,80,34);
    $this->SetDrawColor(0,110,38);
    $this->Line(80,34,150,34);
    $this->SetDrawColor(166,16,8);
    $this->Line(150,34,210,34);
    $this->SetFont('Times','',14);
    $this->Cell(190,8,utf8_decode("Pódios"),0,0,'C');
    $this->Ln(10);
    $this->SetDrawColor(0);
    $this->SetFillColor(87,87,85);
    $this->SetTextColor(255);
    $this->SetLineWidth(.1);
    $this->SetFont('Times','',9);
    // Header
    $this->Ln(20);
    $this->SetX(10);
    // $w = array(8, 10, 44, 10, 58, 20, 20); // menos 2+2+4+10+2 = 20
    // $header = array('#','Dors.','Nome','Esc.','Equipa', 'Tempo', 'Diff');
    // for($sex=0;$sex<count($header);$sex++)
    //     $this->Cell($w[$sex],5,utf8_decode($header[$sex]),1,0,'C',true);
    // $this->Ln();
  }

  // Page footer
  function Footer(){
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
// Campeonato Nacional Clubes Triatlo Longo - Absolutos Masculinos
$pdf->SetFont('Times','',9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(244,244,244);
$fill = false;
$raceId = $_GET['race_id'];
$gender = array('F', 'M');
for($sex = 0; $sex < 2; $sex++) {
  $pos = 1;
  $pdf->Cell(38,5,'Absolutos '.$gender[$sex],1,0,'C',$fill);
  $pdf->Ln();
  $queryAll = $db->prepare('SELECT athlete_name, athlete_bib, team_name, athlete_totaltime, athlete_category FROM athletes INNER JOIN teams ON athlete_team_id=team_id WHERE athlete_race_id=? AND athlete_started=5 AND athlete_sex=? ORDER BY athlete_totaltime LIMIT 3');
  $queryAll->execute([$raceId, $gender[$sex]]);
  $rows = $queryAll->fetchAll();
  foreach ($rows as $row) {
    $pdf->Cell(10,5,$pos,1,0,'L',$fill);
    $pdf->Cell(44,5,utf8_decode($row['athlete_name']),1,0,'L',$fill);
    $pdf->Cell(14,5,utf8_decode($row['athlete_bib']),1,0,'L',$fill);
    $pdf->Cell(14,5,utf8_decode($row['athlete_category']),1,0,'L',$fill);
    $pdf->Cell(44,5,utf8_decode($row['team_name']),1,0,'L',$fill);
    $pdf->Cell(20,5,utf8_decode($row['athlete_totaltime']),1,1,'L',$fill);
    $pos++;
    // echo $row['athlete_name'].'<br>';
  }
  $pdf->Ln();

  $pdf->Cell(38,5,'Escaloes '.$gender[$sex],1,0,'C',$fill);
  $pdf->Ln();
  $athlete_category = array("JUV", "CAD", "JUN", "JUNIOR", "ELITE","20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65-69", "70-74", "75-79", "80-84", "85-89", "90-94", "95-99", "S23", "SEN", "V1", "V2", "V3", "V4", "V5", "PTVI");
  for($cat = 0; $cat < count($athlete_category); $cat++){
    $pos = 1;
    $queryCat = $db->prepare('SELECT athlete_name, athlete_bib, team_name, athlete_totaltime, athlete_category FROM athletes INNER JOIN teams ON athlete_team_id=team_id WHERE athlete_race_id=? AND athlete_started=5 AND athlete_sex=? AND athlete_category=? ORDER BY athlete_totaltime LIMIT 3');
    $queryCat->execute([$raceId, $gender[$sex], $athlete_category[$cat]]);
    $rowCats = $queryCat->fetchAll();
    foreach ($rowCats as $rowCat) {
      if($pos == 1) {
        $pdf->Cell(38,5,$athlete_category[$cat].' '.$gender[$sex],1,0,'C',$fill);
        $pdf->Ln();
      }
      $pdf->Cell(10,5,$pos,1,0,'L',$fill);
      $pdf->Cell(44,5,utf8_decode($rowCat['athlete_name']),1,0,'L',$fill);
      $pdf->Cell(14,5,utf8_decode($rowCat['athlete_bib']),1,0,'L',$fill);
      $pdf->Cell(14,5,utf8_decode($rowCat['athlete_category']),1,0,'L',$fill);
      $pdf->Cell(44,5,utf8_decode($rowCat['team_name']),1,0,'L',$fill);
      $pdf->Cell(20,5,utf8_decode($rowCat['athlete_totaltime']),1,1,'L',$fill);
      $pos++;
    }
  }
  $pdf->Ln();
}

$pdf->Output();
?>