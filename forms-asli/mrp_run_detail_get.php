<?php
	session_start();
	$Bulan = isset($_REQUEST['Bulan']) ? strval($_REQUEST['Bulan']) : '';
	$Tahun = isset($_REQUEST['Tahun']) ? strval($_REQUEST['Tahun']) : '';
	

	
	
	include("../connect/conn.php");

	$sql = "select NVL(SUBSTR(assy_line, 0, INSTR(assy_line, '#')-1), 'assy_line') || '-' ||cell_type as CELL_GRADE,sum(qty) QUANTITY, upload_time,bulan,tahun,tot ,case when tot=0 then 'N' else 'Y' end as ket
  from ztb_assy_plan aa
  left outer join
	(select bulan bulanx, tahun tahunx, upload_time up, nvl(count(distinct tanggal),0) as tot from ztb_assy_plan where used=1 
	group by bulan, tahun, upload_time) bb 
	on aa.bulan=bb.bulanx AND aa.tahun=bb.tahunx
	where bulan = $Bulan and tahun = '2018' and used=1 
  group by bulan,tahun,upload_time,NVL(SUBSTR(assy_line, 0, INSTR(assy_line, '#')-1), 'assy_line') || '-' ||cell_type,tot
  order by CELL_GRADE";

	
  

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);

		

		$q = $items[$rowno]->QUANTITY;
		$items[$rowno]->QUANTITY = number_format($q);

		//$e = $items[$rowno]->QTY_PRODUKSI;
		//$items[$rowno]->QTY_PRODUKSI = $e;//'<a href="javascript:void(0)" title="'.$e.'"   style="text-decoration: none; color: black;">'number_format($e).'</a>';

		// $e = $items[$rowno]->QTY_PRODUKSI;
		// $items[$rowno]->QTY_PRODUKSI = '<a href="javascript:void(0)" title="'.$e.'" onclick="info_kuraire('.$w.')"  style="text-decoration: none; color: black;">'.number_format($e).'</a>';

		// $f = $items[$rowno]->QTY_INVOICED;
		// $items[$rowno]->QTY_INVOICED = '<a href="javascript:void(0)" title="'.$f.'" onclick="info_invoiced('.$w.')"  style="text-decoration: none; color: black;">'.number_format($f).'</a>';
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>