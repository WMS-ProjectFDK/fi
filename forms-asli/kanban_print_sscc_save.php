<?php
session_start();
// error_reporting(0);
// header("Content-type: application/json");
date_default_timezone_set('Asia/Jakarta');
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	include ("sscc_func.php");

	$user = $_SESSION['id_wms'];
	// $date = date("y-m-d H:i:s",time());
	$date = date('Y-m-d H:i:s');
	$wo_no = isset($_REQUEST['wo']) ? strval($_REQUEST['wo']) : '';
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	
	$response = array();
	$msg = '';
	$no = 1;

	$qry = "select a.work_order, a.batery_type, a.cell_grade, a.po_no,
		soh.so_no, sod.customer_part_no, a.item_no, sod.origin_code, a.qty, sod.uom_q,
		sod.customer_po_line_no, i.aging_day,
		sod.ASIN,sod.AMAZON_PO_NO,com.address1,com.address2,com.address3,com.address4
		from mps_header a
		inner join so_header soh on a.po_no = soh.customer_po_no
		inner join so_details sod on soh.so_no=sod.so_no and a.po_line_no=sod.line_no
		left outer join item i on a.item_no=i.item_no
		left outer join company com on soh.consignee_code = cast(com.company as varchar(100))
		where a.work_order='$wo_no'";
	$dataH = oci_parse($connect, $qry);
	oci_execute($dataH);
	$pesan = oci_error($dataH);
	$row = oci_fetch_object($dataH);
	$msg .= $pesan['message'];

	if($msg != ''){
		$msg .= " query header Error : $pesan";
		break;
	}else{
		foreach($queries as $query){
			$sscc_wo = $query->sscc_wo;
			$sscc_plt = $query->sscc_plt;
			$sscc_item = $query->sscc_item;
			$sscc_qtyTotal = $query->sscc_qtyTotal;
			$sscc_pcs_percarton = $query->sscc_pcs_percarton;
			$sscc_qty_carton = $query->sscc_qty_carton;
			$sscc_country_code = $query->sscc_country_code;
			$sscc_start_carton = $query->sscc_start_carton;

			$START = intval($sscc_start_carton);
			$tot_carton = intval($sscc_qtyTotal/$sscc_pcs_percarton);
			$finish = ($START+1)+$tot_carton;
			$start_per_carton = $sscc_start_carton+(($sscc_plt-1)*$sscc_qty_carton)+1;
			$no_carton_to = $sscc_qty_carton+(($sscc_plt-1)*$sscc_qty_carton);
			$no_carton_from = ($no_carton_to-$sscc_qty_carton)+1;
			$FromCarton = intval($start_per_carton);
			$toCarton = $FromCarton	+ intval($sscc_qty_carton);

			// echo $sscc_wo.' - '.$sscc_plt.' - '.substr($sscc_item,-3,3).' - '.$sscc_qtyTotal.' - '.$sscc_pcs_percarton.' - '.$sscc_qty_carton.' - '.$sscc_country_code.' - '.$sscc_start_carton.'<br>';
			// echo 'START:'.$START.'<br/>TOTAL CARTON:'.$tot_carton.'<br/>FINISH:'.$finish.'<br/>START PER PALLET :'.$start_per_carton.'<br/>';
			// echo 'NO SSCC: FROM '.$FromCarton.' TO '.$toCarton.'<br/>';
			// echo 'NO CARTON: FROM '.$no_carton_from.' TO '.$no_carton_to.'<br/>';
			// echo '<hr>';

			$ins ="insert into ztb_sscc_print_history (wo_no, plt_no, upto_date, user_id)
				VALUES ('$sscc_wo',$sscc_plt,'$date','$user')";
			$data_his = oci_parse($connect, $ins);
			oci_execute($data_his);

			for ($i=$FromCarton; $i<$toCarton ; $i++) {
				if($i<10){
					$n_urut = "00000".$i;
				}elseif($i>9 AND $i<100){
					$n_urut = "0000".$i;
				}elseif($i>99 AND $i<1000){
					$n_urut = "000".$i;
				}elseif($i>999 AND $i<10000){
					$n_urut = "00".$i;
				}elseif($i>9999 AND $i<100000){
					$n_urut = "0".$i;
				}elseif($i>99999 AND $i<1000000){
					$n_urut = $i;
				}

				$n = '04976680'.substr($sscc_item,-3,3).$n_urut;
				$sscc = sscc_kode_print($n);

				if($i<$finish){
					if ($sscc_country_code == '304' OR $sscc_country_code == '205' OR $sscc_country_code == '208' OR $sscc_country_code == '237' OR $sscc_country_code == '215' OR $sscc_country_code == '218' OR $sscc_country_code == '223' OR $sscc_country_code == '207' OR $sscc_country_code == '210' OR $sscc_country_code == '213' OR $sscc_country_code == '217'){
						$n = '04976680'.substr($sscc_item,-3,3).$n_urut;
						$sscc = sscc_kode_print($n);	
					}else{
						$n = '04976680'.substr($sscc_item,-3,3).$n_urut;
						$sscc = sscc_kode_print($n);
					}

					$response[] = array('PO' => trim($row->AMAZON_PO_NO),
										'ASIN' => trim($row->ASIN),
										'TOTAL_CARTON' => $tot_carton,
										'FROM_CARTON' => $no_carton_from,
										'TO_CARTON' => $tot_carton,
										'QTY' => $sscc_qtyTotal,
										'SSCC' => $sscc,
										'ADDRESS1' => $row->ADDRESS1,
										'ADDRESS2' => $row->ADDRESS2,
										'ADDRESS3' => $row->ADDRESS3,
										'ADDRESS4' => $row->ADDRESS4,
										'WO_NO' => $wo_no,
										'NO' => $i,
										'PLT_NO' => $sscc_plt
								);
					$fp = fopen('kanban_print_sscc_result_'.$user.'.json', 'w');
					fwrite($fp, json_encode($response));
					fclose($fp);

					$INS2 = "INSERT INTO ZTB_AMAZON_WO_DETAILS 
						SELECT '$wo_no', $i, $no_carton_from, $tot_carton, $tot_carton, '$sscc', $sscc_qtyTotal, '".trim($row->ASIN)."', '".trim($row->AMAZON_PO_NO)."', 
							'".str_replace("'", "''", $row->ADDRESS1)."', 
							'".str_replace("'", "''", $row->ADDRESS2)."', 
							'".str_replace("'", "''", $row->ADDRESS3)."', 
							'".str_replace("'", "''", $row->ADDRESS4)."', 
							sysdate, '$user', $sscc_plt FROM DUAL";
					//echo $n;
					// echo $INS2;
					$data_INS2 = oci_parse($connect, $INS2);
					$pesan = oci_error($data_INS2);
					oci_execute($data_INS2);
					$msg .= $pesan['message'];

					if($msg != ''){
						$msg .= " query header Error : $INS2";
						break;
					}
				}
				$no++;
				$no_carton_from++;
			}
	 	}
	}
	// echo json_encode($response);
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>