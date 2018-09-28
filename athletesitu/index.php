<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	include($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	// include($_SERVER['DOCUMENT_ROOT']."/functions/times.php");
	$stmtraces = $db->prepare("SELECT race_id, race_name FROM races ORDER BY race_id");
	$races = $stmtraces->fetchAll();
  // $stmtTeams = $db->prepare();
?>

<div class="container-fluid">
    <div class="col-md-12">
        <div align="right">
            <button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info btn-lg">Inscrição de Ultima Hora</button>
        </div>
        <br/>
        
        <table class="table table-responsive table-bordered table-hover table-sm" id="user_data">
            <thead>
                <tr>
                    <th width="1%">#</th>
                    <th width="2%">Chip</th>
                    <th width="1%">Dorsal</th>
                    <th width="12%">Nome</th>
                    <th width="1%">Sexo</th>
                    <th width="4%">Nac.</th>
                    <th width="4%">T1</th>
                    <th width="4%">T2</th>
                    <th width="4%">T3</th>
                    <th width="4%">T4</th>
                    <th width="4%">T5</th>
                    <th width="2%">Prova</th>
                    <th width="2%">Pen.</th>
                    <th width="1%"></th>
                    <th width="1%"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th width="1%">#</th>
                    <th width="2%">Chip</th>
                    <th width="1%">Dorsal</th>
                    <th width="12%">Nome</th>
                    <th width="1%">Sexo</th>
                    <th width="4%">Nac.</th>
                    <th width="4%">T1</th>
                    <th width="4%">T2</th>
                    <th width="4%">T3</th>
                    <th width="4%">T4</th>
                    <th width="4%">T5</th>
                    <th width="2%">Prova</th>
                    <th width="2%">Pen.</th>
                    <th width="1%"></th>
                    <th width="1%"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- POPUP WINDOW TO ADD/EDIT DATA -->
<div class="modal fade modalPopUp" tabindex="-1" role="dialog" id="userModal">
	<div class="modal-dialog" role="document">
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
	                    <label for="name" class="col-sm-2 control-label">Nome:</label>
	                    <div class="col-sm-10">
                        	<input type="text" name="firstname" id="firstname" class="form-control" placeholder="Nome" required/>
                        </div>
                    </div>
                    <div class="form-group row">
	                    <label for="lastname" class="col-sm-2 control-label">Sobrenome:</label>
	                    <div class="col-sm-10">
                        	<input type="text" name="lastname" id="lastname" class="form-control" placeholder="Sobrenome" required/>
                        </div>
                    </div>
                    <div class="form-group row">    
	                    <label for="sexo" class="col-sm-2 control-label">Sexo:</label>
	                    <div class="col-sm-10" id="sexo">
	                        <label class="radio-inline"><input type="radio" name="sexo" value="M" required> Masculino </label>
	                        <label class="radio-inline"><input type="radio" name="sexo" value="F" required> Feminino </label>
	                    </div>
	                </div>
	                <div class="form-group row">    
	                    <label for="clube" class="col-sm-2 control-label">Nac.:</label>
	                    <div class="col-sm-10">
	                        <select class="form-control" id="clube" name="clube">
	                            <option value="ADICIONAR"> -- Pais -- </option>
	                            <option value="AUT">Austria</option>
	                            <option value="AZE">Azerbeijão</option>
	                            <option value="BEL">Bélgica</option>
	                            <option value="BER">Bermuda</option>
	                            <option value="CAN">Canada</option>
	                            <option value="COL">Colombia</option>
	                            <option value="CRC">Costa Rica</option>
	                            <option value="CRO">Croácia</option>
	                            <option value="CZE">Rep. Checa</option>
	                            <option value="ECU">Ecuador</option>
	                            <option value="ESP">Espanha</option>
	                            <option value="EST">Estónia</option>
	                            <option value="FRA">França</option>
	                            <option value="GBR">Grã-Bretanha</option>
	                            <option value="GER">Alemanha</option>
	                            <option value="GRE">Grécia</option>
	                            <option value="HUN">Hungria</option>
	                            <option value="ISR">Israel</option>
	                            <option value="IRL">Irlanda</option>
	                            <option value="ISL">Islandia</option>
	                            <option value="ITA">Itália</option>
	                            <option value="ITU">ITU</option>
	                            <option value="KAZ">Kazaquistão</option>
	                            <option value="LAT">Letónia</option>
	                            <option value="LTU">Lituania</option>
	                            <option value="LUX">Luxemburgo</option>
	                            <option value="MEX">México</option>
	                            <option value="NED">Holanda</option>
	                            <option value="NOR">Noruega</option>
	                            <option value="PHI">Filipinas</option>
	                            <option value="POL">Polónia</option>
	                            <option value="POR">Portugal</option>
	                            <option value="RUS">Russia</option>
	                            <option value="SUI">Suiça</option>
	                            <option value="SVK">Eslováquia</option>
	                            <option value="SWE">Suécia</option>
	                            <option value="TAH">Tahiti</option>
	                            <option value="UKR">Ucrania</option>
	                            <option value="USA">EUA</option>
	                        </select>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label for="chip" class="col-sm-2 control-label">Chip:</label>
	                    <div class="col-sm-10">
	                        <input type="text" name="chip" id="chip" class="form-control" placeholder="Chip" required/>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label for="dorsal" class="col-sm-2 control-label">Dorsal:</label>
	                    <div class="col-sm-10">
	                        <input type="text" name="dorsal" id="dorsal" class="form-control" placeholder="Dorsal" required/>
	                    </div>
                    </div>
                    <div class="form-group row">    
	                    <label for="race" class="col-sm-2 control-label">Prova:</label>
	                    <div class="col-sm-10">
	                        <select class="form-control" id="race" name="race">
	                            <option value="ADICIONAR"> -- Provas do Dia -- </option>
	                            <?php foreach ($races as $race): ?>
						    		<option value="<?=$race['race_id']?>"><?=$race['race_name']?></option>
								<?php endforeach ?>
	                        </select>
	                    </div>
	                </div>
	                <div class="form-group row">
	                	<label for="swim" class="col-sm-2 control-label">Natação:</label>
	                    <div class="col-sm-10">
	                        <input type="text" name="swim" id="swim" class="form-control" placeholder="Hora Passagem Natação"/>
	                    </div>
                    </div>
                    <div class="form-group row">
	                    <label for="t1" class="col-sm-2 control-label">T1:</label>
	                    <div class="col-sm-10">
	                        <input type="text" name="t1" id="t1" class="form-control" placeholder="Hora Passagem Transição 1"/>
	                    </div>
                    </div>
                    <div class="form-group row">
	                    <label for="bike" class="col-sm-2 control-label">Ciclismo:</label>
	                    <div class="col-sm-10">
	                        <input type="text" name="bike" id="bike" class="form-control" placeholder="Hora Passagem Ciclismo"/>
	                    </div>
                    </div>
                    <div class="form-group row">
	                    <label for="t2" class="col-sm-2 control-label">T2:</label>
	                    <div class="col-sm-10">
	                        <input type="text" name="t2" id="t2" class="form-control" placeholder="Hora Passagem Transição 2"/>
	                    </div>
                    </div>
                    <div class="form-group row">
	                    <label for="run" class="col-sm-2 control-label">Corrida:</label>
	                    <div class="col-sm-10">
	                        <input type="text" name="run" id="run" class="form-control" placeholder="Hora Passagem Corrida / Meta"/>
	                    </div>
                    </div>
                    <div class="form-group row">
	                    <label for="run" class="col-sm-2 control-label">Penalização:</label>
	                    <div class="col-sm-10" id="time">
	                        <label class="radio-inline"><input type="radio" name="time" value="-"> Em Prova</label>
	                        <label class="radio-inline"><input type="radio" name="time" value="chkin"> Inscrito</label>
	                        <label class="radio-inline"><input type="radio" name="time" value="DNS"> DNS</label>
	                        <label class="radio-inline"><input type="radio" name="time" value="DSQ"> DSQ</label>
	                        <label class="radio-inline"><input type="radio" name="time" value="DNF"> DNF</label>
	                        <label class="radio-inline"><input type="radio" name="time" value="LAP"> LAP</label>
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

<script type="text/javascript" language="javascript" >
    
	$(document).ready(function(){
		$('#add_button').click(function(){
			$('#user_form')[0].reset();
			$('.modal-title').text("Inscrição de Ultima Hora");
			$('#action').val("Adicionar");
			$('#operation').val("Add");
		});
		
		var dataTable = $('#user_data').DataTable({
	        "language": {
	            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
	        },
	        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "TODOS"]],
	        "pageLength": -1,
	        "processing":true,
			"serverSide":true,
	        "order":[],
	        "ajax":{
				url:"fetch.php",
				type:"POST"
			},
	        "columnDefs":[
				{
					"targets":[13, 14],
					"orderable":false,
				},
			],
		});
		
	//**** SUBMETER NOVO ATLETA / ALTERAÇÕES ****//
	$(document).on('submit', '#user_form', function(event){
	    event.preventDefault();
	    $.ajax({
	        url:"insert.php",
	        method:'POST',
	        data:new FormData(this),
	        contentType:false,
	        processData:false,
	        success:function(data){
	            // alert('Atleta inserido com sucesso.');
	            $('#user_form')[0].reset();
	            $('#userModal').modal('hide');
	            dataTable.ajax.reload();
	        }
	    });
	});
	
	//**** ATUALIZAR ATLETA ****//
	$(document).on('click', '.update', function(){
	    var user_id = $(this).attr("id");
	    $.ajax({
	        url:"fetch_single.php",
	        method:"POST",
	        data:{user_id:user_id},
	        dataType:"json",
	        success:function(data){
	            $('#userModal').modal('show');
	            $('#chip').val(data.chip);
	            $('#firstname').val(data.firstname);
	            $('#lastname').val(data.lastname);
	            $('#dorsal').val(data.bib);
	            $('#sexo').val(data.sex);
	            $("input[name='sexo'][value='" + data.sex + "']").prop('checked', true);
	            $('#race').val(data.race);
	            $("input[name='race'][value='" + data.race + "']").prop('selected', true);
	            $('#clube').val(data.team);
	            $("input[name='clube'][value='" + data.team + "']").prop('selected', true);
	            $('#swim').val(data.t1);
	            $('#t1').val(data.t2);
	            $('#bike').val(data.t3);
	            $('#t2').val(data.t4);
	            $('#run').val(data.t5);
	            $('#time').val(data.finishtime);
	            $("input[name='time'][value='" + data.finishtime + "']").prop('checked', true);
	            $('.modal-title').text("Editar dados do Atleta");
	            $('#user_id').val(user_id);
	            $('#action').val("Guardar Alterações");
	            $('#operation').val("Edit");
	        }
	    })
	});
	    
	//**** ELIMINAR ATLETA ****//
	$(document).on('click', '.delete', function(){
	    var user_id = $(this).attr("id");
	    if(confirm("Tem a certeza que quer eliminar o Atleta selecionado?")){
	        $.ajax({
	            url:"delete.php",
	            method:"POST",
	            data:{user_id:user_id},
	            success:function(data){
	                // alert(data);
	                dataTable.ajax.reload();
	            }
	        });
	    }
	    else{
	        return false; 
	    }   
	});
	});
    
</script>