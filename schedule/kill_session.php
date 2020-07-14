<?php
set_time_limit(0);
include("../connect/conn.php");
date_default_timezone_set('Asia/Jakarta');
$sql = "
		BEGIN
		  FOR r IN (select sid,serial# from v$session where username='PORDER')
		  LOOP
		      EXECUTE 'ALTER SYSTEM KILL SESSION ''' || r.sid  || ',' || r.serial# || '';
		  END LOOP
		END
		";
$stmt = oci_parse($connect, $sql);
$res = oci_execute($stmt);

echo "<br/>".date("Y-m-d H:i:s").' - FINISH<br/>';
?>