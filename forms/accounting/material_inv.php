<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>MATERIAL INVENTORY</title>
<link rel="icon" type="image/png" href="../favicon.png">
<script language="javascript">
		function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
		}
</script> 
<link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../../themes/color.css" />
<script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../../js/jquery.easyui.patch.js"></script>
<script type="text/javascript" src="../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../../js/jquery.edatagrid.js"></script>
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
<?php include ('../../ico_logout.php'); ?>

<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:500px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong> PERIOD & ITEM FILTER </strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Search Item</span>
				<input style="width:320px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" autofocus="" />
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Period</span>
				<input style="width:120px;" name="cmb_bln" id="cmb_bln" class="easyui-combobox" data-options="url:'../json/json_period.php', method:'get', valueField:'bln', textField:'blnA', panelHeight:'50px'" required=""/>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;"></span>
				<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
			</div>
		</div>
	</fieldset>
	<fieldset style="margin-left: 525px;border-radius:4px;height: 100px;width:400px;"><legend><span class="style3"><strong>PRINT DATA</strong></span></legend>
		<div class="fitem" align="center" style="margin-top: 13px;">
			<a href="javascript:void(0)" id="printpdf" style="width: 250px;" class="easyui-linkbutton c2" onclick="print_pdf()"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download To PDF</a>
		</div>
		<div class="fitem" align="center" style="margin-top: -3px;">
			<a href="javascript:void(0)" id="printxls" style="width: 250px;" class="easyui-linkbutton c2" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download To Excel</a>
		</div>
		</div>
	</fieldset>
</div>
<table id="dg" title="MATERIAL INVENTORY REPORT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" singleSelect="true"></table>

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

	$(function(){
		$('#printpdf').linkbutton('disable');
		$('#dg').datagrid({
	    	singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'MONTH',title:'PERIOD',width:80, halign: 'center', align: 'center'},
			    {field:'ITEM1',title:'ITEM',width:200, halign: 'center'},
			    {field:'LASTMONTH',title:'LAST MONTH',width:100, halign: 'center', align: 'right'},
			    {field:'LASTMONTHAMOUNT',title:'LAST MONTH<br/>AMOUNT',width:100, halign: 'center', align: 'right'},
			    {field:'TANGGAL1', title:'1', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL2', title:'2', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL3', title:'3', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL4', title:'4', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL5', title:'5', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL6', title:'6', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL7', title:'7', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL8', title:'8', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL9', title:'9', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL0', title:'10', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL1', title:'11', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL2', title:'12', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL3', title:'13', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL4', title:'14', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL5', title:'15', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL6', title:'16', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL7', title:'17', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL8', title:'18', width:80, halign: 'center', align: 'right'},
				{field:'TANGGALL9', title:'19', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL20', title:'20', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL21', title:'21', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL22', title:'22', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL23', title:'23', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL24', title:'24', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL25', title:'25', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL26', title:'26', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL27', title:'27', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL28', title:'28', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL29', title:'29', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL30', title:'30', width:80, halign: 'center', align: 'right'},
				{field:'TANGGAL31', title:'31', width:80, halign: 'center', align: 'right'},
			    {field:'THISMONTH',title:'THIS MONTH',width:100, halign: 'center', align: 'right'},
			    {field:'THISMONTHAMOUNT',title:'THIS MONTH<br/>AMOUNT',width:100, halign: 'center', align: 'right'},
			    {field:'ACTUALAMOUNT',title:'ACTUAL<br/>AMOUNT',width:100, halign: 'center', align: 'right'},
			    {field:'TARGET_QTY',title:'TARGET<br/>QTY',width:100, halign: 'center', align: 'right'},
			    {field:'TARGET_AMT',title:'TARGET<br/>AMOUNT',width:100, halign: 'center', align: 'right'},
			    {field:'STS',title:'RESULT',width:100, halign: 'center', align: 'center'}
		    ]]
		});
	})

	var pdf_url='';

	function filter(event){
		var src = document.getElementById('src').value;
		var search = src.toUpperCase();
		document.getElementById('src').value = search;
		
	    if(event.keyCode == 13 || event.which == 13){
			var src = document.getElementById('src').value;
			//alert(src);
			$('#dg').datagrid('load', {
				src: search,
				cmbBln: $('#cmb_bln').combobox('getValue'),
				cmbBln_txt: $('#cmb_bln').combobox('getText')
			});

			pdf_url = "?cmbBln="+$('#cmb_bln').combobox('getValue')
					 +"&cmbBln_txt="+$('#cmb_bln').combobox('getText')
					 +"&src="+document.getElementById('src').value;

			$('#dg').datagrid({
		    	url:'material_inv_get.php'
		    });

			if (src=='') {
				filterData();
			};;
	    }
	}

	function filterData(){
		$('#dg').datagrid('load', {
			cmbBln: $('#cmb_bln').combobox('getValue'),
			cmbBln_txt: $('#cmb_bln').combobox('getText'),
			src: document.getElementById('src').value
		});

		pdf_url = "?cmbBln="+$('#cmb_bln').combobox('getValue')
				 +"&cmbBln_txt="+$('#cmb_bln').combobox('getText')
				 +"&src="+document.getElementById('src').value

		$('#dg').datagrid({
	    	url:'material_inv_get.php'
	    });

	    var dg = $('#dg').datagrid();
		dg.datagrid('enableFilter');
	}

	function print_xls(){
		if(pdf_url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('material_inv_excel.php'+pdf_url, '_blank');
		}
	}

	function print_pdf(){
		if(pdf_url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('material_pdf.php'+pdf_url, '_blank');
		}	
	}
</script>
</body>
</html>