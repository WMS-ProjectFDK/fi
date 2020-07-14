<?php
include("../connect/conn.php");
//session_start();
//require_once('___loginvalidation.php');
//$user_name = $_SESSION['id_wms'];
$user_name = htmlspecialchars($_REQUEST['user_name']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>MATERIAL PACKING PLAN </title>
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

<script type="text/javascript" src="../js/datagrid-export.js"></script>
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
<?php //include ('../ico_logout.php'); 

	// $newformat = "201812";
	// $newformat .= "01";
	// echo date('Y-m-d', strtotime($newformat));
?>
	<table id="dg" title="MATERIAL PACKING PLAN" class="easyui-datagrid" style="width:100%;height:auto;"  url="item_pm_plan_list.php" toolbar="#toolbar" fitcolumns="true" rownumbers="true"></table>

	<div id="toolbar">
		<div class="fitem">
			
			<form id="upd" method="post" enctype="multipart/form-data">
				<a href="javascript:void(0)" style="width:200px;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit"  id ="upload" onclick="print()">
					<i class="fa fa-upload" aria-hidden="true"></i> Excel
				</a>
				
				
			</form>	

		</div>
	</div>

	<script type="text/javascript">
		var url;
		var x = 0;
		function uploaddata() {
		x=1;
		alert("Process around two minutes, please do not leave this page until the next message.");
		document.getElementById('upload').disabled = true;
		$('#upd').form('submit',{
			url: 'item_upload_process.php',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				$.messager.alert('ITEM NO',result,'info');
		 		$('#fileexcel').filebox('clear');
				document.getElementById('upload').disabled = false;
				x=0;
				}
			});
		};

		$(function(){
			$('#dg').datagrid({
			    columns:[[
				    {field:'ITEM1', halign:'center', align:'center', title:'ITEM NO',width:170},
				    {field:'DESCRIPTION', halign:'center', title:'DESCRIPTION',width:400},
				    {field:'TANGGAL1', halign:'center', title:'1',width:130},
				    {field:'TANGGAL2', halign:'center', align:'center', title:'2',width:130},
				    {field:'TANGGAL3', halign:'center', align:'left', title:'3',width:130},
				    {field:'TANGGAL4', halign:'center', align:'right', title:'4',width:130},
				    {field:'TANGGAL5', halign:'center', align:'right',title:'5',width:130},
				    {field:'TANGGAL6', halign:'center', align:'right',title:'6',width:130},
				    {field:'TANGGAL7', halign:'center', align:'right', title:'7',width:130},
				    {field:'TANGGAL8', halign:'center', align:'right', title:'8',width:130},
				    {field:'TANGGAL9', halign:'center', align:'right', title:'9',width:130},
				    {field:'TANGGALL0', halign:'center', title:'10',width:130},
				     {field:'TANGGALL1', halign:'center', title:'11',width:130},
				    {field:'TANGGALL2', halign:'center', align:'center', title:'12',width:130},
				    {field:'TANGGALL3', halign:'center', align:'left', title:'13',width:100},
				    {field:'TANGGALL4', halign:'center', align:'right', title:'14',width:100},
				    {field:'TANGGALL5', halign:'center', align:'right',title:'15',width:100},
				    {field:'TANGGALL6', halign:'center', align:'right',title:'16',width:100},
				    {field:'TANGGALL7', halign:'center', align:'right', title:'17',width:100},
				    {field:'TANGGALL8', halign:'center', align:'right', title:'18',width:100},
				    {field:'TANGGALL9', halign:'center', align:'right', title:'19',width:100},
				     {field:'TANGGAL20', halign:'center', title:'20',width:100},
				      {field:'TANGGAL21', halign:'center', title:'21',width:100},
				    {field:'TANGGAL22', halign:'center', align:'center', title:'22',width:100},
				    {field:'TANGGAL23', halign:'center', align:'left', title:'23',width:100},
				    {field:'TANGGAL24', halign:'center', align:'right', title:'24',width:100},
				    {field:'TANGGAL25', halign:'center', align:'right',title:'25',width:100},
				    {field:'TANGGAL26', halign:'center', align:'right',title:'26',width:100},
				    {field:'TANGGAL27', halign:'center', align:'right', title:'27',width:100},
				    {field:'TANGGAL28', halign:'center', align:'right', title:'28',width:100},
				    {field:'TANGGAL29', halign:'center', align:'right', title:'29',width:100},
				    {field:'TANGGAL30', halign:'center', align:'right', title:'30',width:100},
				    {field:'TANGGAL31', halign:'center', align:'right', title:'31',width:100},
			    ]]
		    });
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		});

		function filter(){
			$('#dg').datagrid('reload');
				
		};

	
		function print(){
		$('#dg').datagrid('toExcel','sales_report.xls')
		// if(url=='') {
		// 	$.messager.alert('Warning','Data not Found, please click filter data','warning');
		// }else{
		// 	window.open('sales_report_excel.php'+url, '_blank');
		// }
	}
	
	</script>
</body>
</html>