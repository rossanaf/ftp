<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	include($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	include($_SERVER['DOCUMENT_ROOT']."/functions/times-tri.php");
  ////////////////////////////////////////////////////////////////
	include($_SERVER['DOCUMENT_ROOT']."/functions/times-jov.php");
	include($_SERVER['DOCUMENT_ROOT']."/functions/times-rly.php");
  ////////////////////////////////////////////////////////////////
	include($_SERVER['DOCUMENT_ROOT']."/functions/times-all.php");
	$stmtteams = $db->query("SELECT team_id, team_name FROM teams ORDER BY team_name");
	$teams = $stmtteams->fetchAll();
	$stmtraces = $db->query("SELECT race_id, race_name FROM races ORDER BY race_id");
	$races = $stmtraces->fetchAll();
?>
<div class='table-responsive' id="athletesBackground">
	<div align="right">
    <button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info btn-lg">Inscrição de Ultima Hora</button>
  </div>
  <br/>       
  <table class="table table-hover table-sm" id="user_data">
    <thead>
      <tr>
        <th width="1%">#</th>
        <th width="2%">Chip</th>
        <th width="1%">Dorsal</th>
        <th width="10%">Nome</th>
        <th width="1%">Sexo</th>
        <th width="1%">Escalão</th>
        <th width="10%">Clube</th>
        <th width="4%">T0</th>
        <th width="4%">T1</th>
        <th width="4%">T2</th>
        <th width="4%">T3</th>
        <th width="4%">T4</th>
        <th width="4%">T5</th>
        <th width="4%">Prova</th>
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
        <th width="10%">Nome</th>
        <th width="1%">Sexo</th>
        <th width="1%">Escalão</th>
        <th width="10%">Clube</th>
        <th width="4%">T0</th>
        <th width="4%">T1</th>
        <th width="4%">T2</th>
        <th width="4%">T3</th>
        <th width="4%">T4</th>
        <th width="4%">T5</th>
        <th width="4%">Prova</th>
        <th width="2%">Pen.</th>
        <th width="1%"></th>
        <th width="1%"></th>
      </tr>
    </tfoot>
  </table>
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
            <label for="licenca" class="col-sm-2 col-form-label">Licenca:</label>
            <div class="col-sm-10">
              <input type="text" name="licenca" id="licenca" class="form-control" placeholder="Licenca"/>
              <button type="button" name="licenca_id" id="licenca_id" class="btn btn-info">Pesquisar Atleta Federado</button>
            </div>
          </div>
  				<div class="form-group row">
            <label for="name" class="col-sm-2 control-label">Name:</label>
            <div class="col-sm-10">
            	<input type="text" name="name" id="name" class="form-control" placeholder="Nome" required/>
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
            <label for="escalao" class="col-sm-2 control-label">Escalão:</label>
            <div class="col-sm-10">
              <select class="form-control" id="escalao" name="escalao" required>
                <option selected disabled value=""> -- Escalões -- </option>
                <option value="ELITE">ELITE</option>
                <option value="JUNIOR">JUNIOR</option>
                <option value="BEN">Benjamins</option>
                <option value="INF">Infantis</option>
                <option value="INI">Iniciados</option>
                <option value="JUV">Juvenis</option>
                <option value="CAD">Cadetes</option>
                <option value="JUN">Juniores</option>
                <option value="S23">Sub-23</option>
                <option value="SEN">Seniores</option>
                <option value="V1">Veteranos I</option>
                <option value="V2">Veteranos II</option>
                <option value="V3">Veteranos III</option>
                <option value="V4">Veteranos III</option>
                <option value="V4">Veteranos IV</option>
                <option value="V5">Veteranos V</option>
                <option value="VET">Veteranos</option>
                <option value="ABS">Absolutos</option>
                <option value="20-24">20-24</option>
                <option value="25-29">25-29</option>
                <option value="30-34">30-34</option>
                <option value="35-39">35-39</option>
                <option value="40-44">40-44</option>
                <option value="45-49">45-49</option>
                <option value="50-54">50-54</option>
                <option value="55-59">55-59</option>
                <option value="60-64">60-64</option>
                <option value="65-69">65-69</option>
                <option value="70-74">70-74</option>
                <option value="75-79">75-79</option>
                <option value="80-84">80-84</option>
                <option value="85-89">85-89</option>
                <option value="90-94">90-94</option>
                <option value="95-99">95-99</option>
                <option value="100+">100+</option>
                <option value="PTS2">PTS2</option>
                <option value="PTS5">PTS5</option>
                <option value="PTVI">PTVI</option>
                <option value="ESTF">Estafetas</option>                            	
              </select>
            </div>
          </div>
          <div class="form-group row">    
            <label for="clube" class="col-sm-2 control-label">Clube:</label>
            <div class="col-sm-10">
              <select class="form-control" id="clube" name="clube" required>
                <option value="ADICIONAR"> -- Lista de Clubes -- </option>
                <?php foreach ($teams as $team): ?>
  				    		<option value="<?=$team['team_id']?>"><?=$team['team_name']?></option>
  							<?php endforeach ?>
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
              <select class="form-control" id="race" name="race" required>
                <option value="ADICIONAR"> -- Provas do Dia -- </option>
                <?php foreach ($races as $race): ?>
  		            <option value="<?=$race['race_id']?>"><?=$race['race_name']?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="t0" class="col-sm-2 control-label">T0:</label>
            <div class="col-sm-10">
              <input type="text" name="t0" id="t0" class="form-control" placeholder="Hora do GUN / Passagem testemunho"/>
            </div>
          </div>
          <div class="form-group row">
            <label for="swim" class="col-sm-2 control-label">T1:</label>
            <div class="col-sm-10">
              <input type="text" name="swim" id="swim" class="form-control" placeholder="Hora Passagem Natação / 1.ª Corrida"/>
            </div>
          </div>
          <div class="form-group row">
            <label for="t1" class="col-sm-2 control-label">T2:</label>
            <div class="col-sm-10">
              <input type="text" name="t1" id="t1" class="form-control" placeholder="Hora Passagem Transição 1"/>
            </div>
          </div>
          <div class="form-group row">
            <label for="bike" class="col-sm-2 control-label">T3:</label>
            <div class="col-sm-10">
              <input type="text" name="bike" id="bike" class="form-control" placeholder="Hora Passagem Ciclismo"/>
            </div>
          </div>
          <div class="form-group row">
            <label for="t2" class="col-sm-2 control-label">T4:</label>
            <div class="col-sm-10">
              <input type="text" name="t2" id="t2" class="form-control" placeholder="Hora Passagem Transição 2"/>
            </div>
          </div>
          <div class="form-group row">
            <label for="run" class="col-sm-1 control-label">T5:</label>
            <div class="col-sm-5">
              <input type="text" name="run" id="run" class="form-control" placeholder="Hora Passagem Meta"/>
            </div>
            <label for="totaltime" class="col-sm-1 control-label">Meta:</label>
            <div class="col-sm-5">
              <input type="text" name="totaltime" id="totaltime" class="form-control" placeholder="Hora Relógio Meta" disabled />
            </div>
          </div>
          <div class="form-group row">
            <label for="time" class="col-sm-2 control-label">Penalização:</label>
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
      "columnDefs":[{
  			"targets":[15, 16],
  			"orderable":false,
			}],
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
          // alert(data);
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
          $('#name').val(data.name);
          $('#dorsal').val(data.bib);
          $('#licenca').val(data.license);
          $('#sexo').val(data.sex);
          $("input[name='sexo'][value='" + data.sex + "']").prop('checked', true);
          $('#escalao').val(data.category);
          $('#race').val(data.race);
          $("input[name='race'][value='" + data.race + "']").prop('selected', true);
          $('#clube').val(data.team);
          $("input[name='clube'][value='" + data.team + "']").prop('selected', true);
          $('#t0').val(data.t0);
          if(data.t1 == 0) {
            $('#swim').attr('readonly', true);
          } else {
            $('#swim').attr('readonly', false);
            $('#swim').val(data.t1);
          }
          if(data.t1 == 0) {
            $('#t1').attr('readonly', true);
          } else {
            $('#t1').attr('readonly', false);
            $('#t1').val(data.t2);
          }
          if(data.t1 == 0) {
            $('#bike').attr('readonly', true);
          } else {
            $('#bike').attr('readonly', false);
            $('#bike').val(data.t3);
          }
          if(data.t1 == 0) {
            $('#t2').attr('readonly', true);
          } else {
            $('#t2').attr('readonly', false);
            $('#t2').val(data.t4);
          }
          if(data.t1 == 0) {
            $('#run').attr('readonly', true);
          } else {
            $('#run').attr('readonly', false);
            $('#run').val(data.t5);
          }
          $('#totaltime').val(data.totaltime);
          $('#time').val(data.finishtime);
          $("input[name='time'][value='" + data.finishtime + "']").prop('checked', true);
          $('.modal-title').text("Editar dados do Atleta");
          $('#user_id').val(user_id);
          $('#action').val("Guardar Alterações");
          $('#operation').val("Edit");
        }
	    })
  	});
	    
  	//**** CARREGAR FEDERADO ****//
  	$(document).on('click', '#licenca_id', function(){
      var licenca_id = $("input[name='licenca']").val();
      $.ajax({
        url:"federado.php",
        method:"POST",
        data:{licenca_id:licenca_id},
        dataType:"json",
        success:function(data){
          $('#chip').val(data.chip);
          $('#name').val(data.name);
          $('#dorsal').val(data.dorsal);
          $('#sexo').val(data.sexo);
          $("input[name='sexo'][value='" + data.sexo + "']").prop('checked', true);
          $('#escalao').val(data.escalao);
          $('#clube').val(data.clube);
          $("input[name='clube'][value='" + data.clube + "']").prop('selected', true);
          $('#licenca').val(licenca_id);
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
      }else{
        return false; 
      }   
  	});
  });   
</script>