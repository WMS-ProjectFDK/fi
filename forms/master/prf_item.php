<?php 
session_start();
require_once('../___loginvalidation.php');
include("../../connect/conn.php");
$user_name = $_SESSION['id_wms'];

// $qry = "select id_dept from ztb_person_dept where person_code='$user_name'";
// $data = oci_parse($connect, $qry);
// oci_execute($data);
// $dt = oci_fetch_array($data);

// $id_D = $dt[0]; 
// $cod = 'M'.$id_D.'-';
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Master Item</title>
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

	<table id="dg" title="Master Item" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:400px;"></table>

	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="new_item()">New Item</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit_item()">Edit Item</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroy_item()">Remove Item</a>
        <input id="txt_search" class="easyui-textbox" data-options="iconCls:'icon-search',iconWidth:28,prompt:'Search Item No. or Item Name...'" style="width:20%;height:22px;">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-filter" onclick="search_item()">Search</a>
	</div>

	<!-- ADD -->
	<div id="dlg" class="easyui-dialog" style="width:1050px;" closed="true" data-options="position:'bottom'" buttons="#dlg-buttons" data-options="modal:true">
        <form id="fm" method="POST" enctype="multipart/form-data" novalidate>
        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend>Entry Item</legend>
        	<div class="fitem">
				<span style="width:100px;display:inline-block;">Item Group</span>
				<select style="width:180px;" name="ITEM_GRP" id="ITEM_GRP" class="easyui-combobox" data-options=" url:'../json/json_item_grp.php', method:'get', valueField:'idgrp', textField:'nmgrp', panelHeight:'50px',
				onSelect: function(rec){
					var c = rec.idgrp;
					var code;
					if(c == 'GP'){
						code = rec.idgrp+'-';
					}else{
						code = '<?=$cod;?>';
					}
					$('#ITEM_NO').textbox('setValue',code);
				}, required:true
				"></select>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Class Aset</span>
				<select style="width:180px;" name="ITEM_ASSET" id="ITEM_ASSET" class="easyui-combobox" data-options=" url:'../json/json_item_asset.php',method:'get', valueField:'id_item_asset',  textField:'nm_item_asset', panelHeight:'50px'"></select>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Item No.</span>
				<input style="width:180px;" name="ITEM_NO" id="ITEM_NO" class="easyui-textbox" disabled="" />
			</div>
			<div class="fitem">
	            <span style="width:100px;display:inline-block;">Item Name</span>
				<input style="width:522px;" name="ITEM_NAME" id="ITEM_NAME" class="easyui-textbox"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">UoM</span>
				<select style="width:180px;" name="ITEM_UOM" id="ITEM_UOM" class="easyui-combobox" data-options=" url:'../json/json_uom.php', method:'get', valueField:'idunit', textField:'nmunit', panelHeight:'50px'"></select>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Specification</span>
				<input style="width:522px;" name="ITEM_SPEC" id="ITEM_SPEC" class="easyui-textbox"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Currency</span>
	            <select style="width:180px;" name="ITEM_CURR" id="ITEM_CURR" class="easyui-combobox" data-options=" url:'../json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'50px'"></select>
			</div>
			<div class="fitem">
	            <span style="width:100px;display:inline-block;">Item Type</span>
				<select style="width:180px;" name="ITEM_TYPE" id="ITEM_TYPE" class="easyui-combobox" data-options=" url:'../json/json_typ.php', method:'get', valueField:'idtype', textField:'nmtype', panelHeight:'50px'" disabled=""></select>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Safety Stock</span>
				<input style="width:180px;" name="ITEM_SAFETY" id="ITEM_SAFETY" class="easyui-textbox" value="0"/>
				<span style="width:50px;display:inline-block;"></span>
				<!-- <input class="easyui-filebox" id="ITEM_UPLOAD" name="ITEM_UPLOAD" accept="image/*" style="width: 284px;" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])"> -->
				<input type="file" id="ITEM_UPLOAD" name="ITEM_UPLOAD" accept="image/*" style="width: 284px;" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])">
			</div>
        </fieldset>
        </form>
        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend>VIEW IMAGE</legend>
        	<div class="fitem">
	        	<div style="width: 300px;height: 300px;margin-left: 350px;">
	        		<img style="width:300px;height:300px;display:inline-block;" id="output" src="#"/>
	        		<!-- <img style="width:300px;height:300px;display:inline-block;" id="output2" src="#"/> -->
	        	</div>
        	</div>
        </fieldset>
    </div>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_item()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <!-- END ADD -->

    <!-- EDIT -->
	<div id="dlg_e" class="easyui-dialog" style="width:1050px;" closed="true" data-options="position:'bottom'" buttons="#dlg-buttons_e" data-options="modal:true">
        <form id="fm_e" method="POST" enctype="multipart/form-data" novalidate>
        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend>Entry Item</legend>
        	<div class="fitem">
				<span style="width:100px;display:inline-block;">Item Group</span>
				<select style="width:180px;" name="ITEM_GRP_e" id="ITEM_GRP_e" class="easyui-combobox" data-options=" url:'../json/json_item_grp.php', method:'get', valueField:'idgrp', textField:'nmgrp', panelHeight:'50px',
				onSelect: function(rec){
					var c = rec.idgrp;
					var code;
					if(c == 'GP'){
						code = rec.idgrp+'-';
					}else{
						code = 'MA-';
					}
					$('#ITEM_NO').textbox('setValue',code);
				}, required:true
				"></select>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Class Aset</span>
				<select style="width:180px;" name="ITEM_ASSET_e" id="ITEM_ASSET_e" class="easyui-combobox" data-options=" url:'../json/json_item_asset.php', method:'get', valueField:'id_item_asset', textField:'nm_item_asset', panelHeight:'50px'"></select>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Item No.</span>
				<input style="width:180px;" name="ITEM_NO_e" id="ITEM_NO_e" class="easyui-textbox" disabled="" />
			</div>
			<div class="fitem">
	            <span style="width:100px;display:inline-block;">Item Name</span>
				<input style="width:522px;" name="ITEM_NAME_e" id="ITEM_NAME_e" class="easyui-textbox"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">UoM</span>
				<select style="width:180px;" name="ITEM_UOM_e" id="ITEM_UOM_e" class="easyui-combobox" data-options=" url:'../json/json_uom.php', method:'get', valueField:'idunit', textField:'nmunit', panelHeight:'50px'"></select>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Specification</span>
				<input style="width:522px;" name="ITEM_SPEC_e" id="ITEM_SPEC_e" class="easyui-textbox"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Currency</span>
	            <select style="width:180px;" name="ITEM_CURR_e" id="ITEM_CURR_e" class="easyui-combobox" data-options=" url:'../json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'50px'"></select>
			</div>
			<div class="fitem">
	            <span style="width:100px;display:inline-block;">Item Type</span>
				<select style="width:180px;" name="ITEM_TYPE_e" id="ITEM_TYPE_e" class="easyui-combobox" data-options=" url:'../json/json_typ.php', method:'get', valueField:'idtype', textField:'nmtype', panelHeight:'50px'" disabled=""></select>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Safety Stock</span>
				<input style="width:180px;" name="ITEM_SAFETY_e" id="ITEM_SAFETY_e" class="easyui-textbox" value="0"/>
				<span style="width:50px;display:inline-block;"></span>
				<!-- <input class="easyui-filebox" id="ITEM_UPLOAD" name="ITEM_UPLOAD" accept="image/*" style="width: 284px;" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])"> -->
				<input type="file" id="ITEM_UPLOAD_e" name="ITEM_UPLOAD_e" accept="image/*" style="width: 284px;" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])">
			</div>
        </fieldset>
        </form>
        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend>VIEW IMAGE</legend>
        	<div class="fitem">
	        	<div style="width: 300px;height: 300px;margin-left: 350px;">
	        		<img style="width:300px;height:300px;display:inline-block;" id="output_e" src="#"/>
	        		<!-- <img style="width:300px;height:300px;display:inline-block;" id="output2" src="#"/> -->
	        	</div>
        	</div>
        </fieldset>
    </div>
    <div id="dlg-buttons_e">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_item_e()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_e').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <!-- END EDIT -->

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

		function search_item(){
			var s = $('#txt_search').textbox('getValue');
			//alert (s);
			$('#dg').datagrid('load',{find: s});
			$('#txt_search').textbox('setValue','');
		}

		$('#dg').datagrid({
			url: 'prf_item_get.php',
			rownumbers:'true',
			singleSelect:'true',
			fitColumns: 'true',
			columns:[[
				{field:'ITEM_NO',title:'ITEM', halign:'center', width:120},
                {field:'ITEM_NAME', title:'DESCRIPTION', halign:'center', width:250},
                {field:'ITEM_SPEC', title:'SPECIPICATION', halign:'center', width:300},
                {field:'MACHINE_CODE', title:'GROUP', halign:'center', align:'center', width:70},
                {field:'ITEM_TYPE1', title:'ITEM<br>TYPE', halign:'center', align:"center", width:80},
                {field:'UNIT',title:'UoM', halign:'center', align:'center', width:60},
                {field:'ITEM_UOM',title:'UoM', halign:'center', align:'center', width:60,hidden:'true'},
                {field:'ITEM_CURR',title:'crc', halign:'center', align:'center', width:60,hidden:'true'},
                {field:'CURR_SHORT',title:'CURRENCY', halign:'center', align:'center', width:70},
                {field:'ITEM_UPLOAD',title:'image', halign:'center', align:'center', width:60,hidden:'true'},
                {field:'ITEM_ASSET',title:'class', halign:'center', align:'center', width:60,hidden:'true'},
                {field:'ITEM_GRP',title:'grup', halign:'center', align:'center', width:60,hidden:'true'}
			]]
		});

		$(function(){
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		})

		var url;
		function new_item(){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','New Master Item');
            $('#fm').form('clear');
            $('#ITEM_TYPE').combobox('setValue','ALKALINE');
            $('#ITEM_SAFETY').textbox('setValue','0');
            /*document.getElementById('output2').style.visibility = "hidden";*/
            document.getElementById('ITEM_UPLOAD').value = '';
            document.getElementById('output').src = '';
            url = 'prf_item_save.php';
        }

        function save_item(){
        	//alert(url);
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					//alert(result);
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.alert('Error',result.errorMsg,'warning');
						//$.messager.show({title: 'Error',msg: result.errorMsg});
					}else{
						var iditem = $('#ITEM_NO').textbox('getValue');
						$('#dlg').dialog('close');
						$('#dg').datagrid('reload');
						$.messager.alert('Information',result.Success,'info');
					}
				}
			});
		}

		function edit_item(){
        	var row = $('#dg').datagrid('getSelected');
            if (row){
            	var grp = row.ITEM_NO;
            	var grp_S = grp.substring(0,2);
            	if(grp_S=='GP' || grp_S=='MQ' || grp_S=='MF' || grp_S=='MS' || grp_S=='MP' || grp_S=='MG' || grp_S=='MC' || grp_S=='MA'){
            		$.messager.alert('Warning',"Data can't be edited",'warning');
            	}else{
            		$('#dlg_e').dialog('open').dialog('setTitle','Edit Quotation ('+row.QUOTATION_NO+')');
            		$('#QUOT_NO_e').textbox('setValue',row.QUOTATION_NO);
            		$('#QUOT_DATE_e').datebox('setValue',row.QUOTATION_DATE);
            		$('#QUOT_NO2_e').textbox('setValue',row.QUOTATION_NO);
					$('#QUOT_NO22_e').textbox('setValue',row.QUOTATION_NO);
					$('#btn_add_header_e').linkbutton('disable');
					$('#QUOT_DATE_e').datebox('disable');

					$('#dg_add_e').datagrid('load',{
		            	qno: $('#QUOT_NO_e').textbox('getValue')
		            });
            	}
            }
        	/*var row = $('#dg').datagrid('getSelected');
            if (row){
            	
            	$('#dlg_e').dialog('open').dialog('center').dialog('setTitle','Edit Parameter Budget ('+row.ITEM_NO+')');
            	$('#ITEM_GRP_e').combobox('disabled','true');
            	$('#ITEM_NO_e').textbox('setValue',row.ITEM_NO);
            	$('#ITEM_NAME_e').textbox('setValue',row.ITEM_N);
            	$('#ITEM_UOM_e').combobox('setValue',row.ITEM_UOM);

            	$('#ITEM_TYPE_e').combobox('setValue','ALKALINE');
            	$('#ITEM_ASSET_e').combobox('disabled','true');
            	$('#ITEM_GRP_e').combobox('setValue','GP');

            	//document.getElementById('output').style.visibility = "hidden";
            	document.getElementById('output2').src = row.ITEM_UPLOAD;
                url = 'prf_item_update.php?id='+row.ITEM_NO;
            }else{
            	$.messager.alert("warning","Row Not Selected!","Warning");
            }*/
        }

        function destroy_item(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirmation','Are you sure you want to destroy Item '+row.ITEM_NO+'?',function(r){
                    if (r){
                        $.post('prf_item_destroy.php',{id:row.ITEM_NO},function(result){
                            //alert(result);
                            if (result.success){
                                $('#dg').datagrid('reload');    // reload the user data
                            }else{
                                $.messager.show({    // show error message
                                    title: 'Error',
                                    msg: result.errorMsg
                                });
                            }
                        },'json');
                    }
                });
            }
        }
	</script>
    </body>
    </html>