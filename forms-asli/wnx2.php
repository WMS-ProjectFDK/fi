<?php
$host = '172.23.225.93'; 
$port = 80; 
$waitTimeoutInSeconds = 1; 
if($fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds)){   
   echo "It worked"; 
} else {
   echo "It didn't work";
} 
fclose($fp);
?>