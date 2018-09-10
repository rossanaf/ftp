<?php

//load the database configuration file
include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
require('fpdf.php');

class PDF extends FPDF
{
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
    $this->Cell(8,5,utf8_decode("Hora da Partida: "),0,0,'R',true);
    
    $this->SetFont('Times','B',10);
    $this->SetX(34);
    $this->Cell(52,5,utf8_decode(ucwords($rowrace['race_location'])),0,0,'L',true);
    $this->SetX(110);
    $this->Cell(12,5,utf8_decode($rowrace['race_date']),0,0,'L',true);
    $this->SetX(190);
    $this->Cell(10,5,$rowrace['race_gun'],0,0,'R',true);
    // $this->Cell(10,5,$rowrace['race_gun'],0,0,'R',true);
    
    $segment1 = ucwords($rowrace['race_segment1'])." - ".$rowrace['race_distsegment1'];
    $segment1 = ucwords($rowrace['race_segment1'])." - ".$rowrace['race_distsegment1'];
    if ($rowrace['race_segment2'] !== 'n.a.')
    {
        $segment2 = ucwords($rowrace['race_segment2'])." - ".$rowrace['race_distsegment2'];
    }
    $segment3 = ucwords($rowrace['race_segment3'])." - ".$rowrace['race_distsegment3'];

    $this->SetFont('Times','',10);
    $this->Ln(10);
    $this->Cell(20,5,utf8_decode("Distancias: "),0,0,'L',true);
    $this->SetDrawColor(255,214,0);
    $this->Cell(50,5,utf8_decode($segment1),1,0,'C',true);
    $segment1 = ucwords($rowrace['race_segment1'])." - ".$rowrace['race_distsegment1'];
    if ($rowrace['race_segment2'] !== 'n.a.')
    {
        $this->SetDrawColor(166,16,8);
        $this->SetX(90);
        $this->Cell(50,5,utf8_decode($segment2),1,0,'C',true);
    }
    $this->SetX(150);
    $this->SetDrawColor(0,110,38);
    $this->Cell(50,5,utf8_decode($segment3),1,0,'C',true);
    $this->Ln(10);
    $this->SetFont('Times','',14);
    $this->Cell(190,8,utf8_decode("Classificações Absolutos Femininos"),0,0,'C');
    $this->Ln(10);
    $this->SetDrawColor(0);
    $this->SetFillColor(87,87,85);
	$this->SetTextColor(255);
	$this->SetLineWidth(.1);
    $this->SetFont('Times','',9);
	
	// Header
    $this->SetX(10);
	$w = array(8, 12, 8, 44, 10, 50, 19, 20, 19); // menos 2+2+4+10+2 = 20
	$header = array('#','Lic.','Dors.','Nome','Esc.','Equipa','T.Individual','T.Total', 'Diff.');
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
	$this->Ln();
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
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P','A4');

// Campeonato Nacional Clubes Triatlo Longo - Absolutos Masculinos
$pdf->SetFont('Times','',9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(244,244,244);
$pos = 1;
$fill = false;

//TEMPOS DOS GUNS
$race_id = $_GET['race_id'];
$querygun = $db->prepare("SELECT race_gun FROM races WHERE race_id = ? LIMIT 1");
$querygun->execute([$race_id]);
$rowrace = $querygun->fetch();

$query = $db->prepare("SELECT athlete_team_id, athlete_bib FROM athletes WHERE athlete_started >= 4 AND athletes.athlete_race_id = ? AND athlete_sex = 'F' ORDER BY athlete_finishtime ASC");
$query->execute([$race_id]);
$rows = $query->fetchAll();
$teams = array();
foreach ($rows as $row) 
{
    if (!in_array($row['athlete_team_id'], $teams)) 
    {
        $teams[$pos] = $row['athlete_team_id'];
        $posat = substr($row['athlete_bib'], -1);
        $bib = rtrim($row['athlete_bib'],$posat);
        // echo $bib.'<br>';
        $stmt = $db->prepare("SELECT * FROM athletes INNER JOIN teams ON athletes.athlete_team_id = teams.team_id WHERE athlete_bib LIKE ? ORDER BY athlete_bib ASC");
        $stmt->execute([$bib.'_']);
        $team = $stmt->fetchAll();
        foreach ($team as $relay) 
        {
            $team[$pos] = $relay['athlete_team_id'];
            $relay_posat = substr($relay['athlete_bib'], -1);
            $pdf->Cell(8,5,$pos,1,0,'C',$fill);
            $pdf->Cell(12,5,$relay['athlete_license'],1,0,'C',$fill);
            $pdf->Cell(8,5,$relay['athlete_bib'],1,0,'C',$fill);
            $pdf->Cell(44,5,utf8_decode($relay['athlete_name']),1,0,'L',$fill);
            $pdf->Cell(10,5,$relay['athlete_category'],1,0,'C',$fill);
            $pdf->Cell(50,5,utf8_decode($relay['team_name']),1,0,'L',$fill);
            $pdf->Cell(19,5,$relay['athlete_totaltime'],1,0,'C',$fill);
            if ($relay_posat == 'C')
            {
                $pdf->Cell(20,5,$relay['athlete_finishtime'],1,0,'C',$fill);
            } else
            {
                $pdf->Cell(20,5,$relay['athlete_finishtime'],1,1,'C',$fill);
            }
            if ($relay_posat == 'C')
            {
                if ($pos == 1)
                {
                    $relay_first = $relay['athlete_finishtime'];
                    $diff = "00:00:00";
                } else {
                    $diff = gmdate('H:i:s', strtotime($relay['athlete_finishtime'])-strtotime($relay_first));
                }
                $pdf->Cell(19,5,$diff,1,1,'C',$fill);
                $fill = !$fill;
                $pos++;        
            }
        }
    }
}
//**** PENALIZAÇÕES, time = DSQ / DNF / DNS
$penalty = array("DSQ", "DNF", "DNS", "LAP");
for($i=0;$i<count($penalty);$i++)
{
    $query = $db->prepare("SELECT athlete_team_id, athlete_bib FROM athletes WHERE athlete_finishtime = ? AND athletes.athlete_race_id = ? AND athletes.athlete_sex = 'F' AND athlete_bib LIKE '%C' ORDER BY athlete_finishtime ASC");
    $query->execute([$penalty[$i], $race_id]);
    $rows = $query->fetchAll();
    $teams = array();
    foreach ($rows as $row) 
    {
        if (!in_array($row['athlete_team_id'], $teams)) 
        {
            $teams[$pos] = $row['athlete_team_id'];
            $posat = substr($row['athlete_bib'], -1);
            $bib = rtrim($row['athlete_bib'],$posat);
            // echo $bib.'<br>';
            $stmt = $db->prepare("SELECT * FROM athletes INNER JOIN teams ON athletes.athlete_team_id = teams.team_id WHERE athlete_bib LIKE ? ORDER BY athlete_bib ASC");
            $stmt->execute([$bib.'_']);
            $team = $stmt->fetchAll();
            foreach ($team as $relay) 
            {
                $team[$pos] = $relay['athlete_team_id'];
                $relay_posat = substr($relay['athlete_bib'], -1);
                $pdf->Cell(8,5,$relay['athlete_finishtime'],1,0,'C',$fill);
                $pdf->Cell(12,5,$relay['athlete_license'],1,0,'C',$fill);
                $pdf->Cell(8,5,$relay['athlete_bib'],1,0,'C',$fill);
                $pdf->Cell(44,5,utf8_decode($relay['athlete_name']),1,0,'L',$fill);
                $pdf->Cell(10,5,$relay['athlete_category'],1,0,'C',$fill);
                $pdf->Cell(50,5,utf8_decode($relay['team_name']),1,0,'L',$fill);
                $pdf->Cell(19,5,$relay['athlete_totaltime'],1,0,'C',$fill);
                $pdf->Cell(20,5,$relay['athlete_finishtime'],1,1,'C',$fill);
                if ($relay_posat == 'C')
                {
                    $fill=!$fill;
                    $pos++;        
                }
            }
        }
    }
}
$pdf->Output();
?>