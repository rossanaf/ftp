<?php
  //load the database configuration file
  include($_SERVER['DOCUMENT_ROOT'].'/includes/db.php');
  require('fpdf.php');

  class PDF extends FPDF {
    // Page header
    function Header() {
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
      $this->SetFont('Times','B',10);
      $this->SetX(34);
      $this->Cell(52,5,utf8_decode(ucwords($rowrace['race_location'])),0,0,'L',true);
      $this->SetX(110);
      $this->Cell(12,5,utf8_decode($rowrace['race_date']),0,0,'L',true);
      $this->SetX(190);
      $this->Ln(10);
      $this->SetFont('Times','',14);
      $this->Cell(190,8,utf8_decode("Classificaçao Absoluta"),0,0,'C');
      $this->Ln(10);
      $this->SetDrawColor(0);
      $this->SetFillColor(87,87,85);
      $this->SetTextColor(255);
      $this->SetLineWidth(.1);
      $this->SetFont('Times','',10);    
      // Header
      $this->SetX(16);
      $w = array(12, 128, 40); // menos 2+2+4+10+2 = 20
      $header = array('#','Clube','Total Pontos');
      for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],6,utf8_decode($header[$i]),1,0,'C',true);
      $this->Ln();
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
      $this->Cell(0,10,utf8_decode('© Federação de Triatlo de Portugal'),0,0,'L');
      $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'R');
    }
  }

  // Instanciation of inherited class
  $pdf = new PDF();
  $pdf->AliasNbPages();
  $pdf->AddPage('P','A4');
  $pdf->SetFont('Times','',10);
  $pdf->SetTextColor(0);
  $pdf->SetFillColor(244,244,244);
  //TEMPOS DOS GUNS
  $stmtEmpty = $db->prepare('TRUNCATE cjovem; TRUNCATE clubesj');
  $stmtEmpty->execute();
  $stmtEmpty->closeCursor();
  $fill = 0;

  $queryRace = $db->prepare('SELECT race_id FROM races WHERE race_type=?');
  $queryRace->execute(['jEstf']);
  $rowRaces = $queryRace->fetchAll();
  foreach ($rowRaces as $race) {
    for($i=0;$i<2;$i++) {
      $gender = array('F', 'M');
      $queryPoints = $db->prepare('SELECT athlete_team_id FROM athletes WHERE athlete_started>=4 AND athlete_race_id=? AND athlete_sex=? ORDER BY athlete_finishtime ASC');
      $queryPoints->execute([$race['race_id'], $gender[$i]]);
      $rows = $queryPoints->fetchAll();
      $pos = 1;
      foreach ($rows as $row) {
        if (($row['athlete_team_id'] != 1) && ($row['athlete_team_id'] != 2) && ($row['athlete_team_id'] != 3)) {
          if($pos === 1) $pontos = 210;
          elseif ($pos === 2) $pontos = 190;
          elseif ($pos === 3) $pontos = 180;
          elseif ($pos === 4) $pontos = 170;
          elseif ($pos === 5) $pontos = 160;
          elseif ($pos === 6) $pontos = 150;
          elseif ($pos === 7) $pontos = 140;
          elseif ($pos === 8) $pontos = 130;
          elseif ($pos === 9) $pontos = 120;
          elseif ($pos === 10) $pontos = 110;
          elseif ($pos === 11) $pontos = 100;
          elseif ($pos === 12) $pontos = 90;
          elseif ($pos === 13) $pontos = 80;
          elseif ($pos === 14) $pontos = 70;
          elseif ($pos === 15) $pontos = 60;
          elseif ($pos === 16) $pontos = 50;
          elseif ($pos === 17) $pontos = 40;
          elseif ($pos === 18) $pontos = 30;
          elseif ($pos === 19) $pontos = 20;
          elseif ($pos >= 20) $pontos = 10;
          $stmt_team = $db->prepare("INSERT INTO cjovem (clube, pontos) VALUES (?, ?)");
          $stmt_team->execute([$row['athlete_team_id'], $pontos]);
          $pos++;
        }
      }
    }
  }
  $query = 'select teams.team_name, sum(pontos) from cjovem join teams on cjovem.clube=teams.team_id group by clube order by sum(pontos) DESC';
  $stmt = $db->prepare($query);
  $stmt->execute();
  $rows = $stmt->fetchAll();
  $pos = 1;
  foreach ($rows as $row) {
    $pdf->SetX(16);
    $pdf->Cell(12,6,$pos,1,0,'C',$fill);
    $pdf->Cell(128,6,utf8_decode($row['team_name']),1,0,'L',$fill);
    $pdf->Cell(40,6,utf8_decode($row['sum(pontos)']),1,1,'C',$fill);
    $pos++;
    $fill = !$fill;
  }
  $pdf->Output();
?>