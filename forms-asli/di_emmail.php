<?php
	/*$stop_date = date('Y-m-d H:i:s', strtotime("2009-09-30 20:24:00"));

    echo 'date before day adding: '.$stop_date; 

    $stop_date = date('Y-m-d H:i:s', strtotime('+1 day', $stop_date));

    echo ' date after adding one day. SHOULD be rolled over to the next month: '.$stop_date;*/
    echo date('d')."</br>";
    if(date('D')=='Fri'){
    	$k = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+3,date("Y")));
    }else{
    	$k = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")));
    }
    echo $k;
?>