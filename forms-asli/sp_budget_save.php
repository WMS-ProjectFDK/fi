<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn2.php");
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$user = $_SESSION['id_wms'];
	$msg = '';

	foreach($queries as $query){
		$DEPARTMENT = $query->DEPARTMENT;
		$STS = $query->STS;
		$JANUARY = $query->JANUARY;
		$FEBRUARY = $query->FEBRUARY;
		$MARCH = $query->MARCH;
		$APRIL = $query->APRIL;
		$MAY = $query->MAY;
		$JUNE = $query->JUNE;
		$JULY = $query->JULY;
		$AUGUST = $query->AUGUST;
		$SEPTEMBER = $query->SEPTEMBER;
		$OCTOBER = $query->OCTOBER;
		$NOVEMBER = $query->NOVEMBER;
		$DECEMBER = $query->DECEMBER;
		
		$TAHUN = $query->TAHUN;

		if ($STS == 'NEW') {
			if($DEPARTMENT=='ASSEMBLING'){
				$ins1 = "insert into ztb_sp_budget (TAHUN, DEPARTMENT, JANUARY, FEBRUARY, MARCH, APRIL, MAY, JUNE, JULY, AUGUST, SEPTEMBER, OCTOBER, NOVEMBER, DECEMBER) 
					VALUES ('$TAHUN','$DEPARTMENT', $JANUARY, $FEBRUARY, $MARCH, $APRIL, $MAY, $JUNE, $JULY, $AUGUST, $SEPTEMBER, $OCTOBER, $NOVEMBER, $DECEMBER)";
			}

			if($DEPARTMENT=='FINISHING'){
				$ins1 = "insert into ztb_sp_budget (TAHUN, DEPARTMENT, JANUARY, FEBRUARY, MARCH, APRIL, MAY, JUNE, JULY, AUGUST, SEPTEMBER, OCTOBER, NOVEMBER, DECEMBER) 
					VALUES ('$TAHUN','$DEPARTMENT', $JANUARY, $FEBRUARY, $MARCH, $APRIL, $MAY, $JUNE, $JULY, $AUGUST, $SEPTEMBER, $OCTOBER, $NOVEMBER, $DECEMBER)";
			}

			if($DEPARTMENT=='PE'){
				$ins1 = "insert into ztb_sp_budget (TAHUN, DEPARTMENT, JANUARY, FEBRUARY, MARCH, APRIL, MAY, JUNE, JULY, AUGUST, SEPTEMBER, OCTOBER, NOVEMBER, DECEMBER) 
					VALUES ('$TAHUN','$DEPARTMENT', $JANUARY, $FEBRUARY, $MARCH, $APRIL, $MAY, $JUNE, $JULY, $AUGUST, $SEPTEMBER, $OCTOBER, $NOVEMBER, $DECEMBER)";
			}

			if($DEPARTMENT=='COMPONENT'){
				$ins1 = "insert into ztb_sp_budget (TAHUN, DEPARTMENT, JANUARY, FEBRUARY, MARCH, APRIL, MAY, JUNE, JULY, AUGUST, SEPTEMBER, OCTOBER, NOVEMBER, DECEMBER) 
					VALUES ('$TAHUN','$DEPARTMENT', $JANUARY, $FEBRUARY, $MARCH, $APRIL, $MAY, $JUNE, $JULY, $AUGUST, $SEPTEMBER, $OCTOBER, $NOVEMBER, $DECEMBER)";
			}

			if($DEPARTMENT=='QC'){
				$ins1 = "insert into ztb_sp_budget (TAHUN, DEPARTMENT, JANUARY, FEBRUARY, MARCH, APRIL, MAY, JUNE, JULY, AUGUST, SEPTEMBER, OCTOBER, NOVEMBER, DECEMBER) 
					VALUES ('$TAHUN','$DEPARTMENT', $JANUARY, $FEBRUARY, $MARCH, $APRIL, $MAY, $JUNE, $JULY, $AUGUST, $SEPTEMBER, $OCTOBER, $NOVEMBER, $DECEMBER)";
			}

			$data_ins1 = oci_parse($connect, $ins1);
			oci_execute($data_ins1);
			$pesan = oci_error($data_ins1);
			$msg .= $pesan['message'];

			if($msg != ''){
				$msg .= " Update budget Process Error : $ins1";
				break;
			}
		} elseif ($STS = 'EDIT'){
			if($DEPARTMENT=='ASSEMBLING'){
				$ins2 = "  update ztb_sp_budget set JANUARY = $JANUARY, FEBRUARY = $FEBRUARY, MARCH = $MARCH, APRIL = $APRIL, MAY = $MAY, JUNE = $JUNE, JULY = $JULY, AUGUST = $AUGUST, SEPTEMBER = $SEPTEMBER, OCTOBER = $OCTOBER, NOVEMBER  = $NOVEMBER , DECEMBER = $DECEMBER where DEPARTMENT = '$DEPARTMENT' and tahun = '$TAHUN'" ;
			}

			if($DEPARTMENT=='FINISHING'){
				$ins2 = "  update ztb_sp_budget set JANUARY = $JANUARY, FEBRUARY = $FEBRUARY, MARCH = $MARCH, APRIL = $APRIL, MAY = $MAY, JUNE = $JUNE, JULY = $JULY, AUGUST = $AUGUST, SEPTEMBER = $SEPTEMBER, OCTOBER = $OCTOBER, NOVEMBER  = $NOVEMBER , DECEMBER = $DECEMBER where DEPARTMENT = '$DEPARTMENT' and tahun = '$TAHUN'" ;
			}

			if($DEPARTMENT=='PE'){
				$ins2 = "  update ztb_sp_budget set JANUARY = $JANUARY, FEBRUARY = $FEBRUARY, MARCH = $MARCH, APRIL = $APRIL, MAY = $MAY, JUNE = $JUNE, JULY = $JULY, AUGUST = $AUGUST, SEPTEMBER = $SEPTEMBER, OCTOBER = $OCTOBER, NOVEMBER  = $NOVEMBER , DECEMBER = $DECEMBER where DEPARTMENT = '$DEPARTMENT' and tahun = '$TAHUN'" ;
			}

			if($DEPARTMENT=='COMPONENT'){
				$ins2 = "  update ztb_sp_budget set JANUARY = $JANUARY, FEBRUARY = $FEBRUARY, MARCH = $MARCH, APRIL = $APRIL, MAY = $MAY, JUNE = $JUNE, JULY = $JULY, AUGUST = $AUGUST, SEPTEMBER = $SEPTEMBER, OCTOBER = $OCTOBER, NOVEMBER  = $NOVEMBER , DECEMBER = $DECEMBER where DEPARTMENT = '$DEPARTMENT' and tahun = '$TAHUN'" ;
			}

			if($DEPARTMENT=='QC'){
				$ins2 = "  update ztb_sp_budget set JANUARY = $JANUARY, FEBRUARY = $FEBRUARY, MARCH = $MARCH, APRIL = $APRIL, MAY = $MAY, JUNE = $JUNE, JULY = $JULY, AUGUST = $AUGUST, SEPTEMBER = $SEPTEMBER, OCTOBER = $OCTOBER, NOVEMBER  = $NOVEMBER , DECEMBER = $DECEMBER where DEPARTMENT = '$DEPARTMENT' and tahun = '$TAHUN'" ;
			}

			$data_ins2 = oci_parse($connect, $ins2);
			oci_execute($data_ins2);
			$pesan = oci_error($data_ins2);
			$msg .= $pesan['message'];

			if($msg != ''){
				$msg .= " Update budget Process Error : $ins2";
				break;
			}
		}
	}

	if($msg != ''){
		echo json_encode(array('errorMsg'=> $msg));
	}else{
		echo json_encode(array('successMsg'=>'success'));
	}
}else{
	echo json_encode(array('errorMsg'=> 'Session expired'));
}
?>