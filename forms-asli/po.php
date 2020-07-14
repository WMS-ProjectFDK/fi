<?php 
session_start();
include("../connect/conn.php");
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
if ($varConn=='Y'){
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>PURCHASE ORDER</title>
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
	<link rel="stylesheet" type="text/css" href="../css/style.css">
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
	<?php include ('../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name));?>
	
	<!-- ADD -->
	<div id='dlg_add' class="easyui-dialog" style="width:1100px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	   <form id="ff" method="post" novalidate>	
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend> Select Supplier </legend>
			<div class="fitem">
				<span style="width:60px;display:inline-block;">Supplier</span>
				<select style="width:310px;" name="supplier_add" id="supplier_add" class="easyui-combobox" data-options=" url:'json/json_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
				onSelect: function(rec){
					//alert(rec.company_code);
					$('#add_po_add').linkbutton('enable');
					$('#remove_po_add').linkbutton('enable');
					$('#search_prf').linkbutton('enable');
					$.ajax({
						type: 'GET',
						url: 'json/json_company_details.php?id='+rec.company_code,
						data: { kode:'kode' },
						success: function(data){
							$('#country_add').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].COUNTRY);
							$('#payterms_add').textbox('setValue',data[0].PDAYS+'-'+data[0].PDESC);
							$('#attn_add').textbox('setValue',data[0].ATTN);
							$('#curr_add').combobox('setValue',data[0].CURR_CODE);
							$('#rate_add').textbox('setValue',data[0].x_rate);
							$('#ship_add').combobox('setValue',data[0].SHIPTO_CODE);
							$('#trade_add').textbox('setValue',data[0].TTERM);
							$('#rate_add').textbox('setValue',data[0].EX_RATE);
							$('#comment').textbox('setValue','*): LATEST PO: '+data[0].PO_NO+' ('+data[0].PO_DATE+')');
						}
					})
				}" required="" ></select>
				<span style="width:10px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Payment Terms</span>
				<input style="width:360px;" name="payterms_add" id="payterms_add" class="easyui-textbox" disabled="" />
				<input style="width:160px;" name="country_add" id="country_add" class="easyui-textbox" disabled="" />
			</div>
			<div class="fitem">
				<span style="width:60px;display:inline-block;">Currency</span>
				<input style="width:85px;" id="curr_add" class="easyui-combobox" data-options=" url:'json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'100px',
	        	onSelect: function(rec){
	        		$.ajax({
	        			type: 'GET',
						url: 'json/json_exrate.php?curr='+rec.idcrc,
						data: { kode:'kode' },
						success: function(data){
							$('#rate_add').textbox('setValue',data[0].RATE);	
						}
	        		});
	        	}" required="" />
	        	<span style="width:14px;display:inline-block;"></span>
				<span style="width:60px;display:inline-block;">Ex. Rate</span>
				<input style="width:140px;" name="rate_add" id="rate_add" class="easyui-textbox" disabled="" />
				<span style="width:10px;display:inline-block;"></span>
				<span style="width:50px;display:inline-block;">ATTN</span>
				<input style="width:140px;" name="attn_add" id="attn_add" class="easyui-textbox"/>
				<span style="width:50px;display:inline-block;">Ship To</span>
				<select style="width:185px;" name="ship_add" id="ship_add" class="easyui-combobox" data-options=" url:'json/json_ship_to.php', method:'get', valueField:'com_code', textField:'com_name', panelHeight:'150px'"></select>
				<span style="width:40px;display:inline-block;">Trade</span>
				<input style="width:142px;" name="trade_add" id="trade_add" class="easyui-textbox"/>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend>Create PO</legend>
			<div style="width:395px; height: 80px; float:left;">	
				<div class="fitem">
					<input style="width:375px;color: red" name="comment" id="comment" class="easyui-textbox" disabled="" />
				</div>
				<div class="fitem">
					<span style="width:50px;display:inline-block;">PO No.</span>
					<input style="width:163px;" name="po_no_add" id="po_no_add" class="easyui-textbox" required=""/>
					<span style="width:5px;display:inline-block;"></span>
					<span style="width:55px;display:inline-block;">PO Date</span>
					<input style="width:85px;" name="po_date_add" id="po_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				</div>
				<div class="fitem">
					<span style="width:50px;display:inline-block;">DI Type</span>
					<select style="width:75px;" name="di_type_add" id="di_type_add" class="easyui-combobox" data-options="panelHeight:'40px'" required="">
						<option value="0" selected="">No</option>
						<option value="1">Yes</option>
					</select>
					<span style="width:88px;display:inline-block;"></span>
					<span style="width:60px;display:inline-block;">Transport</span>
					<select style="width:85px;" name="trans_add" id="trans_add" class="easyui-combobox" data-options="panelHeight:'70px'" required="">
						<option selected="" value=""></option>
						<option value="1">AIR</option>
						<option value="2">OCEAN</option>
						<option value="3">TRUCK</option>
					</select>
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width: 310px;display:inline-block;">Remark <a href="javascript:void(0)" onclick="sett_rmk_add()">SET</a></span>
					<span style="width: 310px;display:inline-block;">Shipping Mark</span>
				</div>
				<div class="fitem">
					<input style="width: 310px; height: 56px;" name="remark_add" id="remark_add"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,999]'"/>
					<input style="width: 310px; height: 56px;" name="shipp_mark_add" id="shipp_mark_add"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,299]'"/>
				</div>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table align="center" id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:1075px;height:235px;padding:10px 10px; margin:5px;"></table>
		<div id="toolbar_add" style="padding: 5px 5px;">
			<a href="javascript:void(0)" id="search_prf" iconCls="icon-search" class="easyui-linkbutton" onclick="searchPRF()">ADD ITEM FROM PRF</a>
			<a href="javascript:void(0)" id="add_po_add" iconCls='icon-add' class="easyui-linkbutton" onclick="add_item_add()">ADD ITEM</a>
			<a href="javascript:void(0)" id="remove_po_add" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_po_add()">REMOVE ITEM</a>
		</div>
		<div id="dlg_addItem" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons-addItem" data-options="modal:true">
			<table id="dg_addItem" class="easyui-datagrid" toolbar="#toolbar_addItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>

		<div id="toolbar_addItem" style="padding: 5px 5px;">
			<span style="width:80px;display:inline-block;">Search By</span>
			<select style="width:85px;" name="cmb_search" id="cmb_search" class="easyui-combobox" data-options="panelHeight:'70px'">
				<option value="ITEM_NO" selected="">ITEM NO</option>
				<option value="DESCRIPTION">DESCRIPTION</option>
			</select>
			<input style="width:200px;height: 20px;border-radius: 4px;" name="s_item_add" id="s_item_add" onkeypress="sch_item_add(event)"/>
			<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_item_add()">SEARCH ITEM</a>
		</div>
		<div id="dlg_prf_add" class="easyui-dialog" style="width: 880px;height: 300px;" closed="true" buttons="#dlg-buttons-prf_add" data-options="modal:true">
			<table id="dg_prf_add" class="easyui-datagrid" style="width:100%;height:200px;border-radius: 10px;" rownumbers="true" ></table>
		</div>
		<div id="dlg-buttons-prf_add">
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="select_prf()" style="width:90px">SELECT</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_prf_add').dialog('close')" style="width:90px">Cancel</a>	
		</div>	

		<div id="dlg_remark_add" class="easyui-dialog" style="width: 450px;height: 250px;" closed="true" data-options="modal:true">
			<table id="dg_remark_add" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true"></table>
		</div>

		<div id="dlg-buttons-add">
			<input class="easyui-linkbutton c6" iconCls="icon-ok" type="submit" value="Save" onclick="savePo()" style="width:90px; height: 30px;">
			<!-- <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePo()" style="width:90px">Save</a> -->
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
		</div>
	  </form>
	</div>
	<!-- END ADD -->

	<!-- EDIT -->
	<div id='dlg_edit' class="easyui-dialog" style="width:1100px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
	  <form id="ff_edit" method="post" novalidate>	
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend> Select Supplier </legend>
			<div class="fitem">
				<span style="width:60px;display:inline-block;">Supplier</span>
				<select style="width:310px;" name="supplier_edit" id="supplier_edit" class="easyui-combobox" data-options=" url:'json/json_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
				onSelect: function(rec){
					//alert(rec.company_code);
					$('#add_po_edit').linkbutton('enable');
					$('#remove_po_edit').linkbutton('enable');
					$('#search_prf_edit').linkbutton('enable');
					$.ajax({
						type: 'GET',
						url: 'json/json_company_details.php?id='+rec.company_code,
						data: { kode:'kode' },
						success: function(data){
							$('#country_add').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].COUNTRY);
							$('#payterms_add').textbox('setValue',data[0].PDAYS+'-'+data[0].PDESC);
							$('#attn_edit').textbox('setValue',data[0].ATTN);
							$('#curr_edit').combobox('setValue',data[0].CURR_CODE);
							$('#rate_edit').textbox('setValue',data[0].x_rate);
							$('#ship_edit').combobox('setValue',data[0].SHIPTO_CODE);
							$('#trade_edit').textbox('setValue',data[0].TTERM);
							$('#rate_edit').textbox('setValue',data[0].EX_RATE);
							$('#comment').textbox('setValue','*): LATEST PO: '+data[0].PO_NO+' ('+data[0].PO_DATE+')');
						}
					})
				}" disabled="true" ></select>
				<span style="width:10px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Payment Terms</span>
				<input style="width:360px;" name="payterms_edit" id="payterms_edit" class="easyui-textbox" disabled="" />
				<input style="width:160px;" name="country_edit" id="country_edit" class="easyui-textbox" disabled="" />
			</div>
			<div class="fitem">
				<span style="width:60px;display:inline-block;">Currency</span>
				<input style="width:85px;" id="curr_edit" class="easyui-combobox" data-options=" url:'json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'100px',
	        	onSelect: function(rec){
	        		$.ajax({
	        			type: 'GET',
						url: 'json/json_exrate.php?curr='+rec.idcrc,
						data: { kode:'kode' },
						success: function(data){
							$('#rate_edit').textbox('setValue',data[0].RATE);	
						}
	        		});
	        	}"/>
	        	<span style="width:14px;display:inline-block;"></span>
				<span style="width:60px;display:inline-block;">Ex. Rate</span>
				<input style="width:140px;" name="rate_edit" id="rate_edit" class="easyui-textbox" disabled="" />
				<span style="width:10px;display:inline-block;"></span>
				<span style="width:50px;display:inline-block;">ATTN</span>
				<input style="width:140px;" name="attn_edit" id="attn_edit" class="easyui-textbox" disabled="" />
				<span style="width:50px;display:inline-block;">Ship To</span>
				<select style="width:185px;" name="ship_edit" id="ship_edit" class="easyui-combobox" data-options=" url:'json/json_ship_to.php', method:'get', valueField:'com_code', textField:'com_name', panelHeight:'150px'" disabled=""></select>
				<span style="width:40px;display:inline-block;">Trade</span>
				<input style="width:142px;" name="trade_edit" id="trade_edit" class="easyui-textbox" disabled="" />
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend>Create PO</legend>
			<div style="width:395px; height: 80px; float:left;">	
				<div class="fitem">
					<input style="width:375px;color: red" name="comment_edit" id="comment_edit" class="easyui-textbox" disabled="" />
				</div>
				<div class="fitem">
					<span style="width:50px;display:inline-block;">PO No.</span>
					<input style="width:163px;" name="po_no_edit" id="po_no_edit" class="easyui-textbox" disabled=""/>
					<span style="width:5px;display:inline-block;"></span>
					<span style="width:55px;display:inline-block;">PO Date</span>
					<input style="width:85px;" name="po_date_edit" id="po_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				</div>
				<div class="fitem">
					<span style="width:50px;display:inline-block;">DI Type</span>
					<select style="width:75px;" name="di_type_edit" id="di_type_edit" class="easyui-combobox" data-options="panelHeight:'40px'" required="">
						<option value="0" selected="">No</option>
						<option value="1">Yes</option>
					</select>
					<span style="width:88px;display:inline-block;"></span>
					<span style="width:60px;display:inline-block;">Transport</span>
					<select style="width:85px;" name="trans_edit" id="trans_edit" class="easyui-combobox" data-options="panelHeight:'70px'">
						<option selected="" value=""></option>
						<option value="1">AIR</option>
						<option value="2">OCEAN</option>
						<option value="3">TRUCK</option>
					</select>
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width: 205px;display:inline-block;">Remark <a href="javascript:void(0)" onclick="sett_rmk_edit()">SET</a></span>
					<span style="width: 205px;display:inline-block;">Shipping Mark</span>
					<span style="width: 205px;display:inline-block;">Revised PO&nbsp; 
					<input type="checkbox" name="ck_revisi" id="ck_revisi"></input>
					</span>
					
				</div>
				<div class="fitem">
					<input style="width: 205px; height: 56px;" name="remark_edit" id="remark_edit"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,999]'"/>
					<input style="width: 205px; height: 56px;" name="shipp_mark_edit" id="shipp_mark_edit"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,299]'"/>
					<input style="width: 205px; height: 56px;" name="revisi_edit" id="revisi_edit"  multiline="true" class="easyui-textbox"/>
				</div>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table align="center" id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:1075px;height:235px;padding:10px 10px; margin:5px;"></table>
		<div id="toolbar_edit" style="padding: 5px 5px;">
			<a href="javascript:void(0)" id="search_prf" iconCls="icon-search" class="easyui-linkbutton" onclick="searchPRF_edit()">ADD ITEM FROM PRF</a>
			<a href="javascript:void(0)" id="add_po_edit" iconCls='icon-add' class="easyui-linkbutton" onclick="add_item_edit()">ADD ITEM</a>
			<a href="javascript:void(0)" id="remove_po_edit" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_po_edit()">REMOVE ITEM</a>
			<a href="javascript:void(0)" id="remove_po_edit" iconCls='icon-cut' class="easyui-linkbutton" onclick="parsial_po_edit()">PARSIAL ITEM</a>
		</div>
		<div id="dlg_editItem" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons-editItem" data-options="modal:true">
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
		<div id="dlg_prf_edit" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons-prf_edit" data-options="modal:true">
			<table id="dg_prf_edit" class="easyui-datagrid" style="width:100%;height:200px;border-radius: 10px;" rownumbers="true"></table>
		</div>
		<div id="dlg-buttons-prf_edit">
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="select_prf_edit()" style="width:90px">SELECT</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_prf_edit').dialog('close')" style="width:90px">Cancel</a>	
		</div>
		<div id="dlg_remark_edit" class="easyui-dialog" style="width: 450px;height: 250px;" closed="true" data-options="modal:true">
			<table id="dg_remark_edit" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>
		<div id="dlg-buttons-edit">
			<input class="easyui-linkbutton c6" iconCls="icon-ok" type="submit_edit" value="Save" onclick="save_editPo()" style="width:90px; height: 30px;">
			<!-- <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_editPo()" style="width:90px">Save</a> -->
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
		</div>
	  </form>
	</div>
	<!-- END EDIT -->

	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:940px; height:100px; float:left;"><legend>Purchase Order Filter</legend>
			<div style="width:470px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">PO Date</span>
					<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"  value="<?date();?>" />
					to 
					<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"  value="<?date();?>" />
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">PO No.</span>
					<select style="width:300px;" name="cmb_po_no" id="cmb_po_no" class="easyui-combobox" data-options=" url:'json/json_pono.php',method:'get',valueField:'po_no',textField:'po_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_po" id="ck_po" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Supplier</span>
					<select style="width:300px;" name="supplier" id="supplier" class="easyui-combobox" data-options=" url:'json/json_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_supplier" id="ck_supplier" checked="true">All</input></label>
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Item No.</span>
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
					<span style="width:80px;display:inline-block;">Item Name</span>
					<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">ETA</span>
					<input style="width:100px;" name="date_eta" id="date_eta" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
					<label><input type="checkbox" name="ck_eta" id="ck_eta" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; height:100px; margin-left:965px;"><legend>Purchase Order Report</legend>
		  <div align="center">
			   <a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:140px"> Print PO Status</a>
			   <a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_xls()" iconCls="icon-excel" plain="true" style="width:140px"> Print PO Status</a>
		  </div>
		  <div class="fitem" align="center">
			   <a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="po_receive()" iconCls="icon-print" plain="true" style="width:285px"> Purchase Order Print</a>
		  </div>
		</fieldset>
		<div style="clear:both;"></div>
		<div style="margin-top: 5px;margin: 5px;">
			<span style="width:50px;display:inline-block;">search</span> 
			<input style="width:150px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" placeholder="Search PO No." autofocus="autofocus" />
			<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" id="add" onclick="addPo()"><i class="fa fa-plus" aria-hidden="true"></i> Add PO</a>
			<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" id="edit" onclick="editPo()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit PO</a>
			<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" id="delete" onclick="destroyPo()"><i class="fa fa-trash" aria-hidden="true"></i> Delete PO</a>
			<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" id="print" onclick="printPo()"><i class="fa fa-print" aria-hidden="true"></i> Print PO</a>
		</div>
	</div>
	<table id="dg" title="PURCHASE ORDER" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;"></table>

	<div id="dlg_print" class="easyui-dialog" style="width:400px;padding:10px 20px" closed="true" buttons="#dlg-buttons-print" data-options="modal:true">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:325px;"><legend>Order by</legend>
			<div class="fitem">
				<span style="width:100px;display:inline-block;"><input type="radio" name="status_order" id="check_line" value="check_line"/>LINE</span>
				<span style="width:100px;display:inline-block;"><input type="radio" name="status_order" id="check_eta" value="check_eta"/>ETA</span>
			</div>
		</fieldset>
	</div>
	<div id="dlg-buttons-print">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="printpdf()" style="width:90px">Print</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_print').dialog('close')" style="width:90px">Cancel</a>
	</div>

	<div id="dlg_print_rec" class="easyui-dialog" style="width: 1050px;height: 210px;" closed="true" buttons="#dlg-buttons-print-rec" data-options="modal:true">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:1000px; height:110px; float:left;"><legend>Purchase Order Filter</legend>
			<div style="width:470px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Date</span>
					<span style="width:50px;display:inline-block;"><input type="radio" name="status_date" id="check_po" value="check_po"/> PO</span>
				    <span style="width:50px;display:inline-block;"><input type="radio" name="status_date" id="check_eta" value="check_eta"/> ETA</span>

					<input style="width:85px;" name="date_a" id="date_a" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
					to 
					<input style="width:85px;" name="date_z" id="date_z" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
					<label><input type="checkbox" name="ck_date_rec" id="ck_date_rec" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">PO No.</span>
					<select style="width:300px;" name="cmb_po_no_rec" id="cmb_po_no_rec" class="easyui-combobox" data-options=" url:'json/json_pono.php',method:'get',valueField:'po_no',textField:'po_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_po_rec" id="ck_po_rec" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">END ORDER</span>
					<label><input type="checkbox" name="ck_endorder_rec" id="ck_endorder_rec" >VIEW ENDORDER</input></label>
				</div>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Supplier</span>
				<select style="width:330px;" name="supplier_rec" id="supplier_rec" class="easyui-combobox" data-options=" url:'json/json_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_supplier_rec" id="ck_supplier_rec" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Item No.</span>
				<input style="width:120px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onchange="filterREC()" name="src_item" id="src_item" type="text"/>
				<select style="width:203px;" name="cmb_item_name_rec" id="cmb_item_name_rec" class="easyui-combobox" disabled="true"></select>
				<label><input type="checkbox" name="ck_item_no_rec" id="ck_item_no_rec" checked="true">All</input></label>
			</div>
		</fieldset>
	</div>
	<div id="dlg-buttons-print-rec">
		<!-- <a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_receive_pdf()" iconCls="icon-pdf" plain="true" style="width:140px"> Print PO Receive</a> -->
		<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_receive_xls()" iconCls="icon-excel" plain="true" style="width:140px"> Print PO Receive</a>
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

				$('#dg').datagrid('enableFilter');

				if (src=='') {
					filterData();
				};
				//document.getElementById('src').value = '';
		    }
		}

		function filterREC(){
			var x = document.getElementById('src_item').value;
			var j = x.toUpperCase();
			//alert(j);
		    $('#cmb_item_name_rec').combobox({
				url:'json/json_item_all2.php?item='+j,
				valueField:'id_item',
				textField:'name_item'
			});
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
			var search = sch_e.toUpperCase();
			document.getElementById('s_item_edit').value = search;
			
		    if(event.keyCode == 13 || event.which == 13){
				search_item_edit();
		    }
		}

		function cari_prf(){
			alert ('cari prf nya..!!');
		}

		var url_pdf='';

		function access_log(){
			//ADD//UPDATE/T
			//DELETE/T
			//PRINT/T

			var add = "<?=$exp[0]?>";
			var upd = "<?=$exp[1]?>";
			var del = "<?=$exp[2]?>";
			var prn = "<?=$exp[4]?>";

			if (add == 'ADD/T'){
				$('#add').linkbutton('enable');
			}else{
				$('#add').linkbutton('disable');
			}

			if (upd == 'UPDATE/T'){
				$('#edit').linkbutton('enable');
			}else{
				$('#edit').linkbutton('disable');
			}

			if (del == 'DELETE/T'){
				$('#delete').linkbutton('enable');
			}else{
				$('#delete').linkbutton('disable');
			}

			if (prn == 'PRINT/T'){
				$('#print').linkbutton('enable');
			}else{
				$('#print').linkbutton('disable');
			}			
		}

		$(function(){
			access_log();
			$('#date_awal').datebox('disable');
			$('#date_akhir').datebox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_awal').datebox('disable');
					$('#date_akhir').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_awal').datebox('enable');
					$('#date_akhir').datebox('enable');
				};
			})			

			$('#cmb_po_no').combobox('disable');
			$('#ck_po').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_po_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_po_no').combobox('enable');
				};
			})

			$('#supplier').combobox('disable');
			$('#ck_supplier').change(function(){
				if ($(this).is(':checked')) {
					$('#supplier').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#supplier').combobox('enable');
				};
			})

			$('#cmb_item_no').combobox('disable');
			$('#ck_item_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_item_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_item_no').combobox('enable');
				};
			})

			$('#date_eta').datebox('disable');
			$('#ck_eta').change(function(){
				if ($(this).is(':checked')) {
					$('#date_eta').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_eta').datebox('enable');
				};
			})

			$('#dg').datagrid({
		    	url:'po_get.php',
		    	singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
				sortName: 'po_date',
				sortOrder: 'asc',
			    columns:[[
				    {field:'PO_NO',title:'PO NO.',width:75, halign: 'center', sortable:true},
				    {field:'PO_DATE',title:'PO DATE',width:65, halign: 'center', align: 'center', sortable:true},
				    {field:'COMPANY',title:'SUPPLIER',width:200, halign: 'center', sortable:true},
				    {field:'O',title:'AMOUNT',width:80, halign: 'center', align:'right', sortable:true},
				    {field:'CURR_SHORT',title:'CURRENCY',width:60, halign: 'center', align: 'center',sortable:true},
				    {field:'EX_RATE',title:'EXCHANGE<br/>RATE',width:80, halign: 'center', align:'right', sortable:true},
				    {field:'REMARK1',title:'REMARK', width:200, halign: 'center', hidden:true},
				    {field:'REMARK1_2',title:'REMARK', width:200, halign: 'center'},
				    {field:'REQ',title:'USER ENTRY',width:110, halign: 'center', align: 'left',sortable:true,hidden:true},
				    {field:'REQ_2',title:'USER ENTRY',width:110, halign: 'center', align: 'left',sortable:true},
				    {field:'SUPPLIER_CODE', hidden: true},
				    {field:'DI_OUTPUT_TYPE', hidden: true},
				    {field:'REVISE', hidden: true},
				    {field:'REASON1', hidden: true}
			    ]],
			    view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
				},
				onExpandRow: function(index,row){
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
					listbrg.datagrid({
	                	title: 'PO Detail (No: '+row.PO_NO+')',
	                	url:'po_get_detail.php?po='+row.PO_NO,
						toolbar: '#ddv'+index,
						singleSelect:true,
						loadMsg:'load data ...',
						height:'auto',
						fitColumns: true,
						columns:[[
							{field:'LINE_NO',title:'LINE NO.',width:50,halign:'center', align:'center'},
			                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:60, sortable: true},
			                {field:'DESCRIPTION', title:'Material Name', halign:'center', width:200},
			                {field:'UOM_Q', hidden: true},
			                {field:'UNIT_PL', title:'UoM', halign:'center', align:'center', width:40},
			                {field:'ETA', title:'E T A', halign:'center', align:'center', width:60},
			                {field:'QTY', title:'PURCHASE<br/>QTY', halign:'center', align:'right', width:70},
			                {field:'GR_QTY', title:'RECEIVE<br/>QTY', halign:'center', align:'right', width:70},
			                {field:'BAL_QTY', title:'BALANCE<br/>QTY', halign:'center', align:'right', width:70},
			                {field:'U_PRICE', title:'PRICE', halign:'center', align:'right', width:50},
			                {field:'AMT_O', title:'AMOUNT(ORG)', halign:'center', align:'right', width:70},
			                {field:'AMT_L', title:'AMOUNT(LOC)', halign:'center', align:'right', width:70},
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
		});

		function filterData(){
			var ck_date='false';
			var ck_supplier='false';
			var ck_item_no='false';
			var ck_po='false';
			var ck_eta='false';
			var flag = 0;

			if($('#ck_date').attr("checked")){
				ck_date='true';
				flag += 1;
			}

			if($('#ck_supplier').attr("checked")){
				ck_supplier='true';
				flag += 1;
			}
			if($('#ck_item_no').attr("checked")){
				ck_item_no='true';
				flag += 1;
			}
			if($('#ck_po').attr("checked")){
				ck_po='true';
				flag += 1;
			}

			if($('#ck_eta').attr("checked")){
				ck_eta='true';
				flag += 1;
			}

			if(flag == 5) {
				$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
			}
			
			$('#dg').datagrid('load', {
				date_awal: $('#date_awal').datebox('getValue'),
				date_akhir: $('#date_akhir').datebox('getValue'),
				ck_date: ck_date,
				supplier: $('#supplier').combobox('getValue'),
				ck_supplier: ck_supplier,
				cmb_item_no: $('#cmb_item_no').combobox('getValue'),
				ck_item_no: ck_item_no,
				cmb_po: $('#cmb_po_no').combobox('getValue'),
				ck_po: ck_po,
				date_eta: $('#date_eta').datebox('getValue'),
				ck_eta: ck_eta,
				src: ''
			});

		   	$('#dg').datagrid('enableFilter');

		   	url_pdf ="?date_awal="+$('#date_awal').datebox('getValue')+"&date_akhir="+$('#date_akhir').datebox('getValue')+"&ck_date="+ck_date+"&supplier="+$('#supplier').combobox('getValue')+"&supplier_nm="+$('#supplier').combobox('getText')+"&ck_supplier="+ck_supplier+"&cmb_item_no="+$('#cmb_item_no').combobox('getValue')+"&ck_item_no="+ck_item_no+"&cmb_po="+$('#cmb_po_no').combobox('getValue')+"&ck_po="+ck_po+"&date_eta="+$('#date_eta').datebox('getValue')+"&ck_eta="+ck_eta+"&txt_item_name="+$('#txt_item_name').textbox('getValue') ;
		}

		function addPo(){
			$('#dlg_add').dialog('open').dialog('setTitle','Create Purchase Order');
			$('#add_po_add').linkbutton('disable');
			$('#remove_po_add').linkbutton('disable');
			$('#search_prf').linkbutton('disable');
			$('#supplier_add').combobox('setValue','');
			$('#payterms_add').textbox('setValue','');
			$('#country_add').textbox('setValue','');
			$('#ship_add').textbox('setValue','');
			$('#attn_add').textbox('setValue','');
			$('#trade_add').textbox('setValue','');
			$('#curr_add').textbox('setValue','');
			$('#rate_add').textbox('setValue','');
			$('#comment').textbox('setValue','');
			$('#po_no_add').textbox('setValue','');
			$('#trans_add').combobox('setValue','');
			$('#remark_add').textbox('setValue','');
			$('#shipp_mark_add').textbox('setValue','');
			$('#dg_add').datagrid('loadData',[]);

			$('#dg_add').datagrid({
			    singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
			    columns:[[
				    {field:'ITEM_NO', title:'ITEM NO.', width:65, halign: 'center', align: 'center'},
				    {field:'ITEM', title:'ITEM NAME', width:100, halign: 'center'},//, hidden: true},
				    {field:'DESCRIPTION', title:'DESCRIPTION', width: 150, halign: 'center'},
				    {field:'ITEM_FULL', title:'ITEM', width: 150, halign: 'center', hidden: true},//},
				    {field:'UNIT', title:'UoM', halign: 'center', width:45, align:'center'},
				    {field:'COUNTRY', title:'ORIGN', halign: 'center', width:80,},
				    {field:'QTY', title:'QTY', align:'right', halign: 'center', width:70, editor:{type:'numberbox',options:{required:true,precision:2,groupSeparator:','}}},
				    {field:'ESTIMATE_PRICE', title:'PRICE', halign: 'center', width:80, align:'right', editor:{type:'textbox',options:{required:true,precision:5,groupSeparator:','}}},
				    {field:'CURR_SHORT', title:'CURR', halign: 'center', width:50, editor:{type:'combobox',options: {
				    																									required:true,
				    																									url: 'json/json_currency.php',
				    																									panelHeight: '100px',
																									                    valueField: 'idcrc',
																									                    textField: 'nmcrc'
				    																								}
				    																	  }
				    },
				    {field:'ETA_DATE', title: 'E.T.A FI', halign: 'center', width: 80, editor:{
				    																	type:'datebox',
				    																	options:{formatter:myformatter,parser:myparser}
				    																}
				    },
				    {field:'OHSAS', title:'DATE CODE', halign: 'center', width:100, align:'center', editor:{
				    																					type:'textbox'
				    																				}
				    },
				    {field:'PRF_NOMOR', title:'PRF NO', halign: 'center', width:100, align:'center'},
				    {field:'PRF_NO', hidden: true},
				    {field:'PRF_LINE_NO', title:'PRF<br/>LINE NO', halign: 'center', width:50, align:'center'},
				    {field:'ORIGIN_CODE', hidden:true},
				    {field:'UOM_Q', hidden:true}
			    ]],
			    onClickRow:function(row){
			    	$(this).datagrid('beginEdit', row);
			    }
			});
		}

		function sett_rmk_add(){
			$('#dlg_remark_add').dialog('open').dialog('setTitle','Master Remark');
			$('#dg_remark_add').datagrid({
				url: 'po_getRemark_add.php',
				fitColumns: true,
				columns:[[
	                {field:'REMARK_TYPE',title:'TYPE',width:65,halign:'center', align:'center'},
	                {field:'REMARK_DESCRIPTION',title:'DESCRIPTION',width:300,halign:'center'}
	            ]],
	            onClickRow:function(id,row){
	            	var ids = [];
					var rows = $('#dg_remark_add').datagrid('getSelections');
					for(var i=0; i<rows.length; i++){
						ids.push(rows[i].REMARK_DESCRIPTION+"\n");
					}
					//alert(ids.join('\n'));
	            	$('#remark_add').textbox('setValue',ids.join("\n"));
				}
			});
		}

		function searchPRF(){
			var supp_id = $('#supplier_add').combobox('getValue');
			$('#dlg_prf_add').dialog('open').dialog('setTitle','Search Item By PRF');
			$('#dg_prf_add').datagrid('load',{supp: supp_id});
			var dg = $('#dg_prf_add').datagrid();
			dg.datagrid('enableFilter');

			$('#dg_prf_add').datagrid({
				url: 'po_getPRF.php',
				columns:[[
					/*{field:'CK',align:'center', width:50, title:'CHECK', halign: 'center',editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},*/
	                {field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM',title:'ITEM',width:100,halign:'center'},
	                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
	                {field:'PRF_NOMOR',title:'PRF NO.',width:120,halign:'center'},
	                {field:'PRF_DATE',title:'PRF DATE',width:80,halign:'center'},
	                {field:'LINE_NO',title:'LINE',width:50,halign:'center', align:'center'},
	                {field:'REQUIRE_DATE',title:'REQUIRE<br/>DATE',width:80,halign:'center',align:'center'},
	                {field:'QTY',title:'QTY',width:100,halign:'center',align:'right'},
	                {field:'REMAINDER_QTY',title:'REMAIN',width:100,halign:'center',align:'right'},
	                {field:'UNIT',title:'UoM',width:65,halign:'center', align:'center'},
	                {field:'CURR_SHORT',title:'CURR',width:65,halign:'center', align:'center'},
	                {field:'ESTIMATE_PRICE', hidden:true},
	                {field:'ORIGIN_CODE', hidden:true},
	                {field:'UOM_Q', hidden:true},
	                {field:'OHSAS', hidden:true}
	            ]]/*,
	            onSelect:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    },
	            onClickRow:function(id,row){
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=0;
					}else{
						idxfield=total+1;
						for (i=0; i < total; i++) {
							//alert(i);
							var prf = $('#dg_add').datagrid('getData').rows[i].PRF_NOMOR;
							var prf_ln = $('#dg_add').datagrid('getData').rows[i].PRF_LINE_NO;
							//alert(item);
							if (prf==row.PRF_NOMOR && prf_ln==row.LINE_NO) {
								count++;
							};
						};
					}

					if (count>0) {
						$.messager.alert('Warning','PRF present','warning');
					}else{
						$('#dg_add').datagrid('insertRow',{
							index: idxfield,
							row: {
								ITEM_NO: row.ITEM_NO,
								DESCRIPTION: row.DESCRIPTION,
								ITEM: row.ITEM,
								UOM_Q: row.UOM_Q,
								UNIT: row.UNIT,
								ORIGIN_CODE: row.ORIGIN_CODE,
								COUNTRY: row.COUNTRY,
								CURR_CODE: row.CURR_CODE,
								CURR_SHORT: row.CURR_SHORT,
								ESTIMATE_PRICE: row.ESTIMATE_PRICE,
								PRF_NO: row.PRF_NOMOR,
								PRF_NOMOR: row.PRF_NOMOR,
								QTY: row.REMAINDER_QTY,
								ETA_DATE: row.REQUIRE_DATE,
								PRF_LINE_NO:row.LINE_NO,
								OHSAS: row.OHSAS
							}
						});
					}
				}*/
			});

			var dg = $('#dg_prf_add').datagrid();
			dg.datagrid('enableFilter');
		}

		function select_prf(){
			var rows = $('#dg_prf_add').datagrid('getSelections');
			var i = 0;
			for(var i=0; i<rows.length; i++){
				var t = $('#dg_add').datagrid('getRows');
				var total = t.length;
			   	var idxfield=0;
			   	var j = 0;
			   	var count = 0;
				if (parseInt(total) == 0) {
					idxfield=0;
				}else{
					idxfield=total+1;
					for (j=0; j < total; j++) {
						var prf = $('#dg_add').datagrid('getData').rows[j].PRF_NOMOR;
						var prf_ln = $('#dg_add').datagrid('getData').rows[j].PRF_LINE_NO;
						if (prf==rows[i].PRF_NOMOR && prf_ln==rows[i].LINE_NO){
							count++;
						};
					};
				}

				if (count>0) {
					$.messager.alert('Warning','PRF present','warning');
				}else{
					$('#dg_add').datagrid('insertRow',{
						index: idxfield,
						row: {
							ITEM_NO: rows[i].ITEM_NO,
							DESCRIPTION: rows[i].DESCRIPTION,
							ITEM: rows[i].ITEM,
							UOM_Q: rows[i].UOM_Q,
							UNIT: rows[i].UNIT,
							ORIGIN_CODE: rows[i].ORIGIN_CODE,
							COUNTRY: rows[i].COUNTRY,
							CURR_CODE: rows[i].CURR_CODE,
							CURR_SHORT: rows[i].CURR_SHORT,
							ESTIMATE_PRICE: rows[i].ESTIMATE_PRICE,
							PRF_NO: rows[i].PRF_NOMOR,
							PRF_NOMOR: rows[i].PRF_NOMOR,
							QTY: rows[i].REMAINDER_QTY,
							ETA_DATE: rows[i].REQUIRE_DATE,
							PRF_LINE_NO:rows[i].LINE_NO,
							OHSAS: rows[i].OHSAS
						}
					});
				}
			}
		}

		function add_item_add(){
			$('#dlg_addItem').dialog('open').dialog('setTitle','Search Item');
			$('#cmb_search').combobox('setValue','ITEM_NO');
			$('#dg_addItem').datagrid('load',{supp: '', curr: '', item: '', by: ''});
			$('#dg_addItem').datagrid({
				fitColumns: true,
				columns:[[
					{field:'BUYING_PRICE',title:'PRICE',width:80,halign:'center', align:'right'},
	                {field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM',title:'ITEM',width:100,halign:'center'},
	                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
	                {field:'DRAWING_NO',title:'DRW NO',width:80,halign:'center'},
	                {field:'COUNTRY',title:'ORIGN',width:100,halign:'center'},
	                {field:'ORIGIN_CODE', hidden:true},
	                {field:'ESTIMATE_PRICE', hidden:true},
	                {field:'CURR_CODE', hidden:true},
	                {field:'CURR_SHORT', hidden:true},
	                {field:'ORIGIN_CODE', hidden:true},
	                {field:'UOM_Q', hidden:true},
	                {field:'UNIT', hidden:true},
	                {field:'PRF_NO', hidden:true}
	            ]],
	            onDblClickRow:function(id,row){
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=0;
					}else{
						idxfield=total;
					}
					
					$('#dg_add').datagrid('insertRow',{
						index: idxfield,
						row: {
							ITEM_NO: row.ITEM_NO,
							DESCRIPTION: row.DESCRIPTION,
							ITEM: row.ITEM,
							UOM_Q: row.UOM_Q,
							UNIT: row.UNIT,
							ORIGIN_CODE: row.ORIGIN_CODE,
							COUNTRY: row.COUNTRY,
							CURR_CODE: row.CURR_CODE,
							CURR_SHORT: row.CURR_SHORT,
							ESTIMATE_PRICE: row.ESTIMATE_PRICE,
							PRF_NO: row.PRF_NO
						}
					});
				}
			});

			$('#dg_addItem').datagrid('loadData',[]);
		}

		function search_item_add(){
			var supp_id = $('#supplier_add').combobox('getValue');
			var crc = $('#curr_add').combobox('getValue');
			var s_by = $('#cmb_search').combobox('getValue');
			var s_item = document.getElementById('s_item_add').value;

			if(s_item != ''){
				$.ajax({
					type: 'GET',
					url: 'json/json_cek_item_no.php?item='+s_item,
					data: { kode:'kode' },
					success: function(data){
						if(data[0].kode=='YES'){
							$.messager.alert('Warning','Item no. is MRP System ..','warning');
						}else{
							$('#dg_addItem').datagrid('load',{supp: supp_id, curr: crc, item: s_item, by: s_by});
							$('#dg_addItem').datagrid({url: 'po_getItem.php',});
						}
					}
				});

				document.getElementById('s_item_add').value = '';
				/*var dg = $('#dg_addItem').datagrid();
				dg.datagrid('enableFilter');*/

				/*$('#dg_addItem').datagrid('load',{supp: supp_id, curr: crc, item: s_item, by: s_by});
				$('#dg_addItem').datagrid({url: 'po_getItem.php',});
				document.getElementById('s_item_add').value = '';*/

				/*var dg = $('#dg_addItem').datagrid();
				dg.datagrid('enableFilter');*/
			}
		}

		function remove_po_add(){
			var row = $('#dg_add').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						var idx = $("#dg_add").datagrid("getRowIndex", row);
						$('#dg_add').datagrid('deleteRow', idx);
					}	
				});
			}
		}

		$('#ff').form({
	        onSubmit:function(){
	            return $(this).form('validate');
	            savePo();
	        }
	    });

	    $('#ff_edit').form({
	        onSubmit:function(){
	            return $(this).form('validate');
	            save_editPo();
	        }
	    });

	    function validate(value){
			var hasil=0;
			var msg='';
			$.get("../connect/conn.php");
			var conn = "<?=$varConn?>";
			if(conn=='N'){
				msg = $.messager.alert('Warning','CONNECT TO SERVER FAILED ... !!','warning');
				hasil=1;
			}else{
				if(value == 'add'){
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;

					if($('#supplier_add').combobox('getValue')==''){
						msg = $.messager.alert('Warning','Please select supplier','warning');
						hasil=1;
					}else if($('#po_no_add').textbox('getValue')==''){
						msg = $.messager.alert('INFORMATION','PO No. Not Found','info');
						hasil=1;
					}else if($('#remark_add').textbox('getValue').length >= 999){
						msg = $.messager.alert('INFORMATION','Please enter a value remark between 0 and 999','info');
						hasil=1;
					}else if($('#shipp_mark_add').textbox('getValue').length >= 299){
						msg = $.messager.alert('INFORMATION','Please enter a value shipping mark between 0 and 299','info');
						hasil=1;
					}else if ($('#trans_add').combobox('getValue')=='') {
						msg = $.messager.alert('Warning','Please select Transport','warning');
						hasil=1;
					}else if(total == 0){
						msg = $.messager.alert('INFORMATION','Item No. not selected','info');
						hasil=1;
					}

					for(i2=0;i2<total;i2++){
						$('#dg_add').datagrid('endEdit',i2);
						if ($('#dg_add').datagrid('getData').rows[i2].ESTIMATE_PRICE == 0){
							msg = $.messager.alert('INFORMATION','Price not Found','info');
							hasil=1;		
						}else if($('#dg_add').datagrid('getData').rows[i2].QTY <= 0){
							msg = $.messager.alert('INFORMATION','QTY you input is wrong','info');
							hasil=1;
						}
					}
				}else if(value == 'edit'){
					var t2 = $('#dg_edit').datagrid('getRows');
					var total2 = t2.length;

					if($('#supplier_edit').combobox('getValue')==''){
						msg = $.messager.alert('Warning','Please select supplier','warning');
						hasil=1;
					}else if($('#po_no_edit').textbox('getValue')==''){
						msg = $.messager.alert('INFORMATION','PO No. Not Found','info');
						hasil=1;
					}else if($('#remark_edit').textbox('getValue').length >= 999){
						msg = $.messager.alert('INFORMATION','Please enter a value remark between 0 and 999','info');
						hasil=1;
					}else if($('#shipp_mark_edit').textbox('getValue').length >= 299){
						msg = $.messager.alert('INFORMATION','Please enter a value shipping mark between 0 and 299','info');
						hasil=1;
					}else if ($('#trans_edit').combobox('getValue')=='') {
						msg = $.messager.alert('Warning','Please select Transport','warning');
						hasil=1;
					}else if(total2 == 0){
						msg = $.messager.alert('INFORMATION','Item No. not selected','info');
						hasil=1;
					}

					for(i22=0;i22<total2;i22++){
						$('#dg_edit').datagrid('endEdit',i22);
						if ($('#dg_edit').datagrid('getData').rows[i22].ESTIMATE_PRICE == 0){
							msg = $.messager.alert('INFORMATION','Price not Found','info');
							hasil=1;		
						}else if($('#dg_edit').datagrid('getData').rows[i22].QTY <= 0){
							msg = $.messager.alert('INFORMATION','QTY you input is wrong','info');
							hasil=1;
						}
					}
				}
			}
			return hasil;
		}

		function simpan(){
			var dataRows = [];
			var tot_amt_o = 0;
			var tot_amt_l = 0;
			var t = $('#dg_add').datagrid('getRows');
			var total = t.length;
			var jmrow=0;
			for(i=0;i<total;i++){
				var amt_o = 0;
				var amt_l = 0;
				jmrow = i+1;
				$('#dg_add').datagrid('endEdit',i);

				dataRows.push({
					v_from: jmrow,
					v_to: total,
					po_sts: 'DETAILS',
					po_supp: $('#supplier_add').combobox('getValue'),
					po_pterm: $('#payterms_add').textbox('getValue'),
					po_curr: $('#curr_add').combobox('getValue'),
					po_rate: $('#rate_add').textbox('getValue'),
					po_country: $('#country_add').textbox('getValue'),
					po_attn: $('#attn_add').textbox('getValue'),
					po_shipto: $('#ship_add').combobox('getValue'),
					po_tterm: $('#trade_add').textbox('getValue'),
					po_no: $('#po_no_add').textbox('getValue'),
					po_line: jmrow,
					po_date: $('#po_date_add').datebox('getValue'),
					po_di_type: $('#di_type_add').combobox('getValue'),
					po_trans: $('#trans_add').combobox('getValue'),
					po_remark: $('#remark_add').textbox('getValue').replace(/\n/gi,"<br>"),
					po_ship_mark: $('#shipp_mark_add').textbox('getValue').replace(/\n/gi,"<br>"),
					po_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
					po_unit: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
					po_orign: $('#dg_add').datagrid('getData').rows[i].ORIGIN_CODE,
					po_price: $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE,
					po_curr_item: $('#dg_add').datagrid('getData').rows[i].CURR_SHORT,
					po_qty: $('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,''),
					po_eta: $('#dg_add').datagrid('getData').rows[i].ETA_DATE,
					po_prf: $('#dg_add').datagrid('getData').rows[i].PRF_NOMOR,
					po_prf_line: $('#dg_add').datagrid('getData').rows[i].PRF_LINE_NO,
					po_dt_code: $('#dg_add').datagrid('getData').rows[i].OHSAS,
					amt_o: parseFloat($('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,'') * $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE).toFixed(2),
					amt_l: parseFloat($('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,'') * $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE * 
						   $('#rate_add').textbox('getValue')).toFixed(2)
				});

				amt_o = parseFloat($('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,'') * $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE).toFixed(2);
				amt_l = parseFloat($('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,'') * $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE * 
						$('#rate_add').textbox('getValue')).toFixed(2);

				tot_amt_o += parseFloat(amt_o);
				tot_amt_l += parseFloat(amt_l);

				if(i==total-1){
					//$.post('po_save.php',{
					dataRows.push({
						po_sts: 'HEADER',
						po_supp: $('#supplier_add').combobox('getValue'),
						po_pterm: $('#payterms_add').textbox('getValue'),
						po_curr: $('#curr_add').combobox('getValue'),
						po_rate: $('#rate_add').textbox('getValue'),
						po_country: $('#country_add').textbox('getValue'),
						po_attn: $('#attn_add').textbox('getValue'),
						po_shipto: $('#ship_add').combobox('getValue'),
						po_tterm: $('#trade_add').textbox('getValue'),
						po_no: $('#po_no_add').textbox('getValue'),
						po_line: jmrow,
						po_date: $('#po_date_add').datebox('getValue'),
						po_di_type: $('#di_type_add').combobox('getValue'),
						po_trans: $('#trans_add').combobox('getValue'),
						po_remark: $('#remark_add').textbox('getValue').replace(/\n/gi,"<br>"),
						po_ship_mark: $('#shipp_mark_add').textbox('getValue').replace(/\n/gi,"<br>"),
						po_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
						po_unit: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
						po_orign: $('#dg_add').datagrid('getData').rows[i].ORIGIN_CODE,
						po_price: $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE,
						po_curr_item: $('#dg_add').datagrid('getData').rows[i].CURR_SHORT,
						po_qty: $('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,''),
						po_eta: $('#dg_add').datagrid('getData').rows[i].ETA_DATE,
						po_prf: $('#dg_add').datagrid('getData').rows[i].PRF_NOMOR,
						po_prf_line: $('#dg_add').datagrid('getData').rows[i].PRF_LINE_NO,
						po_dt_code: $('#dg_add').datagrid('getData').rows[i].OHSAS,
						amt_o: parseFloat(tot_amt_o).toFixed(2),
						amt_l: parseFloat(tot_amt_l).toFixed(2)
					});
				}
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);

			$.post('po_save.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>PO No. : '+$('#po_no_add').textbox('getValue'),'info');
					$.messager.progress('close');
				}else{
					$.post('po_destroy.php',{po_no: $('#po_no_add').textbox('getValue')},'json');
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}

		function savePo(){
			$.messager.progress({
			    title:'Please waiting',
			    msg:'Save data...'
			});
			var url='';
			var pono = $('#po_no_add').textbox('getValue').trim();
			if (validate('add') != 1){
				$.ajax({
					type: 'GET',
					url: 'json/json_kode_po.php?po='+pono,
					data: { kode:'kode' },
					success: function(data){
						if(data[0].kode=='SUCCESS'){
							simpan();
						}else{
							$.messager.alert('Warning',data[0].kode,'warning');
							$.messager.progress('close');
						}
					}
				});
			}else{
				$.messager.progress('close');
			}
		}

		function editPo(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				$('#dlg_edit').dialog('open').dialog('setTitle','Update Purchase Order (No. '+row.PO_NO+')');
				$('#add_po_edit').linkbutton('enable');
				$('#remove_po_edit').linkbutton('enable');
				$('#search_prf_edit').linkbutton('enable');
				$('#supplier_edit').combobox('setValue',row.SUPPLIER_CODE);
				$('#payterms_edit').textbox('setValue',row.PTERM);
				$('#country_edit').textbox('setValue',row.COUNTRY);
				$('#ship_edit').combobox('setValue',row.SHIPTO_CODE);
				$('#attn_edit').textbox('setValue',row.ATTN);
				$('#trade_edit').textbox('setValue',row.TTERM);
				$('#curr_edit').combobox('setValue',row.CURR_CODE);
				$('#rate_edit').textbox('setValue',row.EX_RATE);
				$('#po_no_edit').textbox('setValue',row.PO_NO);
				$('#po_date_edit').datebox('setValue',row.PO_DATE);
				$('#di_type_edit').combobox('setValue',row.DI_OUTPUT_TYPE);
				$('#trans_edit').combobox('setValue',row.TRANSPORT);
				$('#remark_edit').textbox('setValue',row.REMARK1);
				$('#shipp_mark_edit').textbox('setValue',row.MARKS1);
				if(row.REVISE == 'Y'){
					$('#ck_revisi').attr("checked",true);
				}else{
					$('#ck_revisi').attr("checked",false);
				}
				$('#revisi_edit').textbox('setValue',row.REASON1);
				$('#comment_edit').textbox('setValue','*): LATEST PO:');

				$('#dg_edit').datagrid({
				    url:'po_get_detail_edit.php?po_no='+row.PO_NO,
				    singleSelect: true,
				    fitColumns: true,
					rownumbers: true,
				    columns:[[
					    {field:'ITEM_NO', title:'ITEM NO.', width:65, halign: 'center', align: 'center'},
					    {field:'LINE_NO', title:'LINE NO.', width:65, halign: 'center', align: 'center'},//,hidden: true
					    {field:'ITEM', title:'ITEM NAME', width:100, halign: 'center', hidden: true},
					    {field:'DESCRIPTION', title:'DESCRIPTION', width: 150, halign: 'center'},
					    {field:'ITEM_FULL', title:'ITEM', width: 150, halign: 'center', hidden: true},
					    {field:'UNIT', title:'UoM', halign: 'center', width:45, align:'center'},
					    {field:'COUNTRY', title:'ORIGN', halign: 'center', width:80,},
					    {field:'QTY', title:'PURCHASE<br/>QTY', align:'right', halign: 'center', width:70, editor:{type:'numberbox',options:{required:true,precision:2,groupSeparator:','}}},
					    {field:'GR_QTY', title:'RECEIVE</br>QTY', width:65, halign: 'center', align: 'right'},
					    {field:'BAL_QTY', title:'BALANCE</br>QTY', width:65, halign: 'center', align: 'right'},
					    {field:'ESTIMATE_PRICE', title:'PRICE', halign: 'center', width:80, align:'right', editor:{type:'textbox',options:{required:true,precision:0,groupSeparator:','}}},
					    {field:'CURR_SHORT', title:'CURR', halign: 'center', width:50, editor:{type:'combobox',options: {
					    																									required:true,
					    																									url: 'json/json_currency.php',
					    																									panelHeight: '100px',
																										                    valueField: 'idcrc',
																										                    textField: 'nmcrc'
					    																								}
					    																							}
					    },
					    {field:'ETA_DATE', title: 'E.T.A', halign: 'center', width: 80, editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}},
					    {field:'CARVED_STAMP', title:'DATE CODE', halign: 'center', width:100, align:'center', editor:{
					    																					type:'textbox'
					    																				}
					    },
					    {field:'PRF_NOMOR', title:'PRF NO', halign: 'center', width:100, align:'center'},
					    {field:'PRF_NO', title:'PRF NO', hidden: true},// 
					    {field:'PRF_LINE_NO', title:'PRF<br/>LINE NO', halign: 'center', width:50, align:'center'},
					    {field:'ORIGIN_CODE', hidden:true},
					    {field:'UOM_Q', hidden:true}

				    ]],
				    onClickRow:function(row){
				    	$(this).datagrid('beginEdit', row);
				    }
				});
			}else{
				$.messager.show({title: 'PO EDIT',msg:'Data Not select'});
			}
		}

		function add_item_edit(){
			$('#dlg_editItem').dialog('open').dialog('setTitle','Search Item');
			$('#cmb_search_e').combobox('setValue','ITEM_NO');
			$('#dg_editItem').datagrid('load',{supp: '', curr: '', item: '', by: ''});

			$('#dg_editItem').datagrid({
				url: 'po_getItem.php',
				fitColumns: true,
				columns:[[
					{field:'BUYING_PRICE',title:'PRICE',width:80,halign:'center', align:'right'},
	                {field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM',title:'ITEM',width:100,halign:'center'},
	                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
	                {field:'DRAWING_NO',title:'DRW NO',width:80,halign:'center'},
	                {field:'COUNTRY',title:'ORIGN',width:100,halign:'center'},
	                {field:'ORIGIN_CODE', hidden:true},
	                {field:'ESTIMATE_PRICE', hidden:true},
	                {field:'CURR_CODE', hidden:true},
	                {field:'CURR_SHORT', hidden:true},
	                {field:'ORIGIN_CODE', hidden:true},
	                {field:'UOM_Q', hidden:true},
	                {field:'UNIT', hidden:true},
	                {field:'PRF_NO', hidden:true}
	            ]],
	            onDblClickRow:function(id,row){
					var t = $('#dg_edit').datagrid('getRows');
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=0;
					}else{
						idxfield=total;
					}
					
					$('#dg_edit').datagrid('insertRow',{
						index: idxfield,
						row: {
							ITEM_NO: row.ITEM_NO,
							DESCRIPTION: row.DESCRIPTION,
							ITEM: row.ITEM,
							UOM_Q: row.UOM_Q,
							UNIT: row.UNIT,
							ORIGIN_CODE: row.ORIGIN_CODE,
							COUNTRY: row.COUNTRY,
							CURR_CODE: row.CURR_CODE,
							CURR_SHORT: row.CURR_SHORT,
							ESTIMATE_PRICE: row.ESTIMATE_PRICE,
							PRF_NO: row.PRF_NO,
							LINE_NO: 'NEW',
							GR_QTY: '0',
							BAL_QTY: '0'
						}
					});
				}
			});
			$('#dg_editItem').datagrid('loadData',[]);
		}

		function search_item_edit(){
			var supp_id_e = $('#supplier_edit').combobox('getValue');
			var crc_e = $('#curr_edit').combobox('getValue');
			var s_by = $('#cmb_search_e').combobox('getValue');
			var s_item = document.getElementById('s_item_edit').value;

			if(s_item !=''){
				$.ajax({
					type: 'GET',
					url: 'json/json_cek_item_no.php?item='+s_item,
					data: { kode:'kode' },
					success: function(data){
						if(data[0].kode=='YES'){
							$.messager.alert('Warning','Item no. is MRP System ..','warning');
						}else{
							$('#dg_editItem').datagrid('load',{supp: supp_id_e, curr: crc_e, item: s_item, by: s_by});
							$('#dg_editItem').datagrid({url: 'po_getItem.php',});			
						}
					}
				});

				document.getElementById('s_item_edit').value='';
				
				/*$('#dg_editItem').datagrid('load',{supp: supp_id_e, curr: crc_e, item: s_item, by: s_by});
				$('#dg_editItem').datagrid({url: 'po_getItem.php',});
				
				document.getElementById('s_item_edit').value='';*/
				/*var dg = $('#dg_editItem').datagrid();
				dg.datagrid('enableFilter');*/
			}
		}

		function sett_rmk_edit(){
			$('#dlg_remark_edit').dialog('open').dialog('setTitle','Master Remark');
			$('#dg_remark_edit').datagrid({
				url: 'po_getRemark_add.php',
				fitColumns: true,
				columns:[[
	                {field:'REMARK_TYPE',title:'TYPE',width:65,halign:'center', align:'center'},
	                {field:'REMARK_DESCRIPTION',title:'DESCRIPTION',width:300,halign:'center'}
	            ]],
				onClickRow:function(id,row){
	            	var ids = [];
	            	var arr1 = [];
	            	var re = $('#remark_edit').textbox('getValue');
	            	arr1.push(re);
					var rows = $('#dg_remark_edit').datagrid('getSelections');
					for(var i=0; i<rows.length; i++){
						ids.push("\n"+rows[i].REMARK_DESCRIPTION+"\n");
					}
	            	$('#remark_edit').textbox('setValue',arr1+"\n"+ids.join("\n"));
				}
			});	
		}

		function remove_po_edit(){
			var row = $('#dg_edit').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						if(row.LINE_NO == 'NEW'){
							var idx = $("#dg_edit").datagrid("getRowIndex", row);
							$('#dg_edit').datagrid('deleteRow', idx);
						}else{		
							$.post('po_destroy_dtl.php',{po: $('#po_no_edit').textbox('getValue'), item: row.ITEM_NO, line: row.LINE_NO},function(result){
								if (result.success){
									$('#dg_edit').datagrid('reload');
		                        }else{
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

		function parsial_po_edit(){
			var row = $('#dg_edit').datagrid('getSelected');
			if (row){
				var indx = $('#dg_edit').datagrid('getRowIndex',row);
				var idx = $('#dg_edit').datagrid('getRowIndex', row)+1;
				$('#dg_edit').datagrid('insertRow',{
					index: idx,
					row: {
						ITEM_NO: row.ITEM_NO,
						LINE_NO: 'PARSIAL',
						ITEM: row.ITEM,
						DESCRIPTION: row.DESCRIPTION,
						ITEM_FULL: row.ITEM_FULL,
						UNIT: row.UNIT,
						COUNTRY: row.COUNTRY,
						QTY: row.QTY,
						GR_QTY: '0',
						BAL_QTY: '0',
						ESTIMATE_PRICE: row.ESTIMATE_PRICE,
						CURR_SHORT: row.CURR_SHORT,
						ETA_DATE: row.ETA_DATE,
						CARVED_STAMP: row.CARVED_STAMP,
						PRF_NOMOR: row.PRF_NOMOR,
						PRF_NO: row.PRF_NO,
						PRF_LINE_NO: row.PRF_LINE_NO,
						ORIGIN_CODE: row.ORIGIN_CODE,
						UOM_Q: row.UOM_Q
					}
				});
			}
		}

		function searchPRF_edit(){
			var supp_id = $('#supplier_edit').combobox('getValue');
			var splt = supp_id.split('-');
			var spp = splt[0].trim();
			$('#dlg_prf_edit').dialog('open').dialog('setTitle','Search Item By PRF');
			$('#dg_prf_edit').datagrid('load',{supp: spp});

			$('#dg_prf_edit').datagrid({
				url: 'po_getPRF.php',
				columns:[[
	                {field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM',title:'ITEM',width:100,halign:'center'},
	                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
	                {field:'PRF_NOMOR',title:'PRF NO.',width:120,halign:'center'},
	                {field:'LINE_NO',title:'LINE',width:50,halign:'center', align:'center'},
	                {field:'REQUIRE_DATE',title:'REQUIRE<br/>DATE',width:80,halign:'center',align:'center'},
	                {field:'QTY',title:'QTY',width:100,halign:'center',align:'right'},
	                {field:'REMAINDER_QTY',title:'REMAIN',width:100,halign:'center',align:'right'},
	                {field:'UNIT',title:'UoM',width:65,halign:'center', align:'center'},
	                {field:'CURR_SHORT',title:'CURR',width:65,halign:'center', align:'center'},
	                {field:'ESTIMATE_PRICE', hidden:true},
	                {field:'ORIGIN_CODE', hidden:true},
	                {field:'UOM_Q', hidden:true}       
	            ]]/*,
	            onDblClickRow:function(id,row){
					var t = $('#dg_edit').datagrid('getRows');
					var total = t.length;
				   	var i = 0;
				   	var idxfield=0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=0;
					}else{
						idxfield=total+1;
						for (i=0; i < total; i++) {
							//alert(i);
							var prf = $('#dg_edit').datagrid('getData').rows[i].PRF_NOMOR;
							var prf_ln = $('#dg_edit').datagrid('getData').rows[i].PRF_LINE_NO;
							//alert(item);
							if (prf==row.PRF_NOMOR && prf_ln==row.LINE_NO) {
								count++;
							};
						};
					}
					
					if (count>0) {
						$.messager.alert('Warning','PRF present','warning');
					}else{
						$('#dg_edit').datagrid('insertRow',{
							index: idxfield,
							row: {
								ITEM_NO: row.ITEM_NO,
								DESCRIPTION: row.DESCRIPTION,
								ITEM: row.ITEM,
								UOM_Q: row.UOM_Q,
								UNIT: row.UNIT,
								ORIGIN_CODE: row.ORIGIN_CODE,
								COUNTRY: row.COUNTRY,
								CURR_CODE: row.CURR_CODE,
								CURR_SHORT: row.CURR_SHORT,
								ESTIMATE_PRICE: row.ESTIMATE_PRICE,
								PRF_NO: row.PRF_NOMOR,
								PRF_NOMOR: row.PRF_NOMOR,
								QTY: row.QTY,
								ETA_DATE: row.REQUIRE_DATE,
								PRF_LINE_NO:row.LINE_NO
							}
						});
					}
				}*/
			});

			var dg = $('#dg_prf_edit').datagrid();
			dg.datagrid('enableFilter');
		}

		function select_prf_edit(){
			var rows = $('#dg_prf_edit').datagrid('getSelections');
			var i = 0;
			for(var i=0; i<rows.length; i++){
				var t = $('#dg_edit').datagrid('getRows');
				var total = t.length;
			   	var idxfield=0;
			   	var j = 0;
			   	var count = 0;
				if (parseInt(total) == 0) {
					idxfield=0;
				}else{
					idxfield=total+1;
					for (j=0; j < total; j++) {
						var prf = $('#dg_edit').datagrid('getData').rows[j].PRF_NOMOR;
						var prf_ln = $('#dg_edit').datagrid('getData').rows[j].PRF_LINE_NO;
						if (prf==rows[i].PRF_NOMOR && prf_ln==rows[i].LINE_NO){
							count++;
						};
					};
				}

				if (count>0) {
					$.messager.alert('Warning','PRF present','warning');
				}else{
					$('#dg_edit').datagrid('insertRow',{
						index: idxfield,
						row: {
							ITEM_NO: rows[i].ITEM_NO,
							LINE_NO: 'NEW',
							DESCRIPTION: rows[i].DESCRIPTION,
							ITEM: rows[i].ITEM,
							UOM_Q: rows[i].UOM_Q,
							UNIT: rows[i].UNIT,
							ORIGIN_CODE: rows[i].ORIGIN_CODE,
							COUNTRY: rows[i].COUNTRY,
							CURR_CODE: rows[i].CURR_CODE,
							CURR_SHORT: rows[i].CURR_SHORT,
							ESTIMATE_PRICE: rows[i].ESTIMATE_PRICE,
							PRF_NO: rows[i].PRF_NOMOR,
							PRF_NOMOR: rows[i].PRF_NOMOR,
							QTY: rows[i].REMAINDER_QTY,
							ETA_DATE: rows[i].REQUIRE_DATE,
							PRF_LINE_NO:rows[i].LINE_NO,
							OHSAS: rows[i].OHSAS,
							GR_QTY: '0',
							BAL_QTY: '0'
						}
					});
				}
			}
		}

		function save_editPo(){
			$.messager.progress({
			    title:'Please waiting',
			    msg:'Save data...'
			});

			var dataRows_Edit = [];
			var tot_amt_o = 0;
			var tot_amt_l = 0;
			var ck_rev='N';
			
			if($('#ck_revisi').attr("checked")){ck_rev='Y';}

			var t = $('#dg_edit').datagrid('getRows');
			var total = t.length;
			var jmrow=0;
			if (validate('edit') != 1){
				for(i=0;i<total;i++){
					var amt_o = 0;
					var amt_l = 0;
					jmrow = i+1;
					$('#dg_edit').datagrid('endEdit',i);
					dataRows_Edit.push ({
						v_from: jmrow,
						v_to: total,
						po_sts: 'DETAILS',
						po_no: $('#po_no_edit').textbox('getValue'),
						po_date: $('#po_date_edit').datebox('getValue'),
						po_di_type: $('#di_type_edit').combobox('getValue'),
						po_trans: $('#trans_edit').combobox('getValue'),
						po_remark: $('#remark_edit').textbox('getValue').replace(/\n/gi,"<br>"),
						po_ship_mark: $('#shipp_mark_edit').textbox('getValue').replace(/\n/gi,"<br>"),
						po_rev: ck_rev,
						po_rev_res: $('#revisi_edit').textbox('getValue'),
						po_rate: $('#rate_edit').textbox('getValue'),
						po_line_new: jmrow,
						po_item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
						po_line: $('#dg_edit').datagrid('getData').rows[i].LINE_NO,
						po_unit: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
						po_orign: $('#dg_edit').datagrid('getData').rows[i].ORIGIN_CODE,
						po_price: $('#dg_edit').datagrid('getData').rows[i].ESTIMATE_PRICE,
						po_curr: $('#curr_edit').combobox('getValue'),
						po_curr_item: $('#dg_edit').datagrid('getData').rows[i].CURR_SHORT,
						po_qty: $('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,''),
						po_gr_qty: $('#dg_edit').datagrid('getData').rows[i].GR_QTY.replace(/,/g,''),
						po_bal_qty: $('#dg_edit').datagrid('getData').rows[i].BAL_QTY.replace(/,/g,''),
						po_eta: $('#dg_edit').datagrid('getData').rows[i].ETA_DATE,
						po_prf: $('#dg_edit').datagrid('getData').rows[i].PRF_NOMOR,
						po_prf_line: $('#dg_edit').datagrid('getData').rows[i].PRF_LINE_NO,
						po_dt_code: $('#dg_edit').datagrid('getData').rows[i].CARVED_STAMP,
						amt_o: parseFloat($('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,'') * parseFloat($('#dg_edit').datagrid('getData').rows[i].ESTIMATE_PRICE)).toFixed(2),
						amt_l: parseFloat($('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,'') * parseFloat($('#dg_edit').datagrid('getData').rows[i].ESTIMATE_PRICE) * 
							   $('#rate_edit').textbox('getValue')).toFixed(2)
					});

					amt_o = parseFloat($('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,'') * parseFloat($('#dg_edit').datagrid('getData').rows[i].ESTIMATE_PRICE)).toFixed(2);
					amt_l = parseFloat($('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,'') * parseFloat($('#dg_edit').datagrid('getData').rows[i].ESTIMATE_PRICE) * 
							$('#rate_edit').textbox('getValue')).toFixed(2);

					tot_amt_o += parseFloat(amt_o);
					tot_amt_l += parseFloat(amt_l);

					if(i==total-1){
						dataRows_Edit.push ({
							v_from: jmrow,
							v_to: total,
							po_sts: 'HEADER',
							po_no: $('#po_no_edit').textbox('getValue'),
							po_date: $('#po_date_edit').datebox('getValue'),
							po_di_type: $('#di_type_edit').combobox('getValue'),
							po_trans: $('#trans_edit').combobox('getValue'),
							po_remark: $('#remark_edit').textbox('getValue').replace(/\n/gi,"<br>"),
							po_ship_mark: $('#shipp_mark_edit').textbox('getValue').replace(/\n/gi,"<br>"),
							po_rev: ck_rev,
							po_rev_res: $('#revisi_edit').textbox('getValue'),
							po_rate: $('#rate_edit').textbox('getValue'),
							po_line_new: jmrow,
							po_item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
							po_line: $('#dg_edit').datagrid('getData').rows[i].LINE_NO,
							po_unit: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
							po_orign: $('#dg_edit').datagrid('getData').rows[i].ORIGIN_CODE,
							po_price: $('#dg_edit').datagrid('getData').rows[i].ESTIMATE_PRICE,
							po_curr_item: $('#dg_edit').datagrid('getData').rows[i].CURR_SHORT,
							po_qty: $('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,''),
							po_gr_qty: $('#dg_edit').datagrid('getData').rows[i].GR_QTY.replace(/,/g,''),
							po_bal_qty: $('#dg_edit').datagrid('getData').rows[i].BAL_QTY.replace(/,/g,''),
							po_eta: $('#dg_edit').datagrid('getData').rows[i].ETA_DATE,
							po_prf: $('#dg_edit').datagrid('getData').rows[i].PRF_NOMOR,
							po_prf_line: $('#dg_edit').datagrid('getData').rows[i].PRF_LINE_NO,
							po_dt_code: $('#dg_edit').datagrid('getData').rows[i].CARVED_STAMP,
							amt_o: parseFloat(tot_amt_o).toFixed(2),
							amt_l: parseFloat(tot_amt_l).toFixed(2)
						});
					}
				}

				var myJSON_e=JSON.stringify(dataRows_Edit);
				var str_unescape_e=unescape(myJSON_e);

				console.log(dataRows_Edit);

				$.post('po_edit.php',{
					data: unescape(str_unescape_e)
				}).done(function(res){
					if(res == '"success"'){
						$('#dlg_edit').dialog('close');
						$('#dg').datagrid('reload');
						$.messager.alert('INFORMATION','Update Data Success..!!<br/>PO No. : '+$('#po_no_edit').textbox('getValue'),'info');
						$.messager.progress('close');
					}else{
						//$.post('po_destroy.php',{po_no: $('#po_no_edit').textbox('getValue')},'json');
						$.messager.alert('ERROR',res,'warning');
						$.messager.progress('close');
					}
				});
			}
		}

		function destroyPo(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						$.messager.progress({
						    title:'Please waiting',
						    msg:'removing data...'
						});
						$.post('po_destroy.php',{po_no: row.PO_NO},function(result){
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
			}else{
				$.messager.show({title: 'PO DELETE',msg:'Data Not select'});
			}
		}

		function printPo(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				$('#dlg_print').dialog('open').dialog('setTitle','Print PO Properties ('+row.PO_NO+')');
				document.getElementById('check_line').checked=true;
				pdf_url = "?po="+row.PO_NO
			}else{
				$.messager.show({title: 'PO PRINT',msg: 'Data Not select'});
			}
		}

		function printpdf(){
			if(document.getElementById('check_line').checked == true){
				order = document.getElementById('check_line').value;
			}else if(document.getElementById('check_eta').checked == true){
				order = document.getElementById('check_eta').value;
			}

			pdf_url += "&by="+order
			$('#dlg_print').dialog('close');
			window.open('po_print.php'+pdf_url);
		}

		function print_po_sts_pdf(){
			if(url_pdf=='') {
				$.messager.show({
					title: 'PO Report',
					msg: 'Data Not Defined'
				});
			} else {
				window.open('po_sts_pdf.php'+url_pdf, '_blank');
			}
		}

		function print_po_sts_xls(){
			if(url_pdf=='') {
				$.messager.show({
					title: 'PO Report',
					msg: 'Data Not Defined'
				});
			} else {
				window.open('po_sts_xls.php'+url_pdf, '_blank');
			}
		}

		function po_receive(){
			var url='';
			$('#dlg_print_rec').dialog('open').dialog('setTitle','Filter Print Receive PO');

			/*START CLEAR*/
			$('#date_a').datebox('setValue','');
			$('#date_z').datebox('setValue','');
			$('#cmb_po_no_rec').combobox('setValue','');
			$('#supplier_rec').combobox('setValue','');
			$('#cmb_item_name_rec').combobox('setValue','');
			document.getElementById('src_item').value = '';
			document.getElementById("ck_date_rec").checked = true;
			document.getElementById("cmb_po_no_rec").checked = true;
			document.getElementById("ck_supplier_rec").checked = true;
			document.getElementById("ck_item_no_rec").checked = true;
			/*END CLEAR*/
			
			$('#date_a').datebox('disable');
			$('#date_z').datebox('disable');
			document.getElementById('check_po').disabled = true;
			document.getElementById('check_eta').disabled = true;
			$('#ck_date_rec').change(function(){
				if ($(this).is(':checked')) {
					$('#date_a').datebox('disable');
					$('#date_z').datebox('disable');
					document.getElementById('check_po').disabled = true;
					document.getElementById('check_eta').disabled = true;
				}
				if (!$(this).is(':checked')) {
					$('#date_a').datebox('enable');
					$('#date_z').datebox('enable');
					document.getElementById('check_po').disabled = false;
					document.getElementById('check_eta').disabled = false;
				};
			})

			
			$('#cmb_po_no_rec').combobox('disable');
			$('#ck_po_rec').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_po_no_rec').combobox('disable');
				}

				if (!$(this).is(':checked')) {
					$('#cmb_po_no_rec').combobox('enable');
				}
			})			


			$('#supplier_rec').combobox('disable');
			$('#ck_supplier_rec').change(function(){
				if ($(this).is(':checked')) {
					$('#supplier_rec').combobox('disable');
				}

				if (!$(this).is(':checked')) {
					$('#supplier_rec').combobox('enable');
				}
			})

			document.getElementById('src_item').disabled = true;
			$('#ck_item_no_rec').change(function(){
				if ($(this).is(':checked')) {
					document.getElementById('src_item').disabled = true;
				}

				if (!$(this).is(':checked')) {
					document.getElementById('src_item').disabled = false;
				}
			})
		}

		function filter_rec(){
			var ck_date_rec='false';
			var ck_supplier_rec='false';
			var ck_item_no_rec='false';
			var ck_po_rec='false';
			var ck_endorder_rec = 'false';

			var date_rec;
			if(document.getElementById('check_po').checked == true){
				date_rec = document.getElementById('check_po').value;
			}else if(document.getElementById('check_eta').checked == true){
				date_rec = document.getElementById('check_eta').value;
			}else{
				date_rec ='';
			}

			if($('#ck_date_rec').attr("checked")){
				ck_date_rec='true';
			}

			if($('#ck_po_rec').attr("checked")){
				ck_po_rec='true';
			}			

			if($('#ck_supplier_rec').attr("checked")){
				ck_supplier_rec='true';
			}

			if($('#ck_item_no_rec').attr("checked")){
				ck_item_no_rec='true';
			}

			if($('#ck_endorder_rec').attr("checked")){
				ck_endorder_rec='true';
			}
			
			url ="?date_awal="+$('#date_a').datebox('getValue')+
				 "&date_akhir="+$('#date_z').datebox('getValue')+"&ck_date="+ck_date_rec+
				 "&cmb_po="+$('#cmb_po_no_rec').combobox('getValue')+"&ck_po="+ck_po_rec+
				 "&supplier="+$('#supplier_rec').combobox('getValue')+"&supplier_nm="+$('#supplier_rec').combobox('getText')+"&ck_supplier="+ck_supplier_rec+
				 "&cmb_item_no="+document.getElementById('src_item').value+"&ck_item_no="+ck_item_no_rec+
				 "&txt_item_name="+$('#cmb_item_name_rec').combobox('getValue')+
				 "&date_sts="+date_rec+"&endorder="+ck_endorder_rec;
				 
		}

		/*function print_po_receive_pdf(){
			if($('#ck_date_rec').attr("checked") && $('#ck_supplier_rec').attr("checked") && $('#ck_item_no_rec').attr("checked") && $('#ck_po_rec').attr("checked")){
				$.messager.show({
					title: 'PO Receive',
					msg: 'Data Not Defined'
				});
			}else{
				filter_rec();
				$('#dlg_print_rec').dialog('close');
				window.open('po_receive_pdf.php'+url, '_blank');
			}
		}*/

		function print_po_receive_xls(){
			if($('#ck_date_rec').attr("checked") && $('#ck_supplier_rec').attr("checked") && $('#ck_item_no_rec').attr("checked") && $('#ck_po_rec').attr("checked")){
				$.messager.show({
					title: 'PO Receive',
					msg: 'Data Not Defined'
				});
			}else{
				filter_rec();
				$('#dlg_print_rec').dialog('close');
				window.open('po_receive_xls.php'+url, '_blank');
			}
		}
	</script>
	</body>
    </html>
<?php }else{
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}