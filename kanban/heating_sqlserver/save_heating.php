<?php
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn_kanbansys.php");
$id_prin = isset($_REQUEST['id_prin']) ? strval($_REQUEST['id_prin']) : '';
$id_plan = isset($_REQUEST['id_plan']) ? strval($_REQUEST['id_plan']) : '';
$id_pall = isset($_REQUEST['id_pall']) ? strval($_REQUEST['id_pall']) : '';
$remarks = isset($_REQUEST['remarks']) ? strval($_REQUEST['remarks']) : '';
//$aging = isset($_REQUEST['aging']) ? strval($_REQUEST['aging']) : '';

$now=date('Y-m-d H:i:s');

if($remarks == 'IN'){
	$pos=1;
	$r = "IN-HEATING";
}else{
	$pos=2;
	$r = "OUT-HEATING";
}

if(! $connect){
	echo json_encode(array('errorMsg'=>'CONNECT TO SERVER FAILED ... !!'));
}else{
	$field .= "ID_PRINT,"	;	$value .= "$id_prin," 	;
	$field .= "ID_PLAN,"	;	$value .= "'$id_plan',"	;
	$field .= "id_pallet,"	;	$value .= "'$id_pall',"	;
	$field .= "position,"	;	$value .= "$pos,"		;
	$field .= "upto_date,"	;	$value .= "GETDATE(),"	;
	$field .= "remark"		;	$value .= "'$r'" 		;
	chop($field);              	chop($value) ;

	$ins = "insert into ztb_assy_heating ($field) values ($value) ";
	$data_ins = odbc_exec($connect, $ins);

	if ($data_ins){
		/*if($aging != 0 ){
			$upd = "update ztb_assy_kanban set ng_id = $aging where id_print = $id_prin";
			$data_upd = oci_parse($connect, $upd);
			oci_execute($data_upd);
		}*/
		echo json_encode(array('successMsg'=>$r));
	}else{
		echo json_encode(array('errorMsg'=>'Query Error'));
	}
}
?>