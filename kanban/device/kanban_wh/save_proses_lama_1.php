<?php
include("conn.php");

if(intval(date('H')) < 7){
	$hr = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-1,date("Y")));
}else{
	$hr = date('Y-m-d');
}
//echo $hr."<br/>";
$cek_jml = "select count(*) as jum from ztb_wh_kanban_trans where flag=0 and slip_no is null";
$data_cek_jml = oci_parse($connect, $cek_jml);
oci_execute($data_cek_jml);
$row_jml = oci_fetch_object($data_cek_jml);
$j = intval($row_jml->JUM);

if($j!=0){
	$cek = "select distinct wo_no, plt_no from ztb_wh_kanban_trans where flag=0 and slip_no is null";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	while($dt_cek = oci_fetch_object($data_cek)){
		$kd = "select id as kode from ztb_wh_kanban_trans where wo_no='".$dt_cek->WO_NO."' and plt_no='".$dt_cek->PLT_NO."'";
		$data_kd = oci_parse($connect, $kd);
		oci_execute($data_kd);
		$row_kd = oci_fetch_object($data_kd);

		//cek slip_no di mte_header
		$sql_cek = "select count(*) as CEK_SLIP from mte_header where slip_no='".trim($row_kd->KODE)."' ";
		$data_sql_cek = oci_parse($connect, $sql_cek);
		oci_execute($data_sql_cek);
		$dt_sql_cek = oci_fetch_object($data_sql_cek);
		$jum = intval($dt_sql_cek->CEK_SLIP);

		if($jum == 0){
			$ins1 = "insert into mte_header(slip_no,
									   slip_date,
									   company_code,
									   slip_type,
									   display_type,
									   section_code)
								values('".trim($row_kd->KODE)."',
								   TO_DATE('$hr','yyyy-mm-dd'),
								   100001,
								   '21',
								   'C',
								   100)";
			$data_ins1 = oci_parse($connect, $ins1);
			oci_execute($data_ins1);

			if($data_ins1){
				$cek_brg = "select distinct a.*, b.uom_q, b.item, b.description, b.STOCK_SUBJECT_CODE, b.standard_price,
					(a.qty*b.standard_price) as amount, b.curr_code, b.cost_subject_code, b.cost_process_code, b.unit_stock from ztb_wh_kanban_trans a 
					inner join item b on a.item_no=b.item_no
					inner join unit c on b.uom_q = c.unit_code
					inner join ztb_m_plan d on a.id = d.id
					where a.wo_no='".$dt_cek->WO_NO."' and a.plt_no='".$dt_cek->PLT_NO."' and a.flag=0 and a.slip_no is null";
				$data_cek_brg = oci_parse($connect, $cek_brg);
				oci_execute($data_cek_brg);

				$line=1;
				while($dt_brg = oci_fetch_object($data_cek_brg)){
					$sql_cek_item = "select count(*) as cek_item from mte_details where slip_no='".trim($row_kd->KODE)."' and item_no=".$dt_brg->ITEM_NO."";
					$data_sql_cek_item = oci_parse($connect, $sql_cek_item);
					oci_execute($data_sql_cek_item);
					$dt_sql_cek_item = oci_fetch_object($data_sql_cek_item);
					$jum_item = intval($dt_sql_cek_item->CEK_ITEM);

					if($jum_item==0){
						$ins2 = "insert into mte_details (slip_no,
														 line_no,
														 item_no,
														 qty,
														 uom_q,
														 cost_process_code,
														 reg_date,
														 upto_date,
														 wo_no,
														 remark)
												values('".trim($row_kd->KODE)."',
														".$line.",
														".$dt_brg->ITEM_NO.",
														".$dt_brg->QTY.",
														'".$dt_brg->UOM_Q."',
														".$dt_brg->COST_PROCESS_CODE.",
														TO_DATE('$hr','yyyy-mm-dd'),
														TO_DATE('$hr','yyyy-mm-dd'),
														'".$dt_brg->WO_NO."',
														'KANBAN'
														)";
						$data_ins2 = oci_parse($connect, $ins2);
						oci_execute($data_ins2);

						$split = split('-', $hr);
						$now_month = intval($split[0].$split[1]);
						$month_acc = intval($split[0].$split[1]);

						if($data_ins2){
							$upd = "update ztb_wh_kanban_trans set flag=1, slip_no='".trim($row_kd->KODE)."' 
								where wo_no='".$dt_cek->WO_NO."' and plt_no='".$dt_cek->PLT_NO."' and item_no='".$dt_brg->ITEM_NO."'";
							$data_upd = oci_parse($connect, $upd);
							oci_execute($data_upd);
						}
					}
					$line++;
				}
			}
			// UPDATE UPLOAD ZTB_M_PLAN
			$upd2 = "update ztb_m_plan set upload=1 where wo_no='".$dt_cek->WO_NO."' and plt_no=".$dt_cek->PLT_NO."";
			$data_upd2 = oci_parse($connect, $upd2);
			oci_execute($data_upd2);
		}
	}
	echo "SIMPAN DATA SUKSES..!!<br/>";
}else{
	echo "DATA TIDAK ADA..!!<br/>";
}
?>