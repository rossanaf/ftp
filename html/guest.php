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
  $live_races = $live_stmt->fetchAll();
  $isLive = 0;
?>
  <div class="collapse" id="navbarToggleExternalContent">
    <div class="bg-dark p-4">
      <a href="/"><img class="navbar-brand" src="/images/ftp_navbar.png" height="38px"></a>
        <ul class="navbar-nav mr-auto">
		      <?php 
            foreach ($live_races as $live): 
              $isLive = 1;
          ?>          
            <li class="nav-item">
                <a class="nav-link" href="/resultsm/index.php?raceId=<?=$live['race_id']?>"><?php echo $live['race_name'].' Masc' ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/resultsf/index.php?raceId=<?=$live['race_id']?>"><?php echo $live['race_name'].' Fem' ?></a>
            </li>
          <?php 
            endforeach; 
            if ($isLive === 1) {
          ?>
            <li class="nav-item">
                <a class="nav-link" href="/live">Tempos LIVE</a>
            </li>
          <?php } ?>
		    </ul>
		<!-- <ul class="collapse navbar-collapse navbar-nav justify-content-end"> -->
		<!-- <ul class="navbar-nav justify-content-end">
			<li class="nav-item">
				<a class="nav-login" href="/html/login.php">Login</a>
			</li>
		</ul> -->
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
    	<a href="/"><img class="navbar-brand" src="/images/ftp_navbar.png" height="38px"></a>
        <ul class="navbar-nav mr-auto">
          <?php 
            foreach ($live_races as $live): 
              $isLive = 1;
          ?>          
            <li class="nav-item">
                <a class="nav-link" href="/resultsm/index.php?raceId=<?=$live['race_id']?>"><?php echo $live['race_name'].' Masc' ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/resultsf/index.php?raceId=<?=$live['race_id']?>"><?php echo $live['race_name'].' Fem' ?></a>
            </li>
          <?php 
            endforeach; 
            if ($isLive === 1) {
          ?>
    		    <li class="nav-item">
    		        <a class="nav-link" href="/live">Tempos LIVE</a>
    		    </li>
          <?php } ?>
  		</ul>
		<!-- <ul class="collapse navbar-collapse navbar-nav justify-content-end">
		<ul class="navbar-nav justify-content-end">
			<li class="nav-item">
				<a class="nav-login" href="/html/login.php">Login</a>
			</li>
		</ul> -->
	</div>
  </nav>

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner">
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide08.jpg" alt="Second slide">
		</div>
		<div class="carousel-item active">
			<img class="d-block w-100" src="/images/slider/slide01.jpg" alt="First slide">
			<div class="carousel-caption d-none d-md-block" style="line-height: 80px; letter-spacing: 2px; font-size: 80px; text-color: #F0F8FF; text-shadow: 3px 3px 6px #073E64;">
			    	<?php
			    		echo $race["race_namepdf"]."<br>";
			    	?>
		  	</div>
		</div>
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide02.jpg" alt="Second slide">
			<div class="carousel-caption d-none d-md-block" style="line-height: 80px; letter-spacing: 2px; font-size: 60px; text-color: #F0F8FF; text-shadow: 3px 3px 6px #073E64;">
			    	<?php
			    		echo $race["race_ranking"]."<br>";
			    	?>
		  	</div>
		</div>
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide03.jpg" alt="Second slide">
			<div class="carousel-caption d-none d-md-block" style="line-height: 60px; letter-spacing: 2px; font-size: 40px; text-color: #F0F8FF; text-shadow: 3px 3px 6px #073E64;">
			    	<?php
			    		echo $race["race_segment1"]." ".$race["race_distsegment1"]."<br>";
			    		echo $race["race_segment2"]." ".$race["race_distsegment2"]."<br>";
			    		echo $race["race_segment3"]." ".$race["race_distsegment3"]."<br>";
			    	?>
			    	<!-- <?php
			    		echo "Swim ".$race["race_distsegment1"]."<br>";
			    		echo "Bike ".$race["race_distsegment2"]."<br>";
			    		echo "Run ".$race["race_distsegment3"]."<br>";
			    	?> -->
		  	</div>
		</div>
		<div class="carousel-item">
			<img class="d-block w-100" src="/images/slider/slide04.jpg" alt="Second slide">
			<div class="carousel-caption d-none d-md-block" style="line-height: 80px; letter-spacing: 2px; font-size: 60px; text-color: #F0F8FF; text-shadow: 3px 3px 6px #073E64;">
			    	<?php
			    		echo "<br>";
			    	?>
		  	</div>
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
<?php	
	include($_SERVER['DOCUMENT_ROOT']."/html/footer.php"); 
?>