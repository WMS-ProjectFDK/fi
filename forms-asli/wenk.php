<?php 
include("../connect/conn2.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
/* BUDGET */
$sql = "select budget from ztb_prf_parameter where doc_no=to_char(SYSDATE,'YYYYMM') and departement='FINISHING'";
$data = oci_parse($connect, $sql);
oci_execute($data);
$dt = oci_fetch_array($data);

/* REQUITISION */

/* ACTUAL */
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Requitision Transaction</title>
    <link rel="icon" type="image/png" href="../favicon.png">
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script>
  	<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../themes/icon.css" />
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
	.board_2 {
		position: absolute;
		margin-left:725px;	
		top: 0px;
		border-style: solid;
		border-width: 0px;
	}
	</style>
    </head>
    <body>
	<?php include ('../ico_logout.php'); ?>
		<table title="COBA" id="dg" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:100%;"></table>
		<!-- <table id="dg" title="Parameter Budget" class="easyui-datagrid" toolbar="#toolbar" style="width:50%;height:400px;"></table> -->
		<script type="text/javascript">
			$('#dg').datagrid({
				url: 'item_select_get.php',
				fitColumns: 'true',
				rownumbers: 'true',
				singleSelect: 'true',
				columns:[[
					/*{field:'PO_DATE',width:80,align:'center',halign:'center', title:'PO DATE',hidden:true},
					{field:'ITEM_NO',width:120,align:'center',halign:'center',title:'ITEM'},
					{field:'DESCRIPTION',width:200,halign:'center',title:'ITEM NAME'},
					{field:'UOM_Q',width:50,halign:'center',title:'UNITCODe',hidden:true},
					{field:'UNIT',width:50,halign:'center',title:'UOM',hidden:true},
					{field:'U_PRICE',width:50,halign:'center',title:'PRICE',hidden:true},
					{field:'QTY',width:50,halign:'center',title:'QTY',hidden:true}*/
					{field:'PO_DATE',title:'DOCUMNET NO.', halign:'center', width:50, hidden:true},
					{field:'ITEM_NO', title:'PERIOD', halign:'center', width:70},
					{field:'SUPPLIER_CODE',title:'IDR', halign:'center', align:'right', width:70},
					{field:'CURR_CODE', title:'JPY', halign:'center', align:'right', width:70},
					{field:'SECTION_CODE', title:'SGD', halign:'center', align:'right', width:70}
				]]
				/* {'PO_DATE":"04-NOV-16","ITEM":"L-DA-00113-00","DESCRIP":"ALMA 690 INDUSTRIAL GEAR OIL V","UOM_Q":"240","UNIT":"PAIL","U_PRICE":"2176000","QTY":"0"} */
			});
			/*$('#dg').datagrid({
				url: 'prf_budget_get.php',
				rownumbers:'true', 
				fitColumns:'true',
				singleSelect:'true',
				columns:[[
					{field:'DOC_NO',title:'DOCUMNET NO.', halign:'center', width:50, hidden:true},
	                {field:'DOC', title:'PERIOD', halign:'center', width:70},
	                {field:'IDR_RATE',title:'IDR', halign:'center', align:'right', width:70},
	                {field:'JPY_RATE', title:'JPY', halign:'center', align:'right', width:70},
	                {field:'SGD_RATE', title:'SGD', halign:'center', align:'right', width:70}
				]]
			});*/
		</script>
	</body>
	</html>