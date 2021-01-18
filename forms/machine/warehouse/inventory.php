<?php
include("../../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>WAREHOUSE INVENTORY</title>
<link rel="icon" type="image/png" href="../../favicon.png">
<script language="javascript">
		function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
		}
</script> 
<link rel="stylesheet" type="text/css" href="../../../plugins/font-awesome/css/font-awesome.min.css">
   	<link rel="stylesheet" type="text/css" href="../../../themes/default/easyui.css" />
   	<link rel="stylesheet" type="text/css" href="../../../themes/icon.css" />
   	<link rel="stylesheet" type="text/css" href="../../../themes/color.css" />
   	<script type="text/javascript" src="../../../js/jquery-1.8.3.js"></script>
   	<script type="text/javascript" src="../../../js/jquery.easyui.min.js"></script>
   	<script type="text/javascript" src="../../../js/datagrid-filter.js"></script>
	<script type="text/javascript" src="../../../js/datagrid-detailview.js"></script>
	<script type="text/javascript" src="../../../js/jquery.edatagrid.js"></script>
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
<?php include ('../../../ico_logout.php'); ?>

<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:500px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong> PERIOD & ITEM FILTER </strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Search Item</span>
				<input style="width:320px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" autofocus="" />
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Period</span>
				<input style="width:120px;" name="cmb_bln" id="cmb_bln" class="easyui-combobox" data-options="url:'json/json_period.php', method:'get', valueField:'bln', textField:'blnA', panelHeight:'50px'" required=""/>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;"></span>
				<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:525px;border-radius:4px;width: 550px;height: 100px;"><legend><span class="style3"><strong> STOCK FILTER </strong></span></legend>
	   <div id="select_stock">
		<div class="fitem">
		  <span style="width:180px;display:inline-block;"><input type="radio" name="status_stock" id="check_all" value="check_all"/> ALL</span>
		  <!-- <span style="width:180px;display:inline-block;"><input type="radio" name="status_stock" id="check_PM" value="check_PM"/> PACKING MATERIALS</span>
		  <span style="width:180px;display:inline-block;"><input type="radio" name="status_stock" id="check_FG" value="check_FG"/> FINISHED GOODS</span> -->
		</div>
		<!-- <div class="fitem">
		  <span style="width:180px;display:inline-block;"><input type="radio" name="status_stock" id="check_WP" value="check_WP"/>WOODEN PALLET</span>
		  <span style="width:180px;display:inline-block;"><input type="radio" name="status_stock" id="check_WIP" value="check_WIP"/>WORK IN PROCESS</span>
		  <span style="width:180px;display:inline-block;"><input type="radio" name="status_stock" id="check_CSP" value="check_CSP"/>CUST. SUPPLY PRODUCTS</span>
		</div>
		<div class="fitem">
		  <span style="width:180px;display:inline-block;"><input type="radio" name="status_stock" id="check_RM" value="check_RM"/>RAW MATERIALS</span>
		  <span style="width:180px;display:inline-block;"><input type="radio" name="status_stock" id="check_semiFG" value="check_semiFG"/>SEMI FINISHED GOODS</span>
		  <span style="width:180px;display:inline-block;"><input type="radio" name="status_stock" id="check_material2" value="check_material2"/>MATERIALS 2</span>
		</div> -->
	   </div>
	</fieldset>
	<fieldset style="margin-left: 1098px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>PRINT DATA</strong></span></legend>
		<div class="fitem" align="center" style="margin-top: -3px;">
			<a href="javascript:void(0)" id="printxls" style="width: 200px;" class="easyui-linkbutton c2" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> print To Excel (+DETAIL)</a>
		</div>
		<div class="fitem" align="center" style="margin-top: 13px;">
			<a href="javascript:void(0)" id="printxls_header" style="width: 150px;" class="easyui-linkbutton c2" onclick="print_header()"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Print To Excel </a>
		</div>
		</div>
	</fieldset>
</div>
<table id="dg" title="WAREHOUSE INVENTORY" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

<div id='dlg_detail' class="easyui-dialog" style="width:100%;height:400px;" closed="true" data-options="modal:true">
	<table id="dg_detail" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" showFooter="true"></table>
</div>

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
		document.getElementById('check_all').checked=true;
		$('#dg').datagrid({
	    	url:'inventory_get.php',
	    	singleSelect: true,
		    fitColumns: true,
			rownumbers: true,
		    columns:[[
			    {field:'ITEM_NO',title:'ITEM No.',width:65, halign: 'center'},
			    {field:'DESCRIPTION',title:'DESCRIPTION',width:250, halign: 'center'},
			    {field:'UNIT',title:'UNIT',width:50, halign: 'center', align: 'center'},
			    {field:'PRD',title:'MONTH',width:65, halign: 'center', align: 'center'},
			    {field:'L_INVENTORY',hidden:true},
			    {field:'LAST_INVENTORY',title:'LAST INVENTORY', width:100, halign: 'center', align: 'right'},
			    {field:'RECEIVE1',title:'RECEIVE',width:100, halign: 'center',align: 'right'},
			    {field:'OTHER_RECEIVE1',title:'OTHER RECEIVE', width:100, halign: 'center', align: 'right'},
			    {field:'ISSUE1',title:'ISSUE', width:100, halign: 'center', align: 'right'},
			    {field:'OTHER_ISSUE1',title:'OTHER ISSUE', width:100, halign: 'center', align: 'right'},
			    {field:'THIS_MONTH',hidden: true},
			    {field:'THIS_INVENTORY',title:'THIS INVENTORY',width:100, halign: 'center', align: 'right'}
		    ]],
		    onDblClickRow:function(id,row){
				$('#dlg_detail').dialog('open').dialog('setTitle','Inventory Detail ('+row.ITEM_NO+' - '+row.DESCRIPTION+')');
				console.log('inventory_get_detail.php?id='+row.ITEM_NO+'&month='+$('#cmb_bln').combobox('getValue')+'&l_inv='+row.L_INVENTORY);
				$('#dg_detail').datagrid({
					url:'inventory_get_detail.php?id='+row.ITEM_NO+'&month='+$('#cmb_bln').combobox('getValue')+'&l_inv='+row.L_INVENTORY,
					singleSelect:true,
					showFooter: true,
					loadMsg:'Load Data, Please Wait ...',
					rownumbers: true,
					fitColumns: true,
					columns:[[
		                {field:'SLIP_DATE',title:'SLIP DATE', halign:'center', align:'center', width:50},
		                {field:'SLIP_NO', title:'SLIP NO', halign:'center', width:100},
		                {field:'SLIP_TYPE', title:'SLIP TYPE', halign:'center', width:100},
		                {field:'COMPANY', title:'COMPANY', halign:'center', width:130},
		                {field:'OHSAS', title:'DATE CODE', halign:'center', align:'center', width:50},
		                {field:'RECEIVE', title:'RECEIVE', halign:'center', align:'right', width:65},
		                {field:'OTHER_RECEIVE', title:'OTHER<br/>RECEIVE', halign:'center', align:'right', width:65},
		                {field:'ISSUE', title:'ISSUE', halign:'center', align:'right', width:65},
		                {field:'OTHER_ISSUE', title:'OTHER<br/>ISSUE', halign:'center', align:'right', width:65},
		                {field:'TOTAL', title:'INVENTORY', halign:'center', align:'right', width:65}
					]]
				});
			}
		});

		var dg = $('#dg').datagrid();
		dg.datagrid('enableFilter');
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
			if (src=='') {
				filterData();
			};;
	    }
	}

	function filterData(){
		var order;
		
		if(document.getElementById('check_all').checked == true){
			order = document.getElementById('check_all').value;
		}else if(document.getElementById('check_PM').checked == true){
			order = document.getElementById('check_PM').value;
		}else if(document.getElementById('check_FG').checked == true){
			order = document.getElementById('check_FG').value;
		}else if(document.getElementById('check_WP').checked == true){
			order = document.getElementById('check_WP').value;
		}else if(document.getElementById('check_WIP').checked == true){
			order = document.getElementById('check_WIP').value;
		}else if(document.getElementById('check_CSP').checked == true){
			order = document.getElementById('check_CSP').value;
		}else if(document.getElementById('check_RM').checked == true){
			order = document.getElementById('check_RM').value;
		}else if(document.getElementById('check_semiFG').checked == true){
			order = document.getElementById('check_semiFG').value;
		}else if(document.getElementById('check_material2').checked == true){
			order = document.getElementById('check_material2').value;
		}else{
			order ='';
		}

		
		
		$('#dg').datagrid('load', {
			cmbBln: $('#cmb_bln').combobox('getValue'),
			cmbBln_txt: $('#cmb_bln').combobox('getText'),
			src: document.getElementById('src').value,
			rdo_sts: order
		});
		

		pdf_url = "?cmbBln="+$('#cmb_bln').combobox('getValue')
				 +"&cmbBln_txt="+$('#cmb_bln').combobox('getText')
				 +"&src="+document.getElementById('src').value
				 +"&rdo_sts="+order
		
	}

	function print_xls(){
		if(pdf_url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('inventory_excel.php'+pdf_url, '_blank');
		}
	}

	function print_header(){
		if(pdf_url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('inventory_excel_header.php'+pdf_url, '_blank');
		}	
	}
</script>
</body>
</html>