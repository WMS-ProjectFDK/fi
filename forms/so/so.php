<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>

<!DOCTYPE html>
   	<html>
   	<head>
   	<meta charset="UTF-8">
   	<title>SALES ORDER</title>
   	<link rel="icon" type="image/png" href="../../favicon.png">
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
			margin: 0px;
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
		<?php 
			include ('../../ico_logout.php');
			$exp = explode('-', access_log($menu_id,$user_name));
		?>
		
		<div id="toolbar" style="padding:3px 3px;">
			<fieldset style="float:left;width:540px;border-radius:4px;"><legend><span class="style3"><strong>SALES ORDER FILTER</strong></span></legend>
				<div style="width:540px;float:left">
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">SO Date</span>
						<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
						to 
						<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
						<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
	    			</div>
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">SO No.</span>
	    				<select style="width:190px;" name="cmb_so_no" id="cmb_so_no" class="easyui-combobox" data-options=" url:'../json/json_sono.php', method:'get', valueField:'so_no', textField:'so_no', panelHeight:'75px'"></select>
						<label><input type="checkbox" name="ck_so_no" id="ck_so_no" checked="true">All</input></label>
	    			</div>
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">Customer</span>
	    				<select style="width:370px;" name="cmb_cust" id="cmb_cust" class="easyui-combobox" data-options=" url:'../json/json_company.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px'"></select>
						<label><input type="checkbox" name="ck_cust" id="ck_cust" checked="true">All</input></label>
	    			</div>
	    		</div>
			</fieldset>
            <fieldset style="position:absolute;margin-left:565px;border-radius:4px;width: 500px;"><legend><span class="style3"><strong>ITEM FILTER</strong></span></legend>
                <div class="fitem">
					<span style="width:110px;display:inline-block;">Cust-PO No.</span>
					<select style="width:190px;" name="cmb_cust_po" id="cmb_cust_po" class="easyui-combobox" data-options=" url:'../json/json_cust_pono.php', method:'get', valueField:'cust_po_no', textField:'cust_po_no', panelHeight:'100px'"></select>
					<label><input type="checkbox" name="ck_cust_po" id="ck_cust_po" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Item No.</span>
					<select style="width:330px;" name="cmb_item" id="cmb_item" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
					onSelect:function(rec){
						//alert(rec.id_name_item);
						var spl = rec.id_name_item;
						var sp = spl.split(' - ');
						$('#txt_item_name').textbox('setValue', sp[1]);
					}"></select>
					<label><input type="checkbox" name="ck_item" id="ck_item" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Item Name</span>
					<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
				</div>
			</fieldset>
			<fieldset style="margin-left: 1090px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>PRINT REPORT</strong></span></legend>
				<!-- <div class="fitem" align="center">
					<a href="javascript:void(0)" style="width: 150px;" id="add" class="easyui-linkbutton c2" disabled="true" onclick="print_bc_no()"><i class="fa fa-print" aria-hidden="true"></i> Print BC NO. View</a>
				</div>
				<div class="fitem" align="center">
					<a href="javascript:void(0)" style="width: 150px;" id="add" class="easyui-linkbutton c2"  onclick="print_bc_no_sp()"><i class="fa fa-print" aria-hidden="true"></i> Spart BC NO. View</a>
				</div> -->
			</fieldset>
			<div style="padding:5px 6px;">
		    	<span style="width:50px;display:inline-block;">search</span>
				<input style="width:150px; height: 18px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src"type="text" placeholder="Sales Order No."/>
	    		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2"  onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
	    		<a href="javascript:void(0)" style="width: 120px;" id="add" class="easyui-linkbutton c2" onclick="add_so()"><i class="fa fa-plus" aria-hidden="true"></i> ADD SO</a>
	    		<a href="javascript:void(0)" style="width: 120px;" id="edit" class="easyui-linkbutton c2" onclick="edit_so()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT SO</a>
	    		<a href="javascript:void(0)" style="width: 120px;" id="delete" class="easyui-linkbutton c2" onclick="delete_so()"><i class="fa fa-trash" aria-hidden="true"></i> REMOVE SO</a>
	    	</div></div>
        </div>

		<table id="dg" title="SALES ORDER" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

		<!-- ADD START -->
		<div id="dlg_add" class="easyui-dialog" style="width:1100px;height:420px;padding:5px 5px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true, position: 'center'">
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:500px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CUSTOMER</span>
					<input required="true" style="width:100px;" name="cust_no_add" id="cust_no_add" class="easyui-textbox" disabled="disabled" data-options="" />
					<select style="width:300px;" name="cmb_cust_add" id="cmb_cust_add" class="easyui-combobox" data-options=" url:'../json/json_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
					onSelect:function(rec){
						$('#cust_no_add').textbox('setValue', rec.company_code);
					}
					" required="">
					</select>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">SALES DATE</span>
					<input style="width:100px;" name="so_date_add" id="so_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
					<span style="width:80px;display:inline-block;">SALES NO.</span>
					<input required="true" style="width:215px;" name="so_no_add" id="so_no_add" class="easyui-textbox"/>
				</div>
			</fieldset>
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:530px;margin-left: 510px;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">CUST PO NO.</span>
					<input style="width:250px;" name="so_cust_po_no_add" id="so_cust_po_no_add" class="easyui-textbox"/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">REMARKS</span>
					<input style="width:400px;" name="so_remark_add" id="so_remark_add" class="easyui-textbox"/>
				</div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:250px;border-radius: 10px;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
			<div id="toolbar_add" style="padding: 5px 5px;">
				<a href="#" iconCls='icon-add' class="easyui-linkbutton" onclick="add_item_add()">ADD ITEM</a>
	    		<a href="#" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_item_add()">REMOVE ITEM</a>
			</div>
		</div>
		<div id="dlg-buttons-add">
			<a href="javascript:void(0)" id="save_add" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveAdd()" style="width:90px">Save</a>
			<a href="javascript:void(0)" id="cancel_add" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
		</div>
		<!-- ADD END -->

		<!-- EDIT START -->
		<div id="dlg_edit" class="easyui-dialog" style="width:1100px;height:420px;padding:5px 5px" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true, position: 'center'">
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:500px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CUSTOMER</span>
					<input required="true" style="width:100px;" name="cust_no_edit" id="cust_no_edit" class="easyui-textbox" disabled="disabled" data-options="" />
					<select style="width:300px;" name="cmb_cust_edit" id="cmb_cust_edit" class="easyui-combobox" data-options=" url:'../json/json_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
					onSelect:function(rec){
						$('#cust_no_edit').textbox('setValue', rec.company_code);	
					}
					" required="" disabled="">
					</select>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">SALES DATE</span>
					<input style="width:100px;" name="so_date_edit" id="so_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
					<span style="width:80px;display:inline-block;">RECEIVE NO.</span>
					<input required="true" style="width:215px;" name="so_no_edit" id="so_no_edit" class="easyui-textbox" disabled=""/>
				</div>
			</fieldset>
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:530px;margin-left: 510px;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">CUST PO NO.</span>
					<input style="width:250px;" name="so_cust_po_no_edit" id="so_cust_po_no_edit" class="easyui-textbox"/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">REMARKS</span>
					<input style="width:400px;" name="so_remark_edit" id="so_remark_edit" class="easyui-textbox"/>
				</div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
		</div>
		<div id="dlg-buttons-edit">
			<a href="javascript:void(0)" id="save_edit" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveEdit()" style="width:90px">Save</a>
			<a href="javascript:void(0)" id="cancel_edit" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
		</div>
		<!-- EDIT END -->

		<!-- ADD ITEM START -->
		<div id="dlg_item" class="easyui-dialog" style="width: 950px;height: 270px;" closed="true" buttons="#dlg-buttons_addPO" data-options="modal:true">
			<table id="dg_item" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true" fitColumns="true"></table>
		</div>
		<!-- ADD ITEM END -->

		<!-- ADD PALLET & CASE START -->
		<div id="dlg_mark" class="easyui-dialog" style="width: 950px;height: 360px;" closed="true" buttons="#dlg-buttons-mark" data-options="modal:true">
			<table id="dg_mark" class="easyui-datagrid" style="width:100%;height:auto;border-radius: 10px;" rownumbers="true" singleSelect="true" fitColumns="true"></table>
		</div>
		<div id="dlg-buttons-mark">
			<div>
				<input style="width: 200px;" name="jns_mark" id="jns_mark" class="easyui-textbox"/>
				<input style="width: 200px;" name="sts_mark" id="sts_mark" class="easyui-textbox"/>
				<input style="width: 200px;" name="row_mark" id="row_mark" class="easyui-textbox"/>
				<input style="width: 200px;" name="result_mark" id="result_mark" class="easyui-textbox"/>

			</div>
			<div style="clear:both;padding:10px 0 0;"></div>
			<a href="javascript:void(0)" id="save_mark" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveMark()" style="width:90px">SAVE</a>
			<a href="javascript:void(0)" id="cancel_mark" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_mark').dialog('close')" style="width:90px">CANCEL</a>
		</div>
		<!-- ADD PALLET & CASE END -->

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

			$(function(){
				$('#date_awal').datebox('disable');
				$('#date_akhir').datebox('disable');
				$('#ck_date').change(function(){
					if($(this).is(':checked')){
						$('#date_awal').datebox('disable');
						$('#date_akhir').datebox('disable');
					}else{
						$('#date_awal').datebox('enable');
						$('#date_akhir').datebox('enable');
					}
				});

				$('#cmb_so_no').combobox('disable');
				$('#ck_so_no').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_so_no').combobox('disable');
					}else{
						$('#cmb_so_no').combobox('enable');
					}
				});

				$('#cmb_cust').combobox('disable');
				$('#ck_cust').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_cust').combobox('disable');
					}else{
						$('#cmb_cust').combobox('enable');
					}
				});

				$('#cmb_cust_po').combobox('disable');
				$('#ck_cust_po').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_cust_po').combobox('disable');
					}else{
						$('#cmb_cust_po').combobox('enable');
					}
				});

				$('#cmb_item').combobox('disable');
				$('#ck_item').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_item').combobox('disable');
					}else{
						$('#cmb_item').combobox('enable');
					}
				});

				document.getElementById('src').focus();
			});

			var src ='';

			function filter(event){
				var src = document.getElementById('src').value;
				var search = src.toUpperCase();
				document.getElementById('src').value = search;
				
			    if(event.keyCode == 13 || event.which == 13){
					var src = document.getElementById('src').value;
					// alert(src);
					$('#dg').datagrid('load', {
						src: search
					});

					$('#dg').datagrid('enableFilter');

					if (src=='') {
						filterData();
					}else{
						$('#dg').datagrid({
							url: 'so_get.php'
						});
					}
					document.getElementById('src').value = '';
			    }
			}

			function filterData(){
				var ck_date = "false";
				var ck_so_no = "false";
				var ck_cust = "false";
				var ck_cust_po = "false";
				var ck_item = "false";
				var flag = 0;

				if ($('#ck_date').attr("checked")) {
					ck_date = "true";
					flag += 1;
				}

				if ($('#ck_so_no').attr("checked")) {
					ck_so_no = "true";
					flag += 1;
				};

				if ($('#ck_cust').attr("checked")) {
					ck_cust = "true";
					flag += 1;
				};

				if ($('#ck_cust_po').attr("checked")) {
					ck_cust_po = "true";
					flag += 1;
				};

				if ($('#ck_item').attr("checked")) {
					ck_item = "true";
					flag += 1;
				};

				if(flag == 5) {
					$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
				}

				$('#dg').datagrid('load', {
					date_awal: $('#date_awal').datebox('getValue'),
					date_akhir: $('#date_akhir').datebox('getValue'),
					ck_date: ck_date,
					cmb_so_no : $('#cmb_so_no').combobox('getValue'),
					ck_so_no: ck_so_no,
					cmb_cust: $('#cmb_cust').combobox('getValue'),
					ck_cust: ck_cust,
					cmb_cust_po: $('#cmb_cust_po').combobox('getValue'),
					ck_cust_po: ck_cust_po,
					cmb_item: $('#cmb_item').combobox('getValue'),
					ck_item: ck_item,
					src: ''
				});

				$('#dg').datagrid( {
					url: 'so_get.php',
					view: detailview,
				    columns:[[
					    {field:'SO_NO',title:'SALES ORDER<br>NO.', width:80, halign:'center'},
		                {field:'SO_DATE',title:'SALES ORDER<br>DATE',width:80,halign:'center', align:'center'},
		                {field:'CUSTOMER_CODE',title:'CUSTOMER<br>CODE',width:60,halign:'center', hidden: true},
		                {field:'COMPANY',title:'SUPLIER',width:130,halign:'center'},
						{field:'CUSTOMER_PO_NO',title:'CUSTOMER<br>PO NO.',width:80,halign:'center'},
		                {field:'CURR_SHORT',title:'CURR',width:35,halign:'center', align:'center'},
		                {field:'EX_RATE',title:'RATE',width:50,halign:'center', align:'center'},
		                {field:'AMT_L',title:'AMOUNT',width:80,halign:'center', align:'right'},
		                {field:'REMARK',title:'REMARK',width:180,halign:'center'},
		                {field:'PERSON',title:'PERSON',width:80,halign:'center'}
					]],
					detailFormatter: function(rowIndex, rowData){
						return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
					},
					onExpandRow: function(index,row){
						var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
						listbrg.datagrid({
		                	title: 'SALES ORDER DETAIL (NO: '+row.SO_NO+')',
							url:'so_get_detail.php?so_no='+row.SO_NO,
							toolbar: '#ddv'+index,
							singleSelect:true,
							loadMsg:'load data ...',
							height:'auto',
							fitColumns: true,
							columns:[[
								{field:'LINE_NO', title:'LINE NO.', halign:'center', align:'center', width:50},
				                {field:'ITEM_NO',title:'ITEM NO.', halign:'center', align:'center', width:65, sortable: true},
				                {field:'DESCRIPTION', title:'ITEM<br>DESCRIPTION', halign:'center', width:150},
				                {field:'UNIT', title:'UNIT', halign:'center', align:'center', width:35},
				                {field:'UOM_Q', title:'UoM', halign:'center', align:'center', width:50, hidden: true},
				                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
				                {field:'U_PRICE', title:'PRICE ('+row.CURR_SHORT+')', halign:'center', align:'right', width:50},
				                {field:'AMT_L', title:'AMOUNT', halign:'center', align:'right', width:70},
								{field:'CASE_MARK', title:'CASE MARK', halign:'center', align:'right', width:120},
								{field:'PALLET_MARK', title:'PALLET MARK', halign:'center', align:'right', width:120},
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

				pdf_url = "?date_awal="+$('#date_awal').datebox('getValue')+
					"&date_akhir="+$('#date_akhir').datebox('getValue')+
					"&ck_date="+ck_date+
					"&cmb_so_no="+$('#cmb_so_no').combobox('getValue')+
					"&ck_so_no="+ck_so_no+
					"&cmb_cust="+$('#cmb_cust').combobox('getValue')+
					"&ck_cust="+ck_cust+
					"&cmb_cust_po="+$('#cmb_cust_po').combobox('getValue')+
					"&ck_cust_po="+ck_cust_po+
					"&cmb_item="+$('#cmb_item').combobox('getValue')+
					"&ck_item="+ck_item+
					"&src="+src;

				// console.log(pdf_url);
				
				$('#dg').datagrid('enableFilter');
			}

			function add_so(){
				$('#dlg_add').dialog('open').dialog('setTitle','ADD SALES ORDER');
				$('#save_add').linkbutton('enable');
				$('#cancel_add').linkbutton('enable');
				$('#cust_no_add').textbox('setValue','');
				$('#cmb_supp_add').combobox('setValue','');
				$('#gr_no_add').textbox('setValue','');
				$('#gr_remark_add').textbox('setValue','');
				$('#dg_add').datagrid('loadData',[]);

				$('#dg_add').datagrid({
				    singleSelect: true,
					rownumbers: true,
				    columns:[[
					    {field:'ITEM_NO', title:'ITEM NO.', width:50, halign: 'center'},
					    {field:'ITEM', title:'ITEM NAME', width:80, halign: 'center'},
					    {field:'DESCRIPTION', title:'DESCRIPTION', halign: 'center', width:250},
					    {field:'U_PRICE', title:'PRICE', width:80, halign: 'center'},
					    {field:'UOM_Q', title:'UoM', halign: 'center', width:50, align:'center'},
					    {field:'CURR_MARK', title:'CURR', halign: 'center', width:50, align:'center'},
						{field:'STK_QTY', title:'STOCK<br/>QTY', halign: 'center',width:80, align:'right'},
						{field:'P_MARK', title:'PALLET<br/>MARK', halign: 'center',width:80, align:'center'},
						{field:'C_MARK', title:'CASE<br/>MARK', halign: 'center',width:80, align:'center'},
					    {field:'ACT_QTY', title:'ACTUAL<br>QTY', align:'right', halign: 'center', width:100, editor:{
					    																						type:'numberbox',
					    																						options:{precision:2,groupSeparator:','}
																											}
						},
						{field: 'P_MARK_RESULT'},
						{field: 'C_MARK_RESULT'}
				    ]],
				    onClickRow:function(rowIndex){
				    	$(this).datagrid('beginEdit', rowIndex);
					}
					// ,
				    // onBeginEdit:function(rowIndex){
				    //     var editors = $('#dg_add').datagrid('getEditors', rowIndex);
				    //     var n1 = $(editors[0].target);
				    //     var n2 = $(editors[1].target);
				    //     var n3 = $(editors[2].target);
				    //     n1.add(n3).numberbox({
				    //         onChange:function(){
				    //             var amt = n1.numberbox('getValue') - n2.numberbox('getValue');
				    //             if(n3.numberbox('getValue') > amt){
					// 				$.messager.confirm('Confirm','actual value over',function(r){
					// 					if(r){
					// 						n3.numberbox('setValue',0);
					// 					}else{
					// 						n3.numberbox('setValue',$(editors[2].target));
					// 					}		
					// 				});
				    //             }
				    //         }
				    //     })
				    // }
				});
			}

			function add_item_add(){
				var cust_id = $('#cust_no_add').textbox('getValue');
				var cust_name = $('#cmb_cust_add').textbox('getText');
				var sts = "'ADD'";

				if (cust_name == ''){
					$.messager.alert('Warning','Please select customer','warning');
				}else{
					$('#dlg_item').dialog('open').dialog('setTitle','SEARCH ITEM');
					$('#dg_item').datagrid({
						url: 'so_getItem.php?cust='+cust_id,
						columns:[[
			                {field:'ITEM_NO',title:'ITEM',width:60,halign:'center', align:'center'},
							{field:'ITEM',title:'ITEM',width:80,halign:'center'},
							{field:'DESCRIPTION',title:'DESCRIPTION',width:200,halign:'center'},
							{field:'CUSTOMER_PART_NO',title:'CUSTOMER<br/>PART NO.',width:60,halign:'center', align:'center'},
							{field:'CURR_MARK',title:'CURR',width:50,halign:'center'},
							{field:'U_PRICE',title:'UNIT PRICE',width:50,halign:'center', align:'right'},
							{field:'STK_QTY',title:'BALANCE<br/>QTY',width:50,halign:'center', align:'right'},
							{field:'ORIGIN_CODE',hidden: true},
							{field:'UOM_Q',hidden: true},
							{field:'ORIGIN',hidden: true},
							{field:'CLASS_CODE',hidden: true},
							{field:'SUPPLIER_CODE',hidden: true}
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
									var item = $('#dg_add').datagrid('getData').rows[i].ITEM_NO;
									if (item == row.ITEM_NO) {
										count++;
									};
								};
							}

							if (count > 0) {
								$.messager.alert('Warning','Item present','warning');
							}else{
								$('#dg_add').datagrid('insertRow',{
									index: idxfield,	// index start with 0
									row: {
										ITEM_NO: row.ITEM_NO,
										ITEM: row.ITEM,
										DESCRIPTION: row.DESCRIPTION,
										U_PRICE: row.U_PRICE,
										UNIT: row.UNIT,
										CURR_MARK: row.CURR_MARK,
										UOM_Q: row.UOM_Q,
										STK_QTY: row.STK_QTY,
										ORIGIN_CODE: row.ORIGIN_CODE,
										CUSTOMER_PART_NO: row.CUSTOMER_PART_NO,
										ORIGIN: row.ORIGIN,
										CLASS_CODE: row.CLASS_CODE,
										SUPPLIER_CODE: row.SUPPLIER_CODE,
										TBL: row.TBL,
										P_MARK:'<a href="javascript:void(0)" onclick="input_pallet('+sts+','+row.ITEM_NO+','+idxfield+')">SET</a>',
										C_MARK:'<a href="javascript:void(0)" onclick="input_case('+sts+','+row.ITEM_NO+','+idxfield+')">SET</a>',
										P_MARK_RESULT: row.P_MARK_RESULT,
										C_MARK_RESULT: row.C_MARK_RESULT
									}
								});
							}
						}
					});

					$('#dg_item').datagrid('enableFilter');
				}
			}
			
			function input_pallet(a,b,c){
				// console.log(a,b);
				$('#dlg_mark').dialog('open').dialog('setTitle','ADD PALLET MARK');
				
				$('#jns_mark').textbox('setValue', 'PALLET_MARK')
				$('#sts_mark').textbox('setValue', a);
				c = '' ?  $('#row_mark').textbox('setValue', 1) : $	('#row_mark').textbox('setValue', c);

				$('#dg_mark').datagrid({
					url: 'so_pallet_mark.json',
					columns:[[
						{field:'pmark',title:'PALLET MARK',width:40,halign:'center'},
						{field:'vmark',title:'COMMAND',width:150,halign:'center', editor:{type:'textbox'}}
					]],
					onClickRow:function(rowIndex){
						$(this).datagrid('beginEdit', rowIndex);
					}
				});
			}

			function input_case(a,b,c){
				// console.log(a,b);
				$('#dlg_mark').dialog('open').dialog('setTitle','ADD CASE MARK');
				
				$('#jns_mark').textbox('setValue', 'PALLET_MARK')
				$('#sts_mark').textbox('setValue', a);
				c = '' ? $('#row_mark').textbox('setValue', 1) : $('#row_mark').textbox('setValue', c);

				$('#dg_mark').datagrid({
					url: 'so_pallet_mark.json',
					columns:[[
						{field:'cmark',title:'CASE MARK',width:40,halign:'center'},
						{field:'vmark',title:'COMMAND',width:150,halign:'center', editor:{type:'textbox'}}
					]],
					onClickRow:function(rowIndex){
						$(this).datagrid('beginEdit', rowIndex);
					}
				});
			}

			function saveMark(){
				var arrS = [];	var arrH = [];
				var rows = $('#dg_mark').datagrid('getRows');
				for(var j=0; j<rows.length; j++){
					arrS.push(rows[j].vmark+"\n");
				}
				$('#result_mark').textbox('setValue',arrS.join(""));
			}

			





			// --------------------------------------------------- EDIT SO ---------------------------------------------------//
			function edit_so(){
				$('#dlg_edit').dialog('open').dialog('setTitle','EDIT SALES ORDER');
			}
		</script>
	</body>
    </html>