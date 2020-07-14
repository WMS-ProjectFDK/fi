
<?php
include("../../connect/conn.php");
$table_jum = "select count(*) as j from (select a.kanban_no, a.type_gel, a.type_zn, to_char(b.upto_date_instruksi,'DD-MON-YYYY')||chr(13)||to_char(b.upto_date_instruksi,'HH24:MI:SS') as upto_date_ins,
			to_char(b.upto_date_drymix,'DD-MON-YYYY')||chr(13)||to_char(b.upto_date_drymix,'HH24:MI:SS') as upto_date_dry
			from ztb_assy_anode_gel_sts a 
			inner join ztb_assy_anode_gel b on a.kanban_no= b.kanban_no AND a.type_gel= b.type_gel
			where a.flag=1 AND a.pemakaian_assy=0 AND b.worker_id_drymix is not null AND density is null
			order by b.upto_date_instruksi asc)";
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
<p align="left" style="width:850px;margin-top: 10px;font-size: 10px;">LIST OUTPUT DRY MIX <span class="w3-badge w3-tiny w3-red"><?php echo $row_jum->J; ?></span></p>
<?php if ($row_jum->J > 0){ ?>
<table class="w3-striped" align="center" style="width:850px;margin-top: 10px;">
	<tr>
		<th>NO</th>
		<th>TYPE GEL</th>
		<th>TYPE ZINC POWDER</th>
		<th>KANBAN NO.</th>
		<th>UPDATE INSTRUKSI</th>
		<th>UPDATE DRY MIX</th>
	</tr>
	<?php
		$table_list = "select a.kanban_no, a.type_gel, a.type_zn, to_char(b.upto_date_instruksi,'DD-MON-YYYY')||chr(13)||to_char(b.upto_date_instruksi,'HH24:MI:SS') as upto_date_ins,
			to_char(b.upto_date_drymix,'DD-MON-YYYY')||chr(13)||to_char(b.upto_date_drymix,'HH24:MI:SS') as upto_date_dry
			from ztb_assy_anode_gel_sts a 
			inner join ztb_assy_anode_gel b on a.kanban_no= b.kanban_no AND a.type_gel= b.type_gel
			where a.flag=1 AND a.pemakaian_assy=0 AND b.worker_id_drymix is not null AND density is null
			order by b.upto_date_instruksi asc";
		$data_list = oci_parse($connect, $table_list);
		oci_execute($data_list);
		$no=1;
		while ($row = oci_fetch_object($data_list)) {
			echo "<tr>
					<td>".$no."</td>
					<td>".$row->TYPE_GEL."</td>
					<td>".$row->TYPE_ZN."</td>
					<td>".$row->KANBAN_NO."</td>
					<td>".$row->UPTO_DATE_INS."</td>
					<td>".$row->UPTO_DATE_DRY."</td>
				  <tr>
			";
			$no++;
		}
	?>
</table>
<?php } ?>
</body>
</html>