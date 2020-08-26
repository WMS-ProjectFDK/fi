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
				<a href="javascript:void(0)" style="width: 150px;" id="add" class="easyui-linkbutton c2" onclick="add_group_so()"><i class="fa fa-plus" aria-hidden="true"></i> ADD SO FROM FDK</a>
	    		<a href="javascript:void(0)" style="width: 120px;" id="edit" class="easyui-linkbutton c2" onclick="edit_so()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT SO</a>
	    		<a href="javascript:void(0)" style="width: 120px;" id="delete" class="easyui-linkbutton c2" onclick="delete_so()"><i class="fa fa-trash" aria-hidden="true"></i> REMOVE SO</a>
	    	</div></div>
        </div>

		<table id="dg" title="SALES ORDER" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

		<!-- ADD START -->
		<div id='win_add' class="easyui-window" style="width:auto;height:565px;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
		  <form id="f_add" method="post" novalidate>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:500px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CUSTOMER</span>
					<input required="true" style="width:100px;" name="cust_no_add" id="cust_no_add" class="easyui-textbox" disabled="disabled" data-options="" />
					<select style="width:300px;" name="cmb_cust_add" id="cmb_cust_add" class="easyui-combobox" data-options=" url:'../json/json_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
					onSelect:function(rec){
						$('#cust_no_add').textbox('setValue', rec.company_code);
						$.ajax({
		        			type: 'GET',
							url: '../json/json_info_cust.php?id='+rec.company_code,
							data: { kode:'kode' },
							success: function(data){
								$('#country_add').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].COUNTRY);
								$('#curr_add').combobox('setValue',data[0].CURR_CODE);
								$('#rate_add').textbox('setValue',1);
							}
		        		});
					}
					" required="">
					</select>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">SO DATE</span>
					<input style="width:100px;" name="so_date_add" id="so_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
					<span style="width:55px;display:inline-block;"></span>
					<label><input type="checkbox" name="ck_in_mps" id="ck_in_mps" checked="true">IN MPS</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">SO NO.</span>
					<input required="true" style="width:100px;" name="so_no_add" id="so_no_add" class="easyui-textbox" disabled=true/>
					<span style="display:inline-block;">CUST P/O</span>
					<input required="true" style="width:211px;" name="so_cust_po_no_add" id="so_cust_po_no_add" class="easyui-textbox"/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CONSIGNEE</span>
					<input style="width:100px;" name="consignee_code_add" id="consignee_code_add" class="easyui-textbox" disabled=true/>
					<input style="width:275px;" name="consignee_name_add" id="consignee_name_add" class="easyui-textbox" disabled=true/>
					<span><a href="javascript:void(0)" onclick="consignee_data('add')">SET</a></span>
				</div>
			</fieldset>
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:740px;margin-left: 510px;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CURR</span>
					<!-- <input required="true" style="width:100px;" name="curr_add" id="curr_add" class="easyui-textbox" disabled=true/>	 -->
					<input style="width:100px;" id="curr_add" class="easyui-combobox" data-options=" url:'../json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'100px',
		        	onSelect: function(rec){
		        		$.ajax({
		        			type: 'GET',
							url: '../json/json_exrate.php?curr='+rec.idcrc,
							data: { kode:'kode' },
							success: function(data){
								$('#rate_add').textbox('setValue',data[0].RATE);	
							}
		        		});
		        	}" required="" disabled="" />
					<span style="display:inline-block;">EX RATE</span>
					<input required="true" style="width:100px;" name="rate_add" id="rate_add" class="easyui-textbox" disabled=true/>
					<span style="display:inline-block;">COUNTRY</span>
					<input required="true" style="width:325px;" name="country_add" id="country_add" class="easyui-textbox" disabled=true/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">REMARKS</span>
					<input style="width:650px;" name="so_remark_add" id="so_remark_add" class="easyui-textbox"/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CASE MARK</span>
					<input style="width:650px;" name="so_casemark_add" id="so_casemark_add" class="easyui-textbox"/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CATEGORY</span>
					<input style="width:650px;" name="so_category_add" id="so_category_add" class="easyui-textbox"/>
				</div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:auto;border-radius: 10px;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
			<div id="toolbar_add" style="padding: 5px 5px;">
				<a href="#" iconCls='icon-add' class="easyui-linkbutton" onclick="add_item('add')">ADD ITEM</a>
	    		<a href="#" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_item_add()">REMOVE ITEM</a>
			</div>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
				<a class="easyui-linkbutton c2" id="save_add" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveAdd('add')" style="width:140px" disabled="true"> SAVE </a>
				<a class="easyui-linkbutton c2" id="cancel_add" href="javascript:void(0)" onclick="javascript:$('#win_add').window('close')" style="width:140px"><i class="fa fa-ban" aria-hidden="true"></i> CANCEL </a>
			</div>
		  </form>
		</div>
		<!-- ADD END -->

		<!-- EDIT START -->
		<div id='win_edit' class="easyui-window" style="width:auto;height:565px;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
		  <form id="f_edit" method="post" novalidate>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:500px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CUSTOMER</span>
					<input required="true" style="width:100px;" name="cust_no_edit" id="cust_no_edit" class="easyui-textbox" disabled="disabled" data-options="" />
					<select style="width:300px;" name="cmb_cust_edit" id="cmb_cust_edit" class="easyui-combobox" data-options=" url:'../json/json_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
					onSelect:function(rec){
						$('#cust_no_edit').textbox('setValue', rec.company_code);
						$.ajax({
		        			type: 'GET',
							url: '../json/json_info_cust.php?id='+rec.company_code,
							data: { kode:'kode' },
							success: function(data){
								$('#country_edit').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].COUNTRY);
								$('#curr_edit').combobox('setValue',data[0].CURR_CODE);
								$('#rate_edit').textbox('setValue',1);
							}
		        		});
					}
					" disabled="disabled">
					</select>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">SO DATE</span>
					<input style="width:100px;" name="so_date_edit" id="so_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
					<span style="width:55px;display:inline-block;"></span>
					<label><input type="checkbox" name="ck_in_mps_edit" id="ck_in_mps_edit" checked="true">IN MPS</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">SO NO.</span>
					<input required="true" style="width:100px;" name="so_no_edit" id="so_no_edit" class="easyui-textbox" disabled=true/>
					<span style="display:inline-block;">CUST P/O</span>
					<input required="true" style="width:211px;" name="so_cust_po_no_edit" id="so_cust_po_no_edit" class="easyui-textbox"/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CONSIGNEE</span>
					<input style="width:100px;" name="consignee_code_edit" id="consignee_code_edit" class="easyui-textbox" disabled=true/>
					<input style="width:275px;" name="consignee_name_edit" id="consignee_name_edit" class="easyui-textbox" disabled=true/>
					<span><a href="javascript:void(0)" onclick="consignee_data('edit')">SET</a></span>
				</div>
			</fieldset>
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:750px;margin-left: 510px;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CURR</span>
					<!-- <input required="true" style="width:100px;" name="curr_edit" id="curr_edit" class="easyui-textbox" disabled=true/>	 -->
					<input style="width:100px;" id="curr_edit" class="easyui-combobox" data-options=" url:'../json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'100px',
		        	onSelect: function(rec){
		        		$.ajax({
		        			type: 'GET',
							url: '../json/json_exrate.php?curr='+rec.idcrc,
							data: { kode:'kode' },
							success: function(data){
								$('#rate_edit').textbox('setValue',data[0].RATE);	
							}
		        		});
		        	}" required="" disabled="" />
					<span style="display:inline-block;">EX RATE</span>
					<input required="true" style="width:100px;" name="rate_edit" id="rate_edit" class="easyui-textbox" disabled=true/>
					<span style="display:inline-block;">COUNTRY</span>
					<input required="true" style="width:325px;" name="country_edit" id="country_edit" class="easyui-textbox" disabled=true/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">REMARKS</span>
					<input style="width:650px;" name="so_remark_edit" id="so_remark_edit" class="easyui-textbox"/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CASE MARK</span>
					<input style="width:650px;" name="so_casemark_edit" id="so_casemark_edit" class="easyui-textbox"/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CATEGORY</span>
					<input style="width:650px;" name="so_category_edit" id="so_category_edit" class="easyui-textbox"/>
				</div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:100%;height:auto;border-radius: 10px;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
			<div id="toolbar_edit" style="padding: 5px 5px;">
				<a href="#" iconCls='icon-add' class="easyui-linkbutton" onclick="add_item('edit')">ADD ITEM</a>
	    		<a href="#" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_item_edit()">REMOVE ITEM</a>
			</div>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
				<a class="easyui-linkbutton c2" id="save_edit" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveedit('edit')" style="width:140px"> SAVE </a>
				<a class="easyui-linkbutton c2" id="cancel_edit" href="javascript:void(0)" onclick="javascript:$('#win_edit').window('close')" style="width:140px"><i class="fa fa-ban" aria-hidden="true"></i> CANCEL </a>
			</div>
		  </form>
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
			<div class="fitem" align="center" hidden="true">
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

		<!-- ADD QTY -->
		<div id="dlg_input_qty" class="easyui-dialog" style="width: 270px;height: 30`0px;" closed="true" buttons="#dlg-buttons-qty" data-options="modal:true" align="center">
			<div class="fitem" hidden="true">
				<input style="width: 100px;" name="sts_order" id="sts_order" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">QTY ORDER</span>
				<input style="width: 100px;" name="qty_order" id="qty_order" class="easyui-numberbox"/>
			</div>
			<div class="fitem" align="center">
				<span style="width:100px;display:inline-block;">REQUEST DATE</span>
				<input style="width:100px;" name="req_date_add" id="req_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<!-- <input style="width: 200px;" name="qty_order" id="qty_order" class="easyui-numberbox"/> -->
			</div>
			<div class="fitem" align="center">
				<span style="width:100px;display:inline-block;">EX FACT DATE</span>
				<input style="width:100px;" name="exfact_date_add" id="exfact_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
			</div>
			<div class="fitem" align="center">
				<span style="width:100px;display:inline-block;">AGING DAY</span>
				<input style="width:100px;" name="aging_day_add" id="aging_day_add" class="easyui-numberbox"/>
			</div>
			<div class="fitem" align="center">
				<span style="width:100px;display:inline-block;">DATE-CODE</span>
				<input style="width:100px;" name="date_code_add" id="date_code_add" class="easyui-textbox"/>
			</div>
			<div class="fitem" align="center">
				<span style="width:120px;display:inline-block;">CUST PO LINE NO.</span>
				<input style="width:100px;" name="cust_po_line_no_add" id="cust_po_line_no_add" class="easyui-textbox"/>
			</div>
			<div class="fitem" align="center">
				<span style="width:100px;display:inline-block;">ASIN</span>
				<input style="width:100px;" name="asin_add" id="asin_add" class="easyui-textbox" />
			</div>
			<div class="fitem" align="center">
				<span style="width:100px;display:inline-block;">AMZ PO NO.</span>
				<input style="width:100px;" name="amz_po_no_add" id="amz_po_no_add" class="easyui-textbox"/>
			</div>
			<div class="fitem" hidden="true">
				<input type="hidden" style="width: 200px;" name="row_qty" id="row_qty" class="easyui-textbox"/>
				<input type="hidden" style="width: 200px;" name="price_qty" id="price_qty" class="easyui-textbox"/>
			</div>
		</div>
		<div id="dlg-buttons-qty" align="center">
			<a href="javascript:void(0)" id="save_qty" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveQTY()" style="width:90px">SAVE</a>
		</div>
		<!-- END QTY -->

		<!-- CONSIGNEE SETT -->
		<div id="dlg_Notify" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" data-options="modal:true">
			<table id="dg_Notify" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>
		<!-- END -->


		<!-- ADD FDK GROUP ORDER -->
		<div id="dlg_group" class="easyui-dialog" style="width: 950px;height: 270px;" closed="true" buttons="#dlg-buttons_addPO" data-options="modal:true">
			<table id="dg_group" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true" fitColumns="true"></table>
		</div>
		<!-- ADD FDK GROUP ORDER  -->

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
		                {field:'PERSON',title:'PERSON',width:80,halign:'center'},
						{field:'CASE_MARK', hidden: true},
						{field:'CONSIGNEE_FROM_JP', hidden: true},//consignee_from_jp
						{field:'ANSWER_QTY',title:'SHIPPING PLAN<br/>QTY',width:80,halign:'center', align:'right'}//answer_qty
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
								{field:'CUSTOMER_PO_LINE_NO', title:'CUST PO LINE NO.', halign:'center', align:'center', width:50},
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

			function validate(value){
				var hasil=0;
				var msg='';
				
				if(value == 'add'){
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;

					if($('#cust_no_add').textbox('getValue')==''){
						msg = $.messager.alert('Warning','Please select customer','warning');
						hasil=1;
					}else if($('#so_date_add').datebox('getValue')==''){
						msg = $.messager.alert('INFORMATION','SO DATE Not Found','info');
						hasil=1;
					}else if($('#so_no_add').textbox('getValue')==''){
						msg = $.messager.alert('INFORMATION','SO NO. Not Found','info');
						hasil=1;
					}else if($('#so_cust_po_no_add').textbox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','Cust PO NO. Not Found','info');
						hasil=1;
					}

					// for(i2=0;i2<total;i2++){
					// 	$('#dg_add').datagrid('endEdit',i2);
					// 	if ($('#dg_add').datagrid('getData').rows[i2].ACT_QTY == '' || $('#dg_add').datagrid('getData').rows[i2].ACT_QTY == 0){
					// 		msg = $.messager.alert('INFORMATION','QTY not Found','info');
					// 		hasil=1;
					// 	}
					// }
				}
			}

			function add_so(){
				$('#win_add').window('open').window('setTitle','ADD SALES ORDER');
				$('#win_add').window('center');
				$('#save_add').linkbutton('enable');
				$('#cancel_add').linkbutton('enable');
				$('#cust_no_add').textbox('setValue','');
				$('#cmb_cust_add').combobox('setValue','');
				$('#so_no_add').textbox('setValue','');
				$('#so_cust_po_no_add').textbox('setValue','');
				$('#consignee_code_add').textbox('setValue','');
				$('#consignee_name_add').textbox('setValue','');
				$('#curr_add').combobox('setValue','');
				$('#rate_add').textbox('setValue','');
				$('#country_add').textbox('setValue','');

				$('#dg_add').datagrid('loadData',[]);

				$('#dg_add').datagrid({
				    singleSelect: true,
					rownumbers: true,
				    columns:[[
					    {field:'ITEM_NO', title:'ITEM NO.', width:50, halign: 'center'},
					    {field:'ITEM', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
					    {field:'DESCRIPTION', title:'DESCRIPTION', halign: 'center', width:250},
					    {field:'U_PRICE', title:'PRICE', width:80, halign: 'center'},
					    {field:'UOM_Q', title:'UoM', halign: 'center', width:50, align:'center'},
					    {field:'CURR_MARK', title:'CURR', halign: 'center', width:50, align:'center'},
						{field:'STK_QTY', title:'STOCK<br/>QTY', halign: 'center',width:80, align:'right', hidden: true},
					    {field:'ACT_QTY', title:'ORDER<br>SET', halign: 'center', width:50, align:'center'},
						{field:'ACT_QTY_RESULT', title:'ACT QTY<br/>RESULT', halign: 'center', width:100, align:'right'},
						{field:'AMOUNT_RESULT', title:'AMOUNT<br/>RESULT', halign: 'center', width:100, align:'right', hidden: true},
						{field:'REQ_DATE', title:'REQUEST<br/>DATE', halign: 'center', width:100, align:'center'},
						{field:'EXFACT_DATE', title:'EX FACT<br/>DATE', halign: 'center', width:100, align:'center'},
						{field:'AGING_DAY', title:'AGING<br/>DAY', halign: 'center', width:100, align:'center'},
						{field:'DATE_CODE', title:'DATE<br/>CODE', halign: 'center', width:100, align:'center'},
						{field:'CUSTOMER_PO_LINE_NO', title:'CUST PO<br/>LINE NO.', halign:'center', align:'center', width:50},
						{field:'ASIN', title:'ASIN', halign: 'center', width:100, align:'center'},
						{field:'AMZ_PO', title:'AMZ PO', halign: 'center', width:100, align:'center'},
						{field:'P_MARK', title:'PALLET<br/>MARK', halign: 'center',width:50, align:'center'},
						{field: 'P_MARK_RESULT', title:'PALLET MARK<br/>RESULT', halign: 'center', width:150},
						{field:'C_MARK', title:'CASE<br/>MARK', halign: 'center',width:50, align:'center'},
						{field: 'C_MARK_RESULT', title:'CASE MARK<br/>RESULT', halign: 'center', width:150} //, hidden: true}
				    ]],
				    onClickRow:function(rowIndex){
				    	$(this).datagrid('beginEdit', rowIndex);
					},
					onAfterEdit:function(index,row){
						$(this).datagrid('beginEdit', index);
					}
				});
			}

			function add_item(val){
				var cust_id = '';
				var cust_name = '';
				var sts = '';

				if (val == 'add'){
					cust_id = $('#cust_no_add').textbox('getValue');
					cust_name = $('#cmb_cust_add').textbox('getText');
					sts = "'ADD'";
				}else{
					cust_id = $('#cust_no_edit').textbox('getValue');
					cust_name = $('#cmb_cust_edit').textbox('getText');
					sts = "'EDIT'";
				}

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
							var t = ''
							val == 'add' ? t = $('#dg_add').datagrid('getRows') : t = $('#dg_edit').datagrid('getRows');
							var total = t.length;
						   	var idxfield=0;
						   	var i = 0;
						   	var count = 0;
							
							if (parseInt(total) == 0) {
								idxfield=total;
							}else{
								idxfield=total+1;
								for (i=0; i < total; i++) {
									var item = '';
									val == 'add' ? item = $('#dg_add').datagrid('getData').rows[i].ITEM_NO : item = $('#dg_edit').datagrid('getData').rows[i].ITEM_NO;
									if (item == row.ITEM_NO) {
										count++;
									};
								};
							}

							if (count > 0) {
								$.messager.alert('Warning','Item present','warning');
							}else{
								if (val == 'add'){
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
											ACT_QTY: '<a href="javascript:void(0)" onclick="input_qty('+row.ITEM_NO+','+idxfield+','+row.U_PRICE+','+sts+')">SET</a>', 
											P_MARK:'<a href="javascript:void(0)" onclick="input_pallet('+sts+','+row.ITEM_NO+','+idxfield+')">SET</a>',
											C_MARK:'<a href="javascript:void(0)" onclick="input_case('+sts+','+row.ITEM_NO+','+idxfield+')">SET</a>',
											P_MARK_RESULT: row.P_MARK_RESULT,
											C_MARK_RESULT: row.C_MARK_RESULT
										}
									});
								}else{
									$('#dg_edit').datagrid('insertRow',{
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
											ACT_QTY: '<a href="javascript:void(0)" onclick="input_qty('+row.ITEM_NO+','+idxfield+','+row.U_PRICE+','+sts+')">SET</a>', 
											P_MARK:'<a href="javascript:void(0)" onclick="input_pallet('+sts+','+row.ITEM_NO+','+idxfield+')">SET</a>',
											C_MARK:'<a href="javascript:void(0)" onclick="input_case('+sts+','+row.ITEM_NO+','+idxfield+')">SET</a>',
											P_MARK_RESULT: row.P_MARK_RESULT,
											C_MARK_RESULT: row.C_MARK_RESULT
										}
									});
								}
							}
						}
					});

					$('#dg_item').datagrid('enableFilter');
				}
			}

			function remove_item_add(){
				var row = $('#dg_add').datagrid('getSelected');	
				if (row){
					var idx = $("#dg_add").datagrid("getRowIndex", row);
					$('#dg_add').datagrid('deleteRow', idx);
				}
			}

			function input_qty(a,b,c,d){
				// if (d == 'ADD') {
					$('#dlg_input_qty').dialog('open').dialog('setTitle', d+' QTY ORDER');
					$('#sts_order').textbox('setValue', d);
					$('#qty_order').textbox('setValue','');
					$('#aging_day_add').numberbox('setValue', '');
					$('#date_code_add').textbox('setValue', '');
					$('#cust_po_line_no_add').textbox('setValue', '');
					$('#asin_add').textbox('setValue', '');
					$('#amz_po_no_add').textbox('setValue', '');
					b == '' ?  $('#row_qty').textbox('setValue', 1) : $	('#row_qty').textbox('setValue', b);
					$('#price_qty').textbox('setValue', c);
				// }
				//else if(d == 'EDIT'){
				// 	$('#dlg_input_qty').dialog('open').dialog('setTitle','EDIT QTY ORDER');
				// 	$('#qty_order').textbox('setValue','');
				// 	b == '' ?  $('#row_qty').textbox('setValue', 1) : $	('#row_qty').textbox('setValue', b);
				// 	$('#price_qty').textbox('setValue', c);
				// }
			}

			function saveQTY(){
				var indexo = $('#row_qty').textbox('getValue') - 1;
				var amt = parseFloat(
							$('#qty_order').textbox('getValue') * parseFloat($('#price_qty').textbox('getValue')).toFixed(6)
						).toFixed(2);
				// console.log(parseFloat($('#price_qty').textbox('getValue')).toFixed(6));
				// console.log(amt);
				var s_order = $('#sts_order').textbox('getValue'); 
				if (s_order == 'ADD'){
					$('#dg_add').datagrid('updateRow',{
						index:  indexo,
						row: {
							ACT_QTY_RESULT : $('#qty_order').textbox('getValue'),
							AMOUNT_RESULT : amt,
							REQ_DATE : $('#req_date_add').datebox('getValue'),
							EXFACT_DATE : $('#exfact_date_add').datebox('getValue'),
							AGING_DAY: $('#aging_day_add').numberbox('getValue'),
							DATE_CODE: $('#date_code_add').textbox('getValue'),
							CUSTOMER_PO_LINE_NO : $('#cust_po_line_no_add').textbox('getValue'),
							ASIN: $('#asin_add').textbox('getValue'),
							AMZ_PO: $('#amz_po_no_add').textbox('getValue')

						}
					});
				}else{
					$('#dg_edit').datagrid('updateRow',{
						index: indexo,
						row: {
							ACT_QTY_RESULT : $('#qty_order').textbox('getValue'),
							AMOUNT_RESULT : amt,
							REQ_DATE : $('#req_date_add').datebox('getValue'),
							EXFACT_DATE : $('#exfact_date_add').datebox('getValue'),
							AGING_DAY: $('#aging_day_add').numberbox('getValue'),
							DATE_CODE: $('#date_code_add').textbox('getValue'),
							CUSTOMER_PO_LINE_NO : $('#cust_po_line_no_add').textbox('getValue'),
							ASIN: $('#asin_add').textbox('getValue'),
							AMZ_PO: $('#amz_po_no_add').textbox('getValue')

						}
					});
				}
				$('#dlg_input_qty').dialog('close');
			}
			
			function input_pallet(a,b,c){
				// console.log(a,b);
				$('#dlg_mark').dialog('open').dialog('setTitle','ADD PALLET MARK');
				
				$('#jns_mark').textbox('setValue', 'P_MARK_RESULT')
				$('#sts_mark').textbox('setValue', a);
				c == '' ?  $('#row_mark').textbox('setValue', 1) : $	('#row_mark').textbox('setValue', c);

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
				
				$('#jns_mark').textbox('setValue', 'C_MARK_RESULT')
				$('#sts_mark').textbox('setValue', a);
				c == '' ? $('#row_mark').textbox('setValue', 1) : $('#row_mark').textbox('setValue', c);

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
				$('#dg_mark').datagrid('beginEdit');
				var ids = [];
				var arr1 = [];
				var h;
				var t = $('#dg_mark').datagrid('getRows');
				var total = t.length;
				var jmrow=0;

				for(i=0;i<total;i++){
					jmrow = i+1;
					$('#dg_mark').datagrid('endEdit',i);
					if ($('#dg_mark').datagrid('getData').rows[i].vmark != ''){
						ids.push(
							$('#dg_mark').datagrid('getData').rows[i].vmark
						);
					}
				}

				$('#result_mark').textbox('setValue',ids.join("\n"));
				// console.log(ids.join("\n"));
				// console.log($('#row_mark').textbox('getValue'));
				// console.log($('#jns_mark').textbox('getValue'));
				var indexo = $('#row_mark').textbox('getValue') - 1;
				var nameField = $('#jns_mark').textbox('getValue');
				var sts_dg = $('#sts_mark').textbox('getValue');

				if (sts_dg == 'ADD'){
					if (nameField == 'P_MARK_RESULT'){
						$('#dg_add').datagrid('updateRow',{
							index:  indexo,
							row: {
								P_MARK_RESULT : ids.join("<br/>")
							}
						});
					}else{
						$('#dg_add').datagrid('updateRow',{
							index:  indexo,
							row: {
								C_MARK_RESULT : ids.join("<br/>")
							}
						});
					}
				}else{
					if (nameField == 'P_MARK_RESULT'){
						$('#dg_edit').datagrid('updateRow',{
							index:  indexo,
							row: {
								P_MARK_RESULT : ids.join("<br/>")
							}
						});
					}else{
						$('#dg_edit').datagrid('updateRow',{
							index:  indexo,
							row: {
								C_MARK_RESULT : ids.join("<br/>")
							}
						});
					}
				}
				
				

				$('#dg_add').datagrid({
					// onClickRow:function(rowIndex){
					// 	$(this).datagrid('beginEdit', rowIndex);
					// }
					onAfterEdit:function(index,row){
						$(this).datagrid('beginEdit', index);
					}
				});

				$('#dg_add').datagrid('reload');
				// $('#dg_add').datagrid('beginEdit');
				$('#dlg_mark').dialog('close');
			}

			function consignee_data(value){
				$('#dlg_Notify').dialog('open').dialog('setTitle','SEARCH CONSIGNEE');
				$('#dg_Notify').datagrid('load',{sch: ''});
				$('#dg_Notify').datagrid({
					url: '../json/json_consignee.php',
					fitColumns: true,
					columns:[[
						{field:'CONSIGNEE_CODE',title:'CODE',width:65,halign:'center', align:'center'},
						{field:'CONSIGNEE_NAME',title:'NAME',width:150,halign:'center'}
					]],
					onClickRow:function(id,row){
						var rows = $('#dg_Notify').datagrid('getSelected');
						if(value=='add'){
							$('#consignee_code_add').textbox('setValue', rows.CONSIGNEE_CODE);
							$('#consignee_name_add').textbox('setValue', rows.CONSIGNEE_NAME);
						}//else if(value == 'edit'){
						// 	$('#notify_edit').textbox('setValue', rows.NOTIFY);
						// }
						$('#dlg_Notify').dialog('close');
					}
				});

				$('#dg_Notify').datagrid('enableFilter');
			}

			function saveAdd(value){
				$.messager.progress({
					title:'Please waiting',
					msg:'Saving data...'
				});

				if(value == 'add'){
					$.ajax({
						type: 'GET',
						url: '../json/json_kode_so.php',
						data: { kode:'kode' },
						success: function(data){
							if(data[0].kode == 'UNDEFINIED'){
								$.messager.alert('INFORMATION','SO NO. Error..!!','info');
								$.messager.progress('close');
							}else{
								$('#so_no_add').textbox('setValue', data[0].kode);
								if (validate('add') != 1){
									simpan();
								}
							}
						}
					});
				}
			}

			function simpan(){
				var rows = [];
				var amt = 0;
				var tot_amt = 0;
				var t = $('#dg_add').datagrid('getRows');
				var total = t.length;

				var ck_in_mps = "false";
				if ($('#ck_in_mps').attr("checked")) {
					ck_in_mps = "true";
				};

				for(i=0;i<total;i++){
					jmrow = i+1;
					$('#dg_add').datagrid('endEdit',i);
					rows.push({
						so_sts : 'DETAILS',
						so_cust: $('#cust_no_add').textbox('getValue'),
						so_date: $('#so_date_add').datebox('getValue'),
						so_so_no: $('#so_no_add').textbox('getValue'),
						so_line_no: jmrow,
						so_cust_po_no: $('#so_cust_po_no_add').textbox('getValue'),
						so_consignee_code: $('#consignee_code_add').textbox('getValue'),
						so_consignee_name: $('#consignee_name_add').textbox('getValue'),
						so_curr: $('#curr_add').combobox('getValue'),
						so_rate: $('#rate_add').textbox('getValue'),
						so_country: $('#country_add').textbox('getValue'),
						so_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
						so_price: $('#dg_add').datagrid('getData').rows[i].U_PRICE,
						so_uom: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
						so_qty: $('#dg_add').datagrid('getData').rows[i].ACT_QTY_RESULT,
						so_p_mark: $('#dg_add').datagrid('getData').rows[i].P_MARK_RESULT,
						so_c_mark: $('#dg_add').datagrid('getData').rows[i].C_MARK_RESULT,
						so_amount: $('#dg_add').datagrid('getData').rows[i].AMOUNT_RESULT,
						so_req_date: $('#dg_add').datagrid('getData').rows[i].REQ_DATE,
						so_ex_fact_date: $('#dg_add').datagrid('getData').rows[i].EXFACT_DATE,
						so_aging_day: $('#dg_add').datagrid('getData').rows[i].AGING_DAY,
						so_date_code: $('#dg_add').datagrid('getData').rows[i].DATE_CODE,
						so_po_line_no: $('#dg_add').datagrid('getData').rows[i].CUSTOMER_PO_LINE_NO,
						so_asin: $('#dg_add').datagrid('getData').rows[i].ASIN,
						so_amz_po_no: $('#dg_add').datagrid('getData').rows[i].AMZ_PO
					});

					amt = parseFloat($('#dg_add').datagrid('getData').rows[i].AMOUNT_RESULT).toFixed(2);
					tot_amt += parseFloat(amt);

					if(i==total-1){
						rows.push({
						so_sts : 'HEADER',
						so_cust: $('#cust_no_add').textbox('getValue'),
						so_date: $('#so_date_add').datebox('getValue'),
						so_so_no: $('#so_no_add').textbox('getValue'),
						so_cust_po_no: $('#so_cust_po_no_add').textbox('getValue'),
						so_consignee_code: $('#consignee_code_add').textbox('getValue'),
						so_consignee_name: $('#consignee_name_add').textbox('getValue'),
						so_curr: $('#curr_add').combobox('getValue'),
						so_rate: $('#rate_add').textbox('getValue'),
						so_country: $('#country_add').textbox('getValue'),
						so_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
						so_price: $('#dg_add').datagrid('getData').rows[i].U_PRICE,
						so_uom: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
						so_p_mark: $('#so_remark_add').textbox('getValue'), //$('#dg_add').datagrid('getData').rows[i].P_MARK_RESULT,
						so_c_mark: $('#so_casemark_add').textbox('getValue'), //$('#dg_add').datagrid('getData').rows[i].C_MARK_RESULT,
						so_category_mark: $('#so_category_add').textbox('getValue'),
						so_amount: tot_amt,
						so_in_mps: ck_in_mps
					});
					}
				}

				var myJSON = JSON.stringify(rows);
				var str_unescape = unescape(myJSON);

				console.log('so_save.php?data='+str_unescape);

				$.post('so_save.php',{
					data: unescape(str_unescape)
				}).done(function(res){
					if(res == '"success"'){
						// $('#dlg_add').dialog('close');
						$('#win_add').window('close');
						$('#dg').datagrid('reload');
						$.messager.alert('INFORMATION','Insert Data Success..!!<br/>SO No. : '+$('#so_no_add').textbox('getValue'),'info');
						$.messager.progress('close');
					}else{
						$.post('so_destroy.php',{prf_no: $('#so_no_add').textbox('getValue')},'json');
						$.messager.alert('ERROR',res,'warning');
						$.messager.progress('close');
					}
				});
			}
			
			// --------------------------------------------------- EDIT SO ---------------------------------------------------//
			function edit_so(){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					$('#win_edit').window('open').dialog('setTitle','EDIT SALES ORDER ('+row.SO_NO+')');
					$('#cust_no_edit').textbox('setValue', row.CUSTOMER_CODE);
					$('#cmb_cust_edit').combobox('setValue', row.CUSTOMER_CODE);
					$.ajax({
						type: 'GET',
						url: '../json/json_info_cust.php?id='+row.CUSTOMER_CODE,
						data: { kode:'kode' },
						success: function(data){
							$('#country_edit').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].COUNTRY);
							$('#curr_edit').combobox('setValue',data[0].CURR_CODE);
							$('#rate_edit').textbox('setValue',1);
						}
					});
					$('#so_no_edit').textbox('setValue', row.SO_NO);
					$('#so_cust_po_no_edit').textbox('setValue', row.CUSTOMER_PO_NO);
					$('#consignee_code_edit').textbox('setValue', row.CONSIGNEE_CODE); 
					$('#consignee_name_edit').textbox('setValue', row.CONSIGNEE_NAME); 
					$('#so_remark_edit').textbox('setValue', row.REMARK);
					$('#so_category_edit').textbox('setValue', row. CONSIGNEE_FROM_JP);
					$('#so_casemark_edit').textbox('setValue', row.CASE_MARK);

					$('#dg_edit').datagrid({
						url:'so_get_detail_edit.php?so_no='+row.SO_NO,
						singleSelect: true,
						rownumbers: true,
						columns:[[
							{field:'ITEM_NO', title:'ITEM NO.', width:50, halign: 'center'},
							{field:'ITEM', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
							{field:'DESCRIPTION', title:'DESCRIPTION', halign: 'center', width:250},
							{field:'U_PRICE', title:'PRICE', width:80, halign: 'center'},
							{field:'UOM_Q', title:'UoM', halign: 'center', width:50, align:'center'},
							{field:'CURR_MARK', title:'CURR', halign: 'center', width:50, align:'center'},
							{field:'STK_QTY', title:'STOCK<br/>QTY', halign: 'center',width:80, align:'right', hidden: true},
							{field:'ACT_QTY', title:'ORDER<br>SET', halign: 'center', width:50, align:'center'},
							{field:'ACT_QTY_RESULT', title:'ACT QTY<br/>RESULT', halign: 'center', width:100, align:'right'},
							{field:'AMOUNT_RESULT', title:'AMOUNT<br/>RESULT', halign: 'center', width:100, align:'right', hidden: true},
							{field:'REQ_DATE', title:'REQUEST<br/>DATE', halign: 'center', width:100, align:'center'},
							{field:'EXFACT_DATE', title:'EX FACT<br/>DATE', halign: 'center', width:100, align:'center'},
							{field:'AGING_DAY', title:'AGING<br/>DAY', halign: 'center', width:100, align:'center'},
							{field:'DATE_CODE', title:'DATE<br/>CODE', halign: 'center', width:100, align:'center'},
							{field:'CUSTOMER_PO_LINE_NO', title:'CUST PO<br/>LINE NO.', halign:'center', align:'center', width:50},
							{field:'ASIN', title:'ASIN', halign: 'center', width:100, align:'center'},
							{field:'AMZ_PO', title:'AMZ PO', halign: 'center', width:100, align:'center'},
							{field:'P_MARK', title:'PALLET<br/>MARK', halign: 'center',width:50, align:'center'},
							{field:'P_MARK_RESULT', title:'PALLET MARK<br/>RESULT', halign: 'center', width:150},
							{field:'C_MARK', title:'CASE<br/>MARK', halign: 'center',width:50, align:'center'},
							{field:'C_MARK_RESULT', title:'CASE MARK<br/>RESULT', halign: 'center', width:150} //, hidden: true}
						]],
						onClickRow:function(rowIndex){
							$(this).datagrid('beginEdit', rowIndex);
						},
						onAfterEdit:function(index,row){
							$(this).datagrid('beginEdit', index);
						}
					});
				}
			}

			function remove_item_edit(){
				var row = $('#dg_edit').datagrid('getSelected');	
				if (row){
					var idx = $("#dg_edit").datagrid("getRowIndex", row);
					$('#dg_edit').datagrid('deleteRow', idx);
				}
			}

			function saveedit(value){
				// $.messager.progress({
				// 	title:'Please waiting',
				// 	msg:'Saving data...'
				// });

				if(value == 'edit'){
					var so = $('#so_no_edit').textbox('getValue');
					$.post('so_destroy.php',{so_no: so},function(result){
						if (result.success){
							simpanEdit();
						}else{
							$.messager.show({
								title: 'Error',
								msg: result.errorMsg
							});
							$.messager.progress('close');
						}
					},'json');
				}
			}

			function simpanEdit(){
				var rows = [];
				var amt = 0;
				var tot_amt = 0;
				var t = $('#dg_edit').datagrid('getRows');
				var total = t.length;

				var ck_in_mps = "false";
				if ($('#ck_in_mps_edit').attr("checked")) {
					ck_in_mps = "true";
				};

				for(i=0;i<total;i++){
					jmrow = i+1;
					$('#dg_edit').datagrid('endEdit',i);
					rows.push({
						so_sts : 'DETAILS',
						so_cust: $('#cust_no_edit').textbox('getValue'),
						so_date: $('#so_date_edit').datebox('getValue'),
						so_so_no: $('#so_no_edit').textbox('getValue'),
						so_line_no: jmrow,
						so_cust_po_no: $('#so_cust_po_no_edit').textbox('getValue'),
						so_consignee_code: $('#consignee_code_edit').textbox('getValue'),
						so_consignee_name: $('#consignee_name_edit').textbox('getValue'),
						so_curr: $('#curr_edit').combobox('getValue'),
						so_rate: $('#rate_edit').textbox('getValue'),
						so_country: $('#country_edit').textbox('getValue'),
						so_item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
						so_price: $('#dg_edit').datagrid('getData').rows[i].U_PRICE,
						so_uom: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
						so_qty: $('#dg_edit').datagrid('getData').rows[i].ACT_QTY_RESULT,
						so_p_mark: $('#dg_edit').datagrid('getData').rows[i].P_MARK_RESULT,
						so_c_mark: $('#dg_edit').datagrid('getData').rows[i].C_MARK_RESULT,
						so_amount: $('#dg_edit').datagrid('getData').rows[i].AMOUNT_RESULT,
						so_req_date: $('#dg_edit').datagrid('getData').rows[i].REQ_DATE,
						so_ex_fact_date: $('#dg_edit').datagrid('getData').rows[i].EXFACT_DATE,
						so_aging_day: $('#dg_edit').datagrid('getData').rows[i].AGING_DAY,
						so_date_code: $('#dg_edit').datagrid('getData').rows[i].DATE_CODE,
						so_po_line_no: $('#dg_edit').datagrid('getData').rows[i].CUSTOMER_PO_LINE_NO,
						so_asin: $('#dg_edit').datagrid('getData').rows[i].ASIN,
						so_amz_po_no: $('#dg_edit').datagrid('getData').rows[i].AMZ_PO,
					});

					amt = parseFloat($('#dg_edit').datagrid('getData').rows[i].AMOUNT_RESULT).toFixed(2);
					tot_amt += parseFloat(amt);

					if(i==total-1){
						rows.push({
						so_sts : 'HEADER',
						so_cust: $('#cust_no_edit').textbox('getValue'),
						so_date: $('#so_date_edit').datebox('getValue'),
						so_so_no: $('#so_no_edit').textbox('getValue'),
						so_cust_po_no: $('#so_cust_po_no_edit').textbox('getValue'),
						so_consignee_code: $('#consignee_code_edit').textbox('getValue'),
						so_consignee_name: $('#consignee_name_edit').textbox('getValue'),
						so_curr: $('#curr_edit').combobox('getValue'),
						so_rate: $('#rate_edit').textbox('getValue'),
						so_country: $('#country_edit').textbox('getValue'),
						so_item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
						so_price: $('#dg_edit').datagrid('getData').rows[i].U_PRICE,
						so_uom: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
						so_p_mark: $('#so_remark_edit').textbox('getValue'), //$('#dg_edit').datagrid('getData').rows[i].P_MARK_RESULT,
						so_c_mark: $('#so_casemark_edit').textbox('getValue'), //$('#dg_edit').datagrid('getData').rows[i].C_MARK_RESULT,
						so_category_mark: $('#so_category_edit').textbox('getValue'),
						so_amount: tot_amt,
						so_in_mps: ck_in_mps
					});
					}
				}

				var myJSON = JSON.stringify(rows);
				var str_unescape = unescape(myJSON);

				console.log('so_save.php?data='+str_unescape);

				$.post('so_save.php',{
					data: unescape(str_unescape)
				}).done(function(res){
					if(res == '"success"'){
						// $('#dlg_edit').dialog('close');
						$('#win_edit').window('close');
						$('#dg').datagrid('reload');
						$.messager.alert('INFORMATION','Update Data Success..!!<br/>SO No. : '+$('#so_no_edit').textbox('getValue'),'info');
						$.messager.progress('close');
					}else{
						$.post('so_destroy.php',{prf_no: $('#so_no_edit').textbox('getValue')},'json');
						$.messager.alert('ERROR',res,'warning');
						$.messager.progress('close');
					}
				});
			}

			function add_group_so(){
				$('#dlg_group').dialog('open').dialog('setTitle','GROUP ORDER');
				$('#dg_group').datagrid ({
					url: 'so_getOrderGroup.php',
					columns:[[
						{field:'CUSTOMER_PO_NO',title:'CUSTOMER<br/>PO NO.',width:60,halign:'center', align:'center'},
						{field:'CUSTOMER_PO_DATE',title:'CUSTOMER<br/>PO DATE',width:60,halign:'center', align:'center'},
						{field:'CUSTOMER_PERSON',title:'PERSON',width:200,halign:'center'},
						{field:'CUSTOMER_PERSON_CODE',title:'DESCRIPTION',width:80,halign:'center'},
						{field:'CREATE',title:'ADD TO SO', width:80, halign:'center', align:'center'}
					]]
				});
				$('#dg_group').datagrid('enableFilter');
			}

			function delete_so(){
				var row = $('#dg').datagrid('getSelected');	
				if (row){
					if (row.ANSWER_QTY != 0) {
						$.messager.alert('INFORMATION',"Data can't to remove, because it has Shipping plan",'info');
					}else{
						$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
							if(r){
								$.messager.progress({
									title:'Please waiting',
									msg:'removing data...'
								});
								// console.log('so_destroy.php?so_no='+row.SO_NO);
								$.post('so_destroy.php',{so_no: row.SO_NO},function(result){
									if (result.success){
										$('#dg').datagrid('reload');
										$.messager.progress('close');
									}else{
										$.messager.show({
											title: 'Error',
											msg: result.errorMsg
										});
										$.messager.progress('close');
									}
								},'json');
							}
						});
					}
				}
			}
		</script>
	</body>
    </html>