<?php
session_start();
if (isset($_SESSION['id_wms'])){

	$user_id = trim(htmlspecialchars($_REQUEST['user_id']));
	$menu_id = htmlspecialchars($_REQUEST['menu_id']);
	$add = trim(htmlspecialchars($_REQUEST['add']));
	$edit = trim(htmlspecialchars($_REQUEST['edit']));
	$delete = trim(htmlspecialchars($_REQUEST['del']));
	$view = trim(htmlspecialchars($_REQUEST['view']));
	$print = trim(htmlspecialchars($_REQUEST['print']));
	$userentry = $_SESSION['id_wms'];

	include("../connect/conn.php");

	if($add=='TRUE'){
		$a = 'T';
	}else{
		$a = 'F';
	}

	if($edit=='TRUE'){
		$e = 'T';
	}else{
		$e = 'F';
	}

	if($delete=='TRUE'){
		$d = 'T';
	}else{
		$d = 'F';
	}

	if($view=='TRUE'){
		$v = 'T';
	}else{
		$v = 'F';
	}

	if($print=='TRUE'){
		$p = 'T';
	}else{
		$p = 'F';
	}

	$cek = "select count(*) as j from ztb_user_access where person_code = '$user_id' and menu_id=$menu_id ";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	$dt_cek = oci_fetch_object($data_cek);

	$jum = intval($dt_cek->J);
	echo $jum;
	if($jum > 0){
		$upd = "update ztb_user_access set access_add='$a', access_update='$e', access_delete='$d', access_view='$v', access_print='$p', last_update=TO_DATE('".date('Y-m-d')."','yyyy-mm-dd'), update_by='$userentry' where person_code='$user_id' and menu_id=$menu_id ";
		$result = oci_parse($connect, $upd);
		oci_execute($result);
	}else{
		$ins = "insert into ztb_user_access VALUES ('$user_id',$menu_id,'$a','$e','$d','$v','$p',TO_DATE('".date('Y-m-d')."','yyyy-mm-dd'),'$userentry','T')";
		$result = oci_parse($connect, $ins);
		oci_execute($result);
	}

	if ($result){
		echo json_encode("Success");
	} else {
		echo json_encode(array('errorMsg'=>'Dupplicate User ID'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>