<?php
session_start();
if (isset($_SESSION['id_wms'])){
	$parent = htmlspecialchars($_REQUEST['menu_parent']);
	$kode_menu = htmlspecialchars($_REQUEST['kode_menu']);
	$nama_menu = htmlspecialchars($_REQUEST['nama_menu']);
	$kode_submenu = htmlspecialchars($_REQUEST['kode_submenu']);
	$nama_submenu = htmlspecialchars($_REQUEST['nama_submenu']);
	$link  = htmlspecialchars($_REQUEST['link']);

	$split_parent = split('-',$parent);
	$parent_id = $split_parent[0];
	$parent_name = $split_parent[1];

	$userentry = $_SESSION['id_wms'];

	include("../../connect/conn.php");

	$cek = "select count(*) as j from ztb_menu where id_menu='$kode_menu'";
	$data_cek = sqlsrv_query($connect, $cek);
	$dt_cek = sqlsrv_fetch_object($data_cek);

	if(($dt_cek->j)=='0'){
		$sql_equ = "insert into ztb_menu (menu_parent,
							 	  menu_name,
							 	  menu_sub_parent,
							 	  link,
							 	  last_update,
							 	  update_by,
							 	  id_parent,
							 	  id_menu,
							 	  id_sub_parent)
						values('$parent_name',
							   '$nama_menu',
							   '$nama_submenu', 
							   '$link',  
							   getdate(),
							   '$userentry',
							   '$parent_id',
							   '$kode_menu',
							   '$kode_submenu')";
		$data_equ = sqlsrv_query($connect, $sql_equ);
		if ($data_equ){
			echo json_encode("Success");
		} else {
			echo json_encode(array('errorMsg'=>'Dupplicate Name'));
		}
	}else{
		echo json_encode(array('errorMsg'=>'MENU ID Already Exist'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>