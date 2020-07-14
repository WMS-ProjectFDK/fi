<?php
include("../../connect/conn3.php");
$sql = "select 1 as JALAN,
               2 as STOPS,
               3 as RASIO";
$rs=odbc_exec($con32,$sql);

while (odbc_fetch_row($rs)){
   
    $arrData[$arrNo] = array("JALAN"=>odbc_result($rs,"JALAN"),
                             "STOPS"=>odbc_result($rs,"STOPS"),
                             "RASIO"=>odbc_result($rs,"RASIO")
                    );
}
echo json_encode($arrData);
?>