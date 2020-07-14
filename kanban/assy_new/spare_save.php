<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
include("../../connect/conn_kanbansys.php");
$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$msg = '';

	foreach($queries as $query){
		$add_stss = $query->add_stss;
		$add_idno = $query->add_idno;
		$add_line = $query->add_line;
		$add_mach = $query->add_mach;
		$add_part = $query->add_part;
		$add_pano = $query->add_pano;
		$add_draw = $query->add_draw;
		$add_unit = $query->add_unit;
		$add_life = $query->add_life;
		$add_lead = $query->add_lead;
		$add_repl = $query->add_repl;

		if($add_stss == 'ADD'){
			$field  = "id_machine,"				;	$value .=  "'$add_mach',"		 			;
			$field .= "line,"					;	$value  =  "replace('$add_line','-','#')," 	;
			$field .= "nama_part,"				;	$value .=  "'$add_part',"		 			;
			$field .= "item_no,"				;	$value .=  "'$add_pano',"		 			;
			$field .= "drawing_no,"				;	$value .=  "'$add_draw',"		 			;
			$field .= "unit_qty,"				;	$value .=  "$add_unit,"		 				;
			$field .= "lifetime,"				;	$value .=  "$add_life,"		 				;
			$field .= "leadtime_week,"			;	$value .=  "$add_lead,"		 				;
			$field .= "tgl_ganti,"				;	$value .=  "CONVERT(DATE,'$add_repl'),"		;

			$field .= "current_lifetime,"		;	$value .=  "0,"								;
			$field .= "estimation_tgl_ganti,"	;	$value .=  "'',"							;
			$field .= "status,"					;	$value .=  "'',"							;
			$field .= "update_estimation"		;	$value .=  "''"								;


			$ins = "INSERT INTO assembly_part ($field) VALUES($value)";
			$rs = odbc_exec($connect,$ins);

			if (!$result = @odbc_exec($connect,$ins)) {
			   $msg .= "Error: ".odbc_errormsg()."<br>";
			}
		}elseif($add_stss == 'EDIT'){
			$fieldUpd  = "nama_part = '$add_part',"			   		;
			$fieldUpd .= "item_no = '$add_pano',"		 			;
			$fieldUpd .= "drawing_no = '$add_draw',"		 		;
			$fieldUpd .= "unit_qty = $add_unit,"		 			;
			$fieldUpd .= "lifetime = $add_life,"		 			;
			$fieldUpd .= "leadtime_week = $add_lead,"		 		;
			$fieldUpd .= "tgl_ganti = CONVERT(DATE,'$add_repl')"	;

			$upd = "UPDATE assembly_part set $fieldUpd where id_part = $add_idno";
			$rs = odbc_exec($connect,$upd);

			if (!$result = @odbc_exec($connect,$upd)) {
			   $msg .= "Error: ".odbc_errormsg()."<br>";
			}
		}elseif ($add_stss == 'REPLACE'){
			$upd1 = "UPDATE assembly_part set tgl_ganti= CONVERT(DATE,'$add_repl') where id_part=$add_idno";
			$rs = odbc_exec($connect,$upd1);

			if (!$result = @odbc_exec($connect,$upd1)) {
			   $msg .= "Error: ".odbc_errormsg()."<br>";
			}

			$field1  = "id_part,"		;	$value1 = "a.id_part,"		;
			$field1 .= "tgl_ganti,"		;	$value1 = "a.tgl_ganti,"	;
			$field1 .= "id_machine,"	;	$value1 = "a.id_machine,"	;
			$field1 .= "line,"			;	$value1 = "a.line,"			;
			$field1 .= "nama_part,"		;	$value1 = "a.nama_part,"	;
			$field1 .= "lifetime,"		;	$value1 = "a.lifetime,"		;
			$field1 .= "aktual_pc,"		;	$value1 = "$add_lead,"		;
			$field1 .= "pic"			;	$value1 = "add_life"		;

			$ins1 = "INSERT INTO assembly_part_history ($field1) select $value1 from assembly_part a where a.id_part=$add_idno";
			$rs = odbc_exec($connect,$ins1);

			if (!$result = @odbc_exec($connect,$ins1)) {
			   $msg .= "Error: ".odbc_errormsg()."<br>";
			}
		}

		/*-------------------------------*/
		if($msg != ''){
			echo json_encode($msg);
		}else{
			echo json_encode('success');
		}
	}
?>