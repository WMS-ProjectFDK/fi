<?php
session_start();
if (isset($_SESSION['id_wms'])){
	$nik = trim(htmlspecialchars($_REQUEST['nik']));
	$userid = htmlspecialchars($_REQUEST['userid']);
	$nameUSR = htmlspecialchars($_REQUEST['nameUSR']);
	$txt_email = htmlspecialchars($_REQUEST['txt_email']);
	$cmb_dept = htmlspecialchars($_REQUEST['cmb_dept']);
	$password = htmlspecialchars($_REQUEST['password']);
	$newpassword =htmlspecialchars($_REQUEST['newpassword']);
	
	$arrayDept = array('C' => 'COMPONENT',
					   'A' => 'ASSEMBLING',
					   'F' => 'FINISHING',
					   'Q' => 'QUALITY CONTROL',
					   'P' => 'PE',
					   'S' => 'SCM',
					   'G' => 'GA'
				);
	$desc_dept = $arrayDept[$cmb_dept];

	include("../connect/conn.php");

	$qry = "select person_code, person, email, password from person where person_code='$nik'";
	$data = sqlsrv_query($connect, $qry);
	$dt = sqlsrv_fetch_object($data);

	//echo json_encode(array('errorMsg'=>$password));

	if ($dt->password == $password){
		$sql = "update person set person='$nameUSR', email='$txt_email', password='$newpassword' where person_code='$nik'";
		$dts = sqlsrv_query($connect, $sql);

		if($dts){
			$sql2 = "update ztb_person_dept set id_dept='$cmb_dept', description='$desc_dept' where person_code='$nik'";
			$dts2 = sqlsrv_query($connect, $sql2);
			
			if($dts2){
				echo json_encode(array('successMsg'=>'Update profile Success'));	
			}else{
				echo json_encode(array('errorMsg'=>'Update profile error'));	
			}
		}else{
			echo json_encode(array('errorMsg'=>'insert data error'));
		}
	}else{
		echo json_encode(array('errorMsg'=>'Old password error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>