<?php 
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>DELIVERY NOTE</title>

    <link rel="icon" type="image/png" href="../../favicon.png">

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
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
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
	<?php include ('../../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name)); ?>
	
	<table id="dg" title="DELIVERY NOTE" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;"></table>

	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%; height:auto; float:left;"><legend>DELIVERY NOTE FILTER</legend>
            <div class="fitem">
                <span style="width:80px;display:inline-block;">BL Date</span>
                <input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
                to 
                <input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
                <label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
                <span style="width:100px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">CUSTOMER</span>
                <input required="true" style="width:100px;" name="cust_no" id="cust_no" class="easyui-textbox" disabled="disabled" data-options="" />
                <select style="width:300px;" name="cmb_cust" id="cmb_cust" class="easyui-combobox" data-options=" url:'../json/json_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
                onSelect:function(rec){
                    $('#cust_no').textbox('setValue', rec.company_code);
                }"></select>
                <label><input type="checkbox" name="ck_cust" id="ck_cust" checked="true">All</input></label>
            </div>
		</fieldset>
		<div style="clear:both;"></div>
		<div style="margin-top: 5px;margin: 5px;">
			<span style="width:50px;display:inline-block;">SEARCH</span>
			<input style="width:150px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" />
			<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
			<a href="javascript:void(0)" style="width: 100px;" id="add" class="easyui-linkbutton c2" onclick="addPRF()"><i class="fa fa-plus" aria-hidden="true"></i> ADD DN</a>
			<a href="javascript:void(0)" style="width: 100px;" id="edit" class="easyui-linkbutton c2" onclick="editPRF()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT DN</a>
			<a href="javascript:void(0)" style="width: 100px;" id="delete" class="easyui-linkbutton c2" onclick="destroyPRF()"><i class="fa fa-trash" aria-hidden="true"></i> DELETE DN</a>
			<a href="javascript:void(0)" style="width: 100px;" id="print" class="easyui-linkbutton c2" onclick="printPRF()"><i class="fa fa-print" aria-hidden="true"></i> PRINT DN</a>
		</div>
	</div>

	<!-- ADD -->
	<div id='dlg_add' class="easyui-dialog" style="width:1100px;height:450px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend>SELECT CUSTOMER</legend>
            <div class="fitem">
                <span style="width:80px;display:inline-block;">DN NO.</span>
                <input required="true" style="width:200px;" name="dn_no_add" id="dn_no_add" class="easyui-textbox" data-options="" />
                <span style="width:50px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">CUSTOMER</span>
                <input required="true" style="width:100px;" name="cust_no_add" id="cust_no_add" class="easyui-textbox" disabled="disabled" data-options="" />
                <select style="width:300px;" name="cmb_cust_add" id="cmb_cust_add" class="easyui-combobox" data-options=" url:'../json/json_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
                onSelect:function(rec){
                    $('#cust_no_add').textbox('setValue', rec.company_code);
                }"></select>
            </div>
            <div class="fitem">
                <span style="width:80px;display:inline-block;">DN DATE</span>
                <input style="width:85px;" name="dn_date_add" id="dn_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
                <span style="width:165px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">ATTN</span>
                <input style="width:200px;" name="attn_add" id="attn_add" class="easyui-textbox" data-options="" />
                <span style="width:15px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">BANK</span>
                <input style="width:200px;" name="bank_add" id="bank_add" class="easyui-textbox" data-options="" />
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="set_bank()" >SET</a>
            </div>
            <div class="fitem">
                <span style="width:80px;display:inline-block;">Shipment</span>
                <input style="width:200px;" name="shipment_add" id="shipment_add" class="easyui-textbox" data-options="" />
                <span style="width:50px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">Ex-Factory</span>
                <input style="width:200px;" name="exfact_add" id="exfact_add" class="easyui-textbox" data-options="" />
                <span style="width:15px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">SIGNATURE</span>
                <input style="width:200px;" name="signature_add" id="signature_add" class="easyui-textbox"value="AGUSMAN SURYA"/>
            </div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table align="center" id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:1075px;height:220px;padding:10px 10px; margin:5px;"></table>
		<div id="toolbar_add" style="padding: 5px 5px;">
			<a href="javascript:void(0)" iconCls='icon-add' class="easyui-linkbutton" onclick="add_invoice_add()" >ADD INVOICE</a>
			<a href="javascript:void(0)" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_invoice_add()">REMOVE INVOICE</a>
		</div>
		<div id="dlg_addItem" class="easyui-dialog" style="width: 1000px;height: 270px;" closed="true" buttons="#dlg-buttons-addItem" data-options="modal:true">
			<table id="dg_addItem" class="easyui-datagrid" toolbar="#toolbar_addItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>
		<div id="toolbar_addItem" style="padding: 5px 5px;">
            <span style="width:80px;display:inline-block;">BL Date</span>
            <input style="width:85px;" name="bl_date_awal_add" id="bl_date_awal_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
            to 
            <input style="width:85px;" name="bl_date_akhir_add" id="bl_date_akhir_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_item_add()">SEARCH</a>
		</div>
	</div>

	<div id="dlg-buttons-add">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePRF()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<!-- END ADD -->


	<!-- EDIT -->
	<div id='dlg_edit' class="easyui-dialog" style="width:1100px;height:420px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend>Update Purchase Requestion</legend>
			<div style="width:100%; height: 60px; float:left;">	
				<div class="fitem">
					<span style="width:60px;display:inline-block;">PRF No.</span>
					<input style="width:150px;" name="po_no_edit" id="prf_no_edit" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:60px;display:inline-block;">PRF Date</span>
					<input style="width:85px;" name="prf_date_edit" id="prf_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required=""/>
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">Cust. PO No.</span>
					<input style="width:150px;" name="cust_pono_edit" id="cust_pono_edit" class="easyui-textbox" disabled="true"/>
					<span style="width:5px;display:inline-block;"></span>
					<label><input type="checkbox" name="ck_new_edit" id="ck_new_edit">New Design</input></label>
					<span style="width:20px;display:inline-block;"></span>
					<span style="width: 50px;display:inline-block;">Remark</span>
					<input style="width: 200px; height: 56px;" name="remark_edit" id="remark_edit"  multiline="true" class="easyui-textbox" autofocus=""/>
				</div>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table align="center" id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:1075px;height:220px;padding:10px 10px; margin:5px;"></table>
		<div id="toolbar_edit" style="padding: 5px 5px;">
			<a href="javascript:void(0)" iconCls='icon-add' class="easyui-linkbutton" onclick="add_item_edit()" >ADD ITEM</a>
			<a href="javascript:void(0)" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_item_edit()">REMOVE ITEM</a>
		</div>
		<div id="dlg_addItem_edit" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons-addItem_edit" data-options="modal:true">
			<table id="dg_addItem_edit" class="easyui-datagrid" toolbar="#toolbar_addItem_edit" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>
		<div id="toolbar_addItem_edit" style="padding: 5px 5px;">
			<input style="width:200px;height: 20px;border-radius: 4px;" name="s_item_edit" id="s_item_edit" onkeypress="sch_item_edit(event)"/>
			<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_item_edit()">SEARCH ITEM</a>
		</div>
	</div>

	<div id="dlg-buttons-edit">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_edit_PRF()" style="width:90px">Save</a>
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

				if (src=='') {
					filterData();
				};
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

		$(function(){
			access_log();
			$('#date_awal').datebox('disable');
			$('#date_akhir').datebox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_awal').datebox('disable');
					$('#date_akhir').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_awal').datebox('enable');
					$('#date_akhir').datebox('enable');
				};
			})

			$('#cmb_cust').combobox('disable');
            $('#cust_no').textbox('disable');
			$('#ck_cust').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_cust').combobox('disable');
                    $('#cust_no').textbox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_cust').combobox('enable');
                    $('#cust_no').textbox('enable');
				};
			})

			$('#dg').datagrid({
				url:'dn_get.php',
		    	singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
				sortName: 'prf_date',
				sortOrder: 'asc',
			    columns:[[
				    {field:'DN_NO',title:'DELIVERY NOTE<br>NO.',width:75, halign: 'center', align: 'center'},
				    {field:'DN_DATE',title:'DELIVERY NOTE<br>DATE',width:60, halign: 'center', align: 'center', sortable:true},
				    {field:'COMPANY',title:'CUSTOMER',width:150, halign: 'center'},
				    {field:'AMT_O',title:'AMOUNT(ORG)', width:120, halign: 'center', align: 'right'},
				    {field:'ITEM',title:'ITEM', width:70, halign: 'center', align: 'center'}
			    ]],
			    view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
				},
				onExpandRow: function(index,row){
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
					
					listbrg.datagrid({
	                	title: 'Delivery Note Detail (No: '+row.DN_NO+')',
	                	url:'dn_get_detail.php?dn='+row.DN_NO,
						toolbar: '#ddv'+index,
						singleSelect:true,
						loadMsg:'load data ...',
						height:'auto',
						rownumbers: true,
						fitColumns: true,
						columns:[[
			                {field:'LINE_NO',title:'LINE NO.', halign:'center', align:'center', width:30, sortable: true},
                            {field:'INV_NO',title:'INVOICE NO.', halign:'center', align:'center', width:60, sortable: true},
			                {field:'BL_DATE', title:'BL DATE', halign:'center', align:'center', width:70},
                            {field:'SHIP_NAME', title:'VESSEL', halign:'center', width:200},
			                {field:'ETA', title:'ETA', halign:'center', align:'center', width:70},
                            {field:'ETD', title:'ETD', halign:'center', align:'center', width:70},
			                {field:'AMT_O', title:'AMOUNT(ORG)', halign:'center', align:'right', width:70}
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
			})

			$('#dg_add').datagrid({
			    singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
			    columns:[[
                    {field:'DO_NO',title:'DO NO.',width:65,halign:'center', align:'center'},
	                {field:'BL_DATE',title:'BL DATE',width:100,halign:'center'},
	                {field:'SHIP_NAME',title:'SHIP NAME',width:250,halign:'center'},
	                {field:'ETA',title:'ETA',width:75,halign:'center', align:'center'},
                    {field:'ETD',title:'ETD',width:75,halign:'center', align:'center'},
	                {field:'AMT_O',title:'AMOUNT(ORG)',width:80,halign:'center', align:'right'},
                    {field:'CURR_CODE', hidden:true},
                    {field:'AMT_L', hidden:true},
                    {field:'PO_NO_DESC', hidden:true}
			    ]]
			});

			$('#dg_addItem').datagrid({
				fitColumns: true,
				columns:[[
	                {field:'DO_NO',title:'DO NO.',width:65,halign:'center', align:'center'},
	                {field:'BL_DATE',title:'BL DATE',width:100,halign:'center'},
	                {field:'SHIP_NAME',title:'SHIP NAME',width:250,halign:'center'},
	                {field:'ETA',title:'ETA',width:75,halign:'center', align:'center'},
                    {field:'ETD',title:'ETD',width:75,halign:'center', align:'center'},
	                {field:'AMT_O',title:'AMOUNT(ORG)',width:80,halign:'center', align:'right'},
                    {field:'CURR_CODE', hidden:true},
                    {field:'AMT_L', hidden:true},
                    {field:'PO_NO_DESC', hidden:true}
	            ]],
	            onDblClickRow:function(id,row){
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=0;
					}else{
						idxfield=total;
					}

					$('#dg_add').datagrid('insertRow',{
                        index: idxfield,
                        row: {
                            DO_NO: row.DO_NO,
                            BL_DATE: row.BL_DATE,
                            SHIP_NAME: row.SHIP_NAME,
                            ETA: row.ETA,
                            ETD: row.ETD,
                            AMT_O: row.AMT_O,
                            CURR_CODE: row.CURR_CODE,
                            AMT_L: row.AMT_L,
                            PO_NO_DESC: row.PO_NO_DESC
                        }
                    });
				}
			});

			$('#dg_edit').datagrid({
			    singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
			    columns:[[
				    {field:'ITEM_NO', title:'ITEM NO.', width:65, halign: 'center', align: 'center'},
				    {field:'LINE_NO', title:'LINE NO.', width:65, halign: 'center', align: 'center'}, //hidden: true},
				    {field:'DESCRIPTION', title:'DESCRIPTION', width: 150, halign: 'center'},
				    {field:'UNIT', title:'UoM', halign: 'center', width:45, align:'center'},
				    {field:'ESTIMATE_PRICE', title:'STANDARD PRICE', halign: 'center', width:80, align:'right', editor:{
				    																								type:'numberbox',
				    																								options:{precision:8,groupSeparator:','}
				    																							}
				    },
				    {field:'REQUIRE_DATE', title: 'REQUIRE DATE', halign: 'center', width: 80, editor:{
				    																				type:'datebox',
				    																				options:{formatter:myformatter,parser:myparser}
				    																			}
				    },
				    {field:'QTY', title:'QTY', align:'right', halign: 'center', width:70, editor:{
				    																			type:'numberbox',
				    																			options:{precision:1,groupSeparator:','}
				    																		}
				    },
				    {field:'AMT', title:'ESTIMATE PRICE', halign: 'center', width:80, align:'right', editor:{
				    																					type:'numberbox',
				    																					options:{precision:2,groupSeparator:','}
				    																				}
				    },
				    {field:'OHSAS', title:'DATE CODE', halign: 'center', width:80, align:'right', editor:{
				    																								type:'textbox'
				    																							}
				    },
				    {field:'UOM_Q', hidden: true}
			    ]],
			    onClickRow:function(row){
			    	$(this).datagrid('beginEdit', row);
			    },
			    onBeginEdit:function(rowIndex){
			        var editors = $('#dg_edit').datagrid('getEditors', rowIndex);
			        var n1 = $(editors[0].target);
			        var n2 = $(editors[1].target);
			        var n3 = $(editors[2].target);
			        var n4 = $(editors[3].target);
			        var n5 = $(editors[4].target);
			        n1.add(n3).numberbox({
			            onChange:function(){
			                var amt = n1.numberbox('getValue')*n3.numberbox('getValue');
			                n4.numberbox('setValue',amt);
			            }
			        })
			    }
			});

			$('#dg_addItem_edit').datagrid({
				fitColumns: true,
				columns:[[
	                {field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM',title:'ITEM',width:100,halign:'center'},
	                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
	                {field:'UNIT',title:'UoM',width:75,halign:'center', align:'center'},
	                {field:'STANDARD_PRICE',title:'STANDARD PRICE',width:80,halign:'center', align:'right'}
	            ]],
	            onDblClickRow:function(id,row){
					var t = $('#dg_edit').datagrid('getRows');
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=0;
					}else{
						idxfield=total;
					}
					
					/*CEK DULU UDAH ADA DI ITEMMMAKER TIDAK, JIKA SUDAH ADA BARU INSERT KE DG_aDD*/
					$.ajax({
						type: 'GET',
						url: '../json/json_cek_itemmaker.php?item_no='+row.ITEM_NO,
						data: { kode:'kode' },
						success: function(data){
							$.messager.confirm('INFORMATION', data[0].maker, function(x){
								if(x){
									$('#dg_edit').datagrid('insertRow',{
										index: idxfield,
										row: {
											ITEM_NO: row.ITEM_NO,
											DESCRIPTION: row.DESCRIPTION,
											ITEM: row.ITEM,
											UOM_Q: row.UOM_Q,
											UNIT: row.UNIT,
											ESTIMATE_PRICE: row.STANDARD_PRICE,
											LINE_NO: 'NEW'
										}
									});
								}
							});
						}
					});
				}
			});
		})

		function filterData(){
			var ck_date = 'false';
			var ck_cust = 'false';
			var flag = 0;

			if($('#ck_date').attr("checked")){
				ck_date='true';
				flag += 1;
			}

			if($('#ck_cust').attr("checked")){
				ck_cust='true';
				flag += 1;
			}
			
			if(flag == 2) {
				$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
			}

			$('#dg').datagrid('load', {
				date_awal: $('#date_awal').datebox('getValue'),
				date_akhir: $('#date_akhir').datebox('getValue'),
				ck_date: ck_date,
                cust_no: $('#cust_no').textbox('getValue'),
				cmb_cust: $('#cmb_cust').combobox('getValue'),
				ck_cust: ck_cust,
				src: ''
			});

		   	$('#dg').datagrid('enableFilter');
		}

		function addPRF(){
			$('#dlg_add').dialog('open').dialog('setTitle','CREATE DELIVERY NOTE');
			$('#prf_no_add').textbox('setValue','');
			$('#remark_add').textbox('setValue','');
			$('#dg_add').datagrid('loadData',[]);
		}

		function add_invoice_add(){
            var cst = $('#cust_no_add').textbox('getValue');
            if (cst != ''){
                $('#dlg_addItem').dialog('open').dialog('setTitle','Search Item');
                document.getElementById('s_item_add').value = '';
                $('#dg_addItem').datagrid('loadData',[]);
                var dg = $('#dg_addItem').datagrid();
                dg.datagrid('enableFilter');
            }else{
                $.messager.alert('WARNING','CUSTOMER NOT SELECTED','warning');
            }
		}

		function search_item_add(){
            var cust_no_add = $('#cust_no_add').textbox('getValue');
            var bl_date_a = $('#bl_date_awal_add').datebox('getValue');
            var bl_date_z = $('#bl_date_akhir_add').datebox('getValue');

			if(bl_date_a !='' && bl_date_a != ''){
				$('#dg_addItem').datagrid('load', {
					cust_no_add : cust_no_add,
                    bl_date_a : bl_date_a,
                    bl_date_z : bl_date_z,
				});
				$('#dg_addItem').datagrid({url:'dn_getInvoice.php'});
			}
		}

		function remove_invoice_add(){
			var row = $('#dg_add').datagrid('getSelected');	
			if (row){
				var idx = $("#dg_add").datagrid("getRowIndex", row);
				$('#dg_add').datagrid('deleteRow', idx);
			}
		}

		function simpan(){
			var rows = [];
			var tot_amt_o = 0;
			var t = $('#dg_add').datagrid('getRows');
			var total = t.length;
			var jmrow=0;

			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_add').datagrid('endEdit',i);
                var amt_o = 0;
                amt_o = parseFloat($('#dg_add').datagrid('getData').rows[i].AMT_O).toFixed(2);
				tot_amt_o += parseFloat(amt_o);
				
                if(i==total-1){
                    rows.push({
                        dn_sts: 'HEADER',
                        dn_no: $('#dn_no_add').textbox('getValue'),
                        dn_line: jmrow,
                        dn_cust: $('#cust_no_add').textbox('getValue'),
                        dn_dn_date_add: $('#dn_date_add').datebox('getValue'),
                        dn_attn_add: $('#attn_add').textbox('getValue'),
                        dn_bank_add: $('#bank_add').textbox('getValue'),
                        dn_shipment_add: $('#shipment_add').textbox('getValue'),
                        dn_exfact_add: $('#exfact_add').textbox('getValue'),
                        dn_signature_add: $('#signature_add').textbox('getValue'),
                        dn_DO_NO: $('#dg_add').datagrid('getData').rows[i].DO_NO,
                        dn_BL_DATE: $('#dg_add').datagrid('getData').rows[i].BL_DATE,
                        dn_SHIP_NAME: $('#dg_add').datagrid('getData').rows[i].SHIP_NAME,
                        dn_ETA: $('#dg_add').datagrid('getData').rows[i].ETA,
                        dn_ETD: $('#dg_add').datagrid('getData').rows[i].ETD,
                        dn_AMT_O: tot_amt_o,
                        dn_CURR_CODE: $('#dg_add').datagrid('getData').rows[i].CURR_CODE,
                        dn_AMT_L: $('#dg_add').datagrid('getData').rows[i].AMT_L.replace(/,/g,''),
                        dn_PO_NO_DESC: $('#dg_add').datagrid('getData').rows[i].PO_NO_DESC
                    });
                }else{
                    rows.push({
                        dn_sts: 'DETAILS',
                        dn_no: $('#dn_no_add').textbox('getValue'),
                        dn_line: jmrow,
                        dn_cust: $('#cust_no_add').textbox('getValue'),
                        dn_dn_date_add: $('#dn_date_add').datebox('getValue'),
                        dn_attn_add: $('#attn_add').textbox('getValue'),
                        dn_bank_add: $('#bank_add').textbox('getValue'),
                        dn_shipment_add: $('#shipment_add').textbox('getValue'),
                        dn_exfact_add: $('#exfact_add').textbox('getValue'),
                        dn_signature_add: $('#signature_add').textbox('getValue'),
                        dn_DO_NO: $('#dg_add').datagrid('getData').rows[i].DO_NO,
                        dn_BL_DATE: $('#dg_add').datagrid('getData').rows[i].BL_DATE,
                        dn_SHIP_NAME: $('#dg_add').datagrid('getData').rows[i].SHIP_NAME,
                        dn_ETA: $('#dg_add').datagrid('getData').rows[i].ETA,
                        dn_ETD: $('#dg_add').datagrid('getData').rows[i].ETD,
                        dn_AMT_O: $('#dg_add').datagrid('getData').rows[i].AMT_O.replace(/,/g,''),
                        dn_CURR_CODE: $('#dg_add').datagrid('getData').rows[i].CURR_CODE,
                        dn_AMT_L: $('#dg_add').datagrid('getData').rows[i].AMT_L.replace(/,/g,''),
                        dn_PO_NO_DESC: $('#dg_add').datagrid('getData').rows[i].PO_NO_DESC
                    });
                }
			}
			
			var myJSON = JSON.stringify(rows);
			var str_unescape=unescape(myJSON);
			
            console.log('dn_save.php?data='+unescape(str_unescape));

			// $.post('dn_save.php',{
			// 	data: unescape(str_unescape)
			// }).done(function(res){
			// 	if(res == '"success"'){
			// 		$('#dlg_add').dialog('close');
			// 		$('#dg').datagrid('reload');
			// 		$.messager.alert('INFORMATION','Insert Data Success..!!<br/>DN No. : '+$('#dn_no_add').textbox('getValue'),'info');
			// 		$.messager.progress('close');
			// 		$.messager.confirm('Confirm','Are you sure you want to print Delivery Note?',function(r){
			// 		if(r){
			// 			window.open('dn_print.php?prf='+$('#prf_no_add').textbox('getValue'));
			// 		}
			// 	});
			// 	}else{
			// 		$.post('dn_destroy.php',{prf_no: $('#dn_no_add').textbox('getValue')},'json');
			// 		$.messager.alert('ERROR',res,'warning');
			// 		$.messager.progress('close');
			// 	}
			// });
		}

		function savePRF(){
			// $.messager.progress({
			//     title:'Please waiting',
			//     msg:'Saving data...'
			// });

			// var url='';
			// var dt = $('#prf_date_add').datebox('getValue');
			
			// if( dt == ''){
			// 	$.messager.progress('close');
			// 	$.messager.alert('WARNING','PRF date not found','warning');
			// }else{
			// 	$.ajax({
			// 		type: 'GET',
			// 		url: '../json/json_cek_kode_dn.php?dn_no='+$('#dn_no_add').textbox('getValue'),
			// 		data: { kode:'kode' },
			// 		success: function(data){
			// 			if(parseInt(data[0].kode) > 0){
			// 				$.messager.alert('INFORMATION','kode Already Exixst..!!','info');
			// 				$.messager.progress('close');
			// 			}else{
			// 				$('#dn_no_add').textbox('setValue', data[0].kode);
							simpan();
			// 			}
			// 		}
			// 	});
			// }
		}

		var sts_approved = '';
		var jumPO = '';

		function editPRF(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				if(parseInt(row.STS)==1 && row.JUM_PO != 0){
					$.messager.alert('INFORMATION','Already Approved..!!','info');
				}else{
					sts_approved = row.STS;
					jumPO = row.JUM_PO;
					$('#dlg_edit').dialog('open').dialog('setTitle','Update Purchase Requestion (No. '+row.PRF_NO+') STATUS : '+row.STATUS);
					$('#prf_no_edit').textbox('setValue',row.PRF_NO);
					$('#prf_date_edit').datebox('setValue',row.PRFDATE);
					$('#cust_pono_edit').textbox('setValue',row.CUSTOMER_PO_NO);

					if(parseInt(row.STS_DESIGN) == 1){
						$('#ck_new_edit').attr("checked",true);
					}else{
						$('#ck_new_edit').attr("checked",false);
					}

					$('#remark_edit').textbox('setValue',row.REMARK.replace(/<br>/g,"\n"));
					$('#dg_edit').datagrid({
						url:'purchase_req_get_detail_edit.php?prf_no='+row.PRF_NO
					});
				}
			}
		}

		function add_item_edit(){
			$('#dlg_addItem_edit').dialog('open').dialog('setTitle','Search Item');
			document.getElementById('s_item_edit').value = '';
			var dg = $('#dg_addItem_edit').datagrid();
			dg.datagrid('enableFilter');
		}

		function sch_item_edit(event){
			var sch_e = document.getElementById('s_item_edit').value;
			var search = sch_e.toUpperCase();
			document.getElementById('s_item_edit').value = search;
			
		    if(event.keyCode == 13 || event.which == 13){
				search_item_edit();
		    }
		}

		function search_item_edit(){
			var sch_e = document.getElementById('s_item_edit').value;
			if(sch_e !=''){
				$('#dg_addItem_edit').datagrid('load', {
					s_item: document.getElementById('s_item_edit').value
				});
				$('#dg_addItem_edit').datagrid({url:'purchase_req_getItem.php'});
				document.getElementById('s_item_edit').value = '';
			}
		}

		function remove_item_edit(){
			var row = $('#dg_edit').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						$.post('purchase_req_destroy_dtl.php',{prf: $('#prf_no_edit').textbox('getValue'), item: row.ITEM_NO, line: row.LINE_NO},function(result){
							if (result.success){
								$('#dg_edit').datagrid('reload');
	                        }else{
	                            $.messager.show({
	                                title: 'Error',
	                                msg: result.errorMsg
	                            });
	                        }
						},'json');
					}	
				});
			}
		}

		function save_edit_PRF(){
			//alert(sts_approved+'/'+jumPO);
			/*$.messager.progress({
			    title:'Please waiting',
			    msg:'Editing data...'
			});*/

			var rows_edit = [];
			var ck_dsign_e='false';
			var dt_edit = $('#prf_date_edit').datebox('getValue');

			if($('#ck_new_edit').attr("checked")){
				ck_dsign_e='true';
			}

			if (dt_edit == ''){
				$.messager.alert('WARNING','PRF date not found','warning');
			}else{
				var t = $('#dg_edit').datagrid('getRows');
				var total = t.length;
				var jmrow_edit=0;
				for(i=0;i<total;i++){
					jmrow_edit = i+1;
					$('#dg_edit').datagrid('endEdit',i);
					rows_edit.push({
						pu_sts: sts_approved,
						pu_jumPO: jumPO,
						pu_prf: $('#prf_no_edit').textbox('getValue'),
						pu_line: jmrow_edit,
						pu_date: $('#prf_date_edit').datebox('getValue'),
						pu_cust_po_no: $('#cust_pono_edit').textbox('getValue'),
						pu_ck_new: ck_dsign_e,
						pu_rmark: $('#remark_edit').textbox('getValue').replace(/\n/g,"<br>"),
						pu_item: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
						pu_line_ada: $('#dg_edit').datagrid('getData').rows[i].LINE_NO,
						pu_unit: $('#dg_edit').datagrid('getData').rows[i].UOM_Q,
						pu_s_price: $('#dg_edit').datagrid('getData').rows[i].ESTIMATE_PRICE.replace(/,/g,''),
						pu_require: $('#dg_edit').datagrid('getData').rows[i].REQUIRE_DATE,
						pu_qty: $('#dg_edit').datagrid('getData').rows[i].QTY.replace(/,/g,''),
						pu_amt: $('#dg_edit').datagrid('getData').rows[i].AMT,
						pu_ohsas: $('#dg_edit').datagrid('getData').rows[i].OHSAS
					});
				}

				var myJSON_e = JSON.stringify(rows_edit);
				var str_unescape_edit=unescape(myJSON_e);
				//console.log(str_unescape_edit);
				
				$.post('purchase_req_edit.php',{
					data: unescape(str_unescape_edit)
				}).done(function(res){
					console.log(res);
					if(res == '"success"'){
						$('#dlg_edit').dialog('close');
						$('#dg').datagrid('reload');
						$.messager.alert('INFORMATION','Update Data Success..!!<br/>PRF No. : '+$('#prf_no_edit').textbox('getValue'),'info');
						$.messager.progress('close');
					}else{
						console.log(res);
						$.messager.alert('ERROR',res,'warning');
						$.messager.progress('close');
					}
				});
			}
		}

		function destroyPRF(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				if(row.STS == '1' && row.JUM_PO != 0){
					$.messager.alert('INFORMATION',"Data can't to remove, because it has approved",'info');
				}else{
					$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
						if(r){
							$.messager.progress({
							    title:'Please waiting',
							    msg:'removing data...'
							});

							$.post('purchase_req_destroy.php',{prf_no: row.PRF_NO},function(result){
								if (result.success){
		                            $('#dg').datagrid('reload');
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
		}

		function printPRF(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				pdf_url = "?prf="+row.PRF_NO
				window.open('purchase_req_print.php'+pdf_url);
			}else{
				$.messager.show({title: 'Purchase Requestion',msg: 'Data Not select'});
			}
		}
	</script>
	</body>
    </html>