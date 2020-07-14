<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../connect/conn.php");
include ("sscc_func.php");

if (isset($_SESSION['id_wms'])){
	$wo = isset($_REQUEST['wo']) ? strval($_REQUEST['wo']) : '';
	$plt = isset($_REQUEST['plt']) ? strval($_REQUEST['plt']) : '';
	$response = array();
	$user = $_SESSION['id_wms'];

	$qry = "select a.work_order, a.batery_type, a.cell_grade, a.po_no,
		soh.so_no, sod.customer_part_no, a.item_no, sod.origin_code, a.qty, sod.uom_q,
		sod.customer_po_line_no, i.aging_day,
		sod.ASIN,sod.AMAZON_PO_NO,com.address1,com.address2,com.address3,com.address4
		from mps_header a
		inner join so_header soh on a.po_no = soh.customer_po_no
		inner join so_details sod on soh.so_no=sod.so_no and a.po_line_no=sod.line_no
		left outer join item i on a.item_no=i.item_no
		left outer join company com on soh.consignee_code = cast(com.company as varchar(100))
		where a.work_order='$wo'";
	$dataH = oci_parse($connect, $qry);
	oci_execute($dataH);
	$pesan = oci_error($dataH);
	$rowH = oci_fetch_object($dataH);
	$msg .= $pesan['message'];

	if($msg != ''){
		$msg .= " query header Error : $pesan";
		break;
	}else{
		$sql = "select wo,z.item, start_carton+(($plt-1)*pallet_ctn_number) as StartCarton,
			total_carton, ceil(quantity/external_unit_number/pi.pallet_ctn_number) as TotalPallet,
			pi.pallet_ctn_number,external_unit_number/package_unit_number  as Units,start_carton as STRCTN 
			from ztb_amazon_wo  z
			inner join item i on z.item = i.item_no
			left outer join packing_information pi on i.pi_no = pi.pi_no 
			where wo = '$wo'";
		$dataNya = oci_parse($connect, $sql);
		oci_execute($dataNya);
		$pesan = oci_error($dataNya);
		$msg .= $pesan['message'];

		if($msg != ''){
			$msg .= " query Error : $pesan";
			break;
		}else{
			// loop carton
			while ($row = oci_fetch_object($dataNya)) {
				$no_carton_to = intval($row->PALLET_CTN_NUMBER)+(($plt-1)*intval($row->PALLET_CTN_NUMBER));
				$no_carton_from = ($no_carton_to-intval($row->PALLET_CTN_NUMBER))+1;

				$START = intval($row->STRCTN);
				$tot_carton = intval($row->TOTAL_CARTON);
				$finish = ($START+1)+$tot_carton;

				$FromCarton = intval($row->STARTCARTON)+1;
				$toCarton = $FromCarton	+ intval($row->PALLET_CTN_NUMBER);
				$unit = $row->UNITS;
				
				for ($i=$FromCarton; $i<$toCarton ; $i++) {
					if($i < 10){
						$n_urut = "00000".$i;
					}elseif($i>9 AND $i< 100){
						$n_urut = "0000".$i;
					}elseif($i>99 AND $i< 1000){
						$n_urut = "000".$i;
					}elseif($i>999 AND $i< 10000){
						$n_urut = "00".$i;
					}elseif($i>9999 AND $i< 100000){
						$n_urut = "0".$i;
					}elseif($i>99999 AND $i< 1000000){
						$n_urut = $i;
					}

					if ($START != 0){
						$n = '04976680'.substr($row->ITEM,-3,3).$n_urut;
						$sscc = '00'.sscc_kode_xls($n);
					}else{
						$n = '';
						$sscc = '';
					}

					if($i<$finish){
						$response[] = array('PO' => $rowH->AMAZON_PO_NO,
											'ASIN' => $rowH->ASIN,
											'TOTAL_CARTON' => $tot_carton,
											'FROM_CARTON' => $no_carton_from,
											'TO_CARTON' => $tot_carton,
											'QTY' => $rowH->QTY,
											'SSCC' => $sscc,
											'ID_CARTON' => $n_urut,
											'ADDRESS1' => $rowH->ADDRESS1,
											'ADDRESS2' => $rowH->ADDRESS2,
											'ADDRESS3' => $rowH->ADDRESS3,
											'ADDRESS4' => $rowH->ADDRESS4
									  );
					}
					$no_carton_from++;
				}
			}
		}
	}
	$fp = fopen('kanban_print_sscc_print_ulang_result_'.$user.'.json', 'w');
	fwrite($fp, json_encode($response));
	fclose($fp);
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode($response);
}
?>