<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php"); 
	if (loginClass::checkLoginState($db))
	{
		include_once ($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
		exit();
	}
	$stmt = $db->prepare("SELECT race_namepdf, race_ranking, race_segment1, race_distsegment1, race_segment2, race_distsegment2, race_segment3, race_distsegment3 FROM races LIMIT 1");
	$stmt->execute();
	$race = $stmt->fetch();
  $live_stmt = $db->prepare('SELECT race_name, race_id FROM races WHERE race_live = "1"');
  $live_stmt->execute();
  $liveRaces = $live_stmt->fetchAll();
  $isLive = 0;
?>
<!--   <div class="collapse" id="navbarToggleExternalContent">
    <div class="bg-dark p-4">
    	<div class="movedown">
      <a href="/"><img class="navbar-brand" src="/images/ftp_navbar.png" height="38px"></a>
        <ul class="navbar-nav mr-auto">       
            <li class="nav-item">
                <a class="nav-link" href="/resultsmxelite/">Results ELITE</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/resultsmxjunior/">Results JUNIOR</a>
            </li>
  		</ul>
  		</div>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
    	<a href="/"><img class="navbar-brand" src="/images/ftp_navbar.png" height="38px"></a>
        <ul class="navbar-nav mr-auto">       
            <li class="nav-item">
                <a class="nav-link" href="/resultsmxelite/">Results ELITE</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/resultsmxjunior/">Results JUNIOR</a>
            </li>
  		</ul>
	</div>
  </nav> -->

<div id="carouselExampleIndicators" class="carousel slide d-none d-lg-block" data-ride="carousel">
	<div class="carousel-inner">
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide08.jpg" alt="Second slide">
		</div>
		<div class="carousel-item active">
			<img class="d-block w-100" src="/images/slider/slide01.jpg" alt="First slide">
		</div>
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide02.jpg" alt="Second slide">
		</div>
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide03.jpg" alt="Second slide">
		</div>
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide04.jpg" alt="Second slide">
		</div>
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide05.jpg" alt="Second slide">
		</div>
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide06.jpg" alt="Second slide">
		</div>
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide07.jpg" alt="Second slide">
		</div>
	</div>
</div>
<div id="mainPage">
	<a href="/resultsmxelite/" class="btn btn btn-outline-success btn-lg btn-block" role="button" aria-pressed="true">ELITE MxRelay Triathlon Lisbon <img width="24px" src="/images/refresh.png"/></a>
	<a href="/resultsmxjunior/" class="btn btn btn-outline-success btn-lg btn-block" role="button" aria-pressed="true">JUNIOR MxRelay Triathlon Lisbon <img width="24px" src="/images/refresh.png"/></a>
</div>