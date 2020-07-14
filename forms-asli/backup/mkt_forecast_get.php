<?php
	session_start();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'fc_trans_code';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
	$offset = ($page-1)*$rows;
	$result = array();
	
	$s_periode_awal = isset($_POST['s_periode_awal']) ? strval($_POST['s_periode_awal']) : '';
	$s_periode_akhir = isset($_POST['s_periode_akhir']) ? strval($_POST['s_periode_akhir']) : '';
	
	$s_classabc = isset($_POST['s_classabc']) ? strval($_POST['s_classabc']) : '';
	$ck_classabc = isset($_POST['ck_classabc']) ? strval($_POST['ck_classabc']) : '';
	
	$s_kode_customer = isset($_POST['s_kode_customer']) ? strval($_POST['s_kode_customer']) : '';
	$ck_kode_customer = isset($_POST['ck_kode_customer']) ? strval($_POST['ck_kode_customer']) : '';
	
	$s_remark = isset($_POST['s_remark']) ? strval($_POST['s_remark']) : '';
	$ck_remark = isset($_POST['ck_remark']) ? strval($_POST['ck_remark']) : '';
	
	$s_fc_kategori = isset($_POST['s_fc_kategori']) ? strval($_POST['s_fc_kategori']) : '';
	$ck_category = isset($_POST['ck_category']) ? strval($_POST['ck_category']) : '';
	
	$s_fc_prodfam = isset($_POST['s_fc_prodfam']) ? strval($_POST['s_fc_prodfam']) : '';
	$ck_prodfam = isset($_POST['ck_prodfam']) ? strval($_POST['ck_prodfam']) : '';
	
	
	if ($ck_classabc != "true"){
		$classabc = "b.brg_class = '$s_classabc' and";
	}else{
		 $classabc = " ";
	}
	
	if ($ck_kode_customer != "true"){
		$kode_customer = "a.kode_customer = '$s_kode_customer' and";
	}else{
		 $kode_customer = " ";
	}
	
	if ($ck_remark != "true"){
		$remark = "a.fc_keterangan = '$s_remark' and";
	}else{
		 $remark = " ";
	}

	if ($ck_category != "true"){
		$category = "c.jenis_kategori = '$s_fc_kategori' and";
	}else{
		 $category = " ";
	}
	
	if ($ck_prodfam != "true"){
		$prodfam = "d.jenis_prodfam  = '$s_fc_prodfam' and";
	}else{
		 $prodfam = " ";
	}
	
	$where = "where $classabc $kode_customer $remark $category $prodfam a.fc_datecreate between '$s_periode_awal' and '$s_periode_akhir' ";
	
	include("../connect/koneksi.php");
	
	$rs = pg_query("select count(*) 
					from fc_customer a left join master_barang b 
					on a.brg_codebarang = b.brg_codebarang 
					left join master_kategori c 
					on b.jenis_kategori=c.jenis_kategori 
					left join product_family d 
					on b.jenis_prodfam = d.jenis_prodfam 
					left join master_satuan e 
					on b.nama_satuan = e.nama_satuan 
					left join master_customer f 
					on a.kode_customer = f.kode_customer $where");
	
	$row = pg_fetch_row($rs);
	$result["total"] = $row[0];

	$rs = pg_query("select a.*, b.nama_barang, c.ket_kategori, d.ket_master, e.ket_satuan, b.brg_class, f.nama_customer 
					from fc_customer a left join master_barang b 
					on a.brg_codebarang = b.brg_codebarang 
					left join master_kategori c 
					on b.jenis_kategori=c.jenis_kategori 
					left join product_family d 
					on b.jenis_prodfam = d.jenis_prodfam 
					left join master_satuan e 
					on b.nama_satuan = e.nama_satuan 
					left join master_customer f 
					on a.kode_customer = f.kode_customer $where 
					order by $sort $order limit $rows offset $offset");
	
	$items = array();
	while($row = pg_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>