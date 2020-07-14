<?php
	include("../../connect/conn_kanbansys.php");
	include("../../connect/conn1.php");

	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
	$machine = isset($_REQUEST['machine']) ? strval($_REQUEST['machine']) : '';

	if ($line == 'LR03-1'){
		$ln = 'LR03#1';
	}elseif ($line == 'LR03-2'){
		$ln = 'LR03#2';
	}elseif ($line == 'LR6-1'){
		$ln = 'LR06#1';
	}elseif ($line == 'LR6-2'){
		$ln = 'LR06#2';
	}elseif ($line == 'LR6-3'){
		$ln = 'LR06#3';
	}elseif ($line == 'LR6-4'){
		$ln = 'LR06#4(T)';
	}elseif ($line == 'LR6-5'){
		$ln = 'LR06#5';
	}elseif ($line == 'LR6-6'){
		$ln = 'LR06#6';
	}

	$sql = "select a.id_part, a.id_machine, b.machine, a.line, a.nama_part, a.unit_qty, a.tgl_ganti, a.lifetime, a.item_no,
	    a.status, a.drawing_no, a.leadtime_week,
	    DATEDIFF(day,a.tgl_ganti,getdate()) as selisih_hari,
		DATEDIFF(week,a.tgl_ganti,getdate()) as selisih_minggu
	    from assembly_part a
	    left outer join assembly_line_master b on a.id_machine = b.id_machine and a.line = b.line
	    where replace(a.line,'#','-') = '$line' and a.id_machine=$machine
	    order by a.id_part asc";	    
	$rs = odbc_exec($connect,$sql);

	$items = array();
	$rowno=0;
	while($row = odbc_fetch_object($rs)){
		array_push($items, $row);

		$m = $items[$rowno]->machine;
		$items[$rowno]->machine = strtoupper($m);

		$p = $items[$rowno]->nama_part;
		$items[$rowno]->nama_part = strtoupper($p);
    
	    $lifetime = $items[$rowno]->lifetime;
    	$items[$rowno]->lifetime_r = number_format($lifetime);

    	$tgl_ganti = $items[$rowno]->tgl_ganti;
    	$selisih_minggu = $items[$rowno]->selisih_minggu;

    	$sqlo = "select sum(qty_act_perpallet) as Total from ztb_assy_kanban 
			where assy_line = '$ln'
			and tanggal_actual between to_date ('$tgl_ganti','yyyy-mm-dd') and sysdate";
		$result_sqlo = oci_parse($connection, $sqlo);
		oci_execute($result_sqlo);
		$row_sqlo = oci_fetch_object($result_sqlo);
		
		// hitung
		$x = $row_sqlo->TOTAL/$selisih_minggu;
		$z = ($lifetime/$x) * 7;

		$f_tgl_ganti=strtotime($tgl_ganti);
		$NewjumlahHari=86400*$z;
		$hasilJumlah = $f_tgl_ganti + $NewjumlahHari;
		$tampilHasil=date("Y-m-d",$hasilJumlah);

		// leadtime (pcs)
		$leadtime_pcs = intval($items[$rowno]->leadtime_week * $x);
		$s_pcs = $row_sqlo->TOTAL - $leadtime_pcs;

		if($row_sqlo->TOTAL >= $leadtime_pcs AND $tampilHasil < date("Y-m-d")){
			$s= 'REPLACE';
		}else{
			if($row_sqlo->TOTAL < $leadtime_pcs AND $tampilHasil > date("Y-m-d")) {
				$s = 'ORDER';
			}else{
				$s = 'OK';
			}	
		}

		//	==================================== 

		$items[$rowno]->lifetime_c = number_format($row_sqlo->TOTAL);
		$items[$rowno]->x = number_format($x);
		$items[$rowno]->z = number_format($z);
		$items[$rowno]->estimation_replacment = $tampilHasil;
		$items[$rowno]->leadtime_pcs = $leadtime_pcs;
		$items[$rowno]->s_pcs = $s_pcs;

    	// if ($s == 'ORDER'){
    	// 	$items[$rowno]->status = '<span style="color:orange;font-size:11px;"><b>'.$s.'</b></span>';
    	// }elseif($s == 'REPLACE'){
    	// 	$items[$rowno]->status = '<span style="color:red;font-size:11px;"><b>'.$s.'</b></span>';
    	// }elseif($s == 'OK'){
    	// 	$items[$rowno]->status = '<span style="color:green;font-size:11px;"><b>'.$s.'</b></span>';
    	// }

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>