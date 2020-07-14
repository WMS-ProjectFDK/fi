<?php
include("../../connect/conn.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>INPUT NG LABEL</title>
<link rel="icon" type="../../image/png" href="../../plugins/font-awesome/css/font-awesome.min.css">
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
	font-size:16px;
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
	<h4 align="center">INPUT DATA NG LABELING</h4>
	<table id="dg_ng" class="easyui-datagrid" toolbar="#toolbar" data-options="method: 'get', fitColumns: true, height: '550px', width: '100%'"></table>
	<span style="font-size: 9px;color: red;">*) double click to input NG</span>
	<div id="tt">
		<a href="#" class="icon-cancel" onclick="logOut();"></a>
	</div>

	<div id="toolbar" style="padding:5px 5px;">
		<div class="fitem">
			<span style="width:100px;display:inline-block;font-size: 14px;">LABEL LINE</span>
			<select style="width:200px;" name="cmb_Line" id="cmb_Line" class="easyui-combobox" data-options="panelHeight:'160px'">
				<option selected="true" value="">-SILAHKAN PILIH-</option>
				<option value="LR03-1">LR03#1</option>
				<option value="LR03-2">LR03#2</option>
				<option value="LR6-1">LR6#1</option>
				<option value="LR6-2">LR6#2</option>
				<option value="LR6-3">LR6#3</option>
				<option value="LR6-4">LR6#4</option>
				<option value="LR6-5">LR6#5</option>
				<option value="LR6-6">LR6#6</option>
			</select>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:100px;display:inline-block;font-size: 14px;">SCAN DATE</span>
			<input style="width:100px;" name="date_scan" id="date_scan" class="easyui-datebox" 
				data-options="formatter:myformatter,parser:myparser" value="<?date();?>" required=""/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:100px;display:inline-block;font-size: 14px;font-weight: bold;"><input type="radio" name="shift" id="shift1" value="1"/>SHIFT-1</span>
			<span style="width:100px;display:inline-block;font-size: 14px;font-weight: bold;"><input type="radio" name="shift" id="shift2" value="2"/>SHIFT-2</span>
			<span style="width:100px;display:inline-block;font-size: 14px;font-weight: bold;"><input type="radio" name="shift" id="shift3" value="3"/>SHIFT-3</span>
			<a id="savebtn_Plan" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;display:inline-block;vertical-align: middle;"><i class="fa fa-save" aria-hidden="true"></i> FILTER DATA</a>
			<div style="clear:both;margin-bottom:1px;"></div>
		</div>
	</div>

	<!-- RESULT BATTERY IN -->
	<div id="dlg_result" class="easyui-dialog" style="width: auto;height: auto;padding: 5px;left:100px;top:50px;" closed="true" buttons="#dlg-buttons-print-rec" data-options="modal:true">
	  <fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:90%; height:auto; padding: 5px;">
		<div class="fitem" hidden="true"><!-- hidden="true" -->
			<span style="width:120px;display:inline-block;">ID</span>
			<input style="width:150px;" name="id_rowid" id="id_rowid" class="easyui-textbox" disabled=""/>
		</div>
		<div class="fitem" hidden="true"> <!-- hidden="true" -->
			<span style="width:120px;display:inline-block;">ID</span>
			<input style="width:150px;" name="id_result" id="id_result" class="easyui-textbox" disabled=""/>
		</div>
		<div class="fitem" hidden="true">
			<span style="display:inline-block;">ASSY QTY</span>
			<input style="width:150px;" name="qty_result" id="qty_result" class="easyui-textbox" disabled=""/><!-- 
			<span style="width:50px;display:inline-block;"></span>
			<span style="display:inline-block;">TOTAL QTY NG</span>
			<input style="width:150px;" name="qty_ng_actual" id="qty_ng_actual" class="easyui-textbox" value="0" disabled=""/> -->
		</div>
		<!-- <hr> -->
		<div class="fitem">
			<table id="dg_detail_ng" class="easyui-datagrid" data-options="method: 'get', fitColumns: true, height: 'auto', width: 'auto'"></table>
		</div>
		<hr>
		<div align="center">
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-save" onclick="save_result()" style="width:90px">SAVE</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-cancel" onclick="javascript:$('#dlg_result').dialog('close')" style="width:90px">CANCEL</a>
		</div>
	  </fieldset>
	</div>
	<!-- END -->

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

		var url='';
		
		$(function(){
			var d = new Date();
		  	var n = parseInt(d.getHours());

		  	if (n >= 7 && n < 15){
		  		document.getElementById('shift1').checked = true;
		  	}else if (n >= 15 && n < 23){
		  		document.getElementById('shift2').checked = true;
		  	}else{
		  		document.getElementById('shift3').checked = true;
		  	}

		  	$('#dg_ng').datagrid({
			    url: 'input_ng_get.php',
			    singleSelect: true,
			    fitColumns: true,
			    columns:[[
				    {field:'ID_PRINT', title:'ID', width:100, halign: 'center'},
				    {field:'RECORDDATE', title:'RECORD DATE', width:150, halign: 'center'},
				    {field:'SHIFT', title:'SHIFT', width:100, halign: 'center'},
				    {field:'QTY', title:'RESULT QTY', align:'right', halign: 'center', width:70},
				    {field:'ROW_ID', title:'ID', width:100, halign: 'center', hidden: true},
				    {field:'STS'}
			    ]]
			});
		})

		function filterData(){
			var shift = '';

		  	if(document.getElementById('shift1').checked == true){
				shift = document.getElementById('shift1').value;
			}

			if(document.getElementById('shift2').checked == true){
				shift = document.getElementById('shift2').value;
			}

			if(document.getElementById('shift3').checked == true){
				shift = document.getElementById('shift3').value;
			}

			if ($('#date_scan').datebox('getValue') == ''){
				$.messager.alert('INFORMATION','scan date not found','info');
			}else if ($('#cmb_Line').combobox('getValue') == ''){
				$.messager.alert('INFORMATION','label line not found','info');
			}else if(shift == ''){
				$.messager.alert('INFORMATION','shift not selected','info');
			}else{
				$('#dg_ng').datagrid('load', {
					line: $('#cmb_Line').combobox('getValue'),
					date_akhir: $('#date_scan').datebox('getValue'),
					shift: shift
				});
			}

			$('#dg_ng').datagrid({
				onDblClickRow:function(id,row){
			    	$('#id_result').textbox('setValue',row.ID_PRINT);
					$('#qty_result').textbox('setValue',row.QTY);
					$('#id_rowid').textbox('setValue',row.ROW_ID);
			    	$('#dlg_result').dialog('open').dialog('setTitle','INPUT NG BATTERY LABEL (Lot No. :'+row.ID_PRINT+')');

			    	/*condition for create or update*/
			    	if (row.STS == 1){
			    		url = 'json_data_ng.php?id='+row.ID_PRINT+'&line='+$('#cmb_Line').combobox('getValue');
			    	}else{
			    		url = 'json_data_ng.json';
			    	}
			    	/*------------------------------*/

			    	//console.log(url);

			    	$('#dg_detail_ng').datagrid({
					    url: url,
					    singleSelect: true,
					    fitColumns: true,
					    columns:[[
						    {field:'ID', title:'ID', hidden: true},
						    {field:'PROCESS', title:'PROCESS', width:135, halign: 'center'},
						    {field:'PROCESS_O', hidden:true},
						    {field:'NG_CONTENT1', title:'NG BY LABELING<br>(DISPOSE)', width:150, halign: 'center'},
						    {field:'QTY1', title:'QTY', align:'right', halign: 'center', width:35, editor:{type:'numberbox',options:{required:true,groupSeparator:','}}},
						    {field:'NG_CONTENT2', title:'NG BY ASSEMBLING<br>(DISPOSE)', width:150, halign: 'center'},
						    {field:'QTY2', title:'QTY', align:'right', halign: 'center', width:35, editor:{type:'numberbox',options:{required:true,groupSeparator:','}}},
						    {field:'NG_CONTENT3', title:'NG BY LABELING<br>(REUSE)', width:150, halign: 'center'},
						    {field:'QTY3', title:'QTY', align:'right', halign: 'center', width:35, editor:{type:'numberbox',options:{required:true,groupSeparator:','}}}
					    ]],
					    onClickRow:function(row){
					    	$(this).datagrid('beginEdit', row);
						}
					});
				}
			});
		}

		function simpan(){
			var dataRows = [];
			var id_pr = $('#id_result').textbox('getValue');
			var Line = $('#cmb_Line').combobox('getValue');
			var t_ng = $('#dg_detail_ng').datagrid('getRows');
			var total_ng = t_ng.length;
			var qTotal = 0;
			var q1T=0;				var q2T=0;				var q3T=0;

			for(i=0;i<total_ng;i++){
				$('#dg_detail_ng').datagrid('endEdit',i);
				var ng1='-';			var ng2='-';			var ng3='-';
				var q1=0;				var q2=0;				var q3=0;

				var n1 = parseInt($('#dg_detail_ng').datagrid('getData').rows[i].QTY1.replace(/,/g,''));
				var n2 = parseInt($('#dg_detail_ng').datagrid('getData').rows[i].QTY2.replace(/,/g,''));
				var n3 = parseInt($('#dg_detail_ng').datagrid('getData').rows[i].QTY3.replace(/,/g,''));
				
				if (n1>0){
					ng1 = $('#dg_detail_ng').datagrid('getData').rows[i].NG_CONTENT1;
					q1=n1;
					q1T+=q1;
				}

				if (n2>0){
					ng2 = $('#dg_detail_ng').datagrid('getData').rows[i].NG_CONTENT2;
					q2=n2;
					q2T+=q2;
				}

				if (n3>0){
					ng3 = $('#dg_detail_ng').datagrid('getData').rows[i].NG_CONTENT3;
					q3=n3;
					q3T+=q3;
				}

				//console.log('ke-'+i+' content1='+ng1+' content2='+ng2+' content3='+ng3);

				if (i == 0){
					
				}

				var s='';

				if(ng1=='-'){
					if(ng2=='-'){
						if (ng3=='-'){
							s='lose';
						}else{
							s='n3';
						}
					}else{
						s='n2';
					}
				}else{
					s='n1';
				}

				if(s!='lose'){
					dataRows.push({
						batt_sts: 'DETAILS',
						batt_idNG: $('#dg_detail_ng').datagrid('getData').rows[i].ID,
						batt_id: id_pr,
						batt_ln: Line,
						batt_proc: $('#dg_detail_ng').datagrid('getData').rows[i].PROCESS_O,
						batt_ng1: ng1,
						batt_qty1: q1,
						batt_ng2: ng2,
						batt_qty2: q2,
						batt_ng3: ng3,
						batt_qty3: q3
					});
				}
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			//console.log(unescape(str_unescape));

			$.post('input_ng_save.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_result').dialog('close');
					$('#dg_ng').datagrid('reload');
					$.messager.show({
						title:'KANBAN LABEL',
						msg:'PROSES UPDATE BERHASIL,<br>PALLET INI SUDAH BISA DIPAKAI UNTUK KANBAN WO LABEL',
						timeout:4000,
						showType:'show',
						style:{
							middle:document.body.scrollTop+document.documentElement.scrollTop,
						}
					});
				}else{
					$.messager.show({
						title:'ERROR',
						msg:'PROSES UPDATE GAGAL,<br>SILAHKAN COBA LAGI',
						timeout:4000,
						showType:'show',
						style:{
							middle:document.body.scrollTop+document.documentElement.scrollTop,
						}
					});
				}
			});
		}

		function save_result(){
			var id_pr = $('#id_result').textbox('getValue');
			var Line = $('#cmb_Line').combobox('getValue');

			$.post('input_ng_delete.php',{
				id_print: id_pr, 
				labelline: Line
			}).done(function(res){
				if(res == '"success"'){
					simpan();
				}else{
					$.messager.alert('ERROR',res,'warning');	
				}
			});
		}
	</script>
	</body>
</html>