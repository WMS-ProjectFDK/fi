<?php
session_start();

$kode_customer = htmlspecialchars($_REQUEST['kode_customer']);
$brg_codebarang = htmlspecialchars($_REQUEST['brg_codebarang']);
$fc_bulan1 = htmlspecialchars($_REQUEST['fc_bulan1']);
$fc_bulan2 = htmlspecialchars($_REQUEST['fc_bulan2']);
$fc_bulan3 = htmlspecialchars($_REQUEST['fc_bulan3']);
$fc_bulan4 = htmlspecialchars($_REQUEST['fc_bulan4']);
$fc_bulan5 = htmlspecialchars($_REQUEST['fc_bulan5']);
$fc_bulan6 = htmlspecialchars($_REQUEST['fc_bulan6']);
$fc_keterangan = htmlspecialchars($_REQUEST['fc_keterangan']);
$userentry = $_SESSION['sita_user_name'];


include("../connect/koneksi.php");

$sql_id = "select to_char(now(), 'YYYYMM') || coalesce(lpad(cast(cast(substr(max(fc_trans_code), 7, 4) as integer)+1 as varchar(4)), 4, '0'),  '0001') as code 
			from fc_customer where extract(year from fc_datecreate) = extract(year from now())
			and extract(month from fc_datecreate) = extract(month from now())";
$query_id = pg_query($koneksi, $sql_id);
$row_id = pg_fetch_array($query_id);
$fc_trans_code = $row_id[0];


$sql =  "insert into fc_customer(fc_trans_code, fc_datecreate, kode_customer, brg_codebarang, 
		fc_bulan1, fc_bulan2, fc_bulan3, fc_bulan4, fc_bulan5, fc_bulan6, fc_keterangan, fc_user_create, fc_last_update) 
		values('$fc_trans_code', '".date('Y-m-d')."', '$kode_customer', '$brg_codebarang', 
		'$fc_bulan1', '$fc_bulan2', '$fc_bulan3', '$fc_bulan4', '$fc_bulan5', '$fc_bulan6', '$fc_keterangan', '$userentry', '".date('Y-m-d')."')";
$result = @pg_query($sql);

$sql_fcsupp =  "insert into forecast_supplier(fc_sup_transcode, fc_sup_datecreate, kode_customer, brg_codebarang, 
		fc_sup_bulan1, fc_sup_bulan2, fc_sup_bulan3, fc_sup_bulan4, fc_sup_bulan5, fc_sup_bulan6, fc_sup_keterangan, fc_sup_usercreate, fc_sup_lastupdate) 
		values('$fc_trans_code', '".date('Y-m-d')."', '$kode_customer', '$brg_codebarang', 
		'$fc_bulan1', '$fc_bulan2', '$fc_bulan3', '$fc_bulan4', '$fc_bulan5', '$fc_bulan6', '$fc_keterangan', '$userentry', '".date('Y-m-d')."')";
$result_fcsupp = @pg_query($sql_fcsupp);

if ($result){
	echo json_encode("Success");
} else {
	echo json_encode(array('errorMsg'=>'Error'));
}
?>

