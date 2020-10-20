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
    <title>DEBIT NOTE</title>

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
	
	<table id="dg" title="DEBIT NOTE" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;"></table>

	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%; height:auto; float:left;"><legend>DEBIT NOTE FILTER</legend>
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
                <input style="width:100px;" name="cust_no_add" id="cust_no_add" class="easyui-textbox" data-options="" readonly/>
                <select style="width:505px;" name="cmb_cust_add" id="cmb_cust_add" class="easyui-combobox" data-options=" url:'../json/json_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
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
                <select style="width:300px;" name="bank_add" id="bank_add" class="easyui-combobox" data-options=" url:'../json/json_bank.php', method:'get', valueField:'bank_seq', textField:'bank', panelHeight:'100px'"></select>
            </div>
            <div class="fitem">
                <span style="width:80px;display:inline-block;">Shipment</span>
                <input style="width:200px;" name="shipment_add" id="shipment_add" class="easyui-textbox" data-options="" />
                <span style="width:50px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">Ex-Factory</span>
                <input style="width:200px;" name="exfact_add" id="exfact_add" class="easyui-textbox" data-options="" />
                <span style="width:15px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">SIGNATURE</span>
                <input style="width:300px;" name="signature_add" id="signature_add" class="easyui-textbox"value="AGUSMAN SURYA"/>
            </div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table align="center" id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:1075px;height:220px;padding:10px 10px; margin:5px;"></table>
		<div id="toolbar_add" style="padding: 5px 5px;">
			<a href="javascript:void(0)" iconCls='icon-add' class="easyui-linkbutton" onclick="add_invoice('add')" >ADD INVOICE</a>
			<a href="javascript:void(0)" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_invoice('add')">REMOVE INVOICE</a>
		</div>
	</div>

	<div id="dlg-buttons-add">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePRF('add')" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close');status_add_edit='';" style="width:90px">Cancel</a>
	</div>
	<!-- END ADD -->


    <!-- EDIT -->
    <div id='dlg_edit' class="easyui-dialog" style="width:1100px;height:450px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend>SELECT CUSTOMER</legend>
            <div class="fitem">
                <span style="width:80px;display:inline-block;">DN NO.</span>
                <input required="true" style="width:200px;" name="dn_no_edit" id="dn_no_edit" class="easyui-textbox" data-options="" readonly/>
                <span style="width:50px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">CUSTOMER</span>
                <input style="width:100px;" name="cust_no_edit" id="cust_no_edit" class="easyui-textbox" data-options="" readonly/>
                <select style="width:505px;" name="cmb_cust_edit" id="cmb_cust_edit" class="easyui-combobox" data-options=" url:'../json/json_customer.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px',
                onSelect:function(rec){
                    $('#cust_no_edit').textbox('setValue', rec.company_code);
                }"></select>
            </div>
            <div class="fitem">
                <span style="width:80px;display:inline-block;">DN DATE</span>
                <input style="width:85px;" name="dn_date_edit" id="dn_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
                <span style="width:165px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">ATTN</span>
                <input style="width:200px;" name="attn_edit" id="attn_edit" class="easyui-textbox" data-options="" />
                <span style="width:15px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">BANK</span>
                <select style="width:300px;" name="bank_edit" id="bank_edit" class="easyui-combobox" data-options=" url:'../json/json_bank.php', method:'get', valueField:'bank_seq', textField:'bank', panelHeight:'100px'"></select>
            </div>
            <div class="fitem">
                <span style="width:80px;display:inline-block;">Shipment</span>
                <input style="width:200px;" name="shipment_edit" id="shipment_edit" class="easyui-textbox" data-options="" />
                <span style="width:50px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">Ex-Factory</span>
                <input style="width:200px;" name="exfact_edit" id="exfact_edit" class="easyui-textbox" data-options="" />
                <span style="width:15px;display:inline-block;"></span>
                <span style="width:80px;display:inline-block;">SIGNATURE</span>
                <input style="width:300px;" name="signature_edit" id="signature_edit" class="easyui-textbox"value="AGUSMAN SURYA"/>
            </div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table align="center" id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:1075px;height:220px;padding:10px 10px; margin:5px;"></table>
		<div id="toolbar_edit" style="padding: 5px 5px;">
			<a href="javascript:void(0)" iconCls='icon-add' class="easyui-linkbutton" onclick="add_invoice('edit')">ADD INVOICE</a>
			<a href="javascript:void(0)" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_invoice('edit')">REMOVE INVOICE</a>
		</div>
	</div>

	<div id="dlg-buttons-edit">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePRF('edit')" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close');status_add_edit='';" style="width:90px">Cancel</a>
    </div>
    <!-- END EDIT -->
    
    <!-- ADD ITEM START -->
    <div id="dlg_addItem" class="easyui-dialog" style="width: 1000px;height: 400px;" closed="true" buttons="#dlg-buttons-addItem" data-options="modal:true">
        <table id="dg_addItem" class="easyui-datagrid" toolbar="#toolbar_addItem" style="width:100%;height:90%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
        <div style="clear:both;margin-bottom:10px;"></div>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="select_inv()" style="width:90px">SELECT</a>
    </div>
    <div id="toolbar_addItem" style="padding: 5px 5px;">
        <span style="width:80px;display:inline-block;">BL Date</span>
        <input style="width:85px;" name="bl_date_awal_add" id="bl_date_awal_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
        to 
        <input style="width:85px;" name="bl_date_akhir_add" id="bl_date_akhir_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
        <a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_item_add()">SEARCH</a>
    </div>
    <!-- ADD ITEM END -->

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
				    {field:'DN_NO',title:'DEBIT NOTE<br>NO.',width:75, halign: 'center'},
				    {field:'DN_DATE',title:'DEBIT NOTE<br>DATE',width:60, halign: 'center', align: 'center', sortable:true},
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
	                	title: 'DEBIT NOTE DETAIL (NO: '+row.DN_NO+')',
	                	url:'dn_get_detail.php?dn='+row.DN_NO,
						toolbar: '#ddv'+index,
						singleSelect:true,
						loadMsg:'load data ...',
						height:'auto',
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
                    {field:'DO_NO',title:'INV. NO.',width:65,halign:'center', align:'center'},
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
                singleSelect: false,
                checkOnSelect: true,
                selectOnCheck: true,
				columns:[[
	                {field:'ck', checkbox:true, width:30, halign: 'center'},
                    {field:'DO_NO',title:'DO NO.',width:65,halign:'center', align:'center'},
	                {field:'BL_DATE',title:'BL DATE',width:100,halign:'center'},
	                {field:'SHIP_NAME',title:'SHIP NAME',width:250,halign:'center'},
                    {field:'PO_NO_DESC',title:'PO NO.',width:250,halign:'center'},
	                {field:'ETA',title:'ETA',width:75,halign:'center', align:'center'},
                    {field:'ETD',title:'ETD',width:75,halign:'center', align:'center'},
	                {field:'AMT_O',title:'AMOUNT(ORG)',width:80,halign:'center', align:'right'},
                    {field:'CURR_CODE', hidden:true},
                    {field:'AMT_L', hidden:true},
                    {field:'PO_NO_DESC', hidden:true}
	            ]]
                // ,
	            // onDblClickRow:function(id,row){
                //     if (status_add_edit == 'add'){
                //         var t = $('#dg_add').datagrid('getRows');
                //         var total = t.length;
                //         var idxfield=0;
                //         var i = 0;
                //         var count = 0;
                //         if (parseInt(total) == 0) {
                //             idxfield=0;
                //         }else{
                //             idxfield=total;
                //         }

                //         $('#dg_add').datagrid('insertRow',{
                //             index: idxfield,
                //             row: {
                //                 DO_NO: row.DO_NO,
                //                 BL_DATE: row.BL_DATE,
                //                 SHIP_NAME: row.SHIP_NAME,
                //                 ETA: row.ETA,
                //                 ETD: row.ETD,
                //                 AMT_O: row.AMT_O,
                //                 CURR_CODE: row.CURR_CODE,
                //                 AMT_L: row.AMT_L,
                //                 PO_NO_DESC: row.PO_NO_DESC
                //             }
                //         });
                //     }else{
                //         var t = $('#dg_edit').datagrid('getRows');
                //         var total = t.length;
                //         var idxfield=0;
                //         var i = 0;
                //         var count = 0;
                //         if (parseInt(total) == 0) {
                //             idxfield=0;
                //         }else{
                //             idxfield=total;
                //         }

                //         $('#dg_edit').datagrid('insertRow',{
                //             index: idxfield,
                //             row: {
                //                 DO_NO: row.DO_NO,
                //                 BL_DATE: row.BL_DATE,
                //                 SHIP_NAME: row.SHIP_NAME,
                //                 ETA: row.ETA,
                //                 ETD: row.ETD,
                //                 AMT_O: row.AMT_O,
                //                 CURR_CODE: row.CURR_CODE,
                //                 AMT_L: row.AMT_L,
                //                 PO_NO_DESC: row.PO_NO_DESC
                //             }
                //         });
                //     }
				// }
			});

			$('#dg_edit').datagrid({
			    singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
                columns:[[
                    {field:'DO_NO',title:'INV. NO.',width:65,halign:'center', align:'center'},
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
		})

        function select_inv(){
            $.messager.progress({
                msg:'Get data...'
            });

            console.log(status_add_edit);

            var rows = $('#dg_addItem').datagrid('getSelections');
            var i = 0;
            for(i=0;i<rows.length;i++){
                if (status_add_edit == 'add'){
                    $('#dg_add').datagrid('insertRow',{
                        index: i,
                        row: {
                            DO_NO: rows[i].DO_NO,
                            BL_DATE: rows[i].BL_DATE,
                            SHIP_NAME: rows[i].SHIP_NAME,
                            ETA: rows[i].ETA,
                            ETD: rows[i].ETD,
                            AMT_O: rows[i].AMT_O,
                            CURR_CODE: rows[i].CURR_CODE,
                            AMT_L: rows[i].AMT_L,
                            PO_NO_DESC: rows[i].PO_NO_DESC
                        }
                    });
                }else{
                //     var t = $('#dg_edit').datagrid('getRows');
                //     var total = t.length;
                //     var idxfield=0;
                //     var i = 0;
                //     var count = 0;
                //     if (parseInt(total) == 0) {
                //         idxfield=0;
                //     }else{
                //         idxfield=total;
                //     }

                    $('#dg_edit').datagrid('insertRow',{
                        index: idxfield,
                        row: {
                            DO_NO: rows[i].DO_NO,
                            BL_DATE: rows[i].BL_DATE,
                            SHIP_NAME: rows[i].SHIP_NAME,
                            ETA: rows[i].ETA,
                            ETD: rows[i].ETD,
                            AMT_O: rows[i].AMT_O,
                            CURR_CODE: rows[i].CURR_CODE,
                            AMT_L: rows[i].AMT_L,
                            PO_NO_DESC: rows[i].PO_NO_DESC
                        }
                    });
                }
            }
            $.messager.progress('close');
            $('#dlg_addItem').dialog('close');
        }

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

        var status_add_edit = '';

		function add_invoice(a){
            status_add_edit = a;
            if(a == 'add'){
                var cst = $('#cust_no_add').textbox('getValue');
                if (cst != ''){
                    $('#dlg_addItem').dialog('open').dialog('setTitle','SEARCH INVOICE');
                    $('#dg_addItem').datagrid('loadData',[]);
                    var dg = $('#dg_addItem').datagrid();
                    // dg.datagrid('enableFilter');
                }else{
                    $.messager.alert('WARNING','CUSTOMER NOT SELECTED','warning');
                }
            }else{
                var cst = $('#cust_no_edit').textbox('getValue');
                if (cst != ''){
                    $('#dlg_addItem').dialog('open').dialog('setTitle','SEARCH INVOICE');
                    $('#dg_addItem').datagrid('loadData',[]);
                    var dg = $('#dg_addItem').datagrid();
                    dg.datagrid('enableFilter');
                }else{
                    $.messager.alert('WARNING','CUSTOMER NOT SELECTED','warning');
                }
            }
		}

		function search_item_add(){
            if (status_add_edit == 'add') {
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
            }else{
                var cust_no_add = $('#cust_no_edit').textbox('getValue');
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
		}

		function remove_invoice(a){
            if (a == 'add'){
                var row = $('#dg_add').datagrid('getSelected');	
                if (row){
                    var idx = $("#dg_add").datagrid("getRowIndex", row);
                    $('#dg_add').datagrid('deleteRow', idx);
                }
            }else{
                var row = $('#dg_edit').datagrid('getSelected');	
                if (row){
                    var idx = $("#dg_edit").datagrid("getRowIndex", row);
                    $('#dg_edit').datagrid('deleteRow', idx);
                }
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
				
                rows.push({
                    dn_sts: 'DETAILS',
                    dn_no: $('#dn_no_add').textbox('getValue'),
                    dn_line : jmrow,
                    dn_cust : $('#cust_no_add').textbox('getValue'),
                    dn_date : $('#dn_date_add').datebox('getValue'),
                    dn_attn : $('#attn_add').textbox('getValue'),    
                    dn_bank : $('#bank_add').combobox('getValue'),
                    dn_ship : $('#shipment_add').textbox('getValue'),
                    dn_exfa : $('#exfact_add').textbox('getValue'),
                    dn_sign : $('#signature_add').textbox('getValue'),
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

                if(i==total-1){
                    rows.push({
                        dn_sts: 'HEADER',
                        dn_no: $('#dn_no_add').textbox('getValue'),
                        dn_line: jmrow,
                        dn_cust: $('#cust_no_add').textbox('getValue'),
                        dn_date: $('#dn_date_add').datebox('getValue'),
                        dn_attn: $('#attn_add').textbox('getValue'),
                        dn_bank: $('#bank_add').combobox('getValue'),
                        dn_ship: $('#shipment_add').textbox('getValue'),
                        dn_exfa: $('#exfact_add').textbox('getValue'),
                        dn_sign: $('#signature_add').textbox('getValue'),
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
                }
			}
			
			var myJSON = JSON.stringify(rows);
			var str_unescape=unescape(myJSON);
			
            console.log('dn_save.php?dn_no_h='+$('#dn_no_add').textbox('getValue')+'&dn_stage=ADD&data='+unescape(str_unescape));

			$.post('dn_save.php',{
                dn_no_h: $('#dn_no_add').textbox('getValue'),
                dn_stage: 'ADD',
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>DN No. : '+$('#dn_no_add').textbox('getValue'),'info');
					$.messager.progress('close');
				}else{
					$.post('dn_destroy.php',{prf_no: $('#dn_no_add').textbox('getValue')},'json');
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}

        function simpan_edit(){
            var rows = [];
			var tot_amt_o = 0;
			var t = $('#dg_edit').datagrid('getRows');
			var total = t.length;
			var jmrow=0;

			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_edit').datagrid('endEdit',i);
                var amt_o = 0;
                amt_o = parseFloat($('#dg_edit').datagrid('getData').rows[i].AMT_O).toFixed(2);
				tot_amt_o += parseFloat(amt_o);
				
                rows.push({
                    dn_sts: 'DETAILS',
                    dn_no: $('#dn_no_edit').textbox('getValue'),
                    dn_line : jmrow,
                    dn_cust : $('#cust_no_edit').textbox('getValue'),
                    dn_date : $('#dn_date_edit').datebox('getValue'),
                    dn_attn : $('#attn_edit').textbox('getValue'),    
                    dn_bank : $('#bank_edit').combobox('getValue'),
                    dn_ship : $('#shipment_edit').textbox('getValue'),
                    dn_exfa : $('#exfact_edit').textbox('getValue'),
                    dn_sign : $('#signature_edit').textbox('getValue'),
                    dn_DO_NO: $('#dg_edit').datagrid('getData').rows[i].DO_NO,
                    dn_BL_DATE: $('#dg_edit').datagrid('getData').rows[i].BL_DATE,
                    dn_SHIP_NAME: $('#dg_edit').datagrid('getData').rows[i].SHIP_NAME,
                    dn_ETA: $('#dg_edit').datagrid('getData').rows[i].ETA,
                    dn_ETD: $('#dg_edit').datagrid('getData').rows[i].ETD,
                    dn_AMT_O: $('#dg_edit').datagrid('getData').rows[i].AMT_O.replace(/,/g,''),
                    dn_CURR_CODE: $('#dg_edit').datagrid('getData').rows[i].CURR_CODE,
                    dn_AMT_L: $('#dg_edit').datagrid('getData').rows[i].AMT_L.replace(/,/g,''),
                    dn_PO_NO_DESC: $('#dg_edit').datagrid('getData').rows[i].PO_NO_DESC
                });

                if(i==total-1){
                    rows.push({
                        dn_sts: 'HEADER',
                        dn_no: $('#dn_no_edit').textbox('getValue'),
                        dn_line: jmrow,
                        dn_cust: $('#cust_no_edit').textbox('getValue'),
                        dn_date: $('#dn_date_edit').datebox('getValue'),
                        dn_attn: $('#attn_edit').textbox('getValue'),
                        dn_bank: $('#bank_edit').combobox('getValue'),
                        dn_ship: $('#shipment_edit').textbox('getValue'),
                        dn_exfa: $('#exfact_edit').textbox('getValue'),
                        dn_sign: $('#signature_edit').textbox('getValue'),
                        dn_DO_NO: $('#dg_edit').datagrid('getData').rows[i].DO_NO,
                        dn_BL_DATE: $('#dg_edit').datagrid('getData').rows[i].BL_DATE,
                        dn_SHIP_NAME: $('#dg_edit').datagrid('getData').rows[i].SHIP_NAME,
                        dn_ETA: $('#dg_edit').datagrid('getData').rows[i].ETA,
                        dn_ETD: $('#dg_edit').datagrid('getData').rows[i].ETD,
                        dn_AMT_O: tot_amt_o,
                        dn_CURR_CODE: $('#dg_edit').datagrid('getData').rows[i].CURR_CODE,
                        dn_AMT_L: $('#dg_edit').datagrid('getData').rows[i].AMT_L.replace(/,/g,''),
                        dn_PO_NO_DESC: $('#dg_edit').datagrid('getData').rows[i].PO_NO_DESC
                    });
                }
			}
			
			var myJSON = JSON.stringify(rows);
			var str_unescape=unescape(myJSON);
			
            console.log('dn_save.php?dn_no_h='+$('#dn_no_edit').textbox('getValue')+'&dn_stage=EDIT&data='+unescape(str_unescape));

			$.post('dn_save.php',{
                dn_no_h: $('#dn_no_edit').textbox('getValue'),
                dn_stage: 'EDIT',
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_edit').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Update Data Success..!!<br/>DN No. : '+$('#dn_no_edit').textbox('getValue'),'info');
					$.messager.progress('close');
				}else{
					$.post('dn_destroy.php',{prf_no: $('#dn_no_edit').textbox('getValue')},'json');
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
        }

		function savePRF(s){
			if(s == 'add'){
                $.messager.progress({
                    title:'Please waiting',
                    msg:'Saving data...'
                });

                var url='';
                var dt = $('#dn_no_add').textbox('getValue');
                if( dt == ''){
                    $.messager.progress('close');
                    $.messager.alert('WARNING','DN no. not found','warning');
                }else{
                    $.ajax({
                        type: 'GET',
                        url: '../json/json_cek_kode_dn.php?dn_no='+$('#dn_no_add').textbox('getValue'),
                        data: { kode:'kode' },
                        success: function(data){
                            if(parseInt(data[0].kode) > 0){
                                $.messager.alert('INFORMATION','kode Already Exixst..!!','info');
                                $.messager.progress('close');
                            }else{
                                simpan();
                            }
                        }
                    });
                }
            }else{
                $.messager.progress({
                    title:'Please waiting',
                    msg:'Saving data...'
                });

                var url='';
                var dt = $('#dn_no_edit').textbox('getValue');
                if( dt == ''){
                    $.messager.progress('close');
                    $.messager.alert('WARNING','DN no. not found','warning');
                }else{
                    simpan_edit();
                }
            }
		}

		var sts_approved = '';
		var jumPO = '';

		function editPRF(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
                $('#dlg_edit').dialog('open').dialog('setTitle','EDIT DEBIT NOTE (NO. '+row.DN_NO+')');
                $('#dn_no_edit').textbox('setValue',row.DN_NO);
                $('#dn_date_edit').datebox('setValue',row.DN_DATE);
                $('#attn_edit').textbox('setValue',row.ATTN);
                $('#bank_edit').combobox('setValue',row.BANK_SEQ);
                $('#shipment_edit').textbox('setValue',row.DATE_SHIPMENT);
                $('#exfact_edit').textbox('setValue',row.DATE_SHIPMENT);
                $('#signature_edit').textbox('setValue',row.SIGNATURE_NAME);
                $('#cust_no_edit').textbox('setValue',row.CUSTOMER_CODE);
                $('#cmb_cust_edit').combobox('setValue',row.CUSTOMER_CODE);

                $('#dg_edit').datagrid({
                    url:'dn_get_detail_edit.php?dn_no='+row.DN_NO
                });
			}
		}

		function remove_item_edit(){
            var row = $('#dg_edit').datagrid('getSelected');	
			if (row){
				var idx = $("#dg_edit").datagrid("getRowIndex", row);
				$('#dg_edit').datagrid('deleteRow', idx);
			}
		}

		function destroyPRF(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
                $.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
                    if(r){
                        $.messager.progress({
                            title:'Please waiting',
                            msg:'removing data...'
                        });

                        $.post('dn_destroy.php',{dn_no: row.DN_NO},function(result){
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

		function printPRF(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				pdf_url = "?dn="+row.DN_NO
				window.open('dn_print.php'+pdf_url);
			}else{
				$.messager.show({title: 'Purchase Requestion',msg: 'Data Not select'});
			}
		}
	</script>
	</body>
    </html>