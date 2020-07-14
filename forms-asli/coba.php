<?php include("../connect/koneksi.php");
session_start();

?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Sales Invoicing</title>
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
		margin-left:725px;	
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
		<a href="javascript:void(0)" id="addti" class="easyui-linkbutton" plain="true" iconCls="icon-add" onclick="addInv()">Add</a>
		<a href="javascript:void(0)" id="editti" class="easyui-linkbutton" plain="true" iconCls="icon-edit" onclick="editInv()">Edit</a>
		<a href="javascript:void(0)" id="deleteti" class="easyui-linkbutton" plain="true" iconCls="icon-remove" onclick="deleteInv()">Delete</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="printInv()">PDF</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="printdtlInv()">PDF Detail</a>

		<fieldset style="border:1px solid #d0d0d0; border-radius:6px; width:1050px;"><legend>Filter</legend>
			<div style="width:380px; float:left;">
				<div class="fitem">
					<span style="width:90px;display:inline-block;">Periode</span>
					<input style="width:100px;" name="pd_periode_awal" id="pd_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
					to
					<input style="width:100px;" name="pd_periode_akhir" id="pd_periode_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:90px;display:inline-block;">Customer</span>
					<select style="width:220px;" name="dn_cust" id="dn_cust" class="easyui-combobox" data-options=" url:'json/json_customer.php',method:'get',valueField:'nama_customer',textField:'nama_customer', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_cust" id="ck_cust" checked="true">All</input></label>
				</div>
			</div>
			<div style="width:380px; float:left;">
				<div class="fitem">
					<span style="width:90px;display:inline-block;">Payment Due</span>
					<input style="width:100px;" name="pd_awal" id="pd_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
					to
					<input style="width:100px;" name="pd_akhir" id="pd_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
					<label><input type="checkbox" name="ck_pd" id="ck_pd" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:90px;display:inline-block;">Currency</span>
					<select style="width:130px;" class="easyui-combobox" name="currency1" id="currency1" data-options="url:'json/json_ti_currency.php', method:'get', panelHeight: '100px', valueField:'currecy', textField:'currecy'"></select>
					<label><input type="checkbox" name="ck_cur" id="ck_cur" checked="true">All</input></label>
				</div>
			</div>
			<div style="width:280px; float:left;">
				<div class="fitem">
					<span style="width:90px;display:inline-block;">AR STATUS</span>
					<select style="width:120px;" class="easyui-combobox" name="ar_status" id="ar_status" data-options="url:'json/json_status.json', method:'get', panelHeight: 'auto', valueField:'status', textField:'status'"></select>
					<label><input type="checkbox" name="ck_ar" id="ck_ar" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:90px;display:inline-block;"></span>
					<a href="javascript:void(0)" class="easyui-linkbutton" style="width:120px;" iconCls="icon-filter" onclick="filterData()">Filter</a>
				</div>
			</div>
		</fieldset>
	</div>
	<table id="dg" title="SALES INVOICING" class="easyui-datagrid" style="width:1100px;height:500px;">
	</table>

	<div id="dlg" class="easyui-dialog" style="width:1050px;height:600px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
		<form id="fm" method="post" novalidate>
			<div style="width:430px; float:left;">
				<fieldset id="fildsales" style="border-radius:4px; width:400px;height:100px; border:1px solid #d0d0d0;"><legend><span class="style3">SELECT CUSTOMER</span></legend>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Date</span>
						<input name="tax_date" id="tax_date" class="easyui-datebox" required="true" style="width:100px;" data-options="formatter:myformatter,parser:myparser" />
					</div>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Customer Name</span>
						<select name="cus_name" id="cus_name" class="easyui-combobox" style="width:270px;"  
						data-options=" 
						url:'json/json_customer.php',
						method:'get',
						valueField:'kode_customer',
						textField:'nama_customer', 
						panelHeight:'100px',
						onSelect:function(rek){
							$('#payment_term').combobox('setValue',$.trim(rek.payterm));
						}"
						required="true"></select>
					</div>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Category</span>
						<select name="category" id="category" class="easyui-combobox" style="width:100px;" data-options=" url:'json/json_category.php',method:'get',valueField:'jenis_kategori',textField:'ket_kategori', panelHeight:'auto'" required="true"> 
						</select>
						<span style="width:70px;display:inline-block;">Currency</span>	
						<select name="currency" id="currency" class="easyui-combobox" style="width:90px;" data-options=" url:'json/json_ti_currency.php',method:'get',valueField:'currecy',textField:'currecy', panelHeight:'auto'" required="true"></select>
						</select>
					</div>
				</fieldset>
			</div>
			<div style="width:350px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Sales Invoicing No.</span>
					<select name="sales_invoiceno" id="sales_invoiceno" style="width:150px;" class="easyui-textbox"></select>
				</div>
				<fieldset id="fildsales" style="border-radius:4px; width:270px;height:70px; border:1px solid #d0d0d0;"><legend><span class="style3">PAYMENT</span></legend>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Payment Due</span>
						<input name="due_date" id="due_date" class="easyui-datebox" required="true" style="width:140px;" data-options="formatter:myformatter,parser:myparser" />
					</div>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Term Of Payment</span>
						<select style="width:140px;" name="payment_term" id="payment_term" class="easyui-combobox" data-options=" url:'json/json_payment.php',method:'get',valueField:'kode_pay',textField:'payment', panelHeight:'auto'" required="true"></select>
					</div>
				</fieldset>					
			</div>
			<div class="board_2">
				<div class="fitem" style="margin-top:62px;">
					<fieldset id="fildsales" style="border-radius:4px; width:250px;height:70px; border:1px solid #d0d0d0;"><legend><span class="style3">EXCHANGE RATE</span></legend>
						<div class="fitem">
							<span style="width:30px;display:inline-block;"></span>
							<span style="width:80px;display:inline-block;">USD</span>
							<select name="usd" id="usd" style="width:120px;" class="easyui-textbox"></select>
						</div>
						<div class="fitem">
							<span style="width:30px;display:inline-block;"></span>
							<span style="width:80px;display:inline-block;">JPY</span>
							<select name="jpy" id="jpy" style="width:120px;" class="easyui-textbox"></select>
						</div>
					</fieldset>	
				</div>
			</div>

		<div style="clear:both;margin-bottom:15px;"></div>

		<table id="dg_entry" title="ADD SALES INVOICING" class="easyui-datagrid" style="width:1000px;height:300px;">
		</table>

		<div id="entoolbar">
			<a href="javascript:void(0)" id="addpartno" class="easyui-linkbutton c6" iconCls="icon-add" plain='true' onclick="findPart()">Find Delivery Note</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-remove" plain='true' onclick="addRemove()">Delete</a>
		</div>

		<div style="margin-top:10px; margin-left:400px;width:300px; float:left;">
			<div class="fitem">
				<span style="width:105px;display:inline-block;">Total Tax Basis</span>
				<input name="total_taxbasis" id="total_taxbasis" class="easyui-numberbox" value="1234567.89" data-options="precision:5,groupSeparator:','">
			</div>
			<div class="fitem">
				<span style="width:105px;display:inline-block;">Total Vat</span>
				<input name="total_vat" id="total_vat" class="easyui-numberbox" value="1234567.89" data-options="precision:5,groupSeparator:','">
			</div>
		</div>

		<div style="margin-top:10px;">
			<div class="fitem">
				<span style="width:105px;display:inline-block;">Grand Total </span>
				<input name="grand_tot" id="grand_tot"  class="easyui-numberbox" value="1234567.89" data-options="precision:5,groupSeparator:','">
			</div>
		</div>
		</form>
	</div>

	<div id="dlg_editor" class="easyui-dialog" style="width:1050px;height:600px;padding:10px 20px" closed="true" buttons="#dlg_e-buttons">
		<form id="fm" method="post" novalidate>
			<div style="width:430px; float:left;">
				<fieldset id="fildsales" style="border-radius:4px; border:1px solid #d0d0d0;"><legend><span class="style3">Customer</span></legend>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Date</span>
						<input name="etax_date" id="etax_date" class="easyui-datebox" required="true" style="width:100px;" data-options="formatter:myformatter,parser:myparser" />
					</div>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Customer Name</span>
						<select name="ecus_name" id="ecus_name" class="easyui-combobox" style="width:270px;"  
						data-options=" 
						url:'json/json_customer.php',
						method:'get',
						valueField:'kode_customer',
						textField:'nama_customer', 
						panelHeight:'100px',
						onSelect:function(rek){
							$('#epayment_term').combobox('setValue',$.trim(rek.payterm));
						}"
						required="true"></select>
					</div>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Category</span>
						<select name="ecategory" id="ecategory" class="easyui-combobox" style="width:100px;" data-options=" url:'json/json_category.php',method:'get',valueField:'jenis_kategori',textField:'ket_kategori', panelHeight:'auto'" required="true"> 
						</select>
						<span style="width:70px;display:inline-block;">Currency</span>	
						<select name="ecurrency" id="ecurrency" class="easyui-combobox" style="width:90px;" data-options=" url:'json/json_ti_currency.php',method:'get',valueField:'currecy',textField:'currecy', panelHeight:'auto'" required="true"></select>
						</select>
					</div>
				</fieldset>
			</div>
			<div style="width:350px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Sales Invoicing No.</span>
					<select name="esales_invoiceno" id="esales_invoiceno" style="width:150px;" class="easyui-textbox"></select>
				</div>
				<fieldset id="fildsales" style="border-radius:4px; width:270px;height:70px; border:1px solid #d0d0d0;"><legend><span class="style3">PAYMENT</span></legend>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Payment Due</span>
						<input name="edue_date" id="edue_date" class="easyui-datebox" required="true" style="width:140px;" data-options="formatter:myformatter,parser:myparser" />
					</div>
					<div class="fitem">
						<span style="width:120px;display:inline-block;">Term Of Payment</span>
						<select style="width:140px;" name="epayment_term" id="epayment_term" class="easyui-combobox" data-options=" url:'json/json_payment.php',method:'get',valueField:'kode_pay',textField:'payment', panelHeight:'auto'" required="true"></select>
					</div>
				</fieldset>					
			</div>
			<div class="board_2">
				<div class="fitem" style="margin-top:62px;">
					<fieldset id="fildsales" style="border-radius:4px; width:250px;height:70px; border:1px solid #d0d0d0;"><legend><span class="style3">EXCHANGE RATE</span></legend>
						<div class="fitem">
							<span style="width:30px;display:inline-block;"></span>
							<span style="width:80px;display:inline-block;">USD</span>
							<select name="eusd" id="eusd" style="width:120px;" class="easyui-textbox"></select>
						</div>
						<div class="fitem">
							<span style="width:30px;display:inline-block;"></span>
							<span style="width:80px;display:inline-block;">JPY</span>
							<select name="ejpy" id="ejpy" style="width:120px;" class="easyui-textbox"></select>
						</div>
					</fieldset>	
				</div>
			</div>

		<div style="clear:both;margin-bottom:15px;"></div>
	
		<table id="dg_editor" title="EDIT SALES INVOICING" class="easyui-datagrid" style="width:1000px;height:300px;">
		</table>
		<div id="etoolbar">
			<a href="javascript:void(0)" id="addpartno" class="easyui-linkbutton c6" iconCls="icon-add" plain='true' onclick="efindPartDN()">Find Delivery Note</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-remove" plain='true' onclick="destroyEditor()">Delete</a>
		</div>

		<div style="margin-top:10px; margin-left:400px;width:300px; float:left;">
			<div class="fitem">
				<span style="width:105px;display:inline-block;">Total Tax Basis</span>
				<input name="etotal_taxbasis" id="etotal_taxbasis" class="easyui-numberbox" value="1234567.89" data-options="precision:5,groupSeparator:','">
			</div>
			<div class="fitem">
				<span style="width:105px;display:inline-block;">Total Vat</span>
				<input name="etotal_vat" id="etotal_vat" class="easyui-numberbox" value="1234567.89" data-options="precision:5,groupSeparator:','">
			</div>
		</div>

		<div style="margin-top:10px;">
			<div class="fitem">
				<span style="width:105px;display:inline-block;">Grand Total </span>
				<input name="egrand_tot" id="egrand_tot"  class="easyui-numberbox" value="1234567.89" data-options="precision:5,groupSeparator:','">
			</div>
		</div>
		</form>
	</div>

	<div id="dlg_e-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveEditor()" style="width:90px">Update</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_editor').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveSI()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<div id="dlg-buttons-proses">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="calculate()" style="width:90px">Calculate</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_eti').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<div id="dlg-buttons-proses1">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="calculate1()" style="width:90px">Calculate</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_ti').dialog('close')" style="width:90px">Cancel</a>
	</div>

	<div id="dlg_eFindDN" title="Find Part" class="easyui-window" style="height:350px; width:748px;" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<table id="dg_eFindDN" class="easyui-datagrid" style="width:832px;height:312px;"
				url=""
				rownumbers="true" singleSelect="true"
				sortName="brg_codebarang" sortOrder="asc">
		</table>
	</div>

	<div id="dlg_dn" title="Find Delivery Note" class="easyui-window" style="height:350px; width:748px;" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<table id="dg_findPart" class="easyui-datagrid" style="width:832px;height:312px;"
				url=""
				rownumbers="true" singleSelect="true"
				sortName="delivery_date" sortOrder="desc">
		</table>
	</div>

	<div id="dlg_ti" class="easyui-dialog" style="width:800px;height:400px;padding:10px 20px" closed="true" buttons="#dlg-buttons-proses1">
		<table id="dg_ti" class="easyui-datagrid" style="width:750px;height:300px; magin-left:50px;"
				url=""
				rownumbers="true" singleSelect="true"
				sortName="brg_codebarang" sortOrder="asc">
		</table>
	</div>

	<div id="dlg_eti" class="easyui-dialog" style="width:800px;height:400px;padding:10px 20px" closed="true" buttons="#dlg-buttons-proses">
		<table id="dg_eti" class="easyui-datagrid" style="width:750px;height:300px; magin-left:50px;"
		url=""
		rownumbers="true" singleSelect="true"
		sortName="brg_codebarang" sortOrder="asc">
		</table>
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
			// ada cinta ketinggalan di secangkir kopimu
			var lastIndex;
			var subtot;
			var esubtot;

			function saveEditor(){
				var total = $('#dg_editor').datagrid('getData').total;
				var i = 0;
				var idx=0;
				for(i=0;i<total;i++){
					//alert($('#dg_editor').datagrid('getData').rows[i].doc_no);
					idx=i;
					$('#dg_editor').datagrid('endEdit', idx);

					$.post('sales_invoicing_update.php',{				
						date: $('#etax_date').datebox('getValue'),
						customer: $('#ecus_name').combobox('getValue'),
						category: $('#ecategory').combobox('getValue'),
						sales_invoiceno: $('#esales_invoiceno').textbox('getValue'),
						due_date: $('#edue_date').datebox('getValue'),
						payment_term: $('#epayment_term').combobox('getValue'),
						usd: $('#eusd').textbox('getValue'),
						jpy: $('#ejpy').textbox('getValue'),
						doc_no: $('#dg_editor').datagrid('getData').rows[i].doc_no,
						invoice_date: $('#dg_editor').datagrid('getData').rows[i].salesinvoicetax_date,
						tax_invoiceno: $('#dg_editor').datagrid('getData').rows[i].sales_invoice_tax_no,
						currency_: $('#dg_editor').datagrid('getData').rows[i].si_currency,
						kurs_beli: $('#dg_editor').datagrid('getData').rows[i].selling_rate,
						exchange_rate: $('#dg_editor').datagrid('getData').rows[i].tax_rate,
						tax_basis: $('#dg_editor').datagrid('getData').rows[i].tax_basis,
						vat: $('#dg_editor').datagrid('getData').rows[i].vat,
						sub_total: $('#dg_editor').datagrid('getData').rows[i].subtotal,
						tax_basis_idr: $('#dg_editor').datagrid('getData').rows[i].tax_basis_idr,
						vat_idr: $('#dg_editor').datagrid('getData').rows[i].vat_idr,	
						subtot_idr: $('#dg_editor').datagrid('getData').rows[i].subtotal_idr

					}).done(function(res){
						//alert(res);
						$('#dlg_editor').dialog('close');
						$('#dg').datagrid('reload');
					});
				}
			}

			function destroyEditor(){
				var row = $('#dg_editor').datagrid('getSelected');			
				var idx = $("#dg_editor").datagrid("getRowIndex", row);

				if (row){
					var code = row.doc_no;
					$.messager.confirm('CONFIRM','Are you sure you want to remove?',function(r){
						if (r){
							$.post('sales_invoicing_destroy_editor.php',{
								doc_no: code
							}).done(function(res){
								//alert(res);
								$('#dg_editor').datagrid('deleteRow', idx);
								var row = $('#dg_editor').datagrid('getData').rows;
								var i = 0; 
								var subtotal=0;
								var vat=0;
								var total = $('#dg_editor').datagrid('getData').total;
								for(i=0;i<total;i++){
									$('#dg_editor').datagrid('endEdit', i);
									var sb = $('#dg_editor').datagrid('getData').rows[i].tax_basis_idr.replace(/,/g,'');
									var vt = $('#dg_editor').datagrid('getData').rows[i].vat_idr.replace(/,/g,'');
									var subtotal = subtotal+ parseFloat(sb); 
									var vattotal = vat + parseFloat(vt);
									var grandtot = subtotal + vattotal
									subtot = parseFloat($.trim(subtotal));
									vattot = parseFloat($.trim(vattotal));
									grantot = parseFloat($.trim(grandtot));
									$('#etotal_taxbasis').numberbox('setValue', subtot);
									$('#etotal_vat').numberbox('setValue', vattot);
									$('#egrand_tot').numberbox('setValue', grantot);
								}
								
								$('#dg_editor').datagrid('reload');
							});
						}
					});
				}
			}

			function efindPartDN(){
				$('#dg_eFindDN').datagrid('reload');
				var dg = $('#dg_eFindDN').datagrid();
				$('#dlg_eFindDN').window('open').window('setTitle','FIND DELIVERY NOTE');
				$('#dg_eFindDN').datagrid('loadData', []);
				$('#dg_eFindDN').datagrid({
				url: 'sales_invoicing_get_part.php?cus_name='+$('#ecus_name').combobox('getValue')+'&category='+$('#category').combobox('getValue')+'&currency='+$('#ecurrency').combobox('getValue')
				});			

				dg.datagrid('enableFilter');
			}

			function findPart(){
				$('#dg_findPart').datagrid('reload');
				var dg = $('#dg_findPart').datagrid();
				$('#dlg_dn').window('open').window('setTitle','FIND DELIVERY NOTE');
				$('#dg_findPart').datagrid('loadData', []);
				$('#dg_findPart').datagrid({
				url: 'sales_invoicing_get_part.php?cus_name='+$('#cus_name').combobox('getValue')+'&category='+$('#category').combobox('getValue')+'&currency='+$('#currency').combobox('getValue')
				});			

				dg.datagrid('enableFilter');
			}

			function deleteInv(){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					$.messager.confirm('CONFIRM','Are you sure you want to remove?',function(r){
						if (r){
							$.post('sales_invoicing_destroy.php',{sales_invoicing_no:row.sales_invoicing_no}).done(function(res){
								//alert(res);
								$('#dg').datagrid('reload');
							});
						}
					});
				}
			}

			function editInv(){
			//alert();
			var row = $('#dg').datagrid('getSelected');
				if (row) {
					$('#dlg_editor').dialog('open').dialog('setTitle', 'EDIT SALES INVOICING');
					$('#etax_date').datebox('setValue', row.salesinvoicetax_date);
					$('#esales_invoiceno').textbox('setValue', row.sales_invoicing_no);
					$('#ecus_name').combobox('setValue', $.trim(row.kode_customer));
					$('#ecategory').combobox('setValue', row.category);
					$('#ecurrency').combobox('setValue', row.si_currency);
					$('#edue_date').datebox('setValue', row.payment_due);
					$('#epayment_term').combobox('setValue',row.term_of_payment);
					$('#eusd').textbox('setValue', row.exchangerate_usd);
					$('#ejpy').textbox('setValue', row.exchangerate_jpy);
					$('#etotal_taxbasis').numberbox('setValue', row.tot_taxbasis);
					$('#etotal_vat').numberbox('setValue', row.tot_vat);
					$('#egrand_tot').numberbox('setValue', row.grand_total);
					$('#dg_editor').datagrid('loadData', []);
					$('#dg_editor').datagrid({
						url: 'sales_invoicing_getedit.php?sales_invoicing_no='+row.sales_invoicing_no
					});
				};
			}

			var idx;
			$('#dg_editor').datagrid({ 
				url:'',
			    toolbar: '#etoolbar',
			    singleSelect: true,
				//pagination: true,
				rownumbers: true,
				//remoteFilter: true,
				sortName: '',
				sortOrder: 'asc',
			    columns:[[
			    	{field:'doc_no',title:'doc no',width:130, halign: 'center', hidden:'true'},
					{field:'salesinvoicetax_date',title:'Sales Invoice Tax<br>Date',width:130, halign: 'center'},
					{field:'sales_invoice_tax_no',title:'Sales Invoice Tax No',width:150, halign: 'center'},
					{field:'si_currency',title:'Currency',width:80, halign: 'center'},
					{field:'selling_rate',title:'Selling Rate',width:80, halign: 'center', align:'right'},
					{field:'tax_rate',title:'Rate Tax', halign: 'center',width:80, align:'right'},
					{field:'tax_basis',title:'Tax Basis', align:'right', halign: 'center', width:100},
					{field:'vat',title:'VAT', halign: 'center', align:'right', width:100},
					{field:'subtotal',title:'Sub Total', align:'right', width:100, halign: 'center'},
					{field:'tax_basis_idr',title:'Tax<br>Basis(IDR)', align:'right', width:100, halign: 'center'},
					{field:'vat_idr',title:'VAT<br>(IDR)', align:'right', width:100, halign: 'center'},
					{field:'subtotal_idr',title:'Sub Total<br>(IDR)', align:'right', width:100, halign: 'center'}
			    ]]
			});

			$(function(){
				$('#pd_periode_awal').datebox('disable');
				$('#pd_periode_akhir').datebox('disable');
				$('#ck_date').change(function(){
					if ($(this).is(':checked')) {
						$('#pd_periode_awal').datebox('disable');
						$('#pd_periode_akhir').datebox('disable');
					}
					if (!$(this).is(':checked')) {
						$('#pd_periode_awal').datebox('enable');
						$('#pd_periode_akhir').datebox('enable');
					};
				})

				$('#dn_cust').combobox('disable');
				$('#ck_cust').change(function(){
					if ($(this).is(':checked')) {
						$('#dn_cust').combobox('disable');
					}
					if (!$(this).is(':checked')) {
						//alert('HOREEEEEE!!!');
						$('#dn_cust').combobox('enable');					
					};
				})

				$('#pd_awal').datebox('disable');
				$('#pd_akhir').datebox('disable');
				$('#ck_pd').change(function(){
					if ($(this).is(':checked')) {
						$('#pd_awal').datebox('disable');
						$('#pd_akhir').datebox('disable');
					}
					if (!$(this).is(':checked')) {
						$('#pd_awal').datebox('enable');
						$('#pd_akhir').datebox('enable');
					};
				})

				$('#currency1').combobox('disable');
				$('#ck_cur').change(function(){
					if ($(this).is(':checked')) {
						$('#currency1').combobox('disable');
					}else{
						$('#currency1').combobox('enable');
					}
				})

				$('#ar_status').combobox('disable');
				$('#ck_ar').change(function(){
					if ($(this).is(':checked')) {
						$('#ar_status').combobox('disable');
					}else{
						$('#ar_status').combobox('enable');
					}
				});
			})

			$(function(){
				filterData();
			});		
	
		function saveSI(){
			var i = 0;
			var proc = 0;
			var total = $('#dg_entry').datagrid('getData').total;
			//alert(total);
			//alert(req_by);
			for(i=0;i<total;i++){
				$('#dg_entry').datagrid('endEdit', i);
				//alert();
				$.post('sales_invoicing_save.php',{
					date: $('#tax_date').datebox('getValue'),
					customer: $('#cus_name').combobox('getValue'),
					category: $('#category').combobox('getValue'),
					sales_invoiceno: $('#sales_invoiceno').textbox('getValue'),
					due_date: $('#due_date').datebox('getValue'),
					payment_term: $('#payment_term').combobox('getValue'),
					usd: $('#usd').textbox('getValue'),
					jpy: $('#jpy').textbox('getValue'),
					invoice_date: $('#dg_entry').datagrid('getData').rows[i].invoice_date,
					tax_invoiceno: $('#dg_entry').datagrid('getData').rows[i].tax_invoiceno,
					currency_: $('#dg_entry').datagrid('getData').rows[i].currency,
					kurs_beli: $('#dg_entry').datagrid('getData').rows[i].kurs_beli,
					exchange_rate: $('#dg_entry').datagrid('getData').rows[i].exchange_rate,
					tax_basis: $('#dg_entry').datagrid('getData').rows[i].tax_basis,
					vat: $('#dg_entry').datagrid('getData').rows[i].vat,
					sub_total: $('#dg_entry').datagrid('getData').rows[i].sub_total,
					tax_basis_idr: $('#dg_entry').datagrid('getData').rows[i].tax_basis_idr,
					vat_idr: $('#dg_entry').datagrid('getData').rows[i].vat_idr,	
					subtot_idr: $('#dg_entry').datagrid('getData').rows[i].subtot_idr
				}).done(function(res){
				//alert(res);
				$('#dlg').dialog('close');
				$('#dg').datagrid('reload');
				});
			}
		}

			$('#dg_findPart').datagrid({
				url: '',
			    singleSelect: true,
				rownumbers: true,
			    columns:[[
			    	{field:'invoice_date',title:'Sales Invoice Tax<br>Date',width:300, halign:'center'},
		 		 	{field:'tax_invoiceno',title:'Sales Invoice Tax No.',width:500, halign:'center'}
			    ]],
			    onDblClickRow:function(id, row){
			    	$('#dlg_dn').window('close');
					$('#usd').textbox('setValue', row.kurs_beli_usd);
					$('#jpy').textbox('setValue', row.kurs_beli_jpy);
					var dg_entry = $('#dg_entry').datagrid();
					var total = 1;
					var i =0;
					var indx = 0;
					var subtotal=0;
						for (i = 0; i < total ; i++) {
							//indx = indx + i;
							$.get('sales_invoicing_get_partdtl.php?tax_invoiceno='+row.tax_invoiceno, function(res){
							//alert(i+' '+res);
							//alert(res[i].part_no);
							$('#dg_entry').datagrid('insertRow',{
							index: idx,	// index start with 0
								row: {
									//doc_no: code,
									invoice_date: res[i].invoice_date,
									tax_invoiceno: res[i].tax_invoiceno,
									currency: res[i].currency,
									kurs_beli: res[i].kurs_beli,
									exchange_rate: res[i].exchange_rate,
									tax_basis: res[i].tax_basis,
									vat: res[i].vat,
									sub_total: res[i].sub_total,
									tax_basis_idr: res[i].tax_basis_idr,
									vat_idr: res[i].vat_idr,
									subtot_idr: res[i].subtot_idr
									}
								})
							var row = $('#dg_entry').datagrid('getData').rows;
							var a=0;
							var taxbasis =0;
							var vat=0;
							var grandtot =0;
							var total = $('#dg_entry').datagrid('getData').total;

							for(a=0;a<total;a++){
								var tb = $('#dg_entry').datagrid('getData').rows[a].tax_basis.replace(/,/g,''); //alert(sb);
								var vt = $('#dg_entry').datagrid('getData').rows[a].vat.replace(/,/g,'');
								var taxbasis = taxbasis+ parseFloat(tb); //alert(subtotal);
								var vat = vat + parseFloat(vt);
								subtax = parseFloat($.trim(taxbasis));
								subvat = parseFloat($.trim(vat));
								grandtot = subtax+subvat;
								$('#total_taxbasis').numberbox('setValue', subtax);
								$('#total_vat').numberbox('setValue', subvat);
								$('#grand_tot').numberbox('setValue', grandtot);
							}

							});	
						}			
						var i = 0;
						var subtotal=0;
						}
		    });

			
			$('#dg_eFindDN').datagrid({
				columns:[[
				    {field:'invoice_date',title:'Sales Invoice Tax<br>Date',width:300, halign:'center'},
		 		 	{field:'tax_invoiceno',title:'Sales Invoice Tax No.',width:500, halign:'center'}
			    ]],
			    onDblClickRow:function(id, row){
			    	$('#dlg_eFindDN').window('close');
					var dg_editor = $('#dg_entry').datagrid(); //alert(dg_editor);
					var total = 1;
					var i = 0;
					var indx = 0;
					var subtotal = 0;
					for (i = 0; i < total ; i++) {
						indx = indx + i;
						$.get('sales_invoicing_get_partdtl.php?tax_invoiceno='+row.tax_invoiceno, function(res){
						//	alert(i+' '+res);
						//	alert(res[i].part_no);
							$('#dg_editor').datagrid('insertRow',{
								index: idx,	// index start with 0
									row: {
										//doc_no: code,
										salesinvoicetax_date: res[i].invoice_date,
										sales_invoice_tax_no: res[i].tax_invoiceno,
										si_currency: res[i].currency,
										selling_rate: res[i].kurs_beli,
										tax_rate: res[i].exchange_rate,
										tax_basis: res[i].tax_basis,
										vat: res[i].vat,
										subtotal: res[i].sub_total,
										tax_basis_idr: res[i].tax_basis_idr,
										vat_idr: res[i].vat_idr,
										subtotal_idr: res[i].subtot_idr
									}
							})

						var row = $('#dg_editor').datagrid('getData').rows;
						var taxbasis = 0;
						var vat = 0;
						var a=0;
						var subtotal =0;
						var total = $('#dg_editor').datagrid('getData').total;
						for(a=0;a<total;a++){
								var tb = $('#dg_editor').datagrid('getData').rows[a].tax_basis.replace(/,/g,''); //alert(sb);
								var vt = $('#dg_editor').datagrid('getData').rows[a].vat.replace(/,/g,'');
								var taxbasis = taxbasis+ parseFloat(tb); //alert(subtotal);
								var vat = vat + parseFloat(vt);
								subtax = parseFloat($.trim(taxbasis));
								subvat = parseFloat($.trim(vat));
								grandtot = subtax+subvat;
								$('#etotal_taxbasis').numberbox('setValue', subtax);
								$('#etotal_vat').numberbox('setValue', subvat);
								$('#egrand_tot').numberbox('setValue', grandtot);
							}


						var nol=0;
						i++;
						});	
					}
					var i = 0;
					var subtotal=0;
					//var total = row.total;
			    }
		    });

			$('#dg_ti').datagrid({
				columns:[[
					{field: 'ck', title: '<br>', align:'center', hidden:false, width: 30, sortable: true, 
					editor: {type:'checkbox', options:{disable:true, on: 'TRUE',off: 'FALSE'}}},
					{field:'tax_invoiceno',title:'Tax Invoice No',width:150, halign:'center'},
				    {field:'nama_customer',title:'Customer',width:200, halign:'center'},
				    {field:'tax_basis',title:'Tax Basis',width:120, halign:'center', align:'right'},
				    {field:'vat',title:'Vat',width:100, halign:'center', align:'right'},
				    {field:'down_payment',title:'Down Payment',width:120, halign:'center', align:'right'}
			    ]],
			    onLoadSuccess:function(id, row){
				var total = $('#dg_ti').datagrid('getData').total;
				totc = $('#dg_ti').datagrid('getData').total;
					for (var i = 0; i <total; i++) {
						$('#dg_ti').datagrid('beginEdit',i);
						var data = $('#dg_ti').datagrid('getEditor',{index:i, field:'ck'});
						$(data.target).prop('disabled', true);
					};
				},
			    onClickRow: function(id, row){
			    	var total = $('#dg_ti').datagrid('getData').total;
					var idx = $('#dg_ti').datagrid('getData').rows[id].tax_invoiceno;
					var ck = $('#dg_ti').datagrid('getEditor',{index:id,field:'ck'});
					var cus = $('#dg_ti').datagrid('getData').rows[id].nama_customer;
					var cat = $('#dg_ti').datagrid('getData').rows[id].category;
					var dp = $('#dg_ti').datagrid('getData').rows[id].down_payment; //alert(dp);

					if (parseInt(dp)>0) {
					if (total==totc) {
						if ($(ck.target).attr('checked')) {
							$(ck.target).prop('checked', false);
							totc=totc+1;
							//alert(totc);
							/*lastdate="";
							lastcus="";*/
						}else{
							$(ck.target).prop('checked', true);
							totc=totc-1;
							lastdate=cat;
							lastcus=cus;
						}
					}else if ((total-1)==totc) {
						if (lastdate==cat && lastcus==cus) {
							if ($(ck.target).attr('checked')) {
								$(ck.target).prop('checked', false);
								totc=totc+1;
								lastdate="";
								lastcus="";
							}else{
								$(ck.target).prop('checked', true);
								totc=totc-1;
								lastdate=cat;
								lastcus=cus;
							}
						}else{
							$(ck.target).prop('checked', false);
						//	$.messager.alert('Information','Down Payment is Null','error');
						}
					}else{
						
						if (lastdate==cat && lastcus==cus) {
							if ($(ck.target).attr('checked')) {
								$(ck.target).prop('checked', false);
								totc=totc+1;
								/*lastdate="";
								lastcus="";*/
							}else{
								$(ck.target).prop('checked', true);
								totc=totc-1;
								lastdate=cat;
								lastcus=cus;
							}
						}else{
							$(ck.target).prop('checked', false);
						//	$.messager.alert('Information','Down Payment is Null','error');
						}
					}
				}else{
					//$(ck.target).prop('checked', false);
					//$.messager.alert('Information','stock empty','error');
					if (total==totc) {
						if ($(ck.target).attr('checked')) {
							//alert('checked');
							$(ck.target).prop('checked', false);
							totc=totc+1;
							/*lastdate="";
							lastcus="";*/
						}else{
							$.messager.confirm('CONFIRM','Down Payment is Null!',function(r){
								if (r){
									$(ck.target).prop('checked', true);
									totc=totc-1;
									lastdate=cat;
									lastcus=cus;
									//approval_sts = 'true';
								}
							});
						}
					}else if ((total-1)==totc) {
						if (lastdate==cat && lastcus==cus) 
						{
							if ($(ck.target).attr('checked')) {
								//alert('checked');
								$(ck.target).prop('checked', false);
								totc=totc+1;
								lastdate="";
								lastcus="";
							}else{
								$.messager.confirm('CONFIRM','Down Payment is Null!',function(r){
									if (r){
										$(ck.target).prop('checked', true);
										totc=totc-1;
										lastdate=cat;
										lastcus=cus;
										//approval_sts = 'true';
									}
								});
							}
						}else{
							$(ck.target).prop('checked', false);
							//$.messager.alert('Information','Schedule Delivery Date not Same','error');
						}
					}else{
						
						if (lastdate==cat && lastcus==cus) 
						{
							if ($(ck.target).attr('checked')) {
								//alert('checked');
								$(ck.target).prop('checked', false);
								totc=totc+1;
								/*lastdate="";
								lastcus="";*/
							}else{
								$.messager.confirm('CONFIRM','Down Payment is Null!',function(r){
									if (r){
										$(ck.target).prop('checked', true);
										totc=totc-1;
										lastdate=cat;
										lastcus=cus;
										//approval_sts = 'true';
									}
								});
							}
						}else{
							$(ck.target).prop('checked', false);
						//	$.messager.alert('Information','Schedule Delivery Date not Same','error');
						}
					}
				}

			    }
			});

			$('#dg_eti').datagrid({
				columns:[[
					{field: 'ck', title: '<br>', align:'center', hidden:false, width: 30, sortable: true, 
					editor: {type:'checkbox', options:{disable:true, on: 'TRUE',off: 'FALSE'}}},
					{field:'tax_invoiceno',title:'Tax Invoice No',width:150, halign:'center'},
				    {field:'nama_customer',title:'Customer',width:200, halign:'center'},
				    {field:'tax_basis',title:'Tax Basis',width:120, halign:'center', align:'right'},
				    {field:'vat',title:'Vat',width:100, halign:'center', align:'right'},
				    {field:'down_payment',title:'Down<br>Payment',width:120, halign:'center', align:'right'}
			    ]],
			    onLoadSuccess:function(id, row){
				var total = $('#dg_eti').datagrid('getData').total;
				totc = $('#dg_eti').datagrid('getData').total;
					for (var i = 0; i <total; i++) {
						$('#dg_eti').datagrid('beginEdit',i);
						var data = $('#dg_eti').datagrid('getEditor',{index:i, field:'ck'});
						$(data.target).prop('disabled', true);
					};
				},
			    onClickRow: function(id, row){
			    	var total = $('#dg_eti').datagrid('getData').total;
					var idx = $('#dg_eti').datagrid('getData').rows[id].tax_invoiceno;
					var ck = $('#dg_eti').datagrid('getEditor',{index:id,field:'ck'});
					var cus = $('#dg_eti').datagrid('getData').rows[id].nama_customer;
					var cat = $('#dg_eti').datagrid('getData').rows[id].category;
					var dp = $('#dg_eti').datagrid('getData').rows[id].down_payment;

					if (parseInt(dp)>0) {
					if (total==totc) {
						if ($(ck.target).attr('checked')) {
							$(ck.target).prop('checked', false);
							totc=totc+1;
							//alert(totc);
							/*lastdate="";
							lastcus="";*/
						}else{
							$(ck.target).prop('checked', true);
							totc=totc-1;
							lastdate=cat;
							lastcus=cus;
						}
					}else if ((total-1)==totc) {
						if (lastdate==cat && lastcus==cus) {
							if ($(ck.target).attr('checked')) {
								$(ck.target).prop('checked', false);
								totc=totc+1;
								lastdate="";
								lastcus="";
							}else{
								$(ck.target).prop('checked', true);
								totc=totc-1;
								lastdate=cat;
								lastcus=cus;
							}
						}else{
							$(ck.target).prop('checked', false);
						//	$.messager.alert('Information','Down Payment is Null','error');
						}
					}else{
						
						if (lastdate==cat && lastcus==cus) {
							if ($(ck.target).attr('checked')) {
								$(ck.target).prop('checked', false);
								totc=totc+1;
								/*lastdate="";
								lastcus="";*/
							}else{
								$(ck.target).prop('checked', true);
								totc=totc-1;
								lastdate=cat;
								lastcus=cus;
							}
						}else{
							$(ck.target).prop('checked', false);
						//	$.messager.alert('Information','Down Payment is Null','error');
						}
					}
				}else{
					//$(ck.target).prop('checked', false);
					//$.messager.alert('Information','stock empty','error');
					if (total==totc) {
						if ($(ck.target).attr('checked')) {
							//alert('checked');
							$(ck.target).prop('checked', false);
							totc=totc+1;
							/*lastdate="";
							lastcus="";*/
						}else{
							$.messager.confirm('CONFIRM','Down Payment is Null!',function(r){
								if (r){
									$(ck.target).prop('checked', true);
									totc=totc-1;
									lastdate=cat;
									lastcus=cus;
									//approval_sts = 'true';
								}
							});
						}
					}else if ((total-1)==totc) {
						if (lastdate==cat && lastcus==cus) 
						{
							if ($(ck.target).attr('checked')) {
								//alert('checked');
								$(ck.target).prop('checked', false);
								totc=totc+1;
								lastdate="";
								lastcus="";
							}else{
								$.messager.confirm('CONFIRM','Down Payment is Null',function(r){
									if (r){
										$(ck.target).prop('checked', true);
										totc=totc-1;
										lastdate=cat;
										lastcus=cus;
										//approval_sts = 'true';
									}
								});
							}
						}else{
							$(ck.target).prop('checked', false);
							//$.messager.alert('Information','Schedule Delivery Date not Same','error');
						}
					}else{
						
						if (lastdate==cat && lastcus==cus) 
						{
							if ($(ck.target).attr('checked')) {
								//alert('checked');
								$(ck.target).prop('checked', false);
								totc=totc+1;
								/*lastdate="";
								lastcus="";*/
							}else{
								$.messager.confirm('CONFIRM','Down Payment is Null',function(r){
									if (r){
										$(ck.target).prop('checked', true);
										totc=totc-1;
										lastdate=cat;
										lastcus=cus;
										//approval_sts = 'true';
									}
								});
							}
						}else{
							$(ck.target).prop('checked', false);
						//	$.messager.alert('Information','Schedule Delivery Date not Same','error');
						}
					}
				}

			    }
			});

			function calculate1(){
			var i = 0;
			var proc = 0;
			var subtotal=0;
			var nol=0;
			var total = $('#dg_ti').datagrid('getData').total;
					
			for(i=0;i<total;i++){
				$('#dg_ti').datagrid('endEdit', i);
				var ck = $('#dg_ti').datagrid('getData').rows[i].ck;
				if (ck == 'TRUE') {

					var down_payment = $('#dg_ti').datagrid('getData').rows[i].down_payment;
					var t_b = $('#dg_ti').datagrid('getData').rows[i].tax_basis; //alert(t_b);
					var vat = $('#dg_ti').datagrid('getData').rows[i].vat; //alert(vat);

					var subtotal = subtotal+ parseFloat(down_payment);
					subtot = parseFloat($.trim(subtotal)); //alert(subtot);
					
				}
				if (ck=='FALSE'){
				}					
			}
			$('#dlg_ti').dialog('close');
			$('#ti_dp').numberbox('setValue', subtot);
			}

			$('#ti_dp').numberbox({
				onChange: function(rec){
					var dp = parseFloat($('#ti_dp').numberbox('getValue')); //alert(dp);
					var t_b = parseFloat($('#ti_dpp').numberbox('getValue')); //alert(t_b);
					var vat = parseFloat($('#ti_vat').numberbox('getValue')); //alert(vat);
					var art = parseFloat($('#ti_art').numberbox('getValue')); //alert(art);
 					var grand = parseFloat(t_b)+parseFloat(vat) - (parseFloat(art)+parseFloat(dp)); //alert(grand);
 					$('#ti_grandtot').numberbox('setValue',(grand));
				}
			});

			function calculate(){
			var i = 0;
			var proc = 0;
			var subtotal=0;
			var nol=0;
			var total = $('#dg_eti').datagrid('getData').total;
					
			for(i=0;i<total;i++){
				$('#dg_eti').datagrid('endEdit', i);
				var ck = $('#dg_eti').datagrid('getData').rows[i].ck;
				if (ck == 'TRUE') {

					var down_payment = $('#dg_eti').datagrid('getData').rows[i].down_payment;
					var t_b = $('#dg_eti').datagrid('getData').rows[i].tax_basis; //alert(t_b);
					var vat = $('#dg_eti').datagrid('getData').rows[i].vat; //alert(vat);

					var subtotal = subtotal+ parseFloat(down_payment);
					subtot = parseFloat($.trim(subtotal)); //alert(subtot);
					
				}
				if (ck=='FALSE'){
				}					
			}
			$('#dlg_eti').dialog('close');
			$('#eti_dp').numberbox('setValue', subtot);
			}
			
			$('#eti_dp').numberbox({
				onChange: function(rec){
					var dp = parseFloat($('#eti_dp').numberbox('getValue')); //alert(dp);
					var t_b = parseFloat($('#eti_dpp').numberbox('getValue')); //alert(t_b);
					var vat = parseFloat($('#eti_vat').numberbox('getValue')); //alert(vat);
					var art = parseFloat($('#eti_art').numberbox('getValue')); //alert(art);
 					var grand = parseFloat(t_b)+parseFloat(vat) - (parseFloat(art)+parseFloat(dp)); //alert(grand);
 					$('#eti_grandtot').numberbox('setValue',(grand));
				}
			});

			function addInv(){
				$('#dlg').dialog('open').dialog('setTitle','ADD SALES INVOICING');
				$('#fm').form('clear');
				$('#tax_date').datebox('setValue','<?=date('Y-m-d')?>');
				$('#total_taxbasis').numberbox('setValue',0);
				$('#total_vat').numberbox('setValue',0);
				$('#grand_tot').numberbox('setValue',0);
				$('#dg_entry').datagrid('loadData',[]);

				$.ajax({
				type: 'GET',
				url: 'json/json_sino.php',
				data: { kode:'kode' },
				    success: function(data){
				   	$('#sales_invoiceno').textbox('setValue','SI'+'<?=date('ym');?>'+data[0].kode);
				   	$('#dg_entry').datagrid('reload');
					}
				});
			}

			function editClear(){
				unsetSession('unset_po', $('#po_no').textbox('getValue'));
				$('#dg_entry').datagrid('loadData', []);
			}

			var idx;
			$('#dg_entry').datagrid({ 
				url:'',
			    toolbar: '#entoolbar',
			    singleSelect: true,
				//pagination: true,
				rownumbers: true,
				//remoteFilter: true,
				sortName: 'part_no',
				sortOrder: 'asc',
			    columns:[[
					{field:'invoice_date',title:'Sales Invoice Tax<br>Date',width:130, halign: 'center'},
				    {field:'tax_invoiceno',title:'Sales Invoice Tax No',width:150, halign: 'center'},
				    {field:'currency',title:'Currency',width:80, halign: 'center'},
				    {field:'kurs_beli',title:'Selling Rate',width:80, halign: 'center', align:'right'},
				    {field:'exchange_rate',title:'Rate Tax', halign: 'center',width:80, align:'right'},
				    {field:'tax_basis',title:'Tax Basis', align:'right', halign: 'center', width:100},
				    {field:'vat',title:'VAT', halign: 'center', align:'right', width:100},
					{field:'sub_total',title:'Sub Total', align:'right', width:100, halign: 'center'},
					{field:'tax_basis_idr',title:'Tax<br>Basis(IDR)', align:'right', width:100, halign: 'center'},
					{field:'vat_idr',title:'VAT<br>(IDR)', align:'right', width:100, halign: 'center'},
					{field:'subtot_idr',title:'Sub Total<br>(IDR)', align:'right', width:100, halign: 'center'}
			    ]]
			});

			function addRemove(){
				var row = $('#dg_entry').datagrid('getSelected');
				var sales_invoiceno = $('#sales_invoiceno').textbox('getValue');
				if (row){
					var idx = $("#dg_entry").datagrid("getRowIndex", row);
					$('#dg_entry').datagrid('deleteRow', idx);

					var row = $('#dg_entry').datagrid('getData').rows;
					var i = 0; 
					var taxbasis=0;
					var vat=0;
					var total = $('#dg_entry').datagrid('getData').total;
					for(i=0;i<total;i++){
						$('#dg_entry').datagrid('endEdit', i);
						var tb = $('#dg_entry').datagrid('getData').rows[i].tax_basis.replace(/,/g,'');
						var vt = $('#dg_entry').datagrid('getData').rows[i].vat.replace(/,/g,'');
						var taxbasis = taxbasis+ parseFloat(tb);
						var vat = vat + parseFloat(vt);
						subtax = parseFloat($.trim(taxbasis));
						subvat = parseFloat($.trim(vat));
						grandtot = subtax+subvat;
						$('#total_taxbasis').numberbox('setValue', subtax);
						$('#total_vat').numberbox('setValue', subvat);
						$('#grand_tot').numberbox('setValue', grandtot);
					}
				}
			}
			
			var url;
			var pdf_url='';
			var xls_url='';

			function filterData() {
				var ck_cust = 'false';
				var ck_date = 'false';
				var ck_cur = 'false';
				var ck_pd = 'false';
				var ck_ar = 'false';
				//alert();
				if ($('#ck_date').attr("checked")) {
					ck_date = "true";
				}
				if($('#ck_cust').attr("checked")){
					ck_cust='true';
				}
				if($('#ck_cur').attr("checked")){
					ck_cur='true';
				}
				if ($('#ck_pd').attr("checked")) {
					ck_pd = "true";
				}
				if ($('#ck_ar').attr("checked")) {
					ck_ar = "true";
				}
				$('#dg').datagrid('load', {
					pd_periode_awal: $('#pd_periode_awal').datebox('getValue'),
					pd_periode_akhir: $('#pd_periode_akhir').datebox('getValue'),
					ck_date: ck_date,
					dn_cust: $('#dn_cust').combobox('getValue'),
					ck_cust: ck_cust,
					currency1: $('#currency1').combobox('getValue'),
					ck_cur: ck_cur,
					pd_awal: $('#pd_awal').datebox('getValue'),
					pd_akhir: $('#pd_akhir').datebox('getValue'),
					ck_pd: ck_pd,
					ar_status: $('#ar_status').combobox('getValue'),
					ck_ar: ck_ar
				});

				pdf = "?pd_periode_awal="+$('#pd_periode_awal').datebox('getValue')
					+"&pd_periode_akhir="+$('#pd_periode_akhir').datebox('getValue')
					+"&ck_date="+ck_date
					+"&dn_cust="+$('#dn_cust').combobox('getValue')
					+"&ck_cust="+ck_cust
					+"&currency1="+$('#currency1').combobox('getValue')
					+"&ck_cur="+ck_cur
					+"&pd_awal="+$('#pd_awal').datebox('getValue')
					+"&pd_akhir="+$('#pd_akhir').datebox('getValue')
					+"&ck_pd="+ck_pd
					+"&ar_status="+$('#ar_status').combobox('getValue')
					+"&ck_ar="+ck_ar
					;
				}

			function pdff(){
				if(pdf=='') {
					$.messager.show({
						title: 'PDF Report',
							msg: 'Data Not Defined'
					});
				} else {
					//window.open('askara_supplies_pdf1.php'+pdf, '_blank');
					window.open('internal_request_pdf.php'+pdf, '_blank');
				}
			}

			function pdffdetail(){
				if(pdf=='') {
					$.messager.show({
						title: 'PDF Report',
							msg: 'Data Not Defined'
					});
				} else {
					//window.open('askara_supplies_pdfdtl.php1'+pdf, '_blank');
					window.open('internal_request_pdfdtl.php'+pdf, '_blank');
				}
			}

			function pdfFormInternalRequest(){
				var row = $('#dg').datagrid('getSelected');
				if (row) {
					window.open('internal_request_pdfintreq.php?doc_no='+$.trim(row.doc_no),'_blank');
				} else {
					$.messager.show({
						title: 'PDF Report',
							msg: 'You can print Internal Request by selected Request No from the table'
					});
				}
			}
			
			$('#dg').datagrid({
			    url:'sales_invoicing_get.php',
			    toolbar: '#toolbar',
			    singleSelect: true,
				pagination: true,
				rownumbers: true,
				//remoteFilter: true,
				sortName: '',
				sortOrder: 'desc',
			    columns:[[
			    	{field:'doc_no',title:'doc no',width:130, halign: 'center', hidden:'true'},
				    {field:'nama_customer',title:'CUSTOMER',width:220, halign: 'center', sortable:true},
					{field:'sales_invoice_tax_no',title:'SALES INV TAX NO.',width:150, halign: 'center'},
					{field:'sales_invoicing_no',title:'SI. NO.',width:130, halign: 'center'},
					{field:'salesinvoicetax_date',title:'SI. DATE',width:130, halign: 'center'},
					{field:'payment_due',title:'PAYMENT DUE',width:130, halign: 'center'},
					{field:'si_currency',title:'CURRENCY',width:130, halign: 'center'},
					{field:'selling_rate',title:'SELLING RATE',width:130, halign: 'center'},
					{field:'tax_rate',title:'RATE TAX',width:130, halign: 'center'},
					{field:'tot_taxbasis',title:'TAX BASIS',width:130, halign: 'center'},
					{field:'tot_vat',title:'VAT',width:130, halign: 'center'},
					{field:'grand_total',title:'TOTAL',width:130, halign: 'center'},
					{field:'status_ar',title:'AR STATUS',width:130, halign: 'center'},
					{field:'account_receivable_no',title:'A/R NO.',width:130, halign: 'center'},
					{field:'usr_entry_date',title:'ENTRY DATE',width:130, halign: 'center'},
					{field:'user_entry',title:'USER',width:130, halign: 'center'}
			    ]]				
		    });

		$(function(){
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter', [{
				field:'ket_kategori',
				type:'combobox',
				options:{
					panelHeight:'auto',
					url:'json/json_category.php',
					valueField: 'ket_kategori',
					textField: 'ket_kategori',
					
					onChange:function(value){
						if ($.trim(value) == ''){
							dg.datagrid('removeFilterRule', 'ket_kategori');
						} else {
							//alert(value);
							dg.datagrid('addFilterRule', {
								field: 'ket_kategori',
								op: 'equal',
								value: $.trim(value)
							});
						}
						dg.datagrid('doFilter');
					}
				}
			}]);
		});
	
	</script>
    </body>
    </html>