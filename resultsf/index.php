<?php 
    include($_SERVER['DOCUMENT_ROOT']."/html/header.php");
    if (loginClass::checkLoginState($db))
    {
        include_once ($_SERVER['DOCUMENT_ROOT']."/html/nav.php");
    } else {
        include_once ($_SERVER['DOCUMENT_ROOT']."/html/guest.php");
    }
?>

<div class="container-fluid" id="resultsContainer">
    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-hover table-sm responsive" id="user_data">
            <thead>
                <tr>
                    <th class="dt-center" width="1%">POS</th>
                    <th class="dt-center" width="3%">Num</th>
                    <th width="20%">Nome</th>
                    <th class="dt-center" width="6%">Esc.</th>
                    <th width="20%">Clube</th>
                    <th class="dt-center" width="6%">Natação</th>
                    <th class="dt-center" width="6%">T1</th>
                    <th class="dt-center" width="6%">Ciclismo</th>
                    <th class="dt-center" width="6%">T2</th>
                    <th class="dt-center" width="6%">Corrida</th>
                    <th class="dt-center" width="6%">Total</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript" language="javascript" >   
    $(document).ready(function(){       
        var dataTable = $('#user_data').DataTable({
            "dom": 'ft',
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
            },
            // "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            // "pageLength": 25,
            "processing":true,
            "serverSide":true,
            "order": [],
            // "scrollY": "600px",
            // "scrollCollapse": true,
            // "paging": false,
            "columnDefs": [{
                "className": "dt-center", 
                "targets": [0, 1, 3, 5, 6, 7, 8, 9, 10],
            }],
            "ajax":{
                url:"fetch.php",
                type:"POST"
            }
        });
    });    
</script>
<?php include($_SERVER['DOCUMENT_ROOT']."/html/info.php"); ?>