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
        $this->Ln(20);
        $this->SetX(10);
        // $w = array(8, 10, 44, 10, 58, 20, 20); // menos 2+2+4+10+2 = 20
        // $header = array('#','Dors.','Nome','Esc.','Equipa', 'Tempo', 'Diff');
        // for($i=0;$i<count($header);$i++)
        //     $this->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
        // $this->Ln();
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
$queryraces = $db->prepare("SELECT race_id, race_type, race_gun, race_name FROM races");
$queryraces->execute();
$races = $queryraces->fetchAll();

foreach ($races as $rowracetoday)
{
    if (($rowracetoday['race_type'] === 'triatlo') || ($rowracetoday['race_type'] === 'aquathlon'))
    {
        // **** TEMPOS DE QUEM TERMINOU **** //
        $query = $db->prepare("SELECT athlete_finishtime, athlete_totaltime, athlete_chip FROM athletes WHERE athlete_started >= 5 AND athlete_race_id = ?");
        $query->execute([$rowracetoday['race_gun']]);
        $rows = $query->fetchAll();
        $racegun = $rowracetoday['race_gun'];
        foreach ($rows as $row) 
        {
            if($row['athlete_totaltime']=="-")
            {
                $athlete_totaltime = gmdate('H:i:s', strtotime($row['athlete_finishtime'])-strtotime($racegun));
                $query = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_chip = ? AND athlete_race_id = ?");
                $query->execute([$athlete_totaltime, $row['athlete_chip'], $rowracetoday['race_gun']]);
            }
        }
        // FEMININOS
        $pdf->SetFont('Times','',14);
        $pdf->Cell(190,8,utf8_decode(strtoupper($rowracetoday['race_name'])),0,1,'C');
        $pdf->Cell(190,8,utf8_decode("Absolutos Femininos"),0,0,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',10);
        $pos=1;

        $query = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= '5' AND athletes.athlete_sex = 'F' AND athlete_race_id = ? ORDER BY athlete_totaltime ASC LIMIT 3");
        $query->execute([$rowracetoday['race_gun']]);
        $rows = $query->fetchAll();
        foreach ($rows as $row) 
        {
            $pdf->Cell(8,5,$pos,1,0,'C',$fill);
            $pdf->Cell(10,5,$row['athlete_bib'],1,0,'C',$fill);
            $pdf->Cell(44,5,utf8_decode($row['athlete_name']),1,0,'L',$fill);
            $pdf->Cell(10,5,$row['athlete_category'],1,0,'C',$fill);
            $pdf->Cell(58,5,utf8_decode($row['team_name']),1,0,'L',$fill);
            $pdf->Cell(20,5,$row['athlete_totaltime'],1,0,'C',$fill);
            if($pos == 1){
                $pdf->Cell(20,5,"-",1,1,'C',$fill);
                $time_winner = $row['athlete_totaltime'];
            }else{
                $time = strtotime($row['athlete_totaltime']) - strtotime($time_winner);
                $pdf->Cell(20,5,gmdate('H:i:s', $time),1,1,'C',$fill);
            }
            $fill=!$fill;
            $pos++;
        }
        $pdf->Ln(10);

        // MASCULINOS
        $pdf->SetFont('Times','',14);
        $pdf->Cell(190,8,utf8_decode("Absolutos Masculinos"),0,0,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',10);
        $pos=1;

        $query = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= '5' AND athletes.athlete_sex = 'M' ORDER BY athlete_totaltime ASC LIMIT 3");
        $query->execute();
        $rows = $query->fetchAll();
        foreach ($rows as $row) 
        {
            $pdf->Cell(8,5,$pos,1,0,'C',$fill);
            $pdf->Cell(10,5,$row['athlete_bib'],1,0,'C',$fill);
            $pdf->Cell(44,5,utf8_decode($row['athlete_name']),1,0,'L',$fill);
            $pdf->Cell(10,5,$row['athlete_category'],1,0,'C',$fill);
            $pdf->Cell(58,5,utf8_decode($row['team_name']),1,0,'L',$fill);
            $pdf->Cell(20,5,$row['athlete_totaltime'],1,0,'C',$fill);
            if($pos == 1){
                $pdf->Cell(20,5,"-",1,1,'C',$fill);
                $time_winner = $row['athlete_totaltime'];
            }else{
                $time = strtotime($row['athlete_totaltime']) - strtotime($time_winner);
                $pdf->Cell(20,5,gmdate('H:i:s', $time),1,1,'C',$fill);
            }
            $fill=!$fill;
            $pos++;
        }
        $pdf->Ln(10);

        // ESCALOES FEMININOS
        $pdf->SetFont('Times','',14);
        $pdf->Cell(190,8,utf8_decode("Escalões Femininos"),0,0,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',10);
        $pos=1;
        $athlete_category = array("CAD", "JUN", "20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65-69", "70-74", "75-79", "80-84", "85-89", "90-94", "95-99");
        $athlete_category_extenso = array("Cadetes", "Juniores", "20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65-69", "70-74", "75-79", "80-84", "85-89", "90-94", "95-99");
        // $athlete_category = array("S23", "SEN", "V1", "V2", "V3", "V4", "V5");
        // $athlete_category_extenso = array("Sub-23", "Seniores", "Veteranos 1", "Veterenos 2", "Veterenos 3", "Veterenos 4", "Veterenos 5");
        for($i=0;$i<count($athlete_category);$i++)
        {
            // $query = $db->prepare("SELECT athlete_id FROM athletes WHERE athletes.athlete_sex = 'F' LIMIT 1");
            // $query->execute([$athlete_category[$i]]);
            // $rows = $query->fetchAll();
            // if (count($rows)==1)
            // {
                $pos = 1;
                $fill = false;
                // $pdf->SetFont('Times','B',10);
                // $pdf->Cell(20,10,$athlete_category_extenso[$i],0,1,'C');
                // $pdf->SetFont('Times','',8);
                $queryfinisher = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= 5 AND athletes.athlete_sex = 'F' AND athlete_category = ? ORDER BY athlete_totaltime ASC LIMIT 3");
                $queryfinisher->execute([$athlete_category[$i]]);
                $finishers = $queryfinisher->fetchAll();
                foreach ($finishers as $rowfinisher) 
                {
                    if ($pos == 1)
                    {
                        $pdf->SetFont('Times','B',10);
                        $pdf->Cell(20,10,$athlete_category_extenso[$i],0,1,'C');
                        $pdf->SetFont('Times','',8);
                    }
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
            // }
        }
        $pdf->Ln(10);

        // ESCALOES MASCULINOS
        $pdf->SetFont('Times','',14);
        $pdf->Cell(190,8,utf8_decode("Escalões Masculinos"),0,0,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',10);
        $pos=1;
        $athlete_category = array("CAD", "JUN", "20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65-69", "70-74", "75-79", "80-84", "85-89", "90-94", "95-99");
        $athlete_category_extenso = array("Cadetes", "Juniores", "20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65-69", "70-74", "75-79", "80-84", "85-89", "90-94", "95-99");
        // $athlete_category = array("S23", "SEN", "V1", "V2", "V3", "V4", "V5");
        // $athlete_category_extenso = array("Sub-23", "Seniores", "Veteranos 1", "Veterenos 2", "Veterenos 3", "Veterenos 4", "Veterenos 5");
        for($i=0;$i<count($athlete_category);$i++)
        {
            // $query = $db->prepare("SELECT athlete_id FROM athletes WHERE athletes.athlete_sex = 'M' AND athlete_category = ? LIMIT 1");
            // $query->execute([$athlete_category[$i]]);
            // $rows = $query->fetchAll();
            // // echo count($rows)." ".$athlete_category[$i]." ".$rowracetoday['race_name']."<br>";
            // if (count($rows)==1)
            // {
                $pos = 1;
                $fill = false;
                $queryfinisher = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= 5 AND athletes.athlete_sex = 'M' AND athlete_category = ? ORDER BY athlete_totaltime ASC LIMIT 3");
                $queryfinisher->execute([$athlete_category[$i]]);
                // $finishers_num = $queryfinisher->fetchColumn();
                $finishers = $queryfinisher->fetchAll();
                // if ($finishers_num>0) 
                // {
                //     echo count($finishers)." aqui ".$athlete_category[$i]."<br>";
                // }
                //echo count($finishers)."<br>";
                foreach ($finishers as $rowfinisher) 
                {
                    if ($pos == 1)
                    {
                        $pdf->SetFont('Times','B',10);
                        $pdf->Cell(20,10,$athlete_category_extenso[$i],0,1,'C');
                        $pdf->SetFont('Times','',8);
                    }
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
            // }
        }
        $pdf->Ln(10);

        // // EQUIPAS FEMININAS
        // $pdf->SetFont('Times','',14);
        // $pdf->Cell(190,8,utf8_decode("Equipas Femininas"),0,0,'C');
        // $pdf->Ln(10);
        // $pdf->SetFont('Times','',10);

        // $clube = array();
        // $db->query("TRUNCATE teamresults");

        // //TEMPOS DOS GUNS
        // $querygun = $db->prepare("SELECT race_type, race_gun FROM races LIMIT 1");
        // $querygun->execute();
        // $rowrace = $querygun->fetch();

        // //**** TEMPOS DE QUEM TERMINOU ****//
        // $query = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= '5' AND athletes.athlete_sex = 'F'");
        // $query->execute();
        // $rows = $query->fetchAll();
        // foreach ($rows as $row) 
        // {
        //     if($row['athlete_totaltime']=="-")
        //     {
        //         if ($rowrace['race_type'] == 'crind')
        //         {
        //             $racegun = $row['athlete_t0'];
        //         } else {
        //             $racegun = $rowrace['race_gun'];
        //         }
        //         $athlete_totaltime = gmdate('H:i:s', strtotime($row['athlete_finishtime'])-strtotime($racegun));
        //         $query = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_chip = ?");
        //         $query->execute([$athlete_totaltime, $row['athlete_chip']]);
        //     }
        // }

        // // **** BUSCA OS TRES PRIMEIROS DE CADA EQUIPA **** //
        // $queryteams = $db->prepare("SELECT athlete_team_id FROM athletes WHERE athlete_started >= '5' AND athlete_sex = 'F' GROUP BY athlete_team_id HAVING COUNT(*) > 2");
        // $queryteams->execute();
        // $teams = $queryteams->fetchAll();

        // foreach ($teams as $row_clubes) 
        // {
        //     if(($row_clubes['athlete_team_id'] != 1000) && ($row_clubes['athlete_team_id'] != 1001)) 
        //     {
        //         //echo $race_id." - ".$row_clubes['athlete_team_id']."<br>";
        //         $querytimes = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id = teams.team_id WHERE athlete_team_id = ? AND athlete_started >= 5 AND athlete_sex = 'F' ORDER BY athlete_totaltime LIMIT 3");
        //         $querytimes->execute([$row_clubes['athlete_team_id']]);
        //         $timestable = $querytimes->fetchAll();
        //         $i=1;
        //         foreach ($timestable as $row_tempos) {
        //             //echo $row_tempos['team_name']."<br>";
        //             if ($rowrace['race_type'] == 'crind')
        //             {
        //                 $racegun = $row_tempos['athlete_t0'];
        //             } else {
        //                 $racegun = $rowrace['race_gun'];
        //             }   
        //             $tempo_individual = strtotime($row_tempos['athlete_finishtime'])-strtotime($racegun);
        //             if($i==1)
        //                 $teamresult_teamtime = $tempo_individual;
        //             else
        //                 $teamresult_teamtime = $tempo_individual + $teamresult_teamtime;
        //             //echo $tempo_individual."<br>";
        //             $results = $db->prepare("INSERT INTO teamresults (teamresult_bib, teamresult_finishtime, teamresult_team,  teamresult_license, teamresult_name, teamresult_category, teamresult_validate, teamresult_teamtime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        //             $results->execute([$row_tempos['athlete_bib'], gmdate('H:i:s',$tempo_individual), $row_tempos['team_name'], $row_tempos['athlete_license'], $row_tempos['athlete_name'], $row_tempos['athlete_category'], $i, gmdate('H:i:s',$teamresult_teamtime)]);
        //             $i++;
        //         }
        //     }
        // }

        // // Equipas - 3 melhores
        // $pdf->SetFont('Times','',9);
        // $pdf->SetTextColor(0);
        // $pdf->SetFillColor(244,244,244);
        // $pos = 1;
        // $fill = false;
        // $linha = 1;

        // $query_tempos = $db->query("SELECT teamresult_team FROM teamresults WHERE teamresult_validate = '3' ORDER BY teamresult_teamtime LIMIT 3");
        // $tempos = $query_tempos->execute();
        // foreach ($query_tempos as $row_tempos) 
        // {
        //     // Percorre apenas as equipas, ordem tempos totais da equipa
        //     $query_clubes = $db->prepare("SELECT * FROM teamresults WHERE teamresult_team = ? ORDER BY teamresult_validate");
        //     $query_clubes->execute([$row_tempos['teamresult_team']]);
        //     // Percorre toda a tabela
        //     foreach ($query_clubes as $row_clubes) {
        //         $pdf->Cell(6,5,$pos,1,0,'C',$fill);
        //         if($row_clubes['teamresult_validate'] == 3){
        //             $linha = 0;
        //             if($pos == 1)
        //                 $first_time = $row_clubes['teamresult_teamtime'];
        //             $diff = strtotime($row_clubes['teamresult_teamtime'])-strtotime($first_time);
        //             $teamresult_teamtime = $row_clubes['teamresult_teamtime'];
        //         }
        //         $pdf->Cell(14,5,$row_clubes['teamresult_license'],1,0,'C',$fill);
        //         $pdf->Cell(12,5,$row_clubes['teamresult_bib'],1,0,'C',$fill);
        //         $pdf->Cell(40,5,utf8_decode($row_clubes['teamresult_name']),1,0,'L',$fill);
        //         $pdf->Cell(10,5,$row_clubes['teamresult_category'],1,0,'C',$fill);
        //         $pdf->Cell(52,5,utf8_decode($row_clubes['teamresult_team']),1,0,'L',$fill);
        //         $pdf->Cell(19,5,utf8_decode($row_clubes['teamresult_finishtime']),1,$linha,'C',$fill);
        //     }
        //     $pdf->Cell(19,5,$teamresult_teamtime,1,0,'C',$fill);
        //     $pdf->Cell(18,5,gmdate('H:i:s', $diff),1,1,'C',$fill);
        //     $fill=!$fill;
        //     $linha = 1;
        //     $pos++;
        // }
        // $pdf->Ln(10);

        // // EQUIPAS MASCULINAS
        // $pdf->SetFont('Times','',14);
        // $pdf->Cell(190,8,utf8_decode("Equipas Masculinos"),0,0,'C');
        // $pdf->Ln(10);
        // $pdf->SetFont('Times','',10);

        // $clube = array();
        // $db->query("TRUNCATE teamresults");

        // //TEMPOS DOS GUNS
        // $querygun = $db->prepare("SELECT race_type, race_gun FROM races LIMIT 1");
        // $querygun->execute();
        // $rowrace = $querygun->fetch();

        // //**** TEMPOS DE QUEM TERMINOU ****//
        // $query = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id=teams.team_id WHERE athletes.athlete_started >= '5' AND athletes.athlete_sex = 'M'");
        // $query->execute();
        // $rows = $query->fetchAll();
        // foreach ($rows as $row) 
        // {
        //     if($row['athlete_totaltime']=="-")
        //     {
        //         if ($rowrace['race_type'] == 'crind')
        //         {
        //             $racegun = $row['athlete_t0'];
        //         } else {
        //             $racegun = $rowrace['race_gun'];
        //         }
        //         $athlete_totaltime = gmdate('H:i:s', strtotime($row['athlete_finishtime'])-strtotime($racegun));
        //         $query = $db->prepare("UPDATE athletes SET athlete_totaltime = ? WHERE athlete_chip = ?");
        //         $query->execute([$athlete_totaltime, $row['athlete_chip']]);
        //     }
        // }

        // // **** BUSCA OS TRES PRIMEIROS DE CADA EQUIPA **** //
        // $queryteams = $db->prepare("SELECT athlete_team_id FROM athletes WHERE athlete_started >= '5' AND athlete_sex = 'M' GROUP BY athlete_team_id HAVING COUNT(*) > 2");
        // $queryteams->execute();
        // $teams = $queryteams->fetchAll();

        // foreach ($teams as $row_clubes) 
        // {
        //     if(($row_clubes['athlete_team_id'] != 1000) && ($row_clubes['athlete_team_id'] != 1001)) 
        //     {
        //         //echo $race_id." - ".$row_clubes['athlete_team_id']."<br>";
        //         $querytimes = $db->prepare("SELECT athletes.*, teams.team_name FROM athletes INNER JOIN teams ON athletes.athlete_team_id = teams.team_id WHERE athlete_team_id = ? AND athlete_started >= 5 AND athlete_sex = 'M' ORDER BY athlete_totaltime LIMIT 3");
        //         $querytimes->execute([$row_clubes['athlete_team_id']]);
        //         $timestable = $querytimes->fetchAll();
        //         $i=1;
        //         foreach ($timestable as $row_tempos) {
        //             //echo $row_tempos['team_name']."<br>";
        //             if ($rowrace['race_type'] == 'crind')
        //             {
        //                 $racegun = $row_tempos['athlete_t0'];
        //             } else {
        //                 $racegun = $rowrace['race_gun'];
        //             }   
        //             $tempo_individual = strtotime($row_tempos['athlete_finishtime'])-strtotime($racegun);
        //             if($i==1)
        //                 $teamresult_teamtime = $tempo_individual;
        //             else
        //                 $teamresult_teamtime = $tempo_individual + $teamresult_teamtime;
        //             //echo $tempo_individual."<br>";
        //             $results = $db->prepare("INSERT INTO teamresults (teamresult_bib, teamresult_finishtime, teamresult_team,  teamresult_license, teamresult_name, teamresult_category, teamresult_validate, teamresult_teamtime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        //             $results->execute([$row_tempos['athlete_bib'], gmdate('H:i:s',$tempo_individual), $row_tempos['team_name'], $row_tempos['athlete_license'], $row_tempos['athlete_name'], $row_tempos['athlete_category'], $i, gmdate('H:i:s',$teamresult_teamtime)]);
        //             $i++;
        //         }
        //     }
        // }

        // // Equipas - 3 melhores
        // $pdf->SetFont('Times','',9);
        // $pdf->SetTextColor(0);
        // $pdf->SetFillColor(244,244,244);
        // $pos = 1;
        // $fill = false;
        // $linha = 1;

        // $query_tempos = $db->query("SELECT teamresult_team FROM teamresults WHERE teamresult_validate = '3' ORDER BY teamresult_teamtime LIMIT 3");
        // $tempos = $query_tempos->execute();
        // foreach ($query_tempos as $row_tempos) 
        // {
        //     // Percorre apenas as equipas, ordem tempos totais da equipa
        //     $query_clubes = $db->prepare("SELECT * FROM teamresults WHERE teamresult_team = ? ORDER BY teamresult_validate");
        //     $query_clubes->execute([$row_tempos['teamresult_team']]);
        //     // Percorre toda a tabela
        //     foreach ($query_clubes as $row_clubes) {
        //         $pdf->Cell(6,5,$pos,1,0,'C',$fill);
        //         if($row_clubes['teamresult_validate'] == 3){
        //             $linha = 0;
        //             if($pos == 1)
        //                 $first_time = $row_clubes['teamresult_teamtime'];
        //             $diff = strtotime($row_clubes['teamresult_teamtime'])-strtotime($first_time);
        //             $teamresult_teamtime = $row_clubes['teamresult_teamtime'];
        //         }
        //         $pdf->Cell(14,5,$row_clubes['teamresult_license'],1,0,'C',$fill);
        //         $pdf->Cell(12,5,$row_clubes['teamresult_bib'],1,0,'C',$fill);
        //         $pdf->Cell(40,5,utf8_decode($row_clubes['teamresult_name']),1,0,'L',$fill);
        //         $pdf->Cell(10,5,$row_clubes['teamresult_category'],1,0,'C',$fill);
        //         $pdf->Cell(52,5,utf8_decode($row_clubes['teamresult_team']),1,0,'L',$fill);
        //         $pdf->Cell(19,5,utf8_decode($row_clubes['teamresult_finishtime']),1,$linha,'C',$fill);
        //     }
        //     $pdf->Cell(19,5,$teamresult_teamtime,1,0,'C',$fill);
        //     $pdf->Cell(18,5,gmdate('H:i:s', $diff),1,1,'C',$fill);
        //     $fill=!$fill;
        //     $linha = 1;
        //     $pos++;
        // }
        // $pdf->Ln(10);
    }
}

$pdf->Output();
?>