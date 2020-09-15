<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$user = $_SESSION['id_wms'];

	foreach($queries as $query){
		$PO = $query->PO;
		$ASIN = $query->ASIN;
		$TOTAL_CARTON = $query->TOTAL_CARTON;
		$FROM_CARTON = $query->FROM_CARTON;
		$TO_CARTON = $query->TO_CARTON;
		$QTY = $query->QTY;
		$SSCC = $query->SSCC;
		$ADDRESS1 = $query->ADDRESS1;
		$ADDRESS2 = $query->ADDRESS2;
		$ADDRESS3 = $query->ADDRESS3;
		$ADDRESS4 = $query->ADDRESS4;
		$WO_NO = $query->WO_NO;
		$NO = $query->NO;


		$response[] = array('PO' => trim($PO),
							'ASIN' => trim($ASIN),
							'TOTAL_CARTON' => $TOTAL_CARTON,
							'FROM_CARTON' => $FROM_CARTON,
							'TO_CARTON' => $TO_CARTON,
							'QTY' => $QTY,
							'SSCC' => $SSCC,
							'ADDRESS1' => $ADDRESS1,
							'ADDRESS2' => $ADDRESS2,
							'ADDRESS3' => $ADDRESS3,
							'ADDRESS4' => $ADDRESS4,
							'WO_NO' => $WO_NO,
							'NO' => $NO
					);
		$fp = fopen('sscc_non_mps_result_'.$user.'.json', 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
	}

echo json_encode('success');
}
?>