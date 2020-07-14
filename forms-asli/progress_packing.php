<?php
include("../connect/conn.php");
if ($varConn=='Y'){ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PROGRESS PACKAGING</title>
<link rel="icon" type="image/png" href="../favicon.png">
<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" type="text/css" href="../themes/color.css" />
<script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../js/jquery.edatagrid.js"></script>
<script type="text/javascript" src="../js/datagrid-export.js"></script>

<style>
*{
font-size:14px;
}
body {
	font-family:verdana,helvetica,arial,sans-serif;
	padding:20px;
	font-size:18px;
	margin:0;
}
h2 {
	font-size:28px;
	font-weight:bold;
	margin:0;
	margin-bottom:15px;
}
.datagrid-cell{
    font-size:14px;
}
</style>
</head>
<body>
	<h2 align='center'>VISUALISASI PROGRESS PACKAGING</h2>

	<table id="dg" class="easyui-datagrid" style="width:100%;height:auto;"  url="progress_packing_result.json" rownumbers="false"  fitColumns="true" singleSelect="true">
	 	<thead>
			<tr>
				<th width="250" halign="center" align="left" field="WORK_ORDER"><b>WO NO.</b></th>
				<th width="250" halign="center" align="left" field="ITEM_NAME" data-options="styler:cellStyler3"><b>BRAND</b></th>
				<th width="95" halign="center" align="center" field="CR_DATE"><b>CR DATE</b></th>
				<th width="120" halign="center" align="right" field="QTY"><b>QTY ORDER<br/>(pcs)</b></th>
				<th width="135" halign="center" align="right" field="LABEL_QTY" data-options="styler:cellStyler1"><b>PROGRESS<br/>LABEL (pcs)</b></th>
				<th width="150" halign="center" align="right" field="LABEL_GAP" data-options="styler:cellStyler1"><b>GAP LABEL<br/>(pcs)</b></th>
				<th width="150" halign="center" align="left" field="PACKAGING_TYPE"><b>PACKAGING<br/>GROUP</b></th>
				<th width="135" halign="center" align="right" field="PACK_QTY" data-options="styler:cellStyler2"><b>PROGRESS<br/>PACKING (pcs)</b></th>
				<th width="150" halign="center" align="right" field="PACK_GAP" data-options="styler:cellStyler2"><b>GAP PACK<br/>(pcs)</b></th>
				<th field="LABEL_GAP_PERSEN_O" hidden="true"></th>
				<th field="PACK_GAP_PERSEN_O" hidden="true"></th>
			</tr>
		</thead>
	</table>
	<script type="text/javascript">
		function procedure_proses(){
			$.get("progress_packing_get.php", function(data){
		      	if(data[0].kode == "SUCCESS"){
					dg_reload();
				}
		    });
		}

		function dg_reload(){
			$('#dg').datagrid('reload');
		}

		$(function(){
			var myData1 = setInterval(function(){procedure_proses()},60*1000);
			var myData2 = setInterval(function(){dg_reload()},5*1000);

			$('#dg').datagrid({
				pageSize: 10
			});

			var dg = $('#dg');
			var tr = dg.datagrid('options').finder.getTr(dg[0],1);
			tr.css('height','40px');
		});

		function cellStyler1(value,row,index){
		    if (row.LABEL_GAP_PERSEN_O >= 80) {
		        return 'background-color:green;color:white;';
		    }else if (row.LABEL_GAP_PERSEN_O <= 20){
		    	return 'background-color:red;color:white;';
		    }else{
		    	return 'background-color:yellow;color:black;';
		    }
		}

		function cellStyler2(value,row,index){
		    if (row.PACK_GAP_PERSEN_O >= 80) {
		        return 'background-color:green;color:white;';
		    }else if (row.PACK_GAP_PERSEN_O <= 20){
		    	return 'background-color:red;color:white;';
		    }else{
		    	return 'background-color:yellow;color:black;';
		    }
		}

		function cellStyler3(value,row,index){
	    	return 'font-weight:bold;';
		}
	</script>
</body>
</html>
<?php }else{ echo "<script type='text/javascript'>location.href='../404.html';</script>";}