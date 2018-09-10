<?php  
  function isTime($time){
    if((strlen($time)==6) || (strlen($time)==8)){
      if(strlen($time)==6){
        $final_time="";
        for($i=0;$i<6;$i++){
          if(($i==2) || ($i==4)) 
            $final_time.=":";
          $final_time.=$time[$i];
        }
        list($h,$m,$s)=explode(":",$final_time);
        if(($h<24) && ($m<60) && ($s<60))
          return $final_time;
        else
          return "-";
      }else return $time;
    }else return "-";
  }
?>