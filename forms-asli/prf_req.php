<?php 
include("../connect/conn2.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Requisition Transaction</title>
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
	<?php include ('../ico_logout.php'); ?>

	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="new_req()">New</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit_req()">Edit</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="destroy_req()">Remove</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="print_req()">Print PDF</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="print_req_excel()">Print excel</a>
        <fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%;"><legend>Budget Information</legend>
        <div class="fitem">
			<span id="info_budget" align="right" style="width:200px;display:inline-block;color: blue;font-weight: bold;"></span>
			<span id="info_req" align="right" style="width:350px;display:inline-block;color: blue;font-weight: bold;"></span>
			<span id="info_act" align="right" style="width:300px;display:inline-block;color: blue;font-weight: bold;"></span>
		</div>
		</fieldset>
	</div>

	<table id="dg" title="Riquistion Transaction" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;"></table>

	<!-- START ADD -->
	<div id="dlg_add" class="easyui-dialog" style="width:1000px;height:575px;padding:5px 10px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%; float:left;"><legend>BUDGET INFORMATION</legend>
			<div class="fitem">
				<span id="info_budget_add" align="right" style="width:200px;display:inline-block;color: blue;font-weight: bold;"></span>
				<span id="info_req_add" align="right" style="width:350px;display:inline-block;color: blue;font-weight: bold;"></span>
				<span id="info_act_add" align="right" style="width:300px;display:inline-block;color: blue;font-weight: bold;"></span>	
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%;"><legend><span class="style3"> REQUISITION </span></legend>
			<div class="fitem">
				<span style="width:70px;display:inline-block;">Req. No.</span> :
				<input required="true" style="width:220px;" id="prfno_add" class="easyui-textbox" disabled="disabled" data-options="" />
				<span style="width:30px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">Budget</span> :
				<input type="radio" id="budget_add" name="bud_add" value="BUDGET" checked="checked">BUDGET
				<input type="radio" id="nonbudget_add" name="bud_add" value="NON-BUDGET">NON-BUDGET
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">Status</span> :
				<input type="radio" id="COMPLETE_add" name="COMPLETE_add" value="COMPLETE">COMPLETE
				<input type="radio" id="INCOMPLETE_add" name="COMPLETE_add" value="INCOMPLETE" checked="checked">INCOMPLETE
			</div>
			<div class="fitem">
				<span style="width:70px;display:inline-block;">Req. Date</span> :
				<input style="width:100px;" name="req_date_add" id="req_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required=""/>
				<span style="width:150px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">Remarks</span> :
				<input class="easyui-textbox" id="remarks_add" multiline="true" style="width:520px;height: 50px;"/>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:15px;"></div>
		<div style="padding:2px 0px;" ><table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:250px;"></table></div>
		<div id="toolbar_add">
			<!-- <input type="text" id="txtfind_a" name="txtfind_a" style="width:250px;" onkeypress="new_item(event)"> -->
			<input class="easyui-textbox" id="txtfind_a" name="txtfind_a"  style="width:250px" required="" data-options="prompt:'search item or item no.'" onkeypress="new_item(event)">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="new_item_add()">search</a>
	        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="delete_item_add()">Delete Item</a>
		</div>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%; float:left;"><legend>TOTAL</legend>
			<div class="fitem">
				<span align="right" style="width:720px;display:inline-block;"><b>Grand Total : </b>&nbsp;&nbsp;&nbsp;</span>
				<input style="width:220px;margin-right: 0px;" name="gtotal_add" id="gtotal_add" class="easyui-textbox" disabled="disabled"/>
			</div>
		</fieldset>
	</div>
	<div id="dlg-buttons-add">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveAdd()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<!-- END ADD -->

	<!-- ITEM ADD -->
	<div id="dlg_find_add" title="SELECT ITEM" class="easyui-dialog" style="width:500px;height:350px;padding:5px 10px" closed="true" buttons="#dlg-buttons-item-add" data-options="modal:true">
		<table id="dg_find_add" class="easyui-datagrid" style="width:100%;height:100%;"></table>
		<div id="toolbar_find_add">
			<!-- <div class="fitem">
				<input class="easyui-textbox" id="txtfind" name="txtfind" style="width:350px" required="">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-search" onclick="search_item()" style="width:100px">search</a>
			</div> -->
		</div>
	</div>
	<!-- END ITEM ADD -->

	<!-- START EDIT -->
	<div id="dlg_edit" class="easyui-dialog" style="width:1000px;height:575px;padding:5px 10px" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%; float:left;"><legend>BUDGET INFORMATION</legend>
			<div class="fitem">
				<span id="info_budget_edit" align="right" style="width:200px;display:inline-block;color: blue;font-weight: bold;"></span>
				<span id="info_req_edit" align="right" style="width:350px;display:inline-block;color: blue;font-weight: bold;"></span>
				<span id="info_act_edit" align="right" style="width:300px;display:inline-block;color: blue;font-weight: bold;"></span>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%;"><legend><span class="style3"> REQUISITION </span></legend>
			<!-- <div class="fitem">
				<span style="width:130px;display:inline-block;">REQUISITION Date</span>
				<input style="width:100px;" id="req_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required=""/> 
				<input required="true" style="width:220px;" id="prfno_edit" class="easyui-textbox" disabled="disabled" data-options="" />
			</div> -->
			<div class="fitem">
				<span style="width:70px;display:inline-block;">Req. No.</span> :
				<input required="true" style="width:220px;" id="prfno_edit" class="easyui-textbox" disabled="disabled" data-options="" />
				<span style="width:30px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">Budget</span> :
				<input type="radio" id="budget_edit" name="bud_edit" value="BUDGET">BUDGET
				<input type="radio" id="nonbudget_edit" name="bud_edit" value="NON-BUDGET">NON-BUDGET
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">Status</span> :
				<input type="radio" id="COMPLETE_edit" name="COMPLETE_edit" value="COMPLETE">COMPLETE
				<input type="radio" id="INCOMPLETE_edit" name="COMPLETE_edit" value="INCOMPLETE" checked="checked">INCOMPLETE
			</div>
			<div class="fitem">
				<span style="width:70px;display:inline-block;">Req. Date</span> :
				<input style="width:100px;" name="req_date_edit" id="req_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required=""/>
				<span style="width:150px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">Remarks</span> :
				<input class="easyui-textbox" id="remarks_edit" multiline="true" style="width:520px;height: 50px;"/>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:15px;"></div>
		<div style="padding:2px 0px;" ><table id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:100%;height:250px;"></table></div>
		<div id="toolbar_edit">
			<input class="easyui-textbox" id="txtfind_e" name="txtfind_e"  style="width:250px" required="" data-options="prompt:'search item or item no.'">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="new_item_edit()">search</a>
	        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="delete_item_edit()">Delete Item</a>
		</div>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%; float:left;"><legend>TOTAL</legend>
			<div class="fitem">
				<span align="right" style="width:700px;display:inline-block;"><b>Grand Total : </b>&nbsp;&nbsp;&nbsp;</span>
				<input style="width:220px;margin-right: 0px;" name="gtotal_edit" id="gtotal_edit" class="easyui-textbox" disabled="disabled"/>
			</div>
		</fieldset>
	</div>
	<div id="dlg-buttons-edit">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="updateEdit()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<!-- END EDIT -->

	<!-- ITEM EDIT -->
	<div id="dlg_find_edit" title="SELECT ITEM" class="easyui-dialog" style="width:500px;height:350px;padding:5px 10px" closed="true" buttons="#dlg-buttons-item-add" data-options="modal:true">
		<table id="dg_find_edit" class="easyui-datagrid" style="width:100%;height:100%;"></table>
		<div id="toolbar_find_edit"></div>
	</div>
	<!-- END ITEM EDIT -->


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

		function myTimer() {
		   	var url='';
			$.ajax({
				type: 'GET',
				url: 'prf_info.php?id=<?=$user_name?>',
				dataType: 'json',
				success: function(data){
				    //alert(data[0].budget);
				    document.getElementById('info_budget').innerHTML = "BUDGET : "+data[0].budget+" USD";
				    document.getElementById('info_budget_add').innerHTML = "BUDGET : "+data[0].budget+" USD";
				    document.getElementById('info_budget_edit').innerHTML = "BUDGET : "+data[0].budget+" USD";

				    document.getElementById('info_req').innerHTML = "REQUISITION : "+data[0].req+" USD";
				    document.getElementById('info_req_add').innerHTML = "REQUISITION : "+data[0].req+" USD";
				    document.getElementById('info_req_edit').innerHTML = "REQUISITION : "+data[0].req+" USD";

				    document.getElementById('info_act').innerHTML = "ACTUAL : "+data[0].act+" USD";
				    document.getElementById('info_act_add').innerHTML = "ACTUAL : "+data[0].act+" USD";
				    document.getElementById('info_act_edit').innerHTML = "ACTUAL : "+data[0].act+" USD";
				}
			});
		}

		$(function(){
			var myVar = setInterval(function(){ myTimer() }, 1000);

			$('#dg').datagrid({
				url: 'prf_req_get.php',
				rownumbers:'true', 
				fitColumns:'true',
				singleSelect:'true',
				columns:[[
					{field:'REQ_NO',title:'REQUISITION NO.', halign:'center', width:50},
	                {field:'REQ_DATE', title:'REQ. DATE', halign:'center', width:70},
	                {field:'REQ_DT', title:'REQ. DATE', halign:'center', width:70, hidden: true},
	                {field:'TOTAL',title:'GRAND TOTAL', halign:'center', align:'right', width:70},
	                {field:'TYPE_BUDGET',title:'BUDGET TYPE', halign:'center', align:'center', width:70},
	                {field:'REMARKS',title:'REMARKS', halign:'center', width:170},
	                {field:'USER_ENTRY', title:'USER ENTRY', halign:'center', align:'center', width:70},
	                {field:'LAST_UPDATE', title:'UPDATE', halign:'center', align:'center', width:70},
	                {field:'TYPE_COMPLETE',title:'COMPLETE TYPE', halign:'center', align:'center', width:70}
				]],
				view: detailview,
				detailFormatter:function(index,row){
					return '<div style="padding:2px" class="ddv"></div>';
				},
			 	onExpandRow: function(index,row){
			 		var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
	                var uri_doc = encodeURIComponent(row.REQ_NO)
	                ddv.datagrid({
	                	title: 'REQUISITION Detail (No: '+row.REQ_NO+')',
						url:'prf_req_get_detail.php?req='+row.REQ_NO,
						toolbar: '#ddv'+index,
						singleSelect:true,
						rownumbers:true,
						loadMsg:'load data ...',
						height:'auto',
						rownumbers: true,
						fitColumns: true,
						rowStyler: function(index, row){
							
						},
						columns:[[
							{halign:'center', field:'ID', title:'ID', width:70},
							{halign:'center', field:"ITEM_NO", title:'ITEM', width:150},
							{halign:'center', field:"DESCRIPTION", title:'ITEM NAME', width:250},
							{halign:'center', field:"UNIT", align: 'center', title:'UoM', width:50},
							{halign:'center', field:"PRICE", align:'right', title:'Price (IDR)', width:100},
							{halign:'center', field:"QTY", align:'right', title:'QTY', width:50},
							{halign:'center', field:"TOTAL", align:'right', title:'TOTAL', width:100}
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
					$('#dg').datagrid('fixDetailRowHeight',index);
			 	}
			});

			$('#dg_add').datagrid({
			    singleSelect: true,
				rownumbers: true,
				fitColumns: true,
			    columns:[[
					{field:'ITEM_NO', title:'ITEM NO', width:80, halign: 'center', align: 'center'},
				    {field:'DESCRIPTION', title:'ITEM NAME', width:200, halign: 'center'},
				    {field:'UNIT_CODE', title:'UNIT', halign: 'center', width:80, align: 'right', hidden: true},
				    {field:'UoM', title:'UoM', halign: 'center', width:50, align:'center'},
				    {field:'PRICE', title:'PRICE', halign: 'center',width:50, align:'right'},//, editor:{type:'textbox'}
				    {field:'QTY', title:'QTY', align:'right', halign: 'center', width:100, editor:{type:'numberbox',options:{precision:0,groupSeparator:','}}}
			    ]],
			    onClickRow:function(rowIndex){
			        if (lastIndex != rowIndex){
			            $(this).datagrid('endEdit', lastIndex);
			            //alert(lastIndex);
			            //$(this).datagrid('beginEdit', rowIndex);
			        }
			        lastIndex = rowIndex;
			    },
			    onDblClickRow:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    }
			});

			$('#dg_find_add').datagrid({
				fitColumns: true,
				rownumbers: true,
				singleSelect: true,
				columns:[[
					{field:'ITEM_NO',title:'ITEM NO.', halign:'center', width:100},
	                {field:'DESCRIPTION', title:'ITEM NAME', halign:'center', width:200},
	                {field:'UOM_Q',title:'IDR', halign:'center', align:'right', width:70, hidden:true},
	                {field:'UNIT', title:'UoM', halign:'center', align:'right', width:30, hidden:true},
	                {field:'U_PRICE', title:'PRICE', halign:'center', align:'right', width:70, hidden:true}
				]],
				onDblClickRow:function(id,row){
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=total;
					}else{
						idxfield=total+1;
						for (i=0; i < total; i++) {
							//alert(i);
							var item = $('#dg_add').datagrid('getData').rows[i].ITEM_NO;
							//alert(item);
							if (item == row.ITEM_NO) {
								count++;
							};
						};
					}

					//alert('count = '+count);
					if (count>0) {
						$.messager.alert('Information','Item present','warning');
					}else{
						$('#dg_add').datagrid('insertRow',{
							index: idxfield,	// index start with 0
							row: {
								ITEM_NO: row.ITEM_NO,
								DESCRIPTION: row.DESCRIPTION,
								UNIT_CODE: row.UOM_Q,
								UoM: row.UNIT,
								PRICE:row.U_PRICE
							}
						});
					}
				}
			});

			$('#dg_edit').datagrid({
			    singleSelect: true,
				rownumbers: true,
				fitColumns: true,
			    columns:[[
					{field:'ID', title:'ID', width:80, halign: 'center', align: 'center', hidden: true},
					{field:'ITEM_NO', title:'ITEM NO', width:80, halign: 'center'},
				    {field:'DESCRIPTION', title:'ITEM NAME', width:200, halign: 'center'},
				    {field:'UOM_Q', title:'UNIT', halign: 'center', width:80, align: 'right', hidden: true},
				    {field:'UNIT', title:'UoM', halign: 'center', width:50, align:'center'},
				    {field:'PRICE', title:'PRICE', halign: 'center',width:70, align:'right'},//, editor:{type:'textbox'}
				    {field:'QTY', title:'QTY', align:'right', halign: 'center', width:50, editor:{type:'numberbox',options:{precision:0,groupSeparator:','}}}
			    ]],
			    onClickRow:function(rowIndex){
			        $(this).datagrid('beginEdit', rowIndex);
			    },
			    onDblClickRow:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    }
			});

			$('#dg_find_edit').datagrid({
				fitColumns: true,
				rownumbers: true,
				singleSelect: true,
				columns:[[
					{field:'ITEM_NO',title:'ITEM NO.', halign:'center', width:100},
	                {field:'DESCRIPTION', title:'ITEM NAME', halign:'center', width:200},
	                {field:'UOM_Q',title:'IDR', halign:'center', align:'right', width:70, hidden:true},
	                {field:'UNIT', title:'UoM', halign:'center', align:'right', width:30, hidden:true},
	                {field:'U_PRICE', title:'PRICE', halign:'center', align:'right', width:70, hidden:true}
				]],
				onDblClickRow:function(id,row){
					var t = $('#dg_edit').datagrid('getRows');
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=total;
					}else{
						idxfield=total+1;
						for (i=0; i < total; i++) {
							//alert(i);
							var item = $('#dg_edit').datagrid('getData').rows[i].ITEM_NO;
							//alert(item);
							if (item == row.ITEM_NO) {
								count++;
							};
						};
					}

					//alert('count = '+count);
					if (count>0) {
						$.messager.alert('Information','Item present','warning');
					}else{
						$('#dg_edit').datagrid('insertRow',{
							index: idxfield,	// index start with 0
							row: {
								ITEM_NO: row.ITEM_NO,
								DESCRIPTION: row.DESCRIPTION,
								UNIT: row.UNIT,
								UOM_Q: row.UOM_Q,
								PRICE: row.U_PRICE,
								ID: row.ID
							}
						});
					}
				}
			});

		});

		function new_req(){
			$('#dlg_add').dialog('open').dialog('setTitle','Create Purchase Requistion');
			$('#dg_add').datagrid('loadData',[]);
			$('#req_date_add').datebox('setValue','<?=date('Y-m-d')?>');
			$('#prfno_add').textbox('setValue','');
			$('#gtotal_add').textbox('setValue','');
			$('#remarks_add').textbox('setValue','');
			$('#txtfind_a').textbox('setValue','');
			$('#txtfind_a').next().find('input').focus();
		}
		/*function new_item(event){
			if(event.keyCode == 13 || event.which == 13){
				new_item_add();				
			}
		}*/

		function new_item_add(){
			var fnd = $('#txtfind_a').textbox('getValue');
			if(fnd==''){
				$.messager.alert("warning","Find Item Field Can't Empty!","Warning");
			}else{
				$('#dg_find_add').datagrid('reload');
				$('#dlg_find_add').window('open').window('setTitle','FIND ITEM');
				$('#dg_find_add').datagrid('load',{
					find: $('#txtfind_a').textbox('getValue')
				});

				$('#dg_find_add').datagrid({
					url: 'prf_req_finditem.php'
				});
				$('#dg_find_add').datagrid('enableFilter');
				$('#txtfind_a').textbox('setValue','');
				$('#txtfind_a').next().find('input').focus();
			}
		}

		function delete_item_add(){
			var row = $('#dg_add').datagrid('getSelected');	
			if (row){
				var idx = $("#dg_add").datagrid("getRowIndex", row);
				$('#dg_add').datagrid('deleteRow', idx);
			}
		}

		function simpan(){
			var date_add = $('#req_date_add').datebox('getValue');
			var gtot_add = $('#gtotal_add').textbox('getValue');
			
			var rate_value;
			if (document.getElementById('budget_add').checked) {
				rate_value = document.getElementById('budget_add').value;
			}else if(document.getElementById('nonbudget_add').checked){
				rate_value = document.getElementById('nonbudget_add').value;	
			}

			var comp_value;
			if (document.getElementById('COMPLETE_add').checked) {
				comp_value = document.getElementById('COMPLETE_add').value;
			}else if(document.getElementById('INCOMPLETE_add').checked){
				comp_value = document.getElementById('INCOMPLETE_add').value;	
			}

			if(date_add=='' && gtot_add==''){
				$.messager.alert("warning","Required Field Can't Empty!","Warning");
			}else{
				var t1 = $('#dg_add').datagrid('getRows');
				var total1 = t1.length;
				for(i=0;i<total1;i++){
					$('#dg_add').datagrid('endEdit',i);
					$.post('prf_req_save.php',{
						req_no: $('#prfno_add').textbox('getValue'),
						req_date: $('#req_date_add').datebox('getValue'),
						item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
						price: $('#dg_add').datagrid('getData').rows[i].PRICE.replace(/,/g,''),
						qty: $('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,''),
						unit: $('#dg_add').datagrid('getData').rows[i].UNIT_CODE,
						tot: $('#gtotal_add').textbox('getValue').replace(/,/g,''),
						rmk: $('#remarks_add').textbox('getValue'),
						bdg: rate_value,
						cmplt: comp_value
					}).done(function(res){
						//alert(res);
						//console.log(res);
						$('#dlg_add').dialog('close');
						$('#dg').datagrid('reload');
					})
				}
			}
		}

		function saveAdd(){
			var url='';
			$.ajax({
				type: 'GET',
				url: 'json/json_prf.php',
				data: { kode:'kode' },
				success: function(data){
					$('#prfno_add').textbox('setValue', data[0].kode);
					var t2 = $('#dg_add').datagrid('getRows');
					var total2 = t2.length;
					var jum = 0;
					for(i=0;i<total2;i++){
						$('#dg_add').datagrid('endEdit',i);
						var hrg = $('#dg_add').datagrid('getData').rows[i].PRICE.replace(/,/g,'');
						var q = $('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,'');
						var ttl = hrg * q;
						jum += ttl;
					}
					$('#gtotal_add').textbox('setValue',jum);
					simpan();
				}
			});
			$.messager.alert('Save Purchase Requistion','Data Saved!','info');
			$('#dlg_add').dialog('close');
			$('#dg').datagrid('reload');
		}

		/*EDIT*/
		function edit_req(){
			var row = $('#dg').datagrid('getSelected');
            if (row){
            	$('#dlg_edit').dialog('open').dialog('setTitle','Edit Purchase Requistion ('+row.REQ_NO+')');
				$('#req_date_edit').datebox('setValue',row.REQ_DT);
				$('#prfno_edit').textbox('setValue',row.REQ_NO);
				$('#gtotal_edit').textbox('setValue',row.TOTAL);
				$('#remarks_edit').textbox('setValue',row.REMARKS);
				
				if(document.getElementById('budget_edit').value == row.TYPE_BUDGET){
					document.getElementById('budget_edit').checked = 'true';
				}else if(document.getElementById('nonbudget_edit').value == row.TYPE_BUDGET){
					document.getElementById('nonbudget_edit').checked = 'true';
				}

				if(document.getElementById('COMPLETE_edit').value == row.TYPE_COMPLETE){
					document.getElementById('COMPLETE_edit').checked = 'true';
				}else if(document.getElementById('INCOMPLETE_edit').value == row.TYPE_COMPLETE){
					document.getElementById('INCOMPLETE_edit').checked = 'true';
				}

				$('#dg_edit').datagrid('loadData',[]);
				$('#dg_edit').datagrid({
					url:'prf_req_getedit.php?req_no='+row.REQ_NO
				});
				$('#txtfind_e').textbox('setValue','');
				$('#txtfind_e').next().find('input').focus();
            }
		}

		function new_item_edit(){
			var fnd_e = $('#txtfind_e').textbox('getValue');
			if(fnd_e==''){
				$.messager.alert("warning","Find Item Field Can't Empty!","Warning");
			}else{
				$('#dg_find_add').datagrid('reload');
				$('#dlg_find_edit').window('open').window('setTitle','FIND ITEM');
				$('#dg_find_edit').datagrid('load',{
					find: $('#txtfind_e').textbox('getValue')
				});
				$('#dg_find_edit').datagrid({
					url: 'prf_req_finditem.php'
				});
				$('#dg_find_edit').datagrid('enableFilter');
				$('#txtfind_e').textbox('setValue','');
				$('#txtfind_e').next().find('input').focus();
			}	
		}

		function delete_item_edit(){
			var row = $('#dg_edit').datagrid('getSelected');	
			if (row){
				var id_PRF = row.ID;
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						$.post('prf_req_destroy_dtl.php',{
							idprf: id_PRF
						}).done(function(res){
							//alert(res)
							var idx = $("#dg_edit").datagrid("getRowIndex", row);
							$('#dg_edit').datagrid('deleteRow', idx);
						});
					}	
				});
			}
		}

		function update_E(){
			var date_edit = $('#req_date_edit').datebox('getValue');
			var gtot_edit = $('#gtotal_edit').textbox('getValue');

			var rate_value_e;
			if (document.getElementById('budget_edit').checked) {
				rate_value_e = document.getElementById('budget_edit').value;
			}else if(document.getElementById('nonbudget_edit').checked){
				rate_value_e = document.getElementById('nonbudget_edit').value;	
			}

			var comp_value_e;
			if (document.getElementById('COMPLETE_edit').checked) {
				comp_value_e = document.getElementById('COMPLETE_edit').value;
			}else if(document.getElementById('INCOMPLETE_edit').checked){
				comp_value_e = document.getElementById('INCOMPLETE_edit').value;	
			}

			if(date_edit='' && gtot_edit==''){
				$.messager.alert("warning","Required Field Can't Empty!","Warning");
			}else{
				var t3 = $('#dg_edit').datagrid('getRows');
				var total3 = t3.length;
				for(i=0;i<total3;i++){
					$('#dg_edit').datagrid('endEdit',i);
					$.post('prf_req_update.php',{
						req_no: $('#prfno_edit').textbox('getValue'),
						req_date: $('#req_date_edit').datebox('getValue'),
						item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
						price: $('#dg_edit').datagrid('getData').rows[i].PRICE.replace(/,/g,''),
						qty: $('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,''),
						unit: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
						id: $('#dg_edit').datagrid('getData').rows[i].ID,
						tot: $('#gtotal_edit').textbox('getValue').replace(/,/g,''),
						rmk: $('#remarks_edit').textbox('getValue'),
						bdg: rate_value_e,
						cmplt: comp_value_e
					}).done(function(res){
						//alert(res);
						//console.log(res);
						$('#dlg_edit').dialog('close');
						$('#dg').datagrid('reload');
					})
				}
			}
		}

		function updateEdit(){
			var t4 = $('#dg_edit').datagrid('getRows');
			var total4 = t4.length;
			var jum = 0;
			for(i=0;i<total4;i++){
				$('#dg_edit').datagrid('endEdit',i);
				var hrg = $('#dg_edit').datagrid('getData').rows[i].PRICE.replace(/,/g,'');
				var q = $('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,'');
				var ttl = hrg * q;
				jum += ttl;
			}
			$('#gtotal_edit').textbox('setValue',jum);
			update_E();
		}


		function destroy_req(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Are you sure you want to destroy this REQUISITION?',function(r){
                    if (r){
                        $.post('prf_req_destroy.php',{id:row.REQ_NO},function(result){
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

        function print_req(){
        	var row = $('#dg').datagrid('getSelected');
            if (row){
            	window.open('prf_req_printpdf.php?reqno='+row.REQ_NO+'&reqdate='+row.REQ_DATE, '_blank');
            }else{
            	$.messager.show({title: 'PDF Report',msg: 'Data Not Defined'});
            }
        }

        function print_req_excel(){
        	var row = $('#dg').datagrid('getSelected');
            if (row){
            	window.open('prf_req_printexcel.php?reqno='+row.REQ_NO+'&reqdate='+row.REQ_DATE, '_blank');
            }else{
            	$.messager.show({title: 'PDF Report',msg: 'Data Not Defined'});
            }	
        }
	</script>
    </body>
    </html>
