<?php 

	include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
	if (loginClass::checkLoginState($db))
	{
		include_once ($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
	} else {
		include_once ($_SERVER['DOCUMENT_ROOT']."/html/guest.php");
	}
	include($_SERVER['DOCUMENT_ROOT']."/functions/times.php");
	include($_SERVER['DOCUMENT_ROOT']."/html/footer.php");

?>

<div class="container-fluid" id="resultsContainer">
    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-hover table-md" id="user_data">
            <thead>
                <tr>
                    <th class="dt-center" width="1%">#</th>
                    <th class="dt-center"width="2%">Dorsal</th>
                    <th width="8%">Nome</th>
                    <th class="dt-center" width="1%">Sexo</th>
                    <th class="dt-center" width="1%">GI</th>
                    <th class="dt-center" width="2%">Clube</th>
                    <th class="dt-center" width="4%">T1</th>
                    <th class="dt-center" width="4%">T2</th>
                    <th class="dt-center" width="4%">T3</th>
                    <th class="dt-center" width="4%">T4</th>
                    <th class="dt-center" width="4%">T5</th>
                    <th class="dt-center" width="4%">Total</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- <div class="container-fluid" id="footer">
    <ul class="nav" >
        <li class="nav-item"><a id="brand1-a" href="http://funchaltriathloneuropeancup.pt/wp-content/uploads/2017/09/CALENDARIOS-DE-ANIMACAO-2018-2020_PT.pdf" title="Logo Image"><img id="brand1-img" alt="Logo Image" src="http://funchaltriathloneuropeancup.pt/wp-content/uploads/2017/07/DiscoverMadeira_233x100px.png" /></a></li>

        <li class="nav-item"><a id="brand2-a" href="#" title="Logo Image"><img id="brand2-img" alt="Logo Image" src="http://funchaltriathloneuropeancup.pt/wp-content/uploads/2017/07/CMFunchal_233x100px.png" /></a></li>

        <li class="nav-item"><a id="brand3-a" href="#" title="Logo Image"><img id="brand3-img" alt="Logo Image" src="http://funchaltriathloneuropeancup.pt/wp-content/uploads/2017/07/IPDJ_233x100px.png" /></a></li>

        <li class="nav-item"><a id="brand4-a" href="#" title="Logo Image"><img id="brand4-img" alt="Logo Image" src="http://funchaltriathloneuropeancup.pt/wp-content/uploads/2017/07/ARTM_233x100px.png" /></a></li>

        <li class="nav-item"><a id="brand5-a" href="#" title="Logo Image"><img id="brand5-img" alt="Logo Image" src="http://funchaltriathloneuropeancup.pt/wp-content/uploads/2017/07/FTP_233x100px.png" /></a></li>
    </ul>
</div> -->

<script type="text/javascript" language="javascript" >
    
	$(document).ready(function(){		
		var dataTable = $('#user_data').DataTable({
	        "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
            },
             "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "TUDO"]],
            "pageLength": -1,
            "processing":true,
			"serverSide":true,
            "order": [],
            // "scrollY": "600px",
            // "scrollCollapse": true,
            // "paging": false,
            "columnDefs": [{
                "className": "dt-center", 
                "targets": [0, 3, 4, 5, 6, 7, 8, 9, 10],
            }],
	        "ajax":{
				url:"fetch.php",
				type:"POST"
			}
		});

	});
    
</script>