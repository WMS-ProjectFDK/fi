<?php
include("../../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];	

?>

<!DOCTYPE html>
   	<html>
   	<head>
   	<meta charset="UTF-8">
   	<title>Buying Price</title>
   	<link rel="icon" type="image/png" href="../../favicon.png">
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script> 
  	<link rel="stylesheet" type="text/css" href="../../../plugins/font-awesome/css/font-awesome.min.css">
   	<link rel="stylesheet" type="text/css" href="../../../themes/default/easyui.css" />
   	<link rel="stylesheet" type="text/css" href="../../../themes/icon.css" />
   	<link rel="stylesheet" type="text/css" href="../../../themes/color.css" />
   	<script type="text/javascript" src="../../../js/jquery-1.8.3.js"></script>
   	<script type="text/javascript" src="../../../js/jquery.easyui.min.js"></script>
   	<script type="text/javascript" src="../../../js/datagrid-filter.js"></script>
	<script type="text/javascript" src="../../../js/datagrid-detailview.js"></script>
	<script type="text/javascript" src="../../../js/jquery.edatagrid.js"></script>
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
			include ('../../../ico_logout.php');
		?>
		
		<div id="toolbar" style="padding:10px 10px;height:140px">
			<fieldset style="float:left;width:1350px;height:125px;border-radius:4px;"><legend><span class="style3"><strong>Item Filter For Buying Price</strong></span></legend>
			
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Item No.</span>
					<select style="width:330px;" name="cmb_item" id="cmb_item" class="easyui-combobox" data-options=" mode:'remote',url:'json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
					loader: function(param,success,error){
							var opts = $(this).combobox('options');
							if (!opts.url){return false}
							if (param.q != undefined){
								var q = param.q || '';
								if (q.length < 3){return false}
							}
							$.ajax({
								type: 'GET',
								url: 'json/json_item_all.php?id='+param.q,
								dataType: 'json',
								success: function(data){
									success(data);
								},
								error: function(){
									error.apply(this, arguments);
								}
							});
							
						},
					
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
				
			 
		
			  <div class="fitem">
				<span style="width:110px;display:inline-block;">Supplier</span>
				<select style="width:330px;" name="cmb_supplier" id="cmb_supplier" class="easyui-combobox" data-options=" url:'json/json_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
				onSelect: function(rec){
					//alert(rec.company_code);
					//$('#add_po_add').linkbutton('enable');
					//$('#remove_po_add').linkbutton('enable');
					//$('#search_prf').linkbutton('enable');
					
				}"  ></select>
				<label><input type="checkbox" name="ck_supplier" id="ck_supplier" checked="true">All</input></label>
				</div>
				<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2"  onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> Filter Item</a>
	    		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2"  onclick="saveAdd();"><i class="fa fa-save" aria-hidden="true"></i> Add Item</a>
	    		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2"  onclick="saveEdit();"><i class="fa fa-edit" aria-hidden="true"></i> Edit Item</a>
	    		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2"  onclick="saveDelete();"><i class="fa fa-delete" aria-hidden="true"></i> Delete Item</a>
	    		
			</fieldset> 
			
		</div>
	<!-- ADD -->
	
	<div id='dlg_add' class="easyui-dialog" style="width:500px;height:250px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	   <form id="ff" method="post" novalidate>	
		<div class="fitem">
				<span style="width:110px;display:inline-block;">Item No.</span>
					<select style="width:330px;" name="cmb_item_add" id="cmb_item_add" class="easyui-combobox" data-options=" mode:'remote',url:'json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
					loader: function(param,success,error){
							var opts = $(this).combobox('options');
							if (!opts.url){return false}
							if (param.q != undefined){
								var q = param.q || '';
								if (q.length < 3){return false}
							}
							$.ajax({
								type: 'GET',
								url: 'json/json_item_all.php?id='+param.q,
								dataType: 'json',
								success: function(data){
									success(data);
								},
								error: function(){
									error.apply(this, arguments);
								}
							});
							
						},
					
					onSelect:function(rec){
						//alert(rec.id_name_item);
						var spl = rec.id_name_item;
						var sp = spl.split(' - ');
						$('#txt_item_name_add').textbox('setValue', sp[1]);
					}" required=""></select>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Item Name</span>
					<input style="width:330px;" name="txt_item_name_add" id="txt_item_name_add" class="easyui-textbox" disabled=""/>
				
		</div>
		<div class="fitem">
				<span style="width:110px;display:inline-block;">Supplier</span>
				<select style="width:330px;" name="cmb_supplier_add" id="cmb_supplier_add" class="easyui-combobox" data-options=" url:'json/json_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
				onSelect: function(rec){
					//alert(rec.company_code);
					$.ajax({
						type: 'GET',
						url: '../json/json_company_details.php?id='+rec.company_code,
						data: { kode:'kode' },
						success: function(data){
							$('#curr_add').combobox('setValue',data[0].CURR_CODE);
							}
					})
				}" required="" ></select>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Currency.</span>
				<input style="width:85px;" id="curr_add" class="easyui-combobox" data-options=" url:'json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'100px',
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
			</div>

			<div class="fitem">
				<span style="width:110px;display:inline-block;">Estimate Price.</span>
				<input style="width:85px;" name="price_add" id="price_add" class="easyui-numberbox" required=""/>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Leadtime.</span>
				<input style="width:85px;" name="lead_add" id="lead_add" class="easyui-numberbox" required=""/>
			</div>
				
		</div>
		<div id="dlg-buttons-add">
			<input class="easyui-linkbutton c6" iconCls="icon-ok" type="submit" value="Save" onclick="saveBuyingPrice()" style="width:90px; height: 30px;">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
		</div>
	  </form>
	</div>
	<!-- END ADD -->

	<!-- EDIT -->
	
	<div id='dlg_edit' class="easyui-dialog" style="width:500px;height:250px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
	   <form id="fx" method="post" novalidate>	
		<div class="fitem">
				<span style="width:110px;display:inline-block;">Item No.</span>
					<select style="width:330px;" name="cmb_item_edit" id="cmb_item_edit" class="easyui-combobox" data-options=" mode:'remote',url:'json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
					loader: function(param,success,error){
							var opts = $(this).combobox('options');
							if (!opts.url){return false}
							if (param.q != undefined){
								var q = param.q || '';
								if (q.length < 3){return false}
							}
							$.ajax({
								type: 'GET',
								url: 'json/json_item_all.php?id='+param.q,
								dataType: 'json',
								success: function(data){
									success(data);
								},
								error: function(){
									error.apply(this, arguments);
								}
							});
							
						},
					
					onSelect:function(rec){
						//alert(rec.id_name_item);
						var spl = rec.id_name_item;
						var sp = spl.split(' - ');
						$('#txt_item_name_edit').textbox('setValue', sp[1]);
					}" disabled=""></select>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Item Name</span>
					<input style="width:330px;" name="txt_item_name_edit" id="txt_item_name_edit" class="easyui-textbox" disabled=""/>
				
		</div>
		<div class="fitem">
				<span style="width:110px;display:inline-block;">Supplier</span>
				<select style="width:330px;" name="cmb_supplier_edit" id="cmb_supplier_edit" class="easyui-combobox" data-options=" url:'json/json_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
				onSelect: function(rec){
					//alert(rec.company_code);
					$.ajax({
						type: 'GET',
						url: 'json/json_company_details.php?id='+rec.company_code,
						data: { kode:'kode' },
						success: function(data){
							$('#curr_edit').combobox('setValue',data[0].CURR_CODE);
							}
					})
				}" disabled="" ></select>
			</div>
			

			<div class="fitem">
				<span style="width:110px;display:inline-block;">Estimate Price.</span>
				<input style="width:85px;" name="price_edit" id="price_edit" class="easyui-numberbox" required=""/>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Leadtime.</span>
				<input style="width:85px;" name="lead_edit" id="lead_edit" class="easyui-numberbox" required=""/>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Rank.</span>
				<input style="width:85px;" name="rank_edit" id="rank_edit" class="easyui-numberbox" required=""/>
			</div>
				
		</div>
		<div id="dlg-buttons-edit">
			<input class="easyui-linkbutton c6" iconCls="icon-ok" type="submit" value="Save" onclick="saveEditBuyingPrice()" style="width:90px; height: 30px;">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
		</div>
	  </form>
	</div>
	<!-- END ADD -->

	

		<div id="dlg-uploadbc" class="easyui-dialog" style="width: 450px;height: 180px;" closed="true" buttons="#dlg-buttons-print-rec" data-options="modal:true">
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:410px; height:120px; float:left;"><legend>Upload Data BC</legend>
			<div class="fitem">
				
				<form id="uploaddatabc" method="post" enctype="multipart/form-data">
					<input class="easyui-filebox" name="fileexcel" id="fileexcel" style="width:400px;">
					<br></br>
					<a href="javascript:void(0)" style="width:100px;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit" onclick="uploaddatabc()">
						<i class="fa fa-upload" aria-hidden="true"></i> Upload 
					</a>
				
				</form>	
			</div>
			</fieldset>
		</div>

		<div id="dlg-uploadbcsp" class="easyui-dialog" style="width: 450px;height: 180px;" closed="true" buttons="#dlg-buttons-print-rec" data-options="modal:true">
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:410px; height:120px; float:left;"><legend>Upload BC Sparepparts</legend>
			<div class="fitem">
				
				<form id="uploaddatabcsp" method="post" enctype="multipart/form-data">
					<input class="easyui-filebox" name="fileexcelbc" id="fileexcelbc" style="width:400px;">
					<br></br>
					<a href="javascript:void(0)" style="width:100px;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit" onclick="uploaddatabcsp()">
						<i class="fa fa-upload" aria-hidden="true"></i> Upload 
					</a>
				
				</form>	
			</div>
			</fieldset>
		</div>

		<table id="dg" title="Buying Price" class="easyui-datagrid" toolbar="#toolbar	" style="width:auto;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

		<!-- <div id="progress" class="easyui-dialog" style="background-opacity:0.6; width:460px;padding:10px 20px" data-options="modal:true, collapsible:false, minimizable:false,maximizable:false,
			closable:false,closed:true">
			<div id="p" class="easyui-progressbar" data-options="value:0" style="width:400px;"></div>
		</div> -->

		<script type="text/javascript">
			function upload_bc_show(){
				$('#dlg-uploadbc').dialog('open').dialog('setTitle','Upload Data BC');
				$('#fileexcel').filebox('clear');
			}

			function upload_bc_show_sp(){
				$('#dlg-uploadbcsp').dialog('open').dialog('setTitle','Upload Data BC Spareparts');
				$('#fileexcelbc').filebox('clear');
			}

			function uploaddatabc() {
				$('#uploaddatabc').form('submit',{
					url: 'bc_upload.php',
					onSubmit: function(){
						return $(this).form('validate');
					},
					success: function(result){
						$.messager.alert('UPLOAD BC DATA : ',result,'info');
						//alert(result);
				 		$('#fileexcelbc').filebox('clear');
						//$('#dg').datagrid('reload');
						}
				});
			}

			function saveEditBuyingPrice(){
				//alert($('#cmb_supplier_edit').combobox('getValue'));
				$.post('put_buying_price.php',{
						item_no: $('#cmb_item_edit').combobox('getValue'),
						supplier_code: $('#cmb_supplier_edit').combobox('getValue'),
						rank : $('#rank_edit').numberbox('getValue'),
						estimate_price : $('#price_edit').numberbox('getValue'),
						leadtime : $('#lead_edit').numberbox('getValue')
					}).done(function(res){
					
						if(res.length == 4){
							$('#dlg_edit').dialog('close')
							$('#dg').datagrid('reload');
							$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
							
						}else{
							$.messager.alert('ERROR',res,'warning');
							
						}
					});
			}


			function saveBuyingPrice(){
				$.post('post_buying_price.php',{
						item_no: $('#cmb_item_add').combobox('getValue'),
						supplier_code: $('#cmb_supplier_add').combobox('getValue'),
						curr_code : $('#curr_add').combobox('getValue'),
						estimate_price : $('#price_add').numberbox('getValue'),
						leadtime : $('#lead_add').numberbox('getValue')
					}).done(function(res){
						// alert($('#cmb_item_add').combobox('getValue'));
						// alert($('#cmb_supplier_add').combobox('getValue'));
						// alert($('#curr_add').combobox('getValue'));
						// alert($('#price_add').numberbox('getValue'));
						// alert($('#lead_add').numberbox('getValue'));
						
						if(res.length == 4){
							$('#dlg_add').dialog('close')
							$('#dg').datagrid('reload');
							$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
							
						}else{
							$.messager.alert('ERROR',res,'warning');
							
						}
					});
			}
             
			function saveEdit(){
				var row = $('#dg').datagrid('getSelected');	
				if (row){
					$('#dlg_edit').dialog('open').dialog('setTitle','Edit Buying Price');
					$('#cmb_item_edit').combobox('setValue',row.ITEM_NO);
					$('#txt_item_name_edit').textbox('setValue',row.ITEM_NO);
					$('#cmb_supplier_edit').combobox('setValue',row.SUPPLIER_CODE);
					$('#price_edit').numberbox('setValue',row.ESTIMATE_PRICE);
					$('#lead_edit').numberbox('setValue',row.LEAD);
					$('#rank_edit').numberbox('setValue',row.ALTER_PROCEDURE);
						
				}
			}

			function saveDelete(){
				var row = $('#dg').datagrid('getSelected');	
				if (row){
					$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
						if(r){
							$.messager.progress({
								title:'Please waiting',
								msg:'removing data...'
							});

							$.post('del_buying_price.php',{
									item_no: row.ITEM_NO,
									supplier_code: row.SUPPLIER_CODE,
									rank: row.ALTER_PROCEDURE
							}).done(function(res){
							
								if(res.length == 4){
									$('#dg').datagrid('reload');
									$.messager.alert('INFORMATION','Delete Data Success..!!<br/>','info');
									$.messager.progress('close');
								}else{
									$.messager.alert('ERROR',res,'warning');
									$.messager.progress('close');
									
								}
							});




							// console.log('delete_bom.php?item_no='+row.UPPER_ITEM_NO+'&level_no='+row.LEVEL_NO)
							// $.post('del_buying_price.php',{
							// 		item_no: row.ITEM_NO,
							// 		supplier_code: row.SUPPLIER_CODE,
							// 		rank: row.ALTER_PROCEDURE},
							// function(result){
							// 	alert(result);
							// 	if (result.length == 4){
							// 		$('#dg').datagrid('reload');
							// 		$.messager.progress('close');
							// 	}else{
							// 		$.messager.show({
							// 			title: 'Error',
							// 			msg: result.errorMsg
							// 		});
							// 		$.messager.progress('close');
							// 	}
							// },'json');
						}
					});
				}else{
					$.messager.show({title: 'ITEM BUYING PRICE DELETED',msg:'Data Not select'});
				}
			}

			// function uploaddatabcsp() {
			// 	$('#uploaddatabcsp').form('submit',{
			// 		url: 'bc_uploadsp.php',
			// 		onSubmit: function(){
			// 			return $(this).form('validate');
			// 		},
			// 		success: function(result){
			// 			$.messager.alert('UPLOAD BC DATA : ',result,'info');
			// 			//alert(result);
			// 	 		$('#fileexcelbc').filebox('clear');
			// 			//$('#dg').datagrid('reload');
			// 			}
			// 	});
			// }

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

				

				$('#cmb_supplier').combobox('disable');
				$('#ck_supplier').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_supplier').combobox('disable');
					}else{
						$('#cmb_supplier').combobox('enable');
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
				$('#dg').datagrid( {
					url: 'get_buying_price.php',
					singleSelect: true,
				    fitColumns: true,
					rownumbers: true,
				    columns:[[
						{field:'ALTER_PROCEDURE',title:'RANK.', width:30, halign:'center'},
		                {field:'SUPPLIER_CODE',title:'SUPLIER<br>CODE',width:40,halign:'center'},
		                {field:'COMPANY',title:'SUPLIER',width:150,halign:'center'},
						{field:'LEAD',title:'PURCHASE<br>LEADTIME.',width:30,halign:'center'},
		                {field:'ITEM_NO',title:'ITEM NO.',width:30,halign:'center'},
		                {field:'DESCRIPTION',title:'ITEM',width:100,halign:'center'},   
						{field:'CURR_MARK',title:'CURRENCY',width:20,halign:'center', align:'center'},         
		                {field:'ESTIMATE_PRICE',title:'ESTIMATE<br>PRICE',width:40,halign:'center', align:'right'},
					]],
					
					// detailFormatter: function(rowIndex, rowData){
					// 	return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
					// },
					// onExpandRow: function(index,row){
					// 	var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
					// 	listbrg.datagrid({
		            //     	title: 'Goods Receive Detail (No: '+row.GR_NO+')',
					// 		url:'gr_get_detail.php?gr_no='+row.GR_NO,
					// 		toolbar: '#ddv'+index,
					// 		singleSelect:true,
					// 		loadMsg:'load data ...',
					// 		height:'auto',
					// 		fitColumns: true,
					// 		columns:[[
					// 			{field:'LINE_NO', title:'LINE NO.', halign:'center', align:'center', width:50},
					// 			{field:'PO_NO',title:'PO NO.', halign:'center', width:150, sortable: true},
					// 			{field:'PO_LINE_NO', title:'PO<br/>LINE NO.', halign:'center', align:'center', width:50},
				    //             {field:'ITEM_NO',title:'ITEM NO.', halign:'center', align:'center', width:80, sortable: true},
				    //             {field:'DESCRIPTION', title:'ITEM<br>DESCRIPTION', halign:'center', width:240},
				    //             {field:'UNIT', title:'UNIT', halign:'center', align:'center', width:50},
				    //             {field:'UOM_Q', title:'UoM', halign:'center', align:'center', width:50, hidden: true},
				    //             {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
				    //             {field:'U_PRICE', title:'PRICE ('+row.CURR_SHORT+')', halign:'center', align:'center', width:70},
				    //             {field:'AMT_L', title:'AMOUNT', halign:'center', align:'right', width:70},
					// 		]],
					// 		onResize:function(){
					// 			//alert(index);
					// 			$('#dg').datagrid('fixDetailRowHeight',index);
					// 		},
					// 		onLoadSuccess:function(){
					// 			setTimeout(function(){
					// 				$('#dg').datagrid('fixDetailRowHeight',index);
					// 			},0);
					// 		}
		            //     });
					// }
				});
				// document.getElementById('src').focus();
			})


			function saveAdd(){
				
				$('#dlg_add').dialog('open').dialog('setTitle','Create Buying Price');
				$('#cmb_item_add').combobox('setValue','');
				$('#txt_item_name_add').textbox('setValue','');
				$('#cmb_supplier_add').combobox('setValue','');
				$('#curr_add').combobox('setValue','');
				$('#price_add').numberbox('setValue','0');
				$('#lead_add').numberbox('setValue','0');
			}

			function filter(event){
				var src = document.getElementById('src').value;
				var search = src.toUpperCase();
				//document.getElementById('src').value = search;
				
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
					//document.getElementById('src').value = '';
			    }
			}

			// function sch_po_add(event){
			// 	var sch_a = document.getElementById('s_po_add').value;
			// 	var search = sch_a.toUpperCase();
			// 	document.getElementById('s_po_add').value = search;
				
			//     if(event.keyCode == 13 || event.which == 13){
			// 		search_po_add();
			//     }
			// }

			// function sch_po_edit(event){
			// 	var sch_e = document.getElementById('s_po_edit').value;
			// 	var search_e = sch_e.toUpperCase();
			// 	document.getElementById('s_po_edit').value = search_e;
				
			//     if(event.keyCode == 13 || event.which == 13){
			// 		search_po_edit();
			//     }
			// }

			var get_url='';
			var flag = 5;
			// function save_gr(){
			// 	if(flag == 5){
			// 		$.messager.alert('INFORMATION','Please filter data first','info');
			// 	}else{
			// 		$.messager.progress({
			// 			title:'Please waiting',
			// 			msg:'Save data...'
			// 	    });
			// 		var dataRows = [];
			// 		var t = $('#dg').datagrid('getRows');
			// 		var total = t.length;
			// 		var jmrow=0;
			// 		for(i=0;i<total;i++){
			// 			jmrow = i+1;
			// 			$('#dg').datagrid('endEdit',i);
			// 			if($('#dg').datagrid('getData').rows[i].EDIT == 1){
			// 				//alert($('#dg').datagrid('getData').rows[i].EDIT);
			// 				dataRows.push({
			// 				gr_no: $('#dg').datagrid('getData').rows[i].GR_NO,
			// 				inv_no: $('#dg').datagrid('getData').rows[i].INV_NO,
			// 				inv_date: $('#dg').datagrid('getData').rows[i].INV_DATE
			// 				});
			// 			}
						
						
			// 		}

			// 		var myJSON=JSON.stringify(dataRows);
			// 		var str_unescape=unescape(myJSON);
					
			// 		console.log(unescape(str_unescape));

			// 		$.post('gr_save_invoice.php',{
			// 			data: unescape(str_unescape)
			// 		}).done(function(res){
			// 			if(res.length = 8){
			// 				$('#dg').datagrid('loadData',[]);
			// 				$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
			// 				$.messager.progress('close');
			// 			}else{
			// 				$.messager.alert('ERROR',res,'warning');
			// 				$.messager.progress('close');
			// 			}
			// 		});
			// 	}

			// }

			function filterData(){
				var ck_supplier = "false";
				var ck_item = "false";
				

				
				if ($('#ck_supplier').attr("checked")) {
					ck_supplier = "true";
				};

				if ($('#ck_item').attr("checked")) {
					ck_item = "true";
				};

				
				$('#dg').datagrid('load', {
					cmb_supplier: $('#cmb_supplier').combobox('getValue'),
					ck_supplier: ck_supplier,
					cmb_item: $('#cmb_item').combobox('getValue'),
					ck_item: ck_item
				});
			}
				
		</script>
	</body>
    </html>