<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	if (loginClass::checkLoginState($db)) {
		include_once ($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	} else {
		include_once ($_SERVER['DOCUMENT_ROOT']."/html/guest.php");
	}
?>
<div class="container-fluid" id="resultsContainer">
  <div class="col-md-12">
    <h3><center>ELITE</center></h3>
    <table class="table table-responsive table-bordered table-hover table-sm responsive" id="user_data">
      <thead>
        <tr>
          <th width="1%"></th>
          <th width="1%">POS</th>
          <th width="30%">Team</th>
          <th width="6%">Country</th>
          <th width="1%">Start No</th>
          <th width="6%">Leg 1</th>
          <th width="6%">Leg 2</th>
          <th width="6%">Leg 3</th>
          <th width="6%">Leg 4</th>
          <th width="6%">Time</th>
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
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/fh-3.1.3/r-2.2.1/datatables.min.js"></script>
<script type="text/javascript" language="javascript" >   
  $(document).ready(function() {    
    var dataTable = $('#user_data').DataTable({
      "dom": "ft",
      "processing": true,
      "serverSide": true,
      searching: false,
      "columnDefs": [{
        "className": "dt-center",
        "targets": [0,1,3,4,5,6,7,8,9]
      }],
      "order": [],
      "ajax":{
  			url:"fetch.php",
  			type:"POST",
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
          '<th>Start No.</th>'+
          '<th>Time</th>'+
          '<th>Swim</th>'+
          '<th>T1</th>'+
          '<th>Bike</th>'+
          '<th>T2</th>'+
          '<th>Run</th>'+
          '</tr></thead>'+
          '<tbody>'+
          '<tr><td>'+data.name1+'</td>'+
          '<td>'+data.no1+'</td>'+
          '<td><b>'+data.leg1+'</b></td>'+
          '<td>'+data.t11+'</td>'+
          '<td>'+data.t21+'</td>'+
          '<td>'+data.t31+'</td>'+
          '<td>'+data.t41+'</td>'+
          '<td>'+data.t51+'</td></tr>'+
          '<tr><td>'+data.name2+'</td>'+
          '<td>'+data.no2+'</td>'+
          '<td><b>'+data.leg2+'</b></td>'+
          '<td>'+data.t12+'</td>'+
          '<td>'+data.t22+'</td>'+
          '<td>'+data.t32+'</td>'+
          '<td>'+data.t42+'</td>'+
          '<td>'+data.t52+'</td></tr>'+
          '<tr><td>'+data.name3+'</td>'+
          '<td>'+data.no3+'</td>'+
          '<td><b>'+data.leg3+'</b></td>'+
          '<td>'+data.t13+'</td>'+
          '<td>'+data.t23+'</td>'+
          '<td>'+data.t33+'</td>'+
          '<td>'+data.t43+'</td>'+
          '<td>'+data.t53+'</td></tr>'+
          '<tr><td>'+data.name4+'</td>'+
          '<td>'+data.no4+'</td>'+
          '<td><b>'+data.leg4+'</b></td>'+
          '<td>'+data.t14+'</td>'+
          '<td>'+data.t24+'</td>'+
          '<td>'+data.t34+'</td>'+
          '<td>'+data.t44+'</td>'+
          '<td>'+data.t54+'</td></tr>'+
          '</tbody></table>';
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
<?php 
// include($_SERVER['DOCUMENT_ROOT']."/html/info.php");
  include($_SERVER['DOCUMENT_ROOT']."/html/footer.php"); 
?>