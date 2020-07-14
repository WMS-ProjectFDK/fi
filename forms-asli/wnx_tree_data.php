<?php
include("../connect/koneksi.php");
$sql_menu = pg_query("
	select b.nik, a.kode_menu, a.parent, substring(trim(a.kode_menu),1,1) as kode_parent, 
	a.nama_menu, a.link
	from menu a left join user_access b
	on a.kode_menu = b.kode_menu
	where a.kode_menu !='0' and b.nik = 'NIK8' and b.access_view=true 
	order by a.kode_menu
");

$response = array();

while ($data_menu = pg_fetch_object($sql_menu)) {
	if($data_menu->parent == 0){
		$n = $data_menu->nama_menu;
	}

	$nama_menu = $n;

	$response[] = array('id' => trim($data_menu->kode_menu),
					    'name' => trim($data_menu->nama_menu),
					    'parentId' => trim($data_menu->parent),
					    'pnama' => strtolower($nama_menu), 
					    'link' => trim($data_menu->link)
					   );
}

echo json_encode($response);
?>