<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];

$q= "select count(*) as jum from whinventory where this_month=(SELECT CONVERT(nvarchar(6), getdate(), 112))" ;
$data_q = sqlsrv_query($connect, strtoupper($q));
$dt_q = sqlsrv_fetch_object($data_q);
?>

<!DOCTYPE html>
   	<html>
   	<head>
   	<meta charset="UTF-8">
   	<title>Goods Receive</title>
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
			<fieldset style="float:left;width:540px;height:105px;border-radius:4px;"><legend><span class="style3"><strong>Goods Receive Filter</strong></span></legend>
				<div style="width:540px;float:left">
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">GR Date</span>
						<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
						to 
						<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
						<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
	    			</div>
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">GR No.</span>
	    				<select style="width:190px;" name="cmb_gr_no" id="cmb_gr_no" class="easyui-combobox" data-options=" url:'../json/json_grno.php', method:'get', valueField:'gr_no', textField:'gr_no', panelHeight:'75px'"></select>
						<label><input type="checkbox" name="ck_gr_no" id="ck_gr_no" checked="true">All</input></label>
	    			</div>
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">Supplier</span>
	    				<select style="width:370px;" name="cmb_supp" id="cmb_supp" class="easyui-combobox" data-options=" url:'../json/json_company.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px'"></select>
						<label><input type="checkbox" name="ck_supp" id="ck_supp" checked="true">All</input></label>
	    			</div>
	    		</div>
			</fieldset>
			<fieldset style="position:absolute;margin-left:565px;border-radius:4px;width: 500px;height:105px;"><legend><span class="style3"><strong>Item Filter</strong></span></legend>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">PO No.</span>
					<select style="width:190px;" name="cmb_po" id="cmb_po" class="easyui-combobox" data-options=" url:'../json/json_pono.php', method:'get', valueField:'po_no', textField:'po_no', panelHeight:'100px'"></select>
					<label><input type="checkbox" name="ck_po" id="ck_po" checked="true">All</input></label>
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
			<fieldset style="margin-left: 1090px;border-radius:4px;height: 105px;"><legend><span class="style3"><strong>Print Select</strong></span></legend>
				<div class="fitem" align="center">
					<a href="javascript:void(0)" style="width: 150px;" id="print" class="easyui-linkbutton c2" disabled="true" onclick="print_bc_no()"><i class="fa fa-print" aria-hidden="true"></i> Print BC NO. View</a>
				</div>
				<div class="fitem" align="center">
					<a href="javascript:void(0)" style="width: 150px;" id="print" class="easyui-linkbutton c2"  onclick="print_bc_no_sp()"><i class="fa fa-print" aria-hidden="true"></i> Spart BC NO. View</a>
				</div>
				<div class="fitem" align="center">
					<a href="javascript:void(0)" style="width: 150px;" id="print" class="easyui-linkbutton c2"  onclick="download_report()"><i class="fa fa-print" aria-hidden="true"></i> Download Report</a>
				</div>
			</fieldset>
			<div style="padding:5px 6px;">
		    	<span style="width:50px;display:inline-block;">search</span>
				<input style="width:150px; height: 18px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src"type="text" placeholder="Goods Receive No."/>
	    		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2"  onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
	    		<?php 
	    		if ($dt_q->JUM <= 0){ ?>
	    		<a href="javascript:void(0)" style="width: 150px;" id="add" class="easyui-linkbutton c2" disabled="true" onclick="add_gr()"><i class="fa fa-plus" aria-hidden="true"></i> Add Goods Receive</a>
	    		<a href="javascript:void(0)" style="width: 150px;" id="edit" class="easyui-linkbutton c2" disabled="true" onclick="edit_gr()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Goods Receive</a>
	    		<a href="javascript:void(0)" style="width: 170px;" id="delete" class="easyui-linkbutton c2" disabled="true" onclick="delete_gr()"><i class="fa fa-trash" aria-hidden="true"></i> Remove Goods Receive</a
	    		>
	    		<?php }else{ ?>
	    		<a href="javascript:void(0)" style="width: 150px;" id="add" class="easyui-linkbutton c2" onclick="add_gr()"><i class="fa fa-plus" aria-hidden="true"></i> Add Goods Receive</a>
	    		<a href="javascript:void(0)" style="width: 150px;" id="edit" class="easyui-linkbutton c2" onclick="edit_gr()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Goods Receive</a>
	    		<a href="javascript:void(0)" style="width: 170px;" id="delete" class="easyui-linkbutton c2" onclick="delete_gr()"><i class="fa fa-trash" aria-hidden="true"></i> Remove Goods Receive</a>
	    		<?php } ?>
	    		<a href="javascript:void(0)" style="width: 150px;" id="print" class="easyui-linkbutton c2" onclick="print_gr()"><i class="fa fa-print" aria-hidden="true"></i> Print Goods Receive</a>
	    		<a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="upload_bc_show()"><i class="fa fa-upload" aria-hidden="true"></i> Upload Data BC</a>
				<a href="javascript:void(0)" style="width: 170px;" class="easyui-linkbutton c2" onclick="upload_bc_show_sp()"><i class="fa fa-upload" aria-hidden="true"></i> Upload Data BC Sparts</a>
	    	</div></div>
		</div>

		<!-- ADD GR -->
		<!-- 

		<div class="easyui-dialog" title="Fluid Dialog" style="width:80%;height:200px;max-width:800px;padding:10px" data-options="
	            iconCls:'icon-save',
	            onResize:function(){
	                $(this).dialog('center');
	            }">
	        <p>width: 80%; height: 200px</p>
	    </div> -->
		<div id="dlg_add" class="easyui-dialog" style="width:1100px;height:420px;padding:5px 5px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true, position: 'center'">
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:500px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Supplier</span>
					<input required="true" style="width:100px;" name="supp_no_add" id="supp_no_add" class="easyui-textbox" disabled="disabled" data-options="" />
					<select style="width:300px;" name="cmb_supp_add" id="cmb_supp_add" class="easyui-combobox" data-options=" url:'../json/json_company.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
					onSelect:function(rec){
						$('#supp_no_add').textbox('setValue', rec.company_code);	
					}
					" required="">
					</select>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Receive Date</span>
					<input style="width:100px;" name="gr_date_add" id="gr_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
					<span style="width:80px;display:inline-block;">Receive No.</span>
					<input required="true" style="width:215px;" name="gr_no_add" id="gr_no_add" class="easyui-textbox"/>
				</div>
			</fieldset>
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:530px;margin-left: 510px;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Remarks</span>
				</div>
				<div class="fitem">
					<input style="width:525px;height: 30px;" name="gr_remark_add" id="gr_remark_add" class="easyui-textbox" data-options="multiline:true"/>
				</div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:250px;border-radius: 10px;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
			<div id="toolbar_add" style="padding: 5px 5px;">
				<a href="#" iconCls='icon-add' class="easyui-linkbutton" onclick="add_po_add()">Add PO</a>
	    		<a href="#" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_po_add()">Remove PO</a>
			</div>

			<div id="dlg_addPO" class="easyui-dialog" style="width: 950px;height: 270px;" closed="true" buttons="#dlg-buttons_addPO" data-options="modal:true">
				<table id="dg_addPO" class="easyui-datagrid" toolbar="#toolbar_addPO" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
			</div>
			<div id="toolbar_addPO" style="padding:3px 3px;">
				<span style="width:80px;display:inline-block;">Search By</span>
				<select style="width:85px;" name="cmb_search" id="cmb_search" class="easyui-combobox" data-options="panelHeight:'70px'">
					<option value="PO_NO" selected="">PO NO</option>
					<option value="ITEM_NO">ITEM NO</option>
				</select>
				<input style="width:200px;height: 20px;border-radius: 4px;" name="s_po_add" id="s_po_add" onkeypress="sch_po_add(event)"/>
				<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_po_add()">SEARCH</a>
			</div>

		</div>
		<div id="dlg-buttons-add">
			<a href="javascript:void(0)" id="save_add" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveAdd()" style="width:90px">Save</a>
			<a href="javascript:void(0)" id="cancel_add" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
		</div>
		<!-- END ADD GR -->

		<!-- EDIT GR -->
		<div id="dlg_edit" class="easyui-dialog" style="width:1100px;height:420px;padding:5px 5px" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true, position: 'center'">
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:500px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Supplier</span>
					<input required="true" style="width:100px;" name="supp_no_edit" id="supp_no_edit" class="easyui-textbox" disabled="disabled" data-options="" />
					<select style="width:300px;" name="cmb_supp_edit" id="cmb_supp_edit" class="easyui-combobox" data-options=" url:'../json/json_company.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
					onSelect:function(rec){
						$('#supp_no_edit').textbox('setValue', rec.company_code);	
					}
					" required="" disabled="">
					</select>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Receive Date</span>
					<input style="width:100px;" name="gr_date_edit" id="gr_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
					<span style="width:80px;display:inline-block;">Receive No.</span>
					<input required="true" style="width:215px;" name="gr_no_edit" id="gr_no_edit" class="easyui-textbox" disabled=""/>
				</div>
			</fieldset>
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:530px;margin-left: 510px;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Remarks</span>
				</div>
				<div class="fitem">
					<input style="width:525px;height: 30px;" name="gr_remark_edit" id="gr_remark_edit" class="easyui-textbox" data-options="multiline:true"/>
				</div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:100%;height:250px;border-radius: 10px;" rownumbers="true" singleSelect="true" fitColumns= "true">
				
			</table>
			<div id="toolbar_edit" style="padding: 5px 5px;">
				<a href="#" iconCls='icon-add' class="easyui-linkbutton" onclick="add_po_edit()">Add PO</a>
	    		<a href="#" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_po_edit()">Remove PO</a>
			</div>

			<div id="dlg_editPO" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons_editPO" data-options="modal:true">
				<table id="dg_editPO" class="easyui-datagrid" toolbar="#toolbar_editPO" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
			</div>
			<div id="toolbar_editPO" style="padding:3px 3px;">
				<span style="width:80px;display:inline-block;">Search By</span>
				<select style="width:85px;" name="cmb_search_e" id="cmb_search_e" class="easyui-combobox" data-options="panelHeight:'70px'">
					<option value="PO_NO" selected="">PO NO</option>
					<option value="ITEM_NO">ITEM NO</option>
				</select>
				<input style="width:200px;height: 20px;border-radius: 4px;" name="s_po_edit" id="s_po_edit" onkeypress="sch_po_edit(event)"/>
				<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_po_edit()">SEARCH</a>
			</div>
		</div>
		<div id="dlg-buttons-edit">
			<a href="javascript:void(0)" id="save_edit" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveEdit()" style="width:90px">Save</a> <!-- disabled="true"  -->
			<a href="javascript:void(0)" id="cancel_edit" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
		</div>
		<!-- END EDIT GR -->

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

		<table id="dg" title="Goods Receive & Invoice Report" class="easyui-datagrid" toolbar="#toolbar	" style="width:auto;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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

			//function progress(){
			//	$('#progress').dialog('open').dialog('setTitle', 'Please Wait...');
			//    $('#p').progressbar({value: 0});
			//	setInterval(function(){
			//		var value = $('#p').progressbar('getValue');
			//		//myTimer()
			//		if (value < 100){
			//		    value += Math.floor(Math.random() * 25);
			//		    $('#p').progressbar('setValue', value);
			//		    filterData();
			//		}
			//		if (value >=100) {
			//			$('#progress').dialog('close');
			//		};
			//	},800);
			//}

			var pdf_url='';

			$(function(){
				access_log();
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

				$('#cmb_gr_no').combobox('disable');
				$('#ck_gr_no').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_gr_no').combobox('disable');
					}else{
						$('#cmb_gr_no').combobox('enable');
					}
				});

				$('#cmb_supp').combobox('disable');
				$('#ck_supp').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_supp').combobox('disable');
					}else{
						$('#cmb_supp').combobox('enable');
					}
				});

				$('#cmb_po').combobox('disable');
				$('#ck_po').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_po').combobox('disable');
					}else{
						$('#cmb_po').combobox('enable');
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
					url: 'gr_get.php',
					view: detailview,
				    columns:[[
					    {field:'GR_NO',title:'GOODS RECEIVE<br>NO.', width:80, halign:'center'},
		                {field:'GR_DATE',title:'GOODS RECEIVE<br>DATE',width:80,halign:'center', align:'center'},
		                {field:'SUPPLIER_CODE',title:'SUPLIER<br>CODE',width:60,halign:'center', hidden: true},
		                {field:'COMPANY',title:'SUPLIER',width:180,halign:'center'},
		                {field:'CURR_CODE',title:'CURRENCY<br>CODE',width:40,halign:'center', hidden: true},
		                {field:'CURR_SHORT',title:'CURRENCY',width:50,halign:'center', align:'center'},
		                {field:'EX_RATE',title:'RATE',width:50,halign:'center', align:'center'},
		                {field:'AMT_O',title:'AMOUNT (O)',width:80,halign:'center', align:'right'},
						{field:'AMT_L',title:'AMOUNT (L)',width:80,halign:'center', align:'right'},
		                {field:'REMARK',title:'REMARK',width:60,halign:'center'},
		                {field:'PAYTERMS',title:'Payment<br>Terms',width:150,halign:'center'},
		                {field:'BC_DOC',title:'BC Doc',width:80,halign:'center'},
		                {field:'BC_NO',title:'BC No',width:80,halign:'center'}
					]],
					detailFormatter: function(rowIndex, rowData){
						return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
					},
					onExpandRow: function(index,row){
						var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
						listbrg.datagrid({
		                	title: 'Goods Receive Detail (No: '+row.GR_NO+')',
							url:'gr_get_detail.php?gr_no='+row.GR_NO,
							toolbar: '#ddv'+index,
							singleSelect:true,
							loadMsg:'load data ...',
							height:'auto',
							fitColumns: true,
							columns:[[
								{field:'LINE_NO', title:'LINE NO.', halign:'center', align:'center', width:50},
								{field:'PO_NO',title:'PO NO.', halign:'center', width:150, sortable: true},
								{field:'PO_LINE_NO', title:'PO<br/>LINE NO.', halign:'center', align:'center', width:50},
				                {field:'ITEM_NO',title:'ITEM NO.', halign:'center', align:'center', width:80, sortable: true},
				                {field:'DESCRIPTION', title:'ITEM<br>DESCRIPTION', halign:'center', width:240},
				                {field:'UNIT', title:'UNIT', halign:'center', align:'center', width:50},
				                {field:'UOM_Q', title:'UoM', halign:'center', align:'center', width:50, hidden: true},
				                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
				                {field:'U_PRICE', title:'PRICE ('+row.CURR_SHORT+')', halign:'center', align:'center', width:70},
				                {field:'AMT_O', title:'AMOUNT (ORG)', halign:'center', align:'right', width:70},
							]],
							onResize:function(){
								//alert(index);
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
				document.getElementById('src').focus();
			})

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
					//document.getElementById('src').value = '';
			    }
			}

			function sch_po_add(event){
				var sch_a = document.getElementById('s_po_add').value;
				var search = sch_a.toUpperCase();
				document.getElementById('s_po_add').value = search;
				
			    if(event.keyCode == 13 || event.which == 13){
					search_po_add();
			    }
			}

			function sch_po_edit(event){
				var sch_e = document.getElementById('s_po_edit').value;
				var search_e = sch_e.toUpperCase();
				document.getElementById('s_po_edit').value = search_e;
				
			    if(event.keyCode == 13 || event.which == 13){
					search_po_edit();
			    }
			}

			var get_url='';

			function filterData(){
				var ck_date = "false";
				var ck_gr_no = "false";
				var ck_supp = "false";
				var ck_po = "false";
				var ck_item = "false";
				var flag = 0;

				if ($('#ck_date').attr("checked")) {
					ck_date = "true";
					flag += 1;
				}

				if ($('#ck_gr_no').attr("checked")) {
					ck_gr_no = "true";
					flag += 1;
				};

				if ($('#ck_supp').attr("checked")) {
					ck_supp = "true";
					flag += 1;
				};

				if ($('#ck_po').attr("checked")) {
					ck_po = "true";
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
					cmb_gr_no: $('#cmb_gr_no').combobox('getValue'),
					ck_gr_no: ck_gr_no,
					cmb_supp: $('#cmb_supp').combobox('getValue'),
					ck_supp: ck_supp,
					cmb_po: $('#cmb_po').combobox('getValue'),
					ck_po: ck_po,
					cmb_item: $('#cmb_item').combobox('getValue'),
					ck_item: ck_item,
					src: ''
				});

				get_url = "?date_awal="+$('#date_awal').datebox('getValue')+
						  "&date_akhir="+$('#date_akhir').datebox('getValue')+
						  "&ck_date="+ck_date+
						  "&cmb_gr_no="+$('#cmb_gr_no').combobox('getValue')+
						  "&ck_gr_no="+ck_gr_no+
						  "&cmb_supp="+$('#cmb_supp').combobox('getValue')+
						  "&nm_supp="+$('#cmb_supp').combobox('getText')+
						  "&ck_supp="+ck_supp+
						  "&cmb_po="+$('#cmb_po').combobox('getValue')+
						  "&ck_po="+ck_po+
						  "&cmb_item="+$('#cmb_item').combobox('getValue')+
						  "&ck_item="+ck_item;

				$('#dg').datagrid('enableFilter');
			}

			function download_report(){
				if (get_url != ''){
					console.log('gr_download_proses.php'+get_url);
					$.post('gr_download_proses.php'+get_url,{}).done(function(res){
						// console.log(res);

						// if(res == '"success"'){
							download_excel();
						// }else{
						// 	$.messager.alert('ERROR',res,'warning');
						// }


						// if (res == '"success"'){
						// 	$.messager.confirm('Confirm','Do you want to download file to excel?',function(r){
						// 		if(r){
						// 			download_excel();
						// 		}
						// 	})
						// }else{
						// 	$.messager.show({
						// 		title: 'Error',
						// 		msg: res
						// 	});
						// }
					});
				}
				// else{
				// 	$.messager.show({title: 'Goods Receive',msg: 'Data Not filter'});
				// }
			}

			function download_excel(){
				// console.log('gr_download_xls.php');//+get_url);
				url_download = 'gr_download_xls.php';//+get_url;
				window.open(url_download);
			}

			function add_gr(){
				$('#dlg_add').dialog('open').dialog('setTitle','Add Goods Receive');
				$('#save_add').linkbutton('enable');
				$('#cancel_add').linkbutton('enable');
				$('#supp_no_add').textbox('setValue','');
				$('#cmb_supp_add').combobox('setValue','');
				$('#gr_no_add').textbox('setValue','');
				$('#gr_remark_add').textbox('setValue','');
				$('#dg_add').datagrid('loadData',[]);

				$('#dg_add').datagrid({
				    singleSelect: true,
					rownumbers: true,
				    columns:[[
						{field:'PO_NO', title:'PO NO.', width:100, halign: 'center'},
						{field:'LINE_NO', title: 'PO<br/>LINE NO.', width:50, halign: 'center', align: 'center'},
						{field:'PO_DATE', hidden: true},
					    {field:'ITEM_NO', title:'ITEM NO.', width:80, halign: 'center'},
					    {field:'ITEM', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
					    {field:'DESCRIPTION', title:'DESCRIPTION', halign: 'center', width:250},
					    {field:'STOCK_SUBJECT_CODE', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
					    {field:'COST_PROCESS_CODE', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
					    {field:'COST_SUBJECT_CODE', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
					    {field:'STANDARD_PRICE', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
					    {field:'SUPPLIERS_PRICE', title:'ITEM NAME', width:80, halign: 'center', hidden: true},
					    {field:'UNIT', title:'UoM', halign: 'center', width:50, align:'center'},
					    {field:'CURR_SHORT', title:'CURRENCY', halign: 'center', width:70, align:'center'},
					    {field:'ETA', title:'E T A', halign: 'center', width:80, align:'center'},
					    {field:'QTY_2', title:'PO QTY', halign: 'center',width:80, align:'right'},
					    {field:'QTY', title:'PO QTY', halign: 'center',width:80, align:'right', editor:{
					    																			type:'numberbox',
					    																			options:{precision:2,groupSeparator:','},
					    																			disabled:true
					    																		}
					    ,hidden: true},
					    {field:'GR_QTY_2', title:'GOOD REC.<br>QTY', halign: 'center',width:80, align:'right'},
					    {field:'GR_QTY', title:'GOOD REC.<br>QTY', halign: 'center',width:80, align:'right', editor:{
					    																						type:'numberbox',
					    																						options:{precision:2,groupSeparator:','}
					    																					}
					    ,hidden: true},
					    {field:'BAL_QTY', title:'BALANCE', halign: 'center',width:80, align:'right'},
					    {field:'ACT_QTY', title:'Actual<br>QTY', align:'right', halign: 'center', width:100, editor:{
					    																						type:'numberbox',
					    																						options:{precision:2,groupSeparator:','}
					    																					}
					    },
					    {field:'ORIGIN_CODE', title: 'ORIGIN CODE', width:30, hidden: true},
					    {field:'UOM_Q', title: 'UOM_Q', width:30, hidden: true},
					    {field:'U_PRICE', title: 'PRICE', width:30, hidden: true},
					    {field:'CURR_CODE', title: 'CURR CODE', width:30, hidden: true},
					    {field:'EX_RATE', title: 'EXCHANGE<br>RATE', width:30, hidden: true},
					    {field:'PDAYS', title: 'PDAYS', width:30, hidden: true}, 
					    {field:'PDESC', title: 'PDESC', width:30, hidden: true},
					    {field:'SLIP_TYPE', title: 'SLIP_TYPE', width:30, hidden: true},
					    {field:'STS_WH', title: 'STS_WH', width:70, hidden: true}
				    ]],
				    onClickRow:function(rowIndex){
				    	$(this).datagrid('beginEdit', rowIndex);
				    },
				    onBeginEdit:function(rowIndex){
				        var editors = $('#dg_add').datagrid('getEditors', rowIndex);
				        var n1 = $(editors[0].target);
				        var n2 = $(editors[1].target);
				        var n3 = $(editors[2].target);
				        n1.add(n3).numberbox({
				            onChange:function(){
				                var amt = n1.numberbox('getValue') - n2.numberbox('getValue');
				                if(n3.numberbox('getValue') > amt){
									$.messager.confirm('Confirm','actual value over',function(r){
										if(r){
											n3.numberbox('setValue',0);
										}else{
											n3.numberbox('setValue',$(editors[2].target));
										}		
									});
				                }
				            }
				        })
				    }
				});
			}

			function add_po_add(){
				var supp_id = $('#supp_no_add').textbox('getValue');
				var supp_name = $('#cmb_supp_add').textbox('getText');

				if (supp_name==''){
					$.messager.alert('Warning','Please select supplier','warning');
				}else{
					$('#dlg_addPO').dialog('open').dialog('setTitle','Search PO & Item ('+supp_id+' - '+supp_name+')');
					$('#dg_addPO').datagrid('loadData',[]);
					$('#dg_addPO').datagrid('load',{supp: '', po: '', by: ''});
					$('#dg_addPO').datagrid({
						columns:[[
							{field:'PO_NO',title:'PO NO.', width:100, halign:'center'},
			                {field:'LINE_NO',title:'LINE',width:40,halign:'center', align:'center'},
			                {field:'ETA',title:'E T A',width:80,halign:'center', align:'center'},
			                {field:'CURR_SHORT',title:'CURRENCY',width:70,halign:'center', align:'center'},
			                {field:'ITEM_NO',title:'ITEM',width:80,halign:'center', align:'center'},
			                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
			                {field:'QTY',title:'QTY',width:100,halign:'center', align:'right'},
			                {field:'GR_QTY',title:'GOODS REC.<br>QTY',width:100,halign:'center', align:'right'},
			                {field:'BAL_QTY',title:'BALANCE',width:100,halign:'center', align:'right'},
			                {field:'COUNTRY',title:'ORIGIN',width:100,halign:'center'},
			                {field:'ORIGIN_CODE',hidden: true},
			                {field:'UOM_Q',hidden: true},
			                {field:'U_PRICE',hidden: true},
			                {field:'UNIT',hidden: true},
			                {field:'CURR_CODE',hidden: true},
			                {field:'EX_RATE',hidden: true},
			                {field:'PDAYS',hidden: true},
			                {field:'PDESC',hidden: true},
			                {field:'SLIP_TYPE',hidden: true},
			                {field:'ITEM',hidden: true},
			                {field:'STOCK_SUBJECT_CODE', hidden: true},
			                {field:'CLASS_CODE', hidden: true},
			                {field:'COUNTRY_CODE', hidden: true},
							{field:'COST_PROCESS_CODE', hidden: true},
							{field:'COST_SUBJECT_CODE', hidden: true},
							{field:'STANDARD_PRICE', hidden: true},
							{field:'SUPPLIERS_PRICE', hidden: true},
							{field:'PO_DATE', hidden: true},
							{field:'STS_WH', hidden: true}
			            ]],
			            onDblClickRow:function(id,row){
							var t = $('#dg_add').datagrid('getRows');
							var total = t.length;
						   	var idxfield=0;
						   	var i = 0;
						   	var count = 0;
							if (parseInt(total) == 0) {
								idxfield=total;
							}else{
								idxfield=total+1;
								for (i=0; i < total; i++) {
									//alert(i);
									var po = $('#dg_add').datagrid('getData').rows[i].PO_NO;
									var item = $('#dg_add').datagrid('getData').rows[i].ITEM_NO;
									var line = $('#dg_add').datagrid('getData').rows[i].LINE_NO;
									//alert(item);
									if (po==row.PO_NO && item == row.ITEM_NO && line == row.LINE_NO) {
										count++;
									};
								};
							}

							//alert('count = '+count);
							if (count>0) {
								$.messager.alert('Warning','PO present','warning');
							}else{
								$('#dg_add').datagrid('insertRow',{
									index: idxfield,	// index start with 0
									row: {
										PO_NO: row.PO_NO,
										PO_DATE: row.PO_DATE,
										ITEM_NO: row.ITEM_NO,
										DESCRIPTION: row.DESCRIPTION,
										UNIT: row.UNIT,
										UoM: row.UOM_Q,
										CURR_SHORT: row.CURR_SHORT,
										QTY: row.QTY,
										QTY_2: row.QTY_2,
										GR_QTY: row.GR_QTY,
										GR_QTY_2: row.GR_QTY_2,
										BAL_QTY: row.BAL_QTY,
										/*ACT_QTY: 0,*/
										LINE_NO: row.LINE_NO,
										ORIGIN_CODE: row.ORIGIN_CODE,
										UOM_Q: row.UOM_Q,
										U_PRICE: row.U_PRICE,
										CURR_CODE: row.CURR_CODE,
										EX_RATE: row.EX_RATE,
										PDAYS: row.PDAYS,
										PDESC: row.PDESC,
										SLIP_TYPE: row.SLIP_TYPE,
										ITEM: row.ITEM,
										STOCK_SUBJECT_CODE: row.STOCK_SUBJECT_CODE,
										CLASS_CODE: row.CLASS_CODE,
										COUNTRY_CODE: row.COUNTRY_CODE,
										COST_PROCESS_CODE: row.COST_PROCESS_CODE,
										COST_SUBJECT_CODE: row.COST_SUBJECT_CODE,
										STANDARD_PRICE: row.STANDARD_PRICE,
										SUPPLIERS_PRICE: row.SUPPLIERS_PRICE,
										ETA: row.ETA,
										STS_WH: row.STS_WH
									}
								});
							}
						}
					});

				}
			}

			function search_po_add(){
				var supp_id = $('#supp_no_add').textbox('getValue');
				var s_by = $('#cmb_search').combobox('getValue');
				var po = document.getElementById('s_po_add').value;

				if(po != ''){
					$('#dg_addPO').datagrid('load',{supp: supp_id, po: po, by: s_by});
					$('#dg_addPO').datagrid({url: 'gr_getpo.php',});
					document.getElementById('s_po_add').value = '';
					var dg = $('#dg_addPO').datagrid();
					dg.datagrid('enableFilter');
				}
			}

			function simpan(){
				var dataRows = [];
				if($('#supp_no_add').textbox('getValue')==''){
					$.messager.alert('Warning','Please select supplier','warning');
				}else if($('#gr_no_add').textbox('getValue')==''){
					$.messager.alert('INFORMATION','Receive No. Not Found','info');
				}else{
					var tot_amt_o = 0;
					var	tot_amt_l = 0;
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;
					var jmrow=0;
					for(i=0;i<total;i++){
						var amt_o = 0;
						var amt_l = 0;
						jmrow = i+1;
						$('#dg_add').datagrid('endEdit',i);

						dataRows.push({
							gr_sts: 'DETAILS',
							gr_no: $('#gr_no_add').textbox('getValue'),
							gr_line: jmrow,
							gr_date: $('#gr_date_add').datebox('getValue'),
							gr_supp: $('#supp_no_add').textbox('getValue'),
							gr_supp_name: $('#cmb_supp_add').textbox('getText'),
							gr_remark: $('#gr_remark_add').textbox('getValue'),
							gr_sts_wh: $('#dg_add').datagrid('getData').rows[i].STS_WH, 
							gr_curr: $('#dg_add').datagrid('getData').rows[i].CURR_CODE,
							gr_rate: $('#dg_add').datagrid('getData').rows[i].EX_RATE,
							gr_pday: $('#dg_add').datagrid('getData').rows[i].PDAYS,
							gr_pdes: $('#dg_add').datagrid('getData').rows[i].PDESC,
							gr_amto: parseFloat($('#dg_add').datagrid('getData').rows[i].ACT_QTY.replace(/,/g,'') * $('#dg_add').datagrid('getData').rows[i].U_PRICE).toFixed(2),
							gr_amtl: parseFloat($('#dg_add').datagrid('getData').rows[i].ACT_QTY.replace(/,/g,'') * $('#dg_add').datagrid('getData').rows[i].U_PRICE * 
												$('#dg_add').datagrid('getData').rows[i].EX_RATE).toFixed(2),
							gr_slip: $('#dg_add').datagrid('getData').rows[i].SLIP_TYPE,
							gr_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
							gr_item_name: $('#dg_add').datagrid('getData').rows[i].ITEM,
							gr_desc: $('#dg_add').datagrid('getData').rows[i].DESCRIPTION,
							gr_stock_subject_code: $('#dg_add').datagrid('getData').rows[i].STOCK_SUBJECT_CODE,
							gr_class_code: $('#dg_add').datagrid('getData').rows[i].CLASS_CODE,
							gr_country_code: $('#dg_add').datagrid('getData').rows[i].COUNTRY_CODE,
							gr_cost_process_code: $('#dg_add').datagrid('getData').rows[i].COST_PROCESS_CODE,
							gr_cost_subject_code: $('#dg_add').datagrid('getData').rows[i].COST_SUBJECT_CODE,
							gr_standard_price: $('#dg_add').datagrid('getData').rows[i].STANDARD_PRICE,
							gr_suppliers_price: $('#dg_add').datagrid('getData').rows[i].SUPPLIERS_PRICE,
							gr_orig: $('#dg_add').datagrid('getData').rows[i].ORIGIN_CODE,
							gr_pono: $('#dg_add').datagrid('getData').rows[i].PO_NO,
							gr_po_line: $('#dg_add').datagrid('getData').rows[i].LINE_NO,
							gr_qty: $('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,''),
							gr_uomq: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
							gr_price: $('#dg_add').datagrid('getData').rows[i].U_PRICE,
							gr_qty_act: $('#dg_add').datagrid('getData').rows[i].ACT_QTY.replace(/,/g,''),
							gr_po_date: $('#dg_add').datagrid('getData').rows[i].PO_DATE
						});

						amt_o = parseFloat($('#dg_add').datagrid('getData').rows[i].ACT_QTY * $('#dg_add').datagrid('getData').rows[i].U_PRICE).toFixed(2);
						amt_l = parseFloat($('#dg_add').datagrid('getData').rows[i].ACT_QTY * $('#dg_add').datagrid('getData').rows[i].U_PRICE * 
								$('#dg_add').datagrid('getData').rows[i].EX_RATE).toFixed(2);

						tot_amt_o += parseFloat(amt_o);
						tot_amt_l += parseFloat(amt_l);

						if(i==total-1){
							dataRows.push({
								gr_sts: 'HEADER',
								gr_no: $('#gr_no_add').textbox('getValue'),
								gr_line: jmrow,
								gr_date: $('#gr_date_add').datebox('getValue'),
								gr_supp: $('#supp_no_add').textbox('getValue'),
								gr_supp_name: $('#cmb_supp_add').textbox('getText'),
								gr_remark: $('#gr_remark_add').textbox('getValue'),
								gr_sts_wh: $('#dg_add').datagrid('getData').rows[i].STS_WH, 
								gr_curr: $('#dg_add').datagrid('getData').rows[i].CURR_CODE,
								gr_rate: $('#dg_add').datagrid('getData').rows[i].EX_RATE,
								gr_pday: $('#dg_add').datagrid('getData').rows[i].PDAYS,
								gr_pdes: $('#dg_add').datagrid('getData').rows[i].PDESC,
								gr_amto: parseFloat(tot_amt_o).toFixed(2),
								gr_amtl: parseFloat(tot_amt_l).toFixed(2),
								gr_slip: $('#dg_add').datagrid('getData').rows[i].SLIP_TYPE,
								gr_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
								gr_item_name: $('#dg_add').datagrid('getData').rows[i].ITEM,
								gr_desc: $('#dg_add').datagrid('getData').rows[i].DESCRIPTION,
								gr_stock_subject_code: $('#dg_add').datagrid('getData').rows[i].STOCK_SUBJECT_CODE,
								gr_class_code: $('#dg_add').datagrid('getData').rows[i].CLASS_CODE,
								gr_country_code: $('#dg_add').datagrid('getData').rows[i].COUNTRY_CODE,
								gr_cost_process_code: $('#dg_add').datagrid('getData').rows[i].COST_PROCESS_CODE,
								gr_cost_subject_code: $('#dg_add').datagrid('getData').rows[i].COST_SUBJECT_CODE,
								gr_standard_price: $('#dg_add').datagrid('getData').rows[i].STANDARD_PRICE,
								gr_suppliers_price: $('#dg_add').datagrid('getData').rows[i].SUPPLIERS_PRICE,
								gr_orig: $('#dg_add').datagrid('getData').rows[i].ORIGIN_CODE,
								gr_pono: $('#dg_add').datagrid('getData').rows[i].PO_NO,
								gr_po_line: $('#dg_add').datagrid('getData').rows[i].LINE_NO,
								gr_qty: $('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,''),
								gr_uomq: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
								gr_price: $('#dg_add').datagrid('getData').rows[i].U_PRICE,
								gr_qty_act: $('#dg_add').datagrid('getData').rows[i].ACT_QTY.replace(/,/g,''),
								gr_po_date: $('#dg_add').datagrid('getData').rows[i].PO_DATE
							});
						}
					}

					var myJSON=JSON.stringify(dataRows);
					var str_unescape=unescape(myJSON);
					$.post('gr_save.php',{
						data: unescape(str_unescape)
					}).done(function(res){
						if(res == '"success"'){
							$('#dlg_add').dialog('close');
							$('#dg').datagrid('reload');
							$.messager.alert('INFORMATION','Insert Data Success..!!<br/>GR No. : '+$('#gr_no_add').textbox('getValue'),'info');
							$.messager.progress('close');
						}else{
							//$.post('gr_destroy.php',{gr_no: $('#gr_no_add').textbox('getValue')},'json');
							$.messager.alert('ERROR',res,'warning');
							$.messager.progress('close');
						}
					});
				}
			}

			function saveAdd(){
				// $.messager.progress({
				//     title:'Please waiting',
				//     msg:'Save data...'
				// });

				$('#save_add').linkbutton('disable');
				$('#cancel_add').linkbutton('disable');
				var url='';
				var grno = $('#gr_no_add').textbox('getValue');
				var grdt = $('#gr_date_add').datebox('getValue');
				
				$.ajax({
					type: 'GET',
					url: '../json/json_kode_gr.php?gr='+grno+'&gr_date='+grdt,
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

			function remove_po_add(){
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

			function edit_gr(){
				var row = $('#dg').datagrid('getSelected');
	            if (row){
	            	$('#dlg_edit').dialog('open').dialog('setTitle','Edit Goods Receive ('+row.GR_NO+')');
	            	$('#save_edit').linkbutton('enable');
	            	$('#cancel_edit').linkbutton('enable');
	            	$('#supp_no_edit').textbox('setValue',row.SUPPLIER_CODE);
	            	$('#cmb_supp_edit').combobox('setValue',row.SUPPLIER_CODE);
	            	$('#gr_no_edit').textbox('setValue',row.GR_NO);
	            	$('#gr_date_edit').datebox('setValue',row.GR_DATE_2);
	            	$('#gr_remark_edit').textbox('setValue',row.REMARK);

					$('#dg_edit').datagrid({
					    url:'gr_get_detail_edit.php?gr_no='+row.GR_NO,
					    singleSelect: true,
					    columns:[[
							{field:'LINE_NO_GR', title:'GR<br/>LINE NO', width:50, halign: 'center', align: 'center'},
							{field:'PO_NO', title:'PO NO.', width:100, halign: 'center'},
							{field:'LINE_NO_PO', title:'PO<br/>LINE NO', width:50, halign: 'center', align: 'center'},
							{field:'PO_DATE',hidden: true},	
							{field:'ITEM_NO', title:'ITEM NO.', width:80, halign: 'center'},
							{field:'ITEM', title:'ITEM NAME', width:80, halign: 'center',hidden: true},
							{field:'DESCRIPTION', title:'DESCRIPTION', halign: 'center', width:250},
							{field:'STOCK_SUBJECT_CODE', title:'ITEM NAME', width:80, halign: 'center',hidden: true},
							{field:'COST_PROCESS_CODE', title:'ITEM NAME', width:80, halign: 'center',hidden: true},
							{field:'COST_SUBJECT_CODE', title:'ITEM NAME', width:80, halign: 'center',hidden: true},
							{field:'STANDARD_PRICE', title:'ITEM NAME', width:80, halign: 'center',hidden: true},
							{field:'SUPPLIERS_PRICE', title:'ITEM NAME', width:80, halign: 'center',hidden: true},
							{field:'UNIT', title:'UoM', halign: 'center', width:50, align:'center'},
							{field:'CURR_SHORT', title:'CURRENCY', halign: 'center', width:70, align:'center'},
						    {field:'ETA', title:'E T A', halign: 'center', width:80, align:'center'},
						    {field:'QTY_2', title:'PO QTY', halign: 'center',width:80, align:'right'},
							{field:'QTY', title:'PO QTY', halign: 'center',width:80, align:'right', editor:{
																										type:'numberbox',
																										options:{precision:2,groupSeparator:',',disable:true}
																									}
							,hidden:true},
							{field:'GR_QTY_2', title:'GOOD REC.<br>QTY', halign: 'center',width:80, align:'right'},
							{field:'GR_QTY', title:'GOOD REC.<br>QTY', halign: 'center',width:80, align:'right', editor:{
																													type:'numberbox',
																													options:{precision:2,groupSeparator:','}
																												}
							,hidden: true},
							{field:'BLNC', title:'BALANCE', halign: 'center',width:80, align:'right'},
							{field:'ACT_QTY', title:'Actual<br>QTY', align:'right', halign: 'center', width:100, editor:{
																													type:'numberbox',
																													options:{precision:2,groupSeparator:','}
																												}
							},
							{field:'ORIGIN_CODE', title: 'ORIGIN CODE', width:30,hidden: true},
							{field:'UOM_Q', title: 'UOM_Q', width:30,hidden: true},
							{field:'U_PRICE', title: 'PRICE', width:30,hidden: true},//
							{field:'CURR_CODE', title: 'CURR CODE', width:30,hidden: true},
							{field:'EX_RATE', title: 'EXCHANGE<br>RATE', width:30,hidden: true},
							{field:'PDAYS', title: 'PDAYS', width:30,hidden: true}, 
							{field:'PDESC', title: 'PDESC', width:30,hidden: true}, 
							{field:'AMT_O', title: 'AMT_O', width:30,hidden: true}, 
							{field:'AMT_L', title: 'AMT_L', width:30,hidden: true}, 
							{field:'SLIP_TYPE', title: 'SLIP_TYPE', width:30,hidden: true},
							{field:'STS_WH', title: 'STS_WH', width:70, hidden: true}//
					    ]],
					    onClickRow:function(rowIndex){
					    	$(this).datagrid('beginEdit', rowIndex);
					    },
					    onBeginEdit:function(rowIndex){
					        var editors = $('#dg_edit').datagrid('getEditors', rowIndex);
					        var n1 = $(editors[0].target);
					        var n2 = $(editors[1].target);
					        var n3 = $(editors[2].target);
					        n1.add(n3).numberbox({
					            onChange:function(){
					                var amt = n1.numberbox('getValue') - n2.numberbox('getValue');
					                if(n3.numberbox('getValue') > amt){
										$.messager.confirm('Confirm','actual value over',function(r){
											if(r){
												n3.numberbox('setValue',0);
											}else{
												n3.numberbox('setValue',$(editors[2].target));
											}		
										});
					                }
					            }
					        })
					    }
					});
	            }
			}

			function add_po_edit(){
				var supp_id = $('#supp_no_edit').textbox('getValue');
				var supp_name = $('#cmb_supp_edit').textbox('getText');

				if (supp_name==''){
					$.messager.alert('Warning','Please select supplier','warning');
				}else{
					$('#dlg_editPO').dialog('open').dialog('setTitle','Search PO & Item ('+supp_id+' - '+supp_name+')');
					$('#dg_editPO').datagrid('loadData',[]);
					$('#dg_editPO').datagrid('load',{supp: '', po: '', by: ''});

					$('#dg_editPO').datagrid({
						columns:[[
							{field:'PO_NO',title:'PO NO.', width:100, halign:'center'},
			                {field:'LINE_NO',title:'LINE',width:40,halign:'center', align:'center'},
			                {field:'ETA',title:'E T A',width:80,halign:'center', align:'center'},
			                {field:'CURR_SHORT',title:'CURRENCY',width:70,halign:'center', align:'center'},
			                {field:'ITEM_NO',title:'ITEM',width:80,halign:'center', align:'center'},
			                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
			                {field:'QTY',title:'QTY',width:100,halign:'center', align:'right'},
			                {field:'GR_QTY',title:'GOODS REC.<br>QTY',width:100,halign:'center', align:'right'},
			                {field:'BAL_QTY',title:'BALANCE',width:100,halign:'center', align:'right'},
			                {field:'COUNTRY',title:'ORIGIN',width:100,halign:'center'},
			                {field:'ORIGIN_CODE',hidden: true},
			                {field:'UOM_Q',hidden: true},
			                {field:'U_PRICE'},//,hidden: true
			                {field:'UNIT',hidden: true},
			                {field:'CURR_CODE',hidden: true},
			                {field:'EX_RATE',hidden: true},
			                {field:'PDAYS',hidden: true},
			                {field:'PDESC',hidden: true},
			                {field:'AMT_O',hidden: true},
			                {field:'AMT_L',hidden: true},
			                {field:'SLIP_TYPE',hidden: true},
			                {field:'ITEM',hidden: true},
			                {field:'STOCK_SUBJECT_CODE', hidden: true},
			                {field:'CLASS_CODE', hidden: true},
			                {field:'COUNTRY_CODE', hidden: true},
							{field:'COST_PROCESS_CODE', hidden: true},
							{field:'COST_SUBJECT_CODE', hidden: true},
							{field:'STANDARD_PRICE', hidden: true},
							{field:'SUPPLIERS_PRICE', hidden: true},
							{field:'PO_DATE', hidden: true},
							{field:'STS_WH', hidden: true}
			            ]],
			            onDblClickRow:function(id,row){
							var t = $('#dg_edit').datagrid('getRows');
							var total = t.length;
						   	var idxfield=0;
						   	var i = 0;
						   	var count = 0;
							if (parseInt(total) == 0) {
								idxfield=total;
							}else{
								idxfield=total+1;
								for (i=0; i < total; i++) {
									//alert(i);
									var po = $('#dg_edit').datagrid('getData').rows[i].PO_NO;
									var item = $('#dg_edit').datagrid('getData').rows[i].ITEM_NO;
									var line = $('#dg_edit').datagrid('getData').rows[i].LINE_NO;
									//alert(item);
									if (po==row.PO_NO && item == row.ITEM_NO && line == row.LINE_NO) {
										count++;
									};
								};
							}

							//alert('count = '+count);
							if (count>0) {
								$.messager.alert('Warning','PO present','warning');
							}else{
								$('#dg_edit').datagrid('insertRow',{
									index: idxfield,	// index start with 0
									row: {
										LINE_NO_GR: 'NEW',
										PO_NO: row.PO_NO,
										LINE_NO_PO: row.LINE_NO,
										PO_DATE: row.PO_DATE,
										ITEM_NO: row.ITEM_NO,
										DESCRIPTION: row.DESCRIPTION,
										UNIT: row.UNIT,
										UoM: row.UOM_Q,
										CURR_SHORT: row.CURR_SHORT,
										QTY: row.QTY,
										QTY_2: row.QTY_2,
										GR_QTY: row.GR_QTY,
										GR_QTY_2: row.GR_QTY_2,
										BAL_QTY: row.BAL_QTY,
										ACT_QTY: row.ACT_QTY,
										ORIGIN_CODE: row.ORIGIN_CODE,
										UOM_Q: row.UOM_Q,
										U_PRICE: row.U_PRICE,
										CURR_CODE: row.CURR_CODE,
										EX_RATE: row.EX_RATE,
										PDAYS: row.PDAYS,
										PDESC: row.PDESC,
										AMT_O: row.AMT_O,
										AMT_L: row.AMT_L,
										SLIP_TYPE: row.SLIP_TYPE,
										ITEM: row.ITEM,
										STOCK_SUBJECT_CODE: row.STOCK_SUBJECT_CODE,
										CLASS_CODE: row.CLASS_CODE,
										COUNTRY_CODE: row.COUNTRY_CODE,
										COST_PROCESS_CODE: row.COST_PROCESS_CODE,
										COST_SUBJECT_CODE: row.COST_SUBJECT_CODE,
										STANDARD_PRICE: row.STANDARD_PRICE,
										SUPPLIERS_PRICE: row.SUPPLIERS_PRICE,
										ETA: row.ETA,
										STS_WH: row.STS_WH
									}
								});
							}
						}
					});
					var dg = $('#dg_editPO').datagrid();
					dg.datagrid('enableFilter');
				}
			}

			function search_po_edit(){
				var supp_id_e = $('#supp_no_edit').textbox('getValue');
				var s_by_e = $('#cmb_search_e').combobox('getValue');
				var po_e = document.getElementById('s_po_edit').value;

				if(po_e !=''){
					$('#dg_editPO').datagrid('load',{supp: supp_id_e,  po: po_e, by: s_by_e});
					$('#dg_editPO').datagrid({url: 'gr_getpo.php',});
					
					document.getElementById('s_po_edit').value='';
					var dg = $('#dg_editPO').datagrid();
					dg.datagrid('enableFilter');
				}
			}

			function remove_po_edit(){
				var row = $('#dg_edit').datagrid('getSelected');
				var idx = $("#dg_edit").datagrid("getRowIndex", row);
				$('#dg_edit').datagrid('deleteRow', idx);
				$('#cancel_edit').linkbutton('disable');
				// if (row){
				// 	$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
				// 		if(r){
				// 			if(row.LINE_NO_GR=='NEW'){
				// 				var idx = $("#dg_edit").datagrid("getRowIndex", row);
				// 				$('#dg_edit').datagrid('deleteRow', idx);
				// 			}else{
				// 				$.messager.progress({
				// 				    title:'Please waiting',
				// 				    msg:'removing data...'
				// 				});
				// 				$.post('gr_delete_item_edit.php',{gr_no: $('#gr_no_edit').textbox('getValue'), item_no: row.ITEM_NO},function(result){
				// 					if (result.success){
				// 						$.messager.progress('close');
		        //                         $('#dg_edit').datagrid('reload');
		        //                     }else{
		        //                         $.messager.show({
		        //                             title: 'Error',
		        //                             msg: result.errorMsg
		        //                         });
		        //                     }
				// 				},'json');
				// 				$('#dg_edit').datagrid('deleteRow', idx);	
				// 			}	
				// 		}	
				// 	});
				// }
			}

			function simpan_edit(){
				$.messager.progress({
				    title:'Please waiting',
				    msg:'Save data...'
				});

				var dataRows_Edit = [];
				var tot_amt_o = 0;
				var	tot_amt_l = 0;
				var t = $('#dg_edit').datagrid('getRows');
				var total = t.length;
				var jmrow=0;
				for(i=0;i<total;i++){
					var amt_o = 0;
					var amt_l = 0;
					jmrow = i+1;
					$('#dg_edit').datagrid('endEdit',i);

					dataRows_Edit.push ({
						gr_sts: 'DETAILS',
						gr_no: $('#gr_no_edit').textbox('getValue'),
						gr_line: jmrow,
						gr_date: $('#gr_date_edit').datebox('getValue'),
						gr_supp: $('#supp_no_edit').textbox('getValue'),
						gr_supp_name: $('#cmb_supp_edit').textbox('getText'),
						gr_remark: $('#gr_remark_edit').textbox('getValue'),
						gr_sts_wh: $('#dg_edit').datagrid('getData').rows[i].STS_WH,
						gr_curr: $('#dg_edit').datagrid('getData').rows[i].CURR_CODE,
						gr_rate: $('#dg_edit').datagrid('getData').rows[i].EX_RATE,
						gr_pday: $('#dg_edit').datagrid('getData').rows[i].PDAYS,
						gr_pdes: $('#dg_edit').datagrid('getData').rows[i].PDESC,
						gr_amto: parseFloat($('#dg_edit').datagrid('getData').rows[i].ACT_QTY.replace(/,/g,'') * $('#dg_edit').datagrid('getData').rows[i].U_PRICE).toFixed(2),
						gr_amtl: parseFloat($('#dg_edit').datagrid('getData').rows[i].ACT_QTY.replace(/,/g,'') * $('#dg_edit').datagrid('getData').rows[i].U_PRICE * 
							$('#dg_edit').datagrid('getData').rows[i].EX_RATE).toFixed(2),
						gr_slip: $('#dg_edit').datagrid('getData').rows[i].SLIP_TYPE,
						gr_item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
						gr_item_name: $('#dg_edit').datagrid('getData').rows[i].ITEM,
						gr_desc: $('#dg_edit').datagrid('getData').rows[i].DESCRIPTION,
						gr_stock_subject_code: $('#dg_edit').datagrid('getData').rows[i].STOCK_SUBJECT_CODE,
						gr_class_code: $('#dg_edit').datagrid('getData').rows[i].CLASS_CODE,
						gr_country_code: $('#dg_edit').datagrid('getData').rows[i].COUNTRY_CODE,
						gr_cost_process_code: $('#dg_edit').datagrid('getData').rows[i].COST_PROCESS_CODE,
						gr_cost_subject_code: $('#dg_edit').datagrid('getData').rows[i].COST_SUBJECT_CODE,
						gr_standard_price: $('#dg_edit').datagrid('getData').rows[i].STANDARD_PRICE,
						gr_suppliers_price: $('#dg_edit').datagrid('getData').rows[i].SUPPLIERS_PRICE,
						gr_orig: $('#dg_edit').datagrid('getData').rows[i].ORIGIN_CODE,
						gr_pono: $('#dg_edit').datagrid('getData').rows[i].PO_NO,
						gr_po_line: $('#dg_edit').datagrid('getData').rows[i].LINE_NO_PO,
						gr_qty: $('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,''),
						gr_uomq: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
						gr_price: $('#dg_edit').datagrid('getData').rows[i].U_PRICE,
						gr_qty_act: $('#dg_edit').datagrid('getData').rows[i].ACT_QTY.replace(/,/g,''),
						gr_po_date: $('#dg_edit').datagrid('getData').rows[i].PO_DATE
					});
	//
					amt_o = parseFloat($('#dg_edit').datagrid('getData').rows[i].ACT_QTY * $('#dg_edit').datagrid('getData').rows[i].U_PRICE).toFixed(2);
					amt_l = parseFloat($('#dg_edit').datagrid('getData').rows[i].ACT_QTY * $('#dg_edit').datagrid('getData').rows[i].U_PRICE * 
							$('#dg_edit').datagrid('getData').rows[i].EX_RATE).toFixed(2);
	//
					tot_amt_o += parseFloat(amt_o);
					tot_amt_l += parseFloat(amt_l);
	//
					if(i==total-1){
						//$.post('gr_save.php',{
						dataRows_Edit.push({
							gr_sts: 'HEADER',
							gr_no: $('#gr_no_edit').textbox('getValue'),
							gr_line: jmrow,
							gr_date: $('#gr_date_edit').datebox('getValue'),
							gr_supp: $('#supp_no_edit').textbox('getValue'),
							gr_supp_name: $('#cmb_supp_edit').textbox('getText'),
							gr_remark: $('#gr_remark_edit').textbox('getValue'),
							gr_sts_wh: $('#dg_edit').datagrid('getData').rows[i].STS_WH,
							gr_curr: $('#dg_edit').datagrid('getData').rows[i].CURR_CODE,
							gr_rate: $('#dg_edit').datagrid('getData').rows[i].EX_RATE,
							gr_pday: $('#dg_edit').datagrid('getData').rows[i].PDAYS,
							gr_pdes: $('#dg_edit').datagrid('getData').rows[i].PDESC,
							gr_amto: parseFloat(tot_amt_o).toFixed(2),
							gr_amtl: parseFloat(tot_amt_l).toFixed(2),
							gr_slip: $('#dg_edit').datagrid('getData').rows[i].SLIP_TYPE,
							gr_item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
							gr_item_name: $('#dg_edit').datagrid('getData').rows[i].ITEM,
							gr_desc: $('#dg_edit').datagrid('getData').rows[i].DESCRIPTION,
							gr_stock_subject_code: $('#dg_edit').datagrid('getData').rows[i].STOCK_SUBJECT_CODE,
							gr_class_code: $('#dg_edit').datagrid('getData').rows[i].CLASS_CODE,
							gr_country_code: $('#dg_edit').datagrid('getData').rows[i].COUNTRY_CODE,
							gr_cost_process_code: $('#dg_edit').datagrid('getData').rows[i].COST_PROCESS_CODE,
							gr_cost_subject_code: $('#dg_edit').datagrid('getData').rows[i].COST_SUBJECT_CODE,
							gr_standard_price: $('#dg_edit').datagrid('getData').rows[i].STANDARD_PRICE,
							gr_suppliers_price: $('#dg_edit').datagrid('getData').rows[i].SUPPLIERS_PRICE,
							gr_orig: $('#dg_edit').datagrid('getData').rows[i].ORIGIN_CODE,
							gr_pono: $('#dg_edit').datagrid('getData').rows[i].PO_NO,
							gr_po_line: $('#dg_edit').datagrid('getData').rows[i].LINE_NO_PO,
							gr_qty: $('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,''),
							gr_uomq: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
							gr_price: $('#dg_edit').datagrid('getData').rows[i].U_PRICE,
							gr_qty_act: $('#dg_edit').datagrid('getData').rows[i].ACT_QTY.replace(/,/g,''),
							gr_po_date: $('#dg_edit').datagrid('getData').rows[i].PO_DATE
						});
					}
				}

				var myJSON_e=JSON.stringify(dataRows_Edit);
				var str_unescape_e=unescape(myJSON_e);

				$.post('gr_save.php',{
					data: unescape(str_unescape_e)
				}).done(function(res){
					if(res == '"success"'){
						$('#dlg_edit').dialog('close');
						$('#dg').datagrid('reload');
						$.messager.alert('INFORMATION','Update Data Success..!!<br/>GR No. : '+$('#gr_no_edit').textbox('getValue'),'info');
						$.messager.progress('close');
					}else{
						//$.post('gr_destroy.php',{gr_no: $('#gr_no_edit').textbox('getValue')},'json');
						$.messager.alert('ERROR',res,'warning');
						$.messager.progress('close');
					}
				});
			}

			function saveEdit(){
				$.messager.progress({
				    title:'Please waiting',
				    msg:'Removing data...'
				});

				$('#save_edit').linkbutton('disable');
				$('#cancel_edit').linkbutton('disable');
					//DELETE GR
				$.post('gr_destroy.php',{gr_no: $('#gr_no_edit').textbox('getValue')},function(result){
					if (result.successMsg == 'success'){
	                   $.messager.progress('close');
	                   simpan_edit();
	                }else{
	                   $.messager.show({
	                       title: 'Error',
	                       msg: result.errorMsg
	                   });
	                   $.messager.progress('close');
	               }
				},'json');
			}

			function delete_gr(){
				var row = $('#dg').datagrid('getSelected');	
				if (row){
					$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
						if(r){
							$.messager.progress({
							    title:'Please waiting',
							    msg:'removing data...'
							});

							$.post('gr_destroy.php',{gr_no: row.GR_NO},function(result){
								if (result.successMsg=='success'){
		                            $('#dg').datagrid('reload');
		                            console.log(result.successMsg);
		                            $.messager.progress('close');
		                        }else{
		                            $.messager.show({
		                                title: 'Error',
		                                msg: result.errorMsg
		                            });
		                            $.messager.progress('close');
		                        }
							},'json');
						}
					});
				}
			}

			function print_gr(){
				var row = $('#dg').datagrid('getSelected');
				if(row){
					//progress();
					pdf_url = "?gr="+row.GR_NO
					window.open('gr_print.php'+pdf_url);
				}else{
					$.messager.show({title: 'Goods Receive',msg: 'Data Not select'});
				}
			}

			function print_bc_no(){
				if(get_url==''){
					$.messager.show({title: 'Goods Receive',msg: 'Filter Data Not found'});	
				}else{
					window.open('gr_print_bc_view.php'+get_url);	
				}
			}

			function print_bc_no_sp(){
				var ck_date = "false";
				var ck_gr_no = "false";
				var ck_supp = "false";
				var ck_po = "false";
				var ck_item = "false";
				var flag = 0;

				if ($('#ck_date').attr("checked")) {
					ck_date = "true";
					flag += 1;
				}

				if ($('#ck_gr_no').attr("checked")) {
					ck_gr_no = "true";
					flag += 1;
				};

				if ($('#ck_supp').attr("checked")) {
					ck_supp = "true";
					flag += 1;
				};

				if ($('#ck_po').attr("checked")) {
					ck_po = "true";
					flag += 1;
				};

				if ($('#ck_item').attr("checked")) {
					ck_item = "true";
					flag += 1;
				};

				if(flag==5){
					$.messager.show({title: 'Goods Receive',msg: 'Filter Data Not found'});	
				}else{
					get_url = "?date_awal="+$('#date_awal').datebox('getValue')+
						  "&date_akhir="+$('#date_akhir').datebox('getValue')+
						  "&ck_date="+ck_date+
						  "&cmb_gr_no="+$('#cmb_gr_no').combobox('getValue')+
						  "&ck_gr_no="+ck_gr_no+
						  "&cmb_supp="+$('#cmb_supp').combobox('getValue')+
						  "&nm_supp="+$('#cmb_supp').combobox('getText')+
						  "&ck_supp="+ck_supp+
						  "&cmb_po="+$('#cmb_po').combobox('getValue')+
						  "&ck_po="+ck_po+
						  "&cmb_item="+$('#cmb_item').combobox('getValue')+
						  "&ck_item="+ck_item;

					window.open('gr_print_bc_view_sp.php'+get_url);	
				}
			}
		</script>
	</body>
    </html>