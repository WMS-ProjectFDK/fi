<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Purchase Order From MRP</title>
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

<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:500px;border-radius:4px;height:100px;"><legend><span class="style3"><strong> PURCHASE REQUESTION FILTER </strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PRF Date</span>
				<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to 
				<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				<label><input type="checkbox" name="ck_prf_date" id="ck_prf_date" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PRF No.</span>
				<select style="width:192px;" name="cmb_prf_no" id="cmb_prf_no" class="easyui-combobox" data-options=" url:'../json/json_prf_no.php',method:'get',valueField:'prf_no',textField:'prf_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_prf" id="ck_prf" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Item No.</span>
				<!-- <select style="width:190px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'#', method:'get', valueField:'#', textField:'#', panelHeight:'100px'"></select> -->
				<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:525px;border-radius:4px;width: 540px;height:100px;"><legend><span class="style3"><strong> SELECT SUPPLIER </strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Supplier</span>
			<select style="width:370px;" name="cmb_supp" id="cmb_supp" class="easyui-combobox" data-options=" url:'../json/json_company.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px', 
			onSelect: function(rec){
				filterData();
			}" required=""></select>
		</div>
	</fieldset>
	<fieldset style="margin-left: 1090px;border-radius:4px;height:100px;"><legend><span class="style3"><strong>ACTION</strong></span></legend>
		<!-- <div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 150px;height: 35px;" id="checkmaterial" class="easyui-linkbutton c2" onClick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
		</div> -->
		<div class="fitem" align="center" valign="midle">
			<a href="javascript:void(0)" style="width: 150px;height: 35px;" id="create_po" class="easyui-linkbutton c2" onClick="create_po()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Create PO</a>
		</div>
	</fieldset>
</div>

<!-- ADD -->
<div id='dlg_add' class="easyui-dialog" style="width:1100px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend> Select Supplier </legend>
		<div class="fitem">
			<span style="width:60px;display:inline-block;">Supplier</span>
			<select style="width:310px;" name="supplier_add" id="supplier_add" class="easyui-combobox" data-options=" url:'../json/json_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
			onSelect: function(rec){
				$.ajax({
					type: 'GET',
					url: '../json/json_company_details.php?id='+rec.company_code,
					data: { kode:'kode' },
					success: function(data){
						$('#country_add').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].COUNTRY);
						$('#payterms_add').textbox('setValue',data[0].PDAYS+'-'+data[0].PDESC);
						$('#attn_add').textbox('setValue',data[0].ATTN);
						$('#curr_add').combobox('setValue',data[0].CURR_CODE);
						$('#rate_add').textbox('setValue',data[0].x_rate);
						$('#ship_add').combobox('setValue',data[0].SHIPTO_CODE);
						$('#trade_add').textbox('setValue',data[0].TTERM);
						$('#rate_add').textbox('setValue',data[0].EX_RATE);
						$('#comment').textbox('setValue','*): LATEST PO: '+data[0].PO_NO+' ('+data[0].PO_DATE+')');
					}
				})
			}" required="" ></select>
			<span style="width:10px;display:inline-block;"></span>
			<span style="width:100px;display:inline-block;">Payment Terms</span>
			<input style="width:360px;" name="payterms_add" id="payterms_add" class="easyui-textbox" disabled="" />
			<input style="width:160px;" name="country_add" id="country_add" class="easyui-textbox" disabled="" />
		</div>
		<div class="fitem">
			<span style="width:60px;display:inline-block;">Currency</span>
			<input style="width:85px;" id="curr_add" class="easyui-combobox" data-options=" url:'../json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'100px',
        	onSelect: function(rec){
        		$.ajax({
        			type: 'GET',
					url: '../json/json_exrate.php?curr='+rec.idcrc,
					data: { kode:'kode' },
					success: function(data){
						$('#rate_add').textbox('setValue',data[0].RATE);	
					}
        		});
        	}" required="" />
        	<span style="width:14px;display:inline-block;"></span>
			<span style="width:60px;display:inline-block;">Ex. Rate</span>
			<input style="width:140px;" name="rate_add" id="rate_add" class="easyui-textbox" disabled="" />
			<span style="width:10px;display:inline-block;"></span>
			<span style="width:50px;display:inline-block;">ATTN</span>
			<input style="width:140px;" name="attn_add" id="attn_add" class="easyui-textbox"/>
			<span style="width:50px;display:inline-block;">Ship To</span>
			<select style="width:185px;" name="ship_add" id="ship_add" class="easyui-combobox" data-options=" url:'../json/json_ship_to.php', method:'get', valueField:'com_code', textField:'com_name', panelHeight:'150px'"></select>
			<span style="width:40px;display:inline-block;">Trade</span>
			<input style="width:142px;" name="trade_add" id="trade_add" class="easyui-textbox"/>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend>Create PO</legend>
		<div style="width:395px; height: 80px; float:left;">	
			<div class="fitem">
				<input style="width:375px;color: red" name="comment" id="comment" class="easyui-textbox" disabled="" />
			</div>
			<div class="fitem">
				<span style="width:50px;display:inline-block;">PO No.</span>
				<input style="width:163px;" name="po_no_add" id="po_no_add" class="easyui-textbox" required=""/>
				<span style="width:5px;display:inline-block;"></span>
				<span style="width:55px;display:inline-block;">PO Date</span>
				<input style="width:85px;" name="po_date_add" id="po_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
			</div>
			<div class="fitem">
				<span style="width:50px;display:inline-block;">DI Type</span>
				<select style="width:75px;" name="di_type_add" id="di_type_add" class="easyui-combobox" data-options="panelHeight:'40px'" required="">
					<option value="0" selected="">No</option>
					<option value="1">Yes</option>
				</select>
				<span style="width:88px;display:inline-block;"></span>
				<span style="width:60px;display:inline-block;">Transport</span>
				<select style="width:85px;" name="trans_add" id="trans_add" class="easyui-combobox" data-options="panelHeight:'70px'" required="">
					<option selected="" value=""></option>
					<option value="1">AIR</option>
					<option value="2">OCEAN</option>
					<option value="3">TRUCK</option>
				</select>
			</div>
		</div>
		<div>
			<div class="fitem">
				<span style="width: 310px;display:inline-block;">Remark <a href="javascript:void(0)" onclick="sett_rmk_add()">SET</a></span>
				<span style="width: 310px;display:inline-block;">Shipping Mark</span>
			</div>
			<div class="fitem">
				<input style="width: 310px; height: 56px;" name="remark_add" id="remark_add"  multiline="true" class="easyui-textbox"/>
				<input style="width: 310px; height: 56px;" name="shipp_mark_add" id="shipp_mark_add"  multiline="true" class="easyui-textbox"/>
			</div>
		</div>
	</fieldset>
	<div style="clear:both;margin-bottom:10px;"></div>
	<table align="center" id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:1075px;height:235px;padding:10px 10px; margin:5px;"></table>
	<div id="toolbar_add" style="padding: 5px 5px;">
		<!-- <a href="javascript:void(0)" id="search_prf" iconCls="icon-search" class="easyui-linkbutton" onclick="searchPRF()">ADD ITEM FROM PRF</a>
		<a href="javascript:void(0)" id="add_po_add" iconCls='icon-add' class="easyui-linkbutton" onclick="add_item_add()">ADD ITEM</a>
		<a href="javascript:void(0)" id="remove_po_add" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_po_add()">REMOVE ITEM</a> -->
		<a href="javascript:void(0)" id="add_parsial" iconCls='icon-add' class="easyui-linkbutton" onclick="add_parsial()">ADD PARSIAL</a>
		<a href="javascript:void(0)" id="remove_parsial" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_parsial()">REMOVE PARSIAL</a>
	</div>
	<div id="dlg_remark_add" class="easyui-dialog" style="width: 450px;height: 250px;" closed="true" data-options="modal:true">
		<table id="dg_remark_add" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true"></table>
	</div>
</div>
<div id="dlg-buttons-add">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePo()" style="width:90px">Save</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close'); filterData();clear_addPO();" style="width:90px">Cancel</a>
</div>
<!-- END ADD -->

<table id="dg" title="Purchase Order From MRP" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" selectOnCheck= "true"></table>

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
		$('#date_awal').datebox('disable');
		$('#date_akhir').datebox('disable');
		$('#ck_prf_date').change(function(){
			if ($(this).is(':checked')) {
				$('#date_awal').datebox('disable');
				$('#date_akhir').datebox('disable');
			}else{
				$('#date_awal').datebox('enable');
				$('#date_akhir').datebox('enable');
			}
		});


		$('#cmb_prf_no').combobox('disable');
		$('#ck_prf').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_prf_no').combobox('disable');
			}else{
				$('#cmb_prf_no').combobox('enable');
			}
		});

		$('#cmb_item_no').combobox('disable');
		$('#ck_item_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_item_no').combobox('disable');
			}else{
				$('#cmb_item_no').combobox('enable');
			}
		});

		$('#create_po').linkbutton('disable');

		$('#dg_add').datagrid({
		    singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'ITEM_NO', title:'ITEM NO.', width:65, halign: 'center', align: 'center'},
			    {field:'ITEM', title:'ITEM NAME', width:100, halign: 'center'},//, hidden: true},
			    {field:'DESCRIPTION', title:'DESCRIPTION', width: 190, halign: 'center'},
			    {field:'ITEM_FULL', title:'ITEM', width: 150, halign: 'center', hidden: true},//},
			    {field:'UNIT', title:'UoM', halign: 'center', width:45, align:'center'},
			    {field:'COUNTRY', title:'ORIGN', halign: 'center', width:80,},
			    {field:'QTY_BANDING', editor:{type:'numberbox',options:{required:true,precision:0,groupSeparator:','}}, hidden: true},
			    {field:'QTY', title:'QTY', align:'right', halign: 'center', width:70, editor:{type:'numberbox',options:{required:true,precision:0,groupSeparator:','}}},
			    {field:'ESTIMATE_PRICE', title:'PRICE', halign: 'center', width:80, align:'right', editor:{type:'textbox',options:{required:true,precision:0,groupSeparator:','}}},
			    {field:'CURR_SHORT', title:'CURR', halign: 'center', width:60, editor:{type:'combobox',options: {
			    																									required:true,
			    																									url: '../json/json_currency.php',
			    																									panelHeight: '100px',
																								                    valueField: 'idcrc',
																								                    textField: 'nmcrc'
			    																								}
			    																	  }
			    },
			    {field:'ETA_DATE', title: 'E.T.A FI', halign: 'center', width: 90, editor:{
			    																	type:'datebox',
			    																	options:{formatter:myformatter,parser:myparser}
			    																}
			    },
			    {field:'OHSAS', title:'DATE CODE', halign: 'center', width:100, align:'center', editor:{
			    																					type:'textbox'
			    																				}
			    },
			    {field:'PRF_NOMOR', title:'PRF NO', halign: 'center', width:100, align:'center'},
			    {field:'PRF_NO', hidden: true},//
			    {field:'PRF_LINE_NO', title:'PRF<br/>LINE NO', halign: 'center', width:60, align:'center'},
			    {field:'ORIGIN_CODE', hidden:true},//
			    {field:'UOM_Q', hidden:true}//
		    ]],
		    onClickRow:function(row){
		    	$(this).datagrid('beginEdit', row);
		    },
		    onBeginEdit:function(rowIndex){
		        var editors = $('#dg_add').datagrid('getEditors', rowIndex);
		        var n1 = $(editors[0].target);
		        var n2 = $(editors[1].target);
		        n1.add(n2).numberbox({
		            onChange:function(){
		                /*if(n2.numberbox('getValue') > n1.numberbox('getValue')){
							$.messager.confirm('Confirm','actual value over',function(r){
								if(r){
									n2.numberbox('setValue', n1.numberbox('getValue'));
								}else{
									n2.numberbox('setValue', n1.numberbox('getValue'));
								}		
							});
		                }*/
		            }
		        })
		    }
		});

		filterData();
	});


	function filterData(){
		var ck_prf_date = "false";
		var ck_prf = "false";
		var ck_item_no = "false";

		if ($('#ck_prf_date').attr("checked")) {
			ck_prf_date = "true";
		};

		if ($('#ck_prf').attr("checked")) {
			ck_prf = "true";
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
		};

		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			ck_prf_date: ck_prf_date,
			cmb_prf_no : $('#cmb_prf_no').combobox('getValue'),
			ck_prf: ck_prf,
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no,
			cmb_supp : $('#cmb_supp').combobox('getValue')
		});

		$('#dg').datagrid( {
			url: 'po_by_MRP_get.php',
			columns:[[
		    	{field:'ck', align:'center', width:30, title:'', halign: 'center',editor:{type:'checkbox',options:{on:'TRUE',off:'FALSE'}}},
			    {field:'PRF_NO',title:'PRF NO.', halign:'center', width:150},
			    {field:'PRF_LINE_NO',title:'PRF<br/>LINE NO.', halign:'center', align:'center', width:45},
                {field:'PRF_DATE', title:'PRF DATE', halign:'center', width:50},
                {field:'DESCRIPTION_FULL', title:'ITEM<br/>DESCRIPTION', halign:'center', width:220},
                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:40},
                {field:'QTY', title:'QTY', halign:'center', align:'right', width:50},
                {field:'REMAINDER_QTY', title:'OUTSTANDING<br/>PRF', halign:'center', align:'right', width:70},
                {field:'ESTIMATE_PRICE', title:'ESTIMATE<br/>PRICE', halign:'center', align:'right', width:80},
                {field:'AMT', title:'AMOUNT', halign:'center', align:'right', width:80},
                {field:'ETA_DATE', title:'REQUIRE<br/>DATE', halign:'center', align:'center', width:70}
			]],
			onLoadSuccess: function (data) {
				for (i=0; i<data.rows.length; i++) {
                    $(this).datagrid('beginEdit',i);
                }
			}
		})
		$('#dg').datagrid('enableFilter');
		$('#create_po').linkbutton('enable');
	}

	function create_po(){
		clear_addPO();
		var supp  = $('#cmb_supp').combobox('getValue');
		if(supp == ''){
			$.messager.alert('WARNING','Please Select Supplier..','warning');
		}else{
			var t = $('#dg').datagrid('getRows');
			var total = t.length;
			var t_true = 0;
			var q_tot = 0;
			var idxfield=0;
			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg').datagrid('endEdit',i);
				if($('#dg').datagrid('getData').rows[i].ck=="TRUE"){
					$('#dlg_add').dialog('open').dialog('setTitle','Create Purchase Order From MRP');
					$('#supplier_add').combobox('setValue',$('#cmb_supp').combobox('getValue'));
					$('#supplier_add').combobox('disable');
					$.ajax({
						type: 'GET',
						url: '../json/json_company_details.php?id='+$('#cmb_supp').combobox('getValue'),
						data: { kode:'kode' },
						success: function(data){
							$('#country_add').textbox('setValue',data[0].COUNTRY_CODE+'-'+data[0].COUNTRY);
							$('#payterms_add').textbox('setValue',data[0].PDAYS+'-'+data[0].PDESC);
							$('#attn_add').textbox('setValue',data[0].ATTN);
							$('#curr_add').combobox('setValue',data[0].CURR_CODE);
							$('#rate_add').textbox('setValue',data[0].x_rate);
							$('#ship_add').combobox('setValue',data[0].SHIPTO_CODE);
							$('#trade_add').textbox('setValue',data[0].TTERM);
							$('#rate_add').textbox('setValue',data[0].EX_RATE);
							$('#comment').textbox('setValue','*): LATEST PO: '+data[0].PO_NO+' ('+data[0].PO_DATE+')');
						}
					})

					q_tot += parseFloat($('#dg').datagrid('getData').rows[i].REMAINDER_QTY);

					$('#dg_add').datagrid('insertRow',{
						index: idxfield,
						row: {
							ITEM_NO: $('#dg').datagrid('getData').rows[i].ITEM_NO,
							DESCRIPTION: $('#dg').datagrid('getData').rows[i].DESCRIPTION,
							ITEM: $('#dg').datagrid('getData').rows[i].ITEM,
							UOM_Q: $('#dg').datagrid('getData').rows[i].UOM_Q,
							UNIT: $('#dg').datagrid('getData').rows[i].UNIT,
							ORIGIN_CODE: $('#dg').datagrid('getData').rows[i].ORIGIN_CODE,
							COUNTRY: $('#dg').datagrid('getData').rows[i].COUNTRY,
							CURR_CODE: $('#dg').datagrid('getData').rows[i].CURR_CODE,
							CURR_SHORT: $('#dg').datagrid('getData').rows[i].CURR_SHORT,
							ESTIMATE_PRICE: $('#dg').datagrid('getData').rows[i].ESTIMATE_PRICE,
							PRF_NO: $('#dg').datagrid('getData').rows[i].PRF_NO,
							PRF_NOMOR: $('#dg').datagrid('getData').rows[i].PRF_NO,
							QTY_BANDING: $('#dg').datagrid('getData').rows[i].REMAINDER_QTY.replace(/,/g,''),
							QTY: $('#dg').datagrid('getData').rows[i].REMAINDER_QTY.replace(/,/g,''),
							ETA_DATE: $('#dg').datagrid('getData').rows[i].ETA_DATE,
							PRF_LINE_NO: $('#dg').datagrid('getData').rows[i].PRF_LINE_NO,
							OHSAS: $('#dg').datagrid('getData').rows[i].OHSAS
						}
					});
					t_true++;
					idxfield++;
				}
			}

			if(t_true == 0){
				$.messager.alert('WARNING','Please Select PRF','warning');
				filterData();
			}
			
		}
	}

	function clear_addPO(){
		$('#add_po_add').linkbutton('disable');
		$('#remove_po_add').linkbutton('disable');
		$('#search_prf').linkbutton('disable');
		$('#supplier_add').combobox('setValue','');
		$('#payterms_add').textbox('setValue','');
		$('#country_add').textbox('setValue','');
		$('#ship_add').textbox('setValue','');
		$('#attn_add').textbox('setValue','');
		$('#trade_add').textbox('setValue','');
		$('#curr_add').textbox('setValue','');
		$('#rate_add').textbox('setValue','');
		$('#comment').textbox('setValue','');
		$('#po_no_add').textbox('setValue','');
		$('#trans_add').combobox('setValue','');
		$('#remark_add').textbox('setValue','');
		$('#shipp_mark_add').textbox('setValue','');
		$('#dg_add').datagrid('loadData',[]);
	}

	function sett_rmk_add(){
		$('#dlg_remark_add').dialog('open').dialog('setTitle','Master Remark');
		$('#dg_remark_add').datagrid({
			url: 'po_getRemark_add.php',
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
				//alert(ids.join('\n'));
            	$('#remark_add').textbox('setValue',ids.join("\n"));
			}
		});
	}

	function add_parsial(){
		var row = $('#dg_add').datagrid('getSelected');
		if(row){
			//alert(row.PRF_NOMOR);
			var indx = $('#dg_add').datagrid('getRowIndex',row);
			var idx = $('#dg_add').datagrid('getRowIndex', row)+1;
			var stngah = parseFloat(row.QTY.replace(/,/g,''))/2;
			$('#dg_add').datagrid('insertRow',{
				index: idx,
				row: {
					ITEM_NO: row.ITEM_NO,
					DESCRIPTION: row.DESCRIPTION,
					ITEM: row.ITEM,
					UOM_Q: row.UOM_Q,
					UNIT: row.UNIT,
					ORIGIN_CODE: row.ORIGIN_CODE,
					COUNTRY: row.COUNTRY,
					CURR_CODE: row.CURR_CODE,
					CURR_SHORT: row.CURR_SHORT,
					ESTIMATE_PRICE: row.ESTIMATE_PRICE,
					PRF_NO: row.PRF_NO,
					PRF_NOMOR: row.PRF_NOMOR,
					QTY: stngah,
					ETA_DATE: row.ETA_DATE,
					PRF_LINE_NO: row.PRF_LINE_NO,
					OHSAS: row.OHSAS
				}
			});

			var ed = $('#dg_add').datagrid('getEditor', {index: indx,field:'QTY'});
			$(ed.target).numberbox('setValue', stngah);
		}
	}

	function remove_parsial(){
		var row = $('#dg_add').datagrid('getSelected');	
		if (row){
			$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
				if(r){
					var idx = $("#dg_add").datagrid("getRowIndex", row);
					$('#dg_add').datagrid('deleteRow', idx);

				}	
			});
		}
	}

	function simpan(){
		if($('#supplier_add').combobox('getValue')==''){
			$.messager.alert('Warning','Please select supplier','warning');
		}else if($('#po_no_add').textbox('getValue')==''){
			$.messager.alert('INFORMATION','PO No. Not Found','info');
		}else{
			var dataRows = [];
			var tot_amt_o = 0;
			var tot_amt_l = 0;
			var t = $('#dg_add').datagrid('getRows');
			var total = t.length;
			var jmrow=0;
			for(i=0;i<total;i++){
				var amt_o = 0;
				var amt_l = 0;
				jmrow = i+1;
				$('#dg_add').datagrid('endEdit',i);

				dataRows.push({
					v_from: jmrow,
					v_to: total,
					po_sts: 'DETAILS',
					po_supp: $('#supplier_add').combobox('getValue'),
					po_pterm: $('#payterms_add').textbox('getValue'),
					po_curr: $('#curr_add').combobox('getValue'),
					po_rate: $('#rate_add').textbox('getValue'),
					po_country: $('#country_add').textbox('getValue'),
					po_attn: $('#attn_add').textbox('getValue'),
					po_shipto: $('#ship_add').combobox('getValue'),
					po_tterm: $('#trade_add').textbox('getValue'),
					po_no: $('#po_no_add').textbox('getValue'),
					po_line: jmrow,
					po_date: $('#po_date_add').datebox('getValue'),
					po_di_type: $('#di_type_add').combobox('getValue'),
					po_trans: $('#trans_add').combobox('getValue'),
					po_remark: $('#remark_add').textbox('getValue'),
					po_ship_mark: $('#shipp_mark_add').textbox('getValue'),
					po_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
					po_unit: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
					po_orign: $('#dg_add').datagrid('getData').rows[i].ORIGIN_CODE,
					po_price: $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE,
					po_curr_item: $('#dg_add').datagrid('getData').rows[i].CURR_SHORT,
					po_qty: $('#dg_add').datagrid('getData').rows[i].QTY,
					po_eta: $('#dg_add').datagrid('getData').rows[i].ETA_DATE,
					po_prf: $('#dg_add').datagrid('getData').rows[i].PRF_NOMOR,
					po_prf_line: $('#dg_add').datagrid('getData').rows[i].PRF_LINE_NO,
					po_dt_code: $('#dg_add').datagrid('getData').rows[i].OHSAS,
					amt_o: parseFloat($('#dg_add').datagrid('getData').rows[i].QTY * $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE).toFixed(2),
					amt_l: parseFloat($('#dg_add').datagrid('getData').rows[i].QTY * $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE * 
						   $('#rate_add').textbox('getValue')).toFixed(2)

				});

				amt_o = parseFloat($('#dg_add').datagrid('getData').rows[i].QTY * $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE).toFixed(2);
				amt_l = parseFloat($('#dg_add').datagrid('getData').rows[i].QTY * $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE * 
						$('#rate_add').textbox('getValue')).toFixed(2);

				tot_amt_o += parseFloat(amt_o);
				tot_amt_l += parseFloat(amt_l);

				if(i==total-1){
					dataRows.push({
						po_sts: 'HEADER',
						po_supp: $('#supplier_add').combobox('getValue'),
						po_pterm: $('#payterms_add').textbox('getValue'),
						po_curr: $('#curr_add').combobox('getValue'),
						po_rate: $('#rate_add').textbox('getValue'),
						po_country: $('#country_add').textbox('getValue'),
						po_attn: $('#attn_add').textbox('getValue'),
						po_shipto: $('#ship_add').combobox('getValue'),
						po_tterm: $('#trade_add').textbox('getValue'),
						po_no: $('#po_no_add').textbox('getValue'),
						po_line: jmrow,
						po_date: $('#po_date_add').datebox('getValue'),
						po_di_type: $('#di_type_add').combobox('getValue'),
						po_trans: $('#trans_add').combobox('getValue'),
						po_remark: $('#remark_add').textbox('getValue'),
						po_ship_mark: $('#shipp_mark_add').textbox('getValue'),
						po_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
						po_unit: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
						po_orign: $('#dg_add').datagrid('getData').rows[i].ORIGIN_CODE,
						po_price: $('#dg_add').datagrid('getData').rows[i].ESTIMATE_PRICE,
						po_curr_item: $('#dg_add').datagrid('getData').rows[i].CURR_SHORT,
						po_qty: $('#dg_add').datagrid('getData').rows[i].QTY,
						po_eta: $('#dg_add').datagrid('getData').rows[i].ETA_DATE,
						po_prf: $('#dg_add').datagrid('getData').rows[i].PRF_NOMOR,
						po_prf_line: $('#dg_add').datagrid('getData').rows[i].PRF_LINE_NO,
						po_dt_code: $('#dg_add').datagrid('getData').rows[i].OHSAS,
						amt_o: parseFloat(tot_amt_o).toFixed(2),
						amt_l: parseFloat(tot_amt_l).toFixed(2)
					});
				}
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);

			$.post('po_save.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>PO No. : '+$('#po_no_add').textbox('getValue'),'info');
					$.messager.progress('close');

					$.messager.confirm('Confirm','Are you sure you want to print PO?',function(r){
						if(r){
							window.open('po_print.php?po='+$('#po_no_add').textbox('getValue'))
						}
					});
				}else{
					$.post('po_destroy.php',{po_no: $('#po_no_add').textbox('getValue')},'json');
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}
	}

	function savePo(){
		$.messager.progress({
		    title:'Please waiting',
		    msg:'Save data...'
		});
		var url='';
		var pono = $('#po_no_add').textbox('getValue').trim();
		$.ajax({
			type: 'GET',
			url: '../json/json_kode_po.php?po='+pono,
			data: { kode:'kode' },
			success: function(data){
				if(data[0].kode=='SUCCESS'){
					simpan();
				}else{
					$.messager.alert('Warning',data[0].kode,'warning');
					$.messager.progress('close');
				}
			}
		});
	}
</script>
</body>
</html>