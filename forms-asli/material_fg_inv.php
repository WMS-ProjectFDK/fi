<?php
include("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>FINISH GOODS INVENTORY</title>
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

<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:500px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong> PERIOD & ITEM FILTER </strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Search Item</span>
				<input style="width:320px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" autofocus="" disabled="true" />
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Period</span>
				<input style="width:120px;" name="cmb_bln" id="cmb_bln" class="easyui-combobox" data-options="url:'json/json_period.php', method:'get', valueField:'bln', textField:'blnA', panelHeight:'50px'" required="" disabled="true" />
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;"></span>
				<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
			</div>
		</div>
	</fieldset>
	<fieldset style="margin-left: 525px;border-radius:4px;height: 100px;width:400px;"><legend><span class="style3"><strong>PRINT DATA</strong></span></legend>
		<div class="fitem" align="center" style="margin-top: 13px;">
			<a href="javascript:void(0)" id="printpdf" style="width: 250px;" class="easyui-linkbutton c2" disabled="true" onclick="print_pdf()"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download To PDF</a>
		</div>
		<div class="fitem" align="center" style="margin-top: -3px;">
			<a href="javascript:void(0)" id="printxls" style="width: 250px;" class="easyui-linkbutton c2" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download To Excel</a>
		</div>
		</div>
	</fieldset>
</div>
<table id="dg" title="FINISH GOODS INVENTORY REPORT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" singleSelect="true"></table>

<script type="text/javascript">
	function myformatter(date){
		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
	}
	function myparser(s){
		if (!s) return new Date();
			var ss = (s.split('-'));
			var y = parseInt(ss[0],10);
			var m = parseInt(ss[1],10);
			var d = parseInt(ss[2],10);
			if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
			return new Date(y,m-1,d);
		} else {
			return new Date();
		}
	}
	
	var pdf_url='';

	function filterData(){
		var bln = ["", 
				   "JAN", 
				   "FEB",
				   "MAR",
				   "APR",
				   "MAY",
				   "JUN",
				   "JUL",
				   "AUG",
				   "SEP",
				   "OKT",
				   "NOV",
				   "DES"];

		var tmonth = ($('#cmb_bln').combobox('getValue'));
		var tahun = tmonth.substr(0,4);
		var month = tmonth.substr(4,2);

		var bln_lalu = bln[parseInt(month)-1]+' '+tahun;
		var bln_ini = bln[parseInt(month)]+' '+tahun;

		pdf_url = "?cmbBln="+$('#cmb_bln').combobox('getValue')
				 +"&cmbBln_txt="+$('#cmb_bln').combobox('getText')
				 +"&src="+document.getElementById('src').value

		$('#dg').datagrid({
	    	url:'material_fg_inv_get.php',
	    	fitColumns: true,
	    	singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'CUSTOMER',title:'CUSTOMER',width:150, halign: 'center'},
				{field:'INVENTORYBULANLALU', title:'INVENTORY<br/>'+bln_lalu, width:100, halign: 'center', align: 'right'},
				{field:'AMOUNTINVENTORYBULANLALU', title:'AMOUNT<br/>'+bln_lalu, width:100, halign: 'center', align: 'right'},
				{field:'INVENTORYBULANINI', title:'INVENTORY<br/>'+bln_ini, width:100, halign: 'center', align: 'right'},
				{field:'AMOUNTINVENTORYBULANINI', title:'AMOUNT<br/>'+bln_ini, width:100, halign: 'center', align: 'right'}
		    ]]
		});

		var dg = $('#dg').datagrid();
		dg.datagrid('enableFilter');
	}

	function print_xls(){
		if(pdf_url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('material_fg_inv_excel.php'+pdf_url, '_blank');
		}
	}

	function print_pdf(){
		if(pdf_url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('material_fg_pdf.php'+pdf_url, '_blank');
		}	
	}
</script>
</body>
</html>