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
				<a href="javascript:void(0)" id="checkmaterial" class="easyui-linkbutton" onClick="print_out()"><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Check Material</a>
				<a href="javascript:void(0)" id="printpdf" class="easyui-linkbutton" onClick="print_do()"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>
			</div>
			<!-- <div class="fitem">
				<a href="javascript:void(0)" id="popUp" class="easyui-linkbutton" iconCls='icon-print' onClick="print()">Print All</a>	
			</div> -->
		</fieldset>
	</div>

	<div id="dlg_print" class="easyui-dialog" style="width:920px;height:500px;" closed="true" buttons="#dlg-buttons-print" data-options="modal:true">
		<table id="dg_check" class="easyui-datagrid"></table><br/>
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

		var pdf_url='';

		$(function(){
			$('#checkmaterial').linkbutton('disable');
			$('#printpdf').linkbutton('disable');
		})

		function filterData(){
			$('#checkmaterial').linkbutton('enable');
			$('#printpdf').linkbutton('enable');
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
				    url:'outgoing_get2.php',
				    toolbar: '#toolbar',
				    singleSelect: true,
					rownumbers: true,
					fitColumns: true,
					sortName: 'receive_date',
					sortOrder: 'desc',
				    columns:[[
				    	{field:'SLIP_NO',title:'SLIP NO.', halign:'center', width:150, sortable: true, sortable: true},
		                {field:'SLIP_DATE', title:'SLIP DATE', halign:'center', align:'center', width:50, sortable: true}, 
		                {field:'COMPANY_CODE',hidden:true},
		                {field:'COMPANY',title:'COMPANY', halign:'center', width:200, sortable: true},
		                {field:'APPROVAL_DATE', title:'APPROVAL<br>DATE', halign:'center', align:'center', width:50},
		                {field:'APPROVAL_PERSON_CODE', title:'APPROVAL<br>PERSON', halign:'center', width:70},
		                {field:'STS', title:'STS', halign:'center', align:'center', width:70, hidden: true},
		                {field:'STS_NAME', title:'Status', halign:'center', align:'center', width:90}
				    ]],
				    view: detailview,
					detailFormatter: function(rowIndex, rowData){
						return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
					},
					onExpandRow: function(index,row){
						var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');

		                listbrg.datagrid({
		                	title: 'SLIP Detail (No: '+row.SLIP_NO+')',
							url:'outgoing_get_detail.php?req='+row.SLIP_NO,
							toolbar: '#ddv'+index,
							singleSelect:true,
							rownumbers:true,
							loadMsg:'load data ...',
							height:'auto',
							rownumbers: true,
							fitColumns: true,
							columns:[[
								{field:'SLIP_NO',title:'Slip No.', halign:'center', width:150, sortable: true, hidden:true},
				                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:80, sortable: true},
				                {field:'DESCRIPTION', title:'Material Name', halign:'center', width:240},
				                {field:'LINE_NO', title:'Line No.', halign:'center', align:'center', width:50}, 
				                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
				                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:70}
							]],
							onResize:function(){
								//alert(index);
								$('#dg').datagrid('fixDetailRowHeight',index);
							},
							onLoadSuccess:function(){
								setTimeout(function(){
									$('#dg').datagrid('fixDetailRowHeight',index);
								},0);
							}
		                });
		                
					}
			    });
			}
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		}

		function print_out(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				if(row.STS == 1){
					$.messager.alert('Warning','Data Already Exist','warning');
				}else{
					$('#dlg_print').dialog('open').dialog('setTitle','Check Material ('+row.SLIP_NO+')');
					
					$('#dg_check').datagrid({
						url:'outgoing_check_get.php?slip='+row.SLIP_NO,
					    singleSelect: true,
						rownumbers: true,
						fitColumns: true,
					    columns:[[
							{field:'SLIP_NO',title:'Slip No.', halign:'center', width:150, sortable: true, hidden:true},
			                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:80, sortable: true},
			                {field:'DESCRIPTION', title:'Material Name', halign:'center', width:240},
			                {field:'LINE_NO', title:'Line No.', halign:'center', align:'center', width:50}, 
			                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
			                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:70}
						]],
						view: detailview,
						detailFormatter: function(rowIndex, rowData){
							return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="lismaterial"></table></div>';
						},
						onExpandRow: function(index,row){
							var lismaterial = $(this).datagrid('getRowDetail',index).find('table.lismaterial');

							lismaterial.datagrid({
			                	title: 'MAterial Detail (Item: '+row.DESCRIPTION+')',
								url:'outgoing_check_get_detail.php?slip='+row.SLIP_NO+'&item='+row.ITEM_NO+'&qty='+row.QTY+'&ln='+row.LINE_NO,
								singleSelect:true,
								rownumbers:true,
								loadMsg:'load data ...',
								height:'auto',
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
							    ]],
								onResize:function(){
									//alert(index);
									$('#dg_check').datagrid('fixDetailRowHeight',index);
								},
								onLoadSuccess:function(){
									setTimeout(function(){
										$('#dg_check').datagrid('fixDetailRowHeight',index);
									},0);
								}
			                });
						}
					});
				}
			}else{
				$.messager.show({title: 'Check Materials',msg: 'Data Not select'});
			}
		}

		function print_do(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				if(row.STS == 1){
					pdf_url = "?do="+row.SLIP_NO
					window.open('outgoing_print.php'+pdf_url);	
				}else{
					$.messager.alert('Warning','Please select button Check Material','warning');
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
					slip: $('#dg_check').datagrid('getData').rows[i].SLIP_NO,
					item: $('#dg_check').datagrid('getData').rows[i].ITEM_NO,
					qty: $('#dg_check').datagrid('getData').rows[i].QTY,
					ln: $('#dg_check').datagrid('getData').rows[i].LINE_NO
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