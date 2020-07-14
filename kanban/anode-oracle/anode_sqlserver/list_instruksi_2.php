<?php
include("../../connect/conn_kanbansys.php");
$table_jum = "select count(*) as j from ztb_assy_anode_gel where upto_date_drymix is null AND upto_date_hasil_anode is null";
$data_jum = odbc_exec($connect, $table_jum);
$row_jum = odbc_fetch_object($data_jum);

$jum_adukan = "select count(*) jumlah_adukan from ztb_assy_anode_gel 
			where date_prod between 
			(SELECT CONVERT(VARCHAR(10), SYSDATETIME(),121) + ' 07:00:00')
			AND
			(SELECT CONVERT(VARCHAR(10),CAST(DATEADD(d,+1,GETDATE()) AS DATE)) + ' 07:00:00')";
$data_jum_aduk = odbc_exec($connect, $jum_adukan);
$row_jum_aduk=odbc_fetch_object($data_jum_aduk);
$jumlah_aduk = $row_jum_aduk->jumlah_adukan;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="w3.css" />
</head>
<body>
	<span style="font-size: 10px;" align="left" >LIST INSTRUKSI DRY MIX </span>
	<span class="w3-badge w3-tiny w3-red"><?php echo $row_jum->j; ?></span>
	<span style="width: 150px;"></span>
	<span style="font-size: 10px;" align="right">, JUMLAH ADUKAN</span>
	<span class="w3-badge w3-tiny w3-red"><?php echo $jumlah_aduk; ?></span>
<?php if ($row_jum->j > 0){ ?>
<table class="w3-striped" align="center" style="width:650px;margin-top: 10px;">
	<tr>
		<th>NO. ADUKAN</th>
		<th>TYPE GEL</th>
		<th>TYPE ZINC POWDER</th>
		<th>KANBAN NO.</th>
		<th>UPDATE</th>
	</tr>
	<?php
		$table_list = "select cast(kanban_no as int) kanban_no, type_gel, type_zn, 
			CONVERT(VARCHAR(19), upto_date_instruksi,121) as upto_date, id, cast(no_adukan as int) no_adukan
			from ztb_assy_anode_gel where upto_date_instruksi is not null and upto_date_drymix is null AND upto_date_hasil_anode is null
			order by upto_date_instruksi asc";
		$data_list = odbc_exec($connect, $table_list);
		$no=1;
		while ($row = odbc_fetch_object($data_list)) {
			echo "<tr>
					<td>".$row->no_adukan."</td>
					<td>".$row->type_gel."</td>
					<td>".$row->type_zn."</td>
					<td>".$row->kanban_no."</td>
					<td>".$row->upto_date."</td>
				  <tr>
			";
			$no++;
		}
	?>
</table>
<div class="fitem" style="margin: 10px;">
	<a class="link_A" href="javascript:void(0)" onclick="proses()" ><b>LAKUKAN DRY MIX</b></a>
</div>
<?php } ?>
</body>
</html>