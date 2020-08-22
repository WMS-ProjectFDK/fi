<?php
session_start();
include("../connect/conn_kanbansys.php");
header("Content-type: application/json");

$date_prod = isset($_REQUEST['date_prod']) ? strval($_REQUEST['date_prod']) : '';
$Line = isset($_REQUEST['Line']) ? strval($_REQUEST['Line']) : '';
$cell_type = isset($_REQUEST['cell_type']) ? strval($_REQUEST['cell_type']) : '';
$sts = isset($_REQUEST['sts']) ? strval($_REQUEST['sts']) : '';

$date = split('-',$date_prod);

$Hari = intval($date[2]);
$Bulan = intval($date[1]);
$Tahun = intval($date[0]);

if ($cell_type!=''){
	$cell = "a.cell_type='$cell_type' and ";
}else{
	$cell = "";
}

$string = "where  a.tanggal='$Hari' and  a.bulan='$Bulan' and  a.tahun='$Tahun' and replace(a.assy_line,'#','-') = '$Line' and $cell USED=1";

if ($sts == 'KANBAN'){
	$sql_h = "select a.*, CEILING(a.qty/b.qty_box) as jumlahBox,CEILING(qty/ b.qty_total) as jumlahPallet, b.qty_total, b.qty_box_pallet, b.qty_box from ztb_assy_plan a 
	inner join ztb_assy_set_pallet b on a.assy_line = b.assy_line $string ";
}elseif ($sts == 'LEBIH') {
	$sql_h = "select * from ztb_assy_set_pallet where assy_line != 'LR03#3' AND replace(assy_line,'#','-') = '$Line'
		order by assy_line asc";
}

$result = odbc_exec($connect, $sql_h);

$arrData = array();		$arrID = array();
$noid = '';
$arrNo = 0;		$rowno=0;
while ($data=odbc_fetch_object($result)){
	if ($sts == 'KANBAN'){
		$tgl = $data->TANGGAL."/".$data->BULAN."/".$data->TAHUN;
		$jmlperPallet = intval($data->jumlahPallet);
		$qtyperpallet = $data->qty_total;
		$qty = $data->QTY;
		$qty_b = $data->qty_box;
		$qty_bp = $data->qty_box_pallet;
		$no_plt=1;

		for ($i=1; $i<=$jmlperPallet; $i++) {
			if($qty>0){
				$t = floatval($qty-$qtyperpallet);
				if($t<0) {
					$qty_save = $qty;
					$box = ceil($qty/$qty_b);
				}else{
					$qty_save = $qtyperpallet;
					$box = $qty_bp;
				}
			}

			$ins = "insert into ztb_assy_print(asyline, cell_type, date_prod, pallet, qty, id_plan,box, upto_date)
				VALUES ('".$data->ASSY_LINE."', '".$data->CELL_TYPE."', CONVERT(DATE, '".$date_prod."'), ".$i.", ".$qty_save.", '".$data->ID_PLAN."', ".$box.", '".date('Y-m-d H:i:s')."')";
			$data_in = odbc_exec($connect, $ins);
			
			$qry = "select max(id) as id_print from ztb_assy_print where id_plan='".$data->ID_PLAN."' and pallet= ".$i." ";
			$data_cek = odbc_exec($connect, $qry);
			$dt = odbc_fetch_object($data_cek);

			array_push($arrID, $dt->id_print);
			
			$qty = $t;
			$rowno++;
		}
	}elseif ($sts == 'LEBIH'){
		for ($j=1; $j<=9; $j++) {
			$ins = "insert into ztb_assy_print(asyline, cell_type, date_prod, pallet, qty, id_plan,box, upto_date)
				VALUES ('".$data->ASSY_LINE."', '0', CONVERT(DATE, '".$date_prod."'), ".$j.", ".$data->QTY_TOTAL.", 'LEBIH', ".$data->QTY_BOX_PALLET.", '".date('Y-m-d H:i:s')."')";
			$data_in = odbc_exec($connect, $ins);
		}

		$qry = "select top 9 id as id_print from ztb_assy_print where id_plan='LEBIH' AND cell_type = '0' order by id desc";
		$data_cek = odbc_exec($connect, $qry);
		
		while ($dt = odbc_fetch_object($data_cek)){
			array_push($arrID, $dt->id_print);
		}

		$rowno++;
	}
	
}

if($rowno != 0){
	$noid = implode("-",$arrID);
	$arrData[$arrNo] = array("kode"=>$noid);
}else{
	$arrData[$arrNo] = array("kode"=>'FAILED');
}
echo json_encode($arrData);
?>