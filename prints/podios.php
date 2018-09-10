<?php
//load the database configuration file
include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
require('fpdf.php');
class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
    $this->Image('../images/ftp_logo.png',10,5,54);
    // Times bold 15
    $this->SetFont('Times','B',14);
        // Move to the right
        $this->SetX(100);
        // Title
        $this->Cell(80,6,utf8_decode("PÓDIOS"),0,1,'C');
        // Line break
        // $this->SetX(100);
        // $this->Cell(80,6,utf8_decode("Campeonato Nacional de Clubes - Triatlo Longo"),0,0,'C');
    	$this->Ln(24);
        $this->SetDrawColor(255,214,0);
        $this->Line(0,34,80,34);
        $this->SetDrawColor(0,110,38);
        $this->Line(80,34,150,34);
        $this->SetDrawColor(166,16,8);
        $this->Line(150,34,210,34);
    }
    // Page footer
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

// Instanciation of inherited class
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage('P','A4');
$pdf->SetTextColor(0);
$pdf->SetFillColor(244,244,244);

// PERCORRER TODAS AS PROVAS
$stmt = $db->prepare("SELECT race_id, race_name, race_type FROM races");
$stmt->execute();
$races = $stmt->fetchAll();
foreach ($races as $race) 
{
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(190,14,utf8_decode($race['race_name']),0,1,'L',$fill);
    if ($race['race_type'] == 'jovem')
    {

    } elseif ($race['race_type'] == 'triatlo')
    {
        // DIVIDIR POR MASCULINO E FEMININO
        $sexo = array('F', 'M');
        $genero = array('FEMININOS', 'MASCULINOS');
        for ($j=0; $j<2; $j++)
        {
            $pos = 1;
            $fill = false;
            // $pdf->Cell(190,14,utf8_decode("Absolutos"),0,1,'L',$fill);
            $pdf->SetFont('Times','B',11);
            $pdf->Cell(190,14,utf8_decode("ABSOLUTOS ".$genero[$j]),0,1,'L',$fill);
            $pdf->SetFont('Times','',11);            
            $query = $db->prepare("SELECT athlete_name, athlete_bib, athlete_team FROM athletes WHERE athlete_started >= '5' AND athlete_race_id = ? AND athlete_sex = ? ORDER BY athlete_finishtime LIMIT 3");
            $query->execute([$race['race_id'], $sexo[$j]]);
            $rows = $query->fetchAll();
            foreach ($rows as $row) 
            {
                $pdf->Cell(14,6,$pos,1,0,'C',$fill);
                $pdf->Cell(60,6,utf8_decode($row['athlete_bib']),1,0,'L',$fill);
                $pdf->Cell(20,6,$row['athlete_name'],1,0,'C',$fill);
                $pdf->Cell(80,6,utf8_decode($row['athlete_team']),1,1,'L',$fill);
                $fill=!$fill;
                $pos++;
            }
        }
    }
}   
//     if($query->num_rows > 0) {
//         while($row = mysqli_fetch_array($query)) {        
//             $pdf->Cell(14,6,$pos,1,0,'C',$fill);
//             $pdf->Cell(60,6,utf8_decode($row['name']),1,0,'L',$fill);
//             $pdf->Cell(20,6,$row['escalao'],1,0,'C',$fill);
//             $pdf->Cell(80,6,utf8_decode($row['clube']),1,1,'L',$fill);
//             $fill=!$fill;
//             $pos++;
//         }
//     }
    
//     /***************************************************************************************************************************/
//     /***************************************************************************************************************************/
//     /***************************************************************************************************************************/
    
//     $pos = 1;
//     $fill = false;
    
//     $pdf->SetFont('Times','B',11);
//     //$pdf->Cell(190,14,utf8_decode("GRUPOS DE IDADES"),0,1,'L',$fill);
//     $pdf->Cell(190,14,utf8_decode("ESCALÕES"),0,1,'L',$fill);

//     //$escalao = array("20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65-69", "70-74", "75-79", "80-84", "85-89", "90-94", "95-99", "100+");
//     //$escalao_extenso = array("Grupo de Idade 20-24", "Grupo de Idade 25-29", "Grupo de Idade 30-34", "Grupo de Idade 35-39", "Grupo de Idade 40-44", "Grupo de Idade 45-49", "Grupo de Idade 50-54", "Grupo de Idade 55-59", "Grupo de Idade 60-64", "Grupo de Idade 65-69", "Grupo de Idade 70-74", "Grupo de Idade 75-79", "Grupo de Idade 80-84", "Grupo de Idade 85-89", "Grupo de Idade 90-94", "Grupo de Idade 95-99", "Grupo de Idade 100+");
    
//     $escalao = array("CAD","JUN", "S23", "SEN", "V1", "V2", "V3", "V4");
//     $escalao_extenso = array("Cadetes","Juniores", "Sub-23", "Seniores", "Veteranos I", "Veteranos II", "Veteranos III", "Veteranos IV");


//     for($i=0;$i<count($escalao);$i++){
//         $query = mysqli_query($db, "SELECT name, escalao, clube FROM atletas WHERE started >= '5' AND escalao = '".$escalao[$i]."' AND race = 'po' AND sexo = '".$sexo[$j]."' ORDER BY time LIMIT 3");
//         if($query->num_rows > 0) {
//             $pdf->SetFont('Times','B',11);
//             $pdf->Cell(190,14,$escalao_extenso[$i]." ".$genero[$j],0,1,'L');
//             $pdf->SetFont('Times','',11);
//             $pos=1;
//             while($row = mysqli_fetch_array($query)) {
//                 $pdf->Cell(14,6,$pos,1,0,'C',$fill);
//                 $pdf->Cell(60,6,utf8_decode($row['name']),1,0,'L',$fill);
//                 $pdf->Cell(20,6,$row['escalao'],1,0,'C',$fill);
//                 $pdf->Cell(80,6,utf8_decode($row['clube']),1,1,'L',$fill);
//                 $fill=!$fill;
//                 $pos++;
//             }
//         }
//     }
    
//     /***************************************************************************************************************************/
//     /***************************************************************************************************************************/
//     /***************************************************************************************************************************/    
    
//     mysqli_query($db,"TRUNCATE equipas");

//     $querygun = $db->query("SELECT time FROM gunshot WHERE race = '".$race[$j]."'");
//     $rowgun = mysqli_fetch_assoc($querygun);
//     $fill = 0;
//     $pdf->SetFont('Times','B',11);
//     $pdf->Cell(190,14,utf8_decode("EQUIPAS ".$genero[$j]),0,1,'L',$fill);

//     // **** BUSCA OS TRES PRIMEIROS DE CADA EQUIPA **** //
//     $query_clubes = mysqli_query($db, "SELECT clube, licenca FROM atletas WHERE started >= '5' AND race ='po' AND sexo = '".$sexo[$j]."' GROUP BY clube HAVING COUNT(*) >2");
//     if(mysqli_num_rows($query_clubes)>0)
//     {
//         while ($row_clubes = mysqli_fetch_array($query_clubes))
//         {
//             if(($row_clubes['clube']!=="Individual") && ($row_clubes['clube']!=="Não Federado"))             
//             {
//                 $query_tempos = mysqli_query($db,"SELECT dorsal, time, clube, licenca, name, escalao FROM atletas WHERE clube = '".$row_clubes['clube']."' AND started >= '5' AND race = 'po' AND sexo = '".$sexo[$j]."' ORDER BY time LIMIT 3");
//                 $i=1;
//                 while ($row_tempos = mysqli_fetch_array($query_tempos))
//                 {
//                     $tempo_individual = strtotime($row_tempos['time'])-strtotime($rowgun['time']);
//                     if($i==1)
//                     $tempo_equipa = $tempo_individual;
//                     else
//                     $tempo_equipa = $tempo_individual + $tempo_equipa;
//                     mysqli_query($db,"INSERT INTO equipas (dorsal, time, clube, licenca, name, escalao, valida, tempo_equipa) VALUES ('".$row_tempos['dorsal']."','".gmdate('H:i:s',$tempo_individual)."','".$row_clubes['clube']."','".$row_tempos['licenca']."','".$row_tempos['name']."','".$row_tempos['escalao']."','".$i."','".gmdate('H:i:s',$tempo_equipa)."')");
//                     $i++;
//                 }
//             }   
//         }
//     }

//     // **** ORDENAR DO PRIMEIRO PARA O SEGUNDO E MANDAR PARA PDF **** //
    
//     // Equipas - 3 melhores
//     $pdf->SetFont('Times','',11);
//     $pdf->SetTextColor(0);
//     $pdf->SetFillColor(244,244,244);
//     $pos = 1;
//     $fill = false;
//     $linha = 1;

//     $query_tempos = mysqli_query($db, "SELECT clube FROM equipas WHERE valida = '3' ORDER BY tempo_equipa LIMIT 3");
//     if(mysqli_num_rows($query_tempos)>0)
//     {
//         // Percorre apenas as equipas, ordem tempos totais da equipa
//         while($row_tempos = mysqli_fetch_array($query_tempos)) 
//         {
//             // Percorre toda a tabela
//             $query_clubes = mysqli_query($db,"SELECT * FROM equipas WHERE clube = '".$row_tempos['clube']."' ORDER BY valida");
//             while ($row_clubes = mysqli_fetch_array($query_clubes))
//             {
//                 $pdf->Cell(14,6,$pos,1,0,'C',$fill);
//                 if($row_clubes['valida'] == 3)
//                 {
//                     $linha = 0;
//                 }
//                 $pdf->Cell(90,6,utf8_decode($row_clubes['clube']),1,0,'L',$fill);
//                 $pdf->Cell(20,6,$row_clubes['escalao'],1,0,'C',$fill);
//                 $pdf->Cell(50,6,utf8_decode($row_clubes['name']),1,1,'L',$fill);
//             }
//             $linha = 1;
//             $fill=!$fill;
//             $pos++;
//         }
//     }
// }

// $query = mysqli_query($db, "SELECT * FROM atletas WHERE started >= '5' AND race = 'po-est' ORDER BY ttotal LIMIT 1");
// $pdf->SetFont('Times','B',11);
// $fill = 0;
// $pdf->Cell(190,11,utf8_decode("ESTAFETAS "),0,1,'L',$fill);

// $pdf->SetFont('Times','',11);

// $pos = 1;
// if($query->num_rows > 0) 
// {
//     while($row = mysqli_fetch_array($query)) 
//     {        
//         $pdf->Cell(14,6,$pos,1,0,'C',$fill);
//         $pdf->Cell(60,6,utf8_decode($row['name']),1,0,'L',$fill);
//         $pdf->Cell(20,6,$row['escalao'],1,0,'C',$fill);
//         $pdf->Cell(80,6,utf8_decode($row['clube']),1,1,'L',$fill);
//         $fill=!$fill;
//         $pos++;
//     }
// }
$pdf->Output();
?>