<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	include($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	include($_SERVER['DOCUMENT_ROOT']."/functions/times.php");
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
?>

<div class="container">
    <div class="col-md-12">
    	<div align="right">
            <button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info btn-lg">Adicionar novo Clube</button>
        </div>
        <br/>
        <table class="table table-responsive table-bordered table-hover table-sm" id="user_data">
            <thead>
                <tr>
                    <th>Listagem de Clubes</th>
                    <th width="1%"></th>
                    <th width="1%"></th>
                </tr>
            </thead>
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
	                    <label for="name" class="col-sm-2 col-form-label">Clube:</label>
	                    <div class="col-sm-10">
		                    <input type="text" name="name" id="name" class="form-control" placeholder="Nome do Clube"/>
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
			$('.modal-title').text("Adicionar novo Clube");
			$('#action').val("Adicionar");
			$('#operation').val("Add");
		});

		var dataTable = $('#user_data').DataTable({
	        "language": {
	            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
	        },
	        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "TODOS"]],
	        "pageLength": 10,
	        "processing":true,
			"serverSide":true,
	        "order":[],
	        "ajax":{
				url:"fetch.php",
				type:"POST"
			},
			"columnDefs":[
				{
					"targets":[1, 2],
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
		            //alert(data);
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
		            $('#name').val(data.name);
		            $('.modal-title').text("Editar nome do Clube");
		            $('#user_id').val(user_id);
		            $('#action').val("Guardar Alteração");
		            $('#operation').val("Edit");
		        }
		    })
		});
	    
		//**** ELIMINAR ATLETA ****//
		$(document).on('click', '.delete', function(){
		    var user_id = $(this).attr("id");
		    if(confirm("Tem a certeza que quer eliminar o Clube selecionado?")){
		        $.ajax({
		            url:"delete.php",
		            method:"POST",
		            data:{user_id:user_id},
		            success:function(data){
		                alert(data);
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