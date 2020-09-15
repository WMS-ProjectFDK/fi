<?php
error_reporting(0);
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];

$sts = $_GET['sts'];
$si_no = $_GET['si_no'];

if($sts == 'new'){
	$s = 'SHIPPING INSTRUCTION ENTRY';
	$TITLE = $s;
	$post = 'si_save.php';
}elseif($sts == 'copy'){
    $s = 'SHIPPING INSTRUCTION NEW COPY';
	$TITLE = $s;
	$post = 'si_copy.php';
}elseif($sts == 'edit'){
	$s = 'SHIPPING INSTRUCTION EDIT';
	$TITLE = $s.' ('.$si_no.')';
    $post = 'si_edit.php';
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
		location.href = 'http://localhost:8088/fi/forms/si/si.php?id=1002';
	}

	function save(){
		if('<?=$sts?>' == 'new'){
			var itm = $('#item_no').numberbox('getValue');
			// alert(itm);
			if (itm != ''){
				$.ajax({
					type: 'GET',
					url: '../json/json_cek_master_item_no.php?item='+ $('#item_no').numberbox('getValue'),
					data: {kode:'kode'},
					success: function(data){
						if((data[0].kode)=='0'){
							$('#add').form('submit',{
								onSubmit: function(){
									return $(this).form ('validate');
								},
								success:function(data){
									$.messager.alert('Info', data, 'info');
									console.log(data);
									location.href = 'item.php?id=1190';
								}
							});
						}else{
							$.messager.alert("WARNING","ITEM NO. Already Exist..!!","warning");	
						}
					}
				});
			}else{
				$.messager.alert("WARNING","ITEM NO. not found..!!","warning");	
			}
		}else{
			$('#add').form('submit',{
				onSubmit: function(){
					return $(this).form ('validate');
				},
				success:function(data){
					$.messager.alert('Info', data, 'info');
					console.log(data);
					location.href = 'item.php?id=1190';
				}
			});
		}
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

	$(function(){
		var sts = '<?=$sts?>';
		if(sts == 'edit'){
			$('#item_no').numberbox('setValue','<?=$item_no?>');
			$('#item_code').textbox('setValue', '<?=$item_code?>');
			$('#item').textbox('setValue', '<?=$item?>');
			$('#item_flag').combobox('setValue', '<?=$item_flag?>');
			$('#description').textbox('setValue', '<?=$description?>');
			$('#class_code').combobox('setValue', '<?=$class_code?>');
			$('#origin_code').textbox('setValue', '<?=$origin_code?>');
			$('#curr_code').combobox('setValue', '<?=$curr_code?>');
			$('#external_unit_number').numberbox('setValue', '<?=$external_unit_number?>');
			$('#safety_stock').numberbox('setValue', '<?=$safety_stock?>');
			$('#uom_q').combobox('setValue', '<?=$uom_q?>');
			$('#unit_engineering').combobox('setValue', '<?=$unit_engineering?>');
			$('#unit_stock_rate').textbox('setValue', '<?=$unit_stock_rate?>');
			$('#unit_engineer_rate').textbox('setValue', '<?=$unit_engineer_rate?>');

			$('#weight').textbox('setValue', '<?=$weight?>');
			$('#uom_w').combobox('setValue', '<?=$uom_w?>');
			$('#uom_l').combobox('setValue', '<?=$uom_l?>');
			$('#drawing_no').textbox('setValue', '<?=$drawing_no?>');
			$('#drawing_rev').textbox('setValue', '<?=$drawing_rev?>');
			$('#applicable_model').textbox('setValue', '<?=$applicable_model?>');
			$('#catalog_no').textbox('setValue', '<?=$catalog_no?>');

			$('#standard_price').numberbox('setValue', '<?=$standard_price?>');
			$('#next_term_price').numberbox('setValue', '<?=$next_term_price?>');
			$('#suppliers_price').numberbox('setValue', '<?=$suppliers_price?>');
			$('#manufact_leadtime').numberbox('setValue', '<?=$manufact_leadtime?>');
			$('#purchase_leadtime').numberbox('setValue', '<?=$purchase_leadtime?>');
			$('#adjustment_leadtime').numberbox('setValue', '<?=$adjustment_leadtime?>');
			$('#labeling_to_pack_day').numberbox('setValue', '<?=$labeling_to_pack_day?>');
			$('#assembling_to_lab_day').numberbox('setValue', '<?=$assembling_to_lab_day?>');
			$('#issue_policy').combobox('setValue', '<?=$issue_policy?>');
			$('#issue_lot').numberbox('setValue', '<?=$issue_lot?>');
			$('#manufact_fail_rate').numberbox('setValue', '<?=$manufact_fail_rate?>');

			$('#section_code').combobox('setValue', '<?=$section_code?>');
			$('#stock_subject_code').combobox('setValue', '<?=$stock_subject_code?>');
			$('#cost_process_code').combobox('setValue', '<?=$cost_process_code?>');
			$('#cost_subject_code').combobox('setValue', '<?=$cost_subject_code?>');
			$('#customer_item_no').textbox('setValue', '<?=$customer_item_no?>');
			$('#customer_item_name').textbox('setValue', '<?=$customer_item_name?>');
			$('#order_policy').combobox('setValue', '<?=$order_policy?>');
			$('#maker_flag').combobox('setValue', '<?=$maker_flag?>');
			$('#mak').textbox('setValue', '<?=$mak?>');
			$('#item_type1').textbox('setValue', '<?=$item_type1?>');
			$('#item_type2').textbox('setValue', '<?=$item_type2?>');
			$('#package_unit_number').numberbox('setValue', '<?=$package_unit_number?>');
			$('#unit_package').combobox('setValue', '<?=$unit_package?>');

			$('#unit_price_o').numberbox('setValue', '<?=$unit_price_o?>');
			$('#unit_price_rate').numberbox('setValue', '<?=$unit_price_rate?>');
			$('#unit_curr_code').combobox('setValue', '<?=$unit_curr_code?>');
			$('#customer_type').textbox('setValue', '<?=$customer_type?>');
			$('#package_type').combobox('setValue', '<?=$package_type?>');
			$('#capacity').numberbox('setValue', '<?=$capacity?>');
			$('#date_code_type').textbox('setValue', '<?=$date_code_type?>');
			$('#date_code_month').numberbox('setValue', '<?=$date_code_month?>');
			$('#label_type').combobox('setValue', '<?=$label_type?>');
			$('#measurement').numberbox('setValue', '<?=$measurement?>');

			$('#inner_box_height').numberbox('setValue', '<?=$inner_box_height?>');
			$('#inner_box_width').numberbox('setValue', '<?=$inner_box_width?>');
			$('#inner_box_depth').numberbox('setValue', '<?=$inner_box_depth?>');
			$('#inner_box_unit_number').numberbox('setValue', '<?=$inner_box_unit_number?>');
			$('#medium_box_height').numberbox('setValue', '<?=$medium_box_height?>');
			$('#medium_box_width').numberbox('setValue', '<?=$medium_box_width?>');
			$('#medium_box_depth').numberbox('setValue', '<?=$medium_box_depth?>');
			$('#medium_box_unit_number').numberbox('setValue', '<?=$medium_box_unit_number?>');
			$('#outer_box_height').numberbox('setValue', '<?=$outer_box_height?>');
			$('#outer_box_width').numberbox('setValue', '<?=$outer_box_width?>');
			$('#outer_box_depth').numberbox('setValue', '<?=$outer_box_depth?>');
			$('#ctn_gross_weight').numberbox('setValue', '<?=$ctn_gross_weight?>');

			$('#pi_no').textbox('setValue', '<?=$pi_no?>');
			$('#plt_spec_no').textbox('setValue', '<?=$plt_spec_no?>');
			$('#pallet_size_type').textbox('setValue', '<?=$pallet_size_type?>');
			$('#pallet_ctn_number').textbox('setValue', '<?=$pallet_ctn_number?>');
			$('#pallet_step_ctn_number').textbox('setValue', '<?=$pallet_step_ctn_number?>');
			$('#pallet_height').textbox('setValue', '<?=$pallet_height?>');
			$('#pallet_width').textbox('setValue', '<?=$pallet_width?>');
			$('#pallet_depth').textbox('setValue', '<?=$pallet_depth?>');
			$('#pallet_unit_number').textbox('setValue', '<?=$pallet_unit_number?>');

			$('#operation_time').numberbox('setValue', '<?=$operation_time?>');
			$('#man_power').textbox('setValue', '<?=$man_power?>');
			$('#aging_day').textbox('setValue', '<?=$aging_day?>');

			// $('#item_no').numberbox('disable');
			$('#item_no').numberbox({editable:false})
		}
	});
</script>
</head>
<body>
<?php include ('../../ico_logout.php'); ?>

<div style="margin:20px 0;">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="save()">SAVE</a>
	<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="clearForm()">CLEAR</a>
	<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="backForm()">BACK</a>
</div>
<div class="easyui-panel" title="<?php echo $TITLE; ?>" style="width:100%;max-width:100%;padding:10px 10px;">
<div id="dlg_pi" class="easyui-dialog" style="width:920px;height:500px;" closed="true" buttons="#dlg-buttons-pi" data-options="modal:true">
	<table id="dg_pi" class="easyui-datagrid"></table>
</div>

<div id="dlg-buttons-pi">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="select _pi()" style="width:90px">SELECT</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_pi').dialog('close');" style="width:90px">Cancel</a>
</div>

<form id="add" method="post" action="<?=$post;?>" data-options="novalidate:true">
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>SI NO.</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">SI NO.</span>
			<input style="width:200px;" name="si_no" id="si_no" class="easyui-numberbox" readonly/>
			<span style="width:180px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">CONTRACT NO.</span>
            <input style="width:200px;" name="item_code" id="item_code" class="easyui-textbox"/>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="pi_sett()">SET</a>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">CUSTOMER PO NO.</span>
            <input style="width:742px;height: 80px;" name="item" id="item" class="easyui-textbox" multiline="true"/>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="pi_sett()">SET</a>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">SI NO. FROM CUST</span>
			<input style="width:360px;" name="description" id="description" class="easyui-textbox"/> 
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">PERSON IN CHARGE</span>
			<input style="width:200px;" name="description" id="description" class="easyui-textbox"/> 
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">DESCRIPTION OF GOODS</span>
			<input style="width:742px;" name="description" id="description" class="easyui-textbox"/> 
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>SHIPPER</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">SHIPPER</span>
            <input style="width:250px;" name="weight" id="weight" class="easyui-numberbox"/>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="pi_sett()">SET</a>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ADDRESS 1</span>
			<input style="width:250px;" name="drawing_no" id="drawing_no" class="easyui-textbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:130px;display:inline-block;">ADDRESS 2</span>
            <input style="width:250px;" name="drawing_rev" id="drawing_rev" class="easyui-textbox"/>
            <span style="width:20px;display:inline-block;"></span>
			<span style="width:130px;display:inline-block;">ADDRESS 3</span>
			<input style="width:250px;" name="drawing_rev" id="drawing_rev" class="easyui-textbox"/>
		</div>
		<div class="fitem">
            <span style="width:150px;display:inline-block;">TEL NO.</span>
			<input style="width:250px;" name="drawing_no" id="drawing_no" class="easyui-textbox"/>
			<span style="width:20px;display:inline-block;"></span>
			<span style="width:130px;display:inline-block;">FAX NO.</span>
            <input style="width:250px;" name="drawing_rev" id="drawing_rev" class="easyui-textbox"/>
            <span style="width:20px;display:inline-block;"></span>
			<span style="width:130px;display:inline-block;">ATTN</span>
			<input style="width:250px;" name="drawing_rev" id="drawing_rev" class="easyui-textbox"/>
		</div>
    </fieldset>
    <fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;"><legend><span class="style3"><strong>PORT</strong></span></legend>
        <div class="fitem">
			<span style="width:150px;display:inline-block;">SHIPPER</span>
            <input style="width:250px;" name="weight" id="weight" class="easyui-numberbox"/>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="pi_sett()">SET</a>
		</div>
    </fieldset>
</form>
</div>
</body>
</html>