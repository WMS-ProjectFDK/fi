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
<title>ESTIMATION INQUIRY</title>
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
<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:98%;border-radius:4px;height: auto;"><legend><span class="style3"><strong>FINISH GOODS ITEM FILTER</strong></span></legend>
		<div class="fitem">
			<span style="width:80px;display:inline-block;">WITH BOM</span>
			<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="find_brand_add()">SEARCH BRAND</a>
			<input style="width:300px;" name="brand_add" id="brand_add" class="easyui-textbox" disabled="true" />
			<input style="width:100px;" name="qty_brand_add" id="qty_brand_add" class="easyui-numberbox" data-options="prompt:'QTY...'"/>
			<a href="javascript:void(0)" style="width:150px;" class="easyui-linkbutton c2" onclick="select_brand_add()">CALCULATE</a>
		</div>
	</fieldset>
	<div style="clear:both;margin-bottom:10px;"></div>
</div>

<table id="dg" title="ESTIMATION INQUIRY" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:auto;"></table>

<!-- START ADD BRAND-->
<div id="dlg_addBrand" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons_addBrand" data-options="modal:true">
	<table id="dg_addBrand" class="easyui-datagrid" toolbar="#toolbar_addBrand" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
</div>

<div id="toolbar_addBrand" style="padding: 5px 5px;">
	<input style="width:200px;height: 20px;border-radius: 4px;" name="s_brand_add" id="s_brand_add" onkeypress="sch_brand_add(event)" autofocus="autofocus" />
	<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_brand_add()">SEARCH BRAND</a>
</div>
<!-- END ADD BRAND -->

<script type="text/javascript">
	/*$(function(){
		$('#dg').datagrid({
		    
		});
	});*/

	function sch_brand_add(event){
		var sch_a = document.getElementById('s_brand_add').value;
		var search = sch_a.toUpperCase();
		document.getElementById('s_brand_add').value = search;
		
	    if(event.keyCode == 13 || event.which == 13){
			search_brand_add();
	    }
	}

	function search_brand_add(){
		var s_item = document.getElementById('s_brand_add').value;

		if(s_item != ''){
			$('#dg_addBrand').datagrid('load',{item: s_item});
			$('#dg_addBrand').datagrid({url: 'estimation_inq_getBrand.php',});
			document.getElementById('s_brand_add').value = '';
		}
	}

	var n ='';

	function find_brand_add(){
		document.getElementById('s_brand_add').focus();
		$('#dlg_addBrand').dialog('open').dialog('setTitle','Search Item');
		$('#cmb_search_brand_a').combobox('setValue','ITEM_NO');
		$('#dg_addBrand').datagrid('load',{item: ''});
		$('#dg_addBrand').datagrid({
			fitColumns: true,
			columns:[[
                {field:'ITEM_NO',title:'ITEM NO.',width:70,halign:'center', align:'center'},
                {field:'ITEM',title:'ITEM',width:100,halign:'center'},
                {field:'DESCRIPTION',title:'DESCRIPTION',width:200,halign:'center'},
                {field:'UNIT',title:'UNIT',width:45,halign:'center', align:'center'},
                {field:'LVL',title:'LEVEL',width:45,halign:'center', align:'center'}
            ]],
            onDblClickRow:function(id,row){
            	n = row.ITEM_NO+'@'+row.ITEM+'@'+row.LVL;
            	$('#brand_add').textbox('setValue', row.ITEM_NO+' - '+row.DESCRIPTION);
            	$('#dlg_addBrand').dialog('close');
            }
		});

		$('#dg_addBrand').datagrid('loadData',[]);
	}

	function select_brand_add(){
		var br = n;
		var qt =$('#qty_brand_add').numberbox('getValue');
		if(br == '' && qt== ''){
			$.messager.alert('Warning','Item or Qty not Found','warning');
		}else{
			$('#dg').datagrid('load',{brand: br, qty: qt});
			$('#dg').datagrid({
				url: 'estimation_inq_getBrand_BOM.php',
				singleSelect: true,
				rownumbers: true,
			    columns:[[
				    {field:'ITEM_NO', title:'ITEM NO.', width:80, halign: 'center'},
				    {field:'ITEM', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
				    {field:'DESCRIPTION', title:'DESCRIPTION', halign: 'center', width:300},
				    {field:'UNIT_PL', title:'UoM', halign: 'center', width:75, align:'center'},
				    {field:'STOCK_WH', title:'WAREHOUSE<br>INVENTORY', halign: 'center', width:150, align:'right'},
				    {field:'END_STOCK', title:'ESTIMATION<br>END STOCK', halign: 'center',width:150, align:'right'},
				    {field:'END_DATE', title:'ESTIMATION<br>END DATE', halign: 'center',width:100, align:'center'},
				    {field:'SLIP_QTY', title:'ESTIMATION<br>INQUIRY', align:'right', halign: 'center', width:120},
				    {field:'TOTAL_END_STOCK', title:'TOTAL END<br>STOCK', align:'right', halign: 'center', width:150},
				    {field:'OPEN_PRF', title:'OUTSTANDING PRF', align:'right', halign: 'center', width:150}
			    ]]
			});	
		}
	}
</script>

</body>
</html>