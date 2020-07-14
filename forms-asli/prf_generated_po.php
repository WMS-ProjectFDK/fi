<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Generate PO</title>
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
    <link rel="stylesheet" type="text/css" href="../themes/color.css" />
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
	.floating-box {
	    float: left;
	    width: 265px;
	    height: auto;
	    margin: 3px;
	    border: border:1px solid #272727;
	    border-radius: 4px;
	}
	</style>
    </head>
    <body>
	<?php include ('../ico_logout.php'); ?>

	<table id="dg" title="Generate Purchase" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:400px;"></table>

	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="new_PO()">Add PO</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" onclick="edit_PO()">Edit PO</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="destroy_PO()">Remove PO</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" onclick="printpdf_PO()">Print PO to PDF</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-excel" onclick="printexcel_PO()">Print PO to excel</a>
	</div>

	<!-- ADD -->
	<div id="dlg_add" class="easyui-dialog" style="width:800px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
		<fieldset class="floating-box">
			<div class="fitem">
				<span style="width:50px;display:inline-block;">Budget</span> :
				<input type="radio" id="budget_add" name="bud_add" value="BUDGET" checked="checked">BUDGET
				<input type="radio" id="nonbudget_add" name="bud_add" value="NON-BUDGET">NON-BUDGET
			</div>
			<div class="fitem">
	        	<span style="width:60px;display:inline-block;">PO No.</span>
	        	<input style="width:200px;" name="po_no_add" id="po_no_add" class="easyui-textbox" disabled="" />	
	        </div>
			<div class="fitem">
	        	<span style="width:60px;display:inline-block;">PO Date</span>
	        	<input style="width:100px;" name="po_date_add" id="po_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required=""/>	
	        </div>
	        <div class="fitem">
	        	<span style="width:60px;display:inline-block;">Vendor</span>
	        	<input style="width:200px;" id="vendor_add" class="easyui-combobox" data-options=" url:'json/json_vendor_all.php', method:'get', valueField:'COMPANY_CODE', textField:'COMPANY', panelHeight:'100px',
	        	onSelect: function(rec){
	        		var vndr = rec.COMPANY_CODE;
	        		//alert(vndr);
	        		$('#dg_add').datagrid('load', {
	        			vendor: vndr
	        		});
	        		$.ajax({
	        			type: 'GET',
						url: 'json/json_vendor_select.php?vendor='+rec.COMPANY_CODE,
						data: { kode:'kode' },
						success: function(data){
							$('#payterms_add').textbox('setValue',data[0].TERMS);
						}
	        		});
	        	}" required="true" />
	        </div>
	        <div class="fitem">
	        	<span style="width:60px;display:inline-block;">Currency</span>
	        	<input style="width:70px;" id="curr_add" class="easyui-combobox" data-options=" url:'json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'100px',
	        	onSelect: function(rec){
	        		$.ajax({
	        			type: 'GET',
						url: 'json/json_exrate.php?curr='+rec.idcrc,
						data: { kode:'kode' },
						success: function(data){
							$('#exrate_add').textbox('setValue',data[0].RATE);	
						}
	        		});
	        	}" required="true" />
	        	<input style="width:125px;" name="exrate_add" id="exrate_add" class="easyui-textbox" disabled="" />
	        </div>
		</fieldset>

		<fieldset class="floating-box" style="width: 465px;">
			<div class="fitem">
	        	<span style="width:100px;display:inline-block;">Payment Terms</span>
	        	<input style="width:350px;" name="payterms_add" id="payterms_add" class="easyui-textbox" disabled="" />
	        </div>
	        <div class="fitem">
	        	<span style="width:100px;display:inline-block;">Remark</span>
	        	<input style="width:350px;height:35px" name="remark_add" id="remark_add" class="easyui-textbox" multiline="true"/>
	        	<!-- <input style="width:150px;" name="shipment_add" id="shipment_add" class="easyui-combobox" data-options=" url:'json/json_via.json', method:'get', valueField:'via', textField:'via', panelHeight:'65px'" required/> -->
	        </div>
	        <div class="fitem">
	        	<span style="width:100px;display:inline-block;">Note</span>
	        	<input style="width:350px;height:37px" name="note_add" id="note_add" class="easyui-textbox" multiline="true"/>
	        </div><br/>
	        <div class="fitem"></div><div class="fitem"></div>
		</fieldset>
        <div style="clear:both;margin-bottom:15px;"></div>
		<div style="margin: 3px;"><table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:250px;"></table></div>
    </div>

    <div id="dlg-buttons-add">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_add()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <!-- END ADD -->

    <!-- EDIT -->
	<div id="dlg_edit" class="easyui-dialog" style="width:600px" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
        <fieldset class="floating-box">
			<div class="fitem">
				<span style="width:50px;display:inline-block;">Budget</span> :
				<input type="radio" id="budget_edit" name="bud_edit" value="BUDGET" checked="checked">BUDGET
				<input type="radio" id="nonbudget_edit" name="bud_edit" value="NON-BUDGET">NON-BUDGET
			</div>
			<div class="fitem">
	        	<span style="width:50px;display:inline-block;">PO No.</span>
	        	<input style="width:200px;" name="po_no_edit" id="po_no_edit" class="easyui-textbox" disabled="" />	
	        </div>
			<div class="fitem">
	        	<span style="width:50px;display:inline-block;">PO Date</span>
	        	<input style="width:100px;" name="po_date_edit" id="po_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required=""/>	
	        </div>
	        <div class="fitem">
	        	<span style="width:50px;display:inline-block;">Vendor</span>
	        	<input required="true" style="width:200px;" id="vendor_edit" class="easyui-combobox" data-options="" />
	        </div>
		</fieldset>

		<fieldset class="floating-box">
			<div class="fitem">
	        	<span style="width:100px;display:inline-block;">Payment Terms</span>
	        	<input style="width:150px;" name="payterms_edit" id="payterms_edit" class="easyui-textbox" required=""/>
	        </div>
	        <div class="fitem">
	        	<span style="width:100px;display:inline-block;">Shipment Via</span>
	        	<input required="true" style="width:150px;" id="shipment_edit" class="easyui-combobox" data-options="" />
	        </div><br>
	        <div class="fitem"></div><div class="fitem"></div>
		</fieldset>
        <div style="clear:both;margin-bottom:15px;"></div>
		<div style="margin: 3px;"><table id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:100%;height:250px;"></table></div>
    </div>

    <div id="dlg-buttons-edit">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_edit()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <!-- END EDIT -->

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
			$('#po_date_add').datebox('setValue','<?=date('Y-m-d')?>');

			$('#dg').datagrid({
				url: 'prf_generated_po_get.php',
				rownumbers:'true', 
				fitColumns:'true',
				singleSelect:'true'
			});

			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');

			$('#dg_add').datagrid({
    			url: 'prf_generated_po_getadd.php',
    			rownumbers:'true',
				singleSelect:'true',
				/*frozenColumns:[[
				]],*/
				columns:[[
					{field:'ID',title:'ID', halign:'center', width:100, hidden: 'true'},
					{field:'REQ_NO',title:'PRF NO.', halign:'center', width:100},
					{field:'ITEM_NO',title:'ITEM', halign:'center', width:130},
					{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:250},
					{field:'UNIT',title:'UoM', halign:'center', align:'center', width:50},
					{field:'PRICE',title:'PRICE', halign:'center', align: 'right', width:100},
					{field:'TYPE_BUDGET',title:'BUDGET TYPE', halign:'center', align: 'center', width:100},
					{field:'QTY',title:'QTY', halign:'center', align:'right', width:100},
					{field:'TOTAL',title:'TOTAL', halign:'center', align:'right', width:100}
				]]
			});
		})

		function new_PO(){
            $('#dlg_add').dialog('open').dialog('center').dialog('setTitle','Create Purchase Order');
            $('#po_no_add').textbox('setValue','');
            $('#dg_add').datagrid('loadData',[]);
            $('#vendor_add').combobox('setValue','');
            $('#curr_add').combobox('setValue','');
            $('#payterms_add').textbox('setValue','');
            //$('#shipment_add').combobox('setValue','');
            $('#remark_add').textbox('setValue','');
            $('#note_add').textbox('setValue','');
        }

        function save_add(){
        	var url='';
        	$.ajax({
        		type: 'GET',
				url: 'json/json_po.php',
				data: { kode:'kode' },
				success: function(data){

				}
        	});

			$('#dlg_add').dialog('close');
			$('#dg').datagrid('reload');
		}

        function edit_PO(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg_edit').dialog('open').dialog('center').dialog('setTitle','Update Purchase Order');
            }
        }

        function save_edit(){
			$('#dlg_edit').dialog('close');
			$('#dg').datagrid('reload');
        }

        function destroy_PO(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Are you sure you want to destroy this Item?',function(r){
                    if (r){

                    }
                });
            }
        }
	</script>
    </body>
    </html>