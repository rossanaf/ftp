<?php 
	include($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
?>

<div class="container">
	<h3>Carregar Startlists</h3>
  <div>
  	<table class="table table-responsive table-hover table-sm" id="times">
    	<thead>
    		<tr>
    			<td></td>
    			<th>Tipo de Prova</th>
    			<th><center>Abrir Prova</center></th>
    		</tr>
    	</thead>
        <tbody>
        	<tr>
        		<th scope="row">Aquatlo</th>
          	<td>Aquatlo</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="fileaquathlon" />
            		<button type="button" name="update" id="aquathlon" class="btn btn-success btn-xs aquathlon">Upload</button>
            	</form>
          	</center></td>
          </tr>
          <tr>
        		<th scope="row"></th>
          	<td>Estafetas</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="fileaquarly" />
            		<button type="button" name="update" id="aquarly" class="btn btn-success btn-xs aquarly">Upload</button>
            	</form>
          	</center></td>
          </tr>
          <tr>
          	<th scope="row">Duatlo / Triatlo</th>
          	<td>Contrarrelógio Equipas</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="fileCRE" />
            		<button type="button" name="update" id="cre" class="btn btn-success btn-xs cre">Upload</button>
            	</form>
          	</center></td>
          </tr>
        	<tr>
        		<th scope="row"></th>
          	<td>Contrarrelógio Individual</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="filecrind" />
            		<button type="button" name="update" id="crind" class="btn btn-success btn-xs crind">Upload</button>
            	</form>
          	</center></td>
        	</tr>
        	<tr>
        		<th scope="row"></th>
          	<td>Duatlo / Triatlo</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="filetriatlo" />
            		<button type="button" name="update" id="triatlo" class="btn btn-success btn-xs triatlo">Upload</button>
            	</form>
          	</center></td>
        	</tr>
        	<tr>
        		<th scope="row"></th>
          	<td>Estafetas</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="fileRelay" />
            		<button type="button" name="update" id="relay" class="btn btn-success btn-xs relay">Upload</button>
            	</form>
          	</center></td>
        	</tr>
        	<tr>
        		<th scope="row"></th>
          	<td>Estafetas Mistas</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="filemxrelay" />
            		<button type="button" name="update" id="mxrelay" class="btn btn-success btn-xs mxrelay">Upload</button>
            	</form>
          	</center></td>
        	</tr>
        	<tr>
        		<th scope="row">Provas Jovens</th>
          	<td>Aquatlo</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="filepjaqua" />
            		<button type="button" name="update" id="jovemaq" class="btn btn-success btn-xs pjaqua">Upload</button>
            	</form>
          	</center></td>
        	</tr>
        	<tr>
        		<th scope="row"></th>
          	<td>Duatlo / Triatlo</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="filejovem" />
            		<button type="button" name="update" id="jovem" class="btn btn-success btn-xs jovem">Upload</button>
            	</form>
          	</center></td>
        	</tr>
          <tr>
            <th scope="row"></th>
            <td>Estafetas</td>
            <td><center>
              <form id="formulario" method="post" enctype="multipart/form-data">
                <input type="file" name="file" id="filejEstf" />
                <button type="button" name="update" id="jEstf" class="btn btn-success btn-xs jEstf">Upload</button>
              </form>
            </center></td>
          </tr>
        	<tr>
        		<th scope="row">ITU / ETU</th>
          	<td>Duatlo / Triatlo</td>
          	<td><center>
          		<form id="formulario" method="post" enctype="multipart/form-data">
            		<input type="file" name="file" id="fileitu" />
            		<button type="button" name="update" id="itu" class="btn btn-success btn-xs ituTri">Upload</button>
            	</form>
          	</center></td>
        	</tr>
          <tr>
            <th scope="row"></th>
            <td>Estafestas Mistas</td>
            <td><center>
              <form id="formulario" method="post" enctype="multipart/form-data">
                <input type="file" name="file" id="fileitu" />
                <button type="button" name="update" id="itu" class="btn btn-success btn-xs ituMxRelay">Upload</button>
              </form>
            </center></td>
          </tr>
        </tbody>
      </table>
  </div>
</div>

<script type="text/javascript" language="javascript" >
	$(document).ready(function() {
		var dataTable = $('#times').DataTable({
			"paging": false,
			"searching": false,
			"ordering": false
		});
		$(document).on('click', '.aquarly', function() {
			var formData = new FormData();
			formData.append('file', $('#fileaquarly')[0].files[0]);
			formData.append('prova_id', $(this).attr("id"));
			$.ajax({
				url : 'cn-.php',
				type : 'POST',
				data : formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,  // tell jQuery not to set contentType
				success:function(data){
          alert('Startlist importada com sucesso!');
          location.reload();
        }
			});
		});
		$(document).on('click', '.pjaqua', function() {
			var formData = new FormData();
			formData.append('file', $('#filepjaqua')[0].files[0]);
			formData.append('prova_id', $(this).attr("id"));
			$.ajax({
				url : 'importPJAqua.php',
				type : 'POST',
				data : formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,  // tell jQuery not to set contentType
				success:function(data){
          alert('Startlist importada com sucesso!');
          location.reload();
        }
			});
		});
    $(document).on('click', '.crind', function() {
      var formData = new FormData();
      formData.append('file', $('#filecrind')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'importCRI.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data){
          alert('Startlist importada com sucesso!');
          location.reload();
        }
      });
    });
    $(document).on('click', '.aquathlon', function() {
      var formData = new FormData();
      formData.append('file', $('#fileaquathlon')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'importAqua.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data) {
          alert('Startlist importada com sucesso!');
          location.reload();
        }
      });
    });
    $(document).on('click', '.jovem', function() {
      var formData = new FormData();
      formData.append('file', $('#filejovem')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'importPJ.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data){
          alert('Startlist importada com sucesso!');
          location.reload();
        }
      });
    });
    $(document).on('click', '.jEstf', function() {
      var formData = new FormData();
      formData.append('file', $('#filejEstf')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'importJEstf.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data){
          alert('Startlist importada com sucesso!');
          location.reload();
        }
      });
    });
    $(document).on('click', '.ituTri', function() {
      var formData = new FormData();
      formData.append('file', $('#fileItuTri')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'itu-triathlon.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data){
          alert('Startlist importada com sucesso!');
          location.reload();
        }
      });
    });
    $(document).on('click', '.ituMxRelay', function() {
      var formData = new FormData();
      formData.append('file', $('#fileItuMxrelay')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'itu-mxrelay.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data){
          alert('Startlist importada com sucesso!');
          location.reload();
        }
      });
    });
    // OK 
    $(document).on('click', '.cre', function() {
      var formData = new FormData();
      formData.append('file', $('#fileCRE')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'importCRE.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data){
          // alert('Startlist importada com sucesso!');
          // location.reload();
        }
      });
    });
    $(document).on('click', '.triatlo', function() {
      var formData = new FormData();
      formData.append('file', $('#filetriatlo')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'import-file.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data) {
          alert('Startlist importada com sucesso!');
          location.reload();
        }
      });
    });
    $(document).on('click', '.mxrelay', function() {
      var formData = new FormData();
      formData.append('file', $('#fileMxRelay')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'import-file.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data) {
          alert('Startlist importada com sucesso!');
          location.reload();
        }
      });
    });
    $(document).on('click', '.relay', function() {
      var formData = new FormData();
      formData.append('file', $('#fileRelay')[0].files[0]);
      formData.append('prova_id', $(this).attr("id"));
      $.ajax({
        url : 'import-file.php',
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success:function(data) {
          alert('Startlist importada com sucesso!');
          location.reload();
        }
      });
    });
    // OK
  });
</script>