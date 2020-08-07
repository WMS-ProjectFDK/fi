<?php
session_start();
if (isset($_SESSION['id_wms'])){
	// $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id']) : NULL;
	// $parent = isset($_REQUEST['menu_parent']) ? $_REQUEST['menu_parent']) : NULL;
	// $kode_menu = isset($_REQUEST['kode_menu']) ? $_REQUEST['kode_menu']) : NULL;
	// $nama_menu  = isset($_REQUEST['nama_menu']) ? $_REQUEST['nama_menu']) : NULL;
	
	// $link  = isset($_REQUEST['link']) ? $_REQUEST['link']) : NULL;
	
	$menu_id = htmlspecialchars($_REQUEST['menu_id']);
	$parent = htmlspecialchars($_REQUEST['menu_parent']);
	$kode_menu = htmlspecialchars($_REQUEST['kode_menu']);
	$nama_menu  = htmlspecialchars($_REQUEST['nama_menu']);

	$kode_submenu = htmlspecialchars($_REQUEST['kode_submenu']) != '' ? htmlspecialchars($_REQUEST['kode_submenu']) : NULL ;
	$nama_submenu = htmlspecialchars($_REQUEST['nama_submenu']) != '' ? htmlspecialchars($_REQUEST['nama_submenu']) : NULL ;

	$link  = htmlspecialchars($_REQUEST['link']);

	$split_parent = explode('-', $parent);
	$parent_id = $split_parent[0];
	$parent_name = $split_parent[1];

	$userentry = $_SESSION['id_wms'];

	include("../../connect/conn.php");

	$sql_equ = "update ztb_menu set menu_name='$nama_menu', 
									link='$link', 
									menu_parent = '$parent_name', 
									id_parent = '$parent_id', 
									id_menu = '$kode_menu',
									last_update = getdate(), 
									update_by='$userentry',
									id_sub_parent = '$kode_submenu',
									menu_sub_parent = '$nama_submenu'
				where id=$menu_id";
	// echo $sql_equ;
	$result = sqlsrv_query($connect, $sql_equ);

	// if (($kode_menu == '' OR is_null($kode_menu)) AND ($nama_submenu == '' OR is_null($nama_submenu))) {
		$upd = "update ZTB_MENU set MENU_SUB_PARENT=NULL, ID_SUB_PARENT=NULL 
			where ID_SUB_PARENT is null OR ID_SUB_PARENT = '' ";
		$rs = sqlsrv_query($connect, $upd);
	// }
	
	if ($result){
		echo json_encode("Success");
	} else {
		echo json_encode(array('errorMsg'=>'Dupplicate Name'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>