<?php

    include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
    
    function get_total_all_records()
    {
        include ($_SERVER['DOCUMENT_ROOT']."/includes/db.php");
        $stmt = $db->prepare("SELECT * FROM live WHERE live_sex = 'F'");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $stmt->rowCount();
    }

    $order_column = array("live_pos","live_bib","live_firstname","live_category","live_t1","live_t2","live_t3","live_t4","live_t5","live_finishtime");

    $output = array();

    $query = "SELECT * FROM live ";

    if(isset($_POST["search"]["value"]))
    {
        $query .= 'WHERE live_sex = "F" AND (';
        // $query .= 'LOWER (live_team) LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR LOWER (live_firstname) LIKE "%'.$_POST["search"]["value"].'%" ';
        // $query .= 'OR LOWER (live_bib) LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR LOWER (live_category) LIKE "%'.$_POST["search"]["value"].'%" ';
        // // $query .= 'AND LOWER (live_finishtime) LIKE "%'.$_POST["search"]["value"].'%" ';
        // $query .= 'AND live_bib LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= ') ';
    } else $query .= 'WHERE live_sex = "F" ';

    if(isset($_POST["order"]))
    {
        $query .= 'ORDER BY '.$order_column[$_POST["order"]["0"]["column"]].' '.$_POST["order"]["0"]["dir"].' ';
    } else {
        $query .= 'ORDER BY live_started DESC, live_pos, live_finishtime, live_t4, live_t3, live_t2, live_t1,LENGTH(live_bib), live_bib ';
    }
        
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $data = array();
    $filtered_rows = $stmt->rowCount();

    foreach($result as $row)
    {              
        $sub_array = array();
    
        $live_t1 = $row["live_t1"];
        $live_t2 = $row["live_t2"];
        $live_t3 = $row["live_t3"];
        $live_t4 = $row["live_t4"];
        $live_t5 = $row["live_t5"];
        $live_finishtime = $row["live_finishtime"];
        $flag="<img src='/images/flags/".$row['live_category'].".png' width='18px'>";
        if (($row["live_t1"]) === ("time")) $live_t1 = "";
        if (($row["live_t2"]) === ("time")) $live_t2 = "";
        if (($row["live_t3"]) === ("time")) $live_t3 = "";
        if (($row["live_t4"]) === ("time")) $live_t4 = "";
        if (($row["live_t5"]) === ("time")) $live_t5 = "";
        if (($row["live_finishtime"]) === ("time")) $live_finishtime = "";
        if ($row["live_pos"] == 9999)
            $sub_array[] = "";
        elseif ($row["live_pos"]== -1)
            $sub_array[] = $live_finishtime;
        else
            $sub_array[] = $row["live_pos"];
        // $sub_array[] = $row["live_lastname"];
        $sub_array[] = $row["live_bib"];
        $sub_array[] = $row["live_firstname"].' '.$row["live_lastname"];
        $sub_array[] = $flag.' '.$row["live_category"];
        $sub_array[] = $live_t1;
        $sub_array[] = $live_t2;
        $sub_array[] = $live_t3;
        $sub_array[] = $live_t4;
        $sub_array[] = $live_t5;
        if(($row["live_finishtime"]=="DNF") || ($row["live_finishtime"]=="DNS") || ($row["live_finishtime"]=="DSQ") || ($row["live_finishtime"]=="LAP"))
            $sub_array[] = $row["live_finishtime"];
        else $sub_array[] = $live_finishtime;
        $data[] = $sub_array;
    }

    $output = array(
        "draw"              =>  intval($_POST["draw"]),
        "recordsTotal"      =>  $filtered_rows,
        "recordsFiltered"   =>  get_total_all_records(),
        "data"              =>  $data
    );

    echo json_encode($output);

?>