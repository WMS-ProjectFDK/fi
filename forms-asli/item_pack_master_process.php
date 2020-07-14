<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../class/excel_reader.php";
include("../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount(0);
$pack_t = $data->rowcount(1);
$pack_g = $data->rowcount(2);

$success_i = 0;		$success_j = 0;		$success_k;
$failed = 0;

$Arr_i = array();
for ($i=2; $i<=$pack_t; $i++) {
	$Arr_i[] = array('KODE' => trim($data->val($i,1,1)),
					 'TYPE' => strtoupper(trim($data->val($i,2,1)))
			   );
	$fi = fopen('item_pack_master_json_pck.json', 'w');
	fwrite($fi, json_encode($Arr_i));
	fclose($fi);
	$success_i++;
}

$Arr_j = array();
for ($j=2; $j<=$pack_g; $j++) { 
	$Arr_j[] = array('KODE' => trim($data->val($j,1,2)),
					 'DESC' => strtoupper(trim($data->val($j,2,2))),
					 'PROCESS' => strtoupper(trim($data->val($j,3,2))),
					 'MACH' => strtoupper(trim($data->val($j,4,2)))
			   );
	$fj = fopen('item_pack_master_json_grp.json', 'w');
	fwrite($fj, json_encode($Arr_j));
	fclose($fj);
	$success_j++;	
}


$Arr_k = array();
for ($k=2; $k<=$hasildata; $k++) {
	$it = trim($data->val($k,2,0));
	$pt = trim($data->val($k,6,0));
	$pg = trim($data->val($k,7,0));

	if ($it != ''){
		$type_pack_t ='';
		for ($ipt=0; $ipt<count($Arr_i) ; $ipt++) { 
			if($pt == $Arr_i[$ipt]['KODE']){
				$type_pack_t = $Arr_i[$ipt]['TYPE'];
			}
		}

		$dsc = '';		$_2ndp = '';		$_2ndm = '';
		for ($ipg=0; $ipg<count($Arr_j) ; $ipg++) { 
			if($pg == $Arr_j[$ipg]['KODE']){
				$dsc = $Arr_j[$ipg]['DESC'];
				$_2ndp = $Arr_j[$ipg]['PROCESS'];
				$_2ndm = $Arr_j[$ipg]['MACH'];
			}	
		}

		$Arr_k[] = array('ITEM_NO' => trim($data->val($k,2,0)),
						 'OT' => trim($data->val($k,3,0)),
						 'MAN_POWER' => trim($data->val($k,4,0)),
						 'CAPACITY' => trim($data->val($k,5,0)),
						 'PACK_TYPE' => trim($data->val($k,6,0)),
						 'TYPE' => $type_pack_t,
						 'PACK_GROUP' => trim($data->val($k,7,0)),
						 'DESC' => $dsc,
						 'PROSES' => $_2ndp,
						 'MACH' => $_2ndm
				   );

		$str = "update item set 
					operation_time = ".trim($data->val($k,3,0)).", 
					man_power = ".trim($data->val($k,4,0)).", 
					capacity = ".trim($data->val($k,5,0)).", 
					package_type = '".trim($data->val($k,6,0))."', 
					label_type= ".trim($data->val($k,7,0))."
			where item_no = $it ";
		$data_str = oci_parse($connect, $str);
		oci_execute($data_str);

		$pesan = oci_error($data_str);
		$msg = $pesan['message'];
		if($msg != ''){
			$msg .= " Error pada proses update Master Item Query $str";
			break;
		}else{
			$del = "delete from ztb_item_pck where item_no= $it";
			$data_del = oci_parse($connect, $del);
			oci_execute($data_del);

			$pesan = oci_error($data_str);
			$msg = $pesan['message'];
			if($msg != ''){
				$msg .= " Error pada proses Delete Master Item Pack Query $del";
				break;
			}else{
				$ins = "insert into ztb_item_pck (item_no, operation_time, man_power, capacity, package_type, groups_pck, second_process, second_machine) VALUES ('$it','".trim($data->val($k,3,0))."','".trim($data->val($k,4,0))."','".trim($data->val($k,5,0))."','".$type_pack_t."','".$dsc."','".$_2ndp."','".$_2ndm."')";
				$data_ins = oci_parse($connect, $ins);
				oci_execute($data_ins);

				$pesan = oci_error($data_ins);
				$msg = $pesan['message'];
				if($msg != ''){
					$msg .= " Error pada proses Input Master Item Pack Query $ins";
					break;
				}else{
					$success_k++;
				}
			}
		}	
	}
}

if($msg == ''){
	echo json_encode("Success = ".$success_k."");
}else{
	echo json_encode("".$msg."");
}
?>