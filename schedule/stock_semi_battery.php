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
<link rel="stylesheet" type="text/css" href="../themes/color.css" />
<script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.patch.js"></script>
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
<table id="dg" class="easyui-datagrid" style="width:100%;height:600px;" url= "stock_semi_battery_get.php" rownumbers="true"  fitColumns="true" 
	singleSelect="true" idField="TIPE">
	 	<thead>
			<tr>
				<th rowspan="2" width="120" halign="center" align="left" field="TIPE"><b>TYPE</b></th>
				<th rowspan="2" field="TIPE2" hidden="true"><b>TIPE</b></th>
				<th rowspan="2" width="80" halign="center" align="center" field="AGING"><b>AGING</b></th>
				<th rowspan="2" width="80" halign="center" align="center" field="SAFETY_DAY"><b>SAFETY<br/>DAY</b></th>
				<th rowspan="2" width="80" halign="center" align="center" field="GRADE"><b>GRADE</b></th>
				<th rowspan="2" width="100" halign="center" align="center" field="WORKING_DAY"><b>WORKING<br/>DAY</b></th>
				<th rowspan="2" width="120" halign="center" align="right" field="TOTAL_ORDER"><b>TOTAL<br/>ORDER</b></th>
				<th rowspan="2" width="120" halign="center" align="right" field="ORDER_PER_DAY"><b>ORDER<br/>PER DAY</b></th>
				<th rowspan="2" width="120" halign="center" align="right" field="AVERAGE"><b>AVERAGE</b></th>
				<th rowspan="2" width="120" halign="center" align="right" field="HEATING_ROOM" data-options="styler:cellStyler2"><b>HEATING<br/>ROOM</b></th>
				<th colspan="3" width="375" halign="center" align="right" ><b>AFTER HEATING ROOM</b></th>
				<th rowspan="2" width="125" halign="center" align="right" field="BEFORE_LABEL"><b>BEFORE<br/>LABEL</b></th>
				<th rowspan="2" width="125" halign="center" align="right" field="AFTER_LABEL"><b>AFTER<br/>LABEL</b></th>
				<th rowspan="2" width="125" halign="center" align="right" field="SUSPENDED"><b>SUSPENDED</b></th>
			</tr>
		</thead>
		<thead>
			<tr>
				<th field="STD_MINIMUM" width="120" halign="center" align="right"><b>STANDARD<br/>MINIMUM</b></th>
				<th field="QTY_AGING" width="120" halign="center" align="right"><b>ACTUAL</b></th>
				<th field="BALANCE" width="120" halign="center" align="right" data-options="styler:cellStyler"><b>BALANCE</b></th>
			</tr>
		</thead>
</table>

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
					$('#dg').datagrid('mergeCells',{
						index:splt[0],
						field:'SAFETY_DAY',
						rowspan:splt[1]
					});

					$('#dg').datagrid('mergeCells',{
						index:splt[0],
						field:'GRADE',
						rowspan:splt[1]
					});

					$('#dg').datagrid('mergeCells',{
						index:splt[0],
						field:'HEATING_ROOM',
						rowspan:splt[1]
					});

					$('#dg').datagrid('mergeCells',{
						index:splt[0],
						field:'QTY_AGING',
						rowspan:splt[1]
					});

					$('#dg').datagrid('mergeCells',{
						index:splt[0],
						field:'BALANCE',
						rowspan:splt[1]
					});

					$('#dg').datagrid('mergeCells',{
						index:splt[0],
						field:'BEFORE_LABEL',
						rowspan:splt[1]
					});

					$('#dg').datagrid('mergeCells',{
						index:splt[0],
						field:'AFTER_LABEL',
						rowspan:splt[1]
					});

					$('#dg').datagrid('mergeCells',{
						index:splt[0],
						field:'SUSPENDED',
						rowspan:splt[1]
					});
				}
			}
		});
	});

    function cellStyler(value,row,index){
	    if (parseFloat(value) < 0){
	        return 'background-color:#FFEE00;color:red;';
	    }
	}

	function cellStyler2(value,row,index){
	    if (parseFloat(value) <= 0){
	        return 'background-color:#FFEE00;color:red;';
	    }
	}
</script>

</body>
</html>
<?php }else{ echo "<script type='text/javascript'>location.href='../404.html';</script>";}