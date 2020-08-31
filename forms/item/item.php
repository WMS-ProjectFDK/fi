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
	<fieldset style="float:left;width:470px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>Materials Transaction Filter</strong></span></legend>
		<div style="width:470px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Date</span>
				<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to 
				<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip No.</span>
				<select style="width:190px;" name="cmb_slip_no" id="cmb_slip_no" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'SLIP_NO', panelHeight:'75px'"></select>
				<label><input type="checkbox" name="ck_slip_no" id="ck_slip_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Type</span>
				<select style="width:300px;" name="cmb_slip_type" id="cmb_slip_type" class="easyui-combobox" data-options=" url:'../json/json_sliptype.php', method:'get', valueField:'id', textField:'name', panelHeight:'auto'"></select>
				<label><input type="checkbox" name="ck_slip_type" id="ck_slip_type" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>

	<fieldset style="position:absolute;margin-left:495px;border-radius:4px;width: 500px;height: 100px;"><legend><span class="style3"><strong>Item Filter</strong></span></legend>
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
			//alert(src);
			$('#dg').datagrid('load', {
				src: search
			});
			
			$('#dg').datagrid('enableFilter');

			if (src == '') {
				filterData();
			};
			//document.getElementById('src').value = '';
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
			    {field:'item_no',title:'SLIP NO.', halign:'center', width:50, sortable: true},
                {field:'ITEM', title:'ITEM', halign:'center', width:50}, 
                {field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:200, sortable: true}
			]]
		});
	});

	function filterData(){
		var ck_date = "false";
		var ck_slip_no = "false";
		var ck_slip_type = "false";
		var ck_item_no = "false";
		var ck_sts_approval = "false";
		var flag = 0;

		if ($('#ck_date').attr("checked")) {
			ck_date = "true";
			flag += 1;
		};

		if ($('#ck_slip_no').attr("checked")) {
			ck_slip_no = "true";
			flag += 1;
		};

		if ($('#ck_slip_type').attr("checked")) {
			ck_slip_type = "true";
			flag += 1;
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
			flag += 1;
		};

		if ($('#ck_sts_approval').attr("checked")) {
			ck_sts_approval = "true";
			flag += 1;
		};

		if(flag == 5) {
			$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
		}

		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			ck_date: ck_date,
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
		
		$('#dg').datagrid('enableFilter');
	}

	function add_item(){
        location.href = 'item_add.php?sts=new&item_no=&item_desc=';
	}

	function edit_item(){
        var dataRows = [];
		var row = $('#dg').datagrid('getSelected');
        if(row){
            dataRows.push({
                item_no: row.item_no,
                item_code: row.item_code,
                item: row.item,
                item_flag: row.item_flag,
                description: row.description,
                class_code: row.class_code,
                origin_code: row.origin_code,
                curr_code: row.curr_code,
                external_unit_number: row.external_unit_number,
                safety_stock: row.safety_stock,
                uom_q: row.uom_q,
                unit_engineering: row.unit_engineering,
                unit_stock_rate: row.unit_stock_rate,
                unit_engineer_rate: row.unit_engineer_rate,
                weight: row.weight,
                uom_w: row.uom_w,
                uom_l: row.uom_l,
                drawing_no: row.drawing_no,
                drawing_rev: row.drawing_rev,
                applicable_model: row.applicable_model,
                catalog_no: row.catalog_no,
                standard_price: row.standard_price,
                next_term_price: row.next_term_price,
                suppliers_price: row.suppliers_price,
                manufact_leadtime: row.manufact_leadtime,
                purchase_leadtime: row.purchase_leadtime,
                adjustment_leadtime: row.adjustment_leadtime,
                labeling_to_pack_day: row.labeling_to_pack_day,
                assembling_to_lab_day: row.assembling_to_lab_day,
                issue_policy: row.issue_policy,
                issue_lot: row.issue_lot,
                manufact_fail_rate: row.manufact_fail_rate,
                section_code: row.section_code,
                stock_subject_code: row.stock_subject_code,
                cost_process_code: row.cost_process_code,
                cost_subject_code: row.cost_subject_code,
                customer_item_no: row.customer_item_no,
                customer_item_name: row.customer_item_name,
                order_policy: row.order_policy,
                maker_flag: row.maker_flag,
                mak: row.mak,
                item_type1: row.item_type1,
                item_type2: row.item_type2,
                package_unit_number: row.package_unit_number,
                unit_price_o: row.unit_price_o,
                unit_price_rate: row.unit_price_rate,
                unit_curr_code: row.unit_curr_code,
                customer_type: row.customer_type,
                package_type: row.package_type,
                capacity: row.capacity,
                date_code_type: row.date_code_type,
                date_code_month: row.date_code_month,
                label_type: row.label_type,
                measurement: row.measurement,
                inner_box_height: row.inner_box_height,
                inner_box_width: row.inner_box_width,
                inner_box_depth           : row.inner_box_depth           ,
                inner_box_unit_number: row.inner_box_unit_number,
                medium_box_unit_height: row.medium_box_unit_height,
                edium_box_unit_width: row.edium_box_unit_width,
                edium_box_unit_depth: row.edium_box_unit_depth,
                medium_box_unit_number: row.medium_box_unit_number,
                outer_box_unit_height: row.outer_box_unit_height,
                outer_box_unit_width: row.outer_box_unit_width,
                outer_box_unit_depth: row.outer_box_unit_depth,
                ctn_gross_weight: row.ctn_gross_weight,
                pi_no: row.pi_no,
                opertation_time: row.opertation_time,
                man_power: row.man_power,
                aging_day: row.aging_day
          });
            var myJSON=JSON.stringify(dataRows);
            var str_unescape=unescape(myJSON);

            console.log(str_unescape);

            // location.href = 'item_add.php?sts=edit&item_no='+row.ITEM_NO+
            //     '&item_desc='+row.DESCRIPTION+
            //     '&data='+str_unescape;
        }
	}

	function delete_item(){
		var row = $('#dg').datagrid('getSelected');
        if (row.STS == '0'){
            $.messager.confirm('Confirm','Are you sure you want to destroy this Materials Transaction?',function(r){
if (r){
	$.messager.progress({
					    title:'Please waiting',
					    msg:'removing data...'
					});

  // $.post('do_destroy.php',{id:row.SLIP_NO},function(result){
  //     if (result.success){
  //     	$.messager.progress('close');
  //         $('#dg').datagrid('reload');    // reload the user data
  //     }else{
  //         $.messager.show({    // show error message
  //             title: 'Error',
  //             msg: result.errorMsg
  //         });
  //         $.messager.progress('close');
  //     }
  // },'json');
}
            });
        }else{
        	$.messager.show({title: 'Materials Transaction',msg: 'Data is Approved'});
        }
	}
</script>
</body>
</html>