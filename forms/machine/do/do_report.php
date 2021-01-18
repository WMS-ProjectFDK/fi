<?php 
//error_reporting(0);
include("../../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
ini_set('memory_limit', '-1');
set_time_limit(-1);
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$date=date("d M y / H:i:s",time());
$result = array();

$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
$cmb_slip_no = isset($_REQUEST['cmb_slip_no']) ? strval($_REQUEST['cmb_slip_no']) : '';
$ck_slip_no = isset($_REQUEST['ck_slip_no']) ? strval($_REQUEST['ck_slip_no']) : '';
$cmb_slip_type = isset($_REQUEST['cmb_slip_type']) ? strval($_REQUEST['cmb_slip_type']) : '';
$ck_slip_type = isset($_REQUEST['ck_slip_type']) ? strval($_REQUEST['ck_slip_type']) : '';
$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
$txt_item_name = isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';
$ck_sts_approval = isset($_REQUEST['ck_sts_approval']) ? strval($_REQUEST['ck_sts_approval']) : '';
$cmb_sts_approval = isset($_REQUEST['cmb_sts_approval']) ? strval($_REQUEST['cmb_sts_approval']) : '';

function clean($string) {
    $res = str_ireplace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $string);
    return $res;
}

if ($ck_date != "true"){
	$date = "a.slip_date between '$date_awal' and '$date_akhir' and ";
}else{
	$date = " ";
}

if ($ck_slip_no != "true"){
	$slip_no = "a.slip_no = '$cmb_slip_no' and ";
}else{
	$slip_no = " ";
}

if ($ck_slip_type != "true"){
	$slip_type = "a.slip_type = '$cmb_slip_type' and ";
}else{
	$slip_type = " ";
}

if ($ck_item_no != "true"){
	$item_no = "mtd.slip_no = '$cmb_item_no' and ";
}else{
	$item_no = " ";
}

$sts_appr = '';
if ($ck_sts_approval != "true"){
	if($cmb_sts_approval=='0'){
		$sts_appr = "a.approval_date is null and ";
	}elseif($cmb_sts_approval=='1'){
		$sts_appr = "a.approval_date is not null and ";
	}
}else{
	$sts_appr = " ";
}

$where = "where $date $slip_no $slip_type $item_no $sts_appr a.slip_no is not null ";

$sql = "select a.SLIP_NO, CAST(a.SLIP_DATE as varchar(10)) as SLIP_DATE, a.SLIP_TYPE, a.cost_process_code, b.COST_PROCESS_NAME,
	a.cp_section_code, c.CP_SECTION_NAME, mtd.ITEM_NO, 
	replace(replace(i.DESCRIPTION_ORG,'&nbsp;',''),'%[^0-9a-zA-Z]%','-') as description, 
	un.UNIT, mtd.QTY,
	isnull((select top 1 aa.U_PRICE from SP_GR_DETAILS aa
	 inner join SP_GR_HEADER bb on aa.GR_NO=bb.GR_NO
	 where ITEM_NO=mtd.ITEM_NO order by bb.GR_DATE desc),0) as price, 
	0.00007 as kurs,
	(mtd.QTY *
	isnull((select top 1 aa.U_PRICE from SP_GR_DETAILS aa
	 inner join SP_GR_HEADER bb on aa.GR_NO=bb.GR_NO
	 where ITEM_NO=mtd.ITEM_NO order by bb.GR_DATE desc),1)
	)*
	0.00007 as amount
	from SP_MTE_HEADER a
	left join SP_MTE_DETAILS mtd on a.SLIP_NO = mtd.SLIP_NO
	left outer join SP_ITEM i on mtd.ITEM_NO = i.ITEM_NO
	left outer join sp_unit un on mtd.UOM_Q = un.UNIT_CODE
	left outer join SP_COSTPROCESS b on a.cost_process_code=b.COST_PROCESS_CODE
	left outer join SP_COSTPROCESS_SECTION c on a.cp_section_code = c.CP_SECTION_CODE
	--left outer join RPT_MATERIAL_TRANSACTION rpt on a.slip_no = rpt.TRANS_CODE and mtd.ITEM_NO = rpt.ITEM_NO
	$where
	order by c.CP_SECTION_NAME, a.slip_date, a.slip_no";
// echo $sql;

$out = "SLIP_NO, SLIP_DATE, SLIP_TYPE, COST PROCESS CODE, COST_PROCESS_NAME, SECTION CODE, SECTION NAME, ITEM_NO, DESCRIPTION, UNIT, QTY, PRICE, KURS, AMOUNT";
$out .= "\n";
$results = sqlsrv_query($connect, strtoupper($sql));
while ($l = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
	foreach($l AS $key => $value){
		$pos = strpos(strval($value), '"');
		if ($pos !== false) {
			$value = str_replace('"', '\"', $value);
		}
		// $out .= '"'.$value.'",';
		$out .= clean($value).",";
	}
	$out .= "\n";
	
}
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=report_issue_entry.csv");
echo $out;
?>
