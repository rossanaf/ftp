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