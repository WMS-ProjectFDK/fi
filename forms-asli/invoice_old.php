<?php 
session_start();
include("../connect/conn.php");
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
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
	<?php include ('../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name));?>

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
				<div class="fitem">
					<span style="width:80px;display:inline-block;">PO No.</span>
					<select style="width:300px;" name="cmb_po_no" id="cmb_po_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'po_no',textField:'po_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_po_no" id="ck_po_no" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; height:100px; margin-left:965px;"><legend>Invoice Report</legend></fieldset>
		<div style="clear:both;"></div>
		<div style="margin-top: 5px;margin: 5px;">
			<span style="margin-left:10px;width:50px;display:inline-block;">search</span> 
			<input style="width:150px; height: 20px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" placeholder="invoice no." name="src" id="src" type="text" />
			<a href="javascript:void(0)" id="filbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:108px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			<a href="javascript:void(0)" style="width: 108px;" class="easyui-linkbutton c2" id="add" onclick="addInv()"><i class="fa fa-plus" aria-hidden="true"></i> Add Invoice</a>
			<a href="javascript:void(0)" style="width: 108px;" class="easyui-linkbutton c2" id="edit" onclick="editInv()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Invoice</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="delete" onclick="deleteInv()"><i class="fa fa-trash" aria-hidden="true"></i> Delete Invoice</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="print" onclick="printInv()"><i class="fa fa-print" aria-hidden="true"></i> Print Invoice</a>
		</div>
	</div>
	<table id="dg" title="PACKING LIST & INVOICE" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;"></table>

	<!-- ADD START -->
	<div id='win_add' class="easyui-window" style="width:auto;height:565px;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
		<form id="f_add" method="post" novalidate>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left; margin:5px;">
				<legend> Select Customer &
					Contract(<span id="Contract" style="display:inline-block;"></span>)
					<div style="display: inline-block;" id="sett_contract" hidden="true"><a href="javascript:void(0)" onclick="setting_contract('add')">Sett Contract</a></div>
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
								//$('#attn_add').textbox('setValue',data[0].ATTN);
								$('#curr_add').combobox('setValue',data[0].CURR_CODE);
								$('#rate_add').textbox('setValue',1);
								$('#contract_no_add').textbox('setValue',data[0].CONTRACT_SEQ);
								ct = data[0].JUM_CONTRACT;
								document.getElementById('Contract').innerHTML = ct;
								$('#payment_add').textbox('setValue',data[0].PMETHOD);
								$('#pday_add').textbox('setValue',data[0].PDAYS+'-'+data[0].PDESC);
								$('#tterm_add').textbox('setValue',data[0].TTERM);
								$('#term_add').textbox('setValue', data[0].TERM);

								if(ct > 1){
									$('#sett_contract').show();
									$('#dg_contract').datagrid('load',{id: rec.company_code});
									setting_contract('add');
								}

								$('#sino_add').combogrid({
									url: 'invoice_get_si_no.php?id='+rec.company_code+'&term='+data[0].TERM
								});

								$('#curr_add').combobox('enable');
								$('#do_no_add').textbox('enable');
								$('#do_date_add').combobox('enable');
								$('#GST_add').textbox('enable');
								$('#sino_add').combogrid('enable');
								$('#tterm_add').textbox('enable');

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
		        	}" required="" disabled="" />
		        	<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Ex. Rate</span>
					<input style="width:140px;" name="rate_add" id="rate_add" class="easyui-textbox" disabled="" />
				</div>
				<div class="fitem"></div>
				<div class="fitem">
					<span style="width:70px;display:inline-block;">Invoice No.</span>
					<input style="width:120px;" name="do_no_add" id="do_no_add" class="easyui-textbox" required="" disabled=""/>
					<span style="width:5px;display:inline-block;"></span>
					<span style="width:85px;display:inline-block;">Invoice Date</span>
					<input style="width:85px;" name="do_date_add" id="do_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>" disabled=""/>
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Payment</span>
					<input style="width:140px;" name="payment_add" id="payment_add" class="easyui-textbox" disabled=""/>
					<input style="width:251px;" name="pday_add" id="pday_add" class="easyui-textbox" disabled=""/>
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">GST Rate</span>
					<input style="width:85px;" name="GST_add" id="GST_add" class="easyui-textbox" value="0" disabled=""/>%
					<span style="width:2px;display:inline-block;"></span>
					<span style="display:inline-block;">T. Terms</span>
					<input style="width:140px;" name="tterm_add" id="tterm_add" class="easyui-textbox" disabled=""/>
				</div>
			</fieldset>
			<div hidden="true"><!--  hidden="true" -->
				<input style="width:140px;" name="term_add" id="term_add" class="easyui-textbox" disabled=""/>
				<input style="width:140px;" name="contract_no_add" id="contract_no_add" class="easyui-textbox" disabled=""/>
				<input style="width:140px;" name="transport1_add" id="transport1_add" class="easyui-textbox" disabled=""/>
				<input style="width:140px;" name="transport2_add" id="transport2_add" class="easyui-textbox" disabled=""/>
				<input style="width:170px;" name="goods_names_add" id="goods_names_add" class="easyui-textbox" disabled=""/>
			</div>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left; margin:5px;"><legend>SI Sett</legend>
			  <div style="width:400px; height: 140px; float:left;">	
				<div class="fitem">	
					<span style="width:100px;display:inline-block;">SI NO.</span>
					<select style="width:280px;" name="sino_add" id="sino_add" class="easyui-combogrid" style="width:142px;" disabled=""></select>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">LOADING PORT</span>
					<input style="width:250px;" name="load_port_add" id="load_port_add" class="easyui-textbox" disabled=""/>
					<a href="javascript:void(0)" onclick="sett_port_load('add')">SET</a>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">DISCHARGE</span>
					<input style="width:250px;" name="discharge_add" id="discharge_add" class="easyui-textbox" disabled=""/>
					<a href="javascript:void(0)" onclick="sett_port_disch('add')">SET</a>

				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">DESTINATION</span>
					<input style="width:250px;" name="destination_add" id="destination_add" class="easyui-textbox" disabled=""/>
					<a href="javascript:void(0)" onclick="sett_port_dest('add')">SET</a>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">PPBE No.</span>
					<input style="width:120px;" name="ppbe_no" id="ppbe_no" class="easyui-combobox" 
					data-options=" url:'json/json_ppbe_no.php?user=<? echo $user_name; ?>',method:'get',valueField:'ppbe_no',textField:'ppbe_no', panelHeight:'100px',
					onSelect: function(rec){
		        		var idsPpbe = [];
		        		var rmk0 = $('#remark_add').textbox('getValue');
						idsPpbe.push('PPBE : '+rec.ppbe_no);
						var rmk_new0 = rmk0+'\n'+idsPpbe;
						$('#remark_add').textbox('setValue',rmk_new0);
		        	}" disabled="" />
				</div>
			  </div>
			  <div style="position:absolute; margin-left:410px; width:400px; height: 130px;">
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">VESSEL</span>
					<input style="width:280px; height: 35px;" name="vessel_add" id="vessel_add" class="easyui-textbox" multiline="true" disabled="" />
			  	</div>
			  	<div class="fitem">
					<span style="width:100px;display:inline-block;">ETD</span>
					<input style="width:85px;" name="etd_date_add" id="etd_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" disabled=""/>
					<span style="width:40px;display:inline-block;"></span>
					<span style="width:57px;display:inline-block;">ETA</span>
					<input style="width:85px;" name="eta_date_add" id="eta_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" disabled=""/>
					
			  	</div>
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">NOTIFY<br/>(CONSIGNEE)<br/><a href="javascript:void(0)" onclick="sett_notify('add')">SET</a></span>
					<input style="width: 280px; height: 50px;" name="notify_add" id="notify_add"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,9999]'" disabled=""/>
			  	</div>
			  </div>
			  <div style="margin-left: 810px; width:450px; height: 130px;">
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">REMARKS<br/><!-- <a href="javascript:void(0)" onclick="sett_rmk('add')" disable="true" >SET</a> --></span>
					<input style="width: 330px; height: 56px;" name="remark_add" id="remark_add"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,299]'" disabled=""/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Transport</span>
					<input style="width:85px;" name="trans_add" id="trans_add" class="easyui-combobox" data-options=" url:'json/json_transport.json', method:'get', valueField:'id', textField:'transport', panelHeight:'100px',
		        	onSelect: function(rec){
		        		if (rec.id == 2){
		        			$('#dlg_transport').dialog('open').dialog('setTitle','Setting Transport');
		        			$('#dg_transport').datagrid('loadData',[]);
		        			$('#dg_transport').datagrid('beginEdit');
		        			$('#dg_transport').datagrid({
								rownumbers: true,
								fitColumns: true,
								columns:[[
									{field:'METHOD', title:'METHOD TYPE', width:80, halign:'center', editor:{type:'combobox',options: {url: 'json/json_method_exim.php',
					    																												panelHeight: '50px',
																										                    			valueField: 'METHOD_TYPE',
																										                    			textField: 'DESCRIPTION'
					    																											}
					    																					}
					    			},
					                {field:'SIZE', title:'SIZE TYPE', width:80, halign:'center', editor:{type:'combobox',options: {url: 'json/json_cargo_size.php',
					    																												panelHeight: '50px',
																										                    			valueField: 'SIZE_TYPE',
																										                    			textField: 'DESCRIPTION'
					    																											}
					    																					}
					    			},
					                {field:'QTY', title:'QTY', width:70, halign:'center', align:'right', editor:{type:'numberbox',
																									   			 options:{precision:0,groupSeparator:',',disable:true}
																					   				  			}
									}
					            ]],
					            onClickRow:function(row){
							    	$(this).datagrid('beginEdit', row);
							    }
					        });

		        			$('#dg_transport').datagrid('insertRow',{
								index: 0,
								row: {
									CUSTOMER_PART_NO: '',
									DESCRIPTION: '',
									SO_NO: ''
								}
							});

							$('#dg_transport').datagrid('insertRow',{
								index: 1,
								row: {
									CUSTOMER_PART_NO: '',
									DESCRIPTION: '',
									SO_NO: ''
								}
							});


		        		}else{
		        			var ids_trans = [];
							var rmk_trans = $('#remark_add').textbox('getValue');
							ids_trans.push(rec.transport+' SHIPMENT');
							var rmk_new_trans = rmk_trans+'\n'+ids_trans;
							$('#remark_add').textbox('setValue',rmk_new_trans);

		        			$('#dlg_transport').dialog('close');
		        			$('#transport1_add').textbox('setValue','');
		        			$('#transport2_add').textbox('setValue','');
		        		}
		        	}" required="" disabled=""/>
		        	<span style="width:6px;display:inline-block;"></span>
			        <span style="display:inline-block;">BOOKING NO.</span>
					<input style="width: 140px; " name="book_no_add" id="book_no_add" class="easyui-textbox" required="" disabled=""/>
				</div>
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">FORWARDER</span>
					<input style="width: 140px; " name="forwarder_add" id="forwarder_add" class="easyui-combobox" data-options=" url:'json/json_forwarder.php', method:'get', valueField:'FORWARDER_CODE', textField:'FORWARDER', panelHeight:'100px'" disabled=""/>
					<span style="width: 3px;display:inline-block;"></span>
					<span style="display:inline-block;">EMKL</span>
					<input style="width: 140px; " name="truck_add" id="truck_add" class="easyui-combobox" data-options=" url:'json/json_forwarder.php', method:'get', valueField:'FORWARDER_CODE', textField:'FORWARDER', panelHeight:'100px',
					onSelect: function(rec){
						var idsEMKL = [];
						var rmk_emkl = $('#remark_add').textbox('getValue');
						idsEMKL.push('EMKL : '+rec.FORWARDER);
						var rmk_new_emkl = rmk_emkl+'\n'+idsEMKL;
						$('#remark_add').textbox('setValue',rmk_new_emkl);
					}
					"
					disabled=""/>
			  	</div>
			  </div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:190px;border-radius: 10px;margin:5px;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
			<div id="toolbar_add" style="padding: 5px 5px;">
				<a href="javascript:void(0)" id="add_so_part" iconCls='icon-add' class="easyui-linkbutton" onclick="sett_part_SO('add')" disabled="true">ADD ITEM</a>
				<a href="javascript:void(0)" id="remove_so_part" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_part_SO('add')" disabled="true">REMOVE ITEM</a>
			</div>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
                <a class="easyui-linkbutton c2" id="savebtn" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveDO('add')" style="width:140px" disabled="true"> SAVE </a>
                <!-- <a class="easyui-linkbutton c2" id="packbtn" href="javascript:void(0)" onclick="openPacking()" style="width:140px" disabled="true"><i class="fa fa-cubes" aria-hidden="true"></i> PACKING </a> -->
                <a class="easyui-linkbutton c2" id="clsbtn" href="javascript:void(0)" onclick="javascript:$('#win_add').window('close')" style="width:140px"><i class="fa fa-ban" aria-hidden="true"></i> Cancel </a>
            </div>
		</form>
	</div>
	<!-- ADD FINISH -->

	<!-- EDIT START -->
	<div id='win_edit' class="easyui-window" style="width:auto;height:565px;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
		<form id="f_edit" method="post" novalidate>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left; margin:5px;">
				<legend> Select Customer &
					Contract(<span id="Contract" style="display:inline-block;"></span>)
					<div style="display: inline-block;" id="sett_contract_edit" hidden="true"><a href="javascript:void(0)" onclick="setting_contract('edit')">Sett Contract</a></div>
				</legend>
				<div class="fitem">
					<span style="width:70px;display:inline-block;">Customer</span>
					<select style="width:310px;" name="customer_edit" id="customer_edit" class="easyui-combobox" data-options=" url:'json/json_customer.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
					onSelect: function(rec){
		        		$.ajax({
		        			type: 'GET',
							url: 'json/json_info_cust.php?id='+rec.company_code,
							data: { kode:'kode' },
							success: function(data){
								$('#country_edit').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].COUNTRY);
								$('#attn_edit').textbox('setValue',data[0].ATTN);
								$('#curr_edit').combobox('setValue',data[0].CURR_CODE);
								$('#rate_edit').textbox('setValue',1);
								$('#contract_no_edit').textbox('setValue',data[0].CONTRACT_SEQ);
								ct = data[0].JUM_CONTRACT;
								document.getElementById('Contract').innerHTML = ct;
								$('#payment_add').textbox('setValue',data[0].PMETHOD);
								$('#pday_edit').textbox('setValue',data[0].PDAYS+'-'+data[0].PDESC);
								$('#tterm_edit').textbox('setValue',data[0].TTERM);
								$('#term_edit').textbox('setValue', data[0].TERM);

								if(ct > 1){
									$('#sett_contract_edit').show();
									$('#dg_contract').datagrid('load',{id: rec.company_code});
									setting_contract('edit');
								}

								$('#sino_edit').combogrid({
									url: 'invoice_get_si_no.php?id='+rec.company_code+'&term='+data[0].TERM
								});

								$('#curr_edit').combobox('enable');
								//$('#do_no_edit').textbox('enable');
								$('#do_date_edit').combobox('enable');
								$('#GST_edit').textbox('enable');
								//$('#sino_edit').combogrid('enable');
								$('#attn_edit').textbox('enable');

							}
		        		});
	        		}" required="" disabled=""></select>
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Country</span>
					<input style="width:140px;" name="country_edit" id="country_edit" class="easyui-textbox" disabled="" />
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">ATTN</span>
					<input style="width:200px;" name="attn_edit" id="attn_edit" class="easyui-textbox" disabled="" />
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Currency</span>
					<input style="width:85px;" id="curr_edit" class="easyui-combobox" data-options=" url:'json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'100px',
		        	onSelect: function(rec){
		        		$.ajax({
		        			type: 'GET',
							url: 'json/json_exrate.php?curr='+rec.idcrc,
							data: { kode:'kode' },
							success: function(data){
								$('#rate_edit').textbox('setValue',data[0].RATE);	
							}
		        		});
		        	}" required="" disabled=""/>
		        	<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Ex. Rate</span>
					<input style="width:140px;" name="rate_edit" id="rate_edit" class="easyui-textbox" disabled="" />
				</div>
				<div class="fitem"></div>
				<div class="fitem">
					<span style="width:70px;display:inline-block;">Invoice No.</span>
					<input style="width:120px;" name="do_no_edit" id="do_no_edit" class="easyui-textbox" required="" disabled=""/>
					<span style="width:5px;display:inline-block;"></span>
					<span style="width:85px;display:inline-block;">Invoice Date</span>
					<input style="width:85px;" name="do_date_edit" id="do_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">Payment</span>
					<input style="width:140px;" name="payment_edit" id="payment_edit" class="easyui-textbox" disabled=""/>
					<input style="width:251px;" name="pday_edit" id="pday_edit" class="easyui-textbox" disabled=""/>
					<span style="width:15px;display:inline-block;"></span>
					<span style="display:inline-block;">GST Rate</span>
					<input style="width:85px;" name="GST_edit" id="GST_edit" class="easyui-textbox" value="0" disabled=""/>%
					<span style="width:2px;display:inline-block;"></span>
					<span style="display:inline-block;">T. Terms</span>
					<input style="width:140px;" name="tterm_edit" id="tterm_edit" class="easyui-textbox"/>
				</div>
			</fieldset>
			<div hidden="true"><!-- hidden="true" -->
				<input style="width:140px;" name="term_edit" id="term_edit" class="easyui-textbox" disabled=""/>
				<input style="width:140px;" name="contract_no_edit" id="contract_no_edit" class="easyui-textbox" disabled=""/>
				<input style="width:140px;" name="transport1_edit" id="transport1_edit" class="easyui-textbox" disabled=""/>
				<input style="width:140px;" name="transport2_edit" id="transport2_edit" class="easyui-textbox" disabled=""/>
				<input style="width:170px;" name="goods_names_edit" id="goods_names_edit" class="easyui-textbox" disabled=""/>
			</div>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left; margin:5px;"><legend>SI Sett</legend>
			  <div style="width:400px; height: 140px; float:left;">	
				<div class="fitem">
					<span style="width:100px;display:inline-block;">SI NO.</span>
					<select style="width:280px;" name="sino_edit" id="sino_edit" class="easyui-combogrid" style="width:142px;"></select>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">LOADING PORT</span>
					<input style="width:250px;" name="load_port_edit" id="load_port_edit" class="easyui-textbox"/>
					<a href="javascript:void(0)" onclick="sett_port_load('edit')">SET</a>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">DISCHARGE</span>
					<input style="width:250px;" name="discharge_edit" id="discharge_edit" class="easyui-textbox"/>
					<a href="javascript:void(0)" onclick="sett_port_disch('edit')">SET</a>

				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">DESTINATION</span>
					<input style="width:250px;" name="destination_edit" id="destination_edit" class="easyui-textbox"/>
					<a href="javascript:void(0)" onclick="sett_port_dest('edit')">SET</a>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">PPBE No.</span>
					<select style="width:120px;" name="ppbe_no_edit" id="ppbe_no_edit" class="easyui-combobox" 
					data-options=" url:'json/json_ppbe_no.php?user=<? echo $user_name; ?>',method:'get',valueField:'ppbe_no',textField:'ppbe_no', panelHeight:'100px'"></select>
				</div>
			  </div>
			  <div style="position:absolute; margin-left:410px; width:400px; height: 130px;">
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">VESSEL</span>
					<input style="width:280px;height: 35px;" name="vessel_edit" id="vessel_edit" class="easyui-textbox" multiline="true"/>
			  	</div>
			  	<div class="fitem">
					<span style="width:100px;display:inline-block;">ETD</span>
					<input style="width:85px;" name="etd_date_edit" id="etd_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
					<span style="width:40px;display:inline-block;"></span>
					<span style="width:57px;display:inline-block;">ETA</span>
					<input style="width:85px;" name="eta_date_edit" id="eta_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
					
			  	</div>
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">NOTIFY<br/>(CONSIGNEE)<br/><a href="javascript:void(0)" onclick="sett_notify('add')">SET</a></span>
					<input style="width: 280px; height: 50px;" name="notify_edit" id="notify_edit"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,9999]'"/>
			  	</div>
			  </div>
			  <div style="margin-left: 810px; width:450px; height: 130px;">
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">REMARKS<br/><!-- <a href="javascript:void(0)" onclick="sett_rmk('add')" disable="true" >SET</a> --></span>
					<input style="width: 330px; height: 56px;" name="remark_edit" id="remark_edit"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,299]'"/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Transport</span>
					<input style="width:85px;" name="trans_edit" id="trans_edit" class="easyui-combobox" data-options=" url:'json/json_transport.json', method:'get', valueField:'id', textField:'transport', panelHeight:'100px',
		        	onSelect: function(rec){
		        		if (rec.id == 2){
		        			$('#dlg_transport').dialog('open').dialog('setTitle','Setting Transport');
		        			$('#dg_transport').datagrid('loadData',[]);
		        			$('#dg_transport').datagrid({
								rownumbers: true,
								fitColumns: true,
								columns:[[
									{field:'METHOD', title:'METHOD TYPE', width:80, halign:'center', editor:{type:'combobox',options: {url: 'json/json_method_exim.php',
					    																												panelHeight: '50px',
																										                    			valueField: 'METHOD_TYPE',
																										                    			textField: 'DESCRIPTION'
					    																											}
					    																					}
					    			},
					                {field:'SIZE', title:'SIZE TYPE', width:80, halign:'center', editor:{type:'combobox',options: {url: 'json/json_cargo_size.php',
					    																												panelHeight: '50px',
																										                    			valueField: 'SIZE_TYPE',
																										                    			textField: 'DESCRIPTION'
					    																											}
					    																					}
					    			},
					                {field:'QTY', title:'QTY', width:70, halign:'center', align:'right', editor:{type:'numberbox',
																									   			 options:{precision:0,groupSeparator:',',disable:true}
																					   				  			}
									}
					            ]],
					            onClickRow:function(row){
							    	$(this).datagrid('beginEdit', row);
							    }
					        });

		        			$('#dg_transport').datagrid('insertRow',{
								index: 0,
								row: {
									CUSTOMER_PART_NO: '',
									DESCRIPTION: '',
									SO_NO: ''
								}
							});

							$('#dg_transport').datagrid('insertRow',{
								index: 1,
								row: {
									CUSTOMER_PART_NO: '',
									DESCRIPTION: '',
									SO_NO: ''
								}
							});


		        		}else{
		        			$('#dlg_transport').dialog('close');
		        			$('#transport1_edit').textbox('setValue','');
		        			$('#transport2_edit').textbox('setValue','');
		        		}
		        	}" required=""/>
		        	<span style="width:6px;display:inline-block;"></span>
			        <span style="display:inline-block;">BOOKING NO.</span>
					<input style="width: 140px; " name="book_no_edit" id="book_no_edit" class="easyui-textbox" required=""/>
				</div>
			  	<div class="fitem">
			  		<span style="width:100px;display:inline-block;">FORWARDER</span>
					<input style="width: 140px; " name="forwarder_edit" id="forwarder_edit" class="easyui-combobox" data-options=" url:'json/json_forwarder.php', method:'get', valueField:'FORWARDER_CODE', textField:'FORWARDER', panelHeight:'100px'"/>
					<span style="width: 3px;display:inline-block;"></span>
					<span style="display:inline-block;">EMKL</span>
					<input style="width: 140px; " name="truck_edit" id="truck_edit" class="easyui-combobox" data-options=" url:'json/json_forwarder.php', method:'get', valueField:'FORWARDER_CODE', textField:'FORWARDER', panelHeight:'100px'"/>
			  	</div>
			  </div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:100%;height:190px;border-radius: 10px;margin:5px;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
			<div id="toolbar_edit" style="padding: 5px 5px;">
				<a href="javascript:void(0)" id="add_so_part_edit" iconCls='icon-add' class="easyui-linkbutton" onclick="sett_part_SO('edit')">ADD ITEM</a>
				<a href="javascript:void(0)" id="remove_so_part_edit" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_part_SO('edit')">REMOVE ITEM</a>
			</div>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
                <a class="easyui-linkbutton c2" id="savebtn" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveDO('edit')" style="width:140px" > SAVE </a>
                <!-- <a class="easyui-linkbutton c2" id="packbtn" href="javascript:void(0)" onclick="openPacking()" style="width:140px" disabled="true"><i class="fa fa-cubes" aria-hidden="true"></i> PACKING </a> -->
                <a class="easyui-linkbutton c2" id="clsbtn" href="javascript:void(0)" onclick="javascript:$('#win_edit').window('close')" style="width:140px"><i class="fa fa-ban" aria-hidden="true"></i> Cancel </a>
            </div>
		</form>
	</div>
	<!-- EDIT FINISH -->

	<!-- START SETT -->
	<div>
		<!-- PRINT SETT -->
		<div id="dlg_print" class="easyui-dialog" style="width: 330px;height: 350px;" closed="true" data-options="modal:true">
			<table id="dg_print" class="easyui-datagrid" style="width:100%;height:auto;border-radius: 10px;"></table>
			<div class="fitem" id="print_type">
				<!-- <input type="radio" name="status_stock" id="check_all" value="check_all"/> -->
				<INPUT TYPE='radio' NAME='sheet_type' id='invoice' VALUE='invoice' checked/> INVOICE<br/>
				<INPUT TYPE='radio' NAME='sheet_type' id='packing_list' VALUE='packing_list'/> PACKING LIST<br/>
				<INPUT TYPE='radio' NAME='sheet_type' id='invoice_packing' VALUE='invoice_packing'/> INVOICE & PACKING LIST<br/>
				<INPUT TYPE='radio' NAME='sheet_type' id='final_si' VALUE='final_si'/> FINAL SHIPPING INSTRUCTIONS<br/>
				<!-- <INPUT TYPE='radio' NAME='sheet_type' id='forwarder_letter' VALUE='forwarder_letter'/> FORWARDER LETTER<br/> -->
				<INPUT TYPE='radio' NAME='sheet_type' id='debit_note' VALUE='debit_note'/> DEBIT NOTE<br/>
				<INPUT TYPE='radio' NAME='sheet_type' id='ppbe' VALUE='ppbe'/> PPBE<br/>
				<INPUT TYPE='radio' NAME='sheet_type' id='delivery_slip' VALUE='delivery_slip'/> DELIVERY SLIP<br/>
				<INPUT TYPE='radio' NAME='sheet_type' id='attach_pallet_mark' VALUE='attach_pallet_mark'/> ATTACHMENT PALLET MARK (PDF)<br/>
				<INPUT TYPE='radio' NAME='sheet_type' id='attach_pallet_mark_xls' VALUE='attach_pallet_mark_xls'/> ATTACHMENT PALLET MARK (EXCEL)<br/>
				<INPUT TYPE='radio' NAME='sheet_type' id='multi_final_si' VALUE='multi_final_si'/> MULTIPLE FINAL SHIPPING INSTRUCTIONS<br/>
			</div>
			<div class="fitem" align="center">
				<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="printInv" onclick="print()"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
			</div>
		</div>
		<!-- END -->

		<!-- EDIT JIKA SUDAH DI DELIVERY UPDATE -->
		<div id="dlg_edit_stsdel" class="easyui-dialog" style="width: 550px;height: auto; border:4px;" closed="true" data-options="modal:true">
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width: 500px; float:left; margin:5px;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">ETD</span>
					<input style="width:85px;" name="etd_date_edit_stsdel" id="etd_date_edit_stsdel" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">ETA</span>
					<input style="width:85px;" name="eta_date_edit_stsdel" id="eta_date_edit_stsdel" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">VESSEL</span>
					<input style="width:330px;height: 35px;" name="vessel_edit_stsdel" id="vessel_edit_stsdel" class="easyui-textbox" multiline="true"/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">REMARKS<br/></span>
					<input style="width: 330px; height: 56px;" name="remark_edit_stsdel" id="remark_edit_stsdel"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,299]'"/>
				</div>
				<div data-options="region:'south',border:false" style="text-align:right;padding:10px 0 0;">
					<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="save_edit_stsdel()" style="width:100px">
						<i class="fa fa-save" aria-hidden="true"></i> Save </a>
				</div>
			</fieldset>
		</div>
		<!-- END -->

		<!-- PRINT SETT PPBE -->
		<div id="dlg_print_ppbe" class="easyui-dialog" style="width: 400px;height: 200px;border:4px;" closed="true" data-options="modal:true">
			<div class="fitem">
				<span style="width:120px;display:inline-block;">Closing Cargo</span>
				<input style="width:85px;" name="closing_cargo" id="closing_cargo" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<select style="width:60px;" name="cmb_jam" id="cmb_jam" class="easyui-combobox" data-options="panelHeight:'150px'">
					<option selected="" value="">hour</option>
					<?php 
						for ($i=0; $i<24 ; $i++) {
							if($i < 10){
								echo "<option value=".$i.">0".$i."</option>";
							}else{
								echo "<option value=".$i.">$i</option>";	
							}
						}
					?>
				</select> : 
				<select style="width:60px;" name="cmb_menit" id="cmb_menit" class="easyui-combobox" data-options="panelHeight:'150px'">
					<option selected="" value="">minute</option>
					<?php 
						for ($i=0; $i<60 ; $i++){
							if($i < 10){
								echo "<option value=".$i.">0".$i."</option>";
							}else{
								echo "<option value=".$i.">$i</option>";	
							}
						}
					?>
				</select>
			</div>
			<div class="fitem">
				<span style="width:120px;display:inline-block;">EXPORT</span>
				<INPUT TYPE='radio' NAME='sheet_exp' id='lokal' VALUE='lokal' checked/> LOKAL <br>
				<span style="width:120px;display:inline-block;"></span>
				<INPUT TYPE='radio' NAME='sheet_exp' id='export' VALUE='export'/> EXPORT<br/>
			</div>
			<div class="fitem">
				<span style="width:120px;display:inline-block;">NOTE</span>
				<input style="width: 220px; height: 40px;" name="note_ppbe" id="note_ppbe"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,499]'"/>
			</div>
			<div class="fitem" align="center">
				<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="print_ppbe" onclick="print_ppbeNya()"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
			</div>
		</div>
		<!-- END -->

		<!-- CONTRACT SETT -->
		<div id="dlg_contract" class="easyui-dialog" style="width: 750px;height: 150px;" closed="true" data-options="modal:true">
			<table id="dg_contract" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true"></table>
		</div>
		<!-- END -->

		<!-- PORT SETT -->
		<div id="dlg_port" class="easyui-dialog" style="width: 350px;height: 550px;" closed="true" data-options="modal:true">
			<table id="dg_port" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true"></table>
		</div>
		<!-- END -->

		<!-- REMARK SETT -->
		<div id="dlg_remark" class="easyui-dialog" style="width: 450px;height: 250px;" closed="true" data-options="modal:true">
			<table id="dg_remark" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true"></table>
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

		<!-- SETT PART SO -->
		<div id="dlg_part_SO" class="easyui-dialog" style="width: 1200px;height: 400px;" closed="true" closable="false" data-options="modal:true">
			<table id="dg_part_SO" class="easyui-datagrid" style="width:100%;height:90%;border-radius: 10px;"></table>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="select_part()" style="width:140px">
					<i class="fa fa-ban" aria-hidden="true"></i> SELECT </a>
			</div>
		</div>
		<!-- END PART SO -->

		<!-- SETT TRANSPORT -->
		<div id="dlg_transport" class="easyui-dialog" style="width: 450px;height: 150px;" closed="true" data-options="modal:true">
			<table id="dg_transport" class="easyui-datagrid" style="width:100%;height:auto;border-radius: 10px;" rownumbers="true"></table>
			<div data-options="region:'south',border:false" style="text-align:right;padding:10px 0 0;">
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="save_sett_transport()" style="width:100px">
					<i class="fa fa-save" aria-hidden="true"></i> Save </a>
			</div>
		</div>
		<!-- END TRANSPORT -->

		<!-- SETT TRANSPORT -->
		<div id="dlg_shipping_mark" class="easyui-dialog" style="width: 450px;height: 300px;" closed="true" data-options="modal:true">
			<table id="dg_shipping_mark" class="easyui-datagrid" style="width:100%;height:auto;border-radius: 10px;" rownumbers="true"></table>
			<div data-options="region:'south',border:false" style="text-align:center;padding:10px 0 0;">
				<input style="width: 430px; height: 56px;" name="shipping_mark_result" id="shipping_mark_result"  multiline="true" class="easyui-textbox" />
				<div hidden="true"><input style="width: 200px;" name="kode_shipping_mark" id="kode_shipping_mark" class="easyui-textbox" disabled="true"/></div>
				<div style="clear:both;padding:10px 0 0;"></div>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="save_sett_ship_mark()" style="width:100px">
					<i class="fa fa-save" aria-hidden="true"></i> Save </a>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="javascript:$('#dlg_shipping_mark').dialog('close');" style="width:100px">
					<i class="fa fa-ban" aria-hidden="true"></i> Cancel </a>
			</div>
		</div>
		<!-- END TRANSPORT -->


		<!-- MULTI FINAL SI PRINT -->
		<div id="dlg_multi_final_si" class="easyui-dialog" style="width: 750px;height: 400px;" closed="true" data-options="modal:true">
			<table id="dg_multi_final_si" class="easyui-datagrid" style="width:99%;height:90%;border-radius: 10px;" rownumbers="true"></table>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="print_multi_si()" style="width:100px">
					<i class="fa fa-print" aria-hidden="true"></i> Print </a>
			<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="javascript:$('#dlg_multi_final_si').dialog('close');" style="width:100px">
					<i class="fa fa-ban" aria-hidden="true"></i> Cancel </a>
			</div>	
		
		</div>
		<!-- END -->

	</div>
	<!-- END SETT-->

	<script type="text/javascript">

		function multi_final_si_print(a){
			// alert('json/json_get_do_multi_si.php?do_no='+a);
			$('#dlg_multi_final_si').dialog('open').dialog('setTitle','Multiple Final SI print');
			$('#dg_multi_final_si').datagrid({
			url: 'json/json_get_do_multi_si.php?do_no='+a,
		    singleSelect: true,
			rownumbers: true,
			fitColumns: true,
		    columns:[[
			    {field:'DO_NO', title:'INVOICE NO.', width:120, halign: 'center', align: 'left'},
			    {field:'PPBE', title:'PPBE<br/>NO', width:100, halign: 'center'},
			    {field:'PALLET', title:'PALLET', width: 80, halign: 'center', align: 'right'},
			    {field:'QTY', title:'QUANTITY', width:80, halign: 'center', align: 'right'},
			    {field:'GW', title:'GROSS', width:80, halign: 'center', align: 'right'},
			    {field:'NW', title:'NET', width: 80, halign: 'center', align: 'right'},
			    {field:'MSM', title:'MEASUREMENT', width: 80, halign: 'center', align: 'right'}
		    ]]
		});
	};

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
			$('#do_no_add').textbox('textbox').attr('autocomplete', 'on');

			$('#sett_contract').hide();

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

			$('#cmb_po_no').combobox('disable');
			$('#ck_po_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_po_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_po_no').combobox('enable');
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
				    {field:'CUSTOMER_NAME',title:'CUSTOMER',width:170, halign: 'center', sortable:true},
				    {field:'EX_RATE',title:'EXCHANGE<br/>RATE',width:60, halign: 'center', align:'right', sortable:true},
				    {field:'CURR_MARK',title:'CURRENCY',width:55, halign: 'center', align: 'center',sortable:true},
				    {field:'AMT_O',title:'AMOUNT<br>(ORG)',width:80, halign: 'center', align:'right', sortable:true},
				    {field:'AMT_L',title:'AMOUNT<br>(LOC)',width:80, halign: 'center', align:'right', sortable:true},
				    {field:'REMARK1',title:'REMARK', width:180, halign: 'center'},
				    {field:'CUSTOMER_CODE', hidden: true},
				    {field:'PERSON',title:'USER ENTRY', width:80, halign: 'center'},
				    {field:'DELIVERY_UPDATE',title:'STATUS<br>DELIVERY', width:80, halign: 'center', align: 'center'},
				    {field:'STS', hidden: true}
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

		function validate(value){
			var hasil=0;
			var msg='';
				if(value == 'add'){
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;

					if($('#customer_add').combobox('getValue')==''){
						msg = $.messager.alert('Warning','Please select customer','warning');
						hasil=1;
					}else if($('#do_no_add').textbox('getValue')==''){
						msg = $.messager.alert('INFORMATION','Invoice No. Not Found','info');
						hasil=1;
					}else if($('#do_date_add').datebox('getValue')==''){
						msg = $.messager.alert('INFORMATION','Invoice date Not Found','info');
						hasil=1;
					}else if($('#GST_add').textbox('getValue')==''){
						msg = $.messager.alert('INFORMATION','GST rate Not Found','info');
						hasil=1;
					}else if($('#sino_add').combogrid('getValue')==''){
						msg = $.messager.alert('INFORMATION','SI No. Not Found','info');
						hasil=1;
					}else if ($('#trans_add').combobox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','Transport Not Found','info');
						hasil=1;
					}else if($('#remark_add').textbox('getValue').length >= 299){
						msg = $.messager.alert('INFORMATION','Please enter a value remark between 0 and 299','info');
						hasil=1;
					}
					/*else if ($('#transport1_add').textbox('getValue') =='undefined-undefined-undefined' || $('#transport1_add').textbox('getValue') == '' || $('#transport1_add').textbox('getValue') == '--'){
						msg = $.messager.alert('INFORMATION','Sett Container Not Found','info');
						hasil=1;
						$('#trans_add').combobox('setValue','');
						$('#transport1_add').textbox('setValue','');
						$('#transport2_add').textbox('setValue','');
					}*/else if ($('#forwarder_add').combobox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','Forwarder Not Found','info');
						hasil=1;
					}else if ($('#truck_add').combobox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','Sett Truck Not Found','info');
						hasil=1;
					}else if($('#book_no_add').textbox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','Booking No. Not Found','info');
						hasil=1;
					}else if($('#book_no_add').textbox('getValue').length >= 25){
						msg = $.messager.alert('INFORMATION','Please enter a value remark between 0 and 25','info');
						hasil=1;
					}else if($('#ppbe_no').combobox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','PPBE No. Not Found','info');
						hasil=1;
					}

					for(i2=0;i2<total;i2++){
						$('#dg_add').datagrid('endEdit',i2);
						if ($('#dg_add').datagrid('getData').rows[i2].U_PRICE == ''){
							msg = $.messager.alert('INFORMATION','Price not Found','info');
							hasil=1;		
						}else if($('#dg_add').datagrid('getData').rows[i2].DATE_CODE == ''){
							msg = $.messager.alert('INFORMATION','Date Code not found','info');
							hasil=1;
						}
					}
				}else if(value == 'edit'){
					var t = $('#dg_edit').datagrid('getRows');
					var total = t.length;

					if($('#customer_edit').combobox('getValue')==''){
						msg = $.messager.alert('Warning','Please select customer','warning');
						hasil=1;
					}else if($('#do_no_edit').textbox('getValue')==''){
						msg = $.messager.alert('INFORMATION','Invoice No. Not Found','info');
						hasil=1;
					}else if($('#do_date_edit').datebox('getValue')==''){
						msg = $.messager.alert('INFORMATION','Invoice date Not Found','info');
						hasil=1;
					}else if($('#GST_edit').textbox('getValue')==''){
						msg = $.messager.alert('INFORMATION','GST rate Not Found','info');
						hasil=1;
					}else if($('#sino_edit').combobox('getValue')==''){
						msg = $.messager.alert('INFORMATION','SI No. Not Found','info');
						hasil=1;
					}else if ($('#trans_edit').combobox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','Transport Not Found','info');
						hasil=1;
					}else if($('#remark_edit').textbox('getValue').length >= 299){
						msg = $.messager.alert('INFORMATION','Please enter a value remark between 0 and 299 char','info');
						hasil=1;
					}/*else if ($('#transport1_edit').textbox('getValue') =='undefined-undefined-undefined' || $('#transport1_edit').textbox('getValue') == '' || $('#transport1_edit').textbox('getValue') == '--'){
						msg = $.messager.alert('INFORMATION','Sett Container Not Found','info');
						hasil=1;
						$('#trans_edit').combobox('setValue','');
						$('#transport1_edit').textbox('setValue','');
						$('#transport2_edit').textbox('setValue','');
					}*/else if ($('#forwarder_edit').combobox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','Forwarder Not Found','info');
						hasil=1;
					}else if ($('#truck_edit').combobox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','Sett Truck Not Found','info');
						hasil=1;
					}else if($('#book_no_edit').textbox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','Booking No. Not Found','info');
						hasil=1;
					}else if($('#book_no_edit').textbox('getValue').length >= 25){
						msg = $.messager.alert('INFORMATION','Please enter a value remark between 0 and 25','info');
						hasil=1;
					}else if($('#ppbe_no_edit').combobox('getValue') == ''){
						msg = $.messager.alert('INFORMATION','PPBE No. Not Found','info');
						hasil=1;
					}

					for(i2=0;i2<total;i2++){
						$('#dg_edit').datagrid('endEdit',i2);
						if ($('#dg_edit').datagrid('getData').rows[i2].U_PRICE == ''){
							msg = $.messager.alert('INFORMATION','Price not Found','info');
							hasil=1;		
						}else if($('#dg_edit').datagrid('getData').rows[i2].DATE_CODE == ''){
							msg = $.messager.alert('INFORMATION','Date Code not found','info');
							hasil=1;
						}
					}
				}
			return hasil;
		}

		function default_set(value){
			if(value=='add'){
				$('#curr_add').combobox('disable');
				$('#do_no_add').textbox('disable');
				$('#do_date_add').combobox('disable');
				$('#GST_add').textbox('disable');
				$('#sino_add').combogrid('disable');
				$('#attn_add').textbox('disable');
				$('#eta_date_add').datebox('disable');
		    	$('#etd_date_add').datebox('disable');
		    	$('#notify_add').textbox('disable');
				$('#remark_add').textbox('disable');
				$('#trans_add').combobox('disable');
				$('#forwarder_add').combobox('disable');
				$('#truck_add').combobox('disable');
				$('#book_no_add').combobox('disable');
				$('#add_so_part').linkbutton('disable');
				$('#remove_so_part').linkbutton('disable');
			}
		}

/*========================================== FILTER =========================================*/

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
			var ck_po='false';
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

			if($('#ck_po_no').attr("checked")){
				ck_po='true';
				flag += 1;
			}

			if(flag == 5){
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
				cmb_po: $('#cmb_po_no').combobox('getValue'),
				ck_po: ck_po,
				src: ''
			});

		   	url_pdf ="?date_awal="+$('#date_awal').datebox('getValue')+"&date_akhir="+$('#date_akhir').datebox('getValue')+"&ck_date="+ck_date+"&customer="+$('#customer').combobox('getValue')+"&customer_nm="+$('#customer').combobox('getText')+"&ck_customer="+ck_customer+"&cmb_item_no="+$('#cmb_item_no').combobox('getValue')+"&ck_item_no="+ck_item_no+"&cmb_do="+$('#cmb_do_no').combobox('getValue')+"&ck_do="+ck_do+"&txt_item_name="+$('#cmb_item_no').combobox('getText')+"&ck_po="+ck_po+"&cmb_po="+$('#cmb_po_no').combobox('getValue');

		   	$('#dg').datagrid('enableFilter');
		}

/*========================================== ADD ============================================*/

		function addInv(){
			$('#win_add').window('open').window('setTitle','ADD INVOICE');
			$('#win_add').window('center');
			$('#f_add').form('reset');
			default_set('add');
			$('#dg_add').datagrid('loadData',[]);
			$('#dg_add').datagrid({
				columns:[[
					{field:'CUSTOMER_PART_NO', title:'ITEM<br/>NO.', width:45, halign:'center', align:'center'},
					{field:'DESCRIPTION', title:'DESCRIPTION', width:200, halign:'center'},
					{field:'SO_NO', title:'SO NO.', width:80, halign:'center'},
					{field:'LINE_NO', hidden: true},
					{field:'CUSTOMER_PO_NO', title:'CUSTOMER<br/>PO NO.', width:80, halign:'center'},
					{field:'ANSWER_NO', title:'REF NO.', width:80, halign:'center'},
					{field:'DELIVERY_QTY', title:'QTY', width:80, halign:'center', align:'right'},
					{field:'UOM_Q', title:'UoM', width:35, halign:'center', align:'center'},
					{field:'U_PRICE', title:'PRICE', width:80, halign:'center', align:'right', editor:{type:'numberbox',
																									   options:{precision:6,groupSeparator:',',disable:true}
																					   				  }
					},
					{field:'DATE_CODE', title:'DATE CODE', width:80, halign:'center', editor:{type:'textbox'}},
					//{field:'REMARK_PACKING', title:'PACKING<br/>MARK', width:200, halign:'center', editor:{type:'textbox'}},
					{field:'SHIPPING_SET', title:'SHIPPING<br/>SETT', width:60, halign:'center', align:'center'},
					{field:'REMARK_SHIPPING', title:'SHIPPING<br/>MARK', width:200, halign:'center'},
					{field:'ORIGIN_CODE', hidden: true}
				]],
				onClickRow:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    }
			});
		}

		$('#sino_add').combogrid({
			panelWidth:400,
            idField:'SI_NO',
            textField:'SI_NO',
            fitColumns: true,
            columns:[[
                {field:'SI_NO', width:120},
                {field:'PO_NO', width:120},
                {field:'FINAL_DEST', width:200}
            ]],
		    onClickRow: function(rec){
		    	var g = $('#sino_add').combogrid('grid');
				var r = g.datagrid('getSelected');

		    	if (r) {
		    		/*if( parseInt(r.REMARK) != 1){*/
				    	$('#sino_add').combogrid('setValue', r.SI_NO);
				    	$('#load_port_add').textbox('setValue', r.LOAD_PORT_CODE+'-'+r.LOAD_PORT);
				    	$('#discharge_add').textbox('setValue', r.DISCH_PORT_CODE+'-'+r.DISCH_PORT);
				    	$('#destination_add').textbox('setValue', r.FINAL_DEST_CODE+'-'+r.FINAL_DEST);
				    	$('#vessel_add').textbox('setValue', r.VESSEL);
				    	$('#etd_date_add').datebox('setValue', r.ETD_F);
				    	$('#eta_date_add').datebox('setValue', r.ETA_F);
				    	$('#notify_add').textbox('setValue', r.CONSIGNEE_FULL);
				    	$('#goods_names_add').textbox('setValue',r.GOODS_NAME);
				    	$('#attn_add').textbox('setValue',r.PERSON_NAME);
				    	$('#do_date_add').datebox('setValue', r.EX_FACT_DATE);
					
				    	$('#vessel_add').datebox('enable');
				    	$('#eta_date_add').datebox('enable');
				    	$('#etd_date_add').datebox('enable');
				    	$('#notify_add').textbox('enable');
						$('#remark_add').textbox('enable');
						$('#ppbe_no').combobox('enable');

						if (r.PAYMENT_TYPE == null || r.PAYMENT_REMARK == null){
							$('#remark_add').textbox('setValue','FREIGHT COLLECT');
						}else{
							$('#remark_add').textbox('setValue','FREIGHT '+r.PAYMENT_TYPE.toUpperCase()+' '+r.PAYMENT_REMARK.toUpperCase());
						}

						$('#trans_add').combobox('enable');
						$('#forwarder_add').combobox('enable');
						$('#truck_add').combobox('enable');
						$('#book_no_add').combobox('enable');
						$('#add_so_part').linkbutton('enable');
						$('#remove_so_part').linkbutton('enable');
						$('#attn_add').textbox('enable');
					/*}else{
						$.messager.alert('INFORMATION','Shipping Plan not Approved','info');
						$('#sino_add').combogrid('setValue','');
					}*/
		    	};
		    }
		});

		function remove_part_SO(value){
			if(value == 'add'){
				var row = $('#dg_add').datagrid('getSelected');	
				if (row){
					$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
						if(r){
							var idx = $("#dg_add").datagrid("getRowIndex", row);
							$('#remark_add').textbox('setValue',remark);
							$('#dg_add').datagrid('deleteRow', idx);

						}	
					});
				}
			}else if(value == 'edit'){
				var row = $('#dg_edit').datagrid('getSelected');	
				if (row){
					$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
						if(r){
							var idx = $("#dg_edit").datagrid("getRowIndex", row);
							$('#dg_edit').datagrid('deleteRow', idx);
						}	
					});
				}	
			}
			
		}

		function simpan(){
			var jumError = 0;
			var tot_amt_o = 0;		var tot_amt_l = 0;		var jmrow=1;
			var t = $('#dg_add').datagrid('getRows');
			var total = t.length;

			//for(i=0;i<total;i++){
			//	alert($('#dg_add').datagrid('getData').rows[i].REMARK_SHIPPING);
			//}

			for(i=0;i<total;i++){
				var amt_o = 0;
				var amt_l = 0;
				$('#dg_add').datagrid('endEdit',i);
			
				$.post('invoice_save.php',{
					do_sts: 'DETAILS',
					do_no: $('#do_no_add').textbox('getValue'),
					do_date: $('#do_date_add').datebox('getValue'),
					do_cust: $('#customer_add').combobox('getValue'),
					do_curr: $('#curr_add').combobox('getValue'),
					do_rate: $('#rate_add').textbox('getValue'),
					do_paym: $('#pday_add').textbox('getValue'),
					do_remark: $('#remark_add').textbox('getValue').replace(/\n/gi,"<br>"),
					do_gst: $('#GST_add').textbox('getValue'),
					do_vsl: $('#vessel_add').textbox('getValue').replace(/\n/gi,"<br>"),
					do_port_load: $('#load_port_add').textbox('getValue'),
					do_port_disg: $('#discharge_add').textbox('getValue'),
					do_port_dest: $('#destination_add').textbox('getValue'),
					do_etd: $('#etd_date_add').datebox('getValue'),
					do_eta: $('#eta_date_add').datebox('getValue'),
					do_contract: $('#contract_no_add').textbox('getValue'),
					do_pby: $('#payment_add').textbox('getValue'),
					do_trade_term: $('#tterm_add').textbox('getValue'),
					do_notify: $('#notify_add').textbox('getValue').replace(/\n/gi,"<br>"),
					do_attn: $('#attn_add').textbox('getValue'),
					do_si_no: $('#sino_add').combogrid('getValue'),
					do_goods_name : $('#goods_names_add').textbox('getValue'),
					
					//DETAILS
					do_line_no: jmrow,
					do_item_no: $('#dg_add').datagrid('getData').rows[i].CUSTOMER_PART_NO,
					do_origin: $('#dg_add').datagrid('getData').rows[i].ORIGIN_CODE,
					do_cust_part_no: $('#dg_add').datagrid('getData').rows[i].CUSTOMER_PART_NO,
					do_qty: $('#dg_add').datagrid('getData').rows[i].STK_QTY,
					do_uomq: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
					do_price: $('#dg_add').datagrid('getData').rows[i].U_PRICE,
					do_amt_o: parseFloat($('#dg_add').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,'') * 
							  parseFloat($('#dg_add').datagrid('getData').rows[i].U_PRICE)).toFixed(2),
					do_amt_l: parseFloat($('#dg_add').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,'') * 
							  parseFloat($('#dg_add').datagrid('getData').rows[i].U_PRICE) * 
							  $('#rate_add').textbox('getValue')).toFixed(2),
					do_desc: $('#dg_add').datagrid('getData').rows[i].DESCRIPTION,
					do_so_no: $('#dg_add').datagrid('getData').rows[i].SO_NO,
					do_so_line_no: $('#dg_add').datagrid('getData').rows[i].LINE_NO,
					do_qty_answer: $('#dg_add').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,''),
					do_cust_po_no: $('#dg_add').datagrid('getData').rows[i].CUSTOMER_PO_NO,
					do_answer_no: $('#dg_add').datagrid('getData').rows[i].ANSWER_NO,
					do_date_code: $('#dg_add').datagrid('getData').rows[i].DATE_CODE,
					do_remark_packing: $('#dg_add').datagrid('getData').rows[i].REMARK_PACKING,
					do_remark_shipping: $('#dg_add').datagrid('getData').rows[i].REMARK_SHIPPING,
					//do_line_remark: 
					//o_checked:
					//forwarder letter
					do_forwarder_code: $('#forwarder_add').combobox('getValue'),
					do_domestic_truck_code: $('#truck_add').combobox('getValue'),
					do_booking: $('#book_no_add').textbox('getValue'),
					do_transport_type: $('#trans_add').combobox('getValue'),
					do_transport_name: $('#trans_add').combobox('getText'),	
					do_sett_transport1: $('#transport1_add').textbox('getValue'),
					do_sett_transport2: $('#transport2_add').textbox('getValue'),
					do_ppbe: $('#ppbe_no').combobox('getValue')
			
				}).done(function(res){
					console.log(res);
					//alert(res);
				});
			
				amt_o = parseFloat($('#dg_add').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,'') * parseFloat($('#dg_add').datagrid('getData').rows[i].U_PRICE)).toFixed(2);
				amt_l = parseFloat($('#dg_add').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,'') * parseFloat($('#dg_add').datagrid('getData').rows[i].U_PRICE) * 
						$('#rate_add').textbox('getValue')).toFixed(2);
			
				tot_amt_o += parseFloat(amt_o);
				tot_amt_l += parseFloat(amt_l);
			
				if(i==total-1){
					$.post('invoice_save.php',{
						do_sts: 'HEADER',
						do_no: $('#do_no_add').textbox('getValue'),
						do_date: $('#do_date_add').datebox('getValue'),
						do_cust: $('#customer_add').combobox('getValue'),
						do_curr: $('#curr_add').combobox('getValue'),
						do_rate: $('#rate_add').textbox('getValue'),
						do_paym: $('#pday_add').textbox('getValue'),
						do_remark: $('#remark_add').textbox('getValue').replace(/\n/gi,"<br>"),
						do_gst: $('#GST_add').textbox('getValue'),
						do_vsl: $('#vessel_add').textbox('getValue').replace(/\n/gi,"<br>"),
						do_port_load: $('#load_port_add').textbox('getValue'),
						do_port_disg: $('#discharge_add').textbox('getValue'),
						do_port_dest: $('#destination_add').textbox('getValue'),
						do_etd: $('#etd_date_add').datebox('getValue'),
						do_eta: $('#eta_date_add').datebox('getValue'),
						do_contract: $('#contract_no_add').textbox('getValue'),
						do_pby: $('#payment_add').textbox('getValue'),
						do_trade_term: $('#tterm_add').textbox('getValue'),
						do_notify: $('#notify_add').textbox('getValue').replace(/\n/gi,"<br>"),
						do_attn: $('#attn_add').textbox('getValue'),
						do_si_no: $('#sino_add').combogrid('getValue'),
						do_goods_name : $('#goods_names_add').textbox('getValue'),
			
						//DETAILS
						do_line_no: jmrow,
						do_item_no: $('#dg_add').datagrid('getData').rows[i].CUSTOMER_PART_NO,
						do_origin: $('#dg_add').datagrid('getData').rows[i].ORIGIN_CODE,
						do_cust_part_no: $('#dg_add').datagrid('getData').rows[i].CUSTOMER_PART_NO,
						do_qty: $('#dg_add').datagrid('getData').rows[i].STK_QTY,
						do_uomq: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
						do_price: $('#dg_add').datagrid('getData').rows[i].U_PRICE,
						do_amt_o: parseFloat(tot_amt_o).toFixed(2),
						do_amt_l: parseFloat(tot_amt_l).toFixed(2),
						do_desc: $('#dg_add').datagrid('getData').rows[i].DESCRIPTION,
						do_so_no: $('#dg_add').datagrid('getData').rows[i].SO_NO,
						do_so_line_no: $('#dg_add').datagrid('getData').rows[i].LINE_NO,
						do_qty_answer: $('#dg_add').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,''),
						do_cust_po_no: $('#dg_add').datagrid('getData').rows[i].CUSTOMER_PO_NO,
						do_answer_no: $('#dg_add').datagrid('getData').rows[i].ANSWER_NO,
						do_date_code: $('#dg_add').datagrid('getData').rows[i].DATE_CODE,
						do_remark_packing: $('#dg_add').datagrid('getData').rows[i].REMARK_PACKING,
						//do_line_remark: 
						//do_checked:
						//forwarder letter
						do_forwarder_code: $('#forwarder_add').combobox('getValue'),
						do_domestic_truck_code: $('#truck_add').combobox('getValue'),
						do_booking: $('#book_no_add').textbox('getValue'),
						do_transport_type: $('#trans_add').combobox('getValue'),
						do_transport_name: $('#trans_add').combobox('getText'),
						do_sett_transport1: $('#transport1_add').textbox('getValue'),
						do_sett_transport2: $('#transport2_add').textbox('getValue'),
						do_ppbe: $('#ppbe_no').combobox('getValue')
			
					}).done(function(res){
						console.log(res);
						//alert(res);
					});						
				}
				jmrow++;
			}
		}

		function saveDO(value){
			if(value == 'add'){
				if (validate('add') != 1){
					$.messager.progress({
		                title:'Please waiting',
		                msg:'Loading data...'
		            });
					$.ajax({
						type: 'GET',
						url: 'json/json_kode_do.php?do='+$('#do_no_add').textbox('getValue'),
						data: { kode:'kode' },
						success: function(data){
							if(data[0].kode == 'ALREADY'){
								$.messager.alert('INFORMATION','Invoice No. Already Exists','info');
								$.messager.progress('close');
								$('#do_no_add').textbox('textbox').focus();
							}else{
								simpan();
								$.messager.progress('close');
								$('#win_add').window('close');
								$('#dg').datagrid('reload');
								$.messager.alert('INFORMATION','Insert Success','info');	
							}
						}
					});
				}
			}else if (value == 'edit'){
				if (validate('edit') != 1){
		            $.messager.progress({
		                title:'Please waiting',
		                msg:'Loading data...'
		            });
					$.post('invoice_destroy.php',{do_no: $('#do_no_edit').textbox('getValue')},function(result){
						if (result.successMsg){
							$.messager.progress('close');
		                  	simpan_edit();
							$('#win_edit').window('close');
							$('#dg').datagrid('reload');
							$.messager.alert('INFORMATION','Update Success','info');
		                }else{
		                	$.messager.progress('close');
		                    $.messager.show({
		                        title: 'Error',
		                        msg: result.errorMsg
		                    });
		                }
					},'json');
				}
			}
		}

/*========================================== EDIT ===========================================*/
		var DO_UPD = '';
		function editInv(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
			  if(row.STS == 1){
			  	$.messager.confirm('Confirm','All Data Cannot Be Changed, only ETD, ETA, VESSEL and remark',function(r){
					if(r){
						DO_UPD = row.DO_NO;
						$('#dlg_edit_stsdel').dialog('open').dialog('setTitle','EDIT INVOICE (No. : '+row.DO_NO+')');
					  	$('#etd_date_edit_stsdel').datebox('setValue', row.ETD);
					  	$('#eta_date_edit_stsdel').datebox('setValue', row.ETA);
					  	$('#vessel_edit_stsdel').textbox('setValue', row.SHIP_NAME.replace(/<br>/gi,"\n"));
					  	$('#remark_edit_stsdel').textbox('setValue',row.REMARK1.replace(/<br>/gi,"\n"));
					}
				});
			  }else{
				$('#win_edit').window('open').window('setTitle','EDIT INVOICE (No. : '+row.DO_NO+')');
				$('#customer_edit').combobox('setValue',row.CUSTOMER_CODE);
				$('#do_no_edit').textbox('setValue',row.DO_NO);
				$('#do_date_edit').textbox('setValue',row.TANGGAL_DO);
				$('#country_edit').textbox('setValue',row.COUNTRY_CODE+'-'+row.COUNTRY);
				$('#attn_edit').textbox('setValue',row.ATTN);
				$('#curr_edit').combobox('setValue',row.CURR_CODE);
				$('#rate_edit').textbox('setValue',row.EX_RATE);
				$('#payment_edit').textbox('setValue',row.PBY);
				$('#pday_edit').textbox('setValue',row.PDAYS+'-'+row.PDESC);
				$('#GST_edit').textbox('setValue',row.GST_RATE);
				$('#tterm_edit').textbox('setValue',row.TRADE_TERM);
				$('#term_edit').textbox('setValue',row.TERM);
				$('#vessel_edit').textbox('setValue', row.SHIP_NAME.replace(/<br>/gi,"\n"));
				$('#remark_edit').textbox('setValue',row.REMARK1.replace(/<br>/gi,"\n"));
				$('#eta_date_edit').datebox('setValue', row.ETA);
				$('#etd_date_edit').datebox('setValue', row.ETD);
				$('#load_port_edit').textbox('setValue', row.LOAD_PORT_CODE+'-'+row.LOAD_PORT)
				$('#discharge_edit').textbox('setValue',row.DISCH_PORT_CODE+'-'+row.PORT_DISCHARGE);
				$('#destination_edit').textbox('setValue',row.FINAL_DEST_CODE+'-'+row.FINAL_DESTINATION);
				$('#notify_edit').textbox('setValue', row.NOTIFY.replace(/<br>/gi,"\n"));
				$('#trans_edit').combobox('setValue', row.TRANSPORT_TYPE);
				$('#forwarder_edit').combobox('setValue',row.FORWARDER_CODE);
				$('#truck_edit').combobox('setValue',row.DOMESTIC_TRUCK_CODE);
				$('#book_no_edit').textbox('setValue',row.BOOKING_NO);
				$('#contract_no_edit').textbox('setValue', row.CONTRACT_SEQ);
				$('#transport1_edit').textbox('setValue', row.CARGO_TYPE1+'-'+row.CARGO_SIZE1+'-'+row.CARGO_QTY1);
				$('#transport2_edit').textbox('setValue', row.CARGO_TYPE2+'-'+row.CARGO_SIZE2+'-'+row.CARGO_QTY2);

				$('#dg_contract').datagrid('load',{id: row.CUSTOMER_CODE});
				$('#sino_edit').combogrid({url: 'invoice_get_si_no.php?id='+row.CUSTOMER_CODE+'&term='+row.TERM});
				$('#sino_edit').combogrid('setValue',row.SI_NO);
				$('#goods_names_edit').textbox('setValue',row.GOODS_NAME);
				$('#ppbe_no_edit').combobox('setValue',row.PPBE_NO);

				$('#dg_edit').datagrid({
				    url:'invoice_get_detail_edit.php?do_no='+row.DO_NO,
				    singleSelect: true,
				    columns:[[
						{field:'CUSTOMER_PART_NO', title:'ITEM<br/>NO.', width:45, halign:'center', align:'center'},
						{field:'DESCRIPTION', title:'DESCRIPTION', width:200, halign:'center'},
						{field:'SO_NO', title:'SO NO.', width:80, halign:'center'},
						{field:'LINE_NO', hidden: true},
						{field:'CUSTOMER_PO_NO', title:'CUSTOMER<br/>PO NO.', width:80, halign:'center'},
						{field:'ANSWER_NO', title:'REF NO.', width:80, halign:'center'},
						{field:'DELIVERY_QTY', title:'QTY', width:80, halign:'center', align:'right'},
						{field:'UOM_Q', title:'UoM', width:35, halign:'center', align:'center'},
						{field:'U_PRICE', title:'PRICE', width:80, halign:'center', align:'right', editor:{type:'numberbox',
																										   options:{precision:6,groupSeparator:',',disable:true}
																						   				  }
						},
						{field:'DATE_CODE', title:'DATE CODE', width:80, halign:'center', editor:{type:'textbox'}},
						//{field:'REMARK_PACKING', title:'PACKING<br/>MARK', width:200, halign:'center', editor:{type:'textbox'}},
						{field:'SHIPPING_SET', title:'SHIPPING<br/>SETT', width:60, halign:'center', align:'center'},
						{field:'REMARK_SHIPPING', title:'SHIPPING<br/>MARK', width:200, halign:'center'},
						{field:'ORIGIN_CODE', hidden: true}
					]],
					onClickRow:function(rowIndex){
				    	$(this).datagrid('beginEdit', rowIndex);
				    }
				});
			  }
			}
		}

		$('#sino_edit').combogrid({
			panelHeight: '170px',
			panelWidth: '500',
            idField: 'SI_NO',
            textField: 'SI_NO',
			method: 'get',
			columns: [[
				{field:'SI_NO', title:'INSTRUCTION NO.', halign:'center', width:100},
				{field:'PO_NO', title:'CUST. PO NO.' , halign:'center', width:150},
				{field:'FINAL_DEST', title:'DESTINATION', halign:'center', width:200}
			]],
            fitColumns: true,
			label: 'Select Item:',
			labelPosition: 'top',
		    onClickRow: function(rec){
		    	var g = $('#sino_edit').combogrid('grid');
				var r = g.datagrid('getSelected');

		    	if (r) {
		    		/*if( parseInt(r.REMARK) != 1){*/
				    	$('#sino_edit').combogrid('setValue', r.SI_NO);
				    	$('#load_port_edit').textbox('setValue', r.LOAD_PORT_CODE+'-'+r.LOAD_PORT);
				    	$('#discharge_edit').textbox('setValue', r.DISCH_PORT_CODE+'-'+r.DISCH_PORT);
				    	$('#destination_edit').textbox('setValue', r.FINAL_DEST_CODE+'-'+r.FINAL_DEST);
				    	$('#vessel_edit').textbox('setValue', r.VESSEL);
				    	$('#etd_date_edit').datebox('setValue', r.ETD_F);
				    	$('#eta_date_edit').datebox('setValue', r.ETA_F);
				    	$('#notify_edit').textbox('setValue', r.CONSIGNEE_FULL);
				    	$('#goods_names_edit').textbox('setValue', r.GOODS_NAME);
				    	$('#do_date_edit').datebox('setValue',r.EX_FACT_DATE);

				    	$('#eta_date_edit').datebox('enable');
				    	$('#etd_date_edit').datebox('enable');
				    	$('#notify_edit').textbox('enable');
						$('#remark_edit').textbox('enable');
						
						if (r.PAYMENT_TYPE == null || r.PAYMENT_REMARK == null){
							$('#remark_edit').textbox('setValue','FREIGHT COLLECT');
						}else{
							$('#remark_edit').textbox('setValue','FREIGHT '+r.PAYMENT_TYPE.toUpperCase()+' '+r.PAYMENT_REMARK.toUpperCase());
						}

						$('#trans_edit').combobox('enable');
						$('#forwarder_edit').combobox('enable');
						$('#truck_edit').combobox('enable');
						$('#book_no_edit').combobox('enable');
					/*}else{
						$.messager.alert('INFORMATION','Shipping Plan not Approved','info');
						$('#sino_edit').combogrid('setValue','');
					}*/
		    	};
		    }
		});

		function simpan_edit(){
			var jumError_e = 0;
			var tot_amt_o_e = 0;		var tot_amt_l_e = 0;		var jmrow_e=1;
			var t_e = $('#dg_edit').datagrid('getRows');
			var total_e = t_e.length;

			for(i=0;i<total_e;i++){
				var amt_o_e = 0;
				var amt_l_e = 0;
				$('#dg_edit').datagrid('endEdit',i);

				$.post('invoice_save.php',{
					do_sts: 'DETAILS',
					do_no: $('#do_no_edit').textbox('getValue'),
					do_date: $('#do_date_edit').datebox('getValue'),
					do_cust: $('#customer_edit').combobox('getValue'),
					do_curr: $('#curr_edit').combobox('getValue'),
					do_rate: $('#rate_edit').textbox('getValue'),
					do_paym: $('#pday_edit').textbox('getValue'),
					do_remark: $('#remark_edit').textbox('getValue').replace(/\n/gi,"<br>"),
					do_gst: $('#GST_edit').textbox('getValue'),
					do_vsl: $('#vessel_edit').textbox('getValue').replace(/\n/gi,"<br>"),
					do_port_load: $('#load_port_edit').textbox('getValue'),
					do_port_disg: $('#discharge_edit').textbox('getValue'),
					do_port_dest: $('#destination_edit').textbox('getValue'),
					do_etd: $('#etd_date_edit').datebox('getValue'),
					do_eta: $('#eta_date_edit').datebox('getValue'),
					do_contract: $('#contract_no_edit').textbox('getValue'),
					do_pby: $('#payment_edit').textbox('getValue'),
					do_trade_term: $('#tterm_edit').textbox('getValue'),
					do_notify: $('#notify_edit').textbox('getValue').replace(/\n/gi,"<br>"),
					do_attn: $('#attn_edit').textbox('getValue'),
					do_si_no: $('#sino_edit').combogrid('getValue'),
					do_goods_name: $('#goods_names_edit').textbox('getValue'),
					
					//DETAILS
					do_line_no: jmrow_e,
					do_item_no: $('#dg_edit').datagrid('getData').rows[i].CUSTOMER_PART_NO,
					do_origin: $('#dg_edit').datagrid('getData').rows[i].ORIGIN_CODE,
					do_cust_part_no: $('#dg_edit').datagrid('getData').rows[i].CUSTOMER_PART_NO,
					do_qty: $('#dg_edit').datagrid('getData').rows[i].STK_QTY,
					do_uomq: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
					do_price: $('#dg_edit').datagrid('getData').rows[i].U_PRICE,
					do_amt_o: parseFloat($('#dg_edit').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,'') * parseFloat($('#dg_edit').datagrid('getData').rows[	i].U_PRICE)).toFixed(2),
					do_amt_l: parseFloat($('#dg_edit').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,'') * parseFloat($('#dg_edit').datagrid('getData').rows[i].U_PRICE) * 
							   $('#rate_edit').textbox('getValue')).toFixed(2),
					do_desc: $('#dg_edit').datagrid('getData').rows[i].DESCRIPTION,
					do_so_no: $('#dg_edit').datagrid('getData').rows[i].SO_NO,
					do_so_line_no: $('#dg_edit').datagrid('getData').rows[i].LINE_NO,
					do_qty_answer: $('#dg_edit').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,''),
					do_cust_po_no: $('#dg_edit').datagrid('getData').rows[i].CUSTOMER_PO_NO,
					do_answer_no: $('#dg_edit').datagrid('getData').rows[i].ANSWER_NO,
					do_date_code: $('#dg_edit').datagrid('getData').rows[i].DATE_CODE,
					do_remark_packing: $('#dg_edit').datagrid('getData').rows[i].REMARK_PACKING,
					do_remark_shipping: $('#dg_edit').datagrid('getData').rows[i].REMARK_SHIPPING,
					//do_line_remark: 
					//do_checked:
					//do_forwarder letter:
					do_forwarder_code: $('#forwarder_edit').combobox('getValue'),
					do_domestic_truck_code: $('#truck_edit').combobox('getValue'),
					do_booking: $('#book_no_edit').textbox('getValue'),
					do_transport_type: $('#trans_edit').combobox('getValue'),
					do_transport_name: $('#trans_edit').combobox('getText'),	
					do_sett_transport1: $('#transport1_edit').textbox('getValue'),
					do_sett_transport2: $('#transport2_edit').textbox('getValue'),
					do_ppbe: $('#ppbe_no_edit').combobox('getValue')
				}).done(function(res){
					console.log(res);
				});

				amt_o_e = parseFloat($('#dg_edit').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,'') * parseFloat($('#dg_edit').datagrid('getData').rows[i].U_PRICE)).toFixed(2);
				amt_l_e = parseFloat($('#dg_edit').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,'') * parseFloat($('#dg_edit').datagrid('getData').rows[i].U_PRICE) * 
						$('#rate_edit').textbox('getValue')).toFixed(2);

				tot_amt_o_e += parseFloat(amt_o_e);
				tot_amt_l_e += parseFloat(amt_l_e);

				if(i==total_e-1){
					$.post('invoice_save.php',{
						do_sts: 'HEADER',
						do_no: $('#do_no_edit').textbox('getValue'),
						do_date: $('#do_date_edit').datebox('getValue'),
						do_cust: $('#customer_edit').combobox('getValue'),
						do_curr: $('#curr_edit').combobox('getValue'),
						do_rate: $('#rate_edit').textbox('getValue'),
						do_paym: $('#pday_edit').textbox('getValue'),
						do_remark: $('#remark_edit').textbox('getValue').replace(/\n/gi,"<br>"),
						do_gst: $('#GST_edit').textbox('getValue'),
						do_vsl: $('#vessel_edit').textbox('getValue').replace(/\n/gi,"<br>"),
						do_port_load: $('#load_port_edit').textbox('getValue'),
						do_port_disg: $('#discharge_edit').textbox('getValue'),
						do_port_dest: $('#destination_edit').textbox('getValue'),
						do_etd: $('#etd_date_edit').datebox('getValue'),
						do_eta: $('#eta_date_edit').datebox('getValue'),
						do_contract: $('#contract_no_edit').textbox('getValue'),
						do_pby: $('#payment_edit').textbox('getValue'),
						do_trade_term: $('#tterm_edit').textbox('getValue'),
						do_notify: $('#notify_edit').textbox('getValue').replace(/\n/gi,"<br>"),
						do_attn: $('#attn_edit').textbox('getValue'),
						do_si_no: $('#sino_edit').combogrid('getValue'),
						do_goods_name: $('#goods_names_edit').textbox('getValue'),

						//DETAILS
						do_line_no: jmrow_e,
						do_item_no: $('#dg_edit').datagrid('getData').rows[i].CUSTOMER_PART_NO,
						do_origin: $('#dg_edit').datagrid('getData').rows[i].ORIGIN_CODE,
						do_cust_part_no: $('#dg_edit').datagrid('getData').rows[i].CUSTOMER_PART_NO,
						do_qty: $('#dg_edit').datagrid('getData').rows[i].STK_QTY,
						do_uomq: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
						do_price: $('#dg_edit').datagrid('getData').rows[i].U_PRICE,
						do_amt_o: parseFloat(tot_amt_o_e).toFixed(2),
						do_amt_l: parseFloat(tot_amt_l_e).toFixed(2),
						do_desc: $('#dg_edit').datagrid('getData').rows[i].DESCRIPTION,
						do_so_no: $('#dg_edit').datagrid('getData').rows[i].SO_NO,
						do_so_line_no: $('#dg_edit').datagrid('getData').rows[i].LINE_NO,
						do_qty_answer: $('#dg_edit').datagrid('getData').rows[i].DELIVERY_QTY.replace(/,/g,''),
						do_cust_po_no: $('#dg_edit').datagrid('getData').rows[i].CUSTOMER_PO_NO,
						do_answer_no: $('#dg_edit').datagrid('getData').rows[i].ANSWER_NO,
						do_date_code: $('#dg_edit').datagrid('getData').rows[i].DATE_CODE,
						do_remark_packing: $('#dg_edit').datagrid('getData').rows[i].REMARK_PACKING,
						//do_line_remark: 
						//do_checked:
						//forwarder letter
						do_forwarder_code: $('#forwarder_edit').combobox('getValue'),
						do_domestic_truck_code: $('#truck_edit').combobox('getValue'),
						do_booking: $('#book_no_edit').textbox('getValue'),
						do_transport_type: $('#trans_edit').combobox('getValue'),
						do_transport_name: $('#trans_edit').combobox('getText'),
						do_sett_transport1: $('#transport1_edit').textbox('getValue'),
						do_sett_transport2: $('#transport2_edit').textbox('getValue'),
						do_ppbe: $('#ppbe_no_edit').combobox('getValue')

					}).done(function(res){
						console.log(res);
						//alert(res);
					});						
				}
				jmrow_e++;
			}
		}

		function save_edit_stsdel(){
			$.messager.progress({
                title:'Please waiting',
                msg:'Loading data...'
            });

            $.post('invoice_save_stsdel.php',{
            	stsdel_dono: DO_UPD,
            	stsdel_etd: $('#etd_date_edit_stsdel').datebox('getValue'),
			  	stsdel_eta: $('#eta_date_edit_stsdel').datebox('getValue'),
			  	stsdel_vsl: $('#vessel_edit_stsdel').textbox('getValue').replace(/\n/gi,"<br>"),
			  	stsdel_rmk: $('#remark_edit_stsdel').textbox('getValue').replace(/\n/gi,"<br>")
            },function(result){
				if (result.successMsg){
					$.messager.progress('close');
					$('#dlg_edit_stsdel').dialog('close');
                    $('#dg').datagrid('reload');
                    $.messager.alert('INFORMATION',result.successMsg,'info');
                }else{
                	$.messager.progress('close');
                    $.messager.show({
                        title: 'Error',
                        msg: result.errorMsg
                    });
                }
			},'json');
		}

/*========================================== REMOVE =========================================*/

		function deleteInv(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
			  if(row.STS == 1){
			  	$.messager.alert('INFORMATION','Data Cannot be Changed, Delivery update Already exists','info');
			  }else{
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						$.messager.progress({
			                title:'Please waiting',
			                msg:'Loading data...'
			            });

						$.post('invoice_destroy.php',{do_no: row.DO_NO},function(result){
							if (result.successMsg){
								$.messager.progress('close');
		                        $('#dg').datagrid('reload');
		                    }else{
		                    	$.messager.progress('close');
		                        $.messager.show({
		                            title: 'Error',
		                            msg: result.errorMsg
		                        });
		                    }
						},'json');
					}
				});
			  }
			}else{
				$.messager.show({title: 'INVOICE DELETE',msg:'Data Not select'});
			}
		}

/*========================================== PRINT =========================================*/

		var pdf_url;
		var s_type;
		var no_do;
		var no_si;
		var no_ppbe;
		var rmk_stock;

		function printInv(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				no_do = row.DO_NO;
				no_si = row.SI_NO;
				rmk_stock = row.RMK;
				no_ppbe = row.PPBE_NO;
				
				$.get('inv_check.php',{
					do_no: no_do
				}).done(function(res){
					if(res == '"success"'){
						
				/*if(rmk_stock != '1'){*/
					$('#dg_print').datagrid('loadData',[]);
					$('#dlg_print').dialog('open').dialog('setTitle','Print Properties ('+row.DO_NO+')');
					$('#dg_print').datagrid({
						url: 'invoice_get_total.php?id='+no_do,
						fitColumns: true,
						singleSelect: true,
						columns:[[
			                {field:'SO',title:'ORDER<br/>QTY',width:75,halign:'center', align:'right'},
			                {field:'AVAILABLE',title:'AVAILABLE<br/>QTY',width:75,halign:'center', align:'right'},
			                {field:'PLAN',title:'PLANNED<br/>QTY',width:75,halign:'center', align:'right'},
			                {field:'INVOICE',title:'INVOICED<br/>QTY',width:75,halign:'center', align:'right'},
			                {field:'STS', hidden: true}
			            ]]
					});
				/*}else{
					$.messager.alert('INFORMATION','Invoice not Approved','info');
				}*/


					}else{
						$.messager.alert('ERROR',res,'warning');
					}
				});		
			
			}else{
				$.messager.show({title: 'INVOICE',msg: 'Data Not select'});
			}
		}

		function print(){
			var t = $('#dg_print').datagrid('getRows');
			var total = t.length;

			for(i=0;i<total;i++){
				var plan = parseFloat($('#dg_print').datagrid('getData').rows[i].PLAN.replace(/,/g,''));
				var avlb = parseFloat($('#dg_print').datagrid('getData').rows[i].AVAILABLE.replace(/,/g,''));
			}

			if(plan > avlb){
				if(rmk_stock != '1'){
					if(document.getElementById('invoice').checked == true){
						s_type = 'invoice_proforma';
					}
				}else{
					$.messager.alert('INFORMATION','Invoice not Approved','info');
				}
			}else{
				if(document.getElementById('invoice').checked == true){
					s_type = document.getElementById('invoice').value;
				}
			}

			if(document.getElementById('packing_list').checked == true){
				s_type = document.getElementById('packing_list').value;
			}else if(document.getElementById('invoice_packing').checked == true){
				s_type = document.getElementById('invoice_packing').value;
			}else if(document.getElementById('final_si').checked == true){
				s_type = document.getElementById('final_si').value;
			}else if(document.getElementById('debit_note').checked == true){
				s_type = document.getElementById('debit_note').value;
			}else if(document.getElementById('ppbe').checked == true){
				s_type = document.getElementById('ppbe').value;
			}else if(document.getElementById('delivery_slip').checked == true){
				s_type = document.getElementById('delivery_slip').value;
			}else if(document.getElementById('attach_pallet_mark').checked == true){
				s_type = document.getElementById('attach_pallet_mark').value;
			}else if(document.getElementById('attach_pallet_mark_xls').checked == true){
				s_type = document.getElementById('attach_pallet_mark_xls').value;
			}else if(document.getElementById('multi_final_si').checked == true){
				s_type = document.getElementById('multi_final_si').value;
			}

			if (s_type == ''){
				$.messager.show({title: 'INVOICE',msg: 'Data Not Print,<br>Status Proforma Invoice'});
			}else{
				pdf_url = "?IV_NUM=0&SHEET_TYPE="+s_type+"&DO_NO0="+no_do

				if (s_type == 'final_si'){
					$('#dlg_print').dialog('close');
					window.open('invoice_print_si.php?si='+no_si+'&do='+no_do+'&si_sts=final_si');
				}else if (s_type == 'multi_final_si'){
					$('#dlg_print').dialog('close');
					window.open('invoice_print_si_multi.php?si='+no_si+'&do='+no_do+'&si_sts=final_si');
					//window.open('invoice_print_si.php?si='+no_si+'&do='+no_do+'&si_sts=si');	
				}else if (s_type == 'si'){
					$('#dlg_print').dialog('close');
					window.open('invoice_print_si.php?si='+no_si+'&do='+no_do+'&si_sts=si');
				}else if (s_type == 'ppbe'){
					$('#dlg_print').dialog('close');
					$('#dlg_print_ppbe').dialog('open').dialog('setTitle','Print Properties');
				}else if (s_type == 'delivery_slip'){
					$('#dlg_print').dialog('close');
					window.open('http://sysfi01.indonesia.fdk.co.jp/pglosas/entry/fl/fl_sheet3.asp?DO_NUM=1&do_no0='+no_do);
				}else if (s_type == 'attach_pallet_mark'){
					$('#dlg_print').dialog('close');
					window.open('invoice_print_attach_plt_mark.php?si='+no_si+'&ppbe='+no_ppbe+'&do='+no_do);
				}else if (s_type == 'attach_pallet_mark_xls'){
					$('#dlg_print').dialog('close');
					window.open('invoice_print_attach_plt_mark_xls.php?si='+no_si+'&ppbe='+no_ppbe+'&do='+no_do);
				}else{
					$('#dlg_print').dialog('close');
					window.open('http://sysfi01.indonesia.fdk.co.jp/pglosas/entry/iv2/iv2_sheet3_dummy.asp'+pdf_url);
				}
			}
		}

		function print_ppbeNya(){
			var close_c = $('#closing_cargo').datebox('getValue');
			var jam = $('#cmb_jam').combobox('getValue');
			var mnt = $('#cmb_menit').combobox('getValue');
			var note = $('#note_ppbe').textbox('getValue');
	        var noteNya = note.replace(/\n/gi,"<br>");

			if(document.getElementById('lokal').checked == true){
				s_exp = document.getElementById('lokal').value;
			}else if (document.getElementById('export').checked == true){
				s_exp = document.getElementById('export').value;
			}

			if (jam == '' || mnt == ''){
				$.messager.alert('INFORMATION','Time not found','info');
			}else{
				$('#dlg_print_ppbe').dialog('close');
				window.open('invoice_print_ppbe.php?si='+no_si+'&do='+no_do+'&close_cargo='+close_c+'&jns_export='+s_exp+'&jam='+jam+'&menit='+mnt+'&note='+noteNya);
			}
		}

/*========================================== SETTING ========================================*/
		/*function sett_ppbe(){
			$.ajax({
				type: 'GET',
				url: 'json/json_kode_ppbe.php?user=<? //echo $user_name; ?>',
				data: { kode:'kode' },
				success: function(data){
					var idsPpbe = [];
					$('#ppbe_no').textbox('setValue',data[0].kode);
					//set remark
					var rmk0 = $('#remark_add').textbox('getValue');
					idsPpbe.push("PPBE : "+data[0].kode);
					var rmk_new0 = rmk0+'\n'+idsPpbe;
					$('#remark_add').textbox('setValue',rmk_new0);
				}
			});
		}*/

		function setting_contract(value){
			$('#dlg_contract').dialog('open').dialog('setTitle','SETT CONTRACT');
			$('#dg_contract').datagrid({
				url: '_getContract.php',
				fitColumns: true,
				singleSelect: true,
				columns:[[
					{field:'CONTRACT_SEQ',title:'CONTRACT<br/>NO.',width:75,halign:'center', align:'center'},
	                {field:'TTERM',title:'TRADE TERMS',width:100,halign:'center'},
	                {field:'PMETHOD',title:'PAYMENT',width:85,halign:'center', align:'center'},
	                {field:'PAYMENT',title:'PAYMENT',width:250,halign:'center'},
	                {field:'LOADING_PORT',title:'COUNTRY',width:100,halign:'center'},
	                {field:'TERM', hidden: true}
	            ]],
	            onClickRow:function(id,row){
	            	var rows = $('#dg_contract').datagrid('getSelected');
	            	if(value=='add'){
	            		$('#sino_add').combogrid({
							url: 'invoice_get_si_no.php?id='+$('#customer_add').combobox('getValue')+'&term='+rows.TERM
						});
						$('#sino_add').combogrid('enable');

						$('#curr_add').combobox('setValue',rows.CURR_CODE);
						$('#payment_add').textbox('setValue',rows.PMETHOD);
						$('#pday_add').textbox('setValue',rows.PDAYS+'-'+rows.PDESC);
						$('#tterm_add').textbox('setValue',rows.TTERM);

						$('#load_port_add').textbox('setValue', rows.PORT_LOADING_CODE+'-'+rows.LOADING_PORT);
				    	$('#discharge_add').textbox('setValue', rows.PORT_DISCHARGE_CODE+'-'+rows.DISCHARGE_PORT);
				    	$('#destination_add').textbox('setValue', rows.FINAL_DESTINATION_CODE+'-'+rows.FINAL_DEST);
				    	$('#contract_no_add').textbox('setValue', rows.CONTRACT_SEQ);
				    	$('#term_add').textbox('setValue', rows.TERM);
				    }else if(value == 'edit'){
				    	$('#sino_edit').combogrid({
							url: 'invoice_get_si_no.php?id='+$('#customer_edit').combobox('getValue')+'&term='+rows.TERM
						});
						$('#sino_edit').combogrid('enable');

				    	$('#curr_edit').combobox('setValue',rows.CURR_CODE);
						$('#payment_edit').textbox('setValue',rows.PMETHOD);
						$('#pday_edit').textbox('setValue',rows.PDAYS+'-'+rows.PDESC);
						$('#tterm_edit').textbox('setValue',rows.TTERM);

						$('#load_port_edit').textbox('setValue', rows.PORT_LOADING_CODE+'-'+rows.LOADING_PORT);
				    	$('#discharge_edit').textbox('setValue', rows.PORT_DISCHARGE_CODE+'-'+rows.DISCHARGE_PORT);
				    	$('#destination_edit').textbox('setValue', rows.FINAL_DESTINATION_CODE+'-'+rows.FINAL_DEST);
				    	$('#contract_no_edit').textbox('setValue', rows.CONTRACT_SEQ);
				    	$('#term_edit').textbox('setValue', rows.TERM);
				    }

	            	$('#dlg_contract').dialog('close');
				}
			});
		}

		function sett_port_load(value){
			$('#dlg_port').dialog('open').dialog('setTitle','SETT PORT');
			$('#dg_port').datagrid('load',{sch: ''});
			$('#dg_port').datagrid({
				url: '_getPort.php',
				fitColumns: true,
				columns:[[
					{field:'PORT_CODE',title:'CODE',width:65,halign:'center', align:'center'},
	                {field:'PORT',title:'NAME',width:200,halign:'center'}
	            ]],
	            onClickRow:function(id,row){
	            	var rows = $('#dg_port').datagrid('getSelected');
	            	if(value=='add'){
	            		$('#load_port_add').textbox('setValue',rows.PORT_CODE+'-'+rows.PORT);
	            	}else if (value == 'edit'){
	            		$('#load_port_edit').textbox('setValue',rows.PORT_CODE+'-'+rows.PORT);
	            	}
	            	$('#dlg_port').dialog('close');
				}
			});
			$('#dg_port').datagrid('enableFilter');
		}

		function sett_port_disch(value){
			$('#dlg_port').dialog('open').dialog('setTitle','SETT PORT');
			$('#dg_port').datagrid('load',{sch: ''});
			$('#dg_port').datagrid({
				url: '_getPort.php',
				fitColumns: true,
				columns:[[
					{field:'PORT_CODE',title:'CODE',width:65,halign:'center', align:'center'},
	                {field:'PORT',title:'NAME',width:200,halign:'center'}
	            ]],
	            onClickRow:function(id,row){
	            	var rows = $('#dg_port').datagrid('getSelected');
	            	if(value=='add'){
	            		$('#discharge_add').textbox('setValue',rows.PORT_CODE+'-'+rows.PORT);
	            	}else if(value == 'edit'){
	            		$('#discharge_edit').textbox('setValue',rows.PORT_CODE+'-'+rows.PORT);
	            	}
	            	$('#dlg_port').dialog('close');
				}
			});
			$('#dg_port').datagrid('enableFilter');
		}

		function sett_port_dest(value){
			$('#dlg_port').dialog('open').dialog('setTitle','SETT PORT');
			$('#dg_port').datagrid('load',{sch: ''});
			$('#dg_port').datagrid({
				url: '_getPort.php',
				fitColumns: true,
				columns:[[
					{field:'PORT_CODE',title:'CODE',width:65,halign:'center', align:'center'},
	                {field:'PORT',title:'NAME',width:200,halign:'center'}
	            ]],
	            onClickRow:function(id,row){
	            	var rows = $('#dg_port').datagrid('getSelected');
	            	if(value=='add'){
		            	$('#destination_add').textbox('setValue',rows.PORT_CODE+'-'+rows.PORT);
		            }else if(value == 'edit'){
		            	$('#destination_edit').textbox('setValue',rows.PORT_CODE+'-'+rows.PORT);
		            }
	            	$('#dlg_port').dialog('close');
				}
			});
			$('#dg_port').datagrid('enableFilter');
		}

		function sett_notify(value){
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
	                {field:'COUNTRY',title:'COUNTRY',width:100,halign:'center'}
	            ]],
	            onClickRow:function(id,row){
	            	var rows = $('#dg_Notify').datagrid('getSelected');
	            	if(value=='add'){
		            	$('#notify_add').textbox('setValue', rows.NOTIFY);
		            }else if(value == 'edit'){
		            	$('#notify_edit').textbox('setValue', rows.NOTIFY);
		            }
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

		function sett_rmk(value){
			$('#dlg_remark').dialog('open').dialog('setTitle','Master Remark');
			$('#dg_remark').datagrid({
				url: '_getRemark_add.php?type=DO',
				fitColumns: true,
				columns:[[
	                {field:'REMARK_TYPE',title:'TYPE',width:65,halign:'center', align:'center'},
	                {field:'REMARK_DESCRIPTION',title:'DESCRIPTION',width:300,halign:'center'}
	            ]],
	            onClickRow:function(id,row){
	            	var ids = [];
					var rows = $('#dg_remark').datagrid('getSelections');
					for(var i=0; i<rows.length; i++){
						ids.push(rows[i].REMARK_DESCRIPTION+"\n");
					}
					if(value=='add'){
						$('#remark_add').textbox('setValue',ids.join("\n"));	
					}else if(value == 'edit'){
						$('#remark_edit').textbox('setValue',ids.join("\n"));	
					}
	            	$('#dlg_remark').dialog('close');
				}
			});
		}

		var idsPart = [];	var plt=0;	var cart=0;

		function sett_part_SO(value){
			if (value == 'add'){
				plt  = 0;
				cart = 0;
				var cu_add = $('#customer_add').textbox('getValue');
				var si_no = $('#sino_add').combogrid('getValue');
				if (cu_add == ''){
					$.messager.alert("INFORMATION",'Customer not selected','info');
				}else if(si_no == ''){
					$.messager.alert("INFORMATION",'SI No. not selected','info');
				}else{
					$('#dlg_part_SO').dialog('open').dialog('setTitle','ADD PART SO');
					$('#dg_part_SO').datagrid({
						url: 'invoice_get_part_so.php?cust='+cu_add+'&si='+si_no,
						rownumbers: true,
						fitColumns: true,
						columns:[[
							{field:'CUSTOMER_PART_NO',title:'CUSTOMER<br/>PART NO.',width:65,halign:'center', align:'center'},
			                {field:'DESCRIPTION',title:'DESCRIPTION',width:200,halign:'center'},
			                {field:'STK_QTY',title:'STOCK',width:80,halign:'center', align:'right'},
			                {field:'SO_NO',title:'SO NO.',width:80,halign:'center'},
			                {field:'LINE_NO',title:'LINE<br/>NO.',width:50,halign:'center', align:'center'},
			                {field:'CUSTOMER_PO_NO',title:'PO NO.',width:80,halign:'center'},
			                {field:'ANSWER_NO',title:'REF NO.',width:80,halign:'center'},
			                {field:'DELIVERY_QTY',title:'QTY',width:80,halign:'center', align:'right'},
			                {field:'U_PRICE',title:'PRICE',width:80,halign:'center', align:'right'},
			                {field:'WORK_NO',title:'WORK ORDER NO.',width:180,halign:'center'},
			                {field:'DATE_CODE',title:'DATE<br/>CODE',width:80,halign:'center', align:'center'}
			            ]],
			            onClickRow:function(id,row){
			            	var t = $('#dg_add').datagrid('getRows');var total = t.length;
			            	var idxfield = 0;var count = 0;

			            	if(parseInt(total)==0){
			            		idxfield = total;
			            	}else{
			            		idxfield = total;
			            		for(i=0; i<total; i++){
			            			var ans_no = $('#dg_add').datagrid('getData').rows[i].ANSWER_NO;
			            			if(ans_no == row.ANSWER_NO){
			            				count++;

			            			}
			            		}
			            	}

			            	if (count>0) {
								$.messager.alert('Warning','data already exists','warning');
								
							}else{
								var ans = "'"+row.ANSWER_NO+"'";
								var n = "'"+value+"'";
								$('#dg_add').datagrid('insertRow',{
									index: idxfield,
									row: {
										CUSTOMER_PART_NO: row.CUSTOMER_PART_NO,
										DESCRIPTION: row.DESCRIPTION,
										SO_NO: row.SO_NO,
										LINE_NO: row.LINE_NO,
										CUSTOMER_PO_NO: row.CUSTOMER_PO_NO,
										ANSWER_NO: row.ANSWER_NO,
										DELIVERY_QTY: row.DELIVERY_QTY,
										UOM_Q: row.UOM_Q,
										U_PRICE: row.U_PRICE,
										DATE_CODE: row.DATE_CODE,
										ORIGIN_CODE: row.ORIGIN_CODE,
										//REMARK_SHIPPING: row.REMARK_SHIPPING,
										SHIPPING_SET: '<a href="javascript:void(0)" onclick="sett_shipping_mark('+n+','+ans+','+idxfield+')">SET</a>'
									}
								});

								plt += Math.ceil(row.PALLET);
								cart += Math.ceil(row.CARTON);

								//var rows = $('#dg_part_SO').datagrid('getSelections');
								//for(var i=0; i<rows.length; i++){
										//if (i == rows.length-1){
										//	plt += parseFloat(rows[i].PALLET);
										//	cart += parseFloat(rows[i].CARTON);
										//}
										//alert(i);

										
										//alert(plt+'/'+cart);
										//idsPart.push("CARTON QTY = "+cart+" CARTONS\nPALLET QTY = "+plt+" PALLETS");
										//var rmk = $('#remark_add').textbox('getValue');
										//alert (ids.length);
										//var rmk_new = rmk+'\n'+ids;
										//$('#remark_add').textbox('setValue',rmk_new);
									//}
								//}
							}
						}
					});

					$('#dg_part_SO').datagrid('enableFilter');
				}
			}else if (value == 'edit'){
				var cu_edit = $('#customer_edit').textbox('getValue');
				var si_no_edit = $('#sino_edit').combogrid('getValue');
				if (cu_edit == ''){
					$.messager.alert("INFORMATION",'Customer not selected','info');
				}else{
					$('#dlg_part_SO').dialog('open').dialog('setTitle','ADD PART SO');
					$('#dg_part_SO').datagrid({
						url: 'invoice_get_part_so.php?cust='+cu_edit+'&si='+si_no_edit,
						rownumbers: true,
						fitColumns: true,
						columns:[[
							{field:'CUSTOMER_PART_NO',title:'CUSTOMER<br/>PART NO.',width:65,halign:'center', align:'center'},
			                {field:'DESCRIPTION',title:'DESCRIPTION',width:200,halign:'center'},
			                {field:'STK_QTY',title:'STOCK',width:80,halign:'center', align:'right'},
			                {field:'SO_NO',title:'SO NO.',width:80,halign:'center'},
			                {field:'LINE_NO',title:'LINE<br/>NO.',width:50,halign:'center', align:'center'},
			                {field:'CUSTOMER_PO_NO',title:'PO NO.',width:80,halign:'center'},
			                {field:'ANSWER_NO',title:'REF NO.',width:80,halign:'center'},
			                {field:'DELIVERY_QTY',title:'QTY',width:80,halign:'center', align:'right'},
			                {field:'U_PRICE',title:'PRICE',width:80,halign:'center', align:'right'},
			                {field:'DATE_CODE',title:'DATE<br/>CODE',width:80,halign:'center', align:'center'}
			            ]],
			            onClickRow:function(id,row){
			            	var t = $('#dg_edit').datagrid('getRows');var total = t.length;
			            	var idxfield = 0;var count = 0;

			            	if(parseInt(total)==0){
			            		idxfield = total;
			            	}else{
			            		idxfield = total;
			            		for(i=0; i<total; i++){
			            			var ans_no = $('#dg_edit').datagrid('getData').rows[i].ANSWER_NO;
			            			if(ans_no == row.ANSWER_NO){
			            				count++;
			            				
			            			}
			            		}
			            	}

			            	if (count>0) {
								$.messager.alert('Warning','data already exists','warning');
							}else{
								var ans = "'"+row.ANSWER_NO+"'";
								var n = "'"+value+"'";
								$('#dg_edit').datagrid('insertRow',{
									index: idxfield,
									row: {
										CUSTOMER_PART_NO: row.CUSTOMER_PART_NO,
										DESCRIPTION: row.DESCRIPTION,
										SO_NO: row.SO_NO,
										LINE_NO: row.LINE_NO,
										CUSTOMER_PO_NO: row.CUSTOMER_PO_NO,
										ANSWER_NO: row.ANSWER_NO,
										DELIVERY_QTY: row.DELIVERY_QTY,
										UOM_Q: row.UOM_Q,
										U_PRICE: row.U_PRICE,
										DATE_CODE: row.DATE_CODE,
										ORIGIN_CODE: row.ORIGIN_CODE,
										//REMARK_SHIPPING: row.REMARK_SHIPPING,
										SHIPPING_SET: '<a href="javascript:void(0)" onclick="sett_shipping_mark('+n+','+ans+','+idxfield+')">SET</a>'
									}
								});

								var ids = [];
								var rows = $('#dg_part_SO').datagrid('getSelections');
								for(var i=0; i<rows.length; i++){
									if (rows.length==1){
										ids.push("CARTON QTY = "+rows[i].CARTON+" CARTONS\nPALLET QTY = "+rows[i].PALLET+" PALLETS");
										cart = parseInt(rows[i].CARTON);
										var rmk = $('#remark_edit').textbox('getValue');
										var rmk_new = rmk+'\n'+ids;
				            			$('#remark_edit').textbox('setValue',rmk_new);
									}
								}
							}
						}
					});
					$('#dg_part_SO').datagrid('enableFilter');
				}
			}
		}

		var remark = '';
		var flag_remark = 0;
		// 201808002127
		function select_part(){
			// alert(remark);
			var rmk = $('#remark_add').textbox('getValue');
			var rmk2 = "CARTON QTY = "+Math.ceil(cart)+" CARTONS\nPALLET QTY = "+Math.ceil(plt)+" PALLETS";

			if (flag_remark==0){
				remark = rmk;
			};
			flag_remark=1;

			idsPart.push("CARTON QTY = "+cart+" CARTONS\nPALLET QTY = "+plt+" PALLETS");
			//alert (idsPart);
			var rmk_new = remark+'\n'+rmk2;
			$('#remark_add').textbox('setValue',rmk_new);
			$('#dlg_part_SO').dialog('close');
			$('#savebtn').linkbutton('enable');

			remark = '';
			flag_remark = 0;
		}

		function sett_shipping_mark(a,b,c){
			$('#dlg_shipping_mark').dialog('open').dialog('setTitle','SHIPPING MARK SETTING');
			$('#kode_shipping_mark').textbox('setValue',a+'/'+b+'/'+c);
			$('#shipping_mark_result').textbox('setValue','');
			$('#dg_shipping_mark').datagrid({
				url: 'invoice_get_shipping_mark.php?answer='+b,
				rownumbers: true,
				fitColumns: true,
				columns:[[
					{field:'NO', hidden: true},
					{field:'ck', checkbox:true, width:30, halign: 'center'},
		            {field:'HASIL',title:'DESCRIPTION',width:200,halign:'center'}
		        ]],
		        onClickRow:function(id,row){
	            	var arrS = [];	var arrH = [];
					var rows = $('#dg_shipping_mark').datagrid('getSelections');
					for(var i=0; i<rows.length; i++){
						arrS.push(rows[i].HASIL+"\n");
					}
	            	$('#shipping_mark_result').textbox('setValue',arrS.join(""));
				}
		    });
		}

		function save_sett_ship_mark(){
	        var result = ($('#shipping_mark_result').textbox('getValue'));
	        var rslt = result.replace(/\n/gi,"<br>");
	        if(rslt.substr(rslt.length-4) == '<br>'){
	        	var rsl = rslt.substr(0,rslt.length-4);
	        }else{
	        	var rsl = rslt;
	        }
	        
	        var code = ($('#kode_shipping_mark').textbox('getValue'));
	        var res = code.split("/");

	        //alert(rsl);
	        if (res[0] == 'add'){
	        	$('#dg_add').datagrid('updateRow',{
					index: res[2],
					row: {
						REMARK_SHIPPING: rsl
					}
				});
	        }else if (res[0] == 'edit'){
				$('#dg_edit').datagrid('updateRow',{
					index: res[2],
					row: {
						REMARK_SHIPPING: rsl
					}
				});
	        }
	        
			$('#dlg_shipping_mark').dialog('close')
		}

		function save_sett_transport(){
			var ids = [];	var m=0;	var s=0;	var q=0;
			var t = $('#dg_transport').datagrid('getRows');
			var total = t.length;
			for(i=0;i<total;i++){
				$('#dg_transport').datagrid('endEdit',i);
				
				if(typeof $('#dg_transport').datagrid('getData').rows[i].METHOD == 'undefined' || $('#dg_transport').datagrid('getData').rows[i].METHOD == ''){
					m = 0;
				}else{
					m = $('#dg_transport').datagrid('getData').rows[i].METHOD;
				}

				if(typeof $('#dg_transport').datagrid('getData').rows[i].SIZE == 'undefined' || $('#dg_transport').datagrid('getData').rows[i].SIZE == ''){
					s = 0;
				}else{
					s = $('#dg_transport').datagrid('getData').rows[i].SIZE;
				}

				if(typeof $('#dg_transport').datagrid('getData').rows[i].QTY == 'undefined' || $('#dg_transport').datagrid('getData').rows[i].QTY == ''){
					q = 0;
				}else{
					q = $('#dg_transport').datagrid('getData').rows[i].QTY;
				}

				ids.push(m+'-'+s+'-'+q);

				if (q !== 0){
					var ids_ship = [];
					var rmk_ship = $('#remark_add').textbox('getValue');
					if (m != 0 || s != 0 || q != 0){
						if(m == 1){
							ids_ship.push("LCL SHIPMENT");
						}else{
							if (s == 1){
								ids_ship.push("FCL "+q+"X20 SHIPMENT");
							}else{
								ids_ship.push("FCL "+q+"X40 SHIPMENT");
							}
						}
						
						var rmk_new_ship = rmk_ship+'\n'+ids_ship;
						$('#remark_add').textbox('setValue',rmk_new_ship);
					}
				}
			}
			$('#transport1_add').textbox('setValue',ids[0]);
			$('#transport2_add').textbox('setValue',ids[1]);
			$('#dlg_transport').dialog('close');
		}

	</script>
	</body>
    </html>
<?php }else{	
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}