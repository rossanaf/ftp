<?php
  function pdfHeader_V($page, $raceId, $gender, $scoreDescription) {
    include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
    $raceId = $_GET['race_id'];
    $queryrace = $db->prepare("SELECT * FROM races WHERE race_id = ? LIMIT 1");
    $queryrace->execute([$raceId]);
    $rowRace = $queryrace->fetch();   
    // Logo
    $page->Image('../images/ftp_logo.png',10,4,56);
    $page->SetFont('Times','B',14);
    $page->SetX(100);
    // Title
    $page->Cell(80,8,utf8_decode(strtoupper($rowRace['race_namepdf'])),0,1,'C');
    $page->SetX(100);
    $page->Cell(80,8,utf8_decode(ucwords($rowRace['race_ranking'])),0,0,'C');
    $page->Ln(24);
    $page->SetDrawColor(255,214,0);
    $page->Line(0,34,80,34);
    $page->SetDrawColor(0,110,38);
    $page->Line(80,34,150,34);
    $page->SetDrawColor(166,16,8);
    $page->Line(150,34,210,34);
    $page->SetLineWidth(.4);
    if ($page->PageNo() == 1) {
      $page->SetFont('Times','',10);
      $page->SetFillColor(255);
      $page->Cell(24,5,utf8_decode("Local da Prova: "),0,0,'L',true);
      $page->SetX(100);
      $page->Cell(16,5,utf8_decode("Data: "),0,0,'L',true);
      $page->SetX(176);
      $page->Cell(8,5,utf8_decode("Hora da Partida: "),0,0,'R',true);
      $page->SetFont('Times','B',10);
      $page->SetX(34);
      $page->Cell(52,5,utf8_decode(ucwords($rowRace['race_location'])),0,0,'L',true);
      $page->SetX(110);
      $page->Cell(12,5,utf8_decode($rowRace['race_date']),0,0,'L',true);
      $page->SetX(190);
      if ($gender === 'F') $page->Cell(10,5,utf8_decode($rowRace['race_gun_f']),0,0,'R',true);
      elseif ($gender === 'M') $page->Cell(10,5,utf8_decode($rowRace['race_gun_m']),0,0,'R',true);
      $segment1 = ucwords($rowRace['race_segment1'])." - ".$rowRace['race_distsegment1'];
      if ($rowRace['race_segment2'] !== 'n.a.') {
        $segment2 = ucwords($rowRace['race_segment2'])." - ".$rowRace['race_distsegment2'];
      }
      $segment3 = ucwords($rowRace['race_segment3'])." - ".$rowRace['race_distsegment3'];
      $page->SetFont('Times','',10);
      $page->Ln(10);
      $page->Cell(20,5,utf8_decode("Distancias: "),0,0,'L',true);
      $page->SetDrawColor(255,214,0);
      $page->Cell(50,5,utf8_decode($segment1),1,0,'C',true);
      if ($rowRace['race_segment2'] !== 'n.a.') {
        $page->SetDrawColor(166,16,8);
        $page->SetX(90);
        $page->Cell(50,5,utf8_decode($segment2),1,0,'C',true);
      }
      $page->SetX(150);
      $page->SetDrawColor(0,110,38);
      $page->Cell(50,5,utf8_decode($segment3),1,0,'C',true);
      $page->Ln(10);
    }
    $page->SetFont('Times','',14);
    if (stripos($rowRace['race_name'],'estafeta') === false) $page->Cell(190,8,utf8_decode($scoreDescription),0,0,'C');
    else $page->Cell(190,8,utf8_decode("Classificações Estafetas"),0,0,'C');
    $page->Ln(10);
    $page->SetDrawColor(0);
    $page->SetFillColor(87,87,85);
    $page->SetTextColor(255);
    $page->SetLineWidth(.1);
    $page->SetFont('Times','',9);   
    // Header
    $page->SetX(10);
    if (($rowRace['race_type'] === 'cre') || ($rowRace['race_type'] === 'jEstf')) {
      $w = array(8, 12, 10, 40, 10, 56, 18, 18, 18); // menos 2+2+4+10+2 = 20
      $header = array('#','Lic.','Dors.','Nome','Esc.','Equipa','T.Ind','T.Equipa','Diff.');
    } elseif (stripos($scoreDescription,'equipas') > 0) {
      $w = array(6, 14, 12, 40, 10, 52, 19, 19, 18); // menos 2+2+4+10+2 = 20
      $header = array('#','Lic.','Dors.','Nome','Esc.','Equipa','T.Ind','T.Equipa','Diff.');
    } else {
      $w = array(8, 14, 10, 44, 6, 10, 58, 20, 20); // menos 2+2+4+10+2 = 20
      $header = array('#','Lic.','Dors.','Nome','Gen','Esc.','Equipa','T.Total', 'Diff.');
    }
    for($i=0;$i<count($header);$i++)
      $page->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
    $page->Ln();
  }

  function pdfHeader_H($page, $raceId, $gender, $scoreDescription, $times) {
    include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
    $raceId = $_GET['race_id'];
    $queryrace = $db->prepare("SELECT * FROM races WHERE race_id = ? LIMIT 1");
    $queryrace->execute([$raceId]);
    $rowRace = $queryrace->fetch();   
    // Logo
    $page->Image('../images/ftp_logo.png',10,4,56);
    $page->SetFont('Times','B',14);
    $page->SetX(100);
    // Title
    $page->Cell(240,8,utf8_decode(strtoupper($rowRace['race_namepdf'])),0,1,'C');
    $page->SetX(100);
    $page->Cell(240,8,utf8_decode(ucwords($rowRace['race_ranking'])),0,0,'C');
    $page->Ln(24);
    $page->SetDrawColor(255,214,0);
    $page->Line(0,34,99,34);
    $page->SetDrawColor(0,110,38);
    $page->Line(99,34,198,34);
    $page->SetDrawColor(166,16,8);
    $page->Line(198,34,297,34);
    $page->SetLineWidth(.4);
    if ($page->PageNo() == 1) {
      $page->SetFont('Times','',10);
      $page->SetFillColor(255);
      $page->SetX(12);
      $page->Cell(16,4,utf8_decode("Local da Prova: "),0,0,'L',true);
      $page->SetX(138);
      $page->Cell(16,4,utf8_decode("Data: "),0,0,'L',true);
      $page->SetX(235);
      $page->Cell(16,4,utf8_decode("Hora da Partida: "),0,0,'R',true);
      $page->SetFont('Times','B',10);
      $page->SetX(36);
      $page->Cell(52,4,utf8_decode(ucwords($rowRace['race_location'])),0,0,'L',true);
      $page->SetX(147);
      $page->Cell(12,4,utf8_decode($rowRace['race_date']),0,0,'L',true);
      $page->SetX(256);
      if ($gender === 'F') $page->Cell(10,5,utf8_decode($rowRace['race_gun_f']),0,0,'R',true);
      elseif ($gender === 'M') $page->Cell(10,5,utf8_decode($rowRace['race_gun_m']),0,0,'R',true);
      $segment1 = ucwords($rowRace['race_segment1'])." - ".$rowRace['race_distsegment1'];
      if ($rowRace['race_segment2'] !== 'n.a.') {
          $segment2 = ucwords($rowRace['race_segment2'])." - ".$rowRace['race_distsegment2'];
      }
      $segment3 = ucwords($rowRace['race_segment3'])." - ".$rowRace['race_distsegment3'];
      $page->SetFont('Times','',10);
      $page->Ln(10);
      $page->SetX(12);
      $page->Cell(20,5,utf8_decode("Distancias: "),0,0,'L',true);
      $page->SetDrawColor(255,214,0);
      $page->Cell(50,5,utf8_decode($segment1),1,0,'C',true);
      if ($rowRace['race_segment2'] !== 'n.a.') {
        $page->SetDrawColor(166,16,8);
        $page->SetX(126);
        $page->Cell(50,5,utf8_decode($segment2),1,0,'C',true);
      }
      $page->SetX(220);
      $page->SetDrawColor(0,110,38);
      $page->Cell(50,5,utf8_decode($segment3),1,0,'C',true);
      $page->Ln(10);
    }
    $page->SetFont('Times','',14);
    if (stripos($rowRace['race_name'],'estafeta') === false) $page->Cell(280,8,utf8_decode($scoreDescription),0,0,'C');
    else $page->Cell(280,8,utf8_decode("Classificações Estafetas"),0,0,'C');
    $page->Ln(10);
    $page->SetDrawColor(0);
    $page->SetFillColor(87,87,85);
    $page->SetTextColor(255);
    $page->SetLineWidth(.1);
    $page->SetFont('Times','',9);
    // Header
    if ($times === 3) {
      $page->SetX(16);
      if ($rowRace['race_type'] === 'cre') {
        $w = array(8, 12, 12, 48, 8, 64, 18, 18, 18, 18, 18, 18);
        $header = array('#','Lic.','Dors.','Nome','Esc.','Equipa',$rowRace['race_segment1'],'Ciclismo','Corrida','T.Ind.','T.Equipa','Diff.');
        for($i=0;$i<count($header);$i++)
          $page->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
      } else {
        $w = array(8, 14, 12, 50, 10, 66, 20, 20, 20, 20, 20);
        $header = array('#','Lic.','Dors.','Nome','Esc.','Equipa',$rowRace['race_segment1'],'Ciclismo','Corrida','T.Total','Diff.');
        for($i=0;$i<count($header);$i++)
          $page->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
      }
      $page->Ln();
    } elseif ($times === 5) {
      $page->SetX(12);
      if($rowRace['race_type']==='cre') {
        $w = array(6, 12, 12, 42, 8, 64, 16, 16, 16, 16, 16, 16, 16, 16);
        $header = array('#','Lic.','Dors.','Nome','Esc.','Equipa',$rowRace['race_segment1'],'T1','Ciclismo','T2','Corrida','T.Ind.','T.Equipa','Diff.');
        for($i=0;$i<count($header);$i++)
          $page->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
        $page->Ln();
      } else {
        $w = array(6, 14, 12, 40, 10, 56, 20, 20, 20, 20, 20, 20, 18);
        $header = array('#','Lic.','Dors.','Nome','Esc.','Equipa',$rowRace['race_segment1'],'T1','Ciclismo','T2','Corrida','T.Total','Diff.');
        for($i=0;$i<count($header);$i++)
          $page->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
        $page->Ln();
      }
    }
  }

  function pdfHeaderItuMxRelay($page, $raceId, $gender, $scoreDescription) {
    include($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
    $raceId = $_GET['race_id'];
    $queryrace = $db->prepare("SELECT * FROM races WHERE race_id = ? LIMIT 1");
    $queryrace->execute([$raceId]);
    $rowRace = $queryrace->fetch();   
    // Logo
    $page->Image('../images/ftp_logo.png',10,4,56);
    $page->SetFont('Times','B',14);
    $page->SetX(100);
    // Title
    $page->Cell(80,8,utf8_decode(strtoupper($rowRace['race_namepdf'])),0,1,'C');
    $page->SetX(100);
    $page->Cell(80,8,utf8_decode(ucwords($rowRace['race_ranking'])),0,0,'C');
    $page->Ln(24);
    $page->SetDrawColor(255,214,0);
    $page->Line(0,34,80,34);
    $page->SetDrawColor(0,110,38);
    $page->Line(80,34,150,34);
    $page->SetDrawColor(166,16,8);
    $page->Line(150,34,210,34);
    $page->SetLineWidth(.4);
    if ($page->PageNo() == 1) {
      $page->SetFont('Times','',10);
      $page->SetFillColor(255);
      $page->Cell(24,5,utf8_decode("Location: "),0,0,'L',true);
      $page->SetX(100);
      $page->Cell(16,5,utf8_decode("Date: "),0,0,'L',true);
      $page->SetX(176);
      $page->Cell(8,5,utf8_decode("Start Time: "),0,0,'R',true);
      $page->SetFont('Times','B',10);
      $page->SetX(34);
      $page->Cell(52,5,utf8_decode(ucwords($rowRace['race_location'])),0,0,'L',true);
      $page->SetX(110);
      $page->Cell(12,5,utf8_decode($rowRace['race_date']),0,0,'L',true);
      $page->SetX(190);
      if ($gender === 'F') $page->Cell(10,5,utf8_decode($rowRace['race_gun_f']),0,0,'R',true);
      elseif ($gender === 'M') $page->Cell(10,5,utf8_decode($rowRace['race_gun_m']),0,0,'R',true);
      $segment1 = ucwords($rowRace['race_segment1'])." - ".$rowRace['race_distsegment1'];
      if ($rowRace['race_segment2'] !== 'n.a.') {
        $segment2 = ucwords($rowRace['race_segment2'])." - ".$rowRace['race_distsegment2'];
      }
      $segment3 = ucwords($rowRace['race_segment3'])." - ".$rowRace['race_distsegment3'];
      $page->SetFont('Times','',10);
      $page->Ln(10);
      $page->Cell(20,5,utf8_decode("Distances: "),0,0,'L',true);
      $page->SetDrawColor(255,214,0);
      $page->Cell(50,5,'Swim - ',1,0,'C',true);
      if ($rowRace['race_segment2'] !== 'n.a.') {
        $page->SetDrawColor(166,16,8);
        $page->SetX(90);
        $page->Cell(50,5,'Bike - ',1,0,'C',true);
      }
      $page->SetX(150);
      $page->SetDrawColor(0,110,38);
      $page->Cell(50,5,'Run - ',1,0,'C',true);
      $page->Ln(10);
    }
    $page->SetFont('Times','',14);
    if (stripos($rowRace['race_name'],'estafeta') === false) $page->Cell(190,8,utf8_decode($scoreDescription),0,0,'C');
    else $page->Cell(190,8,utf8_decode("Classificações Estafetas"),0,0,'C');
    $page->Ln(10);
    $page->SetDrawColor(0);
    $page->SetFillColor(87,87,85);
    $page->SetTextColor(255);
    $page->SetLineWidth(.1);
    $page->SetFont('Times','',9);   
    // Header
    $page->SetX(10);
    $w = array(6, 48, 14, 6, 20, 20, 20, 20, 20, 20); // menos 2+2+4+10+2 = 20
    $header = array('#','Team','Country', 'No.', 'Leg 1','Leg 2','Leg 3','Leg 4','Time','Diff.');
    for($i=0;$i<count($header);$i++)
      $page->Cell($w[$i],5,utf8_decode($header[$i]),1,0,'C',true);
    $page->Ln();
  } 
?>