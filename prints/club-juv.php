<?php

//load the database configuration file
include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
require('fpdf.php');

class PDF extends FPDF {
// Page header
  function Header()
  {
    //load the database configuration file
    include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");

    $race_id = $_GET['race_id'];
    $queryrace = $db->prepare("SELECT * FROM races WHERE race_id = ? LIMIT 1");
    $queryrace->execute([$race_id]);
    $rowrace = $queryrace->fetch();

    // Logo
    $this->Image('../images/ftp_logo.png',10,5,54);
    // Times bold 15
    $this->SetFont('Times','B',14);
    // Move to the right
    $this->SetX(100);
    // Title
    $this->Cell(80,8,utf8_decode(strtoupper($rowrace['race_namepdf'])),0,1,'C');
    // Line break
    $this->SetX(100);
    $this->Cell(80,8,utf8_decode(ucwords($rowrace['race_ranking'])),0,0,'C');
    $this->Ln(24);
    
    $this->SetDrawColor(255,214,0);
    $this->Line(0,34,80,34);
    $this->SetDrawColor(0,110,38);
    $this->Line(80,34,150,34);
    $this->SetDrawColor(166,16,8);
    $this->Line(150,34,210,34);
    
    $this->SetLineWidth(.4);
    $this->SetFont('Times','',10);
    $this->SetFillColor(255);
    $this->Cell(24,5,utf8_decode("Local da Prova: "),0,0,'L',true);
    $this->SetX(100);
    $this->Cell(16,5,utf8_decode("Data: "),0,0,'L',true);
    $this->SetX(176);
    // $this->Cell(8,5,utf8_decode("Hora da Partida: "),0,0,'R',true);
    
    $this->SetFont('Times','B',10);
    $this->SetX(34);
    $this->Cell(52,5,utf8_decode(ucwords($rowrace['race_location'])),0,0,'L',true);
    $this->SetX(110);
    $this->Cell(12,5,utf8_decode($rowrace['race_date']),0,0,'L',true);
    $this->SetX(190);
    // $this->Cell(10,5,$rowrace['race_gun'],0,0,'R',true);
    
    // $segment1 = ucwords($rowrace['race_segment1'])." - ".$rowrace['race_distsegment1'];
    // if ($rowrace['race_segment2'] !== 'n.a.')
    // {
    //     $segment2 = ucwords($rowrace['race_segment2'])." - ".$rowrace['race_distsegment2'];
    // }
    // $segment3 = ucwords($rowrace['race_segment3'])." - ".$rowrace['race_distsegment3'];

    // $this->SetFont('Times','',10);
    // $this->Ln(10);
    // $this->Cell(20,5,utf8_decode("Distancias: "),0,0,'L',true);
    // $this->SetDrawColor(255,214,0);
    // $this->Cell(50,5,utf8_decode($segment1),1,0,'C',true);
    // if ($rowrace['race_segment2'] !== 'n.a.')
    // {
    //     $this->SetDrawColor(166,16,8);
    //     $this->SetX(90);
    //     $this->Cell(50,5,utf8_decode($segment2),1,0,'C',true);
    // }
    // $this->SetX(150);
    // $this->SetDrawColor(0,110,38);
    // $this->Cell(50,5,utf8_decode($segment3),1,0,'C',true);
    $this->Ln(10);
    $this->SetFont('Times','',14);
    $this->Cell(190,8,utf8_decode("Classificações Clubes"),0,0,'C');
    $this->Ln(10);
    $this->SetDrawColor(0);
    $this->SetFillColor(87,87,85);
	$this->SetTextColor(255);
	$this->SetLineWidth(.1);
    $this->SetFont('Times','',9);
	
	// Header
    $this->SetX(10);
	$w = array(12, 114, 20, 40); // menos 2+2+4+10+2 = 20
	$header = array('#','Clube','# Atletas', 'Total Pontos');
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
	$this->Ln();
}

//**** Page footer
function Footer()
{
    $this->SetDrawColor(255,214,0);
    $this->Line(0,290,80,290);
    $this->SetDrawColor(0,110,38);
    $this->Line(80,290,150,290);
    $this->SetDrawColor(166,16,8);
    $this->Line(150,290,210,290);
    // Position at 1.0 cm from bottom
    $this->SetXY(10,-10);
    // Arial italic 8
    $this->SetFont('Times','',7);
    // Page number
    $this->Cell(0,10,utf8_decode("© Federação de Triatlo de Portugal"),0,0,'L');
    $this->Cell(0,10,utf8_decode("Página ").$this->PageNo().'/{nb}',0,0,'R');
}
}

//**** Instanciation of inherited class
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage('P','A4');

//**** Prova Aberta - Absolutos Masculinos
$pdf->SetFont('Times','',10);
$pdf->SetTextColor(0);
$pdf->SetFillColor(244,244,244);
$pos = 1;
$fill = false;

$stmt = $db->prepare("TRUNCATE cjovem; TRUNCATE clubesj");
$stmt->execute();
// $stmt->closeCursor();

  //**** classificações clubes campeonato nacional jovem ****//
  $escalao = array("BEN", "INF", "INI", "JUV");
  $sexo = array("F", "M");
  $pontos = 0;

  for($i=0;$i<count($escalao);$i++) {
    for ($j=0;$j<count($sexo);$j++) {
      $stmt = $db->prepare("SELECT athlete_team_id FROM athletes WHERE athlete_sex=? AND athlete_started>='5' AND athlete_category=? ORDER BY athlete_finishtime ASC");
      $stmt->execute([$sexo[$j], $escalao[$i]]);
      $teams = $stmt->fetchAll();
      foreach ($teams as $row) {
        $fill=!$fill;
        if (($row['athlete_team_id'] != 1) && ($row['athlete_team_id'] != 2) && ($row['athlete_team_id'] != 3)) {
          if($pos === 1) $pontos = 100;
          elseif($pos === 2) $pontos = 90;
          elseif($pos === 3) $pontos = 80;
          elseif($pos === 4) $pontos = 70;
          elseif($pos === 5) $pontos = 60;
          elseif($pos === 6) $pontos = 55;
          elseif($pos === 7) $pontos = 50;
          elseif($pos === 8) $pontos = 45;
          elseif($pos === 9) $pontos = 40;
          elseif($pos === 10) $pontos = 35;
          elseif($pos === 11) $pontos = 32;
          elseif($pos === 12) $pontos = 29;
          elseif($pos === 13) $pontos = 26;
          elseif($pos === 14) $pontos = 23;
          elseif($pos === 15) $pontos = 20;
          elseif($pos === 16) $pontos = 18;
          elseif($pos === 17) $pontos = 16;
          elseif($pos === 18) $pontos = 14;
          elseif($pos === 19) $pontos = 12;
          elseif($pos === 20) $pontos = 10;
          elseif($pos === 21) $pontos = 9;
          elseif($pos === 22) $pontos = 8;
          elseif($pos === 23) $pontos = 7;
          elseif($pos === 24) $pontos = 6;
          elseif($pos === 25) $pontos = 5;
          elseif($pos === 26) $pontos = 4;
          elseif($pos === 27) $pontos = 3;
          elseif($pos === 28) $pontos = 2;
          else $pontos = 1;
          $stmt_team = $db->prepare("INSERT INTO cjovem (clube, pontos) VALUES (?, ?)");
          $stmt_team->execute([$row['athlete_team_id'], $pontos]);
          $pos++;
        }
      }
    }
    $pos=1;
  }

$stmt = $db->prepare("SELECT clube FROM cjovem GROUP BY clube");
$stmt->execute();
$rows = $stmt->fetchAll();
foreach ($rows as $row) 
{
    $stmt_youth = $db->prepare("INSERT INTO clubesj (clube) VALUES (?)");
    $stmt_youth->execute([$row['clube']]);
    $stmt_cjovem = $db->prepare("SELECT clube, pontos FROM cjovem WHERE clube = ?");
    $stmt_cjovem->execute([$row['clube']]);
    $pontos = 0;
    $atletas = 1;
    $rows_cjovem = $stmt_cjovem->fetchAll();
    foreach ($rows_cjovem as $row_cjovem) 
    {
    
        $pontos += $row_cjovem['pontos'];
        $stmt_points = $db->prepare("UPDATE clubesj SET pontos = ?, atletas = ? WHERE clube = ?");
        $stmt_points->execute([$pontos, $atletas, $row['clube']]);

        $atletas++;
    }

}

$stmt = $db->prepare("SELECT * FROM clubesj INNER JOIN teams ON clubesj.clube = teams.team_id ORDER BY pontos DESC");
$stmt->execute();
$rows = $stmt->fetchAll();
foreach ($rows as $row) 
{
    $pdf->Cell(12,6,$pos,1,0,'C',$fill);
    $pdf->Cell(114,6,utf8_decode($row['team_name']),1,0,'L',$fill);
    $pdf->Cell(20,6,utf8_decode($row['atletas']),1,0,'C',$fill);
    $pdf->Cell(40,6,utf8_decode($row['pontos']),1,1,'C',$fill);
    $pos++;
    $fill = !$fill;
}

$pdf->Output();
?>