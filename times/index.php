<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	include($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	//include($_SERVER['DOCUMENT_ROOT']."/functions/times.php");
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
?>

<div class="container">
    <div class="col-md-12">
    	<br/>
        <table class="table table-responsive table-hover table-sm" id="times">
        	<thead>
        		<tr>
        			<th>Tempos</th>
        			<th></th>
        			<th></th>
        		</tr>
        	</thead>
            <tbody>
            	<tr>
	            	<td>Tempo 1 - T1</td>
	            	<td><center>
	            		<form id="formulario" method="post" enctype="multipart/form-data">
		            		<input type="file" name="file" id="file1" />
		            		<button type="button" name="update" id="T1" class="btn btn-success btn-xs updateT1">Upload</button>
		            	</form>
	            	</center></td>
	            	<td><center><button type="button" name="delete" id="T1" class="btn btn-danger btn-xs delete">Eliminar</button></center></td>
            	</tr>
            	<tr>
	            	<td>Tempo 2 - T2</td>
	            	<td><center>
	            		<form id="formulario" method="post" enctype="multipart/form-data">
		            		<input type="file" name="file" id="file2" />
		            		<button type="button" name="updateT2" id="T2" class="btn btn-success btn-xs updateT2">Upload</button>
		            	</form>
	            	</center></td>
	            	<td><center><button type="button" name="delete" id="T2" class="btn btn-danger btn-xs delete">Eliminar</button></center></td>
            	</tr>
            	<tr>
	            	<td>Tempo 3 -T3</td>
	            	<td><center>
	            		<form id="formulario" method="post" enctype="multipart/form-data">
		            		<input type="file" name="file" id="file3" />
		            		<button type="button" name="updateT3" id="T3" class="btn btn-success btn-xs updateT3">Upload</button>
		            	</form>
	            	</center></td>
	            	<td><center><button type="button" name="delete" id="T3" class="btn btn-danger btn-xs delete">Eliminar</button></center></td>
            	</tr>
            	<tr>
	            	<td>Tempo 4 - T4</td>
	            	<td><center><form id="formulario" method="post" enctype="multipart/form-data">
		            		<input type="file" name="file" id="file4" />
		            		<button type="button" name="updateT4" id="T4" class="btn btn-success btn-xs updateT4">Upload</button>
		            	</form></center></td>
	            	<td><center><button type="button" name="delete" id="T4" class="btn btn-danger btn-xs delete">Eliminar</button></center></td>
            	</tr>
            	<tr>
	            	<td>Tempo 5 - T5</td>
	            	<td><center>
	            		<form id="formulario" method="post" enctype="multipart/form-data">
		            		<input type="file" name="file" id="file5" />
		            		<button type="button" name="updateT5" id="T5" class="btn btn-success btn-xs updateT5">Upload</button>
		            	</form>
	            	</center></td>
	            	<td><center><button type="button" name="delete" id="T5" class="btn btn-danger btn-xs delete">Eliminar</button></center></td>
            	</tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript" language="javascript" >
    
	$(document).ready(function(){
		var dataTable = $('#times').DataTable({
			"paging": false,
			"searching": false
		});

		$(document).on('click', '.updateT1', function(){
			var formData = new FormData();
			formData.append('file', $('#file1')[0].files[0]);
			formData.append('time_id', $(this).attr("id"));
			$.ajax({
				url : 'upload.php',
				type : 'POST',
				data : formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,  // tell jQuery not to set contentType
				success:function(data){
	                alert('Tempos importados com sucesso!');
	                location.reload();
	            }
			});
		});

		$(document).on('click', '.updateT2', function(){
			var formData = new FormData();
			formData.append('file', $('#file2')[0].files[0]);
			formData.append('time_id', $(this).attr("id"));
			$.ajax({
				url : 'upload.php',
				type : 'POST',
				data : formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,  // tell jQuery not to set contentType
				success:function(data){
	                alert('Tempos importados com sucesso!');
	                location.reload();
	            }
			});
		});

		$(document).on('click', '.updateT3', function(){
			var formData = new FormData();
			formData.append('file', $('#file3')[0].files[0]);
			formData.append('time_id', $(this).attr("id"));
			$.ajax({
				url : 'upload.php',
				type : 'POST',
				data : formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,  // tell jQuery not to set contentType
				success:function(data){
	                alert('Tempos importados com sucesso!');
	                location.reload();
	            }
			});
		});

		$(document).on('click', '.updateT4', function(){
			var formData = new FormData();
			formData.append('file', $('#file4')[0].files[0]);
			formData.append('time_id', $(this).attr("id"));
			$.ajax({
				url : 'upload.php',
				type : 'POST',
				data : formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,  // tell jQuery not to set contentType
				success:function(data){
	                alert('Tempos importados com sucesso!');
	                location.reload();
	            }
			});
		});

		$(document).on('click', '.updateT5', function(){
			var formData = new FormData();
			formData.append('file', $('#file5')[0].files[0]);
			formData.append('time_id', $(this).attr("id"));
			$.ajax({
				url : 'upload.php',
				type : 'POST',
				data : formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,  // tell jQuery not to set contentType
				success:function(data){
	                alert('Tempos importados com sucesso!');
	                location.reload();
	            }
			});
		});

		//**** ELIMINAR ATLETA ****//
		$(document).on('click', '.delete', function(){
		    var time_id = $(this).attr("id");
		    if (confirm("Tem a certeza que quer eliminar TODOS os tempos "+time_id+"?"))
		    {
		        $.ajax(
		        {
		            url:"delete.php",
		            method:"POST",
		            data:{time_id:time_id},
		            success:function(data){
	                	alert(data);
	            	}
		        });
		    } else {
		        return false; 
		    }   
		});
	});
		
</script>