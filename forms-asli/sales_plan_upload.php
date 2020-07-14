<?php
include("../connect/conn.php");
$user_name = htmlspecialchars($_REQUEST['user_name']);

$qry = "select max(operation_date) as last_upload from sales_plan";
$data = oci_parse($connect, $qry);
oci_execute($data);
$row = oci_fetch_object($data)
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SALES PLAN UPLOAD</title>
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
<script type="text/javascript" src="../js/jquery.easyui.patch.js"></script>
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
	<table id="dg" title="SALES PLAN UPLOAD" class="easyui-datagrid" style="width:100%;height:auto;"   url="sales_plan_list.php" toolbar="#toolbar" fitcolumns="true" rownumbers="true"></table>

	<div id="toolbar">
		<div class="fitem">
			<a href="sales_plan_excel.php" style="width:180px;display:inline-block;float:left;margin-right: 5px;" class="easyui-linkbutton c2"><i class="fa fa-table" aria-hidden="true"></i> DOWNLOAD TEMPLATE</a>
			<form id="upd" method="post" enctype="multipart/form-data">
				<input class="easyui-filebox" name="fileexcel" id="fileexcel" style="width:500px">
				<a href="javascript:void(0)" style="width:150px;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit"  id ="upload" onclick="uploaddata()">&nbsp;
					<i class="fa fa-upload" aria-hidden="true">UPLOAD</i> 
				</a>
				<!-- http://sysfi01.indonesia.fdk.co.jp/pglosas/entry/plans/sp_entry1.cgi?FI0122 -->
				<a href="http://sysfi01.indonesia.fdk.co.jp/pglosas/entry/plans/sp_entry1.cgi?<? echo $user_name ?>" style="width:150px;display:inline-block;margin-left;" class="easyui-linkbutton c2"><i class="fa fa-table" aria-hidden="true"></i> BACK TO SALES PLAN</a>

				<a href="javascript:void(0)" id="printxls" style="width: 100px;" class="easyui-linkbutton c2" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> EXCEL</a>
				<span style="color: blue;">LAST UPLOAD : <?php echo $row->LAST_UPLOAD; ?></span>
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
				url: 'sales_plan_upload_process.php?user_name=<? echo $user_name; ?>',
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					$.messager.alert('SAFETY STOCK',result,'info');
					//alert(result);
			 		$('#fileexcel').filebox('clear');
					document.getElementById('upload').disabled = false;
					x=0;
				}
			});
		};

		$(function(){
			var d = new Date();
			var n = d.getMonth();
			var now = new Date();
			current = new Date(now.getFullYear(), now.getMonth()+1, 1);
			current1 = new Date(now.getFullYear(), now.getMonth()+2, 1);
			current2 = new Date(now.getFullYear(), now.getMonth()+3, 1);
			var sp0 = 'SP ' + formatDate(d);
			var sp1 = 'SP ' + formatDate(current);
			var sp2 = 'SP ' + formatDate(current1);
			var sp3 = 'SP ' + formatDate(current2);
			$('#dg').datagrid({
			    columns:[[
				    {field:'COMPANY', halign:'center', align:'center', title:'COMPANY',width:150},
				    {field:'ITEM_NO', halign:'center', title:'ITEM NO',width:100},
				    {field:'DESCRIPTION', halign:'center', align:'left', title:'DESCRIPTION',width:200},
				    {field:'SPMONTH0', halign:'center', align:'right', title:sp0 ,width:100},
				    {field:'MONTH0', halign:'center', align:'right', title: formatDate(d) ,width:100},
				    {field:'SPMONTH1', halign:'center', align:'right', title: sp1 ,width:100},
				    {field:'MONTH1', halign:'center', align:'right', title: formatDate(current) ,width:100},
				    {field:'SPMONTH2', halign:'center', align:'right', title: sp2 ,width:100},
				    {field:'MONTH2', halign:'center', align:'right', title: formatDate(current1) ,width:100},
				    {field:'SPMONTH3', halign:'center', align:'right', title: sp3 ,width:100},
				    {field:'MONTH3', halign:'center', align:'right', title: formatDate(current1) ,width:100}
			    ]]
		    });
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		});

		function formatDate(date) {
		  var monthNames = [
		    "January", "February", "March",
		    "April", "May", "June", "July",
		    "August", "September", "October",
		    "November", "December"
		  ];

		  var day = date.getDate();
		  var monthIndex = date.getMonth();
		  var year = date.getFullYear();
		  return monthNames[monthIndex] + ' ' + year;
		}

		function filter(){
			$('#dg').datagrid('reload');
		};

		function print_xls(){
			$('#dg').datagrid('toExcel','sales_plan_report.xls')
		}

		window.onbeforeunload = function(){
			if(x==1){
				return 'Please do not leave this page, because upload still in progress !';
			}
		};
	</script>
</body>
</html>