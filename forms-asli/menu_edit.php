<?php
session_start();
if (isset($_SESSION['id_wms'])){
	$menu_id = htmlspecialchars($_REQUEST['menu_id']);
	$parent = htmlspecialchars($_REQUEST['menu_parent']);
	$kode_menu = htmlspecialchars($_REQUEST['kode_menu']);
	$nama_menu  = htmlspecialchars($_REQUEST['nama_menu']);
	$kode_submenu = htmlspecialchars($_REQUEST['kode_submenu']);
	$nama_submenu = htmlspecialchars($_REQUEST['nama_submenu']);
	$link  = htmlspecialchars($_REQUEST['link']);

	$split_parent = split('-',$parent);
	$parent_id = $split_parent[0];
	$parent_name = $split_parent[1];

	$userentry = $_SESSION['id_wms'];

	include("../connect/conn.php");

	$sql_equ = "update ztb_menu set menu_name='$nama_menu', 
									link='$link', 
									menu_parent = '$parent_name', 
									id_parent = '$parent_id', 
									id_menu = '$kode_menu',
									last_update = TO_DATE('".date('Y-m-d')."','yyyy-mm-dd'), 
									update_by='$userentry',
									id_sub_parent = '$kode_submenu',
									menu_sub_parent = '$nama_submenu'
				where id=$menu_id";
	$result = oci_parse($connect, $sql_equ);
	oci_execute($result);	
	
	if ($result){
		echo json_encode("Success");
	} else {
		echo json_encode(array('errorMsg'=>'Dupplicate Name'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>