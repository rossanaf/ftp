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
          <th width="1%"></th>
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
<div class="modal fade gunModal bd-example-modal-lg" tabindex="-1" role="dialog" id="gunModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body"></div>
    </div>
  </div>
</div>
<script type="text/javascript" language="javascript" >   
  $(document).ready(function() {    
    var dataTable = $('#user_data').DataTable({
      "dom": "ft",
      "processing": true,
      "serverSide": true,
      "columnDefs": [{
        "className": "dt-center",
        "targets": [0,1,3,4]
      }],
      "order": [],
      "ajax":{
  			url:"fetch.php",
  			type:"POST",
        data:{raceId: <?php echo $raceId ?>}
			}
		});
  }); 
      //**** ATUALIZAR GUNSHOTS PROVAS ****//
    $(document).on('click', '.guns', function(){
      var gun_id = $(this).attr("id");
      var infoModal = $('#gunModal');
      $.ajax({
        url:"fetchLeg.php",
        method:"POST",
        data:{raceId:gun_id},
        dataType:"json",
        success:function(data){
          htmlData = '<table class="table table-responsive responsive">'+
          '<thead><tr><th>Name</th>'+
          '<th>Time</th>'+
          '<th>Swim</th>'+
          '<th>T1</th>'+
          '<th>Bike</th>'+
          '<th>T2</th>'+
          '<th>Run</th>'+
          '</tr></thead>'+
          '<tbody><tr><td>'+data.name1+' '+data.lastname1+'</td>'+
          '<td><b>'+data.leg1+'</b></td>'+
          '<td>'+data.t11+'</td>'+
          '<td>'+data.t12+'</td>'+
          '<td>'+data.t13+'</td>'+
          '<td>'+data.t14+'</td>'+
          '<td>'+data.t15+'</td>'+
          ' </tbody></table>';
          infoModal.find('.modal-title').html(data.teamFlag);
          infoModal.find('.modal-body').html(htmlData);
          infoModal.modal('show');
          // $('#pos').val(data.pos);
          // $('#gunModal').modal('show');
          // $('.modal-title').text("Inserir Horas dos GUNs");
          // $('#action').val("Guardar Alterações");
          // $('#operation').val("Edit");
        }
      })
    });
</script>
<?php } ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/html/info.php"); ?>