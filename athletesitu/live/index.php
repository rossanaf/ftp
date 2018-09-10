<?php 
    include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
    if (loginClass::checkLoginState($db))
    {
        include_once ($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
    } else {
        include_once ($_SERVER['DOCUMENT_ROOT']."/html/guest.php");
    }
?>
<div id="liveContainer">
    <div class="col-sm-1 col-md-12">
        <table class="table table-responsive-sm table-hover table-sm table responsive" id="user_data">
            <thead>
                <tr>
                    <th class="dt-center" width="4%">Start Num</th>
                    <th width="30%">Name</th>
                    <th width="20%">Country</th>
                    <th class="dt-center" width="10%">Segment</th>
                    <th class="dt-center" width="10%">Time</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript" language="javascript" >
	$(document).ready(function()
	{		
		var dataTable = $('#user_data').DataTable({
			"dom": 't',
	        "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
            },
            "ordering": false,
            "serverSide":true,
            "columnDefs": [{
                "className": "dt-center", 
                "targets": [0, 2, 3, 4],
            }],
            "ajax":{
				url:"fetch.php",
				type:"POST"
			}
		});
		setInterval( function () {
    		dataTable.ajax.reload( null, false ); // user paging is not reset on reload
		}, 3000 );
	});
</script>
<?php include($_SERVER['DOCUMENT_ROOT']."/html/info.php"); ?>