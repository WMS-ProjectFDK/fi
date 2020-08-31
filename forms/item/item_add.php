<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$sts = $_GET['sts'];//=edit
$item_no = $_GET['item_no'];//=22396
$item_desc = $_GET['item_desc'];

if($sts == 'new'){
	$s = 'ITEM ENTRY';
}else{
	$s = 'ITEM EDIT';
}

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

<script type="text/javascript">
	function clearForm(){
		$('#add').form('clear');
	}

	function backForm(){
		location.href = 'item.php';
	}

	function save(){
		$('#add').form('submit',{
			onSubmit: function () {
				return $ (this) .form ('validate');
			},
			success:function(data){
				$.messager.alert('Info', data, 'info');
				console.log(data);
			}
		});
	}

	function pi_sett(){
		$('#dlg_pi').dialog('open').dialog('setTitle','SELECT PACKING INFORMATION');
		$('#dg_pi').datagrid({
			url:'set_pi.php',
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			columns:[[
				{field:'PI_NO',title:'PI No.', halign:'center', width:150,},
				{field:'PLT_SPEC_NO',title:'PALLET SPEC', halign:'center', width:150},
				{field:'PALLET_UNIT_NUMBER', title:'PALLET QTY', halign:'center', align:'right', width:100},
				{field:'PALLET_CTN_NUMBER', title:'CARTON QTY', halign:'center', align:'right', width:100}, 
				{field:'PALLET_STEP_CTN_NUMBER', title:'CARTON STEP', halign:'center', align:'right', width:100}
			]],
			onClickRow:function(id,row){
				$('#pi_no').textbox('setValue', row.PI_NO);
				$('#plt_spec_no').textbox('setValue', row.PLT_SPEC_NO);
				$('#pallet_size_type').textbox('setValue', row.PALLET_SIZE_TYPE);
				$('#pallet_ctn_number').textbox('setValue', row.PALLET_CTN_NUMBER);
				$('#pallet_step_ctn_number').textbox('setValue', row.PALLET_STEP_CTN_NUMBER);
				$('#pallet_height').textbox('setValue', row.PALLET_HEIGHT);
				$('#pallet_width').textbox('setValue', row.PALLET_WIDTH);
				$('#pallet_depth').textbox('setValue', row.PALLET_DEPTH);
				$('#pallet_unit_number').textbox('setValue', row.PALLET_UNIT_NUMBER);
			}
		});

		$('#dg_pi').datagrid('enableFilter');
	}
</script>
</head>
<body>
<?php include ('../../ico_logout.php'); ?>

<div style="margin:20px 0;">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="save()">SAVE</a>
	<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="clearForm()">CLEAR</a>
	<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="backForm()">BACK</a>
</div>
<div class="easyui-panel" title="<?php echo $s.' ('.$item_no.' - '.$item_desc.')'; ?>" style="width:100%;max-width:100%;padding:10px 10px;">
<div id="dlg_pi" class="easyui-dialog" style="width:920px;height:500px;" closed="true" buttons="#dlg-buttons-pi" data-options="modal:true">
	<table id="dg_pi" class="easyui-datagrid"></table>
</div>

<div id="dlg-buttons-pi">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="select _pi()" style="width:90px">SELECT</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_pi').dialog('close');" style="width:90px">Cancel</a>
</div>

<form id="add" method="post" action="save_item.php" data-options="novalidate:true">
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM NAME</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">ITEM NO.</span>
			<input style="width:200px;" name="item_no" id="item_no" class="easyui-numberbox" required/> 
			<label><input type="checkbox" name="ck_make_ng" id="ck_make_ng" checked="true">Make an NG Materials </input></label>
			<span style="width:25px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">ITEM CODE</span>
			<input style="width:200px;" name="item_code" id="item_code" class="easyui-textbox"/> 
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ITEM NAME</span>
			<input style="width:360px;" name="item" id="item" class="easyui-textbox" required/> 
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">ITEM FLAG</span>
			<select style="width:200px;" name="item_flag" id="item_flag" class="easyui-combobox" data-options="panelHeight:'auto'">
				<option value="0"></option>
				<option value="1">1. Finished Good or WIP</option>
				<option value="2">2. Material</option>
			</select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">DESCRIPTION</span>
			<input style="width:360px;" name="description" id="description" class="easyui-textbox"/> 
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">CLASS CODE</span>
			<select style="width:200px;" name="class_code" id="class_code" class="easyui-combobox" data-options=" url:'../json/json_class.php', method:'get', valueField:'class_code', textField:'class', panelHeight:'75px'" required></select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ORIGIN CODE</span>
			<select style="width:200px;" name="origin_code" id="origin_code" class="easyui-combobox" data-options="panelHeight:'75px'">
				<option Value=""></option>
				<option Value="601">AUSTRALIA</option>
				<option Value="208">BELGIUM</option>
				<option Value="410">BRAZIL</option>
				<option Value="308">CANADA</option>
				<option Value="409">CHILE</option>
				<option Value="105">CHINA</option>
				<option Value="506">EGYPT</option>
				<option Value="210">FRANCE</option>
				<option Value="213">GERMANY</option>
				<option Value="300">GREECE</option>
				<option Value="108">HONG KONG</option>
				<option Value="123">INDIA</option>
				<option Value="118" SELECTED >INDONESIA</option>
				<option Value="143">ISRAEL</option>
				<option Value="192">JAPAN</option>
				<option Value="144">JORDAN</option>
				<option Value="103">KOREA</option>
				<option Value="237">LITHUANIA</option>
				<option Value="113">MALAYSIA</option>
				<option Value="305">MEXICO</option>
				<option Value="131">NEPAL</option>
				<option Value="207">NETHERLANDS</option>
				<option Value="606">NEW ZEALAND</option>
				<option Value="141">OMAN</option>
				<option Value="407">PERU</option>
				<option Value="117">PHILLIPINES</option>
				<option Value="223">POLAND</option>
				<option Value="217">PORTUGAL</option>
				<option Value="112">SINGAPORE</option>
				<option Value="218">SPAIN</option>
				<option Value="125">SRI LANKA</option>
				<option Value="215">SWITZERLAND</option>
				<option Value="106">TAIWAN</option>
				<option Value="111">THAILAND</option>
				<option Value="147">UNITED ARAB EMIRATES</option>
				<option Value="205">UNITED KINGDOM/UK</option>
				<option Value="304">USA</option>
			</select>
			<span style="width:180px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">CURRENCY</span>
			<select style="width:200px;" name="curr_code" id="curr_code" class="easyui-combobox" data-options="panelHeight:'75px'">
				<option value=''></option>
				<option value='23' >RP </option>
				<option value='1' selected>US$</option>
				<option value='5' >SG$</option>
				<option value='8' >YEN</option>
				<option value='7' >EURO</option>
			</select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">EXT. UNIT NO.</span>
			<input style="width:200px;" name="external_unit_number" id="external_unit_number" class="easyui-numberbox"/>
			<span style="width:180px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue">SAFETY STOCK</span>
			<input style="width:200px;" name="safety_stock" id="safety_stock" class="easyui-numberbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">UNIT STOCK</span>
			<select style="width:100px;" name="uom_q" id="uom_q" class="easyui-combobox" data-options="panelHeight:'75px'">
				<option Value="" ></option>
				<option Value="130" >BTL</option>
				<option Value="110" >CC</option>
				<option Value="35" >KG</option>
				<option Value="75" >L</option>
				<option Value="70" >M</option>
				<option Value="140" >MM</option>
				<option Value="10" >PC</option>
				<option Value="300" >PK</option>
				<option Value="20" >PR</option>
				<option Value="60" >RL</option>
				<option Value="50" >SH</option>
				<option Value="80" >ST</option>
			</select>
			<span style="width:150px;display:inline-block;">UNIT OF ENGINERRING</span>
			<select style="width:100px;" name="unit_engineering" id="unit_engineering" class="easyui-combobox" data-options="panelHeight:'75px'">
				<option Value="" ></option>
				<option Value="130" >BTL</option>
				<option Value="110" >CC</option>
				<option Value="35" >KG</option>
				<option Value="75" >L</option>
				<option Value="70" >M</option>
				<option Value="140" >MM</option>
				<option Value="10" >PC</option>
				<option Value="300" >PK</option>
				<option Value="20" >PR</option>
				<option Value="60" >RL</option>
				<option Value="50" >SH</option>
				<option Value="80" >ST</option>
			</select>
			<span style="display:inline-block;">Unit Stock Rate:Unit Engineer Rate</span>
			<input style="width:50px;" name="unit_stock_rate" id="unit_stock_rate" class="easyui-textbox"/>:
			<input style="width:50px;" name="unit_engineer_rate" id="unit_engineer_rate" class="easyui-textbox"/>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM DESIGN</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">WEIGHT</span>
			<input style="width:200px;" name="weight" id="weight" class="easyui-numberbox"/> 
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">UNIT of WEIGHT</span>
			<select style="width:100px;" name="uom_w" id="uom_w" class="easyui-combobox" data-options="
			panelHeight:'auto'" required>
				<option Value=""></option>
				<option Value="120" >GM</option>
				<option Value="30" >KG</option>
			</select>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">UNIT of MEASUREMENT</span>
			<select style="width:100px;" name="uom_l" id="uom_l" class="easyui-combobox" data-options="panelHeight:'auto'" required>
				<option Value=""></option>
				<option Value="40">M3</option>
			</select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">DRAWING NO.</span>
			<input style="width:200px;" name="drawing_no" id="drawing_no" class="easyui-textbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">DRAWING REV.</span>
			<input style="width:200px;" name="drawing_rev" id="drawing_rev" class="easyui-textbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">APPLICABLE MODEL</span>
			<input style="width:200px;" name="applicable_model" id="applicable_model" class="easyui-textbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">CATALOG NO.</span>
			<input style="width:200px;" name="catalog_no" id="catalog_no" class="easyui-textbox"/>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM PRICE & LEAD TIME</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">STANDARD PRICE</span>
			<input style="width:200px;" name="standard_price" id="standard_price" class="easyui-numberbox"/> 
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">NEXT TERM PRICE</span>
			<input style="width:200px;" name="next_term_price" id="next_term_price" class="easyui-numberbox"/> 
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">SUPPLIER PRICE</span>
			<input style="width:200px;" name="suppliers_price" id="suppliers_price" class="easyui-numberbox"/> 
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">MANUFACT LEAD TIME</span>
			<input style="width:170px;" name="manufact_leadtime" id="manufact_leadtime" class="easyui-numberbox"/>Days
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">PURCHASE LEAD TIME</span>
			<input style="width:170px;" name="purchase_leadtime" id="purchase_leadtime" class="easyui-numberbox"/>Days
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">ADJUST. LEAD TIME</span>
			<input style="width:170px;" name="adjustment_leadtime" id="adjustment_leadtime" class="easyui-numberbox"/>Days
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">Labeling To Packing Day</span>
			<input style="width:170px;" name="labeling_to_pack_day" id="labeling_to_pack_day" class="easyui-numberbox"/>Days
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">Assy To Labeling Day</span>
			<input style="width:170px;" name="assembling_to_lab_day" id="assembling_to_lab_day" class="easyui-numberbox"/>Days
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ISSUE POLICY</span>
			<select style="width:200px;" name="issue_policy" id="issue_policy" class="easyui-combobox" data-options="panelHeight:'auto'">
				<option Value=""></option>
				<option Value="D" >GLOSS ISSUE</option>
				<option Value="A" >KIT ISSUE</option>
				<option Value="E" >REQUIRE ISSUE</option>
			</select>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">ISSUE LOT</span>
			<input style="width:200px;" name="issue_lot" id="issue_lot" class="easyui-numberbox"/> 
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">MANUFACT FAIL RATE</span>
			<input style="width:200px;" name="manufact_fail_rate" id="manufact_fail_rate" class="easyui-numberbox"/> 
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM COST & CUSTOMER</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">SECTION CODE</span>
			<select style="width:200px;" name="section_code" id="section_code" class="easyui-combobox" data-options="panelHeight:'auto'" required>
				<option Value=""></option>
				<option Value="100" >FI</option>
				<option Value="200" >YUSEN</option>
			</select>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">STOCK SUBJECT CODE</span>
			<select style="width:200px;" name="stock_subject_code" id="stock_subject_code" class="easyui-combobox" data-options="panelHeight:'75px'" required>
				<option Value=""></option>
				<option Value="6">Customer Supply Products</option>
				<option Value="5">Finished Goods</option>
				<option Value="9">LR03 Management</option>
				<option Value="8">LR6 Management</option>
				<option Value="7">Materials 2</option>
				<option Value="2">Packing Materials</option>
				<option Value="1">Raw Materials</option>
				<option Value="4">Semi Finished Goods</option>
				<option Value="0">Wooden Pallet</option>
				<option Value="3">Work in Process</option>
			</select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">COST PROCESS CODE</span>
			<select style="width:200px;" name="cost_process_code" id="cost_process_code" class="easyui-combobox" data-options=" url:'../json/json_cost_process_code.php', method:'get', valueField:'id', textField:'name', panelHeight:'75px'" required></select>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">COST SUBJECT CODE</span>
			<select style="width:200px;" name="cost_subject_code" id="cost_subject_code" class="easyui-combobox" data-options="panelHeight:'75px'" required>
				<option Value=""></option>
				<option Value="130040" >FINISH GOOD</option>
				<option Value="136040" >INDIRECT MATERIAL OI</option>
				<option Value="136030" >INDIRECT MATERIAL RM</option>
				<option Value="136020" >PACKING MATERIAL</option>
				<option Value="136010" >RAW MATERIAL</option>
			</select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">CUST. ITEM CODE</span>
			<input style="width:200px;" name="customer_item_no" id="customer_item_no" class="easyui-textbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">CUST. ITEM NAME</span>
			<input style="width:200px;" name="customer_item_name" id="customer_item_name" class="easyui-textbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ORDER POLICY</span>
			<select style="width:200px;" name="order_policy" id="order_policy" class="easyui-combobox" data-options="panelHeight:'auto'">
				<option Value=""></option>
				<option Value="2" >FIXED SIZE REORDER</option>
				<option Value="1" >MRP ORDER</option>
				<option Value="3" >REQUEST ORDER</option>
			</select>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">MAKER FLAG</span>
			<select style="width:200px;" name="maker_flag" id="maker_flag" class="easyui-combobox" data-options="panelHeight:'75px'">
				<option Value=""></option>
				<option Value="2" >MANUFACT</option>
				<option Value="4" >OVERSEA FACTORY</option>
				<option Value="5" >OVERSEA PURCHASE</option>
				<option Value="1" >PURCHASE</option>
				<option Value="3" >SUBCONTRACT</option>
			</select>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">MAKER</span>
			<input style="width:200px;" name="mak" id="mak" class="easyui-textbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ITEM TYPE-1</span>
			<input style="width:200px;" name="item_type1" id="item_type1" class="easyui-textbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">ITEM TYPE-2</span>
			<input style="width:200px;" name="item_type2" id="item_type2" class="easyui-textbox"/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">PACKAGE UNIT NO.</span>
			<input style="width:95px;" name="package_unit_number" id="package_unit_number" class="easyui-textbox" required/>
			<select style="width:100px;" name="unit_package" id="unit_package" class="easyui-combobox" data-options="panelHeight:'75px'" required>
				<option value=""></option>
				<option value='130' >BTL</option>
				<option value='110' >CC</option>
				<option value='35' >KG</option>
				<option value='75' >L</option>
				<option value='70' >M</option>
				<option value='140' >MM</option>
				<option value='10' >PC</option>
				<option value='300' >PK</option>
				<option value='20' >PR</option>
				<option value='60' >RL</option>
				<option value='50' >SH</option>
				<option value='80' >ST</option>
			</select>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM UNIT PRICE</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">UNIT PRICE (ORG)</span>
			<input style="width:200px;" name="unit_price_o" id="unit_price_o" class="easyui-numberbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">UNIT PRICE RATE</span>
			<input style="width:200px;" name="unit_price_rate" id="unit_price_rate" class="easyui-numberbox"/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">UNIT CURR CODE</span>
			<select style="width:100px;" name="unit_curr_code" id="unit_curr_code" class="easyui-combobox" data-options="panelHeight:'75px'">
				<option value=''></option>
				<option value='23' >RP </option>
				<option value='1' selected>US$</option>
				<option value='5' >SG$</option>
				<option value='8' >YEN</option>
				<option value='7' >EURO</option>
			</select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">CUSTOMER TYPE</span>
			<input style="width:200px;" name="customer_type" id="customer_type" class="easyui-textbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">PACKAGE TYPE</span>
			<select style="width:100px;" name="package_type" id="package_type" class="easyui-combobox" data-options="panelHeight:'75px'">
				<option value=''></option>
				<option value='A'>AUTO SHRINK LR6</option>
				<option value='A3'>AUTO SHRINK LR03</option>
				<option value='B'>BLISTER</option>
				<option value='B1'>BLISTER 1,2</option>
				<option value='B2'>BLISTER 3,4</option>
				<option value='B3'>BLISTER 5,6</option>
				<option value='C'>CLAMSHELL</option>
				<option value='D'>BLISTER LR03</option>
				<option value='E'>BLISTER LR6</option>
				<option value='F'>BLISTER 7</option>
				<option value='G'>BLOK SHRINK</option>
				<option value='H'>ASSY</option>
				<option value='I'>INSP</option>
				<option value='L'>MULTI SHRINK</option>
				<option value='M'>MPAC</option>
				<option value='N'>BULK</option>
				<option value='S'>SHRINK</option>
				<option value='S1'>MANUAL SHRINK</option>
				<option value='S2'>MANUAL SHRINK + BRCD</option>
				<option value='RECL'>RECL</option>
			</select>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">CAPACITY</span>
			<input style="width:200px;" name="capacity" id="capacity" class="easyui-textbox" required/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">DATE CODE TYPE</span>
			<input style="width:200px;" name="date_code_type" id="date_code_type" class="easyui-textbox"/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">DATE CODE MONTH</span>
			<input style="width:200px;" name="date_code_month" id="date_code_month" class="easyui-numberbox"/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">PACKAGING GROUP</span>
			<select style="width:200px;" name="label_type" id="label_type" class="easyui-combobox" data-options=" url:'../json/json_pack_grouping.php', method:'get', valueField:'LABEL_TYPE_CODE', textField:'LABEL_TYPE_NAME', panelHeight:'75px'" required></select>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">MEASUREMENT</span>
			<input style="width:200px;" name="measurement" id="measurement" class="easyui-numberbox"/>
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
			<span style="width:150px;display:inline-block;color:blue;">INNER BOX</span>
			<input style="width:177px;" name="inner_box_height" id="inner_box_height" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;color:blue;"></span>
			<input style="width:177px;" name="inner_box_width" id="inner_box_width" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;color:blue;"></span>
			<input style="width:177px;" name="inner_box_depth" id="inner_box_depth" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;color:blue;"></span>
			<input style="width:177px;" name="inner_box_unit_number" id="inner_box_unit_number" class="easyui-numberbox"/>PC
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">MEDIUM BOX</span>
			<input style="width:177px;" name="medium_box_unit_height" id="medium_box_unit_height" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="medium_box_unit_width" id="medium_box_unit_width" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="medium_box_unit_depth" id="medium_box_unit_depth" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="medium_box_unit_number" id="medium_box_unit_number" class="easyui-numberbox"/>PC
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">OUTER BOX</span>
			<input style="width:177px;" name="outer_box_unit_height" id="outer_box_unit_height" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="outer_box_unit_width" id="outer_box_unit_width" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="outer_box_unit_depth" id="outer_box_unit_depth" class="easyui-numberbox"/>mm
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">CTN GROSS WEIGHT</span>
			<input style="width:200px;" name="ctn_gross_weight" id="ctn_gross_weight" class="easyui-numberbox"/>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM PACKAGING INFORMATION</strong></span></legend>
		<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="pi_sett()">SET</a>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">PI NO.</span>
			<input style="width:200px;" name="pi_no" id="pi_no" class="easyui-textbox" required/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">PALLET SPEC NO.</span>
			<input style="width:200px;" name="plt_spec_no" id="plt_spec_no" class="easyui-textbox" required/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">PALLET SIZE TYPE</span>
			<input style="width:200px;" name="pallet_size_type" id="pallet_size_type" class="easyui-numberbox" required/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">CARTON NO.</span>
			<input style="width:200px;" name="pallet_ctn_number" id="pallet_ctn_number" class="easyui-numberbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">STEP CARTON NO.</span>
			<input style="width:200px;" name="pallet_step_ctn_number" id="pallet_step_ctn_number" class="easyui-numberbox"/>
		</div>
		<br/><br/>
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
			<span style="width:150px;display:inline-block;color:blue;">PALLET</span>
			<input style="width:177px;" name="pallet_height" id="pallet_height" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="pallet_width" id="pallet_width" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="pallet_depth" id="pallet_depth" class="easyui-numberbox"/>mm
			<span style="width:20px;display:inline-block;"></span>
			<input style="width:177px;" name="pallet_unit_number" id="pallet_unit_number" class="easyui-numberbox"/>PC
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;"> 
		<legend><span class="style3"><strong>ITEM SET AGING</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">OPERATION TIME</span>
			<input style="width:200px;" name="opertation_time" id="opertation_time" class="easyui-numberbox" required/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">MAN POWER</span>
			<input style="width:200px;" name="man_power" id="man_power" class="easyui-numberbox"/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;color:blue;">AGING DAY</span>
			<input style="width:200px;" name="aging_day" id="aging_day" class="easyui-numberbox" required/>
		</div>
	</fieldset>	
</form>
</div>
</body>
</html>