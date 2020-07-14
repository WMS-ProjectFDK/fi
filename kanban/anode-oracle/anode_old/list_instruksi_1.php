<?php
include("../../connect/conn.php");
$table_jum = "select count(*) as j from (select kanban_no, type_gel, type_zn, to_char(UPTO_DATE_INSTRUKSI,'DD-MON-YYYY')||chr(13)||to_char(UPTO_DATE_INSTRUKSI,'HH24:MI:SS') as upto_date 
			from ztb_assy_anode_gel_sts where flag=0 order by upto_date asc)";
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
<p align="left" style="width:650px;margin-top: 10px;font-size: 10px;">LIST INSTRUKSI DRY MIX <span class="w3-badge w3-tiny w3-red"><?php echo $row_jum->J; ?></span></p>
<?php if ($row_jum->J > 0){ ?>
<table class="w3-striped" align="center" style="width:650px;margin-top: 10px;">
	<tr>
		<th>NO</th>
		<th>TYPE GEL</th>
		<th>TYPE ZINC POWDER</th>
		<th>KANBAN NO.</th>
		<th>UPDATE</th>
	</tr>
	<?php
		
		$table_list = "select kanban_no, type_gel, type_zn, to_char(UPTO_DATE_INSTRUKSI,'DD-MON-YYYY')||chr(13)||to_char(UPTO_DATE_INSTRUKSI,'HH24:MI:SS') as upto_date 
			from ztb_assy_anode_gel_sts where flag=0 order by upto_date asc";
		$data_list = oci_parse($connect, $table_list);
		oci_execute($data_list);
		$no=1;
		while ($row = oci_fetch_object($data_list)) {
			echo "<tr>
					<td>".$no."</td>
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