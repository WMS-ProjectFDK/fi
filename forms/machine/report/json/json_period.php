<?php
	include("../../../../connect/conn.php");
    header("Content-type: application/json");
    $arrData = array();
    $arrNo = 0;
    for ($i=0; $i <= 12 ; $i++) { 
        $sql = "SELECT CONVERT(NVARCHAR(6), DATEADD(MONTH, (-1)*".$i.", GETDATE()), 112) as PERIOD";
        $result = sqlsrv_query($connect, $sql);
        // echo $sql;
        while ($row = sqlsrv_fetch_array($result)) {
            $arrData[$arrNo] = array(
                "PERIOD_CODE"=>$row[0],
                "PERIOD_NAME"=>substr($row[0],0,4).'-'.substr($row[0],4,2)
            );
            $arrNo++;
        }
    }
    echo json_encode($arrData);
?>