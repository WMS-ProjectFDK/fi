<?php
session_start();

$fc_trans_code = htmlspecialchars($_REQUEST['fc_trans_code']);
//$fc_datecreate = htmlspecialchars($_REQUEST['fc_datecreate']);
$kode_customer = htmlspecialchars($_REQUEST['kode_customer']);
$brg_codebarang = htmlspecialchars($_REQUEST['brg_codebarang']);
// $fc_nama_barang = htmlspecialchars($_REQUEST['fc_nama_barang']);
// $fc_kategori = htmlspecialchars($_REQUEST['fc_kategori']);
// $fc_prodfam = htmlspecialchars($_REQUEST['fc_prodfam']);
// $fc_satuan = htmlspecialchars($_REQUEST['fc_satuan']);
// $fc_abc_class = htmlspecialchars($_REQUEST['fc_abc_class']);
$fc_bulan1 = htmlspecialchars($_REQUEST['fc_bulan1']);
$fc_bulan2 = htmlspecialchars($_REQUEST['fc_bulan2']);
$fc_bulan3 = htmlspecialchars($_REQUEST['fc_bulan3']);
$fc_bulan4 = htmlspecialchars($_REQUEST['fc_bulan4']);
$fc_bulan5 = htmlspecialchars($_REQUEST['fc_bulan5']);
$fc_bulan6 = htmlspecialchars($_REQUEST['fc_bulan6']);
$fc_keterangan = htmlspecialchars($_REQUEST['fc_keterangan']);
$userentry = $_SESSION['sita_user_name'];

include("../connect/koneksi.php");

$sql = "update fc_customer set fc_datecreate=cast(now() as date), kode_customer='$kode_customer', brg_codebarang='$brg_codebarang', 
		fc_bulan1='$fc_bulan1', fc_bulan2='$fc_bulan2', 
		fc_bulan3='$fc_bulan3', fc_bulan4='$fc_bulan4', fc_bulan5='$fc_bulan5', fc_bulan6='$fc_bulan6', fc_keterangan='$fc_keterangan', 
		fc_user_create='$userentry', fc_last_update=cast(now() as date) 
		where fc_trans_code='$fc_trans_code'";
$result = @pg_query($sql);
if ($result){
	echo json_encode("Success");
} else {
	echo json_encode(array('errorMsg'=>'Error'));
}
?>