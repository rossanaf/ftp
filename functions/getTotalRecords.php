<?php  
  function get_total_all_records($table){
    include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
    $sql = 'SELECT * FROM '.$table;
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $stmt->rowCount();
  }
?>