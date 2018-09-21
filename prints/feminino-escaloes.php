<?php
  include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
  require('fpdf.php');

  class PDF extends FPDF {
    // Page header
    function Header() {
      include_once($_SERVER['DOCUMENT_ROOT']."/functions/PDFs/pdfHeader.php");
      pdfHeader_V($this, $_GET['race_id'], 'F', 'Classificações Escalões Femininos');
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
  //TEMPOS DOS GUNS
  $race_id = $_GET['race_id'];
  $querygun = $db->prepare("SELECT race_type, race_gun_f FROM races WHERE race_id = ? LIMIT 1");
  $querygun->execute([$race_id]);
  $rowrace = $querygun->fetch();
  //**** TEMPOS DE QUEM TERMINOU ****//
  $query = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= '5' AND athletes.athlete_race_id = ? AND athletes.athlete_sex = 'F'");
  $query->execute([$race_id]);
  $rows = $query->fetchAll();
  foreach ($rows as $row) {
    if ($rowrace['race_type'] == 'crind') $racegun = $row['athlete_t0'];
    else $racegun = $rowrace['race_gun_f'];
    $athlete_totaltime = gmdate('H:i:s', strtotime($row['athlete_finishtime'])-strtotime($racegun));
    $query = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_chip = ?");
    $query->execute([$athlete_totaltime, $row['athlete_chip']]);
  }

  $athlete_category = array("JUV", "CAD", "JUN", "JUNIOR", "ELITE","20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65-69", "70-74", "75-79", "80-84", "85-89", "90-94", "95-99", "S23", "SEN", "V1", "V2", "V3", "V4", "V5", "PTVI");
  $athlete_category_extenso = array("Juvenis", "Cadetes", "Juniores", "JUNIOR", "ELITE", "20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65-69", "70-74", "75-79", "80-84", "85-89", "90-94", "95-99", "Sub-23", "Seniores", "Veteranos 1", "Veterenos 2", "Veterenos 3", "Veterenos 4", "Veterenos 5", "PTVI");
  for($i=0;$i<count($athlete_category);$i++) {
    $query = $db->prepare("SELECT athlete_id FROM athletes WHERE athletes.athlete_race_id = ? AND athletes.athlete_sex = 'F' AND athlete_category = ? LIMIT 1");
    $query->execute([$race_id, $athlete_category[$i]]);
    $rows = $query->fetchAll();
    if (count($rows)==1) {
      $pos = 1;
      $fill = false;
      $pdf->SetFont('Times','B',10);
      $pdf->Cell(20,10,$athlete_category_extenso[$i],0,1,'C');
      $pdf->SetFont('Times','',8);
      $queryfinisher = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= 5 AND athletes.athlete_race_id = ? AND athletes.athlete_sex = 'F' AND athlete_category = ? ORDER BY athlete_totaltime ASC");
      $queryfinisher->execute([$race_id, $athlete_category[$i]]);
      $finishers = $queryfinisher->fetchAll();
      foreach ($finishers as $rowfinisher) {
        $pdf->Cell(8,5,$pos,1,0,'C',$fill);
        $pdf->Cell(14,5,$rowfinisher['athlete_license'],1,0,'C',$fill);
        $pdf->Cell(10,5,$rowfinisher['athlete_bib'],1,0,'C',$fill);
        $pdf->Cell(44,5,utf8_decode($rowfinisher['athlete_name']),1,0,'L',$fill);
        $pdf->Cell(6,5,$rowfinisher['athlete_sex'],1,0,'C',$fill);
        $pdf->Cell(10,5,$rowfinisher['athlete_category'],1,0,'C',$fill);
        $pdf->Cell(58,5,utf8_decode($rowfinisher['team_name']),1,0,'L',$fill);
        $pdf->Cell(20,5,$rowfinisher['athlete_totaltime'],1,0,'C',$fill);
        if($pos == 1) {
            $pdf->Cell(20,5,"-",1,1,'C',$fill);
            $time_winner = $rowfinisher['athlete_totaltime'];
        } else {
            $time = strtotime($rowfinisher['athlete_totaltime']) - strtotime($time_winner);
            $pdf->Cell(20,5,gmdate('H:i:s', $time),1,1,'C',$fill);
        }
        $fill=!$fill;
        $pos++;
      }
      // **** PENALIZAÇÕES, time = DSQ / DNF / DNS
      $penalty = array("DSQ", "DNF", "DNS", "LAP");
      for($j=0;$j<count($penalty);$j++) {
        $querypenalty = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_race_id = ? AND athletes.athlete_finishtime = ? AND athletes.athlete_sex = 'F' AND athlete_category = ? ORDER BY athletes.athlete_started DESC");
        $querypenalty->execute([$race_id, $penalty[$j], $athlete_category[$i]]);
        $rowspenalty = $querypenalty->fetchAll();
        foreach ($rowspenalty as $rowpenalty) {
          $pdf->Cell(8,5,$rowpenalty['athlete_finishtime'],1,0,'C',$fill);
          $pdf->Cell(14,5,$rowpenalty['athlete_license'],1,0,'C',$fill);
          $pdf->Cell(10,5,$rowpenalty['athlete_bib'],1,0,'C',$fill);
          $pdf->Cell(44,5,utf8_decode($rowpenalty['athlete_name']),1,0,'L',$fill);
          $pdf->Cell(6,5,$rowpenalty['athlete_sex'],1,0,'C',$fill);
          $pdf->Cell(10,5,$rowpenalty['athlete_category'],1,0,'C',$fill);
          $pdf->Cell(58,5,utf8_decode($rowpenalty['team_name']),1,0,'L',$fill);
          $pdf->Cell(20,5,$rowpenalty['athlete_finishtime'],1,1,'C',$fill);
          $fill=!$fill;
        }
      }
    }
  }
  $pdf->Output();
?>