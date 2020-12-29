<?php 
session_start();
include("../../connect/conn.php");
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
if ($varConn=='Y'){
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>SHIPPING INSTRUCTION</title>
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
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
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
	<?php include ('../../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name)); ?>
	
	<table id="dg" title="SHIPPING INSTRUCTION" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;" data-options="fitColumns:true"></table>
	<div id="toolbar" style="padding:3px 3px;">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%; height:70px; float:left;"><legend>SHIPPING INSTRUCTION FILTER</legend>
			<div style="float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CR Date</span>
					<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					to 
					<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
                </div>
                <div class="fitem">
					<input style="width:310px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" placeholder="search SI NO. or consignee or customer po no." autofocus="autofocus"/>
					<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</a>
					<a href="javascript:void(0)" style="width: 100px;" id="add" class="easyui-linkbutton c2" onclick="addSI()"><i class="fa fa-plus" aria-hidden="true"></i> ADD SI</a>
					<a href="javascript:void(0)" style="width: 200px;" id="edit" class="easyui-linkbutton c2" onclick="copySI()"><i class="fa fa-pencil" aria-hidden="true"></i> COPY DATA FOR NEW SI</a>
					<a href="javascript:void(0)" style="width: 100px;" id="delete" class="easyui-linkbutton c2" onclick="destroySI()"><i class="fa fa-trash" aria-hidden="true"></i> DELETE SI</a>
				</div>
            </div>
		</fieldset>
	</div>
	
	<!-- START ADD -->
	<div id='win_add' class="easyui-window" style="width:1105px;height:540px;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
		<form id="f_add" method="post" novalidate>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1050px; float:left; margin:5px;"><legend>SETTINGS</legend>
			<div class="fitem">
				<span style="width:70px;display:inline-block;">SI NO.</span>
				<input style="width:160px;" name="si_no_add" id="si_no_add" class="easyui-textbox" disabled="" />
				<span style="width:25px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SI NO. FROM CUST</span>
				<input style="width:605px;height:30px;" name="si_no_from_cust_add" id="si_no_from_cust_add" class="easyui-textbox" multiline="true"/>
			</div>
			<div class="fitem">
				<span style="width:140px;display:inline-block;">PERSON IN CHARGE</span>
				<input style="width:236px;height:30px;" name="person_add" id="person_add" class="easyui-textbox" multiline="true"/>
				<span style="width:21px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">DESC. OF GOODS</span>
				<input style="width:462px;height:30px;" name="goods_name_add" id="goods_name_add" class="easyui-textbox" multiline="true"/>
			</div>
			<div class="fitem">
				<span style="width:140px;display:inline-block;">SET CUST. PO NO.</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_cust_po_no('add', 'CUSTOMER. PO NO')" style="width:70px;"> SET </a>
				<label><input type="checkbox" name="ck_cust_po_no" id="ck_cust_po_no" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET CONTRACT</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_contract('add')" style="width:70px" disabled="true"> SET </a>
				<label><input type="checkbox" name="ck_contract" id="ck_contract" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET SHIPPER</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_shipper('add', 'SHIPPER')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_shipper" id="ck_shipper" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET FORWARDER</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_forwarder('add','FORWARDER')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_forwarder" id="ck_forwarder" disabled="true"></input></label>
			</div>
			<div class="fitem">
				<span style="width:140px;display:inline-block;">SET PORT LOADING</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_port_loading('add','PORT LOADING')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_port_loading" id="ck_port_loading" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET PORT DISCHARGE</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_port_loading('add','PORT DISCHARGE')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_port_discharge" id="ck_port_discharge" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">FINAL DESTINATION</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_port_loading('add', 'FINAL DESTINATION')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_final_dest" id="ck_final_dest" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET PLACE OF DELIVERY</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_port_loading('add', 'PLACE OF DELIVERY')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_place" id="ck_place" disabled="true"></input></label>
			</div>
			<div class="fitem">
				<span style="width:140px;display:inline-block;">SET CONSIGNEE</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_consignee('add','CONSIGNEE')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_consignee" id="ck_consignee" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET NOTIFY-1</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_notify('add','NOTIFY')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_notify" id="ck_notify" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET NOTIFY-2</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_notify2('add','NOTIFY 2')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_notify2" id="ck_notify2" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET EMKL</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_emkl('add','EMKL')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_emkl" id="ck_emkl" disabled="true"></input></label>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">SPECIAL INSTRUCTION</span>
				<input style="width:845px;" name="special_inst" id="special_inst" class="easyui-textbox"/>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1050px; float:left; margin:5px;" hidden="true"><legend>RESULT</legend>
		<!-- hidden="true" -->
			<input style="width:500px;" name="res_cust_po_no" id="res_cust_po_no" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_contract" id="res_contract" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_shipper" id="res_shipper" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_forwarder" id="res_forwarder" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_port_loading" id="res_port_loading" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_port_discharge" id="res_port_discharge" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_final_dest" id="res_final_dest" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_place" id="res_place" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_consignee" id="res_consignee" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_notify" id="res_notify" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_notify2" id="res_notify2" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_emkl" id="res_emkl" class="easyui-textbox" disabled=""/>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1050px; float:left; margin:5px;"><legend>DOCUMENT</legend>
			<div class="fitem">	
				<span style="width:180px;display:inline-block;">SHIPPING METHOD</span>
				<select style="width:120px;" name="cmb_payment_method" id="cmb_payment_method" class="easyui-combobox" >
					<option value=""></option>
					<option value="LCL" selected="true">LCL</option>
					<option value="FCL">FCL</option>
					<option value="BY AIR">BY AIR</option>
					<option value="LOKAL">LOKAL</option>
				</select>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">PAYMENT / FREIGHT</span>
				<select style="width:120px;" name="cmb_payment_type" id="cmb_payment_type" class="easyui-combobox" >
					<option value=""></option>
					<option value="Prepaid" selected="true">Prepaid</option>
					<option value="Colect">Colect</option>
					<option value="Other">Other</option>
				</select>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">PAYMENT REMARK</span>
				<input style="width:250px;" name="payment_remark" id="payment_remark" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">B/L DOC</span>
				<input style="width:120px;" name="cmb_bl_doc" id="cmb_bl_doc" class="easyui-combobox" 
					data-options=" url:'../json/json_bl_doc.php',method:'get',valueField:'doc_name',textField:'doc_name', panelHeight:'100px'"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="sheet_bl_doc" id="sheet_bl_doc" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:390px;" name="detail_bl_doc" id="detail_bl_doc" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">CERTIFICATE OF ORIGIN</span>
				<input style="width:120px;" name="cmb_certificate" id="cmb_certificate" class="easyui-combobox" 
					data-options=" url:'../json/json_certificate.php',method:'get',valueField:'doc_name',textField:'doc_name', panelHeight:'100px'"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="sheet_certificate" id="sheet_certificate" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:390px;" name="detail_certificate" id="detail_certificate" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;"></span>
				<input style="width:120px;" name="inv_doc" id="inv_doc" class="easyui-textbox" disabled="" value="INVOICE"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="shett_inv" id="shett_inv" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:390px;" name="detail_inv" id="detail_inv" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;"></span>
				<input style="width:120px;" name="pack_doc" id="pack_doc" class="easyui-textbox"  disabled=""  value="PACKING LIST"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="sheet_pack" id="sheet_pack" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:390px;" name="detail_pack" id="detail_pack" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">SPECIAL INFORMATION</span>
				<input style="width:845px;" name="special_inform" id="special_inform" class="easyui-textbox"/>
			</div>
		</fieldset>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveSI('add')" style="width:140px"> SAVE </a>
			<a class="easyui-linkbutton c2" id="clsbtn" href="javascript:void(0)" onclick="javascript:$('#win_add').window('close')" style="width:140px"><i class="fa fa-ban" aria-hidden="true"></i> Cancel </a>
		</div>
		</form>
	</div>
	<!-- END ADD -->

	<!-- START EDIT -->
	<div id='win_edit' class="easyui-window" style="width:1105px;height:540px;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
		<form id="f_edit" method="post" novalidate>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1050px; float:left; margin:5px;"><legend>SETTINGS</legend>
			<div class="fitem">
				<span style="width:70px;display:inline-block;">SI NO.</span>
				<input style="width:160px;" name="si_no_edit" id="si_no_edit" class="easyui-textbox" disabled="" />
				<span style="width:25px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SI NO. FROM CUST</span>
				<input style="width:605px;height:30px;" name="si_no_from_cust_edit" id="si_no_from_cust_edit" class="easyui-textbox" multiline="true"/>
			</div>
			<div class="fitem">
				<span style="width:140px;display:inline-block;">PERSON IN CHARGE</span>
				<input style="width:236px;height:30px;" name="person_edit" id="person_edit" class="easyui-textbox" multiline="true"/>
				<span style="width:21px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">DESC. OF GOODS</span>
				<input style="width:462px;height:30px;" name="goods_name_edit" id="goods_name_edit" class="easyui-textbox" multiline="true"/>
			</div>
			<div class="fitem">
				<span style="width:140px;display:inline-block;">SET CUST. PO NO.</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_cust_po_no('edit', 'CUSTOMER. PO NO')" style="width:70px;"> SET </a>
				<label><input type="checkbox" name="ck_cust_po_no_edit" id="ck_cust_po_no_edit" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET CONTRACT</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_contract('edit')" style="width:70px" disabled="true"> SET </a>
				<label><input type="checkbox" name="ck_contract_edit" id="ck_contract_edit" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET SHIPPER</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_shipper('edit', 'SHIPPER')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_shipper_edit" id="ck_shipper_edit" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET FORWARDER</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_forwarder('edit','FORWARDER')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_forwarder_edit" id="ck_forwarder_edit" disabled="true"></input></label>
			</div>
			<div class="fitem">
				<span style="width:140px;display:inline-block;">SET PORT LOADING</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_port_loading('edit','PORT LOADING')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_port_loading_edit" id="ck_port_loading_edit" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET PORT DISCHARGE</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_port_loading('edit','PORT DISCHARGE')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_port_discharge_edit" id="ck_port_discharge_edit" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">FINAL DESTINATION</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_port_loading('edit', 'FINAL DESTINATION')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_final_dest_edit" id="ck_final_dest_edit" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET PLACE OF DELIVERY</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_port_loading('edit', 'PLACE OF DELIVERY')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_place_edit" id="ck_place_edit" disabled="true"></input></label>
			</div>
			<div class="fitem">
				<span style="width:140px;display:inline-block;">SET CONSIGNEE</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_consignee('edit','CONSIGNEE')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_consignee_edit" id="ck_consignee_edit" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET NOTIFY-1</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_notify('edit','NOTIFY')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_notify_edit" id="ck_notify_edit" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET NOTIFY-2</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_notify2('edit','NOTIFY 2')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_notify2_edit" id="ck_notify2_edit" disabled="true"></input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">SET EMKL</span>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="SET_emkl('edit','EMKL')" style="width:70px"> SET </a>
				<label><input type="checkbox" name="ck_emkl_edit" id="ck_emkl_edit" disabled="true"></input></label>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">SPECIAL INSTRUCTION</span>
				<input style="width:845px;" name="special_inst_edit" id="special_inst_edit" class="easyui-textbox"/>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1050px; float:left; margin:5px;" hidden="true"><legend>RESULT</legend>
		<!-- hidden="true" -->
			<input style="width:500px;" name="res_cust_po_no_edit" id="res_cust_po_no_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_contract_edit" id="res_contract_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_shipper_edit" id="res_shipper_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_forwarder_edit" id="res_forwarder_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_port_loading_edit" id="res_port_loading_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_port_discharge_edit" id="res_port_discharge_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_final_dest_edit" id="res_final_dest_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_place_edit" id="res_place_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_consignee_edit" id="res_consignee_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_notify_edit" id="res_notify_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_notify2_edit" id="res_notify2_edit" class="easyui-textbox" disabled=""/>
			<input style="width:500px;" name="res_emkl_edit" id="res_emkl_edit" class="easyui-textbox" disabled=""/>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1050px; float:left; margin:5px;"><legend>DOCUMENT</legend>
			<div class="fitem">	
				<span style="width:180px;display:inline-block;">SHIPPING METHOD</span>
				<select style="width:120px;" name="cmb_payment_method_edit" id="cmb_payment_method_edit" class="easyui-combobox" >
					<option selected="true" value=""></option>
					<option value="LCL">LCL</option>
					<option value="FCL">FCL</option>
					<option value="BY AIR">BY AIR</option>
				</select>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">PAYMENT / FREIGHT</span>
				<select style="width:120px;" name="cmb_payment_type_edit" id="cmb_payment_type_edit" class="easyui-combobox" >
					<option selected="true" value=""></option>
					<option value="Prepaid">Prepaid</option>
					<option value="Colect">Colect</option>
					<option value="Other">Other</option>
				</select>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">PAYMENT REMARK</span>
				<input style="width:250px;" name="payment_remark_edit" id="payment_remark_edit" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">B/L DOC</span>
				<input style="width:120px;" name="cmb_bl_doc_edit" id="cmb_bl_doc_edit" class="easyui-combobox" 
					data-options=" url:'../json/json_bl_doc.php',method:'get',valueField:'doc_name',textField:'doc_name', panelHeight:'100px'"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="sheet_bl_doc_edit" id="sheet_bl_doc_edit" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:390px;" name="detail_bl_doc_edit" id="detail_bl_doc_edit" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">CERTIFICATE OF ORIGIN</span>
				<input style="width:120px;" name="cmb_certificate_edit" id="cmb_certificate_edit" class="easyui-combobox" 
					data-options=" url:'../json/json_certificate.php',method:'get',valueField:'doc_name',textField:'doc_name', panelHeight:'100px'"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="sheet_certificate_edit" id="sheet_certificate_edit" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:390px;" name="detail_certificate_edit" id="detail_certificate_edit" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;"></span>
				<input style="width:120px;" name="inv_doc_edit" id="inv_doc_edit" class="easyui-textbox" disabled="" value="INVOICE"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="shett_inv_edit" id="shett_inv_edit" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:390px;" name="detail_inv_edit" id="detail_inv_edit" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;"></span>
				<input style="width:120px;" name="pack_doc_edit" id="pack_doc_edit" class="easyui-textbox"  disabled=""  value="PACKING LIST"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="sheet_pack_edit" id="sheet_pack_edit" class="easyui-textbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:390px;" name="detail_pack_edit" id="detail_pack_edit" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">SPECIAL INFORMATION</span>
				<input style="width:845px;" name="special_inform_edit" id="special_inform_edit" class="easyui-textbox"/>
			</div>
		</fieldset>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveSI('edit')" style="width:140px"> SAVE </a>
			<a class="easyui-linkbutton c2" id="clsbtn" href="javascript:void(0)" onclick="javascript:$('#win_edit').window('close')" style="width:140px"><i class="fa fa-ban" aria-hidden="true"></i> Cancel </a>
		</div>
		</form>
	</div>
	<!-- END EDIT -->

	<!-- START CUSTOMER PO NO. SETT -->
	<div id="dlg_cust" class="easyui-dialog" style="width: 700px;height: 500px;" closed="true" data-options="modal:true">
		<table id="dg_cust" class="easyui-datagrid" style="width:100%;height:300px;border-radius: 10px;"></table>
		<div class="fitem">
			<input style="width:685px;height: 120px;" name="txt_cust" id="txt_cust" multiline="true" class="easyui-textbox"/>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="select_cust()" style="width:140px">
				<i class="fa fa-ban" aria-hidden="true"></i> SELECT </a>
		</div>
	</div>
	<!-- END CUSTOMER PO NO. SETT -->

	<!-- START CUSTOMER PO NO. SETT -->
	<div id="dlg_shipper" class="easyui-dialog" style="width: 700px;height: 500px;" closed="true" data-options="modal:true">
		<table id="dg_shipper" class="easyui-datagrid" style="width:100%;height:300px;border-radius: 10px;"></table>
		<div class="fitem">
			<input style="width:685px;height: 120px;" name="txt_shipper" id="txt_shipper" multiline="true" class="easyui-textbox"/>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="select_shipper()" style="width:140px">
				<i class="fa fa-ban" aria-hidden="true"></i> SELECT </a>
		</div>
	</div>
	<!-- END CUSTOMER PO NO. SETT -->


	<!-- START FORWARDER PO NO. SETT -->
	<div id="dlg_forwarder" class="easyui-dialog" style="width: 700px;height: 500px;" closed="true" data-options="modal:true">
		<table id="dg_forwarder" class="easyui-datagrid" style="width:100%;height:300px;border-radius: 10px;"></table>
		<div class="fitem">
			<input style="width:685px;height: 120px;" name="txt_forwarder" id="txt_forwarder" multiline="true" class="easyui-textbox"/>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="select_forwarder()" style="width:140px">
				<i class="fa fa-ban" aria-hidden="true"></i> SELECT </a>
		</div>
	</div>
	<!-- END FORWARDER PO NO. SETT -->

	<!-- START PORT_LOADING PO NO. SETT -->
	<div id="dlg_port_loading" class="easyui-dialog" style="width: 700px;height: 500px;" closed="true" data-options="modal:true">
		<table id="dg_port_loading" class="easyui-datagrid" style="width:100%;height:300px;border-radius: 10px;"></table>
		<div class="fitem">
			<input style="width:685px;height: 120px;" name="txt_port_loading" id="txt_port_loading" multiline="true" class="easyui-textbox"/>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="select_port_loading()" style="width:140px">
				<i class="fa fa-ban" aria-hidden="true"></i> SELECT </a>
		</div>
	</div>
	<!-- END PORT_LOADING PO NO. SETT -->

	<!-- START CONSIGNEE SETT -->
	<div id="dlg_consignee" class="easyui-dialog" style="width: 700px;height: 500px;" closed="true" data-options="modal:true">
		<table id="dg_consignee" class="easyui-datagrid" style="width:100%;height:300px;border-radius: 10px;"></table>
		<div class="fitem">
			<input style="width:685px;height: 120px;" name="txt_consignee" id="txt_consignee" multiline="true" class="easyui-textbox"/>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="select_consignee()" style="width:140px">
				<i class="fa fa-ban" aria-hidden="true"></i> SELECT </a>
		</div>
	</div>
	<!-- END CONSIGNEE SETT -->

	<!-- START NOTIFY SETT -->
	<div id="dlg_notify" class="easyui-dialog" style="width: 700px;height: 500px;" closed="true" data-options="modal:true">
		<table id="dg_notify" class="easyui-datagrid" style="width:100%;height:300px;border-radius: 10px;"></table>
		<div class="fitem">
			<input style="width:685px;height: 120px;" name="txt_notify" id="txt_notify" multiline="true" class="easyui-textbox"/>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="select_notify()" style="width:140px">
				<i class="fa fa-ban" aria-hidden="true"></i> SELECT </a>
		</div>
	</div>
	<!-- END CONSIGNEE SETT -->

	<!-- START NOTIFY-2 SETT -->
	<div id="dlg_notify2" class="easyui-dialog" style="width: 700px;height: 500px;" closed="true" data-options="modal:true">
		<table id="dg_notify2" class="easyui-datagrid" style="width:100%;height:300px;border-radius: 10px;"></table>
		<div class="fitem">
			<input style="width:685px;height: 120px;" name="txt_notify2" id="txt_notify2" multiline="true" class="easyui-textbox"/>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="select_notify2()" style="width:140px">
				<i class="fa fa-ban" aria-hidden="true"></i> SELECT </a>
		</div>
	</div>
	<!-- END CONSIGNEE SETT -->

	<!-- START EMKL SETT -->
	<div id="dlg_emkl" class="easyui-dialog" style="width: 700px;height: 500px;" closed="true" data-options="modal:true">
		<table id="dg_emkl" class="easyui-datagrid" style="width:100%;height:300px;border-radius: 10px;"></table>
		<div class="fitem">
			<input style="width:685px;height: 120px;" name="txt_emkl" id="txt_emkl" multiline="true" class="easyui-textbox"/>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="select_emkl()" style="width:140px">
				<i class="fa fa-ban" aria-hidden="true"></i> SELECT </a>
		</div>
	</div>
	<!-- END EMKL SETT -->

	<script type="text/javascript">
		function destroySI(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						$.messager.progress({
							title:'Please waiting',
							msg:'removing data...'
						});
						console.log('si_destroy.php?si_no='+row.SI_NO);
						$.post('si_destroy.php',{si_no: row.SI_NO},function(result){
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

		var sino_lama = '';
		function copySI(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#win_edit').window('open').dialog('setTitle','EDIT SALES INSTRUCTION ('+row.SI_NO+')');
				$('#si_no_from_cust_edit').textbox('setValue', row.CUST_SI_NO);
				$('#person_edit').textbox('setValue', row.PERSON_NAME);
				$('#goods_name_edit').textbox('setValue', row.GOODS_NAME);
				$('#special_inform_edit').textbox('setValue', row.SPECIAL_INFO);
				$('#special_inst_edit').textbox('setValue', row.SPECIAL_INST);
				$('#cmb_payment_method_edit').textbox('setValue', row.SHIPPING_TYPE);
				$('#cmb_payment_type_edit').textbox('setValue', row.PAYMENT_TYPE);
				$('#payment_remark_edit').textbox('setValue', row.PAYMENT_REMARK);
				sino_lama = row.SI_NO
				r_shipper.push({
					'SHIPPER_NO': '',
					'SHIPPER_NAME': row.SHIPPER_NAME,
					'SHIPPER_ADDR': row.SHIPPER_ADDR1+' '+row.SHIPPER_ADDR2+' '+row.SHIPPER_ADDR3,
					'ADDR1': row.SHIPPER_ADDR1,
					'ADDR2': row.SHIPPER_ADDR2,
					'ADDR3': row.SHIPPER_ADDR3,
					'TEL': row.SHIPPER_TEL,
					'FAX': row.SHIPPER_FAX,
					'ATTN': row.SHIPPER_ATTN
				});

				r_forwarder.push({
					'FORWARDER_NO': '',
					'FORWARDER_NAME': row.FORWARDER_NAME,
					'FORWARDER_ADDR': row.FORWARDER_ADDR1,
					'ADDR1': row.FORWARDER_ADDR1,
					'ADDR2': row.FORWARDER_ADDR2,
					'ADDR3': row.FORWARDER_ADDR3,
					'TEL': row.FORWARDER_TEL,
					'FAX': row.FORWARDER_FAX,
					'ATTN': row.FORWARDER_ATTN
				});

				r_port_loading.push({
					'CODE': row.LOAD_PORT_CODE,
					'NAME': row.LOAD_PORT
				});

				r_port_discharge.push({
					'CODE': row.DISCH_PORT_CODE,
					'NAME': row.DISCH_PORT
				});

				r_final_dest.push({
					'CODE': row.FINAL_DEST_CODE,
					'NAME': row.FINAL_DEST
				});

				r_port_place_del.push({
					'CODE': row.PLACE_DELI_CODE,
					'NAME': row.PLACE_DELI
				});

				r_consignee.push({
					'CONSIGNEE_NO': '',
					'NAME': row.CONSIGNEE_NAME,
					'ADDR': row.CONSIGNEE_ADDR1+' '+row.CONSIGNEE_ADDR2+' '+row.CONSIGNEE_ADDR3,
					'ADDR1': row.CONSIGNEE_ADDR1,
					'ADDR2': row.CONSIGNEE_ADDR2,
					'ADDR3': row.CONSIGNEE_ADDR3,
					'TEL': row.CONSIGNEE_TEL,
					'FAX': row.CONSIGNEE_FAX,
					'ATTN': row.CONSIGNEE_ATTN
				});

				r_notify.push({
					'NOTIFY_NO': '',
					'NAME': row.NOTIFY_NAME,
					'ADDR': row.ADDR1+' '+row.ADDR2+' '+row.ADDR3,
					'ADDR1': row.ADDR1,
					'ADDR2': row.ADDR2,
					'ADDR3': row.ADDR3,
					'TEL': row.TEL,
					'FAX': row.FAX,
					'ATTN': row.ATTN
				});

				r_notify2.push({
					'NOTIFY_NO': '',
					'NAME': row.NOTIFY_NAME_2,
					'ADDR': row.ADDR1_2+' '+row.ADDR2_2+' '+row.ADDR3_2,
					'ADDR1': row.ADDR1_2,
					'ADDR2': row.ADDR2_2,
					'ADDR3': row.ADDR3_2,
					'TEL': row.TEL_2,
					'FAX': row.FAX_2,
					'ATTN': row.ATTN_2
				});

				r_emkl.push({
					'EMKL_NO': '',
					'NAME': row.EMKL_NAME,
					'ADDR': row.EMKL_ADDR1+' '+row.EMKL_ADDR2+' '+row.EMKL_ADDR3,
					'ADDR1': row.EMKL_ADDR1,
					'ADDR2': row.EMKL_ADDR2,
					'ADDR3': row.EMKL_ADDR3,
					'TEL': row.EMKL_TEL,
					'FAX': row.EMKL_FAX,
					'ATTN': row.EMKL_ATTN
				});

				// alert(row.CUST_SI_NO);
				// r_cust = row.CUST_SI_NO;
				r_cust = {"cust_po_no" : row.CUST_PO_NO};
			}
		}

		function simpan(a){
			var dataRows = [];
			if (a == 'add'){
				dataRows.push({
					SI_STS: a,
					SI_NO_OLD: '-',
					SI_NO: $('#si_no_add').textbox('getValue'),
					CONTRACT_NO: 'NULL',
					SINO_FROM_CUST: $('#si_no_from_cust_add').textbox('getValue'),
					PERSON_NAME: $('#person_add').textbox('getValue'),
					GOODS_NAME: $('#goods_name_add').textbox('getValue'),
					SHIPPER_NAME: r_shipper[0].SHIPPER_NAME,
					SHIPPER_ADDR1: r_shipper[0].ADDR1,
					SHIPPER_ADDR2: r_shipper[0].ADDR2,
					SHIPPER_ADDR3: r_shipper[0].ADDR3,
					SHIPPER_TEL: r_shipper[0].TEL,
					SHIPPER_FAX: r_shipper[0].FAX,
					SHIPPER_ATTN: r_shipper[0].ATTN,
					CUST_SI_NO: r_cust["cust_po_no"],
					LOAD_PORT_CODE: r_port_loading[0].CODE,
					LOAD_PORT: r_port_loading[0].NAME,
					DISCH_PORT_CODE: r_port_discharge[0].CODE,
					DISCH_PORT: r_port_discharge[0].NAME,
					FINAL_DEST_CODE: r_final_dest[0].CODE,
					FINAL_DEST: r_final_dest[0].NAME,
					PLACE_DELI_CODE: r_port_place_del[0].CODE,
					PLACE_DELI: r_port_place_del[0].NAME,
					SHIPPING_TYPE: $('#cmb_payment_method').combobox('getValue'),
					PAYMENT_TYPE: $('#cmb_payment_type').combobox('getValue'),
					PAYMENT_REMARK: $('#payment_remark').textbox('getValue'),
					// BL_DATE:
					FORWARDER_NAME: r_forwarder[0].FORWARDER_NAME,
					FORWARDER_ADDR1: r_forwarder[0].ADDR1,
					FORWARDER_ADDR2: r_forwarder[0].ADDR2,
					FORWARDER_ADDR3: r_forwarder[0].ADDR3,
					FORWARDER_TEL: r_forwarder[0].TEL,
					FORWARDER_FAX: r_forwarder[0].FAX,
					FORWARDER_ATTN: r_forwarder[0].ATTN,
					SPECIAL_INST: $('#special_inst').textbox('getValue'),
					SPECIAL_INFO: $('#special_inform').textbox('getValue'),
					CONSIGNEE_NAME: r_consignee[0].NAME,
					CONSIGNEE_ADDR1: r_consignee[0].ADDR1,
					CONSIGNEE_ADDR2: r_consignee[0].ADDR2,
					CONSIGNEE_ADDR3: r_consignee[0].ADDR3,
					CONSIGNEE_TEL: r_consignee[0].TEL,
					CONSIGNEE_FAX: r_consignee[0].FAX,
					CONSIGNEE_ATTN: r_consignee[0].ATTN
				});
			}else{
				dataRows.push({
					SI_STS: a,
					SI_NO_OLD: sino_lama,
					SI_NO: $('#si_no_edit').textbox('getValue'),
					CONTRACT_NO: 'NULL',
					SINO_FROM_CUST: $('#si_no_from_cust_edit').textbox('getValue'),
					PERSON_NAME: $('#person_edit').textbox('getValue'),
					GOODS_NAME: $('#goods_name_edit').textbox('getValue'),
					SHIPPER_NAME: r_shipper[0].SHIPPER_NAME,
					SHIPPER_ADDR1: r_shipper[0].ADDR1,
					SHIPPER_ADDR2: r_shipper[0].ADDR2,
					SHIPPER_ADDR3: r_shipper[0].ADDR3,
					SHIPPER_TEL: r_shipper[0].TEL,
					SHIPPER_FAX: r_shipper[0].FAX,
					SHIPPER_ATTN: r_shipper[0].ATTN,
					CUST_SI_NO:  r_cust["cust_po_no"],
					LOAD_PORT_CODE: r_port_loading[0].CODE,
					LOAD_PORT: r_port_loading[0].NAME,
					DISCH_PORT_CODE: r_port_discharge[0].CODE,
					DISCH_PORT: r_port_discharge[0].NAME,
					FINAL_DEST_CODE: r_final_dest[0].CODE,
					FINAL_DEST: r_final_dest[0].NAME,
					PLACE_DELI_CODE: r_port_place_del[0].CODE,
					PLACE_DELI: r_port_place_del[0].NAME,
					SHIPPING_TYPE: $('#cmb_payment_method_edit').combobox('getValue'),
					PAYMENT_TYPE: $('#cmb_payment_type_edit').combobox('getValue'),
					PAYMENT_REMARK: $('#payment_remark_edit').textbox('getValue'),
					// BL_DATE:
					FORWARDER_NAME: r_forwarder[0].FORWARDER_NAME,
					FORWARDER_ADDR1: r_forwarder[0].ADDR1,
					FORWARDER_ADDR2: r_forwarder[0].ADDR2,
					FORWARDER_ADDR3: r_forwarder[0].ADDR3,
					FORWARDER_TEL: r_forwarder[0].TEL,
					FORWARDER_FAX: r_forwarder[0].FAX,
					FORWARDER_ATTN: r_forwarder[0].ATTN,
					SPECIAL_INST: $('#special_inst_edit').textbox('getValue'),
					SPECIAL_INFO: $('#special_inform_edit').textbox('getValue'),
					CONSIGNEE_NAME: r_consignee[0].NAME,
					CONSIGNEE_ADDR1: r_consignee[0].ADDR1,
					CONSIGNEE_ADDR2: r_consignee[0].ADDR2,
					CONSIGNEE_ADDR3: r_consignee[0].ADDR3,
					CONSIGNEE_TEL: r_consignee[0].TEL,
					CONSIGNEE_FAX: r_consignee[0].FAX,
					CONSIGNEE_ATTN: r_consignee[0].ATTN
				});
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);

			console.log('si_save.php?data='+unescape(str_unescape));

			$.post('si_save.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					if (a == 'add'){
						$('#win_add').dialog('close');
						$('#dg').datagrid('reload');
						$.messager.alert('INFORMATION','Insert Data Success..!!<br/>SI No. : '+$('#si_no_add').textbox('getValue'),'info');
					}else{
						$('#win_edit').dialog('close');
						$('#dg').datagrid('reload');
						$.messager.alert('INFORMATION','Copy Data Success..!!<br/>SI No. New : '+$('#si_no_edit').textbox('getValue'),'info');
					}					
				}else{
					$.messager.alert('ERROR',res,'warning');
				}
			});
		}

		function saveSI(value){
			$.ajax({
				type: 'GET',
				url: '../json/json_kode_si.php',
				data: { kode:'kode' },
				success: function(data){
					if(data[0].kode == 'UNDEFINIED'){
						$.messager.alert('INFORMATION','kode SI Error..!!','info');
						// $.messager.progress('close');
					}else{
						if (value == 'add'){
							$('#si_no_add').textbox('setValue', data[0].kode);
						}else{
							$('#si_no_edit').textbox('setValue', data[0].kode);
						}
						
						simpan(value);
					}
				}
			});
		}

		function SET_emkl(values,res){
			$('#dlg_emkl').dialog('open').dialog('setTitle','SET '+res);
			$('#dg_emkl').datagrid({
				url: '_get_emkl.php',
				fitColumns: true,
				singleSelect: true,
				columns:[[
					{field:'EMKL_NO',title:'NOTIFY NO.',width:50,halign:'center', align:'center'},
					{field:'NAME',title:'NAME',width:200,halign:'center'},
					{field:'ADDR',title:'ADDRESS',width:250,halign:'center', align:'center'}
				]]
			});
			$('#dg_notify').datagrid('enableFilter');
		}

		var r_emkl = [];
		function select_emkl(){
			var arrEMKL = [];
			var rows = $('#dg_emkl').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				arrEMKL.push({
					'EMKL_NO': rows[i].EMKL_NO,
					'NAME': rows[i].NAME,
					'ADDR': rows[i].ADDR,
					'ADDR1': rows[0].ADDR1,
					'ADDR2': rows[0].ADDR2,
					'ADDR3': rows[0].ADDR3,
					'TEL': rows[0].TEL,
					'FAX': rows[0].FAX,
					'ATTN': rows[0].ATTN
				});
			}

			r_emkl = arrEMKL;
			// console.log(JSON.stringify(r_emkl));
			$('#res_emkl').textbox('setValue', JSON.stringify(r_emkl));
			document.getElementById("ck_emkl").checked = true;
			$('#dlg_emkl').dialog('close');
		}

		function SET_notify(value,res){
			$('#dlg_notify').dialog('open').dialog('setTitle','SET '+res);
			$('#dg_notify').datagrid({
				url: '_get_notify.php',
				fitColumns: true,
				singleSelect: true,
				columns:[[
					{field:'NOTIFY_NO',title:'NOTIFY NO.',width:50,halign:'center', align:'center'},
					{field:'NAME',title:'NAME',width:200,halign:'center'},
					{field:'ADDR',title:'ADDRESS',width:250,halign:'center', align:'center'}
				]]
			});
			$('#dg_notify').datagrid('enableFilter');
		}

		var r_notify = [];
		function select_notify(){
			var arrNotify = [];
			var rows = $('#dg_notify').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				arrNotify.push({
					'NOTIFY_NO': rows[i].CONSIGNEE_NO,
					'NAME': rows[i].NAME,
					'ADDR': rows[i].ADDR,
					'ADDR1': rows[0].ADDR1,
					'ADDR2': rows[0].ADDR2,
					'ADDR3': rows[0].ADDR3,
					'TEL': rows[0].TEL,
					'FAX': rows[0].FAX,
					'ATTN': rows[0].ATTN
				});
			}

			r_notify = arrNotify;
			// console.log(JSON.stringify(r_notify));
			$('#res_notify').textbox('setValue', JSON.stringify(r_notify));
			document.getElementById("ck_notify").checked = true;
			$('#dlg_notify').dialog('close');
		}

		function SET_notify2(value,res){
			$('#dlg_notify2').dialog('open').dialog('setTitle','SET '+res);
			$('#dg_notify2').datagrid({
				url: '_get_notify.php',
				fitColumns: true,
				singleSelect: true,
				columns:[[
					{field:'NOTIFY_NO',title:'NOTIFY NO.',width:50,halign:'center', align:'center'},
					{field:'NAME',title:'NAME',width:200,halign:'center'},
					{field:'ADDR',title:'ADDRESS',width:250,halign:'center', align:'center'}
				]]
			});
			$('#dg_notify2').datagrid('enableFilter');
		}

		var r_notify2 = [];
		function select_notify2(){
			var arrNotify2 = [];
			var rows = $('#dg_notify2').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				arrNotify2.push({
					'NOTIFY_NO': rows[i].CONSIGNEE_NO,
					'NAME': rows[i].NAME,
					'ADDR': rows[i].ADDR,
					'ADDR1': rows[0].ADDR1,
					'ADDR2': rows[0].ADDR2,
					'ADDR3': rows[0].ADDR3,
					'TEL': rows[0].TEL,
					'FAX': rows[0].FAX,
					'ATTN': rows[0].ATTN
				});
			}

			r_notify2 = arrNotify2;
			// console.log(JSON.stringify(r_notify2));
			$('#res_notify2').textbox('setValue', JSON.stringify(r_notify2));
			document.getElementById("ck_notify2").checked = true;
			$('#dlg_notify2').dialog('close');
		}

		function SET_consignee(value, res){
			$('#dlg_consignee').dialog('open').dialog('setTitle','SET '+res);
			$('#dg_consignee').datagrid({
				url: '_get_consignee.php',
				fitColumns: true,
				singleSelect: true,
				columns:[[
					{field:'CONSIGNEE_NO',title:'CONSIGNEE NO.',width:10,halign:'center', align:'center'},
					{field:'NAME',title:'NAME',width:200,halign:'center', align:'center'},
					{field:'ADDR',title:'ADDRESS',width:250,halign:'center', align:'center'}
				]]
			});
			$('#dg_consignee').datagrid('enableFilter');
		}

		var r_consignee = [];

		function select_consignee(){
			var arrConsignee = [];
			var rows = $('#dg_consignee').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				arrConsignee.push({
					'CONSIGNEE_NO': rows[i].CONSIGNEE_NO,
					'NAME': rows[i].NAME,
					'ADDR': rows[i].ADDR
				});
			}

			r_consignee = arrConsignee;
			// console.log(JSON.stringify(r_consignee));
			$('#res_consignee').textbox('setValue', JSON.stringify(r_consignee));
			document.getElementById("ck_consignee").checked = true;
			$('#dlg_consignee').dialog('close');
		}

		var s_port=[];
		function SET_port_loading(value, res){
			$('#dlg_port_loading').dialog('open').dialog('setTitle','SET '+res);
			s_port = res;
			$('#dg_port_loading').datagrid({
				url: '_get_port_loading.php',
				fitColumns: true,
				singleSelect: true,
				columns:[[
					{field:'CODE',title:'CODE',width:45,halign:'center', align:'center'},
					{field:'NAME',title:'NAME',width:250,halign:'center'},
				]]
			});
			$('#dg_port_loading').datagrid('enableFilter');
		}

		var r_port_loading = [];
		var r_port_discharge = [];
		var r_final_dest = [];
		var r_port_place_del = [];

		function select_port_loading(){
			var arrPort_loading = [];
			var rows = $('#dg_port_loading').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				arrPort_loading.push({
					'CODE': rows[i].CODE,
					'NAME': rows[i].NAME
				});
			}

			if (s_port == 'PORT LOADING'){
				r_port_loading = arrPort_loading;
				// console.log(JSON.stringify(r_port_loading));
				$('#res_port_loading').textbox('setValue', JSON.stringify(r_port_loading));
				document.getElementById("ck_port_loading").checked = true;
			}else if (s_port == 'PORT DISCHARGE'){
				r_port_discharge = arrPort_loading;
				// console.log(JSON.stringify(r_port_discharge));
				$('#res_port_discharge').textbox('setValue', JSON.stringify(r_port_discharge));
				document.getElementById("ck_port_discharge").checked = true;
			}else if (s_port == 'FINAL DESTINATION'){
				r_final_dest = arrPort_loading;
				// console.log(JSON.stringify(r_final_dest));
				$('#res_final_dest').textbox('setValue', JSON.stringify(r_final_dest));
				document.getElementById("ck_final_dest").checked = true;
			}else if (s_port == 'PLACE OF DELIVERY'){
				r_port_place_del = arrPort_loading;
				// console.log(JSON.stringify(r_port_place_del));
				$('#res_place').textbox('setValue', JSON.stringify(r_port_place_del));
				document.getElementById("ck_place").checked = true;
			}

			
			$('#dlg_port_loading').dialog('close');
		}

		function SET_forwarder(value, res){
			$('#dlg_forwarder').dialog('open').dialog('setTitle','SET '+res);
			$('#dg_forwarder').datagrid({
				url: '_get_forwarder.php',
				fitColumns: true,
				singleSelect: true,
				columns:[[
					{field:'FORWARDER_NO',title:'SHIPPER NO.',width:45,halign:'center', align:'center'},
					{field:'NAME',title:'NAME.',width:200,halign:'center'},
					{field:'ADDR',title:'ADDRESS',width:250,halign:'center'}
				]],
				onClickRow:function(id,row){
	            	var v_forwarder = [];
					var rows = $('#dg_forwarder').datagrid('getSelections');
					v_forwarder.push(
						'FORWARDER_NO : '+rows[0].FORWARDER_NO+"\n"+
						'FORWARDER_NAME : '+rows[0].NAME+"\n"+
						'FORWARDER_ADDR : '+rows[0].ADDR+"\n"+
						'ADDR1 : '+rows[0].ADDR1+"\n"+
						'ADDR2 : '+rows[0].ADDR2+"\n"+
						'ADDR3 : '+rows[0].ADDR3+"\n"+
						'TEL : '+rows[0].TEL+"\n"+
						'FAX : '+rows[0].FAX+"\n"+
						'ATTN : '+rows[0].ATTN
					);
					//alert(ids.join('\n'));
	            	$('#txt_forwarder').textbox('setValue',v_forwarder);
				}
			});
			$('#dg_forwarder').datagrid('enableFilter');
		}

		var r_forwarder = [];

		function select_forwarder(){
			var arrforwarder = [];
			var rows = $('#dg_forwarder').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				arrforwarder.push({
					'FORWARDER_NO': rows[i].FORWARDER_NO,
					'FORWARDER_NAME': rows[i].NAME,
					'FORWARDER_ADDR': rows[i].ADDR,
					'ADDR1': rows[0].ADDR1,
					'ADDR2': rows[0].ADDR2,
					'ADDR3': rows[0].ADDR3,
					'TEL': rows[0].TEL,
					'FAX': rows[0].FAX,
					'ATTN': rows[0].ATTN
				});
			}
			r_forwarder = arrforwarder;
			// console.log(JSON.stringify(r_forwarder));
			$('#res_forwarder').textbox('setValue', JSON.stringify(r_forwarder));
			document.getElementById("ck_forwarder").checked = true;
			$('#dlg_forwarder').dialog('close');
		}

		function SET_shipper(value, res){
			$('#dlg_shipper').dialog('open').dialog('setTitle','SET '+res);
			$('#dg_shipper').datagrid({
				url: '_get_shipper.php',
				fitColumns: true,
				singleSelect: true,
				columns:[[
					{field:'SHIPPER_NO',title:'SHIPPER NO.',width:45,halign:'center', align:'center'},
					{field:'NAME',title:'NAME.',width:200,halign:'center'},
					{field:'ADDR',title:'ADDRESS',width:250,halign:'center'}
				]],
				onClickRow:function(id,row){
	            	var v_shipper = [];
					var rows = $('#dg_shipper').datagrid('getSelections');
					v_shipper.push(
						'SHIPPER_NO : '+rows[0].SHIPPER_NO+"\n"+
						'SHIPPER_NAME : '+rows[0].NAME+"\n"+
						'SHIPPER_ADDR : '+rows[0].ADDR+"\n"+
						'ADDR1 : '+rows[0].ADDR1+"\n"+
						'ADDR2 : '+rows[0].ADDR2+"\n"+
						'ADDR3: '+rows[0].ADDR3+"\n"+
						'TEL : '+rows[0].TEL+"\n"+
						'FAX :'+rows[0].FAX+"\n"+
						'ATTN :'+rows[0].ATTN+"\n"
					);
					//alert(ids.join('\n'));
	            	$('#txt_shipper').textbox('setValue',v_shipper.join("\n"));
				}
			});
			$('#dg_shipper').datagrid('enableFilter');
		}

		var r_shipper = [];

		function select_shipper(){
			var arrShipper = [];
			var rows = $('#dg_shipper').datagrid('getSelections');
			arrShipper.push({
				'SHIPPER_NO': rows[0].SHIPPER_NO,
				'SHIPPER_NAME': rows[0].NAME,
				'SHIPPER_ADDR': rows[0].ADDR,
				'ADDR1': rows[0].ADDR1,
				'ADDR2': rows[0].ADDR2,
				'ADDR3': rows[0].ADDR3,
				'TEL': rows[0].TEL,
				'FAX': rows[0].FAX,
				'ATTN': rows[0].ATTN
			});
			r_shipper = arrShipper;
			// console.log(JSON.stringify(r_shipper));
			$('#res_shipper').textbox('setValue', JSON.stringify(r_shipper));
			document.getElementById("ck_shipper").checked = true;
			$('#dlg_shipper').dialog('close');
		}

		function SET_cust_po_no(value, res){
			$('#dlg_cust').dialog('open').dialog('setTitle','SET '+res);
			$('#dg_cust').datagrid({
				url: '_get_cust_po_no.php',
				fitColumns: true,
				singleSelect: false,
				columns:[[
					{field:'CUSTOMER_PO_NO',title:'CUSTOMER<br/>PO NO.',width:75,halign:'center', align:'center'},
					{field:'SO_NO',title:'SO NO.',width:100,halign:'center', align:'center'},
					{field:'SO_DATE',title:'SO DATE',width:75,halign:'center', align:'center'},
					{field:'CONSIGNEE_FROM_JP',title:'CONSIGNEE<br/>FROM JP',width:100,halign:'center'},
					{field:'REMARK', title:'REMARK',width:100,halign:'center'}
				]],
				onClickRow:function(id,row){
	            	var ids = [];
					var rows = $('#dg_cust').datagrid('getSelections');
					for(var i=0; i<rows.length; i++){
						if(i == rows.length-1){
							ids.push(rows[i].CUSTOMER_PO_NO);
						}else{
							ids.push(rows[i].CUSTOMER_PO_NO+",");
						}
					}
					//alert(ids.join('\n'));
	            	$('#txt_cust').textbox('setValue',ids.join(" "));

				}
			});
			$('#dg_cust').datagrid('enableFilter');
		}

		var r_cust = '';

		function select_cust(){
			var arrCust = ''
			var rows = $('#dg_cust').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				if(i==rows.length-1){
					arrCust += rows[i].CUSTOMER_PO_NO;
				}else{
					arrCust += rows[i].CUSTOMER_PO_NO+", ";
				}
			}
			r_cust = {"cust_po_no" : arrCust};
			console.log(r_cust)
			console.log(r_cust["cust_po_no"])
			console.log(JSON.stringify(r_cust));
			$('#res_cust_po_no').textbox('setValue', JSON.stringify(r_cust));
			document.getElementById("ck_cust_po_no").checked = true;
			$('#dlg_cust').dialog('close');
		}

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
				// alert(src);
				$('#dg').datagrid('load', {
					src: search
				});
				
				$('#dg').datagrid('enableFilter');

				if (src=='') {
					filterData();
				};
				
				$('#dg').datagrid({
					url:'si_get.php'
				});

				$('#dg').datagrid('enableFilter');
		    }
		}

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

            $('#dg').datagrid({
				url:'si_get.php',
		    	singleSelect: true,
				rownumbers: true,
				sortName: 'po_date',
				sortOrder: 'asc',
			    columns:[[
				    {field:'SI_NO',title:'SI NO.',width:150, halign: 'center'},
					{field:'CUST_SI_NO',title:'SI NO.<br/>FROM CUST',width:150, halign: 'center'},
					{field:'CUST_PO_NO',title:'PO NO.<br/>CUSTOMER',width:200, halign: 'center'},
					{field:'PERSON_NAME',title:'PERSON IN CHARGE',width:150, halign: 'center'},
					{field:'GOODS_NAME',title:'DESCRIPTION',width:250, halign: 'center'},
					{field:'FORWARDER_NAME',title:'FORWARDER',width:150, halign: 'center'},
					{field:'CONSIGNEE_NAME',title:'CONSIGNEE',width:150, halign: 'center'},
					{field:'CR_DATE',title:'CR DATE',width:100, halign: 'center'}
			    ]]
            });

			$('#dg').datagrid('enableFilter');
        });

        function filterData(){
            var ck_date = 'false';
			var flag = 0;

            if($('#ck_date').attr("checked")){
				ck_date='true';
				flag += 1;
			}
			
			// if(flag == 1) {
			// 	$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
			// }

			$('#dg').datagrid('load', {
				date_awal: $('#date_awal').datebox('getValue'),
				date_akhir: $('#date_akhir').datebox('getValue'),
				ck_date: ck_date,
				src: ''
			});

			console.log('si_get.php?date_awal='+$('#date_awal').datebox('getValue')+'&date_akhir='+$('#date_akhir').datebox('getValue')+'&ck_date='+ck_date+'&src='+document.getElementById('src').value);

			$('#dg').datagrid({
				url:'si_get.php'
			});

			$('#dg').datagrid('enableFilter');
        }

		function addSI(){
			$('#win_add').window('open').window('setTitle','SHIPPING INSTRUCTION ENTRY');
			$('#win_add').window('center');
			$('#f_add').form('reset');
		}
    </Script>
	</body>
	</html>
<?php }else{
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}