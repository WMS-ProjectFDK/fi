<?php 
session_start();
include("../connect/conn.php");
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
if ($varConn=='Y'){
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>SALES INQUIRY</title>
    <link rel="icon" type="image/png" href="../favicon.png">
	<script language="javascript">
		var ct = 0;
		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
		}
	</script> 
	<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
	<link rel="stylesheet" type="text/css" href="../themes/icon.css" />
	<link rel="stylesheet" type="text/css" href="../css/style.css">
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
	</style>
    </head>
    <body>
	<?php include ('../ico_logout.php'); ?>

	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:940px; height:100px; float:left;"><legend>INQUIRY Filter</legend>
			<div style="width:470px; float:left;">
				
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Inquiry No.</span>
					<select style="width:300px;" name="inquiry_no" id="inquiry_no" class="easyui-combobox" data-options=" url:'json/json_inquiry_no.php',method:'get',valueField:'inq_no',textField:'inq_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_inquiry" id="ck_inquiry" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Sales No.</span>
					<select style="width:300px;" name="sales_no" id="sales_no" class="easyui-combobox" data-options=" url:'json/json_customer.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_sales_no" id="ck_sales_no" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Item No.</span>
					<select style="width:300px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'item_no',textField:'item_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width:150px;display:inline-block;">Incoming Email Date</span>
					<input style="width:85px;" name="date_incoming" id="date_incoming" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"  value="<?date();?>" />
					<label><input type="checkbox" name="ck_date_incoming" id="ck_date_incoming" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:150px;display:inline-block;">Destination</span>
					<select style="width:230px;" name="dest" id="dest" class="easyui-combobox" data-options=" url:'_getPort.php?sch=',method:'get',valueField:'PORT',textField:'PORT', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_dest" id="ck_dest" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:150px;display:inline-block;">PO No.</span>
					<select style="width:230px;" name="cmb_po_no" id="cmb_po_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'po_no',textField:'po_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_po_no" id="ck_po_no" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; height:100px; margin-left:965px;"><legend>INQUIRY REPORT</legend></fieldset>
		<div style="clear:both;"></div>
		<div style="margin-top: 5px;margin: 5px;">
			<span style="margin-left:10px;width:50px;display:inline-block;">search</span> 
			<input style="width:150px; height: 20px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" placeholder=" inquiry no." name="src" id="src" type="text" />
			<a href="javascript:void(0)" id="filbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:108px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			<a href="javascript:void(0)" style="width: 108px;" class="easyui-linkbutton c2" id="addInv" onclick="addInq()"><i class="fa fa-plus" aria-hidden="true"></i> Add Inquiry</a>
			<a href="javascript:void(0)" style="width: 108px;" class="easyui-linkbutton c2" id="editInv" onclick="editInq()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Inquiry</a>
		</div>
	</div>
	<table id="dg" title="SALES INQUIRY" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;"></table>

	<!-- START ADD -->
	<div id='win_add' class="easyui-window" style="width:auto;height:auto;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
		<form id="f_add" method="post" novalidate>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:97%; float:left; margin:5px;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Incoming Email</span>
					<input style="width:300px;" name="incoming_email_add" id="incoming_email_add" class="easyui-textbox" required=""/>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:100px;display:inline-block;">Destination</span>
					<select style="width:300px;" name="dest_add" id="dest_add" class="easyui-combobox" style="width:142px;"></select>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Salesman</span>
					<input style="width:300px;" name="sales_add" id="sales_add" class="easyui-textbox" required=""/>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:100px;display:inline-block;">Inquiry No.</span>
					<input style="width:150px;" name="inq_no_add" id="inq_no_add" class="easyui-textbox" required=""/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">PO No.</span>
					<input style="width:300px;" name="po_no_add" id="po_no_add" class="easyui-textbox" required=""/>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:100px;display:inline-block;">PO Input Date</span>
					<input style="width:85px;" name="po_date_add" id="po_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>" required=""/>
				</div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:300px;border-radius: 10px;margin:5px;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
			<div id="toolbar_add" style="padding: 5px 5px;">
				<a href="javascript:void(0)" id="add_item_no" iconCls='icon-add' class="easyui-linkbutton" onclick="sett_item_no('add')">ADD ITEM</a>
				<a href="javascript:void(0)" id="remove_item_no" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_item_no('add')">REMOVE ITEM</a>
			</div>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
                <a class="easyui-linkbutton c2" id="savebtn_add" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveInq('add')" style="width:140px" disabled="true"> SAVE </a>
                <a class="easyui-linkbutton c2" id="clsbtn" href="javascript:void(0)" onclick="javascript:$('#win_add').window('close')" style="width:140px"><i class="fa fa-ban" aria-hidden="true"></i> Cancel </a>
            </div>
		</form>
	</div>
	<!-- FINISH ADD -->


	<!-- START EDIT -->
	<div id='win_edit' class="easyui-window" style="width:auto;height:auto;padding:5px 5px;" closed="true" closable="false" minimizable="false" maximizable="true" collapsible="false" data-options="modal:true">
		<form id="f_edit" method="post" novalidate>
			<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:97%; float:left; margin:5px;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Incoming Email</span>
					<input style="width:300px;" name="incoming_email_edit" id="incoming_email_edit" class="easyui-textbox" required=""/>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:100px;display:inline-block;">Destination</span>
					<select style="width:300px;" name="dest_edit" id="dest_edit" class="easyui-combobox" style="width:142px;"></select>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Salesman</span>
					<input style="width:300px;" name="sales_edit" id="sales_edit" class="easyui-textbox" required=""/>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:100px;display:inline-block;">Inquiry No.</span>
					<input style="width:150px;" name="inq_no_edit" id="inq_no_edit" class="easyui-textbox" required=""/>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">PO No.</span>
					<input style="width:300px;" name="po_no_edit" id="po_no_edit" class="easyui-textbox" required=""/>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:100px;display:inline-block;">PO Input Date</span>
					<input style="width:85px;" name="po_date_edit" id="po_date_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>" required=""/>
				</div>
			</fieldset>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:100%;height:300px;border-radius: 10px;margin:5px;" rownumbers="true" singleSelect="true" fitColumns= "true"></table>
			<div id="toolbar_add" style="padding: 5px 5px;">
				<a href="javascript:void(0)" id="add_item_no" iconCls='icon-add' class="easyui-linkbutton" onclick="sett_item_no('edit')">ADD ITEM</a>
				<a href="javascript:void(0)" id="remove_item_no" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_item_no('edit')">REMOVE ITEM</a>
			</div>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
                <a class="easyui-linkbutton c2" id="savebtn_edit" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="saveInq('edit')" style="width:140px" disabled="true"> SAVE </a>
                <a class="easyui-linkbutton c2" id="clsbtn" href="javascript:void(0)" onclick="javascript:$('#win_edit').window('close')" style="width:140px"><i class="fa fa-ban" aria-hidden="true"></i> Cancel </a>
            </div>
		</form>
	</div>
	<!-- FINISH EDIT -->

	<div id="dlg_addItem" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons-addItem_edit" data-options="modal:true">
		<table id="dg_addItem" class="easyui-datagrid" toolbar="#toolbar_addItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
	</div>
	<div id="toolbar_addItem" style="padding: 5px 5px;">
		<input style="width:200px;height: 20px;border-radius: 4px;" name="s_item" id="s_item" placeholder=" search by item no." onkeypress="sch_item(event)"/>
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

		$(function(){
			$('#inquiry_no').combobox('disable');
			$('#ck_inquiry').change(function(){
				if ($(this).is(':checked')) {
					$('#inquiry_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#inquiry_no').combobox('enable');
				};
			})

			$('#sales_no').combobox('disable');
			$('#ck_sales_no').change(function(){
				if ($(this).is(':checked')) {
					$('#sales_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#sales_no').combobox('enable');
				};
			})

			$('#cmb_item_no').combobox('disable');
			$('#ck_item_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_item_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_item_no').combobox('enable');
				};
			})

			$('#date_incoming').combobox('disable');
			$('#ck_date_incoming').change(function(){
				if ($(this).is(':checked')) {
					$('#date_incoming').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_incoming').combobox('enable');
				};
			})

			$('#dest').combobox('disable');
			$('#ck_dest').change(function(){
				if ($(this).is(':checked')) {
					$('#dest').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#dest').combobox('enable');
				};
			})

			$('#cmb_po_no').combobox('disable');
			$('#ck_po_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_po_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_po_no').combobox('enable');
				};
			})
		});

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
		    }
		}

		function filterData(){
			var ck_inquiry='false';
			var ck_sales='false';
			var ck_item_no='false';
			var ck_date_incoming='false';
			var ck_dest='false';
			var ck_po_no='false';
			var flag = 0;

			if($('#ck_inquiry').attr("checked")){
				ck_inquiry='true';
				flag += 1;
			}

			if($('#ck_sales_no').attr("checked")){
				ck_sales='true';
				flag += 1;
			}
			if($('#ck_item_no').attr("checked")){
				ck_item_no='true';
				flag += 1;
			}
			if($('#ck_date_incoming').attr("checked")){
				ck_date_incoming='true';
				flag += 1;
			}

			if($('#ck_dest').attr("checked")){
				ck_dest='true';
				flag += 1;
			}

			if($('#ck_po_no').attr("checked")){
				ck_po_no='true';
				flag += 1;
			}

			if(flag == 6){
				$.messager.alert("INFORMATION","No filter data, system only show 150 records","info");
			}
			
			$('#dg').datagrid('load', {
				sales_inq: $('#inquiry_no').combobox('getValue'), 
				ck_inquiry: ck_inquiry,
				sales_no: $('#sales_no').combobox('getValue'),
				ck_sales: ck_sales,
				cmb_item_no: $('#cmb_item_no').combobox('getValue'),
				ck_item_no: ck_item_no,
				inc_email_date: $('#date_incoming').datebox('getValue'),
				ck_date_incoming: ck_date_incoming,
				sales_dest: $('#dest').combobox('getValue'),
				ck_dest: ck_dest,
				sales_po: $('#cmb_po_no').combobox('getValue'),
				ck_po_no: ck_po_no,
				src: ''
			});

		   	url_pdf ="?sales_inq="+$('#inquiry_no').combobox('getValue')+"&ck_inquiry="+ck_inquiry+"&sales_no="+$('#sales_no').combobox('getValue')+"&ck_sales="+ck_sales+"&cmb_item_no="+$('#cmb_item_no').combobox('getValue')+"&ck_item_no="+ck_item_no+"&inc_email_date="+$('#date_incoming').datebox('getValue')+"&ck_date_incoming="+ck_date_incoming+"&sales_dest="+$('#dest').combobox('getValue')+"&ck_dest="+ck_dest+"&sales_po="+$('#cmb_po_no').combobox('getValue')+"&ck_po_no="+ck_po_no;

		   	$('#dg').datagrid('enableFilter');
		}

		function validate(value){
			var hasil=0;
			var msg='';
				if(value == 'add'){
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;

					if($('#inq_no_add').combobox('getValue')==''){
						msg = $.messager.alert('INFORMATION','Inquiry No. not found','info');
						hasil=1;
					}

					for(i2=0;i2<total;i2++){
						$('#dg_add').datagrid('endEdit',i2);
						if ($('#dg_add').datagrid('getData').rows[i2].ITEM_NO == ''){
							msg = $.messager.alert('INFORMATION','Item No. not Found','info');
							hasil=1;		
						}
					}
				}else if(value == 'edit'){
					var t = $('#dg_edit').datagrid('getRows');
					var total = t.length;

					if($('#inq_no_edit').combobox('getValue')==''){
						msg = $.messager.alert('INFORMATION','Inquiry No. not found','info');
						hasil=1;
					}

					for(i2=0;i2<total;i2++){
						$('#dg_edit').datagrid('endEdit',i2);
						if ($('#dg_edit').datagrid('getData').rows[i2].ITEM_NO == ''){
							msg = $.messager.alert('INFORMATION','Item No. not Found','info');
							hasil=1;		
						}
					}
				}
			return hasil;
		}


		/*========================================== ADD ============================================*/
		function addInq(){
			$('#win_add').window('open').window('setTitle','ADD INQUIRY');
			$('#win_add').window('center');
			$('#f_add').form('reset');
			$('#dg_add').datagrid('loadData',[]);
			$('#dg_add').datagrid({
				rownumbers: true,
				fitColumns: true,
				columns:[[
					{field:'ITEM_NO', title:'ITEM NO', width:50, halign:'center'},
					{field:'ITEM', title:'ITEM', width:80, halign:'center'},
	                {field:'DESCRIPTION', title:'DESCRIPTION', width:150, halign:'center'},
	                {field:'QTY', title:'QTY', width:70, halign:'center', align:'right', editor:{type:'numberbox',
																					   			 options:{precision:0,groupSeparator:',',disable:true}
																	   				  			}
					},
					{field:'CR_DATE_REQ', title:'CR Date [REQUEST]', width:60, halign:'center', align:'right', editor:{type:'datebox',
																													   options:{formatter:myformatter,parser:myparser}}},
					{field:'ETD_REQ', title:'ETD [REQUEST]', width:60, halign:'center', align:'right', editor:{type:'datebox',
																											   options:{formatter:myformatter,parser:myparser}}},
					{field:'ETA_REQ', title:'ETA [REQUEST]', width:60, halign:'center', align:'right', editor:{type:'datebox',
																											   options:{formatter:myformatter,parser:myparser}}},
					{field:'CR_DATE_ANSWER', title:'CR Date [ANSWER]', width:60, halign:'center', align:'right', editor:{type:'datebox',
																														 options:{formatter:myformatter,parser:myparser}}},
					{field:'ETD_ANSWER', title:'ETD [ANSWER]', width:60, halign:'center', align:'right', editor:{type:'datebox', 
																												 options:{formatter:myformatter,parser:myparser}}},
					{field:'ETA_ANSWER', title:'ETA [ANSWER]', width:60, halign:'center', align:'right', editor:{type:'datebox',
																												 options:{formatter:myformatter,parser:myparser}}}
	            ]],
	            onClickRow:function(row){
			    	$(this).datagrid('beginEdit', row);
			    }
	        });
		}

		function saveInq(value){
			if(value == 'add'){
				if (validate('add') != 1){
					$.messager.progress({
		                title:'Please waiting',
		                msg:'save data...'
		            });
		        }
			}else if (value == 'edit'){
				if (validate('edit') != 1){
					$.messager.progress({
		                title:'Please waiting',
		                msg:'save data...'
		            });
				}
			}
		}

		/*========================================== EDIT ============================================*/



		/*====================================== SET ================================================*/
		function sett_item_no(id_sts){
			$('#dlg_addItem').dialog('open').dialog('setTitle','Search Item');
			document.getElementById('s_item').value = '';
			var dg = $('#dg_addItem').datagrid();
			dg.datagrid('enableFilter');

			$('#dg_addItem').datagrid({
				fitColumns: true,
				columns:[[
	                {field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM',title:'ITEM',width:100,halign:'center'},
	                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
	                {field:'UNIT',title:'UoM',width:75,halign:'center', align:'center'}
	            ]],
	            onDblClickRow:function(id,row){
	            	if (id_sts == 'add'){
	            		var t = $('#dg_add').datagrid('getRows');
	            	}else{
	            		var t = $('#dg_edit').datagrid('getRows');
	            	}
					
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=0;
					}else{
						idxfield=total;
					}
					
					if (id_sts == 'add'){
						$('#dg_add').datagrid('insertRow',{
							index: idxfield,
							row: {
								ITEM_NO: row.ITEM_NO,
								DESCRIPTION: row.DESCRIPTION,
								ITEM: row.ITEM
							}
						});
						$('#savebtn_add').linkbutton('enable');
					}else{
						$('#dg_edit').datagrid('insertRow',{
							index: idxfield,
							row: {
								ITEM_NO: row.ITEM_NO,
								DESCRIPTION: row.DESCRIPTION,
								ITEM: row.ITEM
							}
						});
						$('#savebtn_edit').linkbutton('enable');
					}
				}
			});
		}

		function sch_item(event){
			var sch = document.getElementById('s_item').value;
			var search = sch.toUpperCase();
			document.getElementById('s_item').value = search;
			
		    if(event.keyCode == 13 || event.which == 13){
				search_item();
		    }
		}

		function search_item(){
			var sch = document.getElementById('s_item').value;
			if(sch != ''){
				$('#dg_addItem').datagrid('load', {
					s_item: document.getElementById('s_item').value
				});
				$('#dg_addItem').datagrid({url:'sales_inquiry_get_item.php'});
				document.getElementById('s_item').value = '';
			}
		}

	</script>

	</body>
    </html>
<?php }else{	
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}