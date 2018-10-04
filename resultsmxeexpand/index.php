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
          <th class="details-control" width="1%"></th>
          <th width="1%">POS</th>
          <th width="20%">Team</th>
          <th width="6%">Country</th>
          <th width="3%">Start No</th>
          <!-- <th width="6%">Leg 1</th>
          <th width="6%">Leg 2</th>
          <th width="6%">Leg 3</th>
          <th width="6%">Leg 4</th>
          <th width="6%">Run</th>
          <th width="6%">Time</th> -->
        </tr>
      </thead>
    </table>
  </div>
</div>
<script type="text/javascript" language="javascript" >   
  function format(d) {
    return 
      '<table>'+
        '<tr>'+
          '<td>Full Name</td>'+
          '<td>'+d.bib+'</td>'+
        '</tr>'+
      '</table>';
  }

  $(document).ready(function() {    
    var dataTable = $('#user_data').DataTable({
      "dom": "ft",
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
      },
      "processing": true,
      "serverSide": true,
      "columns": [
        {
          "className": "details-control",
          "orderable": "false",
          "data": null,
          "defaultContent": ''  
        },
        { "className": "dt-center", "data": "pos" },
        { "data": "team" },
        { "className": "dt-center", "data": "country" },
        { "className": "dt-center", "data": "bib" },
      ],
      "order": [],
      "ajax":{
  			url:"fetch.php",
  			type:"POST",
        data:{raceId: <?php echo $raceId ?>}
			}
		});
	}); 
  $('#user_data tbody').on('click', 'details-control', function() {
    alert('clicked');
    // var tr = $(this).closest('tr');
    // var row = table.row(tr);
    // if(row.child.isShown()) {
    //   $('div.slider', row.child()).slideUp(function() {
    //     row.child.hide();
    //     tr.removeClass('shown');
    //   });
    // } else {
    //   row.child(format(row.data()), 'no-padding').show();
    //   tr.addClass('shown');
    //   $('div.slider', row.child()).slideDown();
    // }
  });   
</script>
<?php } ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/html/info.php"); ?>