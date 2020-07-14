<?php
include("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
if ($varConn=='Y'){

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SHIPPING PLAN NEW</title>
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
	<fieldset style="margin-left;border-radius:4px;height:70px;width:90%"><legend><span class="style3"><strong> SHIPPING PLAN FILTER </strong></span></legend>
		<div style="width:375px;float:left">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PPBE NO.</span>
				<select style="width:120px;" name="cmb_ppbe" id="cmb_ppbe" class="easyui-combobox" 
					data-options=" url:'json/json_ppbe_no.php?user=<? echo $user_name; ?>',method:'get',valueField:'ppbe_no',textField:'ppbe_no', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_ppbe" id="ck_ppbe" checked="true">ALL</input></label>
			</div>		
		     <div class="fitem">
				<span style="width:80px;display:inline-block;">SI NO.</span>
				<select style="width:200px;" name="cmb_si_no" id="cmb_si_no" class="easyui-combobox" 
					data-options=" url:'json/json_si_no.php',method:'get',valueField:'si_no',textField:'si_no', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_si" id="ck_si" checked="true">ALL</input></label>
			</div>
		</div>
		<div style="width:450px;float:left">
			<div class="fitem">
				<span style="width:150px;display:inline-block;">CARGO READY DATE</span>
				<input style="width:100px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			  		TO
				<input style="width:100px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				<label><input type="checkbox" name="ck_cr_date" id="ck_cr_date" checked="true">ALL</input></label>
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">WO NO.</span>
				<select style="width:223px;" name="cmb_wo_no" id="cmb_wo_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'work_order',textField:'work_order', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_wo_no" id="ck_wo_no" checked="true">ALL</input></label>
			</div>
		</div>
		<div style="width:450px; margin-left: 825px;">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PO NO.</span>
				<select style="width:250px;" name="cmb_po_no" id="cmb_po_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'po_no',textField:'po_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_po_no" id="ck_po_no" checked="true">ALL</input></label>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">ITEM NO.</span>
				<select style="width:250px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'item_no',textField:'item_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">ALL</input></label>
			</div>
		</div>
	</fieldset>
	<div style="padding:5px 6px;">
		<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="filterData()" style="width:150px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</a>
		<a href="javascript:void(0)" id="add_shipping_plan" class="easyui-linkbutton c2" onClick="addShippingPlan()" style="width:150px;"><i class="fa fa-plus" aria-hidden="true"></i> ADD SHIPPING PLAN</a>
		<a href="javascript:void(0)" id="add_shipping_plan" class="easyui-linkbutton c2" onClick="editShippingPlan()" style="width:150px;"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT SHIPPING PLAN</a>
		<a href="javascript:void(0)" id="add_shipping_plan" class="easyui-linkbutton c2" onClick="deleteShippingPlan()" style="width:160px;"><i class="fa fa-trash" aria-hidden="true"></i> DELETE SHIPPING PLAN</a>
		<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="link_to_beakdown()" style="width:100px;"><i class="fa fa-eye" aria-hidden="true"></i> VIEW BDC</a>
		<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="link_to_packinglist()" style="width:150px;"><i class="fa fa-eye" aria-hidden="true"></i> VIEW PACKING LIST</a>
		<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="print_si()" style="width:170px;">
			<i class="fa fa-print" aria-hidden="true"></i> PRINT SI TO FORWARDER
		</a>
		<label><input type="checkbox" name="ck_mark" id="ck_mark" checked="true">PRINT SI MARKING</input></label>
	</div>
</div>

<table id="dg" title="SHIPPING PLAN ENTRY NEW" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true">	
</table>

<!-- START ADD -->
<div id='win_add' class="easyui-window" style="width:1250px;height:auto;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
	<form id="f_add" method="post" novalidate>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left; margin:5px;">
			<div class="fitem">
				<span style="width:70px;display:inline-block;">CUSTOMER</span>
				<input style="width: 391px; " name="cust_add" id="cust_add" class="easyui-combobox" data-options=" url:'json/json_master_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
				onSelect: function(rec){
					$('#sino_add').combogrid({
						url: 'shipping_plan2_get_si_no.php?id='+rec.company_code
					});
				}"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">EX Fact</span>
				<input style="width:100px;" name="exfact_date_add" id="exfact_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<span style="width:30px;display:inline-block;"></span>
				<span style="width: 30px;display:inline-block;">ETD</span>
				<input style="width:100px;" name="etd_date_add" id="etd_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<span style="width:30px;display:inline-block;"></span>
				<span style="width:30px;display:inline-block;">ETA</span>
				<input style="width:100px;" name="eta_date_add" id="eta_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
			</div>
			<div class="fitem">
				<span style="width:70px;display:inline-block;">PPBE No.</span>
				<input style="width:200px;" name="ppbe_add" id="ppbe_add" class="easyui-textbox" disabled="" />
				<a href="javascript:void(0)" onclick="sett_ppbe('add')">SET</a>
				<span style="width:215px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">VESSEL</span>
				<input style="width:445px;" name="vessel_add" id="vessel_add" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:70px;display:inline-block;">SI No.</span>
				<select style="width:200px;" name="sino_add" id="sino_add" class="easyui-combogrid" style="width:200px;" disabled=""></select>
				<a href="javascript:void(0)" class="easyui-linkbutton c2" data-options="iconCls:'icon-add'"  onclick="SI_entry()" style="width:185px"> CREATE SI </a>
				<span style="width:53px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">REMARK</span>
				<input style="width:445px;" name="remark_add" id="remark_add" class="easyui-textbox"/>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:300px;border-radius: 10px;margin:5px;float: left;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
		<div id="toolbar_add" style="padding: 5px 5px;">
			<a href="javascript:void(0)" id="add_so_part_add" iconCls='icon-add' class="easyui-linkbutton" onclick="sett_part_SO('ADD')" disabled="">ADD ITEM</a>
			<a href="javascript:void(0)" id="partial_add" iconCls='icon-reload' class="easyui-linkbutton" onclick="parsial_part_SO('ADD')" disabled="">PARTIAL ITEM</a>
			<a href="javascript:void(0)" id="remove_add" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_part_SO('ADD')" disabled="">REMOVE ITEM</a>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c2" id="savebtn" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveShipPlan('ADD')" style="width:140px" > SAVE </a>
            <a class="easyui-linkbutton c2" id="clsbtn" href="javascript:void(0)" onclick="javascript:$('#win_add').window('close')" style="width:140px"><i class="fa fa-close" aria-hidden="true"></i> CANCEL </a>
        </div>
	</form>
</div>
<!-- END ADD -->

<!-- START EDIT -->
<div id='win_edit' class="easyui-window" style="width:1250px;height:auto;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
	<form id="f_edit" method="post" novalidate>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left; margin:5px;">
			<div class="fitem">
				<span style="width:70px;display:inline-block;">CUSTOMER</span>
				<input style="width: 391px; " name="cust_edit" id="cust_edit" class="easyui-combobox" data-options=" url:'json/json_master_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
				onSelect: function(rec){
					$('#sino_add').combogrid({
						url: 'shipping_plan2_get_si_no.php?id='+rec.company_code
					});
				}"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">EX Fact</span>
				<input style="width:100px;" name="exfact_date_edit" id="exfact_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<span style="width:30px;display:inline-block;"></span>
				<span style="width: 30px;display:inline-block;">ETD</span>
				<input style="width:100px;" name="etd_date_edit" id="etd_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<span style="width:30px;display:inline-block;"></span>
				<span style="width:30px;display:inline-block;">ETA</span>
				<input style="width:100px;" name="eta_date_edit" id="eta_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
			</div>
			<div class="fitem">
				<span style="width:70px;display:inline-block;">PPBE No.</span>
				<input style="width:200px;" name="ppbe_edit" id="ppbe_edit" class="easyui-textbox" disabled="" />
				<!-- <a href="javascript:void(0)" onclick="sett_ppbe('edit')">SET</a> -->
				<span style="width:240px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">VESSEL</span>
				<input style="width:445px;" name="vessel_edit" id="vessel_edit" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:70px;display:inline-block;">SI No.</span>
				<select style="width:200px;" name="sino_edit" id="sino_edit" class="easyui-combogrid" style="width:200px;" disabled=""></select>
				<!-- <a href="javascript:void(0)" class="easyui-linkbutton c2" data-options="iconCls:'icon-add'"  onclick="SI_entry()" style="width:185px"> CREATE SI </a> -->
				<span style="width:240px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">REMARK</span>
				<input style="width:445px;" name="remark_edit" id="remark_edit" class="easyui-textbox"/>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:100%;height:300px;border-radius: 10px;margin:5px;float: left;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
		<div id="toolbar_edit" style="padding: 5px 5px;">
			<a href="javascript:void(0)" id="add_so_part_edit" iconCls='icon-add' class="easyui-linkbutton" onclick="sett_part_SO('EDIT')">ADD ITEM</a>
			<a href="javascript:void(0)" id="remove_so_part_edit" iconCls='icon-reload' class="easyui-linkbutton" onclick="parsial_part_SO('EDIT')">PARTIAL ITEM</a>
			<a href="javascript:void(0)" id="remove_so_part_edit" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_part_SO('EDIT')">REMOVE ITEM</a>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c2" id="savebtn" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveShipPlan('EDIT')" style="width:140px" > SAVE </a>
            <!-- <a class="easyui-linkbutton c2" id="packbtn" href="javascript:void(0)" onclick="openPacking()" style="width:140px" disabled="true"><i class="fa fa-cubes" aria-hidden="true"></i> PACKING </a> -->
            <a class="easyui-linkbutton c2" id="clsbtn" href="javascript:void(0)" onclick="javascript:$('#win_edit').window('close')" style="width:140px"><i class="fa fa-close" aria-hidden="true"></i> Cancel </a>
        </div>
	</form>
</div>
<!-- END EDIT -->

<!-- SETT PART SO -->
<div id="dlg_part_SO" class="easyui-dialog" style="width: 1200px;height: 400px;" closed="true" closable="false" data-options="modal:true">
	<table id="dg_part_SO" class="easyui-datagrid" style="width:100%;height:90%;border-radius: 10px;"></table>
	<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
		<span style="color: red; font-size: 8px;">*): double click to select</span>
		<a href="javascript:void(0)" class="easyui-linkbutton c2"  onclick="javascript:$('#dlg_part_SO').dialog('close')" style="width:140px"><i class="fa fa-close" aria-hidden="true"></i> CANCEL </a>
	</div>
</div>
<!-- END PART SO -->

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
		$('#date_awal').datebox('disable');
		$('#date_akhir').datebox('disable');
		$('#ck_cr_date').change(function(){
			if ($(this).is(':checked')) {
				$('#date_awal').datebox('disable');
				$('#date_akhir').datebox('disable');
			}else{
				$('#date_awal').datebox('enable');
				$('#date_akhir').datebox('enable');
			}
		});

		$('#cmb_wo_no').combobox('disable');
		$('#ck_wo_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_wo_no').combobox('disable');
			}else{
				$('#cmb_wo_no').combobox('enable');
			}
		});

		$('#cmb_si_no').combobox('disable');
		$('#ck_si').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_si_no').combobox('disable');
			}else{
				$('#cmb_si_no').combobox('enable');
			}
		});

		$('#cmb_po_no').combobox('disable');
		$('#ck_po_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_po_no').combobox('disable');
			}else{
				$('#cmb_po_no').combobox('enable');
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

		$('#cmb_ppbe').combobox('disable');
		$('#ck_ppbe').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_ppbe').combobox('disable');
			}else{
				$('#cmb_ppbe').combobox('enable');
			}
		});
	});

	function filterData(){
		var ck_cr_date = "false";
		var ck_po_no = "false";
		var ck_wo_no = "false";
		var ck_item_no = "false";
		var ck_si = "false";
		var ck_ppbe = "false";

		if ($('#ck_cr_date').attr("checked")) {
			ck_cr_date = "true";
		};

		if ($('#ck_po_no').attr("checked")) {
			ck_po_no = "true";
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
		};

		if ($('#ck_wo_no').attr("checked")) {
			ck_wo_no = "true";
		};

		if ($('#ck_si').attr("checked")) {
			ck_si = "true";
		};

		if ($('#ck_ppbe').attr("checked")) {
			ck_ppbe = "true";
		};		

		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			ck_cr_date: ck_cr_date,
			cmb_wo_no : $('#cmb_wo_no').combobox('getValue'),
			ck_wo_no: ck_wo_no,
			cmb_po_no : $('#cmb_po_no').combobox('getValue'),
			ck_po_no: ck_po_no,
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no,
			cmb_ppbe: $('#cmb_ppbe').combobox('getValue'),
			ck_ppbe: ck_ppbe,
			ck_si: ck_si,
			cmb_si_no : $('#cmb_si_no').combobox('getValue')
		});

		$('#dg').datagrid( {
			url: 'shipping_plan2_get.php',
			singleSelect:true,
			columns:[[
			    {field:'CUSTOMER_CODE', hidden: true},
			    {field:'CRS_REMARK', title:'PPBE NO', halign:'center', width:70},
			    {field:'SI_NO', title:'SI NO.', halign:'center', width:90},
                // {field:'CR_DATE', title:'CR DATE', halign:'center', align:'center', width:70},
                {field:'ETD', title:'ETD', halign:'center', align:'center', width:70},
                {field:'ETA', title:'ETA', halign:'center', align:'center', width:70},
                {field:'STUFFY_DATE', title:'EX FACTORY', halign:'center', align:'center', width:70},
                {field:'VESSEL', title:'VESSEL', halign:'center', width:100},
                {field:'REMARK', title:'REMARK', halign:'center', align:'center', width:100},
                {field:'CONSIGNEE_NAME', title:'CONSIGNEE', halign:'center', width:150},
                {field:'FORWARDER_NAME', title:'FORWARDER', halign:'center', width:150},
                {field:'EMKL_NAME', title:'EMKL', halign:'center', width:150},
			]],
			view: detailview,
			detailFormatter: function(rowIndex, rowData){
				return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
			},
			onExpandRow: function(index,row){
				var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
				listbrg.datagrid({
                	title: 'Shipping Plan Detail (PPBE No: '+row.CRS_REMARK+')',
                	url:'shipping_plan2_getdetail.php?ppbe='+row.CRS_REMARK+'&si='+row.SI_NO,
					toolbar: '#ddv'+index,
					singleSelect:true,
					loadMsg:'load data ...',
					height:'auto',
					fitColumns: true,
					columns:[[
						{field:'COMPANY', title:'COMPANY', halign:'center', align:'center', width:100},
						{field:'CUSTOMER_PO_NO', title:'PO CUSTOMER', halign:'center', align:'center', width:80},
						{field:'SO_NO', title:'SO NO.', halign:'center', align:'center', width:50},
						{field:'SO_LINE_NO',title:'SO LINE<br>NO.',width:50,halign:'center', align:'center'},
		                {field:'ITEM_NO',title:'ITEM NO.', halign:'center', align:'center', width:50},
		                {field:'DESCRIPTION', title:'DESCRIPTION', halign:'center', width:200},
		                {field:'WORK_NO', title:'WORK ORDER', halign:'center', width:130},
		                {field:'CR_DATE', title:'CR DATE', halign:'center', align:'center', width:70},
		                {field:'QTY', title:'ORDER<br>QTY', halign:'center', align:'right', width:50},
		                {field:'QTY_PRODUKSI', title:'AVAILABLE<br>QTY', halign:'center', align:'right', width:50},
		                {field:'QTY_PLAN', title:'PLANNED<br>QTY', halign:'center', align:'right', width:50},
		                {field:'QTY_INVOICED', title:'INVOICED<br>QTY', halign:'center', align:'right', width:50}
					]],
					onResize:function(){
						$('#dg').datagrid('fixDetailRowHeight',index);
					},
					onLoadSuccess:function(){
						setTimeout(function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},0);
					}
                });
			}
		});

		$('#dg').datagrid('enableFilter');
	}

	function default_set(value){
		if(value == 'add') {
			$('#cust_add').combobox('setValue','');
			$('#ppbe_add').textbox('setValue','');
			$('#ppbe_add').textbox('disable');
			$('#sino_add').combogrid('disable');	
		}else{
			$('#cust_edit').combobox('disable');
			$('#ppbe_add').textbox('disable');
			$('#sino_add').combogrid('disable');
		}
	}

	function addShippingPlan(){
		$('#win_add').window('open').window('setTitle','ADD SHIPPING PLAN');
		$('#win_add').window('center');
		$('#f_add').form('reset');
		default_set('add');
		$('#dg_add').datagrid('loadData',[]);
		$('#dg_add').datagrid({
			columns:[[
				{field:'STS', title:'PLAN ID', width: 50, halign: 'center'},
				{field:'ITEM_NO', title:'ITEM NO.', width: 50, halign: 'center'},
				{field:'ITEM_NAME', title:'ITEM NAME', width: 200, halign: 'center'},
				{field:'WORK_ORDER', title:'WORK ORDER', width: 150, halign: 'center'},
				{field:'PO_NO', title:'PO NO.', width: 100, halign: 'center'},
				{field:'PO_LINE_NO', title:'LINE NO.', width: 70, halign: 'center', align: 'center'},
				{field:'CR_DATE', title:'CR DATE', width: 80, halign: 'center', align: 'center'},
				{field:'QTY_ORDER', title:'ORDER<br>QTY', width: 80, halign: 'center', align: 'right'},
				{field:'QTY_PLAN', title:'PLAN<br>QTY', width:80, halign:'center', align:'right', editor:{type:'numberbox',
																								   options:{precision:0,groupSeparator:',',disable:true}
																				   				  }
				},
				{field:'SO_NO', hidden: 'true'},
				{field:'LINE_NO', hidden: 'true'},
				{field:'CURR_CODE', hidden: 'true'},
				{field:'U_PRICE', hidden: 'true'}
			]],
			onClickRow:function(rowIndex){
		    	$(this).datagrid('beginEdit', rowIndex);
		    }
		});
	}

	function link_to_beakdown(){
		window.location.href = 'breakdown_container.php?id=701';
	}

	function link_to_packinglist(){
		window.location.href = 'packing_list.php';
	}

	function print_si(){
		var ck_mark = "false";
		var flag = 0;

		if ($('#ck_mark').attr("checked")) {
			ck_mark = "true";
		};

		var arrSI = [];
    	var r = $('#dg').datagrid('getSelections');

		for(p=0;p<r.length;p++){
			arrSI.push(r[p].SI_NO+"-"+r[p].CRS_REMARK);
		}
		var SIexp = arrSI[0].split("-");

		if(SIexp[0] == null || SIexp[1] == null){
			$.messager.alert("Data not palnned");
		}else{
			window.open('invoice_print_si.php?si='+SIexp[0]+'&do='+SIexp[1]+'&si_sts=si&print_mark='+ck_mark+'');
		}
	}

	// --------------------------- DIALOG ADD [START] ---------------------------------------

	function SI_entry(){
		var session = '<?php echo $user_name ?>';
		window.open('http://sysfi01.indonesia.fdk.co.jp/pglosas/entry/si/si_entry1.asp?KEYWORD='+session);
	}

	function sett_ppbe(value){
		$.ajax({
			type: 'GET',
			url: 'json/json_kode_ppbe_to_shipping.php?user=<? echo $user_name; ?>',
			data: { kode:'kode' },
			success: function(data){
				if (value == 'add'){
					$('#ppbe_add').textbox('enable');
					$('#ppbe_add').textbox('setValue',data[0].kode);
					$('#sino_add').combogrid('enable');	
				}else{
					$('#ppbe_edit').textbox('enable');
					$('#ppbe_edit').textbox('setValue',data[0].kode);
					$('#ppbe_edit').combogrid('enable');
				}
			}
		});
	}

	$('#sino_add').combogrid({
		panelWidth:800,
        idField:'SI_NO',
        textField:'SI_NO',
        fitColumns: true,
        columns:[[
            {field:'SI_NO', title:'SI NO.', halign:'center', width:120},
            {field:'CONSIGNEE_NAME', title:'CONSIGNEE', halign:'center', width:200},
            {field:'FORWARDER_NAME', title:'FORWARDER', halign:'center', width:250},
            {field:'EMKL_NAME', title:'EMKL', halign:'center', width:200}
        ]],
	    onClickRow: function(rec){
	    	var g = $('#sino_add').combogrid('grid');
			var r = g.datagrid('getSelected');

	    	if (r) {
		    	$('#sino_add').combogrid('setValue', r.SI_NO);
		    	$('#add_so_part_add').linkbutton('enable');
				$('#partial_add').linkbutton('enable');
				$('#remove_add').linkbutton('enable');
	    	};
	    }
	});

	function sett_part_SO(value){
		$('#dlg_part_SO').dialog('open').dialog('setTitle', value+' ITEM SHIPPING');
		console.log('shipping_plan2_getItem.php?cust='+$('#cust_add').combobox('getValue')+'&si='+$('#sino_add').combogrid('getValue'));
		
		if(value == 'ADD'){
			$('#dg_part_SO').datagrid({
				url: 'shipping_plan2_getItem.php?si='+$('#sino_add').combogrid('getValue'),
				rownumbers: true,
				fitColumns: true,
				columns:[[
					{field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM_NAME',title:'DESCRIPTION',width:200,halign:'center'},
	                {field:'WORK_ORDER',title:'WORK ORDER',width:150,halign:'center'},
	                {field:'PO_NO',title:'PO NO.',width:80,halign:'center', align:'center'},
	                {field:'PO_LINE_NO',title:'LINE NO.',width:80,halign:'center', align: 'center'},
	                {field:'CR_DATE',title:'CR DATE',width:80,halign:'center', align: 'center'},
	                {field:'QTY_ORDER',title:'ORDER QTY',width:80,halign:'center', align:'right'},
	                {field:'SO_NO', hidden: 'true'},
	                {field:'LINE_NO', hidden: 'true'},
					{field:'CURR_CODE', hidden: 'true'},
					{field:'U_PRICE', hidden: 'true'}
	            ]],
	            onDblClickRow:function(id,row){
	            	var t = $('#dg_add').datagrid('getRows');var total = t.length;
	            	var idxfield = 0;var count = 0;

	            	if(parseInt(total)==0){
	            		idxfield = total;
	            	}else{
	            		idxfield = total;
	            		for(i=0; i<total; i++){
	            			var wo_no = $('#dg_add').datagrid('getData').rows[i].WORK_ORDER;
	            			if(wo_no == row.WORK_ORDER){
	            				count++;
	            			}
	            		}
	            	}

	            	if (count>0) {
						$.messager.alert('Warning','data already exists','warning');	
					}else{
						var ans = "'"+row.ANSWER_NO+"'";
						var n = "'"+value+"'";
						$('#dg_add').datagrid('insertRow',{
							index: idxfield,
							row: {
								ITEM_NO: row.ITEM_NO,
								ITEM_NAME: row.ITEM_NAME,
								WORK_ORDER: row.WORK_ORDER,
								PO_NO: row.PO_NO,
								PO_LINE_NO: row.PO_LINE_NO,
								CR_DATE: row.CR_DATE,
								QTY_ORDER: row.QTY_ORDER,
								QTY_PLAN: row.QTY_ORDER,
								SO_NO: row.SO_NO,
								LINE_NO: row.LINE_NO,
								CURR_CODE: row.CURR_CODE,
								U_PRICE: row.U_PRICE,
								STS: 'NEW'
							}
						});
					}
				}
			});
		}else{
			$('#dg_part_SO').datagrid({
				url: 'shipping_plan2_getItem.php?si='+$('#sino_edit').combogrid('getValue'),
				rownumbers: true,
				fitColumns: true,
				columns:[[
					{field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM_NAME',title:'DESCRIPTION',width:200,halign:'center'},
	                {field:'WORK_ORDER',title:'WORK ORDER',width:150,halign:'center'},
	                {field:'PO_NO',title:'PO NO.',width:80,halign:'center', align:'center'},
	                {field:'PO_LINE_NO',title:'LINE NO.',width:80,halign:'center', align: 'center'},
	                {field:'CR_DATE',title:'CR DATE',width:80,halign:'center', align: 'center'},
	                {field:'QTY_ORDER',title:'ORDER QTY',width:80,halign:'center', align:'right'},
	                {field:'SO_NO', hidden: 'true'},
	                {field:'LINE_NO', hidden: 'true'},
					{field:'CURR_CODE', hidden: 'true'},
					{field:'U_PRICE', hidden: 'true'}
	            ]],
	            onDblClickRow:function(id,row){
	            	var t = $('#dg_edit').datagrid('getRows');var total = t.length;
	            	var idxfield = 0;var count = 0;

	            	if(parseInt(total)==0){
	            		idxfield = total;
	            	}else{
	            		idxfield = total;
	            		for(i=0; i<total; i++){
	            			var wo_no = $('#dg_edit').datagrid('getData').rows[i].WORK_ORDER;
	            			if(wo_no == row.WORK_ORDER){
	            				count++;
	            			}
	            		}
	            	}

	            	if (count>0) {
						$.messager.alert('Warning','data already exists','warning');
						
					}else{
						var ans = "'"+row.ANSWER_NO+"'";
						var n = "'"+value+"'";
						$('#dg_edit').datagrid('insertRow',{
							index: idxfield,
							row: {
								ITEM_NO: row.ITEM_NO,
								ITEM_NAME: row.ITEM_NAME,
								WORK_ORDER: row.WORK_ORDER,
								PO_NO: row.PO_NO,
								PO_LINE_NO: row.PO_LINE_NO,
								CR_DATE: row.CR_DATE,
								QTY_ORDER: row.QTY_ORDER,
								QTY_PLAN: row.QTY_ORDER,
								SO_NO: row.SO_NO,
								LINE_NO: row.LINE_NO,
								CURR_CODE: row.CURR_CODE,
								U_PRICE: row.U_PRICE,
								STS: 'NEW'
							}
						});
					}
				}
			});
		}


		
		$('#dg_part_SO').datagrid('enableFilter');
	}

	function parsial_part_SO(value){
		if (value == 'ADD'){
			var row = $('#dg_add').datagrid('getSelected');
			if(row){
				//alert(row.PRF_NOMOR);
				var indx = $('#dg_add').datagrid('getRowIndex',row);
				var idx = $('#dg_add').datagrid('getRowIndex', row)+1;
				var stngah = parseFloat(row.QTY_PLAN.replace(/,/g,''))/2;
				$('#dg_add').datagrid('insertRow',{
					index: idx,
					row: {
						ITEM_NO: row.ITEM_NO,
						ITEM_NAME: row.ITEM_NAME,
						WORK_ORDER: row.WORK_ORDER,
						PO_NO: row.PO_NO,
						PO_LINE_NO: row.PO_LINE_NO,
						CR_DATE: row.CR_DATE,
						QTY_ORDER: row.QTY_ORDER,
						QTY_PLAN: stngah
					}
				});

				var ed = $('#dg_add').datagrid('getEditor', {index: indx,field:'QTY_PLAN'});
				$(ed.target).numberbox('setValue', stngah);
			}
		}else{
			var row = $('#dg_edit').datagrid('getSelected');
			if(row){
				//alert(row.PRF_NOMOR);
				var indx = $('#dg_edit').datagrid('getRowIndex',row);
				var idx = $('#dg_edit').datagrid('getRowIndex', row)+1;
				var stngah = parseFloat(row.QTY_PLAN.replace(/,/g,''))/2;
				$('#dg_edit').datagrid('insertRow',{
					index: idx,
					row: {
						ITEM_NO: row.ITEM_NO,
						ITEM_NAME: row.ITEM_NAME,
						WORK_ORDER: row.WORK_ORDER,
						PO_NO: row.PO_NO,
						PO_LINE_NO: row.PO_LINE_NO,
						CR_DATE: row.CR_DATE,
						QTY_ORDER: row.QTY_ORDER,
						QTY_PLAN: stngah,
						STS: row.STS
					}
				});

				var ed = $('#dg_add').datagrid('getEditor', {index: indx,field:'QTY_PLAN'});
				$(ed.target).numberbox('setValue', stngah);
			}
		}
	}

	function remove_part_SO(value){
		if (value == 'ADD'){
			var row = $('#dg_add').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						var idx = $("#dg_add").datagrid("getRowIndex", row);
						$('#dg_add').datagrid('deleteRow', idx);
					}	
				});
			}
		}else{
			var row = $('#dg_edit').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						if (row.STS == 'NEW'){
							var idx = $("#dg_edit").datagrid("getRowIndex", row);
							$('#dg_edit').datagrid('deleteRow', idx);
						}else{
							$.post('shipping_plan2_detail_destroy.php',{answer_no: row.STS},function(result){
								if (result.successMsg){
									$.messager.progress('close');
			                        $('#dg_edit').datagrid('reload');
			                    }else{
			                    	$.messager.progress('close');
			                        $.messager.show({
			                            title: 'Error',
			                            msg: result.errorMsg
			                        });
			                    }
							},'json');
						}
					}	
				});
			}
		}
	}

	function simpan(){
		$.messager.progress({
            msg:'save data...'
        });

		var dataRows = [];
		var t = $('#dg_add').datagrid('getRows');
		var ppbe = $('#ppbe_add').textbox('getValue');
		var total = t.length;
		var flag = 0;

		// console.log('Total: '+total);

		for(i=0;i<total;i++){
			$('#dg_add').datagrid('endEdit',i);
			if( ($('#dg_add').datagrid('getData').rows[i].QTY_PLAN == undefined || 
				 $('#dg_add').datagrid('getData').rows[i].QTY_PLAN == '0' || 
				 $('#dg_add').datagrid('getData').rows[i].QTY_PLAN == ''
			    )
			) {
				flag = flag + 1;
				$.messager.alert("WARNING","Data Qty Plan not Found","warning");
			}
		}

		if ($('#exfact_date_add').datebox('getValue') == undefined || $('#exfact_date_add').datebox('getValue') == '0' || $('#exfact_date_add').datebox('getValue') == '') {
			flag = flag + 1;
			$.messager.alert("WARNING","Data ex-fact date not completed","warning");
		} 

		if($('#etd_date_add').datebox('getValue') == undefined || $('#etd_date_add').datebox('getValue') == '0' || $('#etd_date_add').datebox('getValue') == '') {
			flag = flag + 1;
			$.messager.alert("WARNING","Data ETD date not completed","warning");
		}

		if($('#eta_date_add').datebox('getValue') == undefined || $('#eta_date_add').datebox('getValue') == '0' || $('#eta_date_add').datebox('getValue') == '') {
			flag = flag + 1;
			$.messager.alert("WARNING","Data ETA date not completed","warning");
		}

		if($('#vessel_add').textbox('getValue') == undefined || $('#vessel_add').textbox('getValue') == '0' || $('#vessel_add').textbox('getValue') == ''){
			flag = flag + 1;
			$.messager.alert("WARNING","Data Vessel not completed","warning");
		}

		if($('#remark_add').textbox('getValue') == undefined || $('#remark_add').textbox('getValue') == '0' || $('#remark_add').textbox('getValue') == ''){
			flag = flag + 1;
			$.messager.alert("WARNING","Data remark not completed","warning");
		}

		// console.log('FLAG: '+flag);

		if (flag > 0) {
			$.messager.progress('close');
		}else{
			$.messager.alert("INFORMATION","Data completed","info");
			
			for(i=0;i<total;i++){
				dataRows.push({
					WORK_ORDER: $('#dg_add').datagrid('getData').rows[i].WORK_ORDER,
					ITEM_NO: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
					ITEM_NAME: $('#dg_add').datagrid('getData').rows[i].ITEM_NAME,
					SI_NO: $('#sino_add').combogrid('getValue'),
					QUANTITY: $('#dg_add').datagrid('getData').rows[i].QTY_ORDER,
					CR_DATE: $('#dg_add').datagrid('getData').rows[i].CR_DATE,
					ETA_DATE: $('#eta_date_add').datebox('getValue'),
					EX_FAC_DATE: $('#exfact_date_add').datebox('getValue'),
					ETD_DATE: $('#etd_date_add').datebox('getValue'),
					SO_NO: $('#dg_add').datagrid('getData').rows[i].SO_NO,					
					PO_NO: $('#dg_add').datagrid('getData').rows[i].PO_NO,
					PO_LINE_NO: $('#dg_add').datagrid('getData').rows[i].PO_LINE_NO,
					VESSEL: $('#vessel_add').textbox('getValue'),
					LINE_NO: $('#dg_add').datagrid('getData').rows[i].LINE_NO,
					CUST_CODE: $('#cust_add').combobox('getValue'),
					QTY_ORDER: $('#dg_add').datagrid('getData').rows[i].QTY_PLAN.replace(/,/g,''),
					CR_REMARK: $('#remark_add').textbox('getValue'),
					CURR_CODE:$('#dg_add').datagrid('getData').rows[i].CURR_CODE,
					U_PRICE: $('#dg_add').datagrid('getData').rows[i].U_PRICE,
					PPBE: $('#ppbe_add').textbox('getValue')
				});
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			console.log(unescape(str_unescape));

			$.post('shipping_plan2_save.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#win_add').window('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>PPBE No. : '+$('#ppbe_add').textbox('getValue'),'info');
					$.messager.progress('close');
				}else{
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}
	}

	function saveShipPlan(value) {
		if (value == 'ADD'){
			var ppbe = $('#ppbe_add').textbox('getValue');
			$.ajax({
				type: 'GET',
				url: 'json/json_cek_kode_ppbe.php?ppbe='+ppbe,
				data: { kode:'kode' },
				success: function(data){
					if(data[0].kode!='SUCCESS'){
						$.messager.alert('Warning',data[0].kode,'warning');
					}
					simpan();
				}
			});	
		}else{
			var ppbe = $('#ppbe_edit').textbox('getValue');
			$.ajax({
				type: 'GET',
				url: 'json/json_cek_kode_ppbe.php?ppbe='+ppbe,
				data: { kode:'kode' },
				success: function(data){
					if(data[0].kode!='SUCCESS'){
						$.messager.alert('Warning',data[0].kode,'warning');
					}
					simpan_edit();
				}
			});
		}
	}

	// --------------------------- DIALOG ADD [FINISH] ---------------------------------------

	// --------------------------- DIALOG EDIT [START] ---------------------------------------
	function editShippingPlan(){
		var row = $('#dg').datagrid('getSelected');
		if (row) {
			$('#win_edit').window('open').window('setTitle','EDIT SHIPPING PLAN');
			$('#win_edit').window('center');
			$('#f_edit').form('reset');
			default_set('edit');
			$('#cust_edit').combobox('setValue', row.CUSTOMER_CODE);
			$('#ppbe_edit').textbox('setValue', row.CRS_REMARK);
			$('#sino_edit').combogrid('setValue', row.SI_NO);
			$('#exfact_date_edit').datebox('setValue', row.STUFFY_DATE);
			$('#etd_date_edit').datebox('setValue', row.ETD);
			$('#eta_date_edit').datebox('setValue', row.ETA);
			$('#vessel_edit').textbox('setValue', row.VESSEL);
			$('#remark_edit').textbox('setValue', row.REMARK);

			$('#dg_edit').datagrid({
				url:'shipping_plan2_get_detail_edit.php?ppbe='+row.CRS_REMARK+'&si='+row.SI_NO,
			    singleSelect: true,
				columns:[[
					{field:'STS', title:'PLAN ID', width: 50, halign: 'center'},
					{field:'ITEM_NO', title:'ITEM NO.', width: 50, halign: 'center'},
					{field:'ITEM_NAME', title:'ITEM NAME', width: 200, halign: 'center'},
					{field:'WORK_ORDER', title:'WORK ORDER', width: 150, halign: 'center'},
					{field:'PO_NO', title:'PO NO.', width: 100, halign: 'center'},
					{field:'PO_LINE_NO', title:'LINE NO.', width: 70, halign: 'center', align: 'center'},
					{field:'CR_DATE', title:'CR DATE', width: 80, halign: 'center', align: 'center'},
					{field:'QTY_ORDER', title:'ORDER<br>QTY', width: 80, halign: 'center', align: 'right'},
					{field:'QTY_PLAN', title:'PLAN<br>QTY', width:80, halign:'center', align:'right', editor:{type:'numberbox',
																									   options:{precision:0,groupSeparator:',',disable:true}
																					   				  }
					},
					{field:'SO_NO', hidden: 'true'},
					{field:'LINE_NO', hidden: 'true'},
					{field:'CURR_CODE', hidden: 'true'},
					{field:'U_PRICE', hidden: 'true'}
				]],
				onClickRow:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    }
			});

		}else{
			$.messager.show({
                title: 'EDIT SHIPPING PLAN',
                msg: "DATA NOT SELECTED"
            });
		}
	}

	function simpan_edit(){
		$.messager.progress({
            msg:'save data...'
        });

		var dataRows = [];
		var t = $('#dg_edit').datagrid('getRows');
		var ppbe = $('#ppbe_edit').textbox('getValue');
		var total = t.length;
		var flag = 0;

		// console.log('Total: '+total);

		for(i=0;i<total;i++){
			$('#dg_edit').datagrid('endEdit',i);
			if( ($('#dg_edit').datagrid('getData').rows[i].QTY_PLAN == undefined || 
				 $('#dg_edit').datagrid('getData').rows[i].QTY_PLAN == '0' || 
				 $('#dg_edit').datagrid('getData').rows[i].QTY_PLAN == ''
			    )
			) {
				flag = flag + 1;
				$.messager.alert("WARNING","Data Qty Plan not Found","warning");
			}
		}

		if ($('#exfact_date_edit').datebox('getValue') == undefined || $('#exfact_date_edit').datebox('getValue') == '0' || $('#exfact_date_edit').datebox('getValue') == '') {
			flag = flag + 1;
			$.messager.alert("WARNING","Data ex-fact date not completed","warning");
		} 

		if($('#etd_date_edit').datebox('getValue') == undefined || $('#etd_date_edit').datebox('getValue') == '0' || $('#etd_date_edit').datebox('getValue') == '') {
			flag = flag + 1;
			$.messager.alert("WARNING","Data ETD date not completed","warning");
		}

		if($('#eta_date_edit').datebox('getValue') == undefined || $('#eta_date_edit').datebox('getValue') == '0' || $('#eta_date_edit').datebox('getValue') == '') {
			flag = flag + 1;
			$.messager.alert("WARNING","Data ETA date not completed","warning");
		}

		if($('#vessel_edit').textbox('getValue') == undefined || $('#vessel_edit').textbox('getValue') == '0' || $('#vessel_edit').textbox('getValue') == ''){
			flag = flag + 1;
			$.messager.alert("WARNING","Data Vessel not completed","warning");
		}

		if($('#remark_edit').textbox('getValue') == undefined || $('#remark_edit').textbox('getValue') == '0' || $('#remark_edit').textbox('getValue') == ''){
			flag = flag + 1;
			$.messager.alert("WARNING","Data remark not completed","warning");
		}

		// console.log('FLAG: '+flag);

		if (flag > 0) {
			$.messager.progress('close');
		}else{
			$.messager.alert("INFORMATION","Data completed","info");
			
			for(i=0;i<total;i++){
				dataRows.push({
					WORK_ORDER: $('#dg_edit').datagrid('getData').rows[i].WORK_ORDER,
					ITEM_NO: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
					ITEM_NAME: $('#dg_edit').datagrid('getData').rows[i].ITEM_NAME,
					SI_NO: $('#sino_edit').combogrid('getValue'),
					QUANTITY: $('#dg_edit').datagrid('getData').rows[i].QTY_ORDER,
					CR_DATE: $('#dg_edit').datagrid('getData').rows[i].CR_DATE,
					ETA_DATE: $('#eta_date_edit').datebox('getValue'),
					EX_FAC_DATE: $('#exfact_date_edit').datebox('getValue'),
					ETD_DATE: $('#etd_date_edit').datebox('getValue'),
					SO_NO: $('#dg_edit').datagrid('getData').rows[i].SO_NO,					
					PO_NO: $('#dg_edit').datagrid('getData').rows[i].PO_NO,
					PO_LINE_NO: $('#dg_edit').datagrid('getData').rows[i].PO_LINE_NO,
					VESSEL: $('#vessel_edit').textbox('getValue'),
					LINE_NO: $('#dg_edit').datagrid('getData').rows[i].LINE_NO,
					CUST_CODE: $('#cust_edit').combobox('getValue'),
					QTY_ORDER: $('#dg_edit').datagrid('getData').rows[i].QTY_PLAN.replace(/,/g,''),
					CR_REMARK: $('#remark_edit').textbox('getValue'),
					CURR_CODE:$('#dg_edit').datagrid('getData').rows[i].CURR_CODE,
					U_PRICE: $('#dg_edit').datagrid('getData').rows[i].U_PRICE,
					PPBE: $('#ppbe_edit').textbox('getValue'),
					STS: $('#dg_edit').datagrid('getData').rows[i].STS
				});
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			console.log(unescape(str_unescape));

			$.post('shipping_plan2_save_edit.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#win_edit').window('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Update Data Success..!!<br/>PPBE No. : '+$('#ppbe_edit').textbox('getValue'),'info');
					$.messager.progress('close');
				}else{
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}
	}

	// --------------------------- DIALOG DELETE [START] ---------------------------------------
	function deleteShippingPlan() {
		var row = $('#dg').datagrid('getSelected');
		if (row) {
			$.messager.confirm('Confirm','Are you sure you want to Delete shipping plan?',function(r){
				if(r){
					$.post('shipping_plan2_destroy.php',{ppbe: row.CRS_REMARK, si: row.SI_NO},function(result){
						if (result.successMsg){
	                        $('#dg').datagrid('reload');
	                    }else{
	                    	$.messager.progress('close');
	                        $.messager.show({
	                            title: 'Error',
	                            msg: result.errorMsg
	                        });
	                    }
	                    console.log(result.successMsg);

					},'json');
				}	
			});
		}else{
			$.messager.show({
                title: 'DELETE SHIPPING PLAN',
                msg: "DATA NOT SELECTED"
            });
		}
	}
	// --------------------------- DIALOG DELETE [FINISH] ---------------------------------------
</script>

</body>
</html>
<?php }else{	
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}