<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>FG Transfer</title>
    <link rel="icon" type="image/png" href="../favicon.png">
	<script language="javascript">
			function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
			}
	</script> 
	<!-- /<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css"> -->
	<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
	<link rel="stylesheet" type="text/css" href="../themes/icon.css" />
	<link rel="stylesheet" type="text/css" href="../css/style.css">
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
	<?php include ('../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name)); ?>
	
	<table id="dg" title="FINISH GOODS TRANSFER" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:470px;"></table>
	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:97%; float:left; margin:5px;"><legend>Search WO Temporary</legend>
			<div style="width:100%; height: 40px; float:left;">	
				<div class="fitem">
					<span style="width:50px;display:inline-block;">WO No.</span>
					<select style="width:300px;" name="cmb_wo_add_temp" id="cmb_wo_add_temp" class="easyui-combobox" data-options=" url:'json/json_wo_fg_transfer.php?s=temp',method:'get',valueField:'wo_no',textField:'wo_no', panelHeight:'150px',
					onSelect: function(rec){
						$('#qty_wo_add_temp').textbox('setValue',rec.qty_pi);
						$('#item_no_add_temp').textbox('setValue',rec.item);
					}" required=""></select>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:50px;display:inline-block;">Qty</span>
					<input style="width:150px;" name="qty_wo_add_temp" id="qty_wo_add_temp" class="easyui-textbox" disabled="" />
					<span style="width:50px;display:inline-block;"></span>
					<input style="width:350px;" name="item_no_add_temp" id="item_no_add_temp" class="easyui-textbox" disabled="" />
				</div>
			</div>
		</fieldset>
		<div align="center" style="width: 1045px;">
			<i class="fa fa-refresh fa-3x" aria-hidden="true"></i>
		</div>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:97%; float:left; margin:5px;"><legend>Search WO </legend>
			<div style="width:100%; height: 40px; float:left;">	
				<div class="fitem">
					<span style="width:50px;display:inline-block;">WO No.</span>
					<select style="width:300px;" name="cmb_wo_add" id="cmb_wo_add" class="easyui-combobox" data-options=" url:'json/json_wo_fg_transfer.php?s=real',method:'get',valueField:'wo_no',textField:'wo_no', panelHeight:'150px',
					onSelect: function(rec){
						$('#qty_wo_add').textbox('setValue',rec.qty_mps);
						$('#item_no_add').textbox('setValue',rec.item);
					}" required=""></select>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:50px;display:inline-block;">Qty</span>
					<input style="width:150px;" name="qty_wo_add" id="qty_wo_add" class="easyui-textbox" required="" />
					<span style="width:50px;display:inline-block;"></span>
					<input style="width:350px;" name="item_no_add" id="item_no_add" class="easyui-textbox" disabled="" />
				</div>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:97%; float:left; margin:5px;">
			<div align="center">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveFG_trans()" style="width:90px">Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearFG()" style="width:90px">Cancel</a>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
	</div>
	
	<script type="text/javascript">

		$(function(){
			$('#dg').datagrid({
				url:'fg_transfer_get.php',
			    fitColumns: true,
				rownumbers: true,
			    columns:[[
				    {field:'TRANS_CODE',title:'TRANSFER<br/>NO.',width:75, halign: 'center'},
				    {field:'TRANS_DATE',title:'TRANSFER<br/>DATE',width:65, halign: 'center', align: 'center'},
				    {field:'WO_NO_TEMP',title:'WO NO.<BR>TEMPORARY',width:170, halign: 'center'},
				    {field:'QTY_TEMP',title:'QTY<br/>TEMPORARY',width:60, halign: 'center', align:'right'},
				    {field:'WO_NO',title:'WO NO.',width:170, halign: 'center'},
				    {field:'QTY',title:'QTY',width:60, halign: 'center', align:'right'}
			    ]],
			    view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
				},
				onExpandRow: function(index,row){
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
					listbrg.datagrid({
	                	title: 'FINISH GOODS TRANSFER (WO No: '+row.WO_NO+')',
	                	url:'fg_transfer_get_detail.php?wo='+row.WO_NO,
						toolbar: '#ddv'+index,
						singleSelect:true,
						loadMsg:'load data ...',
						height:'auto',
						fitColumns: true,
						columns:[[
			                {field:'ITEM_NO',title:'ITEM NO.', halign:'center', align:'center', width:60, sortable: true},
			                {field:'ITEM_NAME', title:'DESCRIPTION', halign:'center', width:200},
			                {field:'SLIP_NO', title:'SLIP NO.', halign:'center', width:50},
			                {field:'SLIP_DATE', title:'SLIP DATE', halign:'center', align:'center', width:40},
			                {field:'SLIP_QUANTITY', title:'SLIP QTY', halign:'center', align:'right', width:50},
			                {field:'SLIP_PRICE', title:'PRICE', halign:'center', align:'right', width:50},
			                {field:'SLIP_AMOUNT', title:'AMOUNT', halign:'center', align:'right', width:50}
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
		});

		function clearFG(){
			$('#cmb_wo_add_temp').combobox('setValue','');
			$('#qty_wo_add_temp').textbox('setValue','');
			$('#item_no_add_temp').textbox('setValue','');
			$('#cmb_wo_add').combobox('setValue','');
			$('#qty_wo_add').textbox('setValue','');
			$('#item_no_add').textbox('setValue','');
		}

		function validate(){
			var hasil=0;
			var msg='';
			var ItemA = $('#item_no_add_temp').textbox('getValue');
			var resA = ItemA.split("-");
			var ItemB = $('#item_no_add').textbox('getValue');
			var resB = ItemB.split("-");
			
			if($('#cmb_wo_add').combobox('getValue') == '') {
				msg = $.messager.alert('Warning','Please select Work Order','warning');
				hasil=1;
			}else if ($('#qty_wo_add').textbox('getValue') == '' || $('#qty_wo_add').textbox('getValue') == 0) {
				msg = $.messager.alert('Warning','Qty WO not found','warning');
				hasil=1;
			}else if ($('#cmb_wo_add_temp').combobox('getValue') == ''){
				msg = $.messager.alert('Warning','Please select Work Order Temporary','warning');
				hasil=1;
			}else if ($('#qty_wo_add_temp').textbox('getValue') == '' || $('#qty_wo_add_temp').textbox('getValue') == 0){
				msg = $.messager.alert('Warning','Qty WO Temporary not found','warning');
				hasil=1;
			}else if ($('#qty_wo_add_temp').textbox('getValue') - $('#qty_wo_add').textbox('getValue') < 0){
				msg = $.messager.alert('Warning','Qty Temporary Not enough','warning');
				hasil=1;
			}else if (resA[0] != resB[0]){
				msg = $.messager.alert('Warning','Item No. Not the same','warning');
				hasil=1;
			}else if($('#cmb_wo_add').combobox('getValue') == $('#cmb_wo_add_temp').combobox('getValue')){
				msg = $.messager.alert('Warning','Wo No. the same','warning');
				hasil=1;
			}
			return hasil;
		}

		function simpan (){
			var dataRows = [];
			dataRows.push({
				fgTrans_wono: $('#cmb_wo_add').combobox('getValue'),
				fgTrans_qtyA: $('#qty_wo_add').textbox('getValue'),
				fgTrans_woTm: $('#cmb_wo_add_temp').combobox('getValue'),
				fgTrans_qtyB: $('#qty_wo_add_temp').textbox('getValue')
			})

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);

			//console.log(str_unescape);
			$.post('fg_transfer_save.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$.messager.alert('INFORMATION','Insert Data Success..!!','info');
					$('#dg').datagrid('reload');
				}else{
					$.messager.alert('INFORMATION','Insert Data Error..!!<br>'+res,'info');
				}
				$.messager.progress('close');
			});
		}

		function saveFG_trans(){
			console.log(validate());
			if (validate() == 0){
				$.messager.progress({
	                title:'Please waiting',
	                msg:'save data...'
	            });
				simpan();
				clearFG();
		    }
		}
	</script>
	</body>
    </html>