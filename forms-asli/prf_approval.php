<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Requitision Approval</title>
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
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approval()">Approval</a>
	</div>

	<table id="dg" title="Requistion Approval" class="easyui-datagrid" selectOnCheck= "true" toolbar="#toolbar" style="width:100%;height:490px;"></table>

	<div id="dlg_approved" class="easyui-dialog" style="width:1000px;height:450px;padding:5px 10px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
		<!-- <div id="toolbarAppr">
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Payment Terms</span>
				<input required="true" style="width:150px;" name="payment_terms" id="payment_terms" class="easyui-textbox" data-options="" />
				<span style="width:100px;display:inline-block;" align="right">Shipment Via</span>
				<input style="width:100px;" name="cmb_via" id="cmb_via" class="easyui-combobox" data-options=" url:'json/json_via.json',method:'get',valueField:'via',textField:'via', panelHeight:'75px'" required/>
			</div>
		</div> -->
		<table id="dg_appr" class="easyui-datagrid" style="width:100%;height:100%;" toolbar="#toolbarAppr"></table>		
	</div>

	<div id="dlg_pilih" class="easyui-dialog" style="width:500px;height:200px;padding:5px 10px" closed="true" buttons="#dlg-buttons-pilih" data-options="modal:true">
		<table id="dg_pilih" class="easyui-datagrid" style="width:100%;height:100%;"></table>		
	</div>

	<div id="dlg-buttons-add">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="appr()" style="width:90px">Approved</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_approved').dialog('close')" style="width:90px">Cancel</a>
	</div>

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

		$(function(){
			$('#dg').datagrid({
				url: 'prf_req_get.php',
				rownumbers:'true', 
				fitColumns:'true',
				singleSelect:'true',
				striped: 'true',
				columns:[[
					{field:'REQ_NO',title:'REQUITISION NO.', halign:'center', width:50},
	                {field:'REQ_DATE', title:'REQ. DATE', halign:'center', width:70},
	                {field:'REQ_DT', title:'REQ. DATE', halign:'center', width:70, hidden: true},
	                {field:'TOTAL',title:'GRAND TOTAL', halign:'center', align:'right', width:70},
	                {field:'USER_ENTRY', title:'USER ENTRY', halign:'center', align:'right', width:70},
	                {field:'LAST_UPDATE', title:'UPDATE', halign:'center', align:'right', width:70}
				]],
				view: detailview,
				detailFormatter:function(index,row){
					return '<div style="padding:2px" class="ddv"></div>';
				},
			 	onExpandRow: function(index,row){
			 		var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
	                var uri_doc = encodeURIComponent(row.REQ_NO)
	                ddv.datagrid({
	                	title: 'Requitision Detail (No: '+row.REQ_NO+')',
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
			 	}
			});

			$('#dg_appr').datagrid({
				singleSelect: true,
				rownumbers: false,
			    columns:[[
					{halign:'center', field:'REQ_NO', title:'ID', width:70, hidden: true},//
					{halign:'center', field:'ID', title:'ID', width:70, hidden: true},//
					{halign:'center', field:"ITEM_NO", title:'ITEM', width:150},
					{halign:'center', field:"DESCRIPTION", title:'ITEM NAME', width:250},
					{halign:'center', field:"UNIT", align: 'center', title:'UoM', width:50},
					{halign:'center', field:"PRICE", align:'right', title:'Price (IDR)', width:100},
					{halign:'center', field:"QTY", align:'right', title:'QTY', width:50},
					{halign:'center', field:"TOTAL", align:'right', title:'TOTAL', width:100},
					{halign:'center', field:"VENDOR_ID", width:100, hidden: 'true'},
					{halign:'center', field:"VENDOR", title:'VENDOR', width:200},
					{halign:'center', field:"STS", hidden: 'true'},
					{halign:'center', field:"STS_APPR", align:'center', title:'APPROVED', width:100},
					{halign:'center', field:"CURR", hidden: 'true'},
					{halign:'center', field:"ESTIMATE", width:100}
			    ]],
			    onSelect:function(index, row){
			        pilih();
			    }
			});

			$('#dg_pilih').datagrid({
				rownumbers:'true', 
				fitColumns:'true',
				singleSelect:'true',
				columns:[[
					{field:'INDEX', title:'COMPANY', halign:'center', width:200, hidden:'true'},
					{field:'SUPPLIER_CODE',title:'SUPPLIER CODE', halign:'center', width:50, hidden:'true'},
	                {field:'COMPANY', title:'COMPANY', halign:'center', width:200},
	                {field:'ESTIMATE_PRICE', title:'ESTIMATE<br>PRICE', halign:'center',align: 'right', width:70},
	                {field:'CURR', title:'CRC', halign:'center',align: 'right', width:30}
				]],
				onClickRow:function(index,row){
					$(this).datagrid('beginEdit', rowIndex);
				},
				onDblClickRow:function(index,row){
					var idx = row.INDEX;
					$('#dg_appr').datagrid('updateRow',{
						index: idx,
						row: {
							VENDOR: row.COMPANY,
							VENDOR_ID: row.SUPPLIER_CODE,
							STS_APPR: 'APPROVED',
							STS: '1',
							CURR: row.CURR,
							ESTIMATE: row.ESTIMATE_PRICE
						}
					});	
				}
			});

		});

		function approval(){
			var row = $('#dg').datagrid('getSelected');
            if (row){
            	$('#dlg_approved').dialog('open').dialog('setTitle','Requistion Approval ('+row.REQ_NO+')');
            	$('#dg_appr').datagrid('loadData',[]);
				$('#dg_appr').datagrid({
					url:'prf_approval_getdtl.php?req_no='+row.REQ_NO
				});
            }else{
            	$.messager.alert("warning","Can't Selected!","Warning");
            }
		}

		function pilih(){
			var row = $('#dg_appr').datagrid('getSelected');
            if (row){
            	var idx = $('#dg_appr').datagrid('getRowIndex',row);
				$('#dlg_pilih').dialog('open').dialog('setTitle','FIND VENDOR ('+row.ITEM_NO+' : '+row.DESCRIPTION+')');
				$('#dg_pilih').datagrid('loadData',[]);
				$('#dg_pilih').datagrid({
					url:'json/json_vendor.php?item='+row.ITEM_NO+'&index='+idx
				});
			}
		}

		function appr(){
			/*var pay = $('#payment_terms').textbox('getValue');
			var vi = $('#cmb_via').combobox('getValue');*/
			var t = $('#dg_appr').datagrid('getRows');
			var total = t.length;

			/*if(pay=='' && vi==''){
				$.messager.alert("warning","Required Field Can't Empty!","Warning");
			}else{*/
				for(i=0;i<total;i++){
					$('#dg_appr').datagrid('endEdit',i);
					$.post('prf_approval_save.php',{
						rq: $('#dg_appr').datagrid('getData').rows[i].REQ_NO,
						id: $('#dg_appr').datagrid('getData').rows[i].ID,
						vn: $('#dg_appr').datagrid('getData').rows[i].VENDOR_ID,
						st: $('#dg_appr').datagrid('getData').rows[i].STS,
						to: $('#dg_appr').datagrid('getData').rows[i].TOTAL.replace(/,/g,''),
						cr: $('#dg_appr').datagrid('getData').rows[i].CURR,
						pr1: $('#dg_appr').datagrid('getData').rows[i].PRICE.replace(/,/g,''),
						pr2: $('#dg_appr').datagrid('getData').rows[i].ESTIMATE.replace(/,/g,'')
						/*py: $('#payment_terms').textbox('getValue'),
						va: $('#cmb_via').combobox('getValue')*/
					}).done(function(res){
						//alert(res);
						//console.log(res);
						$('#dlg_approved').dialog('close');
						$('#dlg_pilih').dialog('close');
						$('#dg').datagrid('reload');
					})
				}
			/*}*/
		}
	</script>
    </body>
    </html>