<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select   b.BANK_SEQ, b.BANK, b.ADDRESS1, b.ADDRESS2, b.ADDRESS3, b.ADDRESS4,
        b.ACCOUNT_NO, b.CURR_CODE, c.CURR_MARK
        from BANK b
        LEFT JOIN CURRENCY c on b.CURR_CODE = c.CURR_CODE
        WHERE b.DELETE_FLAG is null
        order by b.BANK, c.CURR_MARK";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		if (rtrim($row[0]) == '3'){
			$arrData[$arrNo] = array(
				"bank_seq"=>rtrim($row[0]), 
				"bank"=>strtoupper(rtrim($row[1])),
				"selected" => true
			);
		}else{
			$arrData[$arrNo] = array(
				"bank_seq"=>rtrim($row[0]), 
				"bank"=>strtoupper(rtrim($row[1]))
			);
		}
		$arrNo++;
	}
	echo json_encode($arrData);
?>