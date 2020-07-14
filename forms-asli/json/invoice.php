<?php 
session_start();
include("../connect/conn.php");
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
if ($varConn=='Y'){
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>PACKING LIST & INVOICE</title>
    <link rel="icon" type="image/png" href="../favicon.png">
	<script language="javascript">
		var ct = 0;
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
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:940px; height:100px; float:left;"><legend>INVOICE Filter</legend>
			<div style="width:470px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Invoice Date</span>
					<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"  value="<?date();?>" />
					to 
					<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"  value="<?date();?>" />
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Invoice No.</span>
					<select style="width:300px;" name="cmb_do_no" id="cmb_do_no" class="easyui-combobox" data-options=" url:'json/json_do_no.php',method:'get',valueField:'do_no',textField:'do_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_do" id="ck_do" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Customer</span>
					<select style="width:300px;" name="customer" id="customer" class="easyui-combobox" data-options=" url:'json/json_customer.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_customer" id="ck_customer" checked="true">All</input></label>
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Item No.</span>
					<select style="width:300px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'item_no',textField:'item_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; height:100px; margin-left:965px;"><legend>Invoice Report</legend></fieldset>
		<div style="clear:both;"></div>
		<div style="margin-top: 5px;margin: 5px;">
			<span style="margin-left:10px;width:50px;display:inline-block;">search</span> 
			<input style="width:150px; height: 20px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" placeholder="invoice no." name="src" id="src" type="text" />
			<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:120px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="addInv" onclick="addInv()"><i class="fa fa-plus" aria-hidden="true"></i> Add Invoice</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="editInv" onclick="editInv()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Invoice</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="deleteInv" onclick="deleteInv()"><i class="fa fa-trash" aria-hidden="true"></i> Delete Invoice</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="printInv" onclick="printInv()"><i class="fa fa-print" aria-hidden="true"></i> Print Invoice</a>
		</div>
	</div>
	<table id="dg" title="PACKING LIST & INVOICE" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;"></table>

	<!-- ADD START -->
	<div id='wnd_add' class="easyui-window" style="width:100%;height:550px;padding:5px 5px;" closed="true" data-options="modal:true">
		<form id="f_add" method="post" novalidate>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left; margin:5px;">
				<legend> Select Customer &
					Contract(<span id="Contract" style="display:inline-block;"></span>)
				</legend>
				<div class="fitem">
					<span style="width:70px;display:inline-block;">Customer</span>
					<select style="width:310px;" name="customer_add" id="customer_add" class="easyui-combobox" data-options=" url:'json/json_customer.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
					onSelect: function(rec){
		        		$.ajax({
		        			type: 'GET',
							url: 'json/json_info_cust.php?id='+rec.company_code,
							data: { kode:'kode' },
							success: function(data){
								$('#country_add').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].COUNTRY);
								$('#attn_add').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].ATTN);
								$('#curr_add').combobox('setValue',data[0].CURR_CODE);
								$('#rate_add').textbox('setValue',1);
								ct = data[0].JUM_CONTRACT;
								document.getElementById('Contract').innerHTML = ct;
								$('#payment_add').textbox('setValue',data[0].PMETHOD);
								$('#pday_add').textbox('setValue',data[0].PDAYS+'-'+data[0].PDESC);
								$('#tterm_add').textbox('setValue',data[0].TTERM);
								$('#sino_add').combogrid({
									url: 'invoice_get_si_no.php?id='+rec.company_code
								});

								alert('invoice_get_si_no.php?id='+rec.company_code)
							}
		        		});
	        		}" required=""></select>
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Country</span>
					<input style="width:140px;" name="country_add" id="country_add" class="easyui-textbox" disabled="" />
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">ATTN</span>
					<input style="width:200px;" name="attn_add" id="attn_add" class="easyui-textbox" disabled="" />
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Currency</span>
					<input style="width:85px;" id="curr_add" class="easyui-combobox" data-options=" url:'json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'100px',
		        	onSelect: function(rec){
		        		$.ajax({
		        			type: 'GET',
							url: 'json/json_exrate.php?curr='+rec.idcrc,
							data: { kode:'kode' },
							success: function(data){
								$('#rate_add').textbox('setValue',data[0].RATE);	
							}
		        		});
		        	}" required="" />
		        	<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Ex. Rate</span>
					<input style="width:140px;" name="rate_add" id="rate_add" class="easyui-textbox" disabled="" />
				</div>
				<div class="fitem"></div>
				<div class="fitem">
					<span style="width:70px;display:inline-block;">Invoice No.</span>
					<input style="width:120px;" name="do_no_add" id="do_no_add" class="easyui-textbox" required=""/>
					<span style="width:5px;display:inline-block;"></span>
					<span style="width:85px;display:inline-block;">Invoice Date</span>
					<input style="width:85px;" name="po_date_add" id="po_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Payment</span>
					<input style="width:140px;" name="payment_add" id="payment_add" class="easyui-textbox" disabled=""/>
					<input style="width:251px;" name="pday_add" id="pday_add" class="easyui-textbox" disabled=""/>
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">GST Rate</span>
					<input style="width:85px;" name="GST_add" id="GST_add" class="easyui-textbox"/>%
					<span style="width:2px;display:inline-block;"></span>
					<span style="display:inline-block;">T. Terms</span>
					<input style="width:140px;" name="tterm_add" id="tterm_add" class="easyui-textbox" disabled=""/>
				</div>
			</fieldset>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left; margin:5px;"><legend>SI Sett</legend>
			  <div style="width:400px; height: 140px; float:left;">	
				<div class="fitem">	
					<span style="width:100px;display:inline-block;">SI NO.</span>
					<select style="width:280px;" name="sino_add" id="sino_add" class="easyui-combogrid" style="width:142px;"></select>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">LOADING PORT</span>
					<input style="width:280px;" name="load_port_add" id="load_port_add" class="easyui-textbox" disabled=""/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">DISCHARGE</span>
					<input style="width:280px;" name="discharge_add" id="discharge_add" class="easyui-textbox" disabled=""/>

				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">DESTINATION</span>
					<input style="width:280px;" name="destination_add" id="destination_add" class="easyui-textbox" disabled=""/>
				</div>
			  </div>
			  <div style="position:absolute; margin-left:410px; width:400px; height: 140px;">
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">VESSEL</span>
					<input style="width:280px;" name="vessel_add" id="vessel_add" class="easyui-textbox" disabled=""/>
			  	</div>
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">ETA</span>
					<input style="width:85px;" name="eta_date_add" id="eta_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
					<span style="width:40px;display:inline-block;"></span>
					<span style="width:57px;display:inline-block;">ETD</span>
					<input style="width:85px;" name="etd_date_add" id="etd_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
			  	</div>
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">NOTIFY<br/>(CONSIGNEE)<br/><a href="javascript:void(0)" onclick="sett_notify_add()">SET</a></span>
					<input style="width: 280px; height: 56px;" name="notify_add" id="notify_add"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,9999]'"/>
			  	</div>
			  </div>
			  <div style="margin-left: 810px; width:450px; height: 140px;">
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">REMARKS<br/><a href="javascript:void(0)" onclick="sett_rmk_add()">SET</a></span>
					<input style="width: 330px; height: 56px;" name="remark_add" id="remark_add"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,299]'"/>
				</div>
				<div class="fitem">
					<div style="width:195px; float:left;">
						<span style="width:100px;display:inline-block;">Transport</span>
						<input style="width:85px;" name="trans_add" id="trans_add" class="easyui-combobox" data-options=" url:'json/json_transport.json', method:'get', valueField:'id', textField:'transport', panelHeight:'100px',
			        	onSelect: function(rec){
			        		if (rec.id == 2){
			        			document.getElementById('cont').hidden = false;
			        		}else{
			        			document.getElementById('cont').hidden = true;
			        		}
			        	}" required="" />
			        </div>
		        	<div style="margin-left: 195px;" id="cont" hidden="true">
		        		<span style="display:inline-block;"><input type="radio" name="status_trans_add" id="check_LCL" value="check_all"/>LCL</span>
						<span style="display:inline-block;"><input type="radio" name="status_trans_add" id="check_FCL" value="check_PM"/>FCL</span>
						<select style="width: 70px;" name="feet_add" id="feet_add" class="easyui-combobox" data-options="panelHeight:'70px'" required="">
							<option selected="" value=""></option>
							<option value="20">20 FEET</option>
							<option value="40">40 FEET</option>
						</select>
						<span style="display:inline-block;">X</span>
						<input style="width: 57px;" name="containers_add" id="containers_add"  class="easyui-textbox" required=""/>	
					</div>
				</div>
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">FORWARDER</span>
					<input style="width: 140px; " name="forwarder_add" id="forwarder_add" class="easyui-combobox" data-options=" url:'json/json_forwarder.php', method:'get', valueField:'FORWARDER_CODE', textField:'FORWARDER', panelHeight:'100px'"/>
					<span style="display:inline-block;">TRUCK</span>
					<input style="width: 140px; " name="truck_add" id="truck_add" class="easyui-combobox" data-options=" url:'json/json_forwarder.php', method:'get', valueField:'FORWARDER_CODE', textField:'FORWARDER', panelHeight:'100px'"/>
			  	</div>
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">BOOKING NO.</span>
					<input style="width: 140px; " name="book_no_add" id="book_no_add" class="easyui-combobox" data-options=" url:'json/json_forwarder.php', method:'get', valueField:'FORWARDER_CODE', textField:'FORWARDER', panelHeight:'100px'"/>
			  	</div>
			  </div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:200px;border-radius: 10px;margin:5px;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
			<div id="toolbar_add" style="padding: 5px 5px;">
				<a href="javascript:void(0)" id="add_po_add" iconCls='icon-add' class="easyui-linkbutton" onclick="()">ADD ITEM</a>
				<a href="javascript:void(0)" id="remove_po_add" iconCls='icon-cancel' class="easyui-linkbutton" onclick="()">REMOVE ITEM</a>
			</div>

			<!-- REMARK SETT -->
			<div id="dlg_remark_add" class="easyui-dialog" style="width: 450px;height: 250px;" closed="true" data-options="modal:true">
				<table id="dg_remark_add" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true"></table>
			</div>
			<!-- END -->

			<!-- NOTIFY SETT -->
			<div id="dlg_Notify" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons-Notify" data-options="modal:true">
				<table id="dg_Notify" class="easyui-datagrid" toolbar="#toolbar_Notify" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
			</div>
			<div id="toolbar_Notify" style="padding: 5px 5px;">
				<span style="width:80px;display:inline-block;">Search</span>
				<input style="width:300px;height: 20px;border-radius: 4px;" name="search_txt" id="search_txt" placeholder="code, names, address, country" onkeypress="sch_notify(event)"/>
			</div>
			<!-- END -->
		</form>
	</div>
	<!-- ADD FINISH -->

	<!-- EDIT START -->
	<div id='wnd_edit' class="easyui-window" style="width:100%;height:100%;padding:5px 5px;" closed="true" data-options="modal:true">
		
	</div>
	<!-- EDIT FINISH -->
	
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

		function sett_notify_add(){
			$('#dlg_Notify').dialog('open').dialog('setTitle','Search Notify');
			$('#dg_Notify').datagrid('load',{sch: ''});
			$('#dg_Notify').datagrid({
				url: '_getNotify.php',
				fitColumns: true,
				columns:[[
					{field:'COMPANY_CODE',title:'CODE',width:65,halign:'center', align:'center'},
	                {field:'COMPANY',title:'NAME',width:100,halign:'center'},
	                {field:'COMPANY_TYPE',title:'TYPE',width:35,halign:'center', align:'center'},
	                {field:'ADDRESS',title:'ADDRESS',width:250,halign:'center'},
	                {field:'COUNTRY',title:'COUNTRY',width:100,halign:'center'},
	                {field:'NOTIFY', hidden:true}
	            ]],
	            onDblClickRow:function(id,row){
	            	var rows = $('#dg_Notify').datagrid('getSelected');
	            	$('#notify_add').textbox('setValue', rows.NOTIFY);
	            	$('#dlg_Notify').dialog('close');
				}
			});
		}

		function sch_notify(event){
			var sch_a = document.getElementById('search_txt').value;
			var search = sch_a.toUpperCase();
			document.getElementById('search_txt').value = search;
			
		    if(event.keyCode == 13 || event.which == 13){
				$('#dg_Notify').datagrid('load',{sch: search});
				$('#dg_Notify').datagrid({url: '_getNotify.php',});
				document.getElementById('search_txt').value = '';
		    }
		}

		function sett_rmk_add(){
			$('#dlg_remark_add').dialog('open').dialog('setTitle','Master Remark');
			$('#dg_remark_add').datagrid({
				url: '_getRemark_add.php?type=DO',
				fitColumns: true,
				columns:[[
	                {field:'REMARK_TYPE',title:'TYPE',width:65,halign:'center', align:'center'},
	                {field:'REMARK_DESCRIPTION',title:'DESCRIPTION',width:300,halign:'center'}
	            ]],
	            onClickRow:function(id,row){
	            	var ids = [];
					var rows = $('#dg_remark_add').datagrid('getSelections');
					for(var i=0; i<rows.length; i++){
						ids.push(rows[i].REMARK_DESCRIPTION+"\n");
					}
	            	$('#remark_add').textbox('setValue',ids.join("\n"));
				}
			});
		}

		$('#sino_add').combogrid({
			panelHeight: '170px',
			panelWidth: '500',
            idField: 'si_no',
            textField: 'si_no',
			method: 'get',
			columns: [[
				{field:'SI_NO', title:'SI NO.', halign:'center', width:100},
				{field:'PO_NO', title:'CUST. PO NO.' , halign:'center', width:150},
				{field:'FINAL_DEST', title:'DESTINATION', halign:'center', width:200}
			]],
            fitColumns: true,
			label: 'Select Item:',
			labelPosition: 'top',
		    onClickRow: function(rec){
		    	var g = $('#sino_add').combogrid('grid');
				var r = g.datagrid('getSelected');

		    	if (r) {
			    	$('#sino_add').textbox('setValue', r.SI_NO);
			    	$('#load_port_add').textbox('setValue', r.LOAD_PORT);
			    	$('#discharge_add').textbox('setValue', r.DISCH_PORT);
			    	$('#destination_add').textbox('setValue', r.FINAL_DEST);
			    	$('#vessel_add').textbox('setValue', r.VESSEL);
			    	$('#etd_date_add').datebox('setValue', r.ETD_F);
			    	$('#eta_date_add').datebox('setValue', r.ETA_F);
			    	$('#notify_add').textbox('setValue', r.CONSIGNEE_FULL);
		    	};
		    }
		});

		var url_pdf='';

		$(function(){
			$('#date_awal').datebox('disable');
			$('#date_akhir').datebox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_awal').datebox('disable');
					$('#date_akhir').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_awal').datebox('enable');
					$('#date_akhir').datebox('enable');
				};
			})			

			$('#cmb_do_no').combobox('disable');
			$('#ck_do').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_do_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_do_no').combobox('enable');
				};
			})

			$('#customer').combobox('disable');
			$('#ck_customer').change(function(){
				if ($(this).is(':checked')) {
					$('#customer').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#customer').combobox('enable');
				};
			})

			$('#cmb_item_no').combobox('disable');
			$('#ck_item_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_item_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_item_no').combobox('enable');
				};
			})

			$('#dg').datagrid({
				url:'invoice_get.php',
		    	singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
			    columns:[[
				    {field:'DO_NO',title:'INVOICE<br/>NO.',width:75, halign: 'center', sortable:true},
				    {field:'DO_DATE',title:'INVOICE<br/>DATE',width:65, halign: 'center', align: 'center', sortable:true},
				    {field:'CUSTOMER_NAME',title:'CUSTOMER',width:200, halign: 'center', sortable:true},
				    {field:'EX_RATE',title:'EXCHANGE<br/>RATE',width:80, halign: 'center', align:'right', sortable:true},
				    {field:'CURR_MARK',title:'CURRENCY',width:60, halign: 'center', align: 'center',sortable:true},
				    {field:'AMT_O',title:'AMOUNT (ORG)',width:80, halign: 'center', align:'right', sortable:true},
				    {field:'AMT_L',title:'AMOUNT (LOC)',width:80, halign: 'center', align:'right', sortable:true},
				    {field:'REMARK',title:'REMARK', width:200, halign: 'center'},
				    {field:'CUSTOMER_CODE', hidden: true}
			    ]],
			    view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
				},
				onExpandRow: function(index,row){
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
					listbrg.datagrid({
	                	title: 'Invoice Detail (No: '+row.DO_NO+')',
	                	url:'invoice_get_detail.php?do='+row.DO_NO,
						toolbar: '#ddv'+index,
						singleSelect:true,
						loadMsg:'load data ...',
						height:'auto',
						fitColumns: true,
						columns:[[
							{field:'LINE_NO',title:'LINE NO.',width:50,halign:'center', align:'center'},
			                {field:'ITEM_NO',title:'ITEM NO.', halign:'center', align:'center', width:60, sortable: true},
			                {field:'DESCRIPTION', title:'DESCRIPTION', halign:'center', width:200},
			                {field:'UOM_Q', hidden: true},
			                {field:'UNIT_PL', title:'UoM', halign:'center', align:'center', width:40},
			                {field:'QTY', title:'INVOICE<br/>QTY', halign:'center', align:'right', width:50},
			                {field:'U_PRICE', title:'PRICE', halign:'center', align:'right', width:50},
			                {field:'AMT_O', title:'AMOUNT(ORG)', halign:'center', align:'right', width:70},
			                {field:'AMT_L', title:'AMOUNT(LOC)', halign:'center', align:'right', width:70}
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

				if (src=='') {
					filterData();
				};
		    }
		}

		function filterData(){
			var ck_date='false';
			var ck_customer='false';
			var ck_item_no='false';
			var ck_do='false';
			var flag = 0;

			if($('#ck_date').attr("checked")){
				ck_date='true';
				flag += 1;
			}

			if($('#ck_customer').attr("checked")){
				ck_customer='true';
				flag += 1;
			}
			if($('#ck_item_no').attr("checked")){
				ck_item_no='true';
				flag += 1;
			}
			if($('#ck_do').attr("checked")){
				ck_do='true';
				flag += 1;
			}

			if(flag == 4){
				$.messager.alert("INFORMATION","No filter data, system only show 150 records","info");
			}
			
			$('#dg').datagrid('load', {
				date_awal: $('#date_awal').datebox('getValue'),
				date_akhir: $('#date_akhir').datebox('getValue'),
				ck_date: ck_date,
				customer: $('#customer').combobox('getValue'),
				ck_customer: ck_customer,
				cmb_item_no: $('#cmb_item_no').combobox('getValue'),
				ck_item_no: ck_item_no,
				cmb_do: $('#cmb_do_no').combobox('getValue'),
				ck_do: ck_do,
				src: ''
			});

		   	url_pdf ="?date_awal="+$('#date_awal').datebox('getValue')+"&date_akhir="+$('#date_akhir').datebox('getValue')+"&ck_date="+ck_date+"&customer="+$('#customer').combobox('getValue')+"&customer_nm="+$('#customer').combobox('getText')+"&ck_customer="+ck_customer+"&cmb_item_no="+$('#cmb_item_no').combobox('getValue')+"&ck_item_no="+ck_item_no+"&cmb_do="+$('#cmb_do_no').combobox('getValue')+"&ck_do="+ck_do+"&txt_item_name="+$('#cmb_item_no').combobox('getText');

		   	$('#dg').datagrid('enableFilter');
		}

		function addInv(){
			$('#wnd_add').window('open').window('setTitle','ADD INVOICE');
		}

		function editInv(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				$('#wnd_edit').window('open').window('setTitle','EDIT INVOICE (No. : '+row.DO_NO+')');
			}
		}

	</script>
	</body>
    </html>
<?php }else{	
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}