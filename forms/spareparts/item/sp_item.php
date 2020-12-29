<?php
include("../../../connect/conn.php");
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
<link rel="icon" type="image/png" href="../../../favicon.png">
<script language="javascript">
		function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
		}
</script> 
<link rel="stylesheet" type="text/css" href="../../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../../../themes/color.css" />
<script type="text/javascript" src="../../../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../../../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../../../js/jquery.edatagrid.js"></script>
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
    include ('../../../ico_logout.php');
    $exp = explode('-', access_log($menu_id,$user_name));
?>

<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:500px;border-radius:4px;height: 70px;"><legend><span class="style3"><strong>ITEM FILTER</strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item No.</span>
			<select style="width:330px;" name="cmb_item" id="cmb_item" class="easyui-combobox" data-options=" mode:'remote',url:'json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
					loader: function(param,success,error){
							var opts = $(this).combobox('options');
							if (!opts.url){return false}
							if (param.q != undefined){
								var q = param.q || '';
								if (q.length < 3){return false}
							}
							$.ajax({
								type: 'GET',
								url: 'json/json_item_all.php?id='+param.q,
								dataType: 'json',
								success: function(data){
									success(data);
								},
								error: function(){
									error.apply(this, arguments);
								}
							});
							
						},
					
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
	<fieldset style="position:absolute;margin-left:530px;border-radius:4px;width: 700px;height: 70px;"><legend><span class="style3"><strong>OTHER FILTER & PRINT</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">Stock Subject Code</span>
			<select style="width:200px;" name="cmb_subject_code" id="cmb_subject_code" class="easyui-combobox" >
				<option VALUE=''></option>
				<option VALUE='0'>Wooden Pallet</option>
				<option VALUE='1'>Raw Materials</option>
				<option VALUE='2'>Packing Materials</option>
				<option VALUE='3'>Work in Process</option>
				<option VALUE='4'>Semi Finished Goods</option>
				<option VALUE='5'>Finished Goods</option>
				<option VALUE='6'>Customer Supply Products</option>
				<option VALUE='7'>Materials 2</option>
				<option VALUE='8'>LR6 Management</option>
				<option VALUE='9'>LR03 Management</option>
			</select>
			<label><input type="checkbox" name="ck_subject_code" id="ck_subject_code" checked="true">All</input></label>
		</div>
	</fieldset>
	<div style="clear:both;margin-bottom:0px;"></div>
	<div style="padding:5px 6px;">
       <span style="width:50px;display:inline-block;">search</span>
	   <input style="width:180px; height: 18px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" />
	   <a href="javascript:void(0)" style="width: 180px;" class="easyui-linkbutton c2" onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
	   <a href="javascript:void(0)" style="width: 180px;" id="add" class="easyui-linkbutton c2" onclick="add_item()"><i class="fa fa-plus" aria-hidden="true"></i> ADD ITEM</a>
	   <a href="javascript:void(0)" style="width: 180px;" id="edit" class="easyui-linkbutton c2" onclick="edit_item()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT ITEM</a>
	   <a href="javascript:void(0)" style="width: 190px;" id="delete" class="easyui-linkbutton c2" onclick="delete_item()"><i class="fa fa-trash" aria-hidden="true"></i> REMOVE ITEM</a>
	   <a href="javascript:void(0)" style="width: 190px;" id="delete" class="easyui-linkbutton c2" onclick="download_report()"><i class="fa fa-download" aria-hidden="true"></i> DOWNLOAD ITEM</a>
	</div>
</div>

<table id="dg" title="MASTER ITEM SPAREPARTS" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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

			get_url='?src='+search;

			console.log(get_url);
			
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
		$('#cmb_item').combobox('disable');
		$('#txt_item_name').textbox('disable');
		$('#ck_item_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_item').combobox('disable');
				$('#txt_item_name').textbox('disable');
			}else{
				$('#cmb_item').combobox('enable');
				$('#txt_item_name').textbox('enable');
			}
		});

		$('#cmb_subject_code').combobox('disable');
		$('#ck_subject_code').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_subject_code').combobox('disable');
			}else{
				$('#cmb_subject_code').combobox('enable');
			}
		});

		$('#dg').datagrid( {
			url: 'sp_item_get.php',
		    columns:[[
			    {field:'ITEM_NO',title:'ITEM NO.', halign:'center', width:50, sortable: true},
                // {field:'ITEM', title:'ITEM', halign:'center', width:100}, 
				{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:200, sortable: true},
				{field:'STOCK_SUBJECT', title:'SUBJECT', halign:'center', width:100},
				//{field:'MAK', title:'MAKER', halign:'center', width:100},
				{field:'CLASS', title:'CLASS', halign:'center', width:100}
			]]
		});
	});

	var get_url='';

	function filterData(){
		var ck_item_no = "false";
		var ck_subject_code = "false";
		var flag = 0;

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
			flag += 1;
		};

		if ($('#ck_subject_code').attr("checked")) {
			ck_subject_code = "true";
			flag += 1;
		};

		if(flag == 2) {
			$.messager.alert('INFORMATION','No filter data, system only show 200 records','info');
		}

		$('#dg').datagrid('load', {
			cmb_item_no: $('#cmb_item').combobox('getValue'),
			ck_item_no: ck_item_no,
			txt_item_name: $('#txt_item_name').textbox('getValue'),
			cmb_subject_code: $('#cmb_subject_code').combobox('getValue'),
			ck_subject_code: ck_subject_code,
			src: ''
		});

		get_url='?cmb_ite_no='+$('#cmb_item').combobox('getValue')+
			'&ck_item_no='+ck_item_no+
			'&txt_item_name='+$('#txt_item_name').textbox('getValue')+
			'&cmb_subject_code='+$('#cmb_subject_code').combobox('getValue')+
			'&ck_subject_code='+ck_subject_code;
		
		console.log(get_url);
		
		$('#dg').datagrid('enableFilter');
	}

	function add_item(){
        location.href = 'sp_item_add.php?sts=new&item_no=&item_desc=&data=';
	}

	function edit_item(){
        var dataRows = [];
		var row = $('#dg').datagrid('getSelected');
        if(row){
            dataRows.push({
                ITEM_NO: row.ITEM_NO,
                DESCRIPTION: row.DESCRIPTION,
                DESCRIPTION_ORG: row.DESCRIPTION_ORG,
                SPECIFICATION: row.SPECIFICATION,
                MACHINE_CODE: row.MACHINE_CODE,
                ITEM_TYPE1: row.ITEM_TYPE1,
                UNIT_STOCK: row.UNIT_STOCK,
                UOM_Q: row.UOM_Q,
                CURR_CODE: row.CURR_CODE,
                REPORTGROUP_CODE: row.REPORTGROUP_CODE,
                CLASS_CODE: row.CLASS_CODE,
                STOCK_SUBJECT_CODE: row.STOCK_SUBJECT_CODE,
                SAFETY_STOCK: row.SAFETY_STOCK,
                PURCHASE_LEADTIME: row.PURCHASE_LEADTIME
          });
            var myJSON=JSON.stringify(dataRows);
            var str_unescape=unescape(myJSON);

            console.log('sp_item_add.php?sts=edit&item_no='+row.ITEM_NO+'&item_desc='+row.DESCRIPTION+'&data='+str_unescape);

            location.href = 'sp_item_add.php?sts=edit&item_no='+row.ITEM_NO+'&item_desc='+row.DESCRIPTION+'&data='+unescape(str_unescape);
        }
	}

	function delete_item(){
		var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Are you sure you want to delete this Item?',function(r){
				if (r){
					$.messager.progress({title:'Please waiting', msg:'removing data...'});
					$.post('sp_item_destroy.php',{id:row.ITEM_NO},function(result){
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

	function download_report(){
		console.log('sp_item_report.php');
        window.open('sp_item_report.php');
	}
</script>
</body>
</html>