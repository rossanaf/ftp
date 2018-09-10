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
        $this->Ln(30);
        $this->SetX(10);
        $w = array(8, 10, 44, 10, 58, 20, 20); // menos 2+2+4+10+2 = 20
        $header = array('#','Dors.','Nome','Esc.','Equipa', 'Tempo', 'Diff');
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
$querygun = $db->prepare("SELECT race_id FROM races WHERE race_type='jovem' LIMIT 1");
$querygun->execute();
$rowrace = $querygun->fetch();

//**** TEMPOS DE QUEM TERMINOU ****//
// $query = $db->prepare("SELECT athlete_totaltime, athlete_finishtime, athlete_chip FROM athletes WHERE athletes.athlete_started >= '5' AND athletes.athlete_sex = 'M'");
// $query->execute();
// $rows = $query->fetchAll();
// foreach ($rows as $row) 
// {
//     // if ($rowrace['race_type'] == 'crind')
//     // {
//     //     $racegun = $row['athlete_t0'];
//     // } else {
//     //     $racegun = $rowrace['race_gun'];
//     // }
//     $athlete_totaltime = gmdate('H:i:s', strtotime($row['athlete_finishtime'])-strtotime($racegun));
//     $query = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_chip = ?");
//     $query->execute([$athlete_totaltime, $row['athlete_chip']]);
// }

// ESCALOES FEMININOS
$pdf->SetFont('Times','',14);
$pdf->Cell(190,8,utf8_decode("Escalões Femininos"),0,0,'C');
$pdf->Ln(10);
$pdf->SetFont('Times','',10);
$pos=1;
$athlete_category = array("BEN", "INF", "INI", "JUV");
$athlete_category_extenso = array("Benjamins", "Infantis", "Iniciados", "Juvenis");
// $athlete_category = array("S23", "SEN", "V1", "V2", "V3", "V4", "V5");
// $athlete_category_extenso = array("Sub-23", "Seniores", "Veteranos 1", "Veterenos 2", "Veterenos 3", "Veterenos 4", "Veterenos 5");
for($i=0;$i<count($athlete_category);$i++)
{
    $query = $db->prepare("SELECT athlete_id FROM athletes WHERE athletes.athlete_sex = 'F' LIMIT 1");
    $query->execute([$athlete_category[$i]]);
    $rows = $query->fetchAll();
    if (count($rows)==1)
    {
        $pos = 1;
        $fill = false;
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(20,10,$athlete_category_extenso[$i],0,1,'C');
        $pdf->SetFont('Times','',8);
        $queryfinisher = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= 5 AND athletes.athlete_sex = 'F' AND athlete_category = ? ORDER BY athlete_totaltime ASC LIMIT 3");
        $queryfinisher->execute([$athlete_category[$i]]);
        $finishers = $queryfinisher->fetchAll();
        foreach ($finishers as $rowfinisher) 
        {
            $pdf->Cell(8,5,$pos,1,0,'C',$fill);
            $pdf->Cell(10,5,$rowfinisher['athlete_bib'],1,0,'C',$fill);
            $pdf->Cell(44,5,utf8_decode($rowfinisher['athlete_name']),1,0,'L',$fill);
            $pdf->Cell(10,5,$rowfinisher['athlete_category'],1,0,'C',$fill);
            $pdf->Cell(58,5,utf8_decode($rowfinisher['team_name']),1,0,'L',$fill);
            $pdf->Cell(20,5,$rowfinisher['athlete_totaltime'],1,0,'C',$fill);
            if($pos == 1)
            {
                $pdf->Cell(20,5,"-",1,1,'C',$fill);
                $time_winner = $rowfinisher['athlete_totaltime'];
            } else {
                $time = strtotime($rowfinisher['athlete_totaltime']) - strtotime($time_winner);
                $pdf->Cell(20,5,gmdate('H:i:s', $time),1,1,'C',$fill);
            }
            $fill=!$fill;
            $pos++;
        }
    }
}
$pdf->Ln(10);

// ESCALOES MASCULINOS
$pdf->SetFont('Times','',14);
$pdf->Cell(190,8,utf8_decode("Escalões Masculinos"),0,0,'C');
$pdf->Ln(10);
$pdf->SetFont('Times','',10);
$pos=1;
$athlete_category = array("BEN", "INF", "INI", "JUV");
$athlete_category_extenso = array("Benjamins", "Infantis", "Iniciados", "Juvenis");
// $athlete_category = array("S23", "SEN", "V1", "V2", "V3", "V4", "V5");
// $athlete_category_extenso = array("Sub-23", "Seniores", "Veteranos 1", "Veterenos 2", "Veterenos 3", "Veterenos 4", "Veterenos 5");
for($i=0;$i<count($athlete_category);$i++)
{
    $query = $db->prepare("SELECT athlete_id FROM athletes WHERE athletes.athlete_sex = 'M' AND athlete_category = ? LIMIT 1");
    $query->execute([$athlete_category[$i]]);
    $rows = $query->fetchAll();
    if (count($rows)==1)
    {
        $pos = 1;
        $fill = false;
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(20,10,$athlete_category_extenso[$i],0,1,'C');
        $pdf->SetFont('Times','',8);
        $queryfinisher = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= 5 AND athletes.athlete_sex = 'M' AND athlete_category = ? ORDER BY athlete_totaltime ASC LIMIT 3");
        $queryfinisher->execute([$athlete_category[$i]]);
        $finishers = $queryfinisher->fetchAll();
        //echo count($finishers)."<br>";
        foreach ($finishers as $rowfinisher) 
        {
            $pdf->Cell(8,5,$pos,1,0,'C',$fill);
            $pdf->Cell(10,5,$rowfinisher['athlete_bib'],1,0,'C',$fill);
            $pdf->Cell(44,5,utf8_decode($rowfinisher['athlete_name']),1,0,'L',$fill);
            $pdf->Cell(10,5,$rowfinisher['athlete_category'],1,0,'C',$fill);
            $pdf->Cell(58,5,utf8_decode($rowfinisher['team_name']),1,0,'L',$fill);
            $pdf->Cell(20,5,$rowfinisher['athlete_totaltime'],1,0,'C',$fill);
            if($pos == 1)
            {
                $pdf->Cell(20,5,"-",1,1,'C',$fill);
                $time_winner = $rowfinisher['athlete_totaltime'];
            } else {
                $time = strtotime($rowfinisher['athlete_totaltime']) - strtotime($time_winner);
                $pdf->Cell(20,5,gmdate('H:i:s', $time),1,1,'C',$fill);
            }
            $fill=!$fill;
            $pos++;
        }
    }
}
$pdf->Ln(10);

// EQUIPAS
$pdf->SetFont('Times','',14);
$pdf->Cell(190,8,utf8_decode("Equipas"),0,0,'C');
$pdf->Ln(10);
$pdf->SetFont('Times','',10);
$pos=1;
$stmt = $db->prepare("SELECT * FROM clubesj INNER JOIN teams ON clubesj.clube = teams.team_id ORDER BY pontos DESC LIMIT 5");
$stmt->execute();
$rows = $stmt->fetchAll();
foreach ($rows as $row) 
{
// $query = mysqli_query($db, "SELECT * FROM clubesj ORDER BY pontos DESC");
// while($row = mysqli_fetch_array($query)){
    $pdf->Cell(12,6,$pos,1,0,'C',$fill);
    $pdf->Cell(114,6,utf8_decode($row['team_name']),1,0,'L',$fill);
    $pdf->Cell(20,6,utf8_decode($row['atletas']),1,0,'C',$fill);
    $pdf->Cell(40,6,utf8_decode($row['pontos']),1,1,'C',$fill);
    $pos++;
    $fill = !$fill;
}

$pdf->Output();
?>