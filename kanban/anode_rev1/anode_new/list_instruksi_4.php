
<?php
include("../../connect/conn.php");
$table_jum = "select count(*) as j from ztb_assy_anode_gel where density is not null and assy_line is null AND date_use is null";
$data_jum = oci_parse($connect, $table_jum);
oci_execute($data_jum);
$row_jum = oci_fetch_object($data_jum);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="w3.css" />
</head>
<body>
<p align="left" style="width:850px;margin-top: 10px;font-size: 10px;">LIST OUTPUT DRY MIX BELUM DIPAKAI <span class="w3-badge w3-tiny w3-red"><?php echo $row_jum->J; ?></span></p>
<?php if ($row_jum->J > 0){ ?>
<table class="w3-striped" align="center" style="width:950px;margin-top: 10px;">
	<tr>
		<th style="width: 40px;">NO</th>
		<th style="width: 100px;">TYPE GEL</th>
		<th style="width: 100px;">TYPE ZN</th>
		<th style="width: 80px;">KANBAN NO.</th>
		<th style="width: 80px;">NO. TAG</th>
		<th style="width: 200px;">INSTRUKSI</th>
		<th style="width: 200px;">DRY MIX</th>
		<th style="width: 200px;">HASIL ANODE</th>
	</tr>
	<?php
		$table_list = "select kanban_no, no_tag, type_gel, type_zn, to_char(upto_date_instruksi,'DD-MON-YYYY')||chr(13)||to_char(upto_date_instruksi,'HH24:MI:SS') as upto_date_ins,
			to_char(upto_date_drymix,'DD-MON-YYYY')||chr(13)||to_char(upto_date_drymix,'HH24:MI:SS') as upto_date_dry,
			to_char(upto_date_hasil_anode,'DD-MON-YYYY')||chr(13)||to_char(upto_date_hasil_anode,'HH24:MI:SS') as upto_date_anode
			from ztb_assy_anode_gel
			where density is not null and assy_line is null AND date_use is null
			order by upto_date_instruksi asc";
		$data_list = oci_parse($connect, $table_list);
		oci_execute($data_list);
		$no=1;
		while ($row = oci_fetch_object($data_list)) {
			echo "<tr>
					<td>".$no."</td>
					<td>".$row->TYPE_GEL."</td>
					<td align='center'>".$row->TYPE_ZN."</td>
					<td align='center'>".$row->KANBAN_NO."</td>
					<td align='center'>".$row->NO_TAG."</td>
					<td>".$row->UPTO_DATE_INS."</td>
					<td>".$row->UPTO_DATE_DRY."</td>
					<td>".$row->UPTO_DATE_ANODE."</td>
				  <tr>
			";
			$no++;
		}
	?>
</table>
<?php } ?>
</body>
</html>