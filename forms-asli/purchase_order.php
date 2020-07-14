<?php include("../connect/koneksi.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
require_once('___loginvalidation.php');
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Purchase Order</title>
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
	</style>
    </head>
    <body>
	<?php include ('../ico_logout.php'); ?>
	<!-- INI ENtRY PO -->
	<div id='dlg' class="easyui-dialog" style="width:960px;height:570px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:630px; float:left; margin-bottom:5px;"><legend>Create PO</legend>
			<div style="width:290px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Category</span>
					<select style="width:130px;" name="c_category" id="c_category" class="easyui-combobox" data-options=" url:'json/json_category.php',method:'get',valueField:'jenis_kategori',textField:'ket_kategori', panelHeight:'70px',
					onSelect: function(rec){
						var kategori = rec.ket_kategori;
						if($.trim(rec.ket_kategori)=='RM'){
							$('#subcat').show();
							$('#c_subcategory').combobox('setValue', '00001');
							$('#c_type').combobox('setValue', 'TRADING');
							kategori = $('#c_subcategory').combobox('getText');

						}else{
							$('#subcat').hide();
							$('#c_subcategory').combobox('setValue', '');
						}

						if(rec.ket_kategori=='NRM'){
							$('#c_subtype').combobox('setValue', 'NRM');
						}else{
							$('#c_subtype').combobox('setValue', '');
						}
						var date = $('#po_date').datebox('getValue');
						$.ajax({
							type: 'GET',
							url: 'json/json_po_code.php?kategori='+kategori+'&type='+$('#c_type').combobox('getText')+'&sub='+$('#c_subtype').combobox('getText'),
							data: { kode:'kode' },
							success: function(data){
								var kat = $('#c_category').combobox('getText');
								var type = $('#c_type').combobox('getText');
								var sub = $('#c_subtype').combobox('getText');
								var ket = '';
								if($.trim(kat)=='RM'){
									kat = $('#c_subcategory').combobox('getText');
								}else{
									kat = $('#c_category').combobox('getText');
								}

								if($.trim(kat)=='ALK'){
									ket='ALK';
								}

								if($.trim(kat)=='RM'){
									if($.trim(type)=='TRADING'){
										ket='RM';
									}
									if($.trim(type)=='ASKARA'){
										ket='RMAI';
									}
								}
								
								if($.trim(kat)=='GE'){
									ket = 'GE';
								}
								if($.trim(kat)=='GD'){
									ket = 'GD';
								}
								if($.trim(kat)=='GH'){
									ket = 'GH';
								}
								if($.trim(kat)=='GR'){
									ket = 'GR';
								}
								if($.trim(kat)=='GP'){
									ket = 'GP';
								}

								if($.trim(kat)=='NRM'){
									if($.trim(type)=='TRADING'){
										if($.trim(sub)=='NRM'){
											ket='NRM';
										}
										if($.trim(sub)=='TS'){
											ket='TS';
										}
									}

									if($.trim(type)=='ASKARA'){
										if($.trim(sub)=='NRM'){
											ket='AI';
										}
										if($.trim(sub)=='TS'){
											ket='AITS';
										}
									}
								}
								
								//var month = po_date.substr(1, 4);
								var bln = date.substr(5,2);
								var thn = date.substr(2,2);
								//alert(thn);
							 	//$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
							 	$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/'+bln+thn);
							}
						});
					}
					"></select>
				</div>
				<div class="fitem" id="subcat">
					<span style="width:130px;display:inline-block;">Sub Category</span>
					<select style="width:130px;" name="c_subcategory" id="c_subcategory" class="easyui-combobox" data-options=" url:'json/json_subcategory.php',method:'get',valueField:'kode_subkategori',textField:'nama_subkategori', panelHeight:'70px',
					onSelect: function(rec){
						var kategori = $('#c_category').combobox('getText');
						if($.trim($('#c_category').combobox('getText'))=='RM'){
							//$('#subcat').show();
							//$('#c_subcategory').combobox('setValue', rec.ket_kategori);
							$('#c_type').combobox('setValue', 'TRADING');
							kategori = rec.nama_subkategori;
						}else{
							//$('#subcat').hide();
							//$('#c_subcategory').combobox('setValue', '');
						}

						if(kategori=='NRM'){
							$('#c_subtype').combobox('setValue', kategori);
						}else{
							$('#c_subtype').combobox('setValue', '');
						}
						var date = $('#po_date').datebox('getValue');
						$.ajax({
							type: 'GET',
							url: 'json/json_po_code.php?kategori='+kategori+'&type='+$('#c_type').combobox('getText')+'&sub='+$('#c_subtype').combobox('getText'),
							data: { kode:'kode' },
							success: function(data){
								var kat = $('#c_category').combobox('getText');
								var type = $('#c_type').combobox('getText');
								var sub = $('#c_subtype').combobox('getText');
								var ket = '';
								if($.trim(kat)=='RM'){
									kat = $('#c_subcategory').combobox('getText');
								}else{
									kat = $('#c_category').combobox('getText');
								}

								if($.trim(kat)=='ALK'){
									ket='ALK';
								}

								if($.trim(kat)=='RM'){
									if($.trim(type)=='TRADING'){
										ket='RM';
									}
									if($.trim(type)=='ASKARA'){
										ket='RMAI';
									}
								}
								
								if($.trim(kat)=='GE'){
									ket = 'GE';
								}
								if($.trim(kat)=='GD'){
									ket = 'GD';
								}
								if($.trim(kat)=='GH'){
									ket = 'GH';
								}
								if($.trim(kat)=='GR'){
									ket = 'GR';
								}
								if($.trim(kat)=='GP'){
									ket = 'GP';
								}

								if($.trim(kat)=='NRM'){
									if($.trim(type)=='TRADING'){
										if($.trim(sub)=='NRM'){
											ket='NRM';
										}
										if($.trim(sub)=='TS'){
											ket='TS';
										}
									}

									if($.trim(type)=='ASKARA'){
										if($.trim(sub)=='NRM'){
											ket='AI';
										}
										if($.trim(sub)=='TS'){
											ket='AITS';
										}
									}
								}
								//var month = po_date.substr(1, 4);
								var bln = date.substr(5,2);
								var thn = date.substr(2,2);
								//alert(thn);
							 	//$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
							 	$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/'+bln+thn);
							}
						});
					}"></select>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">PO Type</span>
					<select style="width:130px;" name="c_type" id="c_type" class="easyui-combobox" data-options=" url:'json/json_type_po.php',method:'get',valueField:'name_type',textField:'name_type', panelHeight:'auto',
					onSelect: function(rec){
						var kategori = $('#c_category').combobox('getText');
						if($.trim($('#c_category').combobox('getText'))=='RM'){
							kategori = $('#c_subcategory').combobox('getText');
						}else{
							kategori = $('#c_category').combobox('getText');
						}
						var date = $('#po_date').datebox('getValue');
						$.ajax({
							type: 'GET',
							url: 'json/json_po_code.php?kategori='+kategori+'&type='+$('#c_type').combobox('getText')+'&sub='+$('#c_subtype').combobox('getText'),
							data: { kode:'kode' },
							success: function(data){
								var kat = $('#c_category').combobox('getText');
								var type = $('#c_type').combobox('getText');
								var sub = $('#c_subtype').combobox('getText');
								var ket = '';
								if($.trim(kat)=='RM'){
									kat = $('#c_subcategory').combobox('getText');
								}else{
									kat = $('#c_category').combobox('getText');
								}


								if($.trim(kat)=='ALK'){
									ket='ALK';
								}

								if($.trim(kat)=='RM'){
									if($.trim(type)=='TRADING'){
										ket='RM';
									}
									if($.trim(type)=='ASKARA'){
										ket='RMAI';
									}									
								}
								if($.trim(kat)=='GE'){
									ket = 'GE';
								}
								if($.trim(kat)=='GD'){
									ket = 'GD';
								}
								if($.trim(kat)=='GH'){
									ket = 'GH';
								}
								if($.trim(kat)=='GR'){
									ket = 'GR';
								}
								if($.trim(kat)=='GP'){
									ket = 'GP';
								}

								if($.trim(kat)=='NRM'){
									if($.trim(type)=='TRADING'){
										if($.trim(sub)=='NRM'){
											ket='NRM';
										}
										if($.trim(sub)=='TS'){
											ket='TS';
										}
									}

									if($.trim(type)=='ASKARA'){
										if($.trim(sub)=='NRM'){
											ket='AI';
										}
										if($.trim(sub)=='TS'){
											ket='AITS';
										}
									}
								}


							 	//var month = po_date.substr(1, 4);
								var bln = date.substr(5,2);
								var thn = date.substr(2,2);
								//alert(thn);
							 	//$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
							 	$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/'+bln+thn);
							}
						});
					}"></select>
				</div>
				<div class="fitem" id="subtype" style="display:block">
					<span style="width:130px;display:inline-block;"></span>
					<select style="width:130px;" name="c_subtype" id="c_subtype" class="easyui-combobox" data-options=" url:'json/json_subtype.php',method:'get',valueField:'status',textField:'status', panelHeight:'auto',
					onSelect: function(rec){
						var kategori = $('#c_category').combobox('getText');
						if($.trim($('#c_category').combobox('getText'))=='RM'){
							kategori = $('#c_subcategory').combobox('getText');
						}else{
							kategori = $('#c_category').combobox('getText');
						}
						var date = $('#po_date').datebox('getValue');
						$.ajax({
							type: 'GET',
							url: 'json/json_po_code.php?kategori='+kategori+'&type='+$('#c_type').combobox('getText')+'&sub='+$('#c_subtype').combobox('getText'),
							data: { kode:'kode' },
							success: function(data){
								var kat = $('#c_category').combobox('getText');
								var type = $('#c_type').combobox('getText');
								var sub = $('#c_subtype').combobox('getText');
								var ket = '';
								if($.trim(kat)=='RM'){
									kat = $('#c_subcategory').combobox('getText');
								}else{
									kat = $('#c_category').combobox('getText');
								}


								if($.trim(kat)=='ALK'){
									ket='ALK';
								}

								if($.trim(kat)=='RM'){
									if($.trim(type)=='TRADING'){
										ket='RM';
									}
									if($.trim(type)=='ASKARA'){
										ket='RMAI';
									}
								}
								if($.trim(kat)=='GE'){
									ket = 'GE';
								}
								if($.trim(kat)=='GD'){
									ket = 'GD';
								}
								if($.trim(kat)=='GH'){
									ket = 'GH';
								}
								if($.trim(kat)=='GR'){
									ket = 'GR';
								}
								if($.trim(kat)=='GP'){
									ket = 'GP';
								}

								if($.trim(kat)=='NRM'){
									if($.trim(type)=='TRADING'){
										if($.trim(sub)=='NRM'){
											ket='NRM';
										}
										if($.trim(sub)=='TS'){
											ket='TS';
										}
									}

									if($.trim(type)=='ASKARA'){
										if($.trim(sub)=='NRM'){
											ket='AI';
										}
										if($.trim(sub)=='TS'){
											ket='AITS';
										}
									}
								}


							 	//var month = po_date.substr(1, 4);
								var bln = date.substr(5,2);
								var thn = date.substr(2,2);
								//alert(thn);
							 	//$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
							 	$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/'+bln+thn);
							}
						});
					}"></select>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Purchase Order No</span>
					<input name="po_no" id="po_no" class="easyui-textbox" style="width:130px" readonly="true">
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">PO Date</span>
					<input style="width:130px;" name="po_date" id="po_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,
					onSelect: function(date){
						var npo= $('#po_no').textbox('getValue');
						var len = npo.length;
						var st = len-4;
						var pono= npo.substr(0, st);
						var th = date.getFullYear().toString();
						var thn = th.substr(2,2);
						var bl = date.getMonth()+1;
						var bln;
						if(bl <=9){
							bln='0'+bl;
						}else{
							bln=bl;
						}
						var po_no = pono+bln+thn;
						//alert(po_no);
						$('#po_no').textbox('setValue', po_no);
					}"/> 
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Request Date</span>
					<input style="width:130px;" name="po_req_date" id="po_req_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
				</div>
				
			</div>
			<div style="width:330px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Supplier Name</span>
					<select style="width:180px;" name="c_supplier" id="c_supplier" class="easyui-combobox" data-options=" url:'json/json_supplier.php',method:'get',valueField:'kode_supplier',textField:'nama_supplier', panelHeight:'150px',
					onSelect: function(rec){
						//alert(rec.vat);
						$('#c_vat').combobox('setValue', $.trim(rec.vat));
						$('#c_payterm').combobox('setValue', $.trim(rec.payterm));
					}"></select>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Payment Term</span>
					<select name="c_payterm" id="c_payterm" class="easyui-combobox" style="width:130px;" data-options=" url:'json/json_payment.php',method:'get',valueField:'payment',textField:'payment', panelHeight:'auto'"> 
						</select>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">VAT</span>
					<input name="c_vat" id="c_vat"  class="easyui-combobox" style="width:130px;"
						data-options="
						url: 'json/json_va.json',
						method: 'get',
						textField: 'status',
						valueField: 'status',
						panelHeight: 'auto'
						">
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Note</span>
					<input name="c_notes" id="c_notes" class="easyui-textbox" data-options="multiline:true" style="width:180px;height:50px" >
				</div>
			</div>
			
		</fieldset>
		<fieldset align="center" style="padding-top:20px;width:210px; border:1px solid #d0d0d0; margin:0 0 0 10px; border-radius:4px; height:165px;"><legend>Add SO</legend>
			<span>SO No.</span>
			<select name="so_no" id="so_no" class="easyui-combogrid" style="width:100%;"/></select>
		</fieldset>
		
		<div style="clear:both">
		</div>
		
		<table id="dg_entry" title="Entry Purchase Order" class="easyui-datagrid" style="width:883px;height:250px;">
		</table>

		<div style="margin-top:10px; width:270px; float:left;">
			<div class="fitem">
				<span style="width:105px;display:inline-block;">Sub Total</span>
				<input name="po_subtot" id="po_subtot" class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
			</div>
			<div class="fitem">
				<span style="width:50px;display:inline-block;">Disc%</span>
				<input name="po_disc_prc" id="po_disc_prc" style="width:50px;" class="easyui-textbox"/>
				<input name="po_disc" id="po_disc"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
			</div>
			
		</div>
		<div style="margin-top:10px; width:270px; float:left;">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">DPP</span>
				<input name="po_dpp" id="po_dpp"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">VAT</span>
				<input name="po_vat" id="po_vat"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
			</div>
			
		</div>
		<div style="margin-top:10px; width:270px; float:left;">
			<div class="fitem">
				<span style="width:105px;display:inline-block;">Down Payment </span>
				<input name="po_dp" id="po_dp"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
			</div>
			<div class="fitem">
				<span style="width:105px;display:inline-block;">Grand Total </span>
				<input name="po_grandtot" id="po_grandtot"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
			</div>
		</div>
	</div>

<div id="tl_entry">
	<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-add" onclick="findPart()">Find Part No</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-add" onclick="insertRow()">Non Stock</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-add" onclick="findMore()">Find More</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-remove" onclick="editRemove()">Delete</a>
</div>


<!-- INI EDITOR -->

	<div id='dlg_editor' class="easyui-dialog" style="width:960px;height:570px;padding:10px 20px" closed="true" buttons="#editor-buttons">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:630px; float:left; margin-bottom:5px;"><legend>Create PO</legend>
			<div style="width:290px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Category</span>
					<select style="width:130px;" name="e_category" id="e_category" class="easyui-combobox" data-options=" url:'json/json_category.php',method:'get',valueField:'jenis_kategori',textField:'ket_kategori', panelHeight:'70px',
					onSelect: function(rec){
						var kategori = rec.ket_kategori;
						if($.trim(rec.ket_kategori)=='RM'){
							$('#esubcat').show();
							$('#e_type').combobox('setText', 'TRADING');
							$('#e_subcategory').combobox('setValue', '00001');
							kategori = $('#e_subcategory').combobox('getText');

						}else{
							$('#esubcat').hide();
							$('#e_subcategory').combobox('setValue', '');
						}

						if(rec.ket_kategori=='NRM'){
							$('#e_subtype').combobox('setValue', 'NRM');
						}else{
							$('#e_subtype').combobox('setValue', '');
						}

						//alert('json/json_po_code.php?kategori='+kategori+'&type='+$('#e_type').combobox('getText')+'&sub='+$('#e_subtype').combobox('getText'));
						$.ajax({
							type: 'GET',
							url: 'json/json_po_code.php?kategori='+kategori+'&type='+$('#e_type').combobox('getText')+'&sub='+$('#e_subtype').combobox('getText'),
							data: { kode:'kode' },
							success: function(data){
								var kat = $('#e_category').combobox('getText');
								var type = $('#e_type').combobox('getText');
								var sub = $('#e_subtype').combobox('getText');
								var ket = '';
								//alert();
								if($.trim(kat)=='RM'){
									kat = $('#e_subcategory').combobox('getText');
								}else{
									kat = $('#e_category').combobox('getText');
								}

								if($.trim(kat)=='ALK'){
									ket='ALK';
								}

								if($.trim(kat)=='RM'){
									if($.trim(type)=='TRADING'){
										ket='RM';
									}
									if($.trim(type)=='ASKARA'){
										ket='RMAI';
									}
								}
								
								if($.trim(kat)=='GE'){
									ket = 'GE';
								}
								if($.trim(kat)=='GD'){
									ket = 'GD';
								}
								if($.trim(kat)=='GH'){
									ket = 'GH';
								}
								if($.trim(kat)=='GR'){
									ket = 'GR';
								}
								if($.trim(kat)=='GP'){
									ket = 'GP';
								}

								if($.trim(kat)=='NRM'){
									if($.trim(type)=='TRADING'){
										if($.trim(sub)=='NRM'){
											ket='NRM';
										}
										if($.trim(sub)=='TS'){
											ket='TS';
										}
									}

									if($.trim(type)=='ASKARA'){
										if($.trim(sub)=='NRM'){
											ket='AI';
										}
										if($.trim(sub)=='TS'){
											ket='AITS';
										}
									}
								}

							 	$('#epo_no').textbox('setValue',data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
							 	//alert(data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
							}
						});
					}"></select>
				</div>
				<div class="fitem" id="esubcat">
					<span style="width:130px;display:inline-block;">Sub Category</span>
					<select style="width:130px;" name="e_subcategory" id="e_subcategory" class="easyui-combobox" data-options=" url:'json/json_subcategory.php',method:'get',valueField:'kode_subkategori',textField:'nama_subkategori', panelHeight:'70px',
					onSelect:function(rec){
						var kategori = $('#e_category').combobox('getText');
						if($.trim($('#e_category').combobox('getText'))=='RM'){
							//$('#subcat').show();
							//$('#c_subcategory').combobox('setValue', rec.ket_kategori);
							$('#e_type').combobox('setValue', 'TRADING');
							kategori = rec.nama_subkategori;
						}else{
							//$('#subcat').hide();
							//$('#c_subcategory').combobox('setValue', '');
						}

						if(kategori=='NRM'){
							$('#e_subtype').combobox('setValue', kategori);
						}else{
							$('#e_subtype').combobox('setValue', '');
						}

						$.ajax({
							type: 'GET',
							url: 'json/json_po_code.php?kategori='+kategori+'&type='+$('#e_type').combobox('getText')+'&sub='+$('#e_subtype').combobox('getText'),
							data: { kode:'kode' },
							success: function(data){
								var kat = $('#e_category').combobox('getText');
								var type = $('#e_type').combobox('getText');
								var sub = $('#e_subtype').combobox('getText');
								var ket = '';
								if($.trim(kat)=='RM'){
									kat = $('#e_subcategory').combobox('getText');
								}else{
									kat = $('#e_category').combobox('getText');
								}

								if($.trim(kat)=='ALK'){
									ket='ALK';
								}

								if($.trim(kat)=='RM'){
									if($.trim(type)=='TRADING'){
										ket='RM';
									}
									if($.trim(type)=='ASKARA'){
										ket='RMAI';
									}
								}
								
								if($.trim(kat)=='GE'){
									ket = 'GE';
								}
								if($.trim(kat)=='GD'){
									ket = 'GD';
								}
								if($.trim(kat)=='GH'){
									ket = 'GH';
								}
								if($.trim(kat)=='GR'){
									ket = 'GR';
								}
								if($.trim(kat)=='GP'){
									ket = 'GP';
								}

								if($.trim(kat)=='NRM'){
									if($.trim(type)=='TRADING'){
										if($.trim(sub)=='NRM'){
											ket='NRM';
										}
										if($.trim(sub)=='TS'){
											ket='TS';
										}
									}

									if($.trim(type)=='ASKARA'){
										if($.trim(sub)=='NRM'){
											ket='AI';
										}
										if($.trim(sub)=='TS'){
											ket='AITS';
										}
									}
								}

							 	$('#epo_no').textbox('setValue',data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
							}
						});
					}
					"></select>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">PO Type</span>
					<select style="width:130px;" name="e_type" id="e_type" class="easyui-combobox" data-options=" url:'json/json_type_po.php',method:'get',valueField:'name_type',textField:'name_type', panelHeight:'auto',
					onSelect: function(rec){
						var kategori = $('#c_category').combobox('getText');
						if($.trim($('#c_category').combobox('getText'))=='RM'){
							kategori = $('#c_subcategory').combobox('getText');
						}else{
							kategori = $('#c_category').combobox('getText');
						}
						$.ajax({
							type: 'GET',
							url: 'json/json_po_code.php?kategori='+kategori+'&type='+$('#c_type').combobox('getText')+'&sub='+$('#c_subtype').combobox('getText'),
							data: { kode:'kode' },
							success: function(data){
								var kat = $('#c_category').combobox('getText');
								var type = $('#c_type').combobox('getText');
								var sub = $('#c_subtype').combobox('getText');
								var ket = '';
								if($.trim(kat)=='RM'){
									kat = $('#c_subcategory').combobox('getText');
								}else{
									kat = $('#c_category').combobox('getText');
								}


								if($.trim(kat)=='ALK'){
									ket='ALK';
								}

								if($.trim(kat)=='RM'){
									if($.trim(type)=='TRADING'){
										ket='RM';
									}
									if($.trim(type)=='ASKARA'){
										ket='RMAI';
									}									
								}
								if($.trim(kat)=='GE'){
									ket = 'GE';
								}
								if($.trim(kat)=='GD'){
									ket = 'GD';
								}
								if($.trim(kat)=='GH'){
									ket = 'GH';
								}
								if($.trim(kat)=='GR'){
									ket = 'GR';
								}
								if($.trim(kat)=='GP'){
									ket = 'GP';
								}

								if($.trim(kat)=='NRM'){
									if($.trim(type)=='TRADING'){
										if($.trim(sub)=='NRM'){
											ket='NRM';
										}
										if($.trim(sub)=='TS'){
											ket='TS';
										}
									}

									if($.trim(type)=='ASKARA'){
										if($.trim(sub)=='NRM'){
											ket='AI';
										}
										if($.trim(sub)=='TS'){
											ket='AITS';
										}
									}
								}


							 	$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
							}
						});
					}
					"></select>
				</div>
				<div class="fitem" id="esubtype" style="display:block">
					<span style="width:130px;display:inline-block;"></span>
					<select style="width:130px;" name="e_subtype" id="e_subtype" class="easyui-combobox" data-options=" url:'json/json_subtype.php',method:'get',valueField:'status',textField:'status', panelHeight:'auto',
					onSelect: function(rec){
						var kategori = $('#e_category').combobox('getText');
						if($.trim($('#e_category').combobox('getText'))=='RM'){
							kategori = $('#e_subcategory').combobox('getText');
						}else{
							kategori = $('#e_category').combobox('getText');
						}
						$.ajax({
							type: 'GET',
							url: 'json/json_po_code.php?kategori='+kategori+'&type='+$('#e_type').combobox('getText')+'&sub='+$('#e_subtype').combobox('getText'),
							data: { kode:'kode' },
							success: function(data){
								var kat = $('#e_category').combobox('getText');
								var type = $('#e_type').combobox('getText');
								var sub = $('#e_subtype').combobox('getText');
								var ket = '';
								if($.trim(kat)=='RM'){
									kat = $('#e_subcategory').combobox('getText');
								}else{
									kat = $('#e_category').combobox('getText');
								}


								if($.trim(kat)=='ALK'){
									ket='ALK';
								}

								if($.trim(kat)=='RM'){
									if($.trim(type)=='TRADING'){
										ket='RM';
									}
									if($.trim(type)=='ASKARA'){
										ket='RMAI';
									}
								}
								if($.trim(kat)=='GE'){
									ket = 'GE';
								}
								if($.trim(kat)=='GD'){
									ket = 'GD';
								}
								if($.trim(kat)=='GH'){
									ket = 'GH';
								}
								if($.trim(kat)=='GR'){
									ket = 'GR';
								}
								if($.trim(kat)=='GP'){
									ket = 'GP';
								}

								if($.trim(kat)=='NRM'){
									if($.trim(type)=='TRADING'){
										if($.trim(sub)=='NRM'){
											ket='NRM';
										}
										if($.trim(sub)=='TS'){
											ket='TS';
										}
									}

									if($.trim(type)=='ASKARA'){
										if($.trim(sub)=='NRM'){
											ket='AI';
										}
										if($.trim(sub)=='TS'){
											ket='AITS';
										}
									}
								}
							 	$('#epo_no').textbox('setValue',data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
							}
						});
					}"></select>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Purchase Order No</span>
					<input name="epo_no" id="epo_no" class="easyui-textbox" style="width:130px" readonly="true">
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">PO Date</span>
					<input style="width:130px;" name="epo_date" id="epo_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,
					onSelect: function(date){
						//alert();
					} "/> 
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Request Date</span>
					<input style="width:130px;" name="epo_req_date" id="epo_req_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
				</div>
				
			</div>
			<div style="width:330px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Supplier Name</span>
					<select style="width:180px;" name="e_supplier" id="e_supplier" class="easyui-combobox" data-options=" url:'json/json_supplier.php',method:'get',valueField:'kode_supplier',textField:'nama_supplier', panelHeight:'150px',
					onSelect: function(rec){
						//alert(rec.vat);
						$('#e_vat').combobox('setValue', $.trim(rec.vat));
					}"></select>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Payment Term</span>
					<select name="e_payterm" id="e_payterm" class="easyui-combobox" style="width:130px;" data-options=" url:'json/json_payment.php',method:'get',valueField:'payment',textField:'payment', panelHeight:'auto'"> 
						</select>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">VAT</span>
					<input name="e_vat" id="e_vat"  class="easyui-combobox" style="width:130px;"
						data-options="
						url: 'json/json_va.json',
						method: 'get',
						textField: 'status',
						valueField: 'status',
						panelHeight: 'auto'
						">
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Note</span>
					<input name="e_notes" id="e_notes" class="easyui-textbox" data-options="multiline:true" style="width:180px;height:50px" >
				</div>
				<!-- <fieldset align="center" style="padding-top:20px;width:210px; border:1px solid #d0d0d0; margin:0 0 0 10px; border-radius:4px; height:165px;"><legend>Add SO</legend> -->
					<span style="width:130px;display:inline-block;">So No</span>
					<select name="eso_no" id="eso_no" class="easyui-combogrid" style="width:180px;"/></select>
				<!-- </fieldset> -->
			</div>
		</fieldset>
		
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:200px; height:155px; float:left;"><legend>Editor</legend>
			<div class="fitem">
				<span style="width:90px;display:inline-block;">Revision</span>
				<input name="e_rev" id="e_rev" class="easyui-combobox" style="width:70px;"
						data-options="
						url: 'json/json_revision.php',
						method: 'get',
						textField: 'rev',
						valueField: 'rev',
						panelHeight: 'auto'
						">
			</div>
			<div class="fitem" >
				<span style="width:90px;display:inline-block;">User Update</span>
				<input name="epo_user" id="epo_user" class="easyui-textbox" style="width:100px" readonly="true">
			</div>
			<div class="fitem" >
				<span style="width:90px;display:inline-block;">Last update</span>
				<input style="width:100px;" name="epo_last_update" id="epo_last_update" class="easyui-datebox" readonly="true" data-options="formatter:myformatter,parser:myparser"/> 
			</div>
		</fieldset>
		<div style="clear:both">
		</div>
	
		<table id="dg_editor" title="Entry Purchase Order" class="easyui-datagrid" style="width:883px;height:250px;">
		</table>

		<div style="margin-top:10px; width:270px; float:left;">
				<div class="fitem">
					<span style="width:105px;display:inline-block;">Sub Total</span>
					<input name="epo_subtot" id="epo_subtot" class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
				</div>
				<div class="fitem">
					<span style="width:50px;display:inline-block;">Disc%</span>
					<input name="epo_disc_prc" id="epo_disc_prc" style="width:50px;" class="easyui-textbox"/>
					<input name="epo_disc" id="epo_disc"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
				</div>
				
			</div>
			<div style="margin-top:10px; width:270px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">DPP</span>
					<input name="epo_dpp" id="epo_dpp"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">VAT</span>
					<input name="epo_vat" id="epo_vat"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
				</div>
				
			</div>
			<div style="margin-top:10px; width:270px; float:left;">
				<div class="fitem">
					<span style="width:105px;display:inline-block;">Down Payment </span>
					<input name="epo_dp" id="epo_dp"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
				</div>
				<div class="fitem">
					<span style="width:105px;display:inline-block;">Grand Total </span>
					<input name="epo_grandtot" id="epo_grandtot"  class="easyui-numberbox" data-options="precision:5,groupSeparator:','">
				</div>
			</div>
	</div>	

	<div id="tl_editor">
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-add" id="findParteditor" onclick="findParteditor()">Find Part No</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-add" id="insertRoweditor" onclick="insertRoweditor()">Non Stock</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-remove" id="deleteEditor" onclick="deleteEditor()">Delete</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-ok" id="setPrice" onclick="setPrice()">Update Price</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-ok" id="setQty" onclick="setQty()">Update Qty</a>
	</div>

	<div id="dlg_more" title="Part" class="easyui-window" style="height:400px; width:650px;" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<table id="dg_findmore" class="easyui-datagrid" style="width:620px;height:300px;"
				url=""
				rownumbers="true" singleSelect="true"
				sortName="brg_codebarang" sortOrder="asc">
			<thead>
					<th field="brg_codebarang" width="120" sortable="true">Part No</th>
					<th field="nama_barang" width="165" sortable="true">Part Name</th>
					<th field="ket_satuan" width="80" sortable="true">UoM</th>
					<th field="currecy" width="80" sortable="true">Currency</th>
					<th align="right" field="price" width="120" sortable="true">Price</th>
			</thead>
		</table>
	</div>

	<div id="dlg_part" title="Part" class="easyui-window" style="height:400px; width:650px;" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<table id="dg_findpart" class="easyui-datagrid" style="width:620px;height:300px;"
				url=""
				rownumbers="true" singleSelect="true"
				sortName="brg_codebarang" sortOrder="asc">
			<thead>
					<th field="brg_codebarang" width="120" sortable="true">Part No</th>
					<th field="nama_barang" width="165" sortable="true">Part Name</th>
					<th field="ket_satuan" width="80" sortable="true">UoM</th>
					<th field="currecy" width="80" sortable="true">Currency</th>
					<th align="right" field="price" width="120" sortable="true">Price</th>
			</thead>
		</table>
	</div>

	<div id="dlg_parteditor" title="Part" class="easyui-window" style="height:400px; width:650px;" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<table id="dg_findeditor" class="easyui-datagrid" style="width:620px;height:300px;"
				url=""
				rownumbers="true" singleSelect="true"
				sortName="brg_codebarang" sortOrder="asc">
			<thead>
					<th field="brg_codebarang" width="120" sortable="true">Part No</th>
					<th field="nama_barang" width="165" sortable="true">Part Name</th>
					<th field="ket_satuan" width="80" sortable="true">UoM</th>
					<th field="currecy" width="80" sortable="true">Currency</th>
					<th align="right" field="price" width="120" sortable="true">Price</th>
			</thead>
		</table>
	</div>

	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePo()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>

	<div id="editor-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveEditor()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_editor').dialog('close')" style="width:90px">Cancel</a>
	</div>

	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:740px;float:left;"><legend>Purchase Order Filter</legend>
			<div style="width:430px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">PO Date</span>
					<input style="width:100px;" name="s_periode_awal" id="s_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
					to 
					<input style="width:100px;" name="s_periode_akhir" id="s_periode_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Supplier Name</span>
					<select style="width:220px;" name="supplier" id="supplier" class="easyui-combobox" data-options=" url:'json/json_supplier.php',method:'get',valueField:'kode_supplier',textField:'nama_supplier', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_supplier" id="ck_supplier" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<div style="width:250px; float:left;">
						<span style="width:130px;display:inline-block;">Category</span>
						<select style="width:100px;" name="category" id="category" class="easyui-combobox" data-options=" url:'json/json_category.php',method:'get',valueField:'jenis_kategori',textField:'ket_kategori', panelHeight:'70px',
						onSelect:function(rec){
							//alert(rec);
							if(rec.ket_kategori=='RM'){
								$('#subcat1').show();
								$('#subcategory').combobox('setValue', '00001');
							}else{
								$('#subcat1').hide();
								$('#subcategory').combobox('setValue', '');
							}
						}"></select>
					</div>
					<div style="width:109px; float:left;" id="subcat1">
						<select style="width:105px;" name="subcategory" id="subcategory" class="easyui-combobox" data-options=" url:'json/json_subcategory.php',method:'get',valueField:'kode_subkategori',textField:'nama_subkategori', panelHeight:'80px'"></select>
					</div>
						<label><input type="checkbox" name="ck_kategori" id="ck_kategori" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton" onClick="filterData()" style="width:100px;">Filter</a>
					
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">PO Type</span>
					<select style="width:120px;" name="type" id="type" class="easyui-combobox" data-options=" url:'json/json_type_po.php',method:'get',valueField:'name_type',textField:'name_type', panelHeight:'auto'"></select>
					<label><input type="checkbox" name="ck_type" id="ck_type" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">PO Status</span>
					<select style="width:120px;" name="status" id="status" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_status.json',method:'get',valueField:'status',textField:'status', panelHeight:'auto'"></select>
					<label><input type="checkbox" name="ck_status" id="ck_status" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Part</span>
					<select name="part_" id="part_" class="easyui-combogrid" style="width:160px;"/></select>
					<label><input type="checkbox" name="ck_part" id="ck_part" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:220px; height:135px; float:left;"><legend>Upload PO</legend>
			<form id="upd" method="post" enctype="multipart/form-data" novalidate>
				<div class="fitem" style="margin-top:30px">
					<input class="easyui-filebox" id="btnFile" name="file1" id="file1" data-options="prompt:'Choose a file...'" style="width:220px; margin-top:20px;">	
				</div>
				<div class="fitem">
					<a href="javascript:void(0)" id="btnUpload" class="easyui-linkbutton c6" onclick="uploaddata()" style="width:220px;">Upload</a>
				</div>
			</form>
		</fieldset>
		<div style="clear:both;"></div>

		<span style="width:50px;display:inline-block;">search</span>
		<input style="width:150px; border: 1px solid #0099FF;" onkeypress="filter(event)" name="src" id="src"type="text" />
		<a href="javascript:void(0)" class="easyui-linkbutton" id="addPo" plain="true" iconCls="icon-add" onclick="addPo()">Add</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" id="editPo" plain="true" iconCls="icon-edit" onclick="editPo()">Edit</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" id="deletePo" plain="true" iconCls="icon-remove" onclick="destroyPo()">Delete</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="printPdf()">PDF</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="printPdfpart()">PDF By Part</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="printPdfdtl()">PDF Detail</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="printPo()">PO Letter Single</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="printMul()">PO Letter Multiple</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="printExcel()">Export to Excel</a>

	</div>
	<table id="dg" title="Purchase Order" class="easyui-datagrid" style="width:1100px;">
	</table>

	<div id='dlg_qty' class="easyui-dialog" buttons="#qty-buttons" title="New Qty"  style="padding-top:10px;height:120px; width:270px;" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<div class="fitem">
			<span style="width:60px;display:inline-block;">New Qty</span>
			<input name="new_qty" id="new_qty" style="width:150px;" class="easyui-numberbox"/>
		</div>
	</div>
	<div id="qty-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls='icon-ok' onclick="saveNqty()">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls='icon-cancel' onclick="javascript:$('#dlg_qty').dialog('close')">Cancel</a>
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

			var lastIndex;
			var subtot;
			var esubtot;
			var pdf='';
			var pdfdtl='';

			function filter(event){  // fungsi saat tombol enter
				var src = document.getElementById('src').value;
				/*setTimeout(function(){
				},3000);  
					//alert(event);*/
				var search = src.toUpperCase();
				//alert(search);
				document.getElementById('src').value=search;
				
			    if(event.keyCode == 13 || event.which == 13){  
			  		//document.getElementById('xqty').focus();
			  		//saveScan();
					//alert(src);
					var src = document.getElementById('src').value;
					$('#dg').datagrid('load', {
						src: search
					});

					if (src=='') {
						filterData();
					};
			    }
			}

			function printExcel(){
				var row = $('#dg').datagrid('getSelected');
		        if (row) {
		       //     alert(row.doc_no);
		            window.open('purchase_order_excel.php?po_no='+row.doc_no).print();
		        };
			}

			function uploaddata() {
			//alert();
				$('#upd').form('submit',{
					url: 'purchase_order_upload.php',
					onSubmit: function(){
						return $(this).form('validate');
					},
					success: function(result){
						$.messager.alert('Customer','Upload Success!','info');
						//alert(result);
				 		$('#file1').filebox('clear');
						$('#dg').datagrid('reload');
					}
				});
			}

			$('#part_').combogrid({
				panelWidth: 500,
				panelHeight:250,
				idField: 'brg_codebarang',
				textField: 'nama_barang',
				url: 'json/cbgrid_barang.php',
				mode:'remote',
				columns: [[
					{field:'brg_codebarang',title:'Part No',width:80},
					{field:'nama_barang',title:'Part Name',width:120}
				]],
				fitColumns: true,
				onClickRow:function(rec){
					
				}
			});

			$('#so_no').combogrid({
				panelWidth: 500,
				panelHeight:250,
				idField: 'soc_docno',
				textField: 'soc_docno',
				url: 'purchase_order_getso.php',
				multiple: true,
				mode:'remote',
				columns: [[
					{field:'soc_docno',title:'SO No.',width:80},
					{field:'nama_customer',title:'Customer Name',width:120}
				]],
				fitColumns: true,
				onClickRow:function(rec){
					
				}
			});	
			$('#eso_no').combogrid({
				panelWidth: 500,
				panelHeight:250,
				idField: 'soc_docno',
				textField: 'soc_docno',
				url: 'purchase_order_getso.php',
				multiple: true,
				mode:'remote',
				columns: [[
					{field:'soc_docno',title:'SO No.',width:80},
					{field:'nama_customer',title:'Customer Name',width:120}
				]],
				fitColumns: true,
				onClickRow:function(rec){
					
				}
			});		

			function setPrice(){
				var row = $('#dg_editor').datagrid('getSelected');
				var index = $('#dg_editor').datagrid('getRowIndex', row);
				if (row) {
					//alert(data);
					var data = $('#dg_editor').datagrid('getEditors', index);
					var brg = $('#dg_editor').datagrid('getData').rows[index].part_no;
					var price = $(data[4].target);
					var kode_supplier = $('#e_supplier').combobox('getValue');
					var part_no = brg.replace(/<span style='color:blue;'>/g,'');
					var part = part_no.replace(/<\/span>/g,'');
					//alert(part);
					$.ajax({
						type: 'GET',
						url: 'json/json_harga.php?kode_supplier='+$.trim(kode_supplier)+'&part_no='+$.trim(part),
						data: { price:'price' },
						success: function(data){
							$.messager.confirm('Confirm','Are you sure will change price with = '+data[0].price+'?',function(r){
							if (r){
									price.numberbox('setValue', data[0].price);
									//alert(data[0].price);
								}
							});			
						}
					});
						
				};

			}

			function saveNqty(){
				var row = $('#dg_editor').datagrid('getSelected');
				var index = $('#dg_editor').datagrid('getRowIndex', row);
				if (row) {
					var data = $('#dg_editor').datagrid('getEditors', index);
					var qty = $(data[2].target);
					var qtyold = qty.numberbox('getValue');
					var qtynew = $('#new_qty').numberbox('getValue');
					//alert(qtynew);

					if (parseInt(qtynew) > parseInt(qtyold)) {
						$('#dlg_qty').dialog('close');
						qty.numberbox('setValue', qtynew);
					}else{
						$.messager.alert('information', 'New Qty has been bigger than Qty!', 'error');
						$('#new_qty').focus();
					}
					

					//alert(qty.numberbox('getValue'));
					//$('#dlg_qty').dialog('open');
					/*$.ajax({
						type: 'GET',
						url: 'json/json_harga.php?kode_supplier='+$.trim(kode_supplier)+'&part_no='+$.trim(part),
						data: { price:'price' },
						success: function(data){
							$.messager.confirm('Confirm','Are you sure will change price with = '+data[0].price+'?',function(r){
							if (r){
									price.numberbox('setValue', data[0].price);
								}
							});			
						}
					});*/
						
				};
			}

			function setQty(){
				var row = $('#dg_editor').datagrid('getSelected');
				if (row) {
					$('#dlg_qty').dialog('open');
					$('#new_qty').numberbox('setValue', 0);
				};

			}

			function printPo(){

				var row = $('#dg').datagrid('getSelected');
				if (row) {
					//alert();
					window.open('purchase_order_letter.php?doc_no='+$.trim(row.doc_no),'_blank');
				};
			}

			function printMul(){
				var row = $('#dg').datagrid('getSelected');
				if (row) {
					//alert();
					window.open('purchase_order_multiletter.php?doc_no='+$.trim(row.doc_no),'_blank');
				};
			}

			function filterData() {
					var ck_supplier='false';
					var ck_type='false';
					var ck_kategori='false';
					var ck_status='false';
					var ck_date='false';
					var ck_part='false';

					//alert();
/*
						var brg = $('#part_').combogrid('grid');
						var r = brg.datagrid('getSelected');
						var part = r.brg_codebarang; 
*/					
				//alert();
					
					if($('#ck_part').attr("checked")){
						ck_part='true';
					}

					if($('#ck_supplier').attr("checked")){
						ck_supplier='true';
					}
					if($('#ck_type').attr("checked")){
						ck_type='true';
					}
					if($('#ck_kategori').attr("checked")){
						ck_kategori='true';
					}

					if($('#ck_status').attr("checked")){
						ck_status='true';
					}

					if($('#ck_date').attr("checked")){
						ck_date='true';
					}

					$('#dg').datagrid('load', {
						s_periode_awal: $('#s_periode_awal').datebox('getValue'),
						s_periode_akhir: $('#s_periode_akhir').datebox('getValue'),
						ck_date: ck_date,
						supplier: $('#supplier').combobox('getValue'),
						ck_supplier: ck_supplier,
						category: $('#category').combobox('getValue'),
						subcategory: $('#subcategory').combobox('getValue'),
						ck_kategori: ck_kategori,
						type: $('#type').combobox('getValue'),
						ck_type: ck_type,
						status: $('#status').combobox('getValue'),
						ck_status: ck_status,
						part_no: $('#part_').combogrid('getValue'),
						ck_part: ck_part,
						src: ''
					});

					pdf="?s_periode_awal="+$('#s_periode_awal').datebox('getValue')+"&s_periode_akhir="+$('#s_periode_akhir').datebox('getValue')
					+"&supplier="+$('#supplier').combobox('getValue')+"&ck_supplier="+ck_supplier+"&category="+$('#category').combobox('getValue')
					+"&ck_kategori="+ck_kategori+"&type="+$('#type').combobox('getValue')+"&ck_type="+ck_type+"&status="+$('#status').combobox('getValue')
					+"&ck_status="+ck_status+"&ck_date="+ck_date+"&ck_part="+ck_part+"&part_no="+$('#part_').combogrid('getValue');
				}

			function printPdf(){
				//alert(pdf);
				if(pdf=='') {
					$.messager.show({
						title: 'PDF Report',
						msg: 'Data Not Defined'
					});
				} else {
					window.open('purchase_order_pdf.php'+pdf, '_blank');
				}
			}

			function printPdfpart(){
				//alert(pdf);
				if(pdf=='') {
					$.messager.show({
						title: 'PDF Report',
						msg: 'Data Not Defined'
					});
				} else {
					window.open('purchase_order_pdfpart.php'+pdf, '_blank');
				}
			}

			function printPdfdtl(){
				if(pdf=='') {
					$.messager.show({
						title: 'PDF Report',
						msg: 'Data Not Defined'
					});
				} else {
					window.open('purchase_order_pdfdtl.php'+pdf, '_blank');
				}
			}

			function deleteEditor(){
				var row = $('#dg_editor').datagrid('getSelected');			
				var idx = $("#dg_editor").datagrid("getRowIndex", row);
					
				if (row){
					var code=row.po_no;
					//alert(row.esoc_code);
					$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
						if (r){
							$.post('purchase_order_destroy_dtl.php',{
								po_no: code
							}).done(function(res){
								//alert(res);
								//$.messager.alert('Information','Data Deleted!','info');
								$('#dg_editor').datagrid('deleteRow', idx);
								
							var i = 0; 
							var subtotal=0;
							var total = $('#dg_editor').datagrid('getData').total;
							//alert(i);
							for(i=0;i<total;i++){
								$('#dg_editor').datagrid('endEdit', i);
								var sb = $('#dg_editor').datagrid('getData').rows[i].subtotal.replace(/,/g,'');
								var subtotal = subtotal+ parseFloat(sb); 
							}
							var nol=0;
							var prc= (10/100);
							if ($.trim($('#e_vat').combobox('getValue'))=='EXCLUSIVE') {
								$('#epo_disc_prc').textbox('setValue', '0');
								var vat=parseFloat($.trim(subtotal))*prc;
								subtot=parseFloat($.trim(subtotal));
								$('#epo_vat').numberbox('setValue',vat);
								var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
								$('#epo_dpp').numberbox('setValue', parseFloat($.trim(subtotal)));
								$('#epo_subtot').numberbox('setValue', subtot);
								$('#epo_disc').numberbox('setValue', 0);
								$('#epo_dp').numberbox('setValue', 0);
								
								var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
								$('#epo_grandtot').numberbox('setValue',grand);	
							}
							else if($.trim($('#e_vat').combobox('getValue'))=='INCLUSIVE'){
								$('#epo_disc_prc').textbox('setValue', '0');
								var vat=parseFloat($.trim(subtotal))*prc;
								subtot=parseFloat($.trim(subtotal));
								//alert(subtot);
								$('#epo_vat').numberbox('setValue',vat);
								var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
								$('#epo_dpp').numberbox('setValue', parseFloat($.trim(subtotal)-so_vat));
								$('#epo_subtot').numberbox('setValue', parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat);
								$('#epo_disc').numberbox('setValue', 0);
								$('#epo_dp').numberbox('setValue', 0);
								
								var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
								$('#epo_grandtot').numberbox('setValue',grand);
							}
							else{
								//$('#eso_vat').numberbox('setValue',0);
								//alert('VAT Undefinide');
								$('#epo_disc_prc').textbox('setValue', '0');
								var vat=parseFloat($.trim(subtotal))*prc;
								subtot=parseFloat($.trim(subtotal));
								//alert(subtot);
								$('#epo_vat').numberbox('setValue', 0);
								var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
								$('#epo_dpp').numberbox('setValue', parseFloat($.trim(subtotal)-so_vat));
								$('#epo_subtot').numberbox('setValue', parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat);
								$('#epo_disc').numberbox('setValue', 0);
								$('#epo_dp').numberbox('setValue', 0);
								
								var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
								$('#epo_grandtot').numberbox('setValue',grand);
							}

							});
						}
					});
					
					
				}
			}

			function insertRoweditor(){
				var code;
				
				$.ajax({
					type: 'GET',
					url: 'json/json_nonstock.php',
					data: { kode:'kode' },
					success: function(data){
					   	code=data[0].kode;
					   	var price = 0;
						var total = $('#dg_editor').datagrid('getData').total;
					   	var idxfield=0;
						if (parseInt(total)==0) {
							idxfield=total;
							
						}else{
							idxfield=total+1;
							var k= $('#dg_editor').datagrid('getData').rows[total-1].part_no;
			
								var ns_id= $('#dg_editor').datagrid('getData').rows[total-1].ns_id;
								if ($.trim(k.substr(0,2))=='NS') {
										var code = parseInt(ns_id)+1;
									if ($.trim(ns_id)==code) {
									}

									if ($.trim(ns_id)<code) {
										var code=code;
									};
								}
							
						}

						$('#dg_editor').datagrid('insertRow',{
							index: idxfield,	// index start with 0
							row: {
								ns_id: code,
								po_no: '0',
								part_no: 'NS',
								price: 0,
								disc: 0,
								qty: 0,
								subtotal: 0+'.00'
							}
						});
							
					}
				});			
			}
			

			function destroyPo(){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					$.messager.confirm('Confirm','Are you sure you want to remove this Oder?',function(r){
						if (r){
							$.post('purchase_order_destroy.php',{doc_no:row.doc_no}).done(function(res){
								//alert(res);
								$('#dg').datagrid('reload');
							});
						}
					});
				}
			}

			$('#dg_findeditor').datagrid({
				onDblClickRow:function(id, row){
					var total = $('#dg_editor').datagrid('getData').total;
					var idxfield=0;
					var i = 0;
					var count = 0;
					
					if (parseInt(total)==0) {
						idxfield=total;
					}else{
						idxfield=total+1;
						for (i=0; i < total; i++) {
							//alert(i);
							var part = $('#dg_editor').datagrid('getData').rows[i].part_no;
							
							if ($.trim(part) == $.trim(row.brg_codebarang)) {
								count++;
							};
						};
					}
					//alert('count = '+count);
					if (count>0) {
						$.messager.alert('Information','Part present','warning');
					}else{
						$('#dg_editor').datagrid('insertRow',{
							index: idxfield,
							row:{
								po_no:'0',
								part_no: row.brg_codebarang,
								part_name: row.nama_barang,
								ket_satuan: row.ket_satuan,
								currency: row.currecy,
								price: row.price,
								qty: 0,
								disc: 0,
								subtotal: parseFloat(row.price.replace(/,/g,'')),
								remarks: ''
								
							}
						});
					}
				}
			});

			function saveEditor(){
				var date = new Date();
				var jam = ("0" + date.getHours()).slice(-2)+':'+("0" + date.getMinutes()).slice(-2)+':'+("0" + date.getSeconds()).slice(-2);
				var tanggal = date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + date.getDate()).slice(-2)+' '+jam;
				
				url = url;
				var i = 0;
				var proc = 0;
				var total = $('#dg_editor').datagrid('getData').total;
				//alert(total);
				var idx=0;
				for(i=0;i<total;i++){
					idx=i;
					$('#dg_editor').datagrid('endEdit', idx);
					//alert($('#dg2').datagrid('getData').rows[i].so_partno);
					var part = $('#dg_editor').datagrid('getData').rows[i].part_no.replace(/<span style='color:blue;'>/g,''); 
					var part_no = part.replace(/<\/span>/g,'');

					//alert($('#dg_editor').datagrid('getData').rows[i].po_no);
					$.post(url,{
						doc_no: $('#epo_no').textbox('getValue'),
						po_type: $('#e_type').combobox('getValue'),
						po_date: $('#epo_date').datebox('getValue'),
						po_req_date: $('#epo_req_date').datebox('getValue'),
						po_sup_id: $('#e_supplier').combobox('getValue'),
						po_category: $('#e_category').combobox('getValue'),
						po_subcategory: $('#e_subcategory').combobox('getValue'),
						po_pay: $('#e_payterm').combobox('getValue'),
						poc_vat: $('#e_vat').combobox('getValue'),
						po_notes: $('#e_notes').textbox('getValue'),
						ns_id: $('#dg_editor').datagrid('getData').rows[i].ns_id,
						po_no: $('#dg_editor').datagrid('getData').rows[i].po_no,
						po_brgcodebarang: part_no,
						po_part_name: $('#dg_editor').datagrid('getData').rows[i].part_name,
						po_satuan: $('#dg_editor').datagrid('getData').rows[i].ket_satuan,
						po_currency: $('#dg_editor').datagrid('getData').rows[i].currency,
						po_qty: $('#dg_editor').datagrid('getData').rows[i].qty,
						po_price: $('#dg_editor').datagrid('getData').rows[i].price,
						po_disc: $('#dg_editor').datagrid('getData').rows[i].disc,
						po_subtot: $('#dg_editor').datagrid('getData').rows[i].subtotal,
						remarks: $('#dg_editor').datagrid('getData').rows[i].remarks,
						po_sumsubtot: $('#epo_subtot').numberbox('getValue'),
						po_discall: $('#epo_disc_prc').textbox('getValue'),
						po_dpp: $('#epo_dpp').numberbox('getValue'),
						po_vat: $('#epo_vat').numberbox('getValue'),
						po_dp: $('#epo_dp').numberbox('getValue'),
						po_grandtot: $('#epo_grandtot').numberbox('getValue'),
						i: idx,
						po_rev: $('#e_rev').combobox('getValue'),
						po_subtype: $('#e_subtype').combobox('getValue'),
						eso_no: $('#eso_no').combogrid('getValue'),
						last_update: tanggal
					}).done(function(res){
						console.log(res);
						$('#dlg_editor').dialog('close');
						$('#dg').datagrid('reload');	// reload data
					})	
				}
				$.messager.alert('Information','Saved','info');
			}
			var rec_sts='';
			function editPo(){
				//alert();
				var row = $('#dg').datagrid('getSelected');
				esubtot= row.ft_subtotal;
				if (row){
					if (row.ket_kategori=='RM') {
						$('#esubcat').show();
					}else{
						$('#esubcat').hide();
					}
					rec_sts=row.receipt_status;
					//alert(row.receipt_status);
					$('#dlg_editor').dialog('open').dialog('setTitle','Edit');
					$('#e_category').combobox('setValue',$.trim(row.jenis_kategori));
					$('#e_type').combobox('setValue', $.trim(row.po_type));
					$('#epo_no').textbox('setValue', $.trim(row.doc_no));
					$('#epo_date').datebox('setValue', $.trim(row.po_date));
					$('#epo_req_date').datebox('setValue', $.trim(row.request_date));
					$('#e_supplier').combobox('setValue', row.kode_supplier);
					$('#e_payterm').combobox('setValue', $.trim(row.payterm));
					$('#e_vat').combobox('setValue', $.trim(row.vat));
					$('#e_notes').textbox('setValue', row.notes);
					$('#e_rev').combobox('setValue', row.po_rev);
					$('#e_subcategory').combobox('setValue', row.kode_subkategori);
					$('#e_subtype').combobox('setValue', row.po_subtype);
					$('#epo_disc').numberbox('setValue', 0);
					$('#epo_subtot').numberbox('setValue', row.ft_subtotal);
					$('#epo_disc_prc').textbox('setValue', parseFloat(row.ft_disc));
					$('#epo_dpp').numberbox('setValue', row.ft_dpp);
					$('#epo_vat').numberbox('setValue', row.ft_vat);
					$('#epo_dp').numberbox('setValue', row.ft_dp);
					$('#epo_grandtot').numberbox('setValue', row.ft_grandtot);

					$('#epo_user').textbox('setValue', row.user_entry);
					$('#epo_last_update').datebox('setValue', row.last_update);

					if (row.receipt_status=='RECEIPT') {
						$('#e_category').combobox('disable');
						$('#e_subcategory').combobox('disable');
						$('#e_type').combobox('disable');
						$('#epo_no').textbox('disable');
						$('#epo_date').datebox('disable');
						$('#epo_req_date').datebox('disable');
						$('#e_supplier').combobox('disable');
						$('#e_payterm').combobox('disable');
						$('#e_vat').combobox('disable');
						$('#e_notes').textbox('disable');
						$('#e_rev').combobox('disable');
						$('#findParteditor').linkbutton('disable');
						$('#insertRoweditor').linkbutton('disable');
						$('#deleteEditor').linkbutton('disable');
						$('#setPrice').linkbutton('enable');

						$('#epo_disc').numberbox('disable');
						$('#epo_subtot').numberbox('disable');
						$('#epo_disc_prc').textbox('disable');
						$('#epo_dpp').numberbox('disable');
						$('#epo_vat').numberbox('disable');
						$('#epo_dp').numberbox('disable');
						$('#epo_grandtot').numberbox('disable');
						$('#setQty').linkbutton('enable');
					}else{
						$('#e_category').combobox('enable');
						$('#e_subcategory').combobox('enable');
						$('#e_type').combobox('enable');
						$('#epo_no').textbox('enable');
						$('#epo_date').datebox('enable');
						$('#epo_req_date').datebox('enable');
						$('#e_supplier').combobox('enable');
						$('#e_payterm').combobox('enable');
						$('#e_vat').combobox('enable');
						$('#e_notes').textbox('enable');
						$('#e_rev').combobox('enable');
						$('#findParteditor').linkbutton('enable');
						$('#insertRoweditor').linkbutton('enable');
						$('#deleteEditor').linkbutton('enable');
						$('#setPrice').linkbutton('enable');
						$('#setQty').linkbutton('disable');
						$('#epo_disc').numberbox('enable');
						$('#epo_subtot').numberbox('enable');
						$('#epo_disc_prc').textbox('enable');
						$('#epo_dpp').numberbox('enable');
						$('#epo_vat').numberbox('enable');
						$('#epo_dp').numberbox('enable');
						$('#epo_grandtot').numberbox('enable');
					}
					
					$.post('purchase_order_geteditor.php?doc_no='+row.doc_no).done(function(res){
						//alert(res);
						$('#dg_editor').datagrid('loadData', []);
						$('#dg_editor').datagrid({
							url: 'purchase_order_geteditor.php?doc_no='+row.doc_no
						});
						var doc = row.doc_no;

						$.ajax({
						type: 'GET',
						url: 'purchase_order_cekharga.php',
						//data: { price:'price', part_no:'brg_codebarang', kode_supplier:'kode_supplier' },
						success: function(data){
							$('#dg_editor').datagrid({
							    rowStyler:function(index,row){
							    	var str = $.trim(row.part_no); 
					
									if (str.substr(0,5)=='<span') {
								   		return 'background-color:#d0d0d0;color:blue;';
									};
							       }
							   });
							var i = 0;
							for (i = 0; i >= parseInt(data['total'])-1; i++) {
								var part = data['rows'][i].brg_codebarang;
								var supplier = data['rows'][i].kode_supplier;
								var price = data['rows'][i].price;
							};
							//alert(part);
						
						}
					});

						//$('#dgEditor').datagrid('loadData',res);
					});
					//$('#eso_doc').textbox('readonly',true);
					url = 'purchase_order_update.php?doc_no='+row.doc_no;
				}
			}
			
			
				
			
			function savePo(){
				var date = new Date();
				var jam = ("0" + date.getHours()).slice(-2)+':'+("0" + date.getMinutes()).slice(-2)+':'+("0" + date.getSeconds()).slice(-2);
				var tanggal = date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + date.getDate()).slice(-2)+' '+jam;
				//alert($('#so_no').combogrid('getText')+' '+$('#so_no').combogrid('getValue'));
				var i = 0;
				var a = 0;
				var proc = 0;
				var totqty = 0;
				var total = $('#dg_entry').datagrid('getData').total;
				for (var a = 0; a < total ; a++) {
					var qty = $('#dg_entry').datagrid('getData').rows[a].po_qty;
					var part = $('#dg_entry').datagrid('getData').rows[a].po_partno;

					if (parseFloat(qty)==0 && $.trim(part) !='NS') {
						totqty++;
					};
				};


				if (totqty == 0) {
					var kategori = $('#c_category').combobox('getText');
					if ($.trim(kategori)=='RM') {
						kategori = $('#c_subcategory').combobox('getText');
					};

					//alert(kategori);
					$.ajax({
						type: 'GET',
						url: 'json/json_po_code.php?kategori='+$.trim(kategori)+'&type='+$('#c_type').combobox('getText')+'&sub='+$('#c_subtype').combobox('getText'),
						data: { kode:'kode' },
						success: function(data){
							var kat = $('#c_category').combobox('getText');
							var type = $('#c_type').combobox('getText');
							var sub = $('#c_subtype').combobox('getText');
							var ket = '';
							if($.trim(kat)=='RM'){
								kat = $('#c_subcategory').combobox('getText');
							}else{
								kat = $('#c_category').combobox('getText');
							}


							if($.trim(kat)=='ALK'){
								ket='ALK';
							}

							if($.trim(kat)=='RM'){
								if($.trim(type)=='TRADING'){
									ket='RM';
								}
								if($.trim(type)=='ASKARA'){
									ket='RMAI';
								}									
							}
							if($.trim(kat)=='GE'){
								ket = 'GE';
							}
							if($.trim(kat)=='GD'){
								ket = 'GD';
							}
							if($.trim(kat)=='GH'){
								ket = 'GH';
							}
							if($.trim(kat)=='GR'){
								ket = 'GR';
							}
							if($.trim(kat)=='GP'){
								ket = 'GP';
							}

							if($.trim(kat)=='NRM'){
								if($.trim(type)=='TRADING'){
									if($.trim(sub)=='NRM'){
										ket='NRM';
									}
									if($.trim(sub)=='TS'){
										ket='TS';
									}
								}

								if($.trim(type)=='ASKARA'){
									if($.trim(sub)=='NRM'){
										ket='AI';
									}
									if($.trim(sub)=='TS'){
										ket='AITS';
									}
								}
							}
							var delivdate = $('#po_date').datebox('getValue');
							var date = delivdate.substr(5,2)+delivdate.substr(2,2);
							var kode=data[0].kode+'/'+ket+'/'+date;
							//alert(kode);

							for(i=0;i<total;i++){
							$('#dg_entry').datagrid('endEdit', i);
							//alert(i);
							//alert($('#dg2').datagrid('getData').rows[i].remarks);

							$.post('purchase_order_save.php',{
								so_no: $('#so_no').combogrid('getText'),
								po_no: kode,
								po_type: $('#c_type').combobox('getValue'),
								po_subtype: $('#c_subtype').combobox('getText'),
								po_date: $('#po_date').datebox('getValue'),
								po_req_date: $('#po_req_date').datebox('getValue'),
								po_sup_id: $('#c_supplier').combobox('getValue'),
								po_category: $('#c_category').combobox('getValue'),
								po_subcategory: $('#c_subcategory').combobox('getValue'),
								po_pay: $('#c_payterm').combobox('getValue'),
								poc_vat: $('#c_vat').combobox('getValue'),
								po_notes: $('#c_notes').textbox('getValue'),
								ns_id: $('#dg_entry').datagrid('getData').rows[i].po_ns,
								po_brgcodebarang: $('#dg_entry').datagrid('getData').rows[i].po_partno,
								po_part_name: $('#dg_entry').datagrid('getData').rows[i].po_namabarang,
								po_satuan: $('#dg_entry').datagrid('getData').rows[i].pouom,
								po_currency: $('#dg_entry').datagrid('getData').rows[i].po_currency,
								po_qty: $('#dg_entry').datagrid('getData').rows[i].po_qty,
								po_price: $('#dg_entry').datagrid('getData').rows[i].po_price,
								po_disc: $('#dg_entry').datagrid('getData').rows[i].po_disc,
								po_subtot: $('#dg_entry').datagrid('getData').rows[i].po_subtotal,
								remarks: $('#dg_entry').datagrid('getData').rows[i].po_remarks,
								po_sumsubtot: $('#po_subtot').numberbox('getValue'),
								po_discall: $('#po_disc_prc').textbox('getValue'),
								po_dpp: $('#po_dpp').numberbox('getValue'),
								po_vat: $('#po_vat').numberbox('getValue'),
								po_dp: $('#po_dp').numberbox('getValue'),
								po_grandtot: $('#po_grandtot').numberbox('getValue'),
								last_update: tanggal,
								idx: i
							}).done(function(res){
								//alert(res);
								$('#dlg').dialog('close');
								$('#dg').datagrid('reload');	// reload data
							})
						}
							$.messager.alert('Information',kode+' is Saved','info');

						 	//$('#po_no').textbox('setValue',data[0].kode+'/'+ket+'/<?=date('m');?><?=date('y');?>');
						}
					});
					
					
				}else{
					$.messager.alert('Information','Quantity must bigger than 0','error');
				}
			}

			$('#dg_entry').datagrid({
			    onClickRow:function(rowIndex){
			        if (lastIndex != rowIndex){
			            $(this).datagrid('endEdit', lastIndex);
			            //alert(lastIndex);
			            //$(this).datagrid('beginEdit', rowIndex);
			        }
			        lastIndex = rowIndex;
			    },
			    onDblClickRow:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    	var code = $(this).datagrid('getEditor',{index:rowIndex, field:'po_partno'});
			    	var cd = $(code.target).val();

			    	//alert(cd);

			    	if ($.trim(cd)!='NS') {
			    		var editors = $(this).datagrid('getEditors', rowIndex);
			    		var price = $(editors[5].target);
			    		var cur = $(editors[4].target);

			    		price.numberbox('disable');
			    		cur.combobox('disable');
			    	};
			    },
			    onBeginEdit:function(rowIndex){
			        var editors = $('#dg_entry').datagrid('getEditors', rowIndex);
			        var n1 = $(editors[3].target);
			        var n2 = $(editors[5].target);
			        var n3 = $(editors[6].target);
			        var n4 = $(editors[7].target);
			        //alert(n1.numberbox);

			        n1.add(n2).add(n3).numberbox({
			            onChange:function(){
			            	var prc = (n3.numberbox('getValue')/100);
			                var cost = (n1.numberbox('getValue')*n2.numberbox('getValue'))*prc;
			                var hasil = (n1.numberbox('getValue')*n2.numberbox('getValue'))-cost;
			                n4.numberbox('setValue',hasil);
			                //alert(lastIndex);
			                var row = $('#dg_entry').datagrid('getData').rows;
							var i = 0; 
							var subtotal=0;
							var total = $('#dg_entry').datagrid('getData').total;
							for(i=0;i<total;i++){
								$('#dg_entry').datagrid('endEdit', i);
								var sb = $('#dg_entry').datagrid('getData').rows[i].po_subtotal.replace(/,/g,'');
								var subtotal = subtotal+ parseFloat(sb); 
								//alert(sb+''+subtotal);
							}
							//alert(subtotal);
							var nol=0;
							var prc= (10/100);
							if ($.trim($('#c_vat').combobox('getText'))=='EXCLUSIVE') {
								$('#po_disc_prc').textbox('setValue', '0');
								var vat=parseFloat($.trim(subtotal))*prc;
								subtot=parseFloat($.trim(subtotal));
								$('#po_vat').numberbox('setValue',vat);
								var so_vat=parseFloat($('#po_vat').numberbox('getValue'));
								$('#po_dpp').numberbox('setValue', parseFloat($.trim(subtotal)));
								$('#po_subtot').numberbox('setValue', subtot);
								$('#po_disc').numberbox('setValue', 0);
								$('#po_dp').numberbox('setValue', 0);
								
								var grand = parseFloat($('#po_dpp').numberbox('getValue'))+so_vat;
								$('#po_grandtot').numberbox('setValue',grand);
							}
							else if($.trim($('#c_vat').combobox('getValue'))=='INCLUSIVE'){
								$('#po_disc_prc').textbox('setValue', '0');
								var vat=parseFloat($.trim(subtotal))*prc;
								subtot=parseFloat($.trim(subtotal));
								$('#po_vat').numberbox('setValue',vat);
								var so_vat=parseFloat($('#po_vat').numberbox('getValue'));
								$('#po_dpp').numberbox('setValue', parseFloat($.trim(subtotal)-so_vat));
								$('#po_subtot').numberbox('setValue', parseFloat($('#po_dpp').numberbox('getValue'))+so_vat);
								$('#po_disc').numberbox('setValue', 0);
								$('#po_dp').numberbox('setValue', 0);
								
								var grand = parseFloat($('#po_dpp').numberbox('getValue'))+so_vat;
								$('#po_grandtot').numberbox('setValue',grand);
							}
							else{
								$('#po_disc_prc').textbox('setValue', '0');
								var vat=parseFloat($.trim(subtotal))*prc;
								subtot=parseFloat($.trim(subtotal));
								$('#po_vat').numberbox('setValue', 0);
								var so_vat=parseFloat($('#po_vat').numberbox('getValue'));
								$('#po_dpp').numberbox('setValue', parseFloat($.trim(subtotal)));
								$('#po_subtot').numberbox('setValue', subtot);
								$('#po_disc').numberbox('setValue', 0);
								$('#po_dp').numberbox('setValue', 0);
								
								var grand = parseFloat($('#po_dpp').numberbox('getValue'));
								$('#po_grandtot').numberbox('setValue',grand);	
								//$('#po_vat').numberbox('setValue',0);
							}

							
							
			            }
			        })
			    }
			});
		   
			$('#dg_editor').datagrid({
			    onClickRow:function(rowIndex){
			        if (lastIndex != rowIndex){
			            $(this).datagrid('endEdit', lastIndex);
			        }
			        lastIndex = rowIndex;
			    },
			    onDblClickRow:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    	var editors = $(this).datagrid('getEditors', rowIndex);
			    	var part_name = $(editors[0].target);
			    	var uom = $(editors[1].target);
			    	var qty = $(editors[2].target);
			    	var cur = $(editors[3].target);
			    	var price = $(editors[4].target);
			    	var disc = $(editors[5].target);
			    	var subtot = $(editors[6].target);
			    	var remark = $(editors[7].target);
			    	//alert(rowIndex);
			    	//alert(rec_sts);
			    	var brg = $(this).datagrid('getData').rows[rowIndex].part_no;
			    	if ($.trim(brg)!='NS') {

			    		price.numberbox('disable');
			    	};
			    	if (rec_sts=='RECEIPT') {
			    		part_name.textbox('disable');
			    		uom.combobox('disable');
						qty.numberbox('disable');
						cur.combobox('disable');
						disc.numberbox('disable');
						subtot.numberbox('disable');
						remark.textbox('disable');
			    	}else{
			    		part_name.textbox('enable');
			    		uom.combobox('enable');
						qty.numberbox('enable');
						cur.combobox('enable');
						disc.numberbox('enable');
						subtot.numberbox('enable');
						remark.textbox('enable');
			    	}
			    },
			    onBeginEdit:function(rowIndex){
			        var editors = $('#dg_editor').datagrid('getEditors', rowIndex);
			        var n1 = $(editors[2].target);
			        var n2 = $(editors[4].target);
			        var n3 = $(editors[5].target);
			        var n4 = $(editors[6].target);
			        //alert(n1.numberbox);

			        n1.add(n2).add(n3).numberbox({
			            onChange:function(){
			            	var prc = (n3.numberbox('getValue')/100);
			                var cost = (n1.numberbox('getValue')*n2.numberbox('getValue'))*prc;
			                var hasil = (n1.numberbox('getValue')*n2.numberbox('getValue'))-cost;
			                n4.numberbox('setValue',hasil);
			                //alert(lastIndex);
			                var row = $('#dg_editor').datagrid('getData').rows;
							var i = 0; 
							var subtotal=0;
							var total = $('#dg_editor').datagrid('getData').total;
			            	$('#dg_editor').datagrid('endEdit', rowIndex);
							for(i=0;i<total;i++){
								$('#dg_editor').datagrid('endEdit', i);
								var sb = parseFloat($('#dg_editor').datagrid('getData').rows[i].subtotal);
								var subtotal = parseFloat(subtotal) + parseFloat(sb); 
								console.log(sb+''+subtotal);
							}
							//alert(subtotal);
							var nol=0;
							var prc= (10/100);
							//alert($('#e_vat').combobox('getText'));
							if ($.trim($('#e_vat').combobox('getText'))=='EXCLUSIVE') {
								$('#epo_disc_prc').textbox('setValue', '0');
								var vat=parseFloat($.trim(subtotal))*prc;
								subtot=parseFloat($.trim(subtotal));
								$('#epo_vat').numberbox('setValue',vat);
								var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
								//alert(parseFloat($.trim(subtotal)));
								$('#epo_dpp').numberbox('setValue', $.trim(subtotal));
								$('#epo_subtot').numberbox('setValue', subtot);
								$('#epo_disc').numberbox('setValue', 0);
								$('#epo_dp').numberbox('setValue', 0);
								
								var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
								$('#epo_grandtot').numberbox('setValue', grand);	
							}
							else if($.trim($('#e_vat').combobox('getText'))=='INCLUSIVE'){
								$('#epo_disc_prc').textbox('setValue', '0');
								var vat=parseFloat($.trim(subtotal))*prc;
								subtot=parseFloat($.trim(subtotal));
								$('#epo_vat').numberbox('setValue', vat);
								var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
								$('#epo_dpp').numberbox('setValue', parseFloat($.trim(subtotal)-so_vat));
								$('#epo_subtot').numberbox('setValue', parseFloat(parseFloat($.trim(subtotal)-so_vat)+so_vat)) 	;
								//alert(parseFloat($.trim(subtotal)-so_vat));
								$('#epo_disc').numberbox('setValue', 0);
								$('#epo_dp').numberbox('setValue', 0);
								
								var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
								$('#epo_grandtot').numberbox('setValue', grand);
							}
							else{
								$('#epo_disc_prc').textbox('setValue', '0');
								var vat=parseFloat($.trim(subtotal))*prc;
								subtot=parseFloat($.trim(subtotal));
								$('#epo_vat').numberbox('setValue',0);
								var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
								$('#epo_dpp').numberbox('setValue', parseFloat($.trim(subtotal)));
								$('#epo_subtot').numberbox('setValue', subtot);
								$('#epo_disc').numberbox('setValue', 0);
								$('#epo_dp').numberbox('setValue', 0);
								
								var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
								$('#epo_grandtot').numberbox('setValue', grand);	
								//$('#epo_vat').numberbox('setValue',0);
							}

							
							
			            }
			        })
			    }
			});

			$('#epo_dp').numberbox({
				onChange: function(rec){
					var grand = parseFloat($('#epo_subtot').numberbox('getValue'))+parseFloat($('#epo_vat').numberbox('getValue'));
					var gtot = grand-(parseFloat($('#epo_disc').numberbox('getValue')));
					//alert(gtot);
					$('#epo_grandtot').numberbox('setValue',grand-rec);
				}
			});
			$('#epo_disc_prc').textbox({
				onChange: function(rec){
				//	alert(esubtot);
					if ($('#e_vat').combobox('getValue')=='EXCLUSIVE') {
						var nilai = esubtot*(rec/100);
						var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
						var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
						//var nilai1= nilai*(rec/100);
						//alert(grand);
						var vat=parseFloat($.trim(esubtot))*(10/100);
						var dppsdisc = esubtot - nilai;
						$('#epo_dpp').numberbox('setValue', dppsdisc);
						$('#epo_vat').numberbox('setValue', dppsdisc * (10/100));
						$('#epo_disc').numberbox('setValue',nilai);
						var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+parseFloat($('#epo_vat').numberbox('getValue'));
						$('#epo_grandtot').numberbox('setValue', grand);
						//alert(nilai);

					}
					else if ($('#e_vat').combobox('getValue')=='INCLUSIVE') {
						var nilai = esubtot*(rec/100);
						var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
						var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
						//var nilai1= nilai*(rec/100);
						//alert(grand);
						var vat=parseFloat($.trim(esubtot))*(10/100);
						//var so_vat = parseInt($('#so_vat').numberbox('getValue'));
						var dppsdisc = (esubtot-vat) - nilai;
						$('#epo_dpp').numberbox('setValue', dppsdisc);
						$('#epo_vat').numberbox('setValue', dppsdisc * (10/100));
						$('#epo_disc').numberbox('setValue',nilai);
						var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+vat;
						$('#epo_grandtot').numberbox('setValue', grand);
						//alert(nilai);
					}else{
						/*$('#epo_disc_prc').textbox('setValue', '0');
						var vat=parseFloat($.trim(subtotal))*prc;
						subtot=parseFloat($.trim(subtotal));
						$('#epo_vat').numberbox('setValue',0);
						var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
						$('#epo_dpp').numberbox('setValue', parseFloat($.trim(subtotal)));
						$('#epo_subtot').numberbox('setValue', subtot);
						$('#epo_disc').numberbox('setValue', 0);
						$('#epo_dp').numberbox('setValue', 0);
						
						var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
						$('#epo_grandtot').numberbox('setValue', grand);	
						//$('#epo_vat').numberbox('setValue',0);*/

						var nilai = esubtot*(rec/100);
						var so_vat=parseFloat($('#epo_vat').numberbox('getValue'));
						var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+so_vat;
						//var nilai1= nilai*(rec/100);
						//alert(grand);
						var vat=parseFloat($.trim(esubtot))*(10/100);
						var dppsdisc = esubtot - nilai;
						$('#epo_dpp').numberbox('setValue', dppsdisc);
						$('#epo_vat').numberbox('setValue', 0);
						$('#epo_disc').numberbox('setValue',nilai);
						var grand = parseFloat($('#epo_dpp').numberbox('getValue'))+parseFloat($('#epo_vat').numberbox('getValue'));
						$('#epo_grandtot').numberbox('setValue', grand);
						//alert(nilai);
					}

				}
			});


			$('#po_dp').numberbox({
				onChange: function(rec){
					var grand = parseFloat($('#po_subtot').numberbox('getValue'))+parseFloat($('#po_vat').numberbox('getValue'));
					var gtot = grand-(parseFloat($('#po_disc').numberbox('getValue')));
					//alert(gtot);
					$('#po_grandtot').numberbox('setValue',grand-rec);
				}
			});
			$('#po_disc_prc').textbox({
				onChange: function(rec){
					//alert(subtot);
					if ($('#c_vat').combobox('getValue')=='EXCLUSIVE') {
						var nilai = subtot*(rec/100);
						var so_vat=parseFloat($('#po_vat').numberbox('getValue'));
						var grand = parseFloat($('#po_dpp').numberbox('getValue'))+so_vat;
						//var nilai1= nilai*(rec/100);
						//alert(grand);
						var vat=parseFloat($.trim(subtot))*(10/100);
						var dppsdisc = subtot - nilai;
						$('#po_dpp').numberbox('setValue', dppsdisc);
						$('#po_vat').numberbox('setValue', dppsdisc * (10/100));
						$('#po_disc').numberbox('setValue',nilai);
						var grand = parseFloat($('#po_dpp').numberbox('getValue'))+parseFloat($('#po_vat').numberbox('getValue'));
						$('#po_grandtot').numberbox('setValue', grand);
						//alert(nilai);

					}
					else if ($('#c_vat').combobox('getValue')=='INCLUSIVE') {
						var nilai = subtot*(rec/100);
						var so_vat=parseFloat($('#po_vat').numberbox('getValue'));
						var grand = parseFloat($('#po_dpp').numberbox('getValue'))+so_vat;
						//var nilai1= nilai*(rec/100);
						//alert(grand);
						var vat=parseFloat($.trim(subtot))*(10/100);
						//var so_vat = parseInt($('#so_vat').numberbox('getValue'));
						var dppsdisc = (subtot-vat) - nilai;
						$('#po_dpp').numberbox('setValue', dppsdisc);
						$('#po_vat').numberbox('setValue', dppsdisc * (10/100));
						$('#po_disc').numberbox('setValue',nilai);
						var grand = parseFloat($('#po_dpp').numberbox('getValue'))+vat;
						$('#po_grandtot').numberbox('setValue', grand);
						//alert(nilai);
					}else{
						var nilai = subtot*(rec/100);
						var so_vat=parseFloat($('#po_vat').numberbox('getValue'));
						var grand = parseFloat($('#po_dpp').numberbox('getValue'))+so_vat;
						//var nilai1= nilai*(rec/100);
						//alert(grand);
						var vat=parseFloat($.trim(subtot))*(10/100);
						var dppsdisc = subtot - nilai;
						$('#po_dpp').numberbox('setValue', dppsdisc);
						$('#po_vat').numberbox('setValue', 0);
						$('#po_disc').numberbox('setValue',nilai);
						var grand = parseFloat($('#po_dpp').numberbox('getValue'))+parseFloat($('#po_vat').numberbox('getValue'));
						$('#po_grandtot').numberbox('setValue', grand);
						//alert(nilai);
					}

				}
			});

			function insertRow(){
				var code;
				$.ajax({
					type: 'GET',
					url: 'json/json_nonstock.php',
					data: { kode:'kode' },
					success: function(data){
					   	
					   	code=data[0].kode;
					   	var price = 0;
						var total = $('#dg_entry').datagrid('getData').total;
						
						//alert(code);
						
						var idxfield=0;
						if (parseInt(total)==0) {
							idxfield=parseInt(total);
							
						}else{
							idxfield=total+1;
							var k= $('#dg_entry').datagrid('getData').rows[total-1].po_partno;
							
							if ($.trim(k.substr(0,2))=='NS') {
								var ns_code = $('#dg_entry').datagrid('getData').rows[total-1].po_ns;
								var code = parseInt(ns_code)+1;
							}
							else{
								code=data[0].kode;
							}
						}
						$('#dg_entry').datagrid('insertRow',{
							index: idxfield,	// index start with 0
							row: {
								po_ns: code,
								po_partno: 'NS',
								po_price: 0,
								po_disc:0,
								po_qty: 0,
								po_subtotal: 0+'.00'
							}
						});
							
					}
				});
				//alert(code);
				
			}

			function editClear(){
					unsetSession('unset_po', $('#po_no').textbox('getValue'));
					$('#dg_entry').datagrid('loadData', []);
				}

			function editRemove(){
				var row = $('#dg_entry').datagrid('getSelected');
				
				if (row){
					unsetSession('unset_po_part', $('#po_no').textbox('getValue'), row.po_partno);
					var idx = $("#dg_entry").datagrid("getRowIndex", row);
					$('#dg_entry').datagrid('deleteRow', idx);
				}
			}

			function unsetSession(type, pono, partno){
				var setdata;
				if(type=='unset_all'){
					setdata = {action: type};
				}
				if(type=='unset_po'){
					setdata = {action: type, po_no: pono};
				} 
				if(type=='unset_po_part'){
					setdata = {action: type, po_no: pono, part_no: partno};
					//alert(setdata);
					
				}
				$.post("purchase_order_popadd.php", setdata);
				
			}

			function findPart(){
				$('#dg_findpart').datagrid('reload');
				//var dg = $('#dg_findpart').datagrid();
				//dg.datagrid('enableFilter');
				$('#dlg_part').window('open').window('setTitle','Find Part');
				$('#dg_findpart').datagrid('loadData', []);
				$('#dg_findpart').datagrid({
					url: 'purchase_order_get_part.php?kategori='+$('#c_category').combobox('getValue')+'&supplier='+$('#c_supplier').combobox('getValue')
				});

				$('#dg_findpart').datagrid('enableFilter');
				
			}

			function findMore(){
				//alert();
				$('#dg_findmore').datagrid('reload');
				//var dg = $('#dg_findpart').datagrid();
				//dg.datagrid('enableFilter');
				$('#dlg_more').window('open').window('setTitle','Find More');
				$('#dg_findmore').datagrid('loadData', []);
				$('#dg_findmore').datagrid({
					url: 'purchase_order_get_part.php?kategori='+$('#c_category').combobox('getValue')+'&supplier='+$('#c_supplier').combobox('getValue')
				});

				$('#dg_findmore').datagrid('enableFilter');
			}

			function findParteditor(){
				$('#dg_findeditor').datagrid('reload');
				
				$('#dlg_parteditor').window('open').window('setTitle','Find Part');
				$('#dg_findeditor').datagrid('loadData', []);
				$('#dg_findeditor').datagrid({
					url: 'purchase_order_get_part.php?kategori='+$('#e_category').combobox('getValue')+'&supplier='+$('#e_supplier').combobox('getValue')
				});
				var dg = $('#dg_findeditor').datagrid();
				dg.datagrid('enableFilter');
			}

			$('#dg_findpart').datagrid({
				onDblClickRow:function addSalesList(id, row){
					//alert(row.brg_codebarang);
					/*post("purchase_order_popadd.php", 
						{
							po_no: $('#po_no').textbox('getValue'),
							part_no: row.brg_codebarang,
							part_name: row.nama_barang,
							uom: row.ket_satuan,
							price: row.price,
							currecy: row.currecy
						})
						.done(function(res) {
							//alert(res);
							$('#dg_entry').datagrid('loadData', []);
							$('#dg_entry').datagrid({
								url: 'purchase_order_popadd.php?po_no='+$('#po_no').textbox('getValue')
							});

						}
					);*/

							var total = $('#dg_entry').datagrid('getData').total;
						   	var idxfield=0;
						   	var i = 0;
						   	var count = 0;
							if (parseInt(total)==0) {
								idxfield=total;
							}else{
								idxfield=total+1;
								for (i=0; i < total; i++) {
									//alert(i);
									var part = $('#dg_entry').datagrid('getData').rows[i].po_partno;
									//alert(part);
									if (part == row.brg_codebarang) {
										count++;
									};
								};
							}

							//alert('count = '+count);
							if (count>0) {
								$.messager.alert('Information','Part present','warning');
							}else{
								$('#dg_entry').datagrid('insertRow',{
									index: idxfield,	// index start with 0
									row: {
										po_partno: row.brg_codebarang,
										po_namabarang: row.nama_barang,
										pouom: row.ket_satuan,
										po_qty: 0,
										po_currency: row.currecy,
										po_price: row.price,
										po_disc: 0,
										po_subtotal:0,
										po_remarks: ''
									}
								});
							}
				}
			});

			$(function(){
				$('#subcat1').hide();
				$('#subcat').hide();
				$('#esubcat').hide();
				//filterData();
				/*$('#dg').datagrid('enableFilter', [{
					field:'ket_kategori',
					type:'combobox',
					options:{
						panelHeight:'auto',
						url:'json/json_category.php',
						valueField: 'ket_kategori',
						textField: 'ket_kategori',
						
						onChange:function(value){
							//alert(value);
							if ($.trim(value) == ''){
								dg.datagrid('removeFilterRule', 'ket_kategori');
							} else {
								dg.datagrid('addFilterRule', {
									field: 'ket_kategori',
									op: 'equal',
									value: $.trim(value)
								});
								//alert(value);
							}
							dg.datagrid('doFilter');
						}
					} 
				}]);*/
				$('#part_').combogrid('disable');

				$('#ck_part').change(function(){
					if ($(this).is(':checked')) {
						$('#part_').combogrid('disable');
					}else{
						$('#part_').combogrid('enable');
					}
				});

				$('#s_periode_awal').datebox('disable');
				$('#s_periode_akhir').datebox('disable');
				
				$('#ck_date').change(function(){
					if ($(this).is(':checked')) {
						$('#s_periode_awal').datebox('disable');
						$('#s_periode_akhir').datebox('disable');
					}
					if (!$(this).is(':checked')) {
						$('#s_periode_awal').datebox('enable');
						$('#s_periode_akhir').datebox('enable');
					};
				})

				$('#category').combobox('disable');
				$('#ck_kategori').change(function(){
					if ($(this).is(':checked')) {
						$('#category').combobox('disable');
						$('#subcat1').hide();
					}
					if (!$(this).is(':checked')) {
						//alert('HOREEEEEE!!!');
						$('#category').combobox('enable');
						
					};
				})
				$('#supplier').combobox('disable');
				$('#ck_supplier').change(function(){
					if ($(this).is(':checked')) {
						$('#supplier').combobox('disable');
					}
					if (!$(this).is(':checked')) {
						//alert('HOREEEEEE!!!');
						$('#supplier').combobox('enable');
						
					};
				})

				$('#type').combobox('disable');
				$('#ck_type').change(function(){
					if ($(this).is(':checked')) {
						$('#type').combobox('disable');
					}
					if (!$(this).is(':checked')) {
						//alert('HOREEEEEE!!!');
						$('#type').combobox('enable');
						
					};
				})

				$('#status').combobox('disable');
				$('#ck_status').change(function(){
					if ($(this).is(':checked')) {
						$('#status').combobox('disable');
					}
					if (!$(this).is(':checked')) {
						//alert('HOREEEEEE!!!');
						$('#status').combobox('enable');
						
					};
				})
			})

			function addPo(){
				$('#dlg').dialog('open').dialog('setTitle','Create Purchase Order');
				$('#dg_entry').datagrid('loadData',[]);
				$('#c_subtype').combobox('setValue','');
				$('#c_category').combobox('setValue','');
				$('#c_subcategory').combobox('setValue','');
				$('#c_type').combobox('setValue','');
				$('#c_supplier').combobox('setValue','');
				$('#c_vat').combobox('setValue','');
				$('#c_payterm').combobox('setValue','');
				$('#po_no').textbox('setValue','');
				$('#c_notes').textbox('setValue','');
				$('#po_subtot').numberbox('setValue','0');
				$('#po_disc_prc').textbox('setValue','0');
				$('#po_disc').numberbox('setValue','0');
				$('#po_vat').numberbox('setValue','0');
				$('#po_dpp').numberbox('setValue','0');
				$('#po_dp').numberbox('setValue','0');
				$('#po_grandtot').numberbox('setValue','0');
				$('#po_req_date').datebox('setValue', '');
				$('#po_date').datebox('setValue','<?=date('Y-m-d')?>');
			}

			$('#dg_editor').datagrid({
			    url:'',
			    toolbar: '#tl_editor',
			    singleSelect: true,
				pagination: false,
				rownumbers: true,
				remoteFilter: true,
				sortName: 'username',
				sortOrder: 'asc',
			    columns:[[
				    {field:'part_no',title:'Part No',width:100},
				    {field:'part_name',title:'Part Name',width:100, 
				    editor:{type:'textbox'}},
				    {field:'ket_satuan',title:'Uom',width:80,
				    editor:{type:'combobox',
							options:{
								url:'json/json_satuan.php',
								method:'get',
								panelHeight: '100px',
								valueField:'ket_satuan',
								textField:'ket_satuan'
							}}},
				    {field:'qty',title:'Qty',width:80, align:'right', editor:{type:'numberbox',options:{precision:2}}},
				    {field:'currency',title:'Currency',width:80, 
				    editor:{type:'combobox',
							options:{
								url:'json/json_currency.php',
								method:'get',
								panelHeight: '100px',
								valueField:'currecy',
								textField:'currecy'
							}}},
				    {field:'price',title:'Price', id:'pricetxt', align:'right', width:100, 
				    editor:{type:'numberbox',
				    	options:{precision:5,
				    		groupSeparator:','}}},

				    {field:'disc',title:'Disc %', align:'right', width:80, 
				    editor:{type:'numberbox',
				    	options:{precision:0,
				    		groupSeparator:','}}},

				    {field:'subtotal',title:'Subtotal', align:'right', width:100, 
				    editor:{type:'numberbox',
				    	options:{precision:5,
				    		groupSeparator:','}}},

				    {field:'remarks',title:'Remarks',width:100, 
				    editor:{type:'textbox'}},
				    {field:'ns_id',title:'NS ID',width:100,hidden:'true'},
				    {field:'po_no',title:'Po No',width:100,hidden:'true'} //
			    ]]
		    });

			$('#dg_entry').datagrid({
			    url:'',
			    toolbar: '#tl_entry',
			    singleSelect: true,
				pagination: false,
				rownumbers: true,
				remoteFilter: true,
				sortName: 'username',
				sortOrder: 'asc',
			    columns:[[
				    {field:'po_partno', halign:'center', title:'Part No',width:100, 
				    editor:{type:'textbox'}},
				    {field:'po_namabarang', halign:'center', title:'Part Name',width:100, 
				    editor:{type:'textbox'}},
				    {field:'pouom', halign:'center', title:'Uom',width:80,
				    editor:{type:'combobox',
							options:{
								url:'json/json_satuan.php',
								method:'get',
								panelHeight: '100px',
								valueField:'ket_satuan',
								textField:'ket_satuan'
							}}},
				    {field:'po_qty', halign:'center', title:'Qty',width:80, align:'right', editor:{type:'numberbox',options:{precision:2}}},
				    {field:'po_currency', halign:'center', title:'Currency',width:80, 
				    editor:{type:'combobox',
							options:{
								url:'json/json_currency.php',
								method:'get',
								panelHeight: '100px',
								valueField:'currecy',
								textField:'currecy'
							}}},
				    {field:'po_price', halign:'center', title:'Price', align:'right', width:100, 
				    editor:{type:'numberbox',
				    	options:{precision:5,
				    		groupSeparator:','}}},

				    {field:'po_disc', halign:'center', title:'Disc %', align:'right', width:80, 
				    editor:{type:'numberbox',
				    	options:{precision:0,
				    		groupSeparator:','}}},

				    {field:'po_subtotal', align:'right',  halign:'center', title:'Subtotal',width:100, 
				    editor:{type:'numberbox',
				    	options:{precision:5,
				    		groupSeparator:','}}},

				    {field:'po_remarks', halign:'center', title:'Remarks',width:100, 
				    editor:{type:'textbox'}},
				    {field:'po_ns',title:'NS ID',width:100, hidden:'true'}
			    ]]
		    });

			$('#dg').datagrid({
			    url:'purchase_order_get.php',
			    toolbar: '#toolbar',
			    singleSelect: true,
				pagination: true,
				rownumbers: true,
				//remoteFilter: true,
				sortName: 'po_date',
				sortOrder: 'asc',
			    columns:[[
				    {field:'ck', checkbox:'true'},
				    {field:'doc_no',title:'Purchase Order No',width:130, sortable:true},
				    {field:'po_date',title:'PO Date',width:90, sortable:true},
				    {field:'request_date',title:'REQ Date',width:90, sortable:true},
				    {field:'nama_supplier',title:'Supplier Name',width:170, sortable:true},
				    {field:'payterm',title:'Payment Term',width:70, sortable:true},
				    //{field:'currency',title:'Currency',width:70},
				    {field:'ft_grandtot',title:'Grand Total', align:'right', width:70, sortable:true},
				    {field:'ket_kategori',title:'Category',width:70, sortable:true},
				    {field:'nama_subkategori',title:'Sub Category',width:70, sortable:true},
				    {field:'po_type',title:'PO Type',width:70, sortable:true},
				    {field:'notes',title:'Note',width:100, sortable:true},
				    {field:'so_no',title:'SO No.',width:100, sortable:true},
				    {field:'status',title:'PO Status',width:70, sortable:true},
				    {field:'user_entry',title:'User',width:50, sortable:true},
				    {field:'last_update',title:'Last Update',width:150, sortable:true},
				    {field:'receipt_status',title:'Receipt Status',width:70, sortable:true},
				    {field:'stsuse',title:'Trans His',width:70, sortable:true, hidden:true}
			    ]],
			    onClickRow:function(id, row){
			    	if (row.receipt_status=='RECEIPT') {
			    		//alert();
			    		//$('#editPo').linkbutton('disable');
			    		$('#deletePo').linkbutton('disable');
			    	}else{
			    		//$('#editPo').linkbutton('enable');
			    		$('#deletePo').linkbutton('enable');
			    	}
			    },
				view: detailview,
				detailFormatter: function(rowIndex, rowData){
						return '<div style="padding:10px" id="tbdetail'+rowIndex+'"></div><div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
					},
			   
				onExpandRow: function(index,row){
					var uri_doc = encodeURIComponent(row.doc_no);
					var gcd_doc = $.trim(row.doc_no);
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
					var part_no = $('#part_').combogrid('getValue');
					var ck_part = 'false';

					if($('#ck_part').attr("checked")){
						ck_part='true';
					}
					//alert(gcd_doc);

					listbrg.datagrid({
						title: 'Purchase Order (Item: '+row.doc_no+')',
						url:'purchase_order_get_detail.php?doc_no='+uri_doc+'&part_no='+part_no+'&ck_part='+ck_part,
						toolbar: '#tbdetail'+index,
						singleSelect:true,
						rownumbers:true,
						loadMsg:'Please Wait...',
						height:'auto',
						pagination: true,
						rownumbers: true,
						rowStyler: function(index, row){
							
						},
						columns:[[
								{field: 'part_no', halign:'center', title: 'Part No', width: 100, sortable: true},
								{field: 'part_name', halign:'center', title: 'Part Name', width: 225, sortable: true},
								{field: 'ket_satuan', halign:'center', title: 'UoM', width: 75, sortable: true},
								{field: 'qty', title: 'Qty', halign:'center', align:'right', width: 75, sortable: true},
								{field: 'currency', halign:'center', title: 'Currency', width: 75, sortable: true},
								{field: 'price', halign:'center', title: 'Price', align:'right', width: 75, sortable: true},
								{field: 'disc', halign:'center', title: 'Disc %', width: 75, sortable: true},
								{field: 'subtotal', halign:'center', title: 'Subtotal', align:'right', width: 75, sortable: true},
								{field: 'remarks', halign:'center', title: 'Remarks', width: 75, sortable: true}
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
				listbrg.datagrid('enableFilter');
				}
		    });


			
		</script>
    </body>
    </html>