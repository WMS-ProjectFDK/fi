<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>SHIPPING PLAN ENTRY</title>
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
	<?php include ('../ico_logout.php'); ?>

	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:700px; height:75px; float:left;"><legend>SHIPPING PLAN FILTER</legend>
			<div style="width:400px; float:left;">
				<div class="fitem">
				   <span style="width:80px;display:inline-block;">ETD Date</span>
				   <input style="width:85px;" name="etd_date_awal" id="etd_date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				   to
				   <input style="width:85px;" name="etd_date_akhir" id="etd_date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				</div>
				<div class="fitem">	
					<span style="width:80px;display:inline-block;">SO No.</span>
					<select style="width:190px;" name="cmb_so_no" id="cmb_so_no" class="easyui-combobox" data-options=" url:'json/json_so_no.php',method:'get',valueField:'so_no',textField:'so_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_so_no" id="ck_so_no" checked="true">All</input></label>
				</div>
			</div>
			<div>
				<div class="fitem">	
					<span style="width:80px;display:inline-block;">CR Date</span>
					<input style="width:85px;" name="cr_date" id="cr_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					<label><input type="checkbox" name="ck_cr_date" id="ck_cr_date" checked="true">All</input></label>
				</div>
				<div class="fitem">	
					<span style="width:80px;display:inline-block;">ETA Date</span>
					<input style="width:85px;" name="eta_date" id="eta_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					<label><input type="checkbox" name="ck_eta_date" id="ck_eta_date" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<div style="clear:both;"></div>
		<div style="margin-top: 5px;margin: 10px;">
			<span style="width:50px;display:inline-block;">search</span>
			<input style="width:150px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" />
			<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</a>
			<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="parsial()"><i class="fa fa-plus" aria-hidden="true"></i> PARSIAL</a>
			<a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="save()"><i class="fa fa-save" aria-hidden="true"></i> SAVE SHIPPING PLAN</a>
		</div>
	</div>

	<table id="dg" title="SHIPPING PLAN ENTRY" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;"></table>
	
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

		var url_combo='';

		$(function(){
			$('#cmb_so_no').combobox('disable');
			$('#ck_so_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_so_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_so_no').combobox('enable');
				};
			})

			$('#cr_date').datebox('disable');
			$('#ck_cr_date').change(function(){
				if ($(this).is(':checked')) {
					$('#cr_date').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cr_date').datebox('enable');
				};
			})

			$('#eta_date').datebox('disable');
			$('#ck_eta_date').change(function(){
				if ($(this).is(':checked')) {
					$('#eta_date').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#eta_date').datebox('enable');
				};
			})

			$('#dg').datagrid({
		    	singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
			    columns:[[
				    {field:'SO_NO',title:'SO NO.',width:75, halign: 'center', align: 'center'},
				    {field:'LINE_NO',title:'CUST. CODE', hidden: true},
				    {field:'CUSTOMER_CODE',title:'CUST. CODE', hidden: true},
				    {field:'CUSTOMER_PO_NO',title:'CUST. PO', hidden: true},
				    {field:'CUSTOMER_DTL',title:'CUSTOMER',width:150, halign: 'center', align: 'left', sortable:true},
				    {field:'ITEM_NO',title:'ITEM NO',width:60, halign: 'center', align: 'center', hidden: true},
				    {field:'ITEM',title:'ITEM',width:250, halign: 'center'},
				    {field:'ORIGIN_CODE',title:'ORIGIN<br/>CODE', hidden: true},
				    {field:'COUNTRY_CODE',title:'COUNTRY<br/>CODE', hidden: true},
				    {field:'CR_DATE',title:'CR DATE', width:93, halign: 'center', editor:{
				    																	type:'datebox',
				    																	options:{formatter:myformatter,parser:myparser}
				    																}},
				    {field:'DATA_DATE',title:'EX FACT', width:93, halign: 'center', editor:{
				    																	type:'datebox',
				    																	options:{formatter:myformatter,parser:myparser}
				    																}},
				    {field:'ETD_DATE',title:'ETD DATE', width:93, halign: 'center', editor:{
				    																	type:'datebox',
				    																	options:{formatter:myformatter,parser:myparser}
				    																}},
				    {field:'ETA_DATE',title:'ETA DATE', width:93, halign: 'center', editor:{
				    																	type:'datebox',
				    																	options:{formatter:myformatter,parser:myparser}
				    																}},
				    {field:'QTY_SO',title:'ORDER<br/>QTY', width:70, halign: 'center', align: 'right'},
				    {field:'PLAN_QTY',title:'PLAN<br/>QTY', width:70, halign: 'center', align: 'right',editor:{type:'numberbox',options:{required:true,precision:0,groupSeparator:','}}},
				    {field:'SI_NO',title:'SI NO.', width:100, halign: 'center',editor:{
															                     type:'combogrid',
															                     options:{
																					panelWidth:470,
																					idField:'SI_NO',
																					textField:'SI_NO',
																					url: +url_combo,
																					columns:[[
																						{field:'SI_NO',title:'SI NO.',width:80, halign: 'center', align: 'center'},
																						{field:'PLACE_DELI_CODE',title:'PLACE Of DELIVERY',width:150, halign: 'center'},
																						{field:'CONSIGNE_NAME',title:'CONSIGNEE',width:150, halign: 'center'},
																						{field:'FORWARDER_NAME',title:'TO',width:150, halign: 'center'}
																					]]
																				  }
															                   }
					},
				    {field:'VESSEL',title:'VESSEL', width:100, halign: 'center',editor:{type:'textbox'}},
				    {field:'PALLET_NO',title:'PALLET', width:50, halign: 'center',editor:{type:'numberbox',options:{precision:0,groupSeparator:','}}},
				    {field:'CR_REMARK',title:'CR REMARK', width:150, halign: 'center',editor:{type:'textbox'}},
				    {field:'FLAG_QTY',title:'ANSWER', width:80, halign: 'center', align: 'center'},
				    {field:'ANSWER_NO', hidden: true},//
				    {field:'FLAG_ANS',title:'ANSWER', width:80, halign: 'center', align: 'center', hidden: true},//
				    {field:'PO_NO', hidden: true},//
				    {field:'PO_LINE_NO', hidden: true},//
				    {field:'WORK_ORDER', hidden: true},//
				    {field:'U_PRICE', hidden: true},//
				    {field:'CURR_CODE', hidden: true}//
			    ]],
			    onDblClickRow:function(row){
			    	x_load();
			    	$(this).datagrid('beginEdit', row);
			    }
			});
		});


		function filterData(){
			var ck_so_no='false';
			var ck_cr_date='false';
			var ck_eta_date='false';

			if($('#ck_so_no').attr("checked")){
				ck_so_no='true';
			}
			if($('#ck_cr_date').attr("checked")){
				ck_cr_date='true';
			}
			if($('#ck_eta_date').attr("checked")){
				ck_eta_date='true';
			}
			
			$('#dg').datagrid('load', {
				date_awal: $('#etd_date_awal').datebox('getValue'),
				date_akhir: $('#etd_date_akhir').datebox('getValue'),
				cmb_so_no: $('#cmb_so_no').combobox('getValue'),
				ck_so_no: ck_so_no,
				cr_date: $('#cr_date').datebox('getValue'),
				ck_cr_date: ck_cr_date,
				src: ''
			});

			$('#dg').datagrid({
				url:'shipping_plan_entry_get.php',
			})
		}

		function x_load(){
			var row = $('#dg').datagrid('getSelected');
			url_combo = 'json/json_po_no_shipping.php?cust_po='+row.CUSTOMER_PO_NO ;
		}

		function parsial(){
	    	var row = $('#dg').datagrid('getSelected');
	    	url_combo = 'json/json_po_no_shipping.php?cust_po='+row.CUSTOMER_PO_NO ;
			if (row.FLAG_QTY == 'OPEN'){
				var index = $('#dg').datagrid('getRowIndex', row)+1;

				$('#dg').datagrid('insertRow', {
					index: index,
					row:{
						SO_NO: row.SO_NO,
						LINE_NO: row.LINE_NO,
						CUSTOMER_CODE: row.CUSTOMER_CODE,
						CUSTOMER_PO_NO: row.CUSTOMER_PO_NO,
						CUSTOMER_DTL: row.CUSTOMER_DTL,
						ITEM_NO: row.ITEM_NO,
						ITEM: row.ITEM,
						ORIGIN_CODE: row.ORIGIN_CODE,
						COUNTRY_CODE: row.COUNTRY_CODE,
						CR_DATE: row.CR_DATE,
						DATA_DATE: row.DATA_DATE,
						ETD_DATE: row.ETD_DATE,
						ETA_DATE: row.ETA_DATE,
						QTY_SO: row.QTY_SO,
						PLAN_QTY: '',
						SI_NO: row.SI_NO,
						VESSEL: row.VESSEL,
						PALLET_NO: row.PALLET_NO,
						CR_REMARK: row.CR_REMARK,
						FLAG_QTY: row.FLAG_QTY,
						ANSWER_NO: '',
						FLAG_ANS: 'N',
						PO_NO: row.PO_NO,
						PO_LINE_NO: row.PO_LINE_NO,
						WORK_ORDER: row.WORK_ORDER,
						U_PRICE: row.U_PRICE,
						CURR_CODE: row.CURR_CODE
					}
				});
			}
			/*$('#dg').datagrid('selectRow',index);
			$('#dg').datagrid('beginEdit',index);*/
		}

		function save(){
			var t = $('#dg').datagrid('getRows');
			var total = t.length;
			var jmrow=0;
			for(i=0;i<total;i++){
				$('#dg').datagrid('endEdit',i);
				$.post('shipping_plan_entry_save.php',{
					sp_SO_NO: $('#dg').datagrid('getData').rows[i].SO_NO,
					sp_LINE_NO: $('#dg').datagrid('getData').rows[i].LINE_NO,
					sp_CUSTOMER_CODE: $('#dg').datagrid('getData').rows[i].CUSTOMER_CODE,
					sp_CUSTOMER_PO_NO: $('#dg').datagrid('getData').rows[i].CUSTOMER_PO_NO,
					sp_CUSTOMER_DTL: $('#dg').datagrid('getData').rows[i].CUSTOMER_DTL,
					sp_ITEM_NO: $('#dg').datagrid('getData').rows[i].ITEM_NO,
					sp_ITEM: $('#dg').datagrid('getData').rows[i].ITEM,
					sp_ORIGIN_CODE: $('#dg').datagrid('getData').rows[i].ORIGIN_CODE,
					sp_COUNTRY_CODE: $('#dg').datagrid('getData').rows[i].COUNTRY_CODE,
					sp_CR_DATE: $('#dg').datagrid('getData').rows[i].CR_DATE,
					sp_DATA_DATE: $('#dg').datagrid('getData').rows[i].DATA_DATE,
					sp_ETD_DATE: $('#dg').datagrid('getData').rows[i].ETD_DATE,
					sp_ETA_DATE: $('#dg').datagrid('getData').rows[i].ETA_DATE,
					sp_QTY_SO: $('#dg').datagrid('getData').rows[i].QTY_SO.replace(/,/g,''),
					sp_PLAN_QTY: $('#dg').datagrid('getData').rows[i].PLAN_QTY.replace(/,/g,''),
					sp_SI_NO: $('#dg').datagrid('getData').rows[i].SI_NO,
					sp_VESSEL: $('#dg').datagrid('getData').rows[i].VESSEL,
					sp_PALLET_NO: $('#dg').datagrid('getData').rows[i].PALLET_NO,
					sp_CR_REMARK: $('#dg').datagrid('getData').rows[i].CR_REMARK,
					sp_FLAG_QTY: $('#dg').datagrid('getData').rows[i].FLAG_QTY,
					sp_ANSWER_NO: $('#dg').datagrid('getData').rows[i].ANSWER_NO,
					sp_FLAG_ANS: $('#dg').datagrid('getData').rows[i].FLAG_ANS,
					sp_PO_NO: $('#dg').datagrid('getData').rows[i].PO_NO,
					sp_PO_LINE_NO: $('#dg').datagrid('getData').rows[i].PO_LINE_NO,
					sp_WORK_ORDER: $('#dg').datagrid('getData').rows[i].WORK_ORDER,
					sp_U_PRICE: $('#dg').datagrid('getData').rows[i].U_PRICE,
					sp_CURR_CODE: $('#dg').datagrid('getData').rows[i].CURR_CODE
				}).done(function(res){
					//alert(res);
					console.log(res);
				});
			}
			$.messager.alert('INFORMATION','Insert Data Success..!!','info');
			$('#dg').datagrid('reload');
		}
	</script>

	</body>
    </html>