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
<title>MASTER ITEM</title>
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
<?php 
    include ('../../ico_logout.php');
    $exp = explode('-', access_log($menu_id,$user_name));
?>

<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:500px;border-radius:4px;height: 70px;"><legend><span class="style3"><strong>ITEM FILTER</strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item No.</span>
			<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
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
	</fieldset>

	<fieldset style="position:absolute;margin-left:530px;border-radius:4px;width: 750px;height: 70px;"><legend><span class="style3"><strong>OTHER FILTER & PRINT</strong></span></legend>
		
	</fieldset>
	<div style="clear:both;margin-bottom:0px;"></div>
	<div style="padding:5px 6px;">
       <span style="width:50px;display:inline-block;">search</span>
	   <input style="width:180px; height: 18px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" />
	   <a href="javascript:void(0)" style="width: 180px;" class="easyui-linkbutton c2" onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
	   <a href="javascript:void(0)" style="width: 180px;" id="add" class="easyui-linkbutton c2" onclick="add_item()"><i class="fa fa-plus" aria-hidden="true"></i> ADD ITEM</a>
	   <a href="javascript:void(0)" style="width: 180px;" id="edit" class="easyui-linkbutton c2" onclick="edit_item()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT ITEM</a>
	   <a href="javascript:void(0)" style="width: 190px;" id="delete" class="easyui-linkbutton c2" onclick="delete_item()"><i class="fa fa-trash" aria-hidden="true"></i> REMOVE ITEM</a>
	</div>
</div>

<table id="dg" title="MASTER ITEM" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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
			$('#dg').datagrid('load', {
				src: search
			});
			
			$('#dg').datagrid('enableFilter');

			if (src == '') {
				filterData();
			};
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
			}else{
				$('#date_awal').datebox('enable');
				$('#date_akhir').datebox('enable');
			}
		});

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
			url: 'item_get.php',
		    columns:[[
			    {field:'ITEM_NO',title:'ITEM NO.', halign:'center', width:50, sortable: true},
                {field:'ITEM', title:'ITEM', halign:'center', width:100}, 
				{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:200, sortable: true},
				{field:'STOCK_SUBJECT', title:'SUBJECT', halign:'center', width:100},
				{field:'MAK', title:'MAKER', halign:'center', width:100},
				{field:'CLASS', title:'CLASS', halign:'center', width:100}
			]]
		});
	});

	function filterData(){
		var ck_item_no = "false";
		var flag = 0;

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
			flag += 1;
		};

		if(flag == 5) {
			$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
		}

		$('#dg').datagrid('load', {
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no,
			txt_item_name: $('#txt_item_name').textbox('getValue'),
			src: ''
		});
		
		$('#dg').datagrid('enableFilter');
	}

	function add_item(){
        location.href = 'item_add.php?sts=new&item_no=&item_desc=&data=';
	}

	function edit_item(){
        var dataRows = [];
		var row = $('#dg').datagrid('getSelected');
        if(row){
            dataRows.push({
				item_no: row.ITEM_NO,
				item_code: row.ITEM_CODE,
				item: row.ITEM,
				item_flag: row.ITEM_FLAG,
				description: row.DESCRIPTION,
				class_code: row.CLASS_CODE,
				origin_code: row.ORIGIN_CODE,
				curr_code: row.CURR_CODE,
				external_unit_number: row.EXTERNAL_UNIT_NUMBER,
				safety_stock: row.SAFETY_STOCK,
				uom_q: row.UOM_Q,
				unit_engineering: row.UNIT_ENGINEERING,
				unit_stock_rate: row.UNIT_STOCK_RATE,
				unit_engineer_rate: row.UNIT_ENGINEER_RATE,
				weight: row.WEIGHT,
				uom_w: row.UOM_W,
				uom_l: row.UOM_L,
				drawing_no: row.DRAWING_NO,
				drawing_rev: row.DRAWING_REV,
				applicable_model: row.APPLICABLE_MODEL,
				catalog_no: row.CATALOG_NO,
				standard_price: row.STANDARD_PRICE,
				next_term_price: row.NEXT_TERM_PRICE,
				suppliers_price: row.SUPPLIERS_PRICE,
				manufact_leadtime: row.MANUFACT_LEADTIME,
				purchase_leadtime: row.PURCHASE_LEADTIME,
				adjustment_leadtime: row.ADJUSTMENT_LEADTIME,
				labeling_to_pack_day: row.LABELING_TO_PACK_DAY,
				assembling_to_lab_day: row.ASSEMBLING_TO_LAB_DAY,
				issue_policy: row.ISSUE_POLICY,
				issue_lot: row.ISSUE_LOT,
				manufact_fail_rate: row.MANUFACT_FAIL_RATE,
				section_code: row.SECTION_CODE,
				stock_subject_code: row.STOCK_SUBJECT_CODE,
				cost_process_code: row.COST_PROCESS_CODE,
				cost_subject_code: row.COST_SUBJECT_CODE,
				customer_item_no: row.CUSTOMER_ITEM_NO,
				customer_item_name: row.CUSTOMER_ITEM_NAME,
				order_policy: row.ORDER_POLICY,
				maker_flag: row.MAKER_FLAG,
				mak: row.MAK,
				item_type1: row.ITEM_TYPE1,
				item_type2: row.ITEM_TYPE2,
				package_unit_number: row.PACKAGE_UNIT_NUMBER,
				unit_package: row.UNIT_PACKAGE,
				unit_price_o: row.UNIT_PRICE_O,
				unit_price_rate: row.UNIT_PRICE_RATE,
				unit_curr_code: row.UNIT_CURR_CODE,
				customer_type: row.CUSTOMER_TYPE,
				package_type: row.PACKAGE_TYPE,
				capacity: row.CAPACITY,
				date_code_type: row.DATE_CODE_TYPE,
				date_code_month: row.DATE_CODE_MONTH,
				label_type: row.LABEL_TYPE,
				measurement: row.MEASUREMENT,
				inner_box_height: row.INNER_BOX_HEIGHT,
				inner_box_width: row.INNER_BOX_WIDTH,
				inner_box_depth: row.INNER_BOX_DEPTH,
				inner_box_unit_number: row.INNER_BOX_UNIT_NUMBER,
				medium_box_height: row.MEDIUM_BOX_HEIGHT,
				medium_box_width: row.MEDIUM_BOX_WIDTH,
				medium_box_depth: row.MEDIUM_BOX_DEPTH,
				medium_box_unit_number: row.MEDIUM_BOX_UNIT_NUMBER,
				outer_box_height: row.OUTER_BOX_HEIGHT,
				outer_box_width: row.OUTER_BOX_WIDTH,
				outer_box_depth: row.OUTER_BOX_DEPTH,
				ctn_gross_weight: row.CTN_GROSS_WEIGHT,
				pi_no: row.PI_NO,
				operation_time: row.OPERATION_TIME,
				man_power: row.MAN_POWER,
				aging_day: row.AGING_DAY,
          });
            var myJSON=JSON.stringify(dataRows);
            var str_unescape=unescape(myJSON);

            console.log('item_add.php?sts=edit&item_no='+row.ITEM_NO+'&item_desc='+row.DESCRIPTION+'&data='+str_unescape);

            location.href = 'item_add.php?sts=edit&item_no='+row.ITEM_NO+'&item_desc='+row.DESCRIPTION+'&data='+unescape(str_unescape);
        }
	}

	function delete_item(){
		var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Are you sure you want to destroy this Item?',function(r){
				if (r){
					$.messager.progress({title:'Please waiting', msg:'removing data...'});
					$.post('item_destroy.php',{id:row.ITEM_NO},function(result){
					    if (result.success){
					    	$.messager.progress('close');
					        $('#dg').datagrid('reload');
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
        	$.messager.show({title: 'MASTER ITEM',msg: 'Data not Selected'});
        }
	}
</script>
</body>
</html>