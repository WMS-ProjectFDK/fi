<?php include("../connect/conn.php");
session_start();

?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Purchase Order Report</title>
	<script language="javascript">
 function confirmLogOut(){
	var is_confirmed;
	is_confirmed = window.confirm("End current session?");
	return is_confirmed;
 }
  </script> 
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../themes/icon.css">
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
		margin-left:460px;	
		top: 0px;
		border-style: solid;
		border-width: 0px;
	}
	</style>
    </head>
    <body>
	<?php include ('../ico_logout.php'); ?>

	<div class="fitem">
	</div>

	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:400px;height:170px; margin-bottom:2px;"><legend>PURCHASE ORDER FILTER</legend>
			<div style="width:430px; float:left;">
				<!-- <div class="fitem"><br/><br/></div> -->
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Period</span>
					<input style="width:100px;" name="pd_periode_awal" id="pd_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required/> to
					<input style="width:100px;" name="pd_periode_akhir" id="pd_periode_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required/>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Supplier</span>
					<select style="width:220px;" name="dn_supp" id="dn_supp" class="easyui-combobox" data-options=" url:'json/json_supplier_cmb.php',method:'get',valueField:'kode_supplier',textField:'nama_supplier', panelHeight:'150px'"></select>
					<input type="checkbox" name="ck_supp" id="ck_supp" checked="true">All</input>
				</div>
			</div>
		</fieldset>
	<div class="board_2">
		<fieldset id="fildsales" style="border-radius:4px; width:710px;height:170px; border:1px solid #d0d0d0;margin-bottom:2px;"><legend><span class="style3">REPORT PURCHASE ORDER</span></legend>
			<table>
				<tr class="fitem">
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print1_pdf()" iconCls="icon-pdf" plain="true" style="width:350px"> Summary of Purchase Order by Material Group</a></td>
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print1_xls()" iconCls="icon-excel" plain="true" style="width:350px"> Summary of Purchase Order by Material Group</a></td>
				</tr>
				<!-- <tr class="fitem">
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print2()" iconCls="icon-pdf" plain="true" style="width:350px"> Summary of Accounts Payable by Invoice &nbsp;</a></td>
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print2xls()" iconCls="icon-excel" plain="true" style="width:350px"> Summary of Accounts Payable by Invoice &nbsp;</a></td>
				</tr>
				<tr class="fitem">
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print3()" iconCls="icon-pdf" plain="true" style="width:350px"> Detail of Accounts Payable by Invoice &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print3xls()" iconCls="icon-excel" plain="true" style="width:350px"> Detail of Accounts Payable by Invoice &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
				</tr>
				<tr class="fitem">
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print4()" iconCls="icon-pdf" plain="true" style="width:350px"> Detail of Accounts Payable by Supplier&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print4xls()" iconCls="icon-excel" plain="true" style="width:350px"> Detail of Accounts Payable by Supplier&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
				</tr>
				<tr class="fitem">
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print5()" iconCls="icon-pdf" plain="true" style="width:350px"> Accounts Payable Report By Supplier &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
					<td><a href="javascript:void(0)" class="easyui-linkbutton btnPrint" onclick="print5xls()" iconCls="icon-excel" plain="true" style="width:350px"> Accounts Payable Report By Supplier &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
				</tr> -->
			</table>
			<div class="fitem"><br/></div>
			<div class="fitem"><br/></div>
    	</fieldset>
	</div>
	</div>
	<table id="dg" title="PURCHASE ORDER REPORT" class="easyui-datagrid" style="width:1200px;"></table>
	
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
			$('#dn_supp').combobox('disable');
			$('#ck_supp').change(function(){
				if ($(this).is(':checked')) {
					$('#dn_supp').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#dn_supp').combobox('enable');					
				};
			})

			$('#dg').datagrid({
			    toolbar: '#toolbar'
		    });
		})

		function print1_pdf(){
			var bln = $.trim($('#pd_periode_awal').combobox('getValue'));
			var bln2 = $.trim($('#pd_periode_akhir').combobox('getValue'));

			if(bln=='' && bln2=='') {
				$.messager.show({
					title: 'PDF Report',
					msg: 'Data Not Defined, please select Date'
				});
			}else{
				var ck_supp ='false';

				if($('#ck_supp').attr("checked")){
					ck_supp='true';
				}

				pdf = "?pd_periode_awal="+$('#pd_periode_awal').datebox('getValue')
				+"&pd_periode_akhir="+$('#pd_periode_akhir').datebox('getValue')
				+"&dn_supp="+$('#dn_supp').combobox('getValue')
				+"&dn_supp_nm="+$('#dn_supp').combobox('getText')
				+"&ck_supp="+ck_supp;
				
				window.open('po_report_by_material_group.php'+pdf, '_blank');	
			}
		}

		function print1_xls(){
			var bln = $.trim($('#pd_periode_awal').combobox('getValue'));
			var bln2 = $.trim($('#pd_periode_akhir').combobox('getValue'));

			if(bln=='' && bln2=='') {
				$.messager.show({
					title: 'PDF Report',
					msg: 'Data Not Defined, please select Date'
				});
			}else{
				var ck_supp ='false';
				
				if($('#ck_supp').attr("checked")){
					ck_supp='true';
				}

				pdf = "?pd_periode_awal="+$('#pd_periode_awal').datebox('getValue')
				+"&pd_periode_akhir="+$('#pd_periode_akhir').datebox('getValue')
				+"&dn_supp="+$('#dn_supp').combobox('getValue')
				+"&dn_supp_nm="+$('#dn_supp').combobox('getText')
				+"&ck_supp="+ck_supp;

				window.open('ap_report_by_supp_excel.php'+pdf, '_blank');
			}
		}

		

	</script>
    </body>
    </html>