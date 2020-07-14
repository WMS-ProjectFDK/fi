

<?php  
  $db = "(DESCRIPTION =(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = usdbsrv2.kosai.fdk.co.jp)(PORT = 1521)))(CONNECT_DATA = (SERVER = DEDICATED)(SERVICE_NAME = usdbsrv2XDB.oracle.fdk.co.jp)))";
  $connect = oci_connect("porder", "porder", $db);

  if(!$connect){
		$varConn = "N";
	}else{
		$varConn = "Y";
	}

?>