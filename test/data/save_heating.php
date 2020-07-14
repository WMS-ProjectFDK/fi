<?php
	include("../../connect/conn.php");
	date_default_timezone_set('Asia/Jakarta');

	$items = array();
	$rowno=0;
	//$data = $_POST['data'];
	$data1 = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$data3 = json_decode(json_encode($data1));
	$str = preg_replace('/\\\\\"/',"\"", $data3);

	$queries = json_decode($str);

	 $now=date('Y-m-d H:i:s');

	//Looping
	foreach($queries as $query){
	$field = "";
	$value = "";

	$ID_PRINT =  $query->ID_PRINT;

	$ID_PLAN = $query->ID_PLAN;
	$STATUS = $query->STATUS;

	if($STATUS == 'IN'){
		$pos=1;
		$r = "IN-HEATING";
	}else{
		$pos=2;
		$r = "OUT-HEATING";
	}

	$field .= "ID_PRINT,"	;	$value .= "$ID_PRINT," 	;
	$field .= "ID_PLAN,"	;	$value .= "'$ID_PLAN',"	;
	$field .= "id_pallet,"	;	$value .= "'$ID_PLAN',"	;
	$field .= "position,"	;	$value .= "$pos,"		;
	$field .= "upto_date,"	;	$value .= "'$now',"		;
	$field .= "remark"		;	$value .= "'$r'" 		;
	chop($field);              	chop($value) ;

	$ins = "insert into ztb_assy_heating ($field) values ($value) ";
	$data_ins = oci_parse($connect, $ins);
	oci_execute($data_ins);
	$pesan = oci_error($data_ins);
	$msg = $pesan['message'];
	if($msg == ''){
		
	}else{
		$msg .= " Error pada proses upload data $ins";
		break;
	}

	};	
	if($msg == ''){
		$msg .= "Proses Berhasil.";
	}
	echo json_encode($msg);
?>