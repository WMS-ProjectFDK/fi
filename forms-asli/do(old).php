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

	<fieldset style="float:left;width:470px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>Materials Transaction Filter</strong></span></legend>
		<div style="width:470px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Date</span>
				<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to 
				<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip No.</span>
				<select style="width:190px;" name="cmb_slip_no" id="cmb_slip_no" class="easyui-combobox" data-options=" url:'json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'slip_no', panelHeight:'75px'"></select>
				<label><input type="checkbox" name="ck_slip_no" id="ck_slip_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Type</span>
				<select style="width:300px;" name="cmb_slip_type" id="cmb_slip_type" class="easyui-combobox" data-options=" url:'json/json_sliptype.php', method:'get', valueField:'id', textField:'name', panelHeight:'auto'"></select>
				<label><input type="checkbox" name="ck_slip_type" id="ck_slip_type" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>

	<fieldset style="position:absolute;margin-left:495px;border-radius:4px;width: 500px;height: 100px;"><legend><span class="style3"><strong>Item Filter</strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item No.</span>
			<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
			onSelect:function(rec){
				//alert(rec.id_name_item);
				var spl = rec.id_name_item;
				var sp = spl.split(' - ');
				$('#txt_item_name').textbox('setValue', sp[1]);
			}"></select>
			<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
		</div>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item Name</span>
			<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox"></input>
		</div>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Approval</span>
			<select style="width:190px;" name="cmb_sts_approval" id="cmb_sts_approval" class="easyui-combobox" data-options="panelHeight:'50px'">
				<option selected=""></option>
				<option value=0> BELUM APPROVE</option>
				<option value=1> SUDAH APPROVE</option>
			</select>
			<label><input type="checkbox" name="ck_sts_approval" id="ck_sts_approval" checked="true">All</input></label>
		</div>
	</fieldset>

	<fieldset style="margin-left: 1020px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>Print Select</strong></span></legend>
		
	</fieldset>

	<div style="padding:5px 6px;">
       <span style="width:50px;display:inline-block;">search</span>
	   <input style="width:180px; height: 18px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" />
	   <a href="javascript:void(0)" style="width: 180px;" class="easyui-linkbutton c2" onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
	   <a href="javascript:void(0)" style="width: 180px;" class="easyui-linkbutton c2" onclick="add_mt()"><i class="fa fa-plus" aria-hidden="true"></i> Add Materials Transaction</a>
	   <a href="javascript:void(0)" style="width: 180px;" class="easyui-linkbutton c2" onclick="edit_mt()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Materials Transaction</a>
	   <a href="javascript:void(0)" style="width: 190px;" class="easyui-linkbutton c2" onclick="delete_mt()"><i class="fa fa-trash" aria-hidden="true"></i> Remove Materials Transaction</a>
	   <a href="javascript:void(0)" style="width: 190px;" class="easyui-linkbutton c2" onclick="print_mt()"><i class="fa fa-trash" aria-hidden="true"></i> Print Materials Transaction</a>
	</div>
</div>

<!-- START ADD_MT -->
<div id="dlg_add" class="easyui-dialog" style="width:1150px;height:400px;padding:5px 5px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:650px; float:left;height: 60px;">
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
	<fieldset style="position:absolute;margin-left:675px;border:1px solid #d0d0d0;border-radius:2px;width: 430px;height: 60px;">
		<div class="fitem">
			<span style="width:80px;display:inline-block;">WITH BOM</span>
			<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="find_brand_add()">SEARCH BRAND</a>
		</div>
		<div class="fitem">
		<input style="width:300px;" name="brand_add" id="brand_add" class="easyui-textbox" disabled="true" />
		<input style="width:70px;" name="qty_brand_add" id="qty_brand_add" class="easyui-numberbox" data-options="prompt:'QTY...'"/>
		<a href="javascript:void(0)" style="width:50px;" class="easyui-linkbutton" onclick="select_brand_add()">ADD</a>
		</div>
	</fieldset>
	<div style="clear:both;margin-bottom:10px;"></div>
	<table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:1125px;height:230px; border-radius: 10px;"></table>
	<div id="toolbar_add" style="padding: 5px 5px;">
		<a href="#" iconCls='icon-add' class="easyui-linkbutton" onclick="add_do_add()">ADD ITEM</a>
		<a href="#" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_do_add()">REMOVE ITEM</a>
	</div>
	<!-- START ADD ITEM -->
	<div id="dlg_addItem" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons_addItem" data-options="modal:true">
		<table id="dg_addItem" class="easyui-datagrid" toolbar="#toolbar_addItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
	</div>

	<div id="toolbar_addItem" style="padding: 5px 5px;">
		<span style="width:80px;display:inline-block;">Search By</span>
		<select style="width:85px;" name="cmb_search_a" id="cmb_search_a" class="easyui-combobox" data-options="panelHeight:'70px'">
			<option value="ITEM_NO" selected="">ITEM NO</option>
			<option value="DESCRIPTION">DESCRIPTION</option>
		</select>
		<input style="width:200px;height: 20px;border-radius: 4px;" name="s_item_add" id="s_item_add" onkeypress="sch_item_add(event)"/>
		<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_item_add()">SEARCH ITEM</a>
	</div>
	<!-- END ADD ITEM -->

	<!-- START ADD BRAND-->
	<div id="dlg_addBrand" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons_addBrand" data-options="modal:true">
		<table id="dg_addBrand" class="easyui-datagrid" toolbar="#toolbar_addBrand" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
	</div>

	<div id="toolbar_addBrand" style="padding: 5px 5px;">
		<input style="width:200px;height: 20px;border-radius: 4px;" name="s_brand_add" id="s_brand_add" onkeypress="sch_brand_add(event)" autofocus="autofocus" />
		<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_brand_add()">SEARCH BRAND</a>
	</div>
	<!-- END ADD BRAND -->

</div>
<div id="dlg-buttons-add">
	<a href="javascript:void(0)" id="save_add" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveAdd()" style="width:90px">Save</a>
	<a href="javascript:void(0)" id="cancel_add" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
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
	<table id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:1125px;height:230px; border-radius: 10px;"></table>
	<div id="toolbar_edit" style="padding: 5px 5px;">
		<a href="#" iconCls='icon-add' class="easyui-linkbutton" onclick="add_do_edit()">ADD ITEM</a>
		<a href="#" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_do_edit()">REMOVE ITEM</a>
	</div>
	<div id="dlg_editItem" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons_editItem" data-options="modal:true">
		<table id="dg_editItem" class="easyui-datagrid" toolbar="#toolbar_editItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
	</div>

	<div id="toolbar_editItem" style="padding: 5px 5px;">
		<span style="width:80px;display:inline-block;">Search By</span>
		<select style="width:85px;" name="cmb_search_e" id="cmb_search_e" class="easyui-combobox" data-options="panelHeight:'70px'">
			<option value="ITEM_NO" selected="">ITEM NO</option>
			<option value="DESCRIPTION">DESCRIPTION</option>
		</select>
		<input style="width:200px;height: 20px;border-radius: 4px;" name="s_item_edit" id="s_item_edit" onkeypress="sch_item_edit(event)"/>
		<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_item_edit()">SEARCH ITEM</a>
	</div>

</div>
<div id="dlg-buttons-edit">
	<a href="javascript:void(0)" id="save_edit" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveEdit()" style="width:90px">Save</a>
	<a href="javascript:void(0)" id="cancel_edit" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
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
	//slip_date_edit
	


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

	function filter(event){
		var src = document.getElementById('src').value;
		var search = src.toUpperCase();
		document.getElementById('src').value = search;
		
	    if(event.keyCode == 13 || event.which == 13){
			var src = document.getElementById('src').value;
			//alert(src);
			$('#dg').datagrid('load', {
				src: search
			});

			$('#dg').datagrid( {
				url: 'do_get.php'
			});
			
			$('#dg').datagrid('enableFilter');

			if (src == '') {
				filterData();
			};
			//document.getElementById('src').value = '';
	    }
	}

	function sch_brand_add(event){
		var sch_a = document.getElementById('s_brand_add').value;
		var search = sch_a.toUpperCase();
		document.getElementById('s_brand_add').value = search;
		
	    if(event.keyCode == 13 || event.which == 13){
			search_brand_add();
	    }
	}

	function sch_item_add(event){
		var sch_a = document.getElementById('s_item_add').value;
		var search = sch_a.toUpperCase();
		document.getElementById('s_item_add').value = search;
		
	    if(event.keyCode == 13 || event.which == 13){
			search_item_add();
	    }
	}

	function sch_item_edit(event){
		var sch_e = document.getElementById('s_item_edit').value;
		var search_e = sch_e.toUpperCase();
		document.getElementById('s_item_edit').value = search_e;
		
	    if(event.keyCode == 13 || event.which == 13){
			search_item_edit();
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
		$('#txt_item_name').textbox('disable');
		$('#ck_item_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_item_no').combobox('disable');
				$('#txt_item_name').textbox('disable');
			}else{
				$('#cmb_item_no').combobox('enable');
				$('#txt_item_name').textbox('enable');
			}
		});

		$('#cmb_sts_approval').combobox('disable');
		$('#ck_sts_approval').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_sts_approval').combobox('disable');
			}else{
				$('#cmb_sts_approval').combobox('enable');
			}
		});

		$('#dg').datagrid( {
		    columns:[[
			    {field:'SLIP_NO',title:'SLIP NO.', halign:'center', width:150, sortable: true, sortable: true},
                {field:'SLIP_DATE', title:'SLIP DATE', halign:'center', align:'center', width:50, sortable: true}, 
                {field:'SLIP_DT', hidden: true}, 
                {field:'COMPANY_CODE',hidden:true},
                {field:'SLIP_TYPE',hidden:true},
                {field:'COMPANY',title:'COMPANY', halign:'center', width:200, sortable: true},
                {field:'APPROVAL_DATE', title:'APPROVAL<br>DATE', halign:'center', align:'center', width:50},
                {field:'PERSON_CODE', title:'USER ENTRY', halign:'center', width:70},
                {field:'STS', title:'STS', halign:'center', align:'center', width:70, hidden: true},
                {field:'STS_NAME', title:'STATUS', halign:'center', align:'center', width:90}
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
			    {field:'SLIP_QTY', title:'SLIP<br>QTY', align:'right', halign: 'center', width:100, editor:{type:'numberbox',options:{precision:0,groupSeparator:','}}},
			    {field:'COST_PROCESS_CODE', title:'COST PROCESS<br>CODE', width:200, halign: 'center', editor:{type:'combobox',
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
			    {field:'UOM_Q', hidden: true},
			    {field:'STOCK2', halign: 'center',width:120, align:'right', editor:{
    																			type:'numberbox',
    																			options:{precision:0,groupSeparator:','},
    																			disabled:true
    																		}
			    ,hidden: true}
		    ]],
		    onClickRow:function(rowIndex){
		        $(this).datagrid('beginEdit', rowIndex);
		    },
		    onBeginEdit:function(rowIndex){
		        var editors = $('#dg_add').datagrid('getEditors', rowIndex);
		        var n1 = $(editors[0].target);
		        var n2 = $(editors[5].target);
                n1.numberbox({
		            onChange:function(){
		            	var slip_type = $('#cmb_slip_type_add').combobox('getValue');
		            	if(slip_type == '21'){
		            		if(parseFloat(n2.numberbox('getValue').replace(/,/g,'')) - parseFloat(n1.numberbox('getValue').replace(/,/g,'')) < 0 ){
								$.messager.confirm('Confirm','actual value over',function(r){
									if(r){
										n1.numberbox('setValue',0);
									}
								});
			                }
		            	}
		            }
		        })
		    }
		});

		$('#dg_edit').datagrid({
		    singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'LINE_NO', title:'LINE<br>NO.', width:50, halign: 'center', align: 'center'},
			    {field:'ITEM_NO', title:'ITEM<br>NO.', width:80, halign: 'center', align: 'center'},
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
			    {field:'UOM_Q', hidden: true},
			    {field:'STOCK2', halign: 'center',width:120, align:'right', editor:{
    																			type:'numberbox',
    																			options:{precision:0,groupSeparator:','},
    																			disabled:true
    																		}
			    ,hidden: true}
		    ]],
		    onClickRow:function(rowIndex){
		        $(this).datagrid('beginEdit', rowIndex);
		    },
		    onBeginEdit:function(rowIndex){
		        var editors = $('#dg_edit').datagrid('getEditors', rowIndex);
		        var n1 = $(editors[0].target);
		        var n2 = $(editors[5].target);
                n1.numberbox({
		            onChange:function(){
		            	var slip_type = $('#cmb_slip_type_edit').combobox('getValue');
		            	if(slip_type == '21'){
		            		if(parseFloat(n2.numberbox('getValue').replace(/,/g,'')) - parseFloat(n1.numberbox('getValue').replace(/,/g,'')) < 0 ){
								$.messager.confirm('Confirm','actual value over',function(r){
									if(r){
										n1.numberbox('setValue',0);
									}
								});
			                }
		            	}
		            }
		        })
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
							STOCK2: row.STOCK,
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
		var ck_sts_approval = "false";

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

		if ($('#ck_sts_approval').attr("checked")) {
			ck_sts_approval = "true";
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
			txt_item_name: $('#txt_item_name').textbox('getValue'),
			ck_sts_approval: ck_sts_approval,
			cmb_sts_approval: $('#cmb_sts_approval').combobox('getValue'),
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
		$('#save_add').linkbutton('enable');
		$('#cancel_add').linkbutton('enable');
		$('#slip_no_add').textbox('setValue','');
		$('#cmb_slip_type_add').combobox('setValue','');
		$('#brand_add').textbox('setValue','');
		$('#qty_brand_add').numberbox('setValue','');
		$('#dg_add').datagrid('loadData',[]);
	}

	function find_brand_add(){
		document.getElementById('s_brand_add').focus();
		$('#dlg_addBrand').dialog('open').dialog('setTitle','Search Item');
		$('#cmb_search_brand_a').combobox('setValue','ITEM_NO');
		$('#dg_addBrand').datagrid('load',{item: ''});
		$('#dg_addBrand').datagrid({
			fitColumns: true,
			columns:[[
                {field:'ITEM_NO',title:'ITEM NO.',width:80,halign:'center', align:'center'},
                {field:'ITEM',title:'ITEM',width:200,halign:'center'},
                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
                {field:'UNIT',title:'UNIT',width:50,halign:'center', align:'right'},
                {field:'LEVEL_NO', hidden:true}
            ]],
            onDblClickRow:function(id,row){
            	var n = row.ITEM_NO+'-'+row.DESCRIPTION+'-'+row.LEVEL_NO;
            	$('#brand_add').textbox('setValue',n);
            	$('#dlg_addBrand').dialog('close');
            }
		});

		$('#dg_addBrand').datagrid('loadData',[]);
	}

	function search_brand_add(){
		var s_item = document.getElementById('s_brand_add').value;

		if(s_item != ''){
			$('#dg_addBrand').datagrid('load',{item: s_item});
			$('#dg_addBrand').datagrid({url: 'do_getBrand.php',});
			document.getElementById('s_brand_add').value = '';
		}
	}

	function select_brand_add(){
		var br = $('#brand_add').textbox('getValue');
		var qt =$('#qty_brand_add').numberbox('getValue');
		if(br =='' && qt==''){
			$.messager.alert('Warning','Item or Qty not Found','warning');
		}else{
			$('#dg_add').datagrid('load',{brand: br, qty: qt});
			$('#dg_add').datagrid({url: 'do_getBrand_BOM.php',});	
		}
	}

	function add_do_add(){
		$('#dlg_addItem').dialog('open').dialog('setTitle','Search Item');
		$('#cmb_search_a').combobox('setValue','ITEM_NO');
		$('#dg_addItem').datagrid('load',{item: '', by: ''});
		$('#dg_addItem').datagrid({
			fitColumns: true,
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
							STOCK2: row.STOCK,
							COST_PROCESS_CODE: row.COST_PROCESS_CODE,
							SLIP_QTY: row.SLIP_QTY,
							WO_NO: "MATERIAL COMMON",
							DATE_CODE: row.DATE_CODE,
							REMARK: row.REMARK
						}
					});
				}
			}
		});

		$('#dg_addItem').datagrid('loadData',[]);
	}

	function search_item_add(){
		var s_by = $('#cmb_search_a').combobox('getValue');
		var s_item = document.getElementById('s_item_add').value;

		if(s_item != ''){
			$('#dg_addItem').datagrid('load',{item: s_item, by: s_by});
			$('#dg_addItem').datagrid({url: 'do_getItem.php',});
			document.getElementById('s_item_add').value = '';
		}
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

		if(slip_no_add == 'UNDEFINED' || slip_date_add == '' || cmb_slip_type_add =='' || cmb_company_add == ''){
			$.messager.alert("warning","Required Field Can't Empty!","Warning");
		}else{
			$('#save_add').linkbutton('disable');
			$('#cancel_add').linkbutton('disable');
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
			$.messager.alert('INFORMATION','Insert Data Success..!!<br/>GR No. '+slip_no_add,'info');
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
		if (row.STS == '0' && row.STS_EDIT == '0'){
			$('#dlg_edit').dialog('open').dialog('setTitle','Edit Materials Transaction ('+row.SLIP_NO+')');
			$('#save_edit').linkbutton('enable');
			$('#cancel_edit').linkbutton('enable');
			$('#slip_no_edit').textbox('setValue',row.SLIP_NO);
			$('#slip_date_edit').datebox('setValue',row.SLIP_DT);
			$('#cmb_slip_type_edit').combobox('setValue',row.SLIP_TYPE);
			$('#cmb_company_edit').combobox('setValue',row.COMPANY_CODE);

			$('#dg_edit').datagrid('loadData',[]);
			$('#dg_edit').datagrid({
				url:'do_getedit.php?slip_no='+row.SLIP_NO
			});
		}else{
			$.messager.show({title: 'Materials Transaction',msg: 'Data is Approved or Data is KANBAN'});
		}
	}

	function add_do_edit(){
		$('#dlg_editItem').dialog('open').dialog('setTitle','Search Item');

		$('#cmb_search_e').combobox('setValue','ITEM_NO');
		$('#dg_editItem').datagrid('load',{item: '', by: ''});
		$('#dg_editItem').datagrid({
			fitColumns: true,
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
							SLIP_QTY: row.SLIP_QTY,
							WO_NO: "MATERIAL COMMON",
							DATE_CODE: row.DATE_CODE,
							REMARK: row.REMARK
						}
					});
				}
			}
		});

		$('#dg_editItem').datagrid('loadData',[]);
	}

	function search_item_edit(){
		var s_by_e = $('#cmb_search_e').combobox('getValue');
		var s_item_e = document.getElementById('s_item_edit').value;

		if(s_item_e != ''){
			$('#dg_editItem').datagrid('load',{item: s_item_e, by: s_by_e});
			$('#dg_editItem').datagrid({url: 'do_getItem.php',});
			document.getElementById('s_item_edit').value = '';
		}
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
			$('#save_edit').linkbutton('disable');
			$('#cancel_edit').linkbutton('disable');
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
					do_lins: $('#dg_edit').datagrid('getData').rows[i].LINE_NO,
					do_item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
					do_qqty: $('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,''),
					do_uomq: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
					do_cost: $('#dg_edit').datagrid('getData').rows[i].COST_PROCESS_CODE,
					do_d_co: $('#dg_edit').datagrid('getData').rows[i].DATE_CODE,
					do_rmrk: $('#dg_edit').datagrid('getData').rows[i].REMARK,
					do_wono: $('#dg_edit').datagrid('getData').rows[i].WO_NO
				}).done(function(res){
					//alert(res);
					//console.log(res);
					$('#dlg_edit').dialog('close');
					$('#dg').datagrid('reload');
				})
			}
			$.messager.alert('INFORMATION','Update Data Success..!!','info');
		}
	}

	function delete_mt(){
		var row = $('#dg').datagrid('getSelected');
        if (row.STS == '0'){
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
        }else{
        	$.messager.show({title: 'Materials Transaction',msg: 'Data is Approved'});
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

	function print_mt(){
		var row = $('#dg').datagrid('getSelected');
		if(row){
			pdf_url = "?do="+row.SLIP_NO
			window.open('do_print.php'+pdf_url);
		}else{
			$.messager.show({title: 'Material Transaction',msg: 'Data Not select'});
		}
	}
</script>
</body>
</html>