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
<title>MATERIALS TRANSACTION</title>
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
	<fieldset style="float:left;width:540px;border-radius:4px;"><legend><span class="style3"><strong>Materials Transaction Filter</strong></span></legend>
		<div style="width:540px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Date</span>
				<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to 
				<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip No.</span>
				<select style="width:190px;" name="cmb_slip_no" id="cmb_slip_no" class="easyui-combobox" data-options=" url:'#', method:'get', valueField:'#', textField:'#', panelHeight:'75px'"></select>
				<label><input type="checkbox" name="ck_slip_no" id="ck_slip_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Type</span>
				<select style="width:370px;" name="cmb_slip_type" id="cmb_slip_type" class="easyui-combobox" data-options=" url:'#', method:'get', valueField:'#', textField:'#', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_slip_type" id="ck_slip_type" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:565px;border-radius:4px;width: 500px;"><legend><span class="style3"><strong>Item Filter</strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item No.</span>
			<select style="width:190px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'#', method:'get', valueField:'#', textField:'#', panelHeight:'100px'"></select>
			<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
		</div>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item Name</span>
			<select style="width:330px;" name="cmb_item_name" id="cmb_item_name" class="easyui-combobox" data-options=" url:'#', method:'get', valueField:'#', textField:'#', panelHeight:'150px'"></select>
			<label><input type="checkbox" name="ck_item_name" id="ck_item_name" checked="true">All</input></label>
		</div><br/><br/>
	</fieldset>
	<fieldset style="margin-left: 1090px;border-radius:4px;"><legend><span class="style3"><strong>Print Select</strong></span></legend>
		<div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 150px;" id="checkmaterial" class="easyui-linkbutton c2" onClick="print_out()"><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Check Material</a>
		</div>
		<div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 150px;" id="printpdf" class="easyui-linkbutton c2" onClick="print_do()"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>
		</div>
		<div class="fitem"></div><div class="fitem"></div><div class="fitem"></div><div class="fitem"></div>
	</fieldset>
	<div style="padding:5px 6px;">
    	<span style="width:50px;display:inline-block;">search</span>
		<input style="width:200px; height: 18px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src"type="text" />
		<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2" onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
		<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2" onclick="add_mt()"><i class="fa fa-plus" aria-hidden="true"></i> Add Materials Transaction</a>
		<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2" onclick="edit_mt()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Materials Transaction</a>
		<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2" onclick="delete_mt()"><i class="fa fa-trash" aria-hidden="true"></i> Remove Materials Transaction</a>
	</div></div>
</div>

<!-- START ADD_MT -->
<div id="dlg_add" class="easyui-dialog" style="width:1150px;height:400px;padding:5px 5px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left;">
		<div class="fitem">
			<span style="width:80px;display:inline-block;">SLIP NO</span>
			<input style="width:100px;" name="slip_no_add" id="slip_no_add" class="easyui-textbox" data-options="formatter:myformatter,parser:myparser" disabled=""/>
			<span style="width:100px;display:inline-block;"></span>
			<span style="width:80px;display:inline-block;">SLIP TYPE</span>
			<select style="width:250px;" name="cmb_slip_type_add" id="cmb_slip_type_add" class="easyui-combobox" data-options=" url:'json/json_sliptype.php', method:'get', valueField:'id', textField:'name', panelHeight:'auto'" required=""></select>
		</div>
		<div class="fitem">
			<span style="width:80px;display:inline-block;">SLIP DATE</span>
			<input style="width:100px;" name="slip_date_add" id="slip_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
			<span style="width:100px;display:inline-block;"></span>
			<span style="width:80px;display:inline-block;">ISSUE TO</span>
			<select style="width:250px;" name="cmb_company_add" id="cmb_company_add" class="easyui-combobox" data-options=" url:'json/json_company_plant.php', method:'get',valueField:'COMPANY_CODE', textField:'COMB_COMPANY', panelHeight:'auto'" required=""></select>
		</div>
	</fieldset>
	<div style="clear:both;margin-bottom:10px;"></div>
	<table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:1125px;height:auto; border-radius: 10px;"></table>
	<div id="toolbar_add" style="padding: 5px 5px;">
		<a href="#" iconCls='icon-add' class="easyui-linkbutton" onclick="add_do_add()">ADD ITEM</a>
		<a href="#" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_do_add()">REMOVE ITEM</a>
	</div>
	<div id="dlg_addItem" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons_addItem" data-options="modal:true">
		<table id="dg_addItem" class="easyui-datagrid" toolbar="#toolbar_addItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
	</div>
</div>
<div id="dlg-buttons-add">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveAdd()" style="width:90px">Save</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
</div>
<!-- END ADD_MT -->

<!-- START EDIT_MT -->
<div id="dlg_edit" class="easyui-dialog" style="width:1150px;height:400px;padding:5px 5px" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
	<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left;">
		<div class="fitem">
			<span style="width:80px;display:inline-block;">SLIP NO</span>
			<input style="width:100px;" name="slip_no_edit" id="slip_no_edit" class="easyui-textbox" data-options="formatter:myformatter,parser:myparser" disabled=""/>
			<span style="width:100px;display:inline-block;"></span>
			<span style="width:80px;display:inline-block;">SLIP TYPE</span>
			<select style="width:250px;" name="cmb_slip_type_edit" id="cmb_slip_type_edit" class="easyui-combobox" data-options=" url:'json/json_sliptype.php', method:'get', valueField:'id', textField:'name', panelHeight:'auto'" disabled=""></select>
		</div>
		<div class="fitem">
			<span style="width:80px;display:inline-block;">SLIP DATE</span>
			<input style="width:100px;" name="slip_date_edit" id="slip_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
			<span style="width:100px;display:inline-block;"></span>
			<span style="width:80px;display:inline-block;">ISSUE TO</span>
			<select style="width:250px;" name="cmb_company_edit" id="cmb_company_edit" class="easyui-combobox" data-options=" url:'json/json_company_plant.php', method:'get',valueField:'COMPANY_CODE', textField:'COMB_COMPANY', panelHeight:'auto'" disabled=""></select>
		</div>
	</fieldset>
	<div style="clear:both;margin-bottom:10px;"></div>
	<table id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:1125px;height:auto; border-radius: 10px;"></table>
	<div id="toolbar_edit" style="padding: 5px 5px;">
		<a href="#" iconCls='icon-add' class="easyui-linkbutton" onclick="add_do_edit()">ADD ITEM</a>
		<a href="#" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_do_edit()">REMOVE ITEM</a>
	</div>
	<div id="dlg_editItem" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons_editItem" data-options="modal:true">
		<table id="dg_editItem" class="easyui-datagrid" toolbar="#toolbar_editItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
	</div>
</div>
<div id="dlg-buttons-edit">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveEdit()" style="width:90px">Save</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
</div>
<!-- END EDIT_MT -->

<div id="dlg_print" class="easyui-dialog" style="width:920px;height:500px;" closed="true" buttons="#dlg-buttons-print" data-options="modal:true">
	<table id="dg_check" class="easyui-datagrid"></table><br/>
</div>

<div id="dlg-buttons-print">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-print" onClick="print_pdf()" style="width:90px">Print</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_print').dialog('close');$('#dg').datagrid('reload');" style="width:90px">Cancel</a>
</div>

<table id="dg" title="MATERIALS TRANSACTION" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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
		$('#cmb_slip_no').combobox('disable');
		$('#ck_slip_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_slip_no').combobox('disable');
			}else{
				$('#cmb_slip_no').combobox('enable');
			}
		});

		$('#cmb_slip_type').combobox('disable');
		$('#ck_slip_type').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_slip_type').combobox('disable');
			}else{
				$('#cmb_slip_type').combobox('enable');
			}
		});

		$('#cmb_item_no').combobox('disable');
		$('#ck_item_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_item_no').combobox('disable');
			}else{
				$('#cmb_item_no').combobox('enable');
			}
		});

		$('#cmb_item_name').combobox('disable');
		$('#ck_item_name').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_item_name').combobox('disable');
			}else{
				$('#cmb_item_name').combobox('enable');
			}
		});

		$('#dg').datagrid( {
		    columns:[[
			    {field:'SLIP_NO',title:'SLIP NO.', halign:'center', width:150, sortable: true, sortable: true},
                {field:'SLIP_DATE', title:'SLIP DATE', halign:'center', align:'center', width:50, sortable: true}, 
                {field:'COMPANY_CODE',hidden:true},
                {field:'SLIP_TYPE',hidden:true},
                {field:'COMPANY',title:'COMPANY', halign:'center', width:200, sortable: true},
                {field:'APPROVAL_DATE', title:'APPROVAL<br>DATE', halign:'center', align:'center', width:50},
                {field:'PERSON_CODE', title:'USER ENTRY', halign:'center', width:70},
                {field:'STS', title:'STS', halign:'center', align:'center', width:70, hidden: true},
                {field:'STS_NAME', title:'Status', halign:'center', align:'center', width:90}
			]]
		});

		$('#dg_add').datagrid({
		    singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'ITEM_NO', title:'ITEM<br>NO.', width:80, halign: 'center'},
			    {field:'ITEM', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
			    {field:'DESCRIPTION', title:'DESCRIPTION', halign: 'center', width:250},
			    {field:'UNIT_PL', title:'UoM', halign: 'center', width:65, align:'center'},
			    {field:'STOCK', title:'STOCK', halign: 'center',width:120, align:'right'},
			    {field:'SLIP_QTY', title:'SLIP<br>QTY', align:'right', halign: 'center', width:130, editor:{type:'numberbox',options:{precision:0,groupSeparator:','}}},
			    {field:'COST_PROCESS_CODE', title:'COST PROCESS<br>CODE', width:170, halign: 'center', editor:{type:'combobox',
																											   options:{
																											   		url: 'json/json_cost_process_code.php',
																											   		valueField: 'id',
																											   		textField: 'name'
																											   } 
																											  }
				},
			    {field:'WO_NO', title:'WO NO.', halign: 'center', width:120, align:'center', editor:{type:'textbox',required: true}},
			    {field:'DATE_CODE', title:'DATE CODE', halign: 'center', width:120, align:'center', editor:{type:'textbox'}},
			    {field:'REMARK', title:'REMARK', halign: 'center', width:120, align:'center', editor:{type:'textbox'}},
			    {field:'UOM_Q', hidden: true}
		    ]],
		    onClickRow:function(rowIndex){
		        $(this).datagrid('beginEdit', rowIndex);
		        /*if (lastIndex != rowIndex){
		            $(this).datagrid('endEdit', lastIndex);
		        }
		        lastIndex = rowIndex;*/
		    }/*,
		    onDblClickRow:function(rowIndex){
		    	$(this).datagrid('beginEdit', rowIndex);
		    }*/
		});

		$('#dg_edit').datagrid({
		    singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'ITEM_NO', title:'ITEM<br>NO.', width:80, halign: 'center'},
			    {field:'ITEM', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
			    {field:'DESCRIPTION', title:'DESCRIPTION', halign: 'center', width:250},
			    {field:'UNIT_PL', title:'UoM', halign: 'center', width:65, align:'center'},
			    {field:'STOCK', title:'STOCK', halign: 'center',width:120, align:'right'},
			    {field:'QTY', title:'SLIP<br>QTY', align:'right', halign: 'center', width:130, editor:{type:'numberbox',options:{precision:0,groupSeparator:','}}},
			    {field:'COST_PROCESS_CODE', title:'COST PROCESS<br>CODE', width:170, halign: 'center', editor:{type:'combobox',
																											   options:{
																											   		url: 'json/json_cost_process_code.php',
																											   		valueField: 'id',
																											   		textField: 'name'
																											   } 
																											  }
				},
			    {field:'WO_NO', title:'WO NO.', halign: 'center', width:120, align:'center', editor:{type:'textbox',required: true}},
			    {field:'DATE_CODE', title:'DATE CODE', halign: 'center', width:120, align:'center', editor:{type:'textbox'}},
			    {field:'REMARK', title:'REMARK', halign: 'center', width:120, align:'center', editor:{type:'textbox'}},
			    {field:'UOM_Q', hidden: true}
		    ]],
		    onClickRow:function(rowIndex){
		        $(this).datagrid('beginEdit', rowIndex);
		    }
		});

		$('#dg_addItem').datagrid({
			fitColumns: true,
			url: 'do_getItem.php',
			columns:[[
                {field:'ITEM_NO',title:'ITEM NO.',width:80,halign:'center', align:'center'},
                {field:'ITEM',title:'ITEM',width:200,halign:'center'},
                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
                {field:'UNIT_PL',title:'UNIT',width:50,halign:'center', align:'right'},
                {field:'STOCK',title:'STOCK',width:100,halign:'center', align:'right'},
                {field:'UOM_Q', hidden:true}
            ]],
            onDblClickRow:function(id,row){
				var t = $('#dg_add').datagrid('getRows');
				var total = t.length;
			   	var idxfield=0;
			   	var i = 0;
			   	var count = 0;
				if (parseInt(total) == 0) {
					idxfield=total;
				}else{
					idxfield=total+1;
					for (i=0; i < total; i++) {
						//alert(i);
						var item = $('#dg_add').datagrid('getData').rows[i].ITEM_NO;
						//alert(item);
						if (item == row.ITEM_NO) {
							count++;
						};
					};
				}

				if (count>0) {
					$.messager.alert('Warning','Item present','warning');
				}else{
					$('#dg_add').datagrid('insertRow',{
						index: idxfield,	// index start with 0
						row: {
							ITEM_NO: row.ITEM_NO,
							DESCRIPTION: row.DESCRIPTION,
							ITEM: row.ITEM,
							UNIT_PL: row.UNIT_PL,
							UOM_Q: row.UOM_Q,
							STOCK: row.STOCK,
							COST_PROCESS_CODE: row.COST_PROCESS_CODE,
							SLIP_QTY: row.SLIP_QTY,
							WO_NO: row.WO_NO,
							DATE_CODE: row.DATE_CODE,
							REMARK: row.REMARK
						}
					});
				}
			}
		});

		$('#dg_editItem').datagrid({
			fitColumns: true,
			url: 'do_getItem.php',
			columns:[[
                {field:'ITEM_NO',title:'ITEM NO.',width:80,halign:'center', align:'center'},
                {field:'ITEM',title:'ITEM',width:200,halign:'center'},
                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
                {field:'UNIT_PL',title:'UNIT',width:50,halign:'center', align:'right'},
                {field:'STOCK',title:'STOCK',width:100,halign:'center', align:'right'},
                {field:'UOM_Q', hidden:true}
            ]],
            onDblClickRow:function(id,row){
				var t = $('#dg_edit').datagrid('getRows');
				var total = t.length;
			   	var idxfield=0;
			   	var i = 0;
			   	var count = 0;
				if (parseInt(total) == 0) {
					idxfield=total;
				}else{
					idxfield=total+1;
					for (i=0; i < total; i++) {
						//alert(i);
						var item = $('#dg_edit').datagrid('getData').rows[i].ITEM_NO;
						//alert(item);
						if (item == row.ITEM_NO) {
							count++;
						};
					};
				}

				if (count>0) {
					$.messager.alert('Warning','Item present','warning');
				}else{
					$('#dg_edit').datagrid('insertRow',{
						index: idxfield,	// index start with 0
						row: {
							ITEM_NO: row.ITEM_NO,
							DESCRIPTION: row.DESCRIPTION,
							ITEM: row.ITEM,
							UNIT_PL: row.UNIT_PL,
							UOM_Q: row.UOM_Q,
							STOCK: row.STOCK,
							COST_PROCESS_CODE: row.COST_PROCESS_CODE,
							QTY: row.SLIP_QTY,
							WO_NO: row.WO_NO,
							DATE_CODE: row.DATE_CODE,
							REMARK: row.REMARK
						}
					});
				}
			}
		});
		
	});

	function filterData(){
		var ck_slip_no = "false";
		var ck_slip_type = "false";
		var ck_item_no = "false";
		var ck_item_name = "false";

		if ($('#ck_slip_no').attr("checked")) {
			ck_slip_no = "true";
		};

		if ($('#ck_slip_type').attr("checked")) {
			ck_slip_type = "true";
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
		};

		if ($('#ck_item_name').attr("checked")) {
			ck_item_name = "true";
		};

		//$.messager.alert("warning",$('#date_akhir').datebox('getValue'),"Warning");

		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			cmb_slip_no : $('#cmb_slip_no').combobox('getValue'),
			ck_slip_no: ck_slip_no,
			cmb_slip_type: $('#cmb_slip_type').combobox('getValue'),
			ck_slip_type: ck_slip_type,
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no,
			cmb_item_name: $('#cmb_item_name').combobox('getValue'),
			ck_item_name: ck_item_name,
			src: ''
		});

		$('#dg').datagrid( {
			url: 'do_get.php',
			view: detailview,
			detailFormatter: function(rowIndex, rowData){
				return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
			},
			onExpandRow: function(index,row){
				var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
				listbrg.datagrid({
                	title: 'Materials Transaction Detail (No: '+row.SLIP_NO+')',
					url:'do_get_detail.php?req='+row.SLIP_NO,
					toolbar: '#ddv'+index,
					singleSelect:true,
					rownumbers:true,
					loadMsg:'load data ...',
					height:'auto',
					rownumbers: true,
					fitColumns: true,
					columns:[[
						{field:'SLIP_NO',title:'Slip No.', halign:'center', width:150, sortable: true, hidden:true},
		                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:80, sortable: true},
		                {field:'DESCRIPTION', title:'Material Name', halign:'center', width:240},
		                {field:'LINE_NO', title:'Line No.', halign:'center', align:'center', width:50}, 
		                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
		                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:70}
					]],
					onResize:function(){
						//alert(index);
						$('#dg').datagrid('fixDetailRowHeight',index);
					},
					onLoadSuccess:function(){
						setTimeout(function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},0);
					}
                });
			}
		})
		$('#dg').datagrid('enableFilter');
	}

	function add_mt(){
		$('#dlg_add').dialog('open').dialog('setTitle','Add Materials Transaction');
		$('#slip_no_add').textbox('setValue','');
		$('#cmb_slip_type_add').combobox('setValue','');
		//$('#cmb_company_add').combobox('setValue','');
		$('#dg_add').datagrid('loadData',[]);
	}

	function add_do_add(){
		$('#dlg_addItem').dialog('open').dialog('setTitle','Search Item');
		var dg = $('#dg_addItem').datagrid();
		dg.datagrid('enableFilter');
	}

	function remove_do_add(){
		var row = $('#dg_add').datagrid('getSelected');	
		if (row){
			var idx = $("#dg_add").datagrid("getRowIndex", row);
			$('#dg_add').datagrid('deleteRow', idx);
		}
	}

	function simpan(){
		var slip_no_add = $('#slip_no_add').textbox('getValue');
		var slip_date_add = $('#slip_date_add').datebox('getValue');
		var cmb_slip_type_add = $('#cmb_slip_type_add').combobox('getValue');
		var cmb_company_add = $('#cmb_company_add').combobox('getValue');

		if(slip_no_add=='' && slip_date_add=='' && cmb_slip_type_add=='' && cmb_company_add==''){
			$.messager.alert("warning","Required Field Can't Empty!","Warning");
		}else{
			var t = $('#dg_add').datagrid('getRows');
			var total = t.length;
			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_add').datagrid('endEdit',i);
				$.post('do_save.php',{
					do_slip: slip_no_add,
					do_line: jmrow,
					do_date: slip_date_add,
					do_type: cmb_slip_type_add,
					do_comp: cmb_company_add,
					do_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
					do_qqty: $('#dg_add').datagrid('getData').rows[i].SLIP_QTY.replace(/,/g,''),
					do_uomq: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
					do_cost: $('#dg_add').datagrid('getData').rows[i].COST_PROCESS_CODE,
					do_d_co: $('#dg_add').datagrid('getData').rows[i].DATE_CODE,
					do_rmrk: $('#dg_add').datagrid('getData').rows[i].REMARK,
					do_wono: $('#dg_add').datagrid('getData').rows[i].WO_NO
				}).done(function(res){
					//alert(res);
					//console.log(res);
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
				})
			}
		}
	}

	function saveAdd(){
		var url='';
		var slip = $('#cmb_slip_type_add').combobox('getValue');
		$.ajax({
			type: 'GET',
			url: 'json/json_kode_slip.php?slip='+slip,
			data: { kode:'kode' },
			success: function(data){
				$('#slip_no_add').textbox('setValue', data[0].kode);
				simpan();
			}
		});
	}

	function edit_mt(){
		var row = $('#dg').datagrid('getSelected');	
		if (row){
			$('#dlg_edit').dialog('open').dialog('setTitle','Edit Materials Transaction ('+row.SLIP_NO+')');
			$('#slip_no_edit').textbox('setValue',row.SLIP_NO);
			$('#slip_date_edit').datebox('setValue',row.SLIP_DATE);
			$('#cmb_slip_type_edit').combobox('setValue',row.SLIP_TYPE);
			$('#cmb_company_edit').combobox('setValue',row.COMPANY_CODE);

			$('#dg_edit').datagrid('loadData',[]);
			$('#dg_edit').datagrid({
				url:'do_getedit.php?slip_no='+row.SLIP_NO
			});
		}
	}

	function add_do_edit(){
		$('#dlg_editItem').dialog('open').dialog('setTitle','Search Item');
		var dg = $('#dg_editItem').datagrid();
		dg.datagrid('enableFilter');
	}

	function remove_do_edit(){
		var row = $('#dg_edit').datagrid('getSelected');
		if (row){
			$.messager.confirm('Confirm','Are you sure you want to destroy this Item?',function(r){
                if (r){
                	$.post('do_destroy_dtl.php',{
						slip: $('#slip_no_edit').textbox('getValue'),
						item: row.ITEM_NO
					}).done(function(res){
						//alert(res)
						var idx = $("#dg_edit").datagrid("getRowIndex", row);
						$('#dg_edit').datagrid('deleteRow', idx);
					});
                }
            });
			
		}
	}

	function saveEdit(){
		var slip_no_edit = $('#slip_no_edit').textbox('getValue');
		var slip_date_edit = $('#slip_date_edit').datebox('getValue');
		var cmb_slip_type_edit = $('#cmb_slip_type_edit').combobox('getValue');
		var cmb_company_edit = $('#cmb_company_edit').combobox('getValue');

		if(slip_no_edit=='' && slip_date_edit=='' && cmb_slip_type_edit=='' && cmb_company_edit==''){
			$.messager.alert("warning","Required Field Can't Empty!","Warning");
		}else{
			var t = $('#dg_edit').datagrid('getRows');
			var total = t.length;
			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_edit').datagrid('endEdit',i);
				$.post('do_save_edit.php',{
					do_slip: slip_no_edit,
					do_line: jmrow,
					do_date: slip_date_edit,
					do_type: cmb_slip_type_edit,
					do_comp: cmb_company_edit,
					do_item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
					do_qqty: $('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,''),
					do_uomq: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
					do_cost: $('#dg_edit').datagrid('getData').rows[i].COST_PROCESS_CODE,
					do_d_co: $('#dg_edit').datagrid('getData').rows[i].DATE_CODE,
					do_rmrk: $('#dg_edit').datagrid('getData').rows[i].REMARK,
					do_wono: $('#dg_edit').datagrid('getData').rows[i].WO_NO
				}).done(function(res){
					//alert(res);
					console.log(res);
					/*$('#dlg_edit').dialog('close');
					$('#dg').datagrid('reload');*/
				})
			}
		}
	}

	function delete_mt(){
		var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Are you sure you want to destroy this Materials Transaction?',function(r){
                if (r){
                    $.post('do_destroy.php',{id:row.SLIP_NO},function(result){
                        if (result.success){
                            $('#dg').datagrid('reload');    // reload the user data
                        }else{
                            $.messager.show({    // show error message
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        }
                    },'json');
                }
            });
        }
	}

	function print_out(){
		var row = $('#dg').datagrid('getSelected');
		if(row){
			if(row.STS == 1){
				$.messager.alert('Warning','Data Already Exist','warning');
			}else{
				$('#dlg_print').dialog('open').dialog('setTitle','Check Material ('+row.SLIP_NO+')');
				
				$('#dg_check').datagrid({
					url:'outgoing_check_get.php?slip='+row.SLIP_NO,
				    singleSelect: true,
					rownumbers: true,
					fitColumns: true,
				    columns:[[
						{field:'SLIP_NO',title:'Slip No.', halign:'center', width:150, sortable: true, hidden:true},
		                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:80, sortable: true},
		                {field:'DESCRIPTION', title:'Material Name', halign:'center', width:240},
		                {field:'LINE_NO', title:'Line No.', halign:'center', align:'center', width:50}, 
		                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
		                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:70}
					]],
					view: detailview,
					detailFormatter: function(rowIndex, rowData){
						return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="lismaterial"></table></div>';
					},
					onExpandRow: function(index,row){
						var lismaterial = $(this).datagrid('getRowDetail',index).find('table.lismaterial');

						lismaterial.datagrid({
		                	title: 'MAterial Detail (Item: '+row.DESCRIPTION+')',
							url:'outgoing_check_get_detail.php?slip='+row.SLIP_NO+'&item='+row.ITEM_NO+'&qty='+row.QTY+'&ln='+row.LINE_NO,
							singleSelect:true,
							rownumbers:true,
							loadMsg:'load data ...',
							height:'auto',
							rownumbers: true,
							fitColumns: true,
							columns:[[
						    	{field:'GR_NO',title:'Good Receive No. ', halign:'center', width:150, sortable: true},
				                {field:'LINE_NO', title:'Line No.', halign:'center', align:'center', width:80}, 
				                {field:'RACK',title:'Rack No.', halign:'center', align:'center', width:80, sortable: true},
				                {field:'PALLET', title:'PALLET', halign:'center', align:'center', width:70},
				                {field:'QTY', title:'QTY', halign:'center', align:'center', width:100},
				                {field:'ID', title:'ID', halign:'center', align:'center',hidden: true},
				                {field:'ITEM_NO', title:'Item No.', halign:'center', align:'center', hidden: true}
						    ]],
							onResize:function(){
								//alert(index);
								$('#dg_check').datagrid('fixDetailRowHeight',index);
							},
							onLoadSuccess:function(){
								setTimeout(function(){
									$('#dg_check').datagrid('fixDetailRowHeight',index);
								},0);
							}
		                });
					}
				});
			}
		}else{
			$.messager.show({title: 'Check Materials',msg: 'Data Not select'});
		}
	}

	function print_do(){
		var row = $('#dg').datagrid('getSelected');
		if(row){
			if(row.STS == 1){
				pdf_url = "?do="+row.SLIP_NO
				window.open('outgoing_print.php'+pdf_url);	
			}else{
				$.messager.alert('Warning','Please select button Check Material','warning');
			}
		}else{
			$.messager.show({title: 'Check Materials',msg: 'Data Not select'});
		}
	}

	function print_pdf(){
		var i = 0;
		var proc = 0;
		var rows = $('#dg_check').datagrid('getRows');
		
		for(i=0;i<rows.length;i++){
			$('#dg_check').datagrid('endEdit', i);
			$.post("outgoing_save.php", {
				slip: $('#dg_check').datagrid('getData').rows[i].SLIP_NO,
				item: $('#dg_check').datagrid('getData').rows[i].ITEM_NO,
				qty: $('#dg_check').datagrid('getData').rows[i].QTY,
				ln: $('#dg_check').datagrid('getData').rows[i].LINE_NO
			}).done(function(res){
				$('#dlg_print').dialog('close');
				$('#dg').datagrid('reload');
				//alert(res);
				//console.log(res);
			});
		}
		$.messager.alert('Save Outgoing Material','Data Saved!','info');
	}
</script>
</body>
</html>