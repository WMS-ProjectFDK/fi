<?php
$serverName = "localhost, 1542"; //serverName\instanceName, portNumber (1433 by default)
$connectionInfo = array( "Database"=>"FDKSYS20", "UID"=>"sa", "PWD"=>"P@ssw0rd");
$conn = mssql_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Successfuly connected.<br />";
}else{
     echo "Connection error.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>