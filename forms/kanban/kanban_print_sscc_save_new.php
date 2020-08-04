<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();

if (isset($_SESSION['id_wms'])){
	include("../../connect/conn.php");
	$user = $_SESSION['id_wms'];
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$msg = '';

	foreach($queries as $query){
		$sscc_new_qty = $query->sscc_new_qty;
		$sscc_new_wo = $query->sscc_new_wo;
		$sscc_new_item = $query->sscc_new_item;
		$sscc_new_pcs_per_pallet = $query->sscc_new_pcs_per_pallet;
		$sscc_new_carton_per_pallet = $query->sscc_new_carton_per_pallet;
		$sscc_new_pcs_per_carton = $query->sscc_new_pcs_per_carton;
		$sscc_new_country_code = $query->sscc_new_country_code;

		$jum_carton = $sscc_new_qty / $sscc_new_pcs_per_carton;

		if ($sscc_new_country_code == '304' OR $sscc_new_country_code == '205' OR $sscc_new_country_code == '208' OR $sscc_new_country_code == '237' OR $sscc_new_country_code == '215' OR $sscc_new_country_code == '218' OR $sscc_new_country_code == '223' OR $sscc_new_country_code == '207' OR $sscc_new_country_code == '210' OR $sscc_new_country_code == '213' OR $sscc_new_country_code == '217'){
			$str = "Insert into ztb_amazon_wo (Quantity,Wo,From_Carton,To_Carton,Total_carton,Item,Start_carton) 
			select $sscc_new_qty,'$sscc_new_wo',1,$jum_carton,$jum_carton,'$sscc_new_item', isnull(sum(total_carton),0) 
			from ztb_amazon_wo where item= '$sscc_new_item'";
		}else{
			$str = "Insert into ztb_amazon_wo (Quantity,Wo,From_Carton,To_Carton,Total_carton,Item,Start_carton) 
			values($sscc_new_qty,'$sscc_new_wo',1,$jum_carton,$jum_carton,'$sscc_new_item',0)";
		}
		// echo $str;
		$data = sqlsrv_query($connect, $str);
		if( $data === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $str;
				}
			}
		}
	}
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>