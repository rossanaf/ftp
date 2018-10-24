<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	if (loginClass::checkLoginState($db)) {
		include_once ($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	} else {
		include_once ($_SERVER['DOCUMENT_ROOT']."/html/guest.php");
	}
  $raceId = $_GET['raceId'];
  $stmt = $db->prepare('SELECT race_live FROM races WHERE race_id=?');
  $stmt->execute([$raceId]);
  $stmtLive = $stmt->fetch();
  if ($stmtLive['race_live'] == 1) {
?>

<div class="container-fluid" id="resultsContainer">
  <div class="col-md-12">
    <table class="table table-responsive table-bordered table-hover table-sm responsive" id="user_data">
      <thead>
        <tr>
          <th width="2%">POS</th>
          <th width="15%">Firstname</th>
          <th width="15%">Lastname</th>
          <th width="4%">Country</th>
          <th width="4%">Start Number</th>
          <th width="10%">Time</th>
          <th width="10%">Swim</th>
          <th width="10%">T1</th>
          <th width="10%">Bike</th>
          <th width="10%">T2</th>
          <th width="10%">Run</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<script type="text/javascript" language="javascript" >   
	$(document).ready(function(){		
		var dataTable = $('#user_data').DataTable({
      "dom": "ft",
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
      },
      "processing":true,
			"serverSide":true,
      "order": [],
      "columnDefs": [{
        "className": "dt-center", 
        "targets": [0, 4, 3, 5, 6, 7, 8, 9, 10],
      }],
      "ajax":{
  			url:"fetch.php",
  			type:"POST",
        data:{raceId: <?php echo $raceId ?>}
			}
		});
	});    
</script>
<?php } ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/html/info.php"); ?>