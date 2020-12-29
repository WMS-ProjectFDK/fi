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
   	<title>SALES PRICE</title>
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
		<?php include ('../../ico_logout.php'); ?>
		<div id="toolbar" style="padding:5px 5px;">
			<fieldset style="float:left;width:97%;height:auto;border-radius:4px;"><legend><span class="style3"><strong>Item Filter For sales Price</strong></span></legend>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">ITEM NO.</span>
					<select style="width:330px;" name="cmb_item" id="cmb_item" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
					onSelect:function(rec){
						//alert(rec.id_name_item);
						var spl = rec.id_name_item;
						var sp = spl.split(' - ');
						$('#txt_item_name').textbox('setValue', sp[1]);
					}"></select>
					<label><input type="checkbox" name="ck_item" id="ck_item" checked="true">All</input></label>
					<span style="width:100px;display:inline-block;"></span>
					<span style="width:110px;display:inline-block;">CUSTOMER</span>
					<select style="width:330px;" name="cmb_supplier" id="cmb_supplier" class="easyui-combobox" data-options=" url:'../json/json_customer.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_supplier" id="ck_supplier" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">ITEM NAME</span>
					<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
				</div>
			</fieldset> 
			<div style="clear:both;margin-bottom:3px;"></div>
			<div style="margin: 3px;">
				<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2"  onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> FILTER SALES PRICE</a>
	    		<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2"  onclick="saveAdd();"><i class="fa fa-save" aria-hidden="true"></i> ADD SALES PRICE</a>
	    		<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2"  onclick="saveEdit();"><i class="fa fa-edit" aria-hidden="true"></i> EDIT SALES PRICE</a>
	    		<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2"  onclick="saveDelete();"><i class="fa fa-trash" aria-hidden="true"></i> DELETE SALES PRICE</a>
				<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2"  onclick="Download_SP();"><i class="fa fa-download" aria-hidden="true"></i> DOWNLOAD SALES PRICE</a>
			</div>
		</div>

		<!-- ADD -->
		<div id='dlg_add' class="easyui-dialog" style="width:500px;height:250px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
		   <form id="ff" method="post" novalidate>	
			<div class="fitem">
					<span style="width:110px;display:inline-block;">Item No.</span>
						<select style="width:330px;" name="cmb_item_add" id="cmb_item_add" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
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
					<span style="width:110px;display:inline-block;">Customer</span>
					<select style="width:330px;" name="cmb_supplier_add" id="cmb_supplier_add" class="easyui-combobox" data-options=" url:'../json/json_customer.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
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
				</div>

				<div class="fitem">
					<span style="width:110px;display:inline-block;">Unit Price.</span>
					<input style="width:85px;" name="price_add" id="price_add" class="easyui-numberbox" data-options="min:0,precision:5" required=""/>
				</div>
			
					
			</div>
			<div id="dlg-buttons-add">
				<input class="easyui-linkbutton c6" iconCls="icon-ok" type="submit" value="Save" onclick="savesalesPrice()" style="width:90px; height: 30px;">
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
						<select style="width:330px;" name="cmb_item_edit" id="cmb_item_edit" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
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
					<span style="width:110px;display:inline-block;">Customer</span>
					<select style="width:330px;" name="cmb_supplier_edit" id="cmb_supplier_edit" class="easyui-combobox" data-options=" url:'../json/json_customer.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px',
					onSelect: function(rec){
						//alert(rec.company_code);
						$.ajax({
							type: 'GET',
							url: '../json/json_company_details.php?id='+rec.company_code,
							data: { kode:'kode' },
							success: function(data){
								$('#curr_edit').combobox('setValue',data[0].CURR_CODE);
								}
						})
					}" disabled="" ></select>
				</div>
				

				<div class="fitem">
					<span style="width:110px;display:inline-block;">Unit Price.</span>
					<input style="width:85px;" name="price_edit" id="price_edit" class="easyui-numberbox" required="" data-options="
			precision:5,
			formatter:function(value){
				var value = $.fn.numberbox.defaults.formatter.call(this,value);
				value = parseFloat(value);
				return value;
			}"/>
				</div>
			
				
					
			</div>
			<div id="dlg-buttons-edit">
				<input class="easyui-linkbutton c6" iconCls="icon-ok" type="submit" value="Save" onclick="saveEditsalesPrice()" style="width:90px; height: 30px;">
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
			</div>
		  </form>
		</div>
		<!-- END EDIT -->

		<table id="dg" title="SALES PRICE" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

		<script type="text/javascript">
			function Download_SP(){
				if (getUrl != ''){
					console.log('sales_price_download_proses.php'+getUrl);
					$.post('sales_price_download_proses.php'+getUrl,{}).done(function(res){
						download_excel();
					});
				}
			}

			function download_excel(){
				url_download = 'sales_price_download_xls.php';
				window.open(url_download);
			}

			function saveEditsalesPrice(){
				//alert($('#cmb_supplier_edit').combobox('getValue'));
				var angka = 0;
				if($('#cmb_item_edit').combobox('getValue') == ""){
					$.messager.alert('ERROR',"Please Fill Item Number",'warning');
					angka=angka+1;
				}
				if($('#cmb_supplier_edit').combobox('getValue') == ""){
					$.messager.alert('ERROR',"Please Fill Customer Number",'warning');
					angka=angka+1;
				}

				if($('#price_edit').numberbox('getValue') == ""){
					$.messager.alert('ERROR',"Please Fill Price ",'warning');
					angka=angka+1;
				}
				

				if(angka==0){
					$.post('put_sales_price.php',{
						item_no: $('#cmb_item_edit').combobox('getValue'),
						supplier_code: $('#cmb_supplier_edit').combobox('getValue'),
						estimate_price : $('#price_edit').numberbox('getValue'),
					}).done(function(res){
					
						if(res == '"success"'){
							$('#dlg_edit').dialog('close')
							$('#dg').datagrid('loadData',[]);
							$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
							
						}else{
							$.messager.alert('ERROR',res,'warning');
							
						}
					});

				}
				
			}


			function savesalesPrice(){
				var angka = 0;	
				var item = $('#cmb_item_add').combobox('getValue');
				if( item == ""){
					$.messager.alert('ERROR',"Please Fill Item Number",'warning');
					angka=angka+1;
				}
				if($('#cmb_supplier_add').combobox('getValue') == ""){
					$.messager.alert('ERROR',"Please Fill Customer Number",'warning');
					angka=angka+1;
				}

				if($('#curr_add').combobox('getValue') == ""){
					$.messager.alert('ERROR',"Please Fill Currency Number",'warning');
					angka=angka+1;
					
				}
				if($('#price_add').numberbox('getValue') == ""){
					$.messager.alert('ERROR',"Please Fill Price ",'warning');
					angka=angka+1;
				}

				if(angka==0){

			
				$.post('post_sales_price.php',{
						item_no: $('#cmb_item_add').combobox('getValue'),
						supplier_code: $('#cmb_supplier_add').combobox('getValue'),
						curr_code : $('#curr_add').combobox('getValue'),
						estimate_price : $('#price_add').numberbox('getValue')
					}).done(function(res){
						// alert($('#cmb_item_add').combobox('getValue'));
						// alert($('#cmb_supplier_add').combobox('getValue'));
						// alert($('#curr_add').combobox('getValue'));
						// alert($('#price_add').numberbox('getValue'));
						// alert($('#lead_add').numberbox('getValue'));
						$('#dlg_add').dialog('close')
						$('#dg').datagrid('loadData',[]);
						$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
						
						// if(res.length == 9){
						// 	$('#dlg_add').dialog('close')
						// 	$('#dg').datagrid('loadData',[]);
						// 	$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
							
						// }else{
						// 	$.messager.alert('ERROR',res,'warning');
							
						// }
					});
				}
			}
             
			function saveEdit(){
				var row = $('#dg').datagrid('getSelected');	
				if (row){
					$('#dlg_edit').dialog('open').dialog('setTitle','Edit sales Price');
					$('#cmb_item_edit').combobox('setValue',row.ITEM_NO);
					$('#txt_item_name_edit').textbox('setValue',row.ITEM_NO);
					$('#cmb_supplier_edit').combobox('setValue',row.CUSTOMER_CODE);
					$('#price_edit').numberbox('setValue',row.U_PRICE);
						
				}
			}

			function saveDelete(){
				var row = $('#dg').datagrid('getSelected');	
				if (row){
					$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
						if(r){
							// $.messager.progress({
							// 	title:'Please waiting',
							// 	msg:'removing data...'
							// });
							// // console.log('delete_bom.php?item_no='+row.UPPER_ITEM_NO+'&level_no='+row.LEVEL_NO)
							// $.post('del_sales_price.php',{
							// 		item_no: row.ITEM_NO,
							// 		supplier_code: row.CUSTOMER_CODE
							// 		}
							// 		,
							// function(result){
							// 		$('#dg').datagrid('reload');
							// 		$.messager.progress('close');
							// 	},'json');

							$.post('del_sales_price.php',{
									item_no: row.ITEM_NO,
									supplier_code: row.CUSTOMER_CODE
							}).done(function(res){
								$('#dg').datagrid('reload');
								$.messager.progress('close');
							});
							
								
						}
					});
				}else{
					$.messager.show({title: 'ITEM sales PRICE DELETED',msg:'Data Not select'});
				}
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
					url: 'get_sales_price.php',
					singleSelect: true,
				    fitColumns: true,
					rownumbers: true,
				    columns:[[
		                {field:'CUSTOMER_CODE',title:'CUSTOMER<br>CODE',width:40,halign:'center'},
		                {field:'COMPANY',title:'SUPLIER',width:150,halign:'center'},
		                {field:'ITEM_NO',title:'ITEM NO.',width:30,halign:'center'},
		                {field:'DESCRIPTION',title:'ITEM',width:100,halign:'center'},   
						{field:'CURR_MARK',title:'CURRENCY',width:20,halign:'center', align:'center'},         
		                {field:'U_PRICE',title:'UNIT<br>PRICE',width:40,halign:'center', align:'right'},
					]],
				});
			})


			function saveAdd(){
				$('#dlg_add').dialog('open').dialog('setTitle','Create sales Price');
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
			    }
			}

			var flag = 5;
			function save_gr(){
				if(flag == 5){
					$.messager.alert('INFORMATION','Please filter data first','info');
				}else{
					$.messager.progress({
						title:'Please waiting',
						msg:'Save data...'
				    });
					var dataRows = [];
					var t = $('#dg').datagrid('getRows');
					var total = t.length;
					var jmrow=0;
					for(i=0;i<total;i++){
						jmrow = i+1;
						$('#dg').datagrid('endEdit',i);
						if($('#dg').datagrid('getData').rows[i].EDIT == 1){
							//alert($('#dg').datagrid('getData').rows[i].EDIT);
							dataRows.push({
							gr_no: $('#dg').datagrid('getData').rows[i].GR_NO,
							inv_no: $('#dg').datagrid('getData').rows[i].INV_NO,
							inv_date: $('#dg').datagrid('getData').rows[i].INV_DATE
							});
						}
						
						
					}

					var myJSON=JSON.stringify(dataRows);
					var str_unescape=unescape(myJSON);
					
					console.log(unescape(str_unescape));

					$.post('gr_save_invoice.php',{
						data: unescape(str_unescape)
					}).done(function(res){
						if(res == '"success"'){
							$('#dg').datagrid('loadData',[]);
							$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
							$.messager.progress('close');
						}else{
							$.messager.alert('ERROR',res,'warning');
							$.messager.progress('close');
						}
					});
				}

			}

			var getUrl = '';

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

				getUrl = '?cmb_supplier='+$('#cmb_supplier').combobox('getValue')+
					'&ck_supplier='+ck_supplier+
					'&cmb_item='+$('#cmb_item').combobox('getValue')+
					'&ck_item='+ck_item;
			}
		</script>
	</body>
    </html>