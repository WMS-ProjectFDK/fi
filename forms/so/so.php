<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>

<!DOCTYPE html>
   	<html>
   	<head>
   	<meta charset="UTF-8">
   	<title>SALES ORDER</title>
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
			margin: 0px;
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
		<?php 
			include ('../../ico_logout.php');
			$exp = explode('-', access_log($menu_id,$user_name));
		?>
		
		<div id="toolbar" style="padding:3px 3px;">
			<fieldset style="float:left;width:540px;border-radius:4px;"><legend><span class="style3"><strong>SALES ORDER FILTER</strong></span></legend>
				<div style="width:540px;float:left">
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">SO Date</span>
						<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
						to 
						<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
						<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
	    			</div>
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">SO No.</span>
	    				<select style="width:190px;" name="cmb_so_no" id="cmb_so_no" class="easyui-combobox" data-options=" url:'../json/json_sono.php', method:'get', valueField:'so_no', textField:'so_no', panelHeight:'75px'"></select>
						<label><input type="checkbox" name="ck_so_no" id="ck_so_no" checked="true">All</input></label>
	    			</div>
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">Customer</span>
	    				<select style="width:370px;" name="cmb_cust" id="cmb_cust" class="easyui-combobox" data-options=" url:'../json/json_company.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px'"></select>
						<label><input type="checkbox" name="ck_cust" id="ck_cust" checked="true">All</input></label>
	    			</div>
	    		</div>
			</fieldset>
            <fieldset style="position:absolute;margin-left:565px;border-radius:4px;width: 500px;"><legend><span class="style3"><strong>ITEM FILTER</strong></span></legend>
                <div class="fitem">
					<span style="width:110px;display:inline-block;">Cust-PO No.</span>
					<select style="width:190px;" name="cmb_cust_po" id="cmb_cust_po" class="easyui-combobox" data-options=" url:'../json/json_cust_pono.php', method:'get', valueField:'cust_po_no', textField:'cust_po_no', panelHeight:'100px'"></select>
					<label><input type="checkbox" name="ck_cust_po" id="ck_cust_po" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Item No.</span>
					<select style="width:330px;" name="cmb_item" id="cmb_item" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
					onSelect:function(rec){
						//alert(rec.id_name_item);
						var spl = rec.id_name_item;
						var sp = spl.split(' - ');
						$('#txt_item_name').textbox('setValue', sp[1]);
					}"></select>
					<label><input type="checkbox" name="ck_item" id="ck_item" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Item Name</span>
					<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
				</div>
			</fieldset>
			<fieldset style="margin-left: 1090px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>PRINT REPORT</strong></span></legend>
				<!-- <div class="fitem" align="center">
					<a href="javascript:void(0)" style="width: 150px;" id="add" class="easyui-linkbutton c2" disabled="true" onclick="print_bc_no()"><i class="fa fa-print" aria-hidden="true"></i> Print BC NO. View</a>
				</div>
				<div class="fitem" align="center">
					<a href="javascript:void(0)" style="width: 150px;" id="add" class="easyui-linkbutton c2"  onclick="print_bc_no_sp()"><i class="fa fa-print" aria-hidden="true"></i> Spart BC NO. View</a>
				</div> -->
			</fieldset>
			<div style="padding:5px 6px;">
		    	<span style="width:50px;display:inline-block;">search</span>
				<input style="width:150px; height: 18px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src"type="text" placeholder="Sales Order No."/>
	    		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2"  onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
	    		<a href="javascript:void(0)" style="width: 120px;" id="add" class="easyui-linkbutton c2" onclick="add_gr()"><i class="fa fa-plus" aria-hidden="true"></i> ADD SO</a>
	    		<a href="javascript:void(0)" style="width: 120px;" id="edit" class="easyui-linkbutton c2" onclick="edit_gr()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT SO</a>
	    		<a href="javascript:void(0)" style="width: 120px;" id="delete" class="easyui-linkbutton c2" onclick="delete_gr()"><i class="fa fa-trash" aria-hidden="true"></i> REMOVE SO</a>
	    	</div></div>
        </div>

		<table id="dg" title="SALES ORDER" class="easyui-datagrid" toolbar="#toolbar	" style="width:auto;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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
				$('#date_awal').datebox('disable');
				$('#date_akhir').datebox('disable');
				$('#ck_date').change(function(){
					if($(this).is(':checked')){
						$('#date_awal').datebox('disable');
						$('#date_akhir').datebox('disable');
					}else{
						$('#date_awal').datebox('enable');
						$('#date_akhir').datebox('enable');
					}
				});

				$('#cmb_so_no').combobox('disable');
				$('#ck_so_no').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_so_no').combobox('disable');
					}else{
						$('#cmb_so_no').combobox('enable');
					}
				});

				$('#cmb_cust').combobox('disable');
				$('#ck_cust').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_cust').combobox('disable');
					}else{
						$('#cmb_cust').combobox('enable');
					}
				});

				$('#cmb_cust_po').combobox('disable');
				$('#ck_cust_po').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_cust_po').combobox('disable');
					}else{
						$('#cmb_cust_po').combobox('enable');
					}
				});

				$('#cmb_item').combobox('disable');
				$('#ck_item').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_item').combobox('disable');
					}else{
						$('#cmb_item').combobox('enable');
					}
				});

				document.getElementById('src').focus();
			});

			var src ='';

			function filter(event){
				var src = document.getElementById('src').value;
				var search = src.toUpperCase();
				document.getElementById('src').value = search;
				
			    if(event.keyCode == 13 || event.which == 13){
					var src = document.getElementById('src').value;
					// alert(src);
					$('#dg').datagrid('load', {
						src: search
					});

					$('#dg').datagrid('enableFilter');

					if (src=='') {
						filterData();
					}else{
						$('#dg').datagrid({
							url: 'so_get.php'
						});
					}
					document.getElementById('src').value = '';
			    }
			}

			function filterData(){
				var ck_date = "false";
				var ck_so_no = "false";
				var ck_cust = "false";
				var ck_cust_po = "false";
				var ck_item = "false";
				var flag = 0;

				if ($('#ck_date').attr("checked")) {
					ck_date = "true";
					flag += 1;
				}

				if ($('#ck_so_no').attr("checked")) {
					ck_so_no = "true";
					flag += 1;
				};

				if ($('#ck_cust').attr("checked")) {
					ck_cust = "true";
					flag += 1;
				};

				if ($('#ck_cust_po').attr("checked")) {
					ck_cust_po = "true";
					flag += 1;
				};

				if ($('#ck_item').attr("checked")) {
					ck_item = "true";
					flag += 1;
				};

				if(flag == 5) {
					$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
				}

				$('#dg').datagrid('load', {
					date_awal: $('#date_awal').datebox('getValue'),
					date_akhir: $('#date_akhir').datebox('getValue'),
					ck_date: ck_date,
					cmb_so_no : $('#cmb_so_no').combobox('getValue'),
					ck_so_no: ck_so_no,
					cmb_cust: $('#cmb_cust').combobox('getValue'),
					ck_cust: ck_cust,
					cmb_cust_po: $('#cmb_cust_po').combobox('getValue'),
					ck_cust_po: ck_cust_po,
					cmb_item: $('#cmb_item').combobox('getValue'),
					ck_item: ck_item,
					src: ''
				});

				$('#dg').datagrid( {
					url: 'so_get.php',
					view: detailview,
				    columns:[[
					    {field:'SO_NO',title:'SALES ORDER<br>NO.', width:80, halign:'center'},
		                {field:'SO_DATE',title:'SALES ORDER<br>DATE',width:80,halign:'center', align:'center'},
		                {field:'CUSTOMER_CODE',title:'CUSTOMER<br>CODE',width:60,halign:'center', hidden: true},
		                {field:'COMPANY',title:'SUPLIER',width:130,halign:'center'},
						{field:'CUSTOMER_PO_NO',title:'CUSTOMER<br>PO NO.',width:80,halign:'center'},
		                {field:'CURR_SHORT',title:'CURR',width:35,halign:'center', align:'center'},
		                {field:'EX_RATE',title:'RATE',width:50,halign:'center', align:'center'},
		                {field:'AMT_L',title:'AMOUNT',width:80,halign:'center', align:'right'},
		                {field:'REMARK',title:'REMARK',width:180,halign:'center'},
		                {field:'PERSON',title:'PERSON',width:80,halign:'center'}
					]],
					detailFormatter: function(rowIndex, rowData){
						return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
					},
					onExpandRow: function(index,row){
						var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
						listbrg.datagrid({
		                	title: 'SALES ORDER DETAIL (NO: '+row.SO_NO+')',
							url:'so_get_detail.php?so_no='+row.SO_NO,
							toolbar: '#ddv'+index,
							singleSelect:true,
							loadMsg:'load data ...',
							height:'auto',
							fitColumns: true,
							columns:[[
								{field:'LINE_NO', title:'LINE NO.', halign:'center', align:'center', width:50},
				                {field:'ITEM_NO',title:'ITEM NO.', halign:'center', align:'center', width:65, sortable: true},
				                {field:'DESCRIPTION', title:'ITEM<br>DESCRIPTION', halign:'center', width:150},
				                {field:'UNIT', title:'UNIT', halign:'center', align:'center', width:35},
				                {field:'UOM_Q', title:'UoM', halign:'center', align:'center', width:50, hidden: true},
				                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
				                {field:'U_PRICE', title:'PRICE ('+row.CURR_SHORT+')', halign:'center', align:'right', width:50},
				                {field:'AMT_L', title:'AMOUNT', halign:'center', align:'right', width:70},
								{field:'CASE_MARK', title:'CASE MARK', halign:'center', align:'right', width:120},
								{field:'PALLET_MARK', title:'PALLET MARK', halign:'center', align:'right', width:120},
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

				pdf_url = "?date_awal="+$('#date_awal').datebox('getValue')+
					"&date_akhir="+$('#date_akhir').datebox('getValue')+
					"&ck_date="+ck_date+
					"&cmb_so_no="+$('#cmb_so_no').combobox('getValue')+
					"&ck_so_no="+ck_so_no+
					"&cmb_cust="+$('#cmb_cust').combobox('getValue')+
					"&ck_cust="+ck_cust+
					"&cmb_cust_po="+$('#cmb_cust_po').combobox('getValue')+
					"&ck_cust_po="+ck_cust_po+
					"&cmb_item="+$('#cmb_item').combobox('getValue')+
					"&ck_item="+ck_item+
					"&src="+src;

				// console.log(pdf_url);
				
				$('#dg').datagrid('enableFilter');
			}
		</script>
	</body>
    </html>