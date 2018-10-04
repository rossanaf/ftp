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
          <th width="1%">POS</th>
          <th width="20%">Name</th>
          <th width="20%">Team</th>
          <th width="6%">Country</th>
          <th width="3%">Start No</th>
          <th width="6%">Swim</th>
          <th width="6%">T1</th>
          <th width="6%">Bike</th>
          <th width="6%">T2</th>
          <th width="6%">Run</th>
          <th width="6%">Time</th>
          <th width="6%">Team Time</th>
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
        "targets": [0, 3, 4, 5, 6, 7, 8, 9, 10, 11],
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