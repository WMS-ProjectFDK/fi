<?php 
include("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Outgoing Materials</title>
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
    <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../js/datagrid-filter.js"></script>
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
		<fieldset style="border:1px solid #d0d0d0; float: left; border-radius:4px; width:540px;height: 80px;"><legend>Filter</legend>
		<div style="float:left;">
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Type</span>
				<select style="width:200px;" name="dn_type" id="dn_type" class="easyui-combobox" data-options=" url:'json/json_type.php',method:'get',valueField:'id',textField:'type', panelHeight:'50px'" required></select>
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Date</span>
				<input style="width:95px;" name="dn_periode_awal" id="dn_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required/>
				<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton" onClick="filterData()" style="width:100px;"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;FILTER</a>
			</div>
		</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:350px;height: 80px;"><legend>Print Select</legend>
			<div class="fitem">
				<a href="javascript:void(0)" id="printpdf" class="easyui-linkbutton" onClick="print_out()"><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Check Material</a>
			</div>
			<!-- <div class="fitem">
				<a href="javascript:void(0)" id="popUp" class="easyui-linkbutton" iconCls='icon-print' onClick="print()">Print All</a>	
			</div> -->
		</fieldset>
	</div>

	<div id="dlg_print" class="easyui-dialog" style="width:520px;height:300px;padding:10px 20px" closed="true" buttons="#dlg-buttons-print">
		<table id="dg_check" class="easyui-datagrid" style="width:450px;"></table><br/>
		<div class="fitem">
			<span style="width:100px;display:inline-block;">Slip No.</span>
			<span style="width:100px;display:inline-block;">Material No.</span>
			<span style="width:100px;display:inline-block;">Qty</span>
			<span style="width:60px;display:inline-block;">UoM</span>
			<span style="width:60px;display:inline-block;">Line No.</span>
		</div>
		<div class="fitem">
			<input style="width:100px;display:inline-block;" id="slip_no" class="easyui-textbox" disabled="disabled"/>
			<input style="width:100px;display:inline-block;" id="item_no" class="easyui-textbox" disabled="disabled"/>
			<input style="width:100px;display:inline-block;" id="qty_slip" class="easyui-textbox" disabled="disabled"/>
			<input style="width:60px;display:inline-block;" id="uom" class="easyui-textbox" disabled="disabled"/>
			<input style="width:60px;display:inline-block;" id="line" class="easyui-textbox" disabled="disabled"/>
		</div>
	</div>

	<div id="dlg-buttons-print">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-print" onClick="print_pdf()" style="width:90px">Print</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_print').dialog('close');$('#dg').datagrid('reload');" style="width:90px">Cancel</a>
	</div>

	<table id="dg" title="Outgoing Materials" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;"></table>

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

		var url;
		var pdf_url='';
		var xls_url='';
		var total;

		$(function(){
			$('#printpdf').hide();
		})

		function filterData(){
			var ty = $('#dn_type').combobox('getValue');
			var t = $('#dn_periode_awal').datebox('getValue');
			if(ty=='' || t==''){
				$.messager.show({title: 'warning',msg: 'Please select filter'});
			}else{
				//$('#saved').show();
				$('#printpdf').show();
				$('#dg').datagrid('load', {
					type: $('#dn_type').combobox('getValue'),
					pd_periode_awal: $('#dn_periode_awal').datebox('getValue')
				});

				$('#dg').datagrid({
				    url:'outgoing_get.php',
				    toolbar: '#toolbar',
				    singleSelect: true,
					rownumbers: true,
					fitColumns: true,
					sortName: 'receive_date',
					sortOrder: 'desc',
				    columns:[[
				    	{field:'SLIP_NO',title:'Slip No.', halign:'center', width:150, sortable: true},
		                {field:'LINE_NO', title:'Line No.', halign:'center', align:'center', width:50}, 
		                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:80, sortable: true},
		                {field:'DESCRIPTION', title:'Material Name', halign:'center', width:240},
		                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
		                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:70},
		                {field:'STS', title:'STS', halign:'center', align:'center', width:70, hidden: true},
		                {field:'STS_NAME', title:'Status', halign:'center', align:'center', width:90}
				    ]],
					onDblClickRow:function(rowIndex){
				    	$(this).datagrid('beginEdit', rowIndex);
				    }
			    });
			}
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		}

		function print_out(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				pdf_url = "?do="+row.SLIP_NO+"&item="+row.ITEM_NO+"&line_no="+row.LINE_NO
				if(row.STS == 1){
					window.open('outgoing_print.php'+pdf_url);
				}else{
					$('#dlg_print').dialog('open').dialog('setTitle','Check Material ('+row.ITEM_NO+' - '+row.DESCRIPTION+')');
					$('#slip_no').textbox('setValue',""+row.SLIP_NO+"");
					$('#item_no').textbox('setValue',""+row.ITEM_NO+"");
					$('#qty_slip').textbox('setValue',""+row.QTY+"");
					$('#uom').textbox('setValue',""+row.UNIT+"");
					$('#line').textbox('setValue',""+row.LINE_NO+"");

					$('#dg_check').datagrid('load', {
						slip: +row.SLIP_NO,
						item: +row.ITEM_NO,
						qty: +row.QTY.replace(/,/g,''),
						ln: +row.LINE_NO
					});

					$('#dg_check').datagrid({
						url:'outgoing_check_get.php',
					    singleSelect: true,
						rownumbers: true,
						fitColumns: true,
					    columns:[[
					    	{field:'GR_NO',title:'Good Receive No. ', halign:'center', width:150, sortable: true},
			                {field:'LINE_NO', title:'Line No.', halign:'center', align:'center', width:80}, 
			                {field:'RACK',title:'Rack No.', halign:'center', align:'center', width:80, sortable: true},
			                {field:'PALLET', title:'PALLET', halign:'center', align:'center', width:70},
			                {field:'QTY', title:'QTY', halign:'center', align:'center', width:100},
			                {field:'ID', title:'ID', halign:'center', align:'center',hidden: true},
			                {field:'ITEM_NO', title:'Item No.', halign:'center', align:'center', hidden: true}
					    ]]
					});
				}
			}else{
				$.messager.show({title: 'Check Materials',msg: 'Data Not select'});
			}
		}

		function print_pdf(){
			var i = 0;
			var proc = 0;
			var rows = $('#dg_check').datagrid('getRows');
			
			for(i=0;i<rows.length;i++){
				$('#dg_check').datagrid('endEdit', i);
				$.post("outgoing_save.php", {
					gr_no: $('#dg_check').datagrid('getData').rows[i].GR_NO,
					lineno_in: $('#dg_check').datagrid('getData').rows[i].LINE_NO,
					rack: $('#dg_check').datagrid('getData').rows[i].RACK,
					pallet: $('#dg_check').datagrid('getData').rows[i].PALLET,
					qtyy: $('#dg_check').datagrid('getData').rows[i].QTY.replace(/,/g,''),
					id: $('#dg_check').datagrid('getData').rows[i].ID,
					lineno_out: $('#line').textbox('getValue'),
					slipno: $('#slip_no').textbox('getValue'),
					itemno: $('#item_no').textbox('getValue'),
					q_slip: $('#qty_slip').textbox('getValue').replace(/,/g,'')
				}).done(function(res){
					$('#dlg_print').dialog('close');
					$('#dg').datagrid('reload');
					//alert(res);
					//console.log(res);
				});
			}
			$.messager.alert('Save Outgoing Material','Data Saved!','info');
		}
	</script>
    </body>
    </html>