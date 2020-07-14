<?php
 $ip_num = $_SERVER['REMOTE_ADDR']; //untuk mendeteksi alamat IP
 $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']); //untuk mendeteksi computer name

 $ip_address=$_SERVER['REMOTE_ADDR'];
 $info=$_SERVER['HTTP_USER_AGENT'];

 
 echo"Alamat IP : $ip_num";
 echo"<br />";
 echo"Nama Komputer : $host_name";
 echo"<br />";
 echo $info;
?>