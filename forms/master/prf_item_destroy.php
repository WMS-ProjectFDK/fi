<?php
$id = strval($_REQUEST['id']);
include("../connect/conn2.php");

$sql = "delete from item where item_no='$id'";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	$cek = "select name_image from ztb_prf_item where item_no='$id'";
	$cekNya = oci_parse($connect,$cek);
	oci_execute($cekNya);
	$dt = oci_fetch_array($cekNya);
	if(file_exists("upload/".$dt[0])){
		$path = "upload/".$dt[0];
		chown($path, 666);
		unlink($path);
	}
	$del2 = "delete from ztb_prf_item where item_no='$id'";
	$delNya = oci_parse($connect, $del2);
	oci_execute($delNya);
	if($delNya){
		echo json_encode(array('success'=>true));	
	}
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>