<?php
include("../connect/koneksi.php");
session_start();
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Forecast</title>
	<script language="javascript">
		 function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
		 }
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
  </script> 
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../themes/icon.css">
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
	.style4 {
	font-size: 11px;
	color: #CC0000;
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
	.board_1 {
		position: absolute;
		margin-left:615px;	
		top: 0px;
		border-style: solid;
		border-width: 0px;
	}
	.board_2 {
		position: absolute;
		margin-left:910px;	
		top: 0px;
		border-style: solid;
		border-width: 0px;
	}
	.button_filter {
		position: absolute;
		margin-left:440px;	
		top: 134px;
	}
	.button_generate {
		position: absolute;
		margin-left:100px;	
		top: 134px;
	}
	</style>
    </head>
    <body>
<?php include ('../ico_logout.php'); ?>

<div id="tbsearch">
<fieldset style="width:590px;height:150px;"><legend><span class="style3">Forcast Filter</span></legend>
	<table border="0">
		<tr>
			<td>FC Periode</td>
			<td>
				<div class="fitem">
					<input style="width:100px;" name="s_periode_awal" id="s_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
					to 
					<input style="width:100px;" name="s_periode_akhir" id="s_periode_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" /> 
				</div>
			</td>
			<td width='20px'>&nbsp;</td>
			<td>Part No Class</td>
			<td>
				<select style="width:70px;" name="s_classabc" id="s_classabc" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_classabc.php',method:'get',valueField:'class',textField:'class', panelHeight:'auto'"></select>
				<input type="checkbox" field="ck_classabc" checkbox="true" name="ck_classabc" id="ck_classabc" checked="true" value="all">All</input>
			</td>
		</tr>
		<tr>
			<td>Customer Name</td>
			<td>
				<select style="width:100px;" name="s_kode_customer" id="s_kode_customer" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_customer.php',method:'get',valueField:'kode_customer',textField:'nama_customer', panelHeight:'auto'"></select>
				<input type="checkbox" field="ck_kode_customer" checkbox="true" name="ck_kode_customer" id="ck_kode_customer" checked="true" value="all">All</input>
			</td>
			<td width='20px'>&nbsp;</td>
			<td>Remark</td>
			<td>
				<select style="width:70px;" name="s_remark" id="s_remark" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_remark.php',method:'get',valueField:'remark',textField:'remark', panelHeight:'auto'"></select>
				<input type="checkbox" name="ck_remark" id="ck_remark" checked="true">All</input>
			</td>
		</tr>
			<td>Category</td>
			<td>
				<select style="width:100px;" name="s_fc_kategori" id="s_fc_kategori" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_category.php',method:'get',valueField:'jenis_kategori',textField:'ket_kategori', panelHeight:'auto'"></select>
				<input type="checkbox" name="ck_category" id="ck_category" checked="true">All</input>
			</td>
			<td width='20px'>&nbsp;</td>
			
		<tr>
			<td>Product Family</td>
			<td>
				<select style="width:100px;" name="s_fc_prodfam" id="s_fc_prodfam" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_prodfam.php',method:'get',valueField:'jenis_prodfam',textField:'ket_master', panelHeight:'auto'"></select>
				<input type="checkbox" name="ck_prodfam" id="ck_prodfam" checked="true">All</input>
			</td>
		</tr>
	</table>
	<div class="button_filter"><a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="filterdata()">Filter Data</a>&nbsp;<a href="#" class="easyui-linkbutton" onclick="">Clear Data</a></div>
</fieldset>
<div class="board_1"><fieldset style="width:270px;height:151px;"><legend><span class="style3" style="">Forcast Analisys</span></legend>
	<table>
		<tr>
			<td>X (Moving Average)</td>
			<td>&nbsp;</td>
			<td><select style="width:60px;" name="cb_x" id="cb_x" class="easyui-combobox" style="width:142px;" data-options=" url:'json/json_tiga.php',method:'get',valueField:'class',textField:'class', panelHeight:'auto'"></select> Month</td>
		</tr>
		<tr>
			<td>M Compare Data</td>
			<td>&nbsp;</td>
			<td><select style="width:60px;" name="cb_m" id="cb_m" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_tiga.php',method:'get',valueField:'class',textField:'class', panelHeight:'auto'"></select> Month</td>
		</tr>
	</table>
	<div class="button_generate"><a href="javascript:void(0)" class="easyui-linkbutton" onclick="generated()">Generated</a>&nbsp;<a href="#" class="easyui-linkbutton" style="width:90px;">View & Print</a></div>
</fieldset></div>

<form id="upd" method="post" enctype="multipart/form-data" novalidate>
<div class="board_2"><fieldset style="width:162px;height:151px;"><legend><span class="style3" style="">Upload Forecast</span></legend><br><br><br>
<input class="easyui-filebox" name="file1" id="file1" data-options="prompt:'Choose a file...'" style="width:100%">
<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="uploaddata()" style="width:100%;position:absolute;top:95px;margin-left:-162px;">Upload</a>
</fieldset></div>
</form>

<p><div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newForecast()">Add</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyForecast()">Delete</a>
	<a href="mkt_forecast_pdf.php" class="easyui-linkbutton" iconCls="icon-print" plain="true" target="_blank">PDF</a>
	<a href="mkt_forecast_excel.php" class="easyui-linkbutton" iconCls="icon-print" plain="true">Excel</a>

</div>
</div>
		<table id="dg" title="Forecast" class="easyui-datagrid" style="width:1100px;height:500px;"
		
				url="mkt_forecast_get.php"
				toolbar="#tbsearch" pagination="true"
				rownumbers="true" fitColumns="true" singleSelect="true"
				sortName="fc_trans_code" sortOrder="asc">
				 
			<thead>
			<tr>
				<th field="fc_trans_code" width="130">Trans ID</th>
				<th field="fc_datecreate" width="130">Trans Date</th>
				<th field="nama_customer" width="130">Customer</th>
				<th field="brg_codebarang" width="130">Part No.</th>
				<th field="nama_barang" width="130">Part Name</th>
				<th field="ket_kategori" width="130">Category</th>
				<th field="ket_master" width="130">Prod. Fam</th>
				<th field="ket_satuan" width="130">UoM</th>
				<th field="brg_class" width="130">Class</th>
				<th field="fc_bulan1" width="100">FC 1</th>
				<th field="fc_bulan2" width="100">FC 2</th>
				<th field="fc_bulan3" width="100">FC 3</th>
				<th field="fc_bulan4" width="100">FC 4</th>
				<th field="fc_bulan5" width="100">FC 5</th>
				<th field="fc_bulan6" width="100">FC 6</th>
				<th field="fc_keterangan" width="130">Remarks</th>
				</tr>
			</thead>
		</table>
		<div id="dlg" class="easyui-dialog" style="width:400px;height:350px;padding:10px 20px"
				closed="true" buttons="#dlg-buttons">
			<form id="fm" method="post" novalidate>
				<input name="fc_trans_code" type="text" style="display:none;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Customer</span>
					<select style="width:100px;" name="kode_customer" id="kode_customer" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_customer.php',method:'get',valueField:'kode_customer',textField:'nama_customer', panelHeight:'auto'" required="true"></select>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Part No.</span>
					<select style="width:100px;" name="brg_codebarang" id="brg_codebarang" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_barang.php',method:'get',valueField:'brg_codebarang',textField:'brg_codebarang', panelHeight:'auto'" required="true"></select>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">FC 1</span>
					<input name="fc_bulan1" class="easyui-textbox" required="true">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">FC 2</span>
					<input name="fc_bulan2" class="easyui-textbox" required="true">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">FC 3</span>
					<input name="fc_bulan3" class="easyui-textbox" required="true">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">FC 4</span>
					<input name="fc_bulan4" class="easyui-textbox" required="true">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">FC 5</span>
					<input name="fc_bulan5" class="easyui-textbox" required="true">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">FC MI 6</span>
					<input name="fc_bulan6" class="easyui-textbox" required="true">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Remarks</span>
					<input name="fc_keterangan" class="easyui-textbox" required="true">
				</div>
			</form>
		</div>
		<div id="dlg-buttons">
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveForecast()" style="width:90px">Save</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
		</div>	
		
		
		<!-- form 2 -->
		<div id="popanalisis" class="easyui-dialog" style="width:1000px;height:450px;padding:10px 20px"
				closed="true" modal="true">
				<iframe id="pdfcontent" style="height:400px;width:900px;" src="mkt_fcanalysis.php"></iframe>
				<!--
		<table id="t2" title="Forecast" class="easyui-datagrid" style="width:900px;height:400px;"
		
				url="mkt_forecast_get.php"
				toolbar="#tbsearch" pagination="true"
				rownumbers="true" fitColumns="true" singleSelect="true"
				sortName="fc_trans_code" sortOrder="asc">
				 
			<thead>
			<tr>
				<th field="fc_trans_code" width="130">Trans ID</th>
				<th field="nama_customer" width="130">Customer</th>
				<th field="brg_codebarang" width="130">Part No.</th>
				<th field="nama_barang" width="130">Part Name</th>
				<th field="ket_kategori" width="130">Category</th>
				<th field="ket_master" width="130">Prod. Fam</th>
				<th field="ket_satuan" width="70">UoM</th>
				<th field="brg_class" width="70">Class</th>
				<th field=" " width="100">X1</th>
				<th field=" " width="100">X2</th>
				<th field=" " width="100">X3</th>
				<th field=" " width="100">AVG1</th>
				<th field="fc_bulan1" width="70">FC1</th>
				<th field="fc_bulan2" width="70">FC2</th>
				<th field="fc_bulan3" width="70">FC3</th>
				<th field="fc_bulan6" width="70">AVG2</th>
				<th field=" " width="130">Balance</th>
				</tr>
			</thead>
		</table>
		-->
		<!-- frame fc -->
		
		
		
		</div>
		<script type="text/javascript">
			var url;
			function filterdata() {
					var chk_classabc='false';
					var chk_kode_customer='false';
					var chk_remark='false';
					var chk_category='false';
					var chk_prodfam='false';
					
					if($('#ck_classabc').attr("checked")){
						chk_classabc='true';
					}
					if($('#ck_kode_customer').attr("checked")){
						chk_kode_customer='true';
					}
					if($('#ck_remark').attr("checked")){
						chk_remark='true';
					}
					if($('#ck_category').attr("checked")){
						chk_category='true';
					}
					if($('#ck_prodfam').attr("checked")){
						chk_prodfam='true';
					}
					
					$('#dg').datagrid('load', {
						s_classabc: $('#s_classabc').combobox('getValue'),
						ck_classabc: chk_classabc,
						
						s_kode_customer: $('#s_kode_customer').combobox('getValue'),
						ck_kode_customer: chk_kode_customer,
						
						s_remark: $('#s_remark').combobox('getValue'),
						ck_remark: chk_remark,
						
						s_fc_kategori: $('#s_fc_kategori').combobox('getValue'),
						ck_category: chk_category,
						
						s_fc_prodfam: $('#s_fc_prodfam').combobox('getValue'),
						ck_prodfam: chk_prodfam,
						
						s_periode_awal: $('#s_periode_awal').textbox('getValue'),
						s_periode_akhir: $('#s_periode_akhir').textbox('getValue')
					});
					
					//-- pop up
					

				}
			function popup() {
				$('#popanalisis').dialog('open').dialog('setTitle','Analisis Forecast');
					
					$('#t2').datagrid('load', {
						s_classabc: $('#s_classabc').combobox('getValue'),
						ck_classabc: chk_classabc,
						
						s_kode_customer: $('#s_kode_customer').combobox('getValue'),
						ck_kode_customer: chk_kode_customer,
						
						s_remark: $('#s_remark').combobox('getValue'),
						ck_remark: chk_remark,
						
						s_fc_kategori: $('#s_fc_kategori').combobox('getValue'),
						ck_category: chk_category,
						
						s_fc_prodfam: $('#s_fc_prodfam').combobox('getValue'),
						ck_prodfam: chk_prodfam,
						
						s_periode_awal: $('#s_periode_awal').textbox('getValue'),
						s_periode_akhir: $('#s_periode_akhir').textbox('getValue')
					});
			}
			function uploaddata() {
				$('#upd').form('submit',{
					url: 'mkt_forecast_upload.php',
					onSubmit: function(){
						return $(this).form('validate');
					},
					success: function(result){
						alert(result);
						$('#file1').filebox('clear');
						$('#dg').datagrid('reload');
					}
				});
			}
			
			function newForecast(){
				$('#dlg').dialog('open').dialog('setTitle','Add');
				$('#fm').form('clear');
				url = 'mkt_forecast_save.php';
			}
			function saveForecast(){
				$('#fm').form('submit',{
					url: url,
					onSubmit: function(){
						return $(this).form('validate');
					},
					success: function(result){
						var result = eval('('+result+')');
						if (result.errorMsg){
							$.messager.show({
								title: 'Error',
								msg: result.errorMsg
							});
						} else {
							$('#dlg').dialog('close');		// close the dialog
							$('#dg').datagrid('reload');	// reload data
						}
					}
				});
			}
			function editForecast(){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					$('#dlg').dialog('open').dialog('setTitle','Edit');
					$('#fm').form('load',row);
					$('#kode_customer').combobox('setValue',$.trim(row.kode_customer));
					url = 'mkt_forecast_update.php?fc_trans_code='+row.fc_trans_code;
				}
			}
			function findForecast(){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					$('#dlg').dialog('open').dialog('setTitle','Find Forecast');
					$('#fm').form('load',row);
					url = 'mkt_forecast_update.php?fc_trans_code='+row.fc_trans_code;
				}
				
			}
			function destroyForecast(){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					$.messager.confirm('Confirm','Are you sure you want to remove this Forecast?',function(r){
						if (r){
							$.post('mkt_forecast_destroy.php',{fc_trans_code:row.fc_trans_code},function(result){
								if (result.success){
									$('#dg').datagrid('reload');	// reload data
								} else {
									$.messager.show({	// show error message
										title: 'Error',
										msg: result.errorMsg
									});
								}
							},'json');
						}
					});
				}
			}
			
			$(function(){
				var dg = $('#dg').datagrid();
				dg.datagrid('enableFilter');
				$('#cb_x').combobox('setValue', '3');
				$('#cb_m').combobox('setValue', '3');
			});
			
			function generated(){
				$('#popanalisis').dialog('open').dialog('setTitle','Analisis Forecast');
			}
		</script>
	
    </body>
    </html>