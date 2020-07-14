<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>SHIPPING FORWARDER</title>
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
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="si_forwarder()"> SHIPPING FORWARDER </a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" onclick="si_print()"> PRINT </a>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; padding: 2px; width:auto;"><legend>Filter Shipping</legend>
			<div style="width:380px; float:left;">
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Shipping Date</span>
					<input style="width:91px;" name="dn_periode_awal" id="dn_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="" /> 
					to 
					<input style="width:91px;" name="dn_periode_akhir" id="dn_periode_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="" />
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Shipping No.</span>
					<select style="width:202px;" name="dn_shipp" id="dn_shipp" class="easyui-combobox" data-options=" url:'json/json_supplier.php',method:'get',valueField:'kode_supplier',textField:'nama_supplier', panelHeight:'150px'"></select>
					<input type="checkbox" name="ck_shipp" id="ck_shipp" checked="true">All</input>
				</div>
			</div>
			<div style="width:380px; float: left;">
				<div class="fitem">
					<div class="fitem">
						<span style="width:110px;display:inline-block;">Vendor Name</span>
						<select style="width:202px;" name="dn_vendor" id="dn_vendor" class="easyui-combobox" data-options=" url:'json/json_supplier.php',method:'get',valueField:'kode_supplier',textField:'nama_supplier', panelHeight:'150px'"></select>
						<input type="checkbox" name="ck_vendor" id="ck_vendor" checked="true">All</input>
					</div>
				</div>
			</div>
			<div style="width:380px; float: left;">
				<div class="fitem">
					<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton" iconCls="icon-filter" onClick="filterData()" style="width:100px;">FILTER</a>
				</div>
			</div>
		</fieldset>
	</div>
	<table id="dg" title="SHIPPING FORWARDER" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;"></table>

	<div id="dlg" class="easyui-dialog" style="width:1050px;" closed="true" data-options="position:'bottom'" buttons="#dlg-buttons" data-options="modal:true">

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
			$('#dn_shipp').combobox('disable');
			$('#ck_shipp').change(function(){
				if ($(this).is(':checked')) {
					$('#dn_shipp').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					//alert('HOREEEEEE!!!');
					$('#dn_shipp').combobox('enable');					
				};
			})

			$('#dn_vendor').combobox('disable');
			$('#ck_vendor').change(function(){
				if ($(this).is(':checked')) {
					$('#dn_vendor').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					//alert('HOREEEEEE!!!');
					$('#dn_vendor').combobox('enable');					
				};
			})

			$('#dg').datagrid({
				fitColumns: true,
			    columns:[[
			    	{field:'SI_NO',title:'Shipping No.', halign:'center', width:150, sortable: true},
	                {field:'CUST_SI_NO', title:'Shipping No.<br> From Customer', halign:'center', align:'center', width:150}, 
	                {field:'CREATE_DATE',title:'Entry Date', halign:'center', align:'center', width:80, sortable: true},
	                {field:'COMPANY', title:'Forward<br>Place of Delivery', halign:'center', width:240}
			    ]]
		    });
		})

		function filterData(){
			var ck_shipp = 'false';
			var ck_vendor = 'false';
			var pd_a = $('#dn_periode_awal').datebox('getValue');
			var pd_z = $('#dn_periode_akhir').datebox('getValue');

			if($('#ck_shipp').attr("checked")) {
				ck_shipp = "true";
			}

			if($('#ck_vendor').attr("checked")) {
				ck_vendor = "true";
			}

			if(pd_a=='' || pd_z==''){
				$.messager.alert("Warning","Required Field Can't Empty!","warning");
			}else{
				$('#dg').datagrid('load', {
					pd_periode_awal: $('#dn_periode_awal').datebox('getValue'),
					pd_periode_akhir: $('#dn_periode_akhir').datebox('getValue')/*,
					dn_shipp: $('#dn_shipp').combobox('getValue'),
					ck_shipp: ck_shipp,
					dn_vendor: $('#dn_vendor').combobox('getValue'),
					ck_vendor: ck_vendor*/
				});

				$('#dg').datagrid({
				    url:'exim_ship_info_get.php',
				    toolbar: '#toolbar',
				    singleSelect: true,
					rownumbers: true,
					fitColumns: true,
				    columns:[[
				    	{field:'SI_NO',title:'Shipping No.', halign:'center', width:100, sortable: true},
		                {field:'CUST_SI_NO', title:'Shipping No.<br> From Customer', halign:'center', width:150}, 
		                {field:'CREATE_DATE',title:'Entry Date', halign:'center', align:'center', width:80, sortable: true},
		                {field:'FORWARDER_NAME', title:'Forward<br>Place of Delivery', halign:'center', width:240}
				    ]],
					onDblClickRow:function(rowIndex){
				    	$(this).datagrid('beginEdit', rowIndex);
				    }
			    });	
			}

			//alert(ck_shipp);
			

			/*ppdf = "?pd_periode_awal="+$('#dn_periode_awal').datebox('getValue')+
				"&pd_periode_akhir="+$('#dn_periode_akhir').datebox('getValue')+
				"&ck_date="+ck_date+
				"&dn_supp="+$('#dn_supp').combobox('getValue')+
				"&ck_supp="+ck_supp*/
		}

		function si_forwarder(){
			var row = $('#dg').datagrid('getSelected');
            if (row){
            	$('#dlg').dialog('open').dialog('setTitle','SHIPPING FORWARDER PROCESS (SI : '+row.SI_NO+')');
            }else{
            	$.messager.alert('INFORMATION',"Data is not selected",'info');
            }
		}
	</script>
    </body>
    </html>