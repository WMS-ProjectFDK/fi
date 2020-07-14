<?php
include("../../connect/conn.php");
$table_jum = "select count(*) as j from ztb_assy_anode_gel where upto_date_drymix is null AND upto_date_hasil_anode is null";
$data_jum = oci_parse($connect, $table_jum);
oci_execute($data_jum);
$row_jum = oci_fetch_object($data_jum);

$jum_adukan = "select count(*) jumlah_adukan from ztb_assy_anode_gel 
	where to_date(to_char(date_prod, 'yyyy-mm-dd hh24:mi:ss'),'yyyy-mm-dd hh24:mi:ss') between 
	(select to_date(to_char(sysdate, 'yyyy-mm-dd')||' 07:00:00', 'yyyy-mm-dd hh24:mi:ss') from dual) AND
	(select to_date(to_char(sysdate+1, 'yyyy-mm-dd')||' 07:00:00', 'yyyy-mm-dd hh24:mi:ss') from dual)";
$data_jum_aduk = oci_parse($connect, $jum_adukan);
oci_execute($data_jum_aduk);
$row_jum_aduk=oci_fetch_object($data_jum_aduk);
$jumlah_aduk = $row_jum_aduk->JUMLAH_ADUKAN;

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="w3.css" />
</head>
<body>
	<span style="font-size: 10px;" align="left" >LIST INSTRUKSI DRY MIX </span>
	<span class="w3-badge w3-tiny w3-red"><?php echo $row_jum->J; ?></span>
	<span style="width: 150px;"></span>
	<span style="font-size: 10px;" align="right">, JUMLAH ADUKAN</span>
	<span class="w3-badge w3-tiny w3-red"><?php echo $jumlah_aduk; ?></span>

<?php if ($row_jum->J > 0){ ?>
<table class="w3-striped" align="center" style="width:650px;margin-top: 10px;">
	<tr>
		<th>NO. ADUKAN</th>
		<th>TYPE GEL</th>
		<th>TYPE ZINC POWDER</th>
		<th>KANBAN NO.</th>
		<th>UPDATE</th>
	</tr>
	<?php
		$table_list = "select kanban_no, type_gel, type_zn, to_char(upto_date_instruksi,'DD-MON-YYYY')||chr(13)||to_char(upto_date_instruksi,'HH24:MI:SS') as upto_date, no_adukan
			from ztb_assy_anode_gel where upto_date_instruksi is not null and upto_date_drymix is null AND upto_date_hasil_anode is null
			order by upto_date_instruksi asc";
		$data_list = oci_parse($connect, $table_list);
		oci_execute($data_list);
		$no=1;
		while ($row = oci_fetch_object($data_list)) {
			echo "<tr>
					<td>".$row->NO_ADUKAN."</td>
					<td>".$row->TYPE_GEL."</td>
					<td>".$row->TYPE_ZN."</td>
					<td>".$row->KANBAN_NO."</td>
					<td>".$row->UPTO_DATE."</td>
				  <tr>
			";
			$no++;
		}
	?>
</table>
<?php } ?>
</body>
</html>