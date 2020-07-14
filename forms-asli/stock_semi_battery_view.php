<?php
include("../connect/conn.php");
//session_start();
//require_once('___loginvalidation.php');
//$user_name = $_SESSION['id_wms'];
if ($varConn=='Y'){ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>STOCK SEMI BATTERY</title>
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
<style>
*{
font-size:12px;
}
body {
	font-family:verdana,helvetica,arial,sans-serif;
	padding:20px;
	font-size:12px;
	margin:0;
}
h2 {
	font-size:18px;
	font-weight:bold;
	margin:0;
	margin-bottom:15px;
}
.demo-info{
	padding:0 0 12px 0;
}
.demo-tip{
	display:none;
}
.fitem{
	padding: 3px 0px;
}
</style>
</head>
<body>
<h2 align='center'>STOCK SEMI BATTERY</h2>
<table id="dg" class="easyui-datagrid" style="width:100%;height:900px;" url= "stock_semi_battery_get.php" rownumbers="false"  fitColumns="true" 
	singleSelect="true" idField="TIPE">
	 	<thead>
			<tr>
				<th rowspan="2" width="100" halign="center" align="left" field="TIPE"><b>TYPE</b></th>
				<th rowspan="2" width="80" field="TIPE2" hidden="true"><b>TIPE</b></th>
				<th rowspan="2" width="70" halign="center" align="center" field="AGING"><b>AGING</b></th>
				<th rowspan="2" width="70" halign="center" align="center" field="SAFETY_DAY"><b>SAFETY<br/>DAY</b></th>
				<th rowspan="2" width="80" halign="center" align="center" field="GRADE"><b>GRADE</b></th>
				<th rowspan="2" width="100" halign="center" align="center" field="WORKING_DAY"><b>WORKING<br/>DAY</b></th>
				<th rowspan="2" width="120" halign="center" align="right" field="TOTAL_ORDER"><b>TOTAL<br/>ORDER</b></th>
				<th rowspan="2" width="100" halign="center" align="right" field="ORDER_PER_DAY"><b>ORDER<br/>PER DAY</b></th>
				<!-- <th rowspan="2" width="100" halign="center" align="right" field="AVERAGE"><b>AVERAGE</b></th> -->
				<th rowspan="2" width="100" halign="center" align="right" field="HEATING_ROOM" data-options="styler:cellStyler2"><b>HEATING<br/>ROOM</b></th>
				<th colspan="7" width="475" halign="center" align="right" ><b>AGING SEMI BATTERY</b></th>
				<th rowspan="2" width="120" halign="center" align="right" field="BEFORE_LABEL" data-options="styler:cellStyler2"><b>BEFORE<br/>LABEL</b></th>
				<th rowspan="2" width="120" halign="center" align="right" field="AFTER_LABEL"><b>AFTER<br/>LABEL</b></th>
				<th rowspan="2" width="120" halign="center" align="right" field="SUSPENDED" data-options="styler:cellStyler2"><b>SUSPENDED</b></th>
			</tr>
		</thead>
		<thead>
			<tr>
				<th field="MINIMUM_STOCK" width="110" halign="center" align="right"><b>MIN</b></th>
				<th field="STD_MINIMUM" width="110" halign="center" align="right"><b>STD</b></th>
				<th field="MAXIMUM_STOCK" width="110" halign="center" align="right"><b>MAX</b></th>
				<th field="QTY_AGING" width="100" halign="center" align="right" data-options="styler:cellStyler2"><b>ACTUAL</b></th>
				<th field="DAY" width="50" halign="center" align="right" data-options="styler:cellStyler"><b>DAY</b></th>
				<th field="GAP" width="120" halign="center" align="right" data-options="styler:cellStyler"><b>GAP</b></th>
				<th field="STS" width="100" halign="center" align="center" data-options="styler:cellStyler"><b>STATUS</b></th>
			</tr>
		</thead>
</table>

<!-- START VIEW INFO -->
<div id='dlg_info' class="easyui-dialog" style="width:800px;height:400px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_prf" data-options="modal:true">
	<table id="dg_info" class="easyui-datagrid" style="width:100%;height:auto;" toolbar="#toolbar_viewPRF"></table>
</div>
<!-- END VIEW INFO-->

<!-- <div style="margin-top: 5px; width: 100%;">
	<div style="float: left;width:100px;height: 17px;border-radius:4px; text-align: center;"><b>STATUS : </b></div>
	<div style="float: left;width:100px;height: 17px;border-radius:4px; background-color: red; color: white; text-align: center;"><b>SHORTAGE</b></div>
	<div style="position:absolute; margin-left: 210px; width:100px;height: 17px;border-radius:4px; background-color: green; color: white; text-align: center;"><b>ACHIEVED</b></div>
	<div style="margin-left: 320px;width:100px;height: 17px;border-radius:4px; background-color: blue; color: white; text-align: center;"><b>OVER</b></div>
</div> -->

<script type="text/javascript">
	function procedure_proses(){
		$.ajax({
			type: 'GET',
			url: 'stock_semi_battery_execute.php'
		});
	}

	function table_proses(){
		$('#dg').datagrid({
			url: 'stock_semi_battery_get.php'
		});
	}

	$(function(){
		var myData1 = setInterval(function(){procedure_proses()},10*60*1000);
		var myData2 = setInterval(function(){table_proses()},5*60*1000);
		
		$('#dg').datagrid({
			onLoadSuccess:function(){
				var total = $('#dg').datagrid('getRows').length;
				rspan=0;		var tipe2="";				
				var ids = [];	var idx='';
				for (i=0; i < total; i++) {
					if (i==0){
						idx = i;	rspan=1;
					}else{
						if($('#dg').datagrid('getData').rows[i].TIPE2 == tipe2){
							rspan+=1;
						}else{
							var x = idx+'-'+rspan;
							ids.push(x);
							idx = i;	rspan=1;
						}
					}
					tipe2 = $('#dg').datagrid('getData').rows[i].TIPE2;
				}

				for(var ih=0; ih<ids.length; ih++){
					var splt = ids[ih].split("-");
					// $('#dg').datagrid('mergeCells',{
					// 	index:splt[0],
					// 	field:'SAFETY_DAY',
					// 	rowspan:splt[1]
					// });

					// $('#dg').datagrid('mergeCells',{
					// 	index:splt[0],
					// 	field:'GRADE',
					// 	rowspan:splt[1]
					// });

					// $('#dg').datagrid('mergeCells',{
					// 	index:splt[0],
					// 	field:'HEATING_ROOM',
					// 	rowspan:splt[1]
					// });

					// $('#dg').datagrid('mergeCells',{
					// 	index:splt[0],
					// 	field:'QTY_AGING',
					// 	rowspan:splt[1]
					// });

					// $('#dg').datagrid('mergeCells',{
					// 	index:splt[0],
					// 	field:'BALANCE',
					// 	rowspan:splt[1]
					// });

					// $('#dg').datagrid('mergeCells',{
					// 	index:splt[0],
					// 	field:'BEFORE_LABEL',
					// 	rowspan:splt[1]
					// });

					// $('#dg').datagrid('mergeCells',{
					// 	index:splt[0],
					// 	field:'AFTER_LABEL',
					// 	rowspan:splt[1]
					// });

					// $('#dg').datagrid('mergeCells',{
					// 	index:splt[0],
					// 	field:'SUSPENDED',
					// 	rowspan:splt[1]
					// });
				}
			}
		});
		$('#dg').datagrid('enableFilter');
	});

    function cellStyler(value,row,index){
	    if (row.STS == 'SHORTAGE') {
	        return 'background-color:red;color:white;';
	    }else if (row.STS == 'OVER'){
	    	return 'background-color:yellow;color:black;';
	    }else if (row.STS == 'ACHIEVED'){
	    	return 'background-color:green;color:white;';
	    }
	}

	function cellStyler2(value,row,index){
		if (row.TIPE.substring(0,5) != '<span'){
		    if (parseFloat(value) <= 0){
		    	return 'background-color:#FFD400;color:red;';
		    }else{
		    	return 'background-color:#009999;color:white;';
		    }
		}
	}

    $(function(){
		$('#dg').datagrid({
			rowStyler:function(index,row){
				if (row.TIPE == '<span style="font-size:11px;"><b>TOTAL</b></span>'){
					return 'background-color:#D2D2D2;font-weight:bold;';
				}else if (row.TIPE == '<span style="font-size:11px;"><b>GRAND<br/>TOTAL<br/>LR-1</b></span>' || row.TIPE == '<span style="font-size:11px;"><b>GRAND<br/>TOTAL<br/>LR-3</b></span>' || row.TIPE == '<span style="font-size:11px;"><b>GRAND<br/>TOTAL<br/>LR-6</b></span>'){
					return 'background-color:#505050;font-weight:bold;color:white';
				}else if (row.TIPE == '<span style="font-size:11px;"><b>GRAND<br/>TOTAL<br/>ALL</b></span>'){
					return 'background-color:#0000FF;font-weight:bold;color:white';
				}
			}
		});
	});

	function info_heat(a,b){
		$('#dlg_info').dialog('open').dialog('setTitle','VIEW INFO HEATING (TIPE: '+a+')');
		$('#dg_info').datagrid({
			url: 'stock_semi_battery_get_info.php?tipe='+a+'&jns='+b,
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			showFooter: true,
		    columns:[[
			    {field:'ID_PRINT', title:'PLAN NO.', width: 70, halign: 'center'},
			    {field:'ID_PLAN', title:'PLAN ASSY', width: 100, halign: 'center'},
			    {field:'PALLET', title:'PALLET<br>NO.', width: 50, halign: 'center', align: 'center'},
			    {field:'ASSY_LINE', title:'ASSY LINE', width: 90, halign: 'center'},
			    {field:'CELL_TYPE', title:'CELL TYPE', width: 90, halign: 'center'},
			    {field:'TANGGAL_PRODUKSI', title:'TGL PROD', width: 90, halign: 'center'},
			    {field:'QTY', title:'QTY', width: 100, halign: 'center', align: 'right'}
			]]
		});
	}

	function info_aging(a,b){
		$('#dlg_info').dialog('open').dialog('setTitle','VIEW INFO ACTUAL (TIPE: '+a+')');
		$('#dg_info').datagrid({
			url: 'stock_semi_battery_get_info.php?tipe='+a+'&jns='+b,
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			showFooter: true,
		    columns:[[
			    {field:'ID_PRINT', title:'PLAN NO.', width: 70, halign: 'center'},
			    {field:'ID_PLAN', title:'PLAN ASSY', width: 100, halign: 'center'},
			    {field:'PALLET', title:'PALLET<br>NO.', width: 50, halign: 'center', align: 'center'},
			    {field:'ASSY_LINE', title:'ASSY LINE', width: 90, halign: 'center'},
			    {field:'CELL_TYPE', title:'CELL TYPE', width: 90, halign: 'center'},
			    {field:'TANGGAL_PRODUKSI', title:'TGL PROD', width: 90, halign: 'center'},
			    {field:'QTY', title:'QTY', width: 100, halign: 'center', align: 'right'}
			]]
		});
	}
	function info_b_label(a,b){
		$('#dlg_info').dialog('open').dialog('setTitle','VIEW INFO BEFORE LABEL (TIPE: '+a+')');
		$('#dg_info').datagrid({
			url: 'stock_semi_battery_get_info.php?tipe='+a+'&jns='+b,
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			showFooter: true,
		    columns:[[
			    {field:'ID_PRINT', title:'PLAN NO.', width: 70, halign: 'center'},
			    {field:'ID_PLAN', title:'PLAN ASSY', width: 100, halign: 'center'},
			    {field:'PALLET', title:'PALLET<br>NO.', width: 50, halign: 'center', align: 'center'},
			    {field:'ASSY_LINE', title:'ASSY LINE', width: 90, halign: 'center'},
			    {field:'CELL_TYPE', title:'CELL TYPE', width: 90, halign: 'center'},
			    {field:'TANGGAL_PRODUKSI', title:'TGL PROD', width: 90, halign: 'center'},
			    {field:'QTY', title:'QTY', width: 100, halign: 'center', align: 'right'}
			]]
		});
	}
	
	function info_suspended(a,b){
		$('#dlg_info').dialog('open').dialog('setTitle','VIEW INFO BEFORE LABEL (TIPE: '+a+')');
		$('#dg_info').datagrid({
			url: 'stock_semi_battery_get_info.php?tipe='+a+'&jns='+b,
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			showFooter: true,
		    columns:[[
			    {field:'ID_PRINT', title:'PLAN NO.', width: 70, halign: 'center'},
			    {field:'ID_PLAN', title:'PLAN ASSY', width: 100, halign: 'center'},
			    {field:'PALLET', title:'PALLET<br>NO.', width: 50, halign: 'center', align: 'center'},
			    {field:'ASSY_LINE', title:'ASSY LINE', width: 90, halign: 'center'},
			    {field:'CELL_TYPE', title:'CELL TYPE', width: 90, halign: 'center'},
			    {field:'TANGGAL_PRODUKSI', title:'TGL PROD', width: 90, halign: 'center'},
			    {field:'QTY', title:'QTY', width: 100, halign: 'center', align: 'right'}
			]]
		});
	}
</script>

</body>
</html>
<?php }else{ echo "<script type='text/javascript'>location.href='../404.html';</script>";}