<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	include($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
?>

<div class="container-fluid">
	<h3><center>Editar Provas</center></h3>
  <div class="col-md-12">
    <table class="table table-responsive table-bordered table-hover table-sm" id="user_data">
      <thead>
        <tr>
          <th>Id.</th>
          <th>Nome Startlist</th>
          <th>Prova</th>
          <th>Ranking</th>
          <th>Segmento 1</th>
          <th>Dist. Segm. 1</th>
          <th>Segmento 2</th>
          <th>Dist. Segm. 2</th>
          <th>Segmento 3</th>
          <th>Dist. Segm. 3</th>
          <th>Data</th>
          <th>Local</th>
          <th>Live</th>
          <th width="1%"></th>
          <th width="1%"></th>
          <th width="1%"></th>
          <th width="1%"></th>
          <th width="1%"></th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>Id.</th>
          <th>Nome Startlist</th>
          <th>Prova</th>
          <th>Ranking</th>
          <th>Segmento 1</th>
          <th>Dist. Segm. 1</th>
          <th>Segmento 2</th>
          <th>Dist. Segm. 2</th>
          <th>Segmento 3</th>
          <th>Dist. Segm. 3</th>
          <th>Data</th>
          <th>Local</th>
          <th>Live</th>
          <th width="1%"></th>
          <th width="1%"></th>
          <th width="1%"></th>
          <th width="1%"></th>
          <th width="1%"></th>
        </tr>
      </tfoot>
    </table>
  </div>
  <?php if ($_COOKIE['userid'] < 4) { ?>
    <div align="right">
      <button type="button" class="btn btn-default btn-lg">
      	<a href="../functions/deletedb.php" onclick="return confirm('Este processo é irreversível. Tem a certeza que deseja eliminar todas as provas?');">Eliminar Todas as Provas</a>
      </button>
    </div>
  <?php } ?>
</div>

<!-- EDITAR PROVAS ADULTOS TRIATLO / DUATLO -->
<div class="modal fade" tabindex="-1" role="dialog" id="userModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	<span aria-hidden="true">&times;</span>
        </button>
    	</div>
    	<form method="POST" id="user_form" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group row">
            <div class="col-sm-3">
              <input type="text" name="id" id="id" class="form-control" disabled />
            </div>
            <div class="col-sm-9">
            	<input type="text" name="name" id="name" class="form-control" disabled />
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-3">
              <input type="text" name="date" id="date" class="form-control" placeholder="data" />
            </div>
            <div class="col-sm-9">
              <input type="text" name="location" id="location" class="form-control" placeholder="Local da Prova" />
            </div>
          </div>
          <div class="form-group row">
            <label for="namepdf" class="col-sm-2 col-form-label">Prova:</label>
            <div class="col-sm-10">
              <input type="text" name="namepdf" id="namepdf" class="form-control" placeholder="Prova"/>
            </div>
          </div>
          <div class="form-group row">
            <label for="ranking" class="col-sm-2 col-form-label">Ranking:</label>
            <div class="col-sm-10">
              <input type="text" name="ranking" id="ranking" class="form-control" placeholder="Ranking"/>
            </div>
          </div>
          <div class="form-group row">
            <div class="custom-control custom-checkbox">
              <div class="col-sm-12">
                <input type="checkbox" class="custom-control-input" name='live' id="live" />
                <label class="custom-control-label" for="live">Resultados Live</label>
              </div>
            </div>
          </div>
          <hr>
          <h5><center>Distâncias</center></h5>
					<div class="form-group row">
          	<label class="col-sm-2 col-form-label">Segm. 1:</label>
            <div class="col-sm-3" id="segment1">
              <label class="radio-inline"><input type="radio" name="segment1" value="Natação">
                <img src="../images/swim.jpg" width="30%">
              </label>
              <label class="radio-inline"><input type="radio" name="segment1" value="Corrida">
                <img src="../images/run.jpg" width="30%">
              </label>
           	</div>
           	<div class="col-sm-7">
              <input type="text" name="distsegment1" id="distsegment1" class="form-control" placeholder="distância segmento 1" />
            </div>
          </div>
          <div class="form-group row">
          	<label class="col-sm-2 col-form-label">Segm. 2:</label>
          	<div class="col-sm-3">
            	<input type="text" name="segment2" id="segment2" class="form-control" disabled />
            </div>
            <div class="col-sm-7">
              <input type="text" name="distsegment2" id="distsegment2" class="form-control" placeholder="distância segmento 2"/>
            </div>
          </div>
          <div class="form-group row">
          	<label class="col-sm-2 col-form-label">Segm. 3:</label>
          	<div class="col-sm-3">
            	<input type="text" name="segment3" id="segment3" class="form-control" disabled />
            </div>
            <div class="col-sm-7">
              <input type="text" name="distsegment3" id="distsegment3" class="form-control" placeholder="distância segmento 3"/>
            </div>
      	  </div>
        </div>
        <div class="modal-footer">
					<input type="hidden" name="user_id" id="user_id" />
					<input type="hidden" name="operation" id="operation" />
					<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar sem Guardar</button>
			  </div>
      </form>
	  </div>
  </div>
</div>

<!-- EDITAR PROVAS JOVENS TRIATLO / DUATLO -->
<div class="modal fade" tabindex="-1" role="dialog" id="youthModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
		        <h5 class="modal-title" id="exampleyouthLabel"></h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
	      	</div>
	      	<form method="POST" id="youth_form" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group row">
	                    <div class="col-sm-3">
		                    <input type="text" name="youth_id" id="youth_id" class="form-control" disabled />
	                    </div>
	                    <div class="col-sm-9">
	                    	<input type="text" name="youthname" id="youthname" class="form-control" disabled />
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <div class="col-sm-3">
		                    <input type="text" name="youthdate" id="youthdate" class="form-control" placeholder="data" />
	                    </div>
	                    <div class="col-sm-9">
		                    <input type="text" name="youthlocation" id="youthlocation" class="form-control" placeholder="Local da Prova" />
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label for="namepdf" class="col-sm-2 col-form-label">Prova:</label>
	                    <div class="col-sm-10">
		                    <input type="text" name="youthnamepdf" id="youthnamepdf" class="form-control" placeholder="Prova"/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label for="ranking" class="col-sm-2 col-form-label">Ranking:</label>
	                    <div class="col-sm-10">
		                    <input type="text" name="youthranking" id="youthranking" class="form-control" placeholder="Ranking"/>
	                    </div>
	                </div>
	                <hr>
	                <h3><center>Distâncias Benjamins</center></h3>
					<div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 1:</label>
	                    <div class="col-sm-3" id="segment1">
	                        <label class="radio-inline"><input type="radio" name="youths1ben" value="Natação"> <img src="../images/swim.jpg" width="30%"></label>
	                        <label class="radio-inline"><input type="radio" name="youths1ben" value="Corrida"> <img src="../images/run.jpg" width="30%"></label>
	                   	</div>
	                   	<div class="col-sm-7">
		                    <input type="text" name="youthd1ben" id="youthd1ben" class="form-control" placeholder="distância segmento 1"/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 2:</label>
	                	<div class="col-sm-3">
	                    	<input type="text" name="youths2ben" id="youths2ben" class="form-control" disabled />
	                    </div>
	                    <div class="col-sm-7">
		                    <input type="text" name="youthd2ben" id="youthd2ben" class="form-control" placeholder="distância segmento 2"/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 3:</label>
	                	<div class="col-sm-3">
	                    	<input type="text" name="youths3ben" id="youths3ben" class="form-control" disabled />
	                    </div>
	                    <div class="col-sm-7">
		                    <input type="text" name="youthd3ben" id="youthd3ben" class="form-control" placeholder="distância segmento 3"/>
	                    </div>
	            	</div>
	            	<h3><center>Distâncias Infantis</center></h3>
					<div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 1:</label>
	                    <div class="col-sm-3" id="segment1">
	                        <label class="radio-inline"><input type="radio" name="youths1inf" value="Natação"> <img src="../images/swim.jpg" width="30%"></label>
	                        <label class="radio-inline"><input type="radio" name="youths1inf" value="Corrida"> <img src="../images/run.jpg" width="30%"></label>
	                   	</div>
	                   	<div class="col-sm-7">
		                    <input type="text" name="youthd1inf" id="youthd1inf" class="form-control" placeholder="distância segmento 1"/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 2:</label>
	                	<div class="col-sm-3">
	                    	<input type="text" name="youths2inf" id="youths2inf" class="form-control" disabled />
	                    </div>
	                    <div class="col-sm-7">
		                    <input type="text" name="youthd2inf" id="youthd2inf" class="form-control" placeholder="distância segmento 2"/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 3:</label>
	                	<div class="col-sm-3">
	                    	<input type="text" name="youths3inf" id="youths3inf" class="form-control" disabled />
	                    </div>
	                    <div class="col-sm-7">
		                    <input type="text" name="youthd3inf" id="youthd3inf" class="form-control" placeholder="distância segmento 3"/>
	                    </div>
	            	</div>
	            	<h3><center>Distâncias Iniciados</center></h3>
					<div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 1:</label>
	                    <div class="col-sm-3" id="segment1">
	                        <label class="radio-inline"><input type="radio" name="youths1ini" value="Natação"> <img src="../images/swim.jpg" width="30%"></label>
	                        <label class="radio-inline"><input type="radio" name="youths1ini" value="Corrida"> <img src="../images/run.jpg" width="30%"></label>
	                   	</div>
	                   	<div class="col-sm-7">
		                    <input type="text" name="youthd1ini" id="youthd1ini" class="form-control" placeholder="distância segmento 1"/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 2:</label>
	                	<div class="col-sm-3">
	                    	<input type="text" name="youths2ini" id="youths2ini" class="form-control" disabled />
	                    </div>
	                    <div class="col-sm-7">
		                    <input type="text" name="youthd2ini" id="youthd2ini" class="form-control" placeholder="distância segmento 2"/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 3:</label>
	                	<div class="col-sm-3">
	                    	<input type="text" name="youths3ini" id="youths3ini" class="form-control" disabled />
	                    </div>
	                    <div class="col-sm-7">
		                    <input type="text" name="youthd3ini" id="youthd3ini" class="form-control" placeholder="distância segmento 3"/>
	                    </div>
	            	</div>
	            	<h3><center>Distâncias Juvenis</center></h3>
					<div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 1:</label>
	                    <div class="col-sm-3" id="segment1">
	                        <label class="radio-inline"><input type="radio" name="youths1juv" value="Natação"> <img src="../images/swim.jpg" width="30%"></label>
	                        <label class="radio-inline"><input type="radio" name="youths1juv" value="Corrida"> <img src="../images/run.jpg" width="30%"></label>
	                   	</div>
	                   	<div class="col-sm-7">
		                    <input type="text" name="youthd1juv" id="youthd1juv" class="form-control" placeholder="distância segmento 1"/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 2:</label>
	                	<div class="col-sm-3">
	                    	<input type="text" name="youths2juv" id="youths2juv" class="form-control" disabled />
	                    </div>
	                    <div class="col-sm-7">
		                    <input type="text" name="youthd2juv" id="youthd2juv" class="form-control" placeholder="distância segmento 2"/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                	<label class="col-sm-2 col-form-label">Segm. 3:</label>
	                	<div class="col-sm-3">
	                    	<input type="text" name="youths3juv" id="youths3juv" class="form-control" disabled />
	                    </div>
	                    <div class="col-sm-7">
		                    <input type="text" name="youthd3juv" id="youthd3juv" class="form-control" placeholder="distância segmento 3"/>
	                    </div>
	            	</div>
	            </div>
	            <div class="modal-footer">
					<input type="hidden" name="youthid" id="youthid" />
					<input type="hidden" name="operation" id="operation" />
					<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar sem Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- INSERIR GUNS PROVA JOVEM -->
<div class="modal fade gunYouthModal" tabindex="-1" role="dialog" id="gunYouthModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel"></h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
	      	</div>
	      	<form method="POST" id="gunsYouthForm" enctype="multipart/form-data">
				<div class="modal-body">
					<center><h5>Benjamins</h5></center>
					<div class="form-group row">
	                	<label for="gunbenf" class="col-sm-3 col-form-label">Femininos:</label>
	                    <div class="col-sm-3">
		                    <input type="text" name="gunbenf" id="gunbenf" class="form-control" placeholder="00:00:00"/>
	                    </div>
	                    <label for="gunbenm" class="col-sm-3 col-form-label">Masculinos:</label>
	                    <div class="col-sm-3">
		                    <input type="text" name="gunbenm" id="gunbenm" class="form-control" placeholder="00:00:00"/>
	                    </div>
	            	</div>
	            	<center><h5>Infantis</h5></center>
					<div class="form-group row">
	                	<label for="guninff" class="col-sm-3 col-form-label">Femininos:</label>
	                    <div class="col-sm-3">
		                    <input type="text" name="guninff" id="guninff" class="form-control" placeholder="00:00:00"/>
	                    </div>
	                    <label for="guninfm" class="col-sm-3 col-form-label">Masculinos:</label>
	                    <div class="col-sm-3">
		                    <input type="text" name="guninfm" id="guninfm" class="form-control" placeholder="00:00:00"/>
	                    </div>
	            	</div>
	            	<center><h5>Iniciados</h5></center>
					<div class="form-group row">
	                	<label for="guninif" class="col-sm-3 col-form-label">Femininos:</label>
	                    <div class="col-sm-3">
		                    <input type="text" name="guninif" id="guninif" class="form-control" placeholder="00:00:00"/>
	                    </div>
	                    <label for="guninim" class="col-sm-3 col-form-label">Masculinos:</label>
	                    <div class="col-sm-3">
		                    <input type="text" name="guninim" id="guninim" class="form-control" placeholder="00:00:00"/>
	                    </div>
	            	</div>
	            	<center><h5>Juvenis</h5></center>
					<div class="form-group row">
	                	<label for="gunjuvf" class="col-sm-3 col-form-label">Femininos:</label>
	                    <div class="col-sm-3">
		                    <input type="text" name="gunjuvf" id="gunjuvf" class="form-control" placeholder="00:00:00"/>
	                    </div>
	                    <label for="gunjuvm" class="col-sm-3 col-form-label">Masculinos:</label>
	                    <div class="col-sm-3">
		                    <input type="text" name="gunjuvm" id="gunjuvm" class="form-control" placeholder="00:00:00"/>
	                    </div>
	            	</div>
	            </div>
	            <div class="modal-footer">
					<input type="hidden" name="gun_id" id="gun_id" />
					<input type="hidden" name="operation" id="operation" />
					<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar sem Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- INSERIR GUNS PROVA PROVAS ADULTOS (EXCETO CONTRARRELOGIO E ESTAFETAS MISTAS) -->
<div class="modal fade gunModal" tabindex="-1" role="dialog" id="gunModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" id="guns_form" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group row">
            <label for="gunwmn" class="col-sm-3 col-form-label">Femininos:</label>
            <div class="col-sm-3">
              <input type="text" name="gunwmn" id="gunwmn" class="form-control" placeholder="00:00:00"/>
            </div>
            <label for="gunmen" class="col-sm-3 col-form-label">Masculinos:</label>
            <div class="col-sm-3">
              <input type="text" name="gunmen" id="gunmen" class="form-control" placeholder="00:00:00"/>
            </div>
          </div>
        </div>  
        <div class="modal-footer">
          <input type="hidden" name="gun_id" id="guns_id" />
          <input type="hidden" name="operation" id="operation" />
          <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar sem Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript" language="javascript" >    
	$(document).ready(function(){
		// $('#add_button').click(function(){
		// 	$('#user_form')[0].reset();
		// 	$('.modal-title').text("Criar Prova");
		// 	$('#action').val("Criar");
		// 	$('#operation').val("Add");
		// });
		
		var dataTable = $('#user_data').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
      },
      "paging": false,
      "lengthMenu": [[-1], ["TODOS"]],
      "searching": false,
      "processing":true,
			"serverSide":true,
      "order":[],
      "ajax":{
				url:"fetch.php",
				type:"POST"
			},
      "columnDefs":[{
    		"targets":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17],
    		"orderable":false,
			}],
		});
		
  	//**** SUBMETER NOVA PROVA / ALTERAÇÕES ****//
  	$(document).on('submit', '#user_form', function(event){
	    event.preventDefault();
	    $.ajax({
        url:"insert.php",
        method:'POST',
        data:new FormData(this),
        contentType:false,
        processData:false,
        success:function(data){
          //alert(data);
          $('#user_form')[0].reset();
          $('#userModal').modal('hide');
          location.reload();
          // dataTable.ajax.reload();
        }
	    });
  	});
	
  	//**** ATUALIZAR PROVA ADULTOS TRIATLO / DUATLO ****//
  	$(document).on('click', '.update', function(){
	    var user_id = $(this).attr("id");
	    $.ajax({
        url:"fetchRace.php",
        method:"POST",
        data:{user_id:user_id},
        dataType:"json",
        success:function(data){
          // alert(data.live);
          $('#userModal').modal('show');
          $('#id').val(data.id);
          $('#name').val(data.name);
          $('#namepdf').val(data.namepdf);
          $('#ranking').val(data.ranking);
          if (data.live == 0) $('#live').prop('checked', false)
          else $('#live').prop('checked', true);
          $('#segment1').val(data.segment1);
          $("input[name='segment1'][value='" + data.segment1 + "']").prop('checked', true);
          $('#distsegment1').val(data.distsegment1);
          $('#segment2').val(data.segment2);
          $('#distsegment2').val(data.distsegment2);
          $('#segment3').val(data.segment3);
          $('#distsegment3').val(data.distsegment3);
          $('#date').val(data.date);
          $('#location').val(data.location);
          $('.modal-title').text("Inserir Info da Prova");
          $('#user_id').val(user_id);
          $('#action').val("Guardar Alterações");
          $('#operation').val("Edit");
        }
	    })
  	});

  	//**** SUBMETER ALTERAÇÕES DUATLO/TRIATLO JOVEM ****//
  	$(document).on('submit', '#youth_form', function(event){
	    event.preventDefault();
	    $.ajax({
        url:"insertYouth.php",
        method:'POST',
        data:new FormData(this),
        contentType:false,
        processData:false,
        success:function(data){
          //alert(data);
          $('#youth_form')[0].reset();
          $('#youthModal').modal('hide');
          dataTable.ajax.reload();
        }
	    });
  	});

  	//**** ATUALIZAR PROVA JOVEM ****//
  	$(document).on('click', '.youthupdate', function(){
	    var youthid = $(this).attr("id");
	    $.ajax({
        url:"fetchYouth.php",
        method:"POST",
        data:{youthid:youthid},
        dataType:"json",
        success:function(data){
          $('#youthModal').modal('show');
          $('#youth_id').val(data.youth_id);
          $('#youthname').val(data.youthname);
          $('#youthnamepdf').val(data.youthnamepdf);
          $('#youthranking').val(data.youthranking);
          $('#youths1ben').val(data.youths1ben);
          $("input[name='youths1ben'][value='" + data.youths1ben + "']").prop('checked', true);
          $('#youthd1ben').val(data.youthd1ben);
          $('#youths2ben').val(data.youths2ben);
          $('#youthd2ben').val(data.youthd2ben);
          $('#youths3ben').val(data.youths3ben);
          $('#youthd3ben').val(data.youthd3ben);
          $('#youths1inf').val(data.youths1inf);
          $("input[name='youths1inf'][value='" + data.youths1inf + "']").prop('checked', true);
          $('#youthd1inf').val(data.youthd1inf);
          $('#youths2inf').val(data.youths2inf);
          $('#youthd2inf').val(data.youthd2inf);
          $('#youths3inf').val(data.youths3inf);
          $('#youthd3inf').val(data.youthd3inf);
          $('#youths1ini').val(data.youths1ini);
          $("input[name='youths1ini'][value='" + data.youths1ini + "']").prop('checked', true);
          $('#youthd1ini').val(data.youthd1ini);
          $('#youths2ini').val(data.youths2ini);
          $('#youthd2ini').val(data.youthd2ini);
          $('#youths3ini').val(data.youths3ini);
          $('#youthd3ini').val(data.youthd3ini);
          $('#youths1juv').val(data.youths1juv);
          $("input[name='youths1juv'][value='" + data.youths1juv + "']").prop('checked', true);
          $('#youthd1juv').val(data.youthd1juv);
          $('#youths2juv').val(data.youths2juv);
          $('#youthd2juv').val(data.youthd2juv);
          $('#youths3juv').val(data.youths3juv);
          $('#youthd3juv').val(data.youthd3juv);
          $('#youthdate').val(data.youthdate);
          $('#youthlocation').val(data.youthlocation);
          $('.modal-title').text("Inserir Info da Prova");
          $('#youthid').val(youthid);
          $('#action').val("Guardar Alterações");
          $('#operation').val("Edit");
        }
	    })
  	});

  	//**** SUBMETER ALTERAÇÕES GUNSHOTS PROVA JOVEM ****//
  	$(document).on('submit', '#gunsYouthForm', function(event){
	    event.preventDefault();
	    $.ajax({
        url:"insertYouthGun.php",
        method:'POST',
        data:new FormData(this),
        contentType:false,
        processData:false,
        success:function(data){
          //alert(data);
          $('#guns_form')[0].reset();
          $('#gunYouthModal').modal('hide');
          dataTable.ajax.reload();
        }
	    });
  	});

  	//**** ATUALIZAR GUNSHOTS PROVA JOVEM ****//
  	$(document).on('click', '.gunsYouth', function(){
	    var gun_id = $(this).attr("id");
	    $.ajax({
        url:"fetchYouthGun.php",
        method:"POST",
        data:{gun_id:gun_id},
        dataType:"json",
        success:function(data){
          $('#gunYouthModal').modal('show');
          $('#gunbenf').val(data.gunbenf);
          $('#gunbenm').val(data.gunbenm);
          $('#guninff').val(data.guninff);
          $('#guninfm').val(data.guninfm);
          $('#guninif').val(data.guninif);
          $('#guninim').val(data.guninim);
          $('#gunjuvf').val(data.gunjuvf);
          $('#gunjuvm').val(data.gunjuvm);
          $('.modal-title').text("Inserir Horas GUN da Prova Jovem");
          $('#gun_id').val(gun_id);
          $('#action').val("Guardar Alterações");
          $('#operation').val("Edit");
        }
	    })
  	});

    //**** SUBMETER ALTERAÇÕES GUNSHOTS PROVAS ****//
    $(document).on('submit', '#guns_form', function(event){
      event.preventDefault();
      $.ajax({
        url:"insertGuns.php",
        method:'POST',
        data:new FormData(this),
        contentType:false,
        processData:false,
        success:function(data){
          //alert(data);
          $('#guns_form')[0].reset();
          $('#gunModal').modal('hide');
          dataTable.ajax.reload();
        }
      });
    });

    //**** ATUALIZAR GUNSHOTS PROVAS ****//
    $(document).on('click', '.guns', function(){
      var gun_id = $(this).attr("id");
      $.ajax({
        url:"fetchGun.php",
        method:"POST",
        data:{gun_id:gun_id},
        dataType:"json",
        success:function(data){
          $('#gunModal').modal('show');
          $('#gunwmn').val(data.gunwmn);
          $('#gunmen').val(data.gunmen);
          $('.modal-title').text("Inserir Horas dos GUNs");
          $('#guns_id').val(gun_id);
          $('#action').val("Guardar Alterações");
          $('#operation').val("Edit");
        }
      })
    });
	    
    //**** ELIMINAR PROVA ****//
    $(document).on('click', '.delete', function(){
      var user_id = $(this).attr("id");
      if(confirm("Tem a certeza que quer eliminar a prova selecionada?")){
        $.ajax({
            url:"delete.php",
            method:"POST",
            data:{user_id:user_id},
            success:function(data){
                //alert(data);
                location.reload();
                // dataTable.ajax.reload();
            }
        });
      } else {
          return false; 
      }   
  	});

	//**** DOWNLOAD PARA CSV DA PROVA ****//
	// $(document).on('click', '.download', function(){
	//     var user_id = $(this).attr("id");
	//     if(confirm("Tem a certeza que quer passar para CSV a Prova selecionada?")){
	//         $.ajax({
	//             url:"download.php",
	//             method:"POST",
	//             data:{user_id:user_id},
	//             success:function(data){
	//                 //alert(data);
	//                 //location.reload();
	//                 //dataTable.ajax.reload();
	//             }
	//         });
	//     }
	//     else{
	//         return false; 
	//     }   
	// });

});
    
</script>