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
<title>ITEM ENTRY</title>
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
<div style="margin:20px 0;">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="save">SAVE</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="clearForm()">CLEAR</a>
</div>
<div class="easyui-panel" title="ITEM ENTRY" style="width:100%;max-width:100%;padding:10px 10px;">
<form id="add" method="post">
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM NAME</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ITEM NO.</span>
			<input style="width:200px;" name="item_no" id="item_no" class="easyui-numberbox" required/> 
			<label><input type="checkbox" name="ck_make_ng" id="ck_make_ng" checked="true">Make an NG Materials </input></label>
			<span style="width:25px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">ITEM CODE</span>
			<input style="width:200px;" name="item_code" id="item_code" class="easyui-numberbox"/> 
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ITEM NAME</span>
			<input style="width:360px;" name="item_name" id="item_name" class="easyui-numberbox" required/> 
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">ITEM FLAG</span>
			<select style="width:200px;" name="cmb_item_flag" id="cmb_item_flag" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">DESCRIPTION</span>
			<input style="width:360px;" name="item_description" id="item_description" class="easyui-numberbox"/> 
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">CLASS CODE</span>
			<select style="width:200px;" name="cmb_class_code" id="cmb_class_code" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'" required></select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ORIGIN CODE</span>
			<select style="width:200px;" name="cmb_origin_code" id="cmb_origin_code" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
			<span style="width:180px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">CURRENCY</span>
			<select style="width:200px;" name="cmb_curr_no" id="cmb_curr_no" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">EXT. UNIT NO.</span>
			<input style="width:200px;" name="ext_unit_no" id="ext_unit_no" class="easyui-numberbox"/>
			<span style="width:180px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">SAFETY STOCK</span>
			<input style="width:200px;" name="safety_stock" id="safety_stock" class="easyui-numberbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">UNIT STOCK</span>
			<select style="width:100px;" name="cmb_unit_stock" id="cmb_unit_stock" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
			<span style="width:150px;display:inline-block;">UNIT OF ENGINERRING</span>
			<select style="width:100px;" name="cmb_unit_enginerring" id="cmb_unit_enginerring" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
			<span style="display:inline-block;">Unit Stock Rate:Unit Engineer Rate</span>
			<input style="width:50px;" name="ext_unit_no" id="ext_unit_no" class="easyui-numberbox"/>:
			<input style="width:50px;" name="ext_unit_no" id="ext_unit_no" class="easyui-numberbox"/>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM DESIGN</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">WEIGHT</span>
			<input style="width:200px;" name="weight_item" id="weight_item" class="easyui-numberbox"/> 
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">UNIT of WEIGHT</span>
			<select style="width:100px;" name="cmb_unit_weight" id="cmb_unit_weight" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'" required></select>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">UNIT of MEASUREMENT</span>
			<select style="width:100px;" name="cmb_unit_msm" id="cmb_unit_msm" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'" required></select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">DRAWING NO.</span>
			<input style="width:200px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>
			<span style="width:180px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">DRAWING REV.</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">APPLICABLE MODEL</span>
			<input style="width:200px;" name="applicable_model" id="applicable_model" class="easyui-numberbox"/>
			<span style="width:180px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">CATALOG NO.</span>
			<input style="width:200px;" name="catalog_no" id="catalog_no" class="easyui-numberbox"/>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM PRICE & LEAD TIME</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">STANDARD PRICE</span>
			<input style="width:200px;" name="standard_price" id="standard_price" class="easyui-numberbox"/> 
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">NEXT TERM PRICE</span>
			<input style="width:200px;" name="next_term_price" id="next_term_price" class="easyui-numberbox"/> 
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">SUPPLIER PRICE</span>
			<input style="width:200px;" name="supplier_price" id="supplier_price" class="easyui-numberbox"/> 
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">MANUFACT LEAD TIME</span>
			<input style="width:170px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>Days
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">PURCHASE LEAD TIME</span>
			<input style="width:170px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>Days
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">ADJUST. LEAD TIME</span>
			<input style="width:170px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>Days
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">Labeling To Packing Day</span>
			<input style="width:170px;" name="applicable_model" id="applicable_model" class="easyui-numberbox"/>Days
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">Assy To Labeling Day</span>
			<input style="width:170px;" name="catalog_no" id="catalog_no" class="easyui-numberbox"/>Days
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM COST & CUSTOMER</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">SECTION CODE</span>
			<select style="width:200px;" name="cmb_unit_weight" id="cmb_unit_weight" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'" required></select>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">STOCK SUBJECT CODE</span>
			<input style="width:200px;" name="weight_item" id="weight_item" class="easyui-numberbox"/> 
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">COST PROCESS CODE</span>
			<select style="width:200px;" name="cmb_unit_weight" id="cmb_unit_weight" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'" required></select>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">COST SUBJECT CODE</span>
			<select style="width:200px;" name="cmb_unit_weight" id="cmb_unit_weight" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'" required></select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">CUST. ITEM CODE</span>
			<input style="width:200px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">CUST. ITEM NAME</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ORDER POLICY</span>
			<select style="width:200px;" name="cmb_unit_weight" id="cmb_unit_weight" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">MAKER FLAG</span>
			<select style="width:200px;" name="cmb_unit_weight" id="cmb_unit_weight" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">MAKER</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ITEM TYPE-1</span>
			<input style="width:200px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">ITEM TYPE-2</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">PACKAGE UNIT NO.</span>
			<input style="width:95px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox" required/>
			<select style="width:100px;" name="cmb_unit_weight" id="cmb_unit_weight" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM UNIT PRICE</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">UNIT PRICE (ORG)</span>
			<input style="width:200px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">UNIT PRICE RATE</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">UNIT CURR CODE</span>
			<input style="width:95px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox" required/>
			<select style="width:100px;" name="cmb_unit_weight" id="cmb_unit_weight" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">CUSTOMER TYPE</span>
			<input style="width:200px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">PACKAGE TYPE</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">CAPACITY</span>
			<input style="width:200px;" name="drawing_no" id="drawing_no" class="easyui-numberbox" required/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">DATE CODE TYPE</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">DATE CODE MONTH</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">PACKAGING GROUP</span>
			<select style="width:200px;" name="cmb_unit_weight" id="cmb_unit_weight" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'" required></select>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">MEASUREMENT</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM SET BOX</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;"></span>
			<span style="width:200px;display:inline-block;">HEIGHT</span>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:200px;display:inline-block;">WIDTH</span>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:200px;display:inline-block;">DEPTH</span>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:200px;display:inline-block;">UNIT NO.</span>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">INNER BOX</span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>PC
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">MEDIUM BOX</span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>PC
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">OUTER BOX</span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>PC
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">CTN GROSS WEIGHT</span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>PC
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM PACKAGING INFORMATION</strong></span></legend>
		<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="save">SET</a>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">PALLET SPEC NO.</span>
			<input style="width:200px;" name="drawing_no" id="drawing_no" class="easyui-numberbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">PALLET SIZE TYPE</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-numberbox"/>
		</div>
	</fieldset>
</form>
</div>
</body>
</html>