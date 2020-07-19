<?php 
session_start();
require_once('../	.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Incoming Materials</title>
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
	<?php include ('../../ico_logout.php'); ?>
	<div id="toolbar">
		<a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="set_incoming()"><i class="fa fa-sliders" aria-hidden="true"></i> Set Pallet</a>
        <a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="destroy_incoming()"><i class="fa fa-reply" aria-hidden="true"></i> Reset Pallet</a>
        <a href="javascript:void(0)" style="width: 150px;" id="popUp" class="easyui-linkbutton c2" onClick="popUp()"><i class="fa fa-print" aria-hidden="true"></i> Print Pallet</a>	
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:100%;"><legend> Filter Goods Receiving </legend>
		<div style="float:left;">
			<div class="fitem">
				<span style="width:180px;display:inline-block;">Goods Receive List Date</span>
				<input style="width:107px;" name="dn_periode_awal" id="dn_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>" required/>
				<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:150px;"><i class="fa fa-search" aria-hidden="true"></i> FILTER</a>
			</div>
		</div>
		</fieldset>
	</div>

	<div id="progress" class="easyui-dialog" style="background-opacity:0.6; width:460px;padding:10px 20px" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false,closed:true">
		<div id="p" class="easyui-progressbar" data-options="value:0" style="width:400px;"></div>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:700px;padding:5px 5px" closed="true" buttons="#dlg-buttons" data-options="modal:true">
		<div id="toolbar_pallet">
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px;">
				<div class="fitem" id="info"><!-- id="info" -->
					<span style="width:120px;display:inline-block;">RECEIVE No.</span>
					<input style="width:250px;" name="gr_no" id="gr_no" class="easyui-textbox" disabled="" />
					<span style="display:inline-block;">LINE No.</span>
					<input style="width:100px;" name="line_no" id="line_no" class="easyui-textbox" disabled="" />
				</div>
				<div class="fitem">
					<span style="width:120px;display:inline-block;">ITEM</span>
					<input style="width:520px;" name="ITEM_NO" id="ITEM_NO" class="easyui-textbox" disabled="" />
				</div>
				<div class="fitem">
					<span style="width:120px;display:inline-block;">QTY</span>
					<input style="width:80px;" name="QTY" id="QTY" class="easyui-textbox" disabled="" />
					<span style="display:inline-block;">PALLET</span>
					<input style="width:80px;" name="pall" id="pall" class="easyui-textbox" required=""/>
					<span style="display:inline-block;">QTY/PALLET</span>
					<input style="width:100px;" name="qtypall" id="qtypall" class="easyui-textbox" required=""/>
					<span style="width:35px;display:inline-block;"></span>
					<a href="javascript:void(0)" id="sett_plt" class="easyui-linkbutton" iconCls='icon-ok' onClick="set_pallet()">Set Pallet</a>	
				</div>
			</fieldset>
		</div>
		<table id="dg_pallet" class="easyui-datagrid" toolbar="#toolbar_pallet" style="width:100%;height:300px;"></table>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" id="save_incoming" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="save_in()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>

	<div id="dlg_print" class="easyui-dialog" style="width:400px;padding:10px 20px" closed="true" buttons="#dlg-buttons-print" data-options="modal:true">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:325px;">
			<div class="fitem">
				<span style="width:120px;display:inline-block;">Receiver Person</span>
				<select style="width:200px;" name="dn_receive" id="dn_receive" class="easyui-combobox" data-options=" url:'../json/json_receiver.php',method:'get',valueField:'nama_receiver',textField:'nama_receiver', panelHeight:'150px'" required></select>
			</div>
		</fieldset>
	</div>
	<div id="dlg-buttons-print">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="printpdf()" style="width:90px">Print</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_print').dialog('close')" style="width:90px">Cancel</a>
	</div>

	<table id="dg" title="Incoming Materials" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;"></table>

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

		var url;
		var pdf_url='';
		var xls_url='';
		var total;

		$(function(){
			var dg = $('#dg').datagrid();
			$('#dg').datagrid('loadData',[]);

			$('#dg').datagrid({
			    toolbar: '#toolbar',
			    singleSelect: true,
				rownumbers: true,
				fitColumns: true,
				sortName: 'receive_date',
				sortOrder: 'desc',
			    columns:[[
			    	{field:'GR_NO',title:'Good Receive<br>No.', halign:'center', width:150},
			    	{field:'GR_DATE',title:'Good Receive<br>Date', halign:'center', align:'center', width:80},
	                {field:'LINE_NO', title:'Line', halign:'center', align:'center', width:40}, 
	                {field:'SUPPLIER_CODE',title:'Vendor<br>No.', halign:'center', align:'center', width:65},
	                {field:'COMPANY', title:'Vendor<br>Name', halign:'center', width:240},
	                {field:'ITEM_NO', title:'Material<br>No.', halign:'center', align:'center', width:80},
	                {field:'DESCRIPTION', title:'Material<br>Name', halign:'center', width:240},
	                {field:'QTY', title:'Good Receive<br>Qty', halign:'center', align:'right', width:80},
	                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:70},
	                {field:'PALLET', title:'Pallet', halign:'center', align:'right', width:70},
	                {field:'QTYPALLET', title:'Qty/Pallet', halign:'center', align:'right', width:70}
			    ]],
			    onclickRow:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    }
		    });

			$('#dg_pallet').datagrid({
				columns: [[
					{field:'ITEM_NO',title:'ITEM NAME', halign:'center', width:80},
					{field:'DESCRIPTION',title:'ITEM NAME', halign:'center', width:120},
					{field:'PALLET',title:'PALLET', halign:'center', align:'center', width:35},
					{field:'QTY',title:'QTY/PALLET', halign:'center', align:'right', width:80, editor:{type:'textbox'}}
				]],
			    onDblClickRow:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    }
			});
			document.getElementById('src').focus();
		})

		function filterData(){
			var t = $('#dn_periode_awal').datebox('getValue');
			if(t==''){
				$.messager.show({title: 'warning',msg: 'Please select period'});
			}else{
				$('#popUp').show();
				$('#saved').show();
				$('#dg').datagrid('load', {
					pd_periode_awal: $('#dn_periode_awal').datebox('getValue')
				});

				$('#dg').datagrid({
					url:'wms_get.php'
				});
			}

			var col1 = $('#dg').datagrid('getColumnOption','PALLET');
			col1.styler = function(){
				return 'background-color:#AAFFFF; color: #000000; font-weight:bold;';
			};

			var col2 = $('#dg').datagrid('getColumnOption','QTYPALLET');
			col2.styler = function(){
				return 'background-color:#AAFFFF; color: #000000; font-weight:bold;';
			};

			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		}

		function set_incoming(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				$('#dlg').dialog('open').dialog('setTitle','Input Goods Receive ('+row.GR_NO+')');
		    	$('#dg_pallet').datagrid('reload');
		    	$('#dg_pallet').datagrid('load',{gr: row.GR_NO, item: row.ITEM_NO, line: row.LINE_NO});
		    	$('#dg_pallet').datagrid({fitColumns: true, singleSelect: true, url: 'wms_get_dtl.php'});
		    	$('#gr_no').textbox('setValue',row.GR_NO);
		    	$('#line_no').textbox('setValue',row.LINE_NO);
		    	$('#ITEM_NO').textbox('setValue',row.ITEM_NO+'-'+row.DESCRIPTION);
		    	$('#QTY').textbox('setValue',row.QTY);

		    	$('#info').hide();

				if(row.PALLET=='0' || row.QTYPALLET=='0'){
			    	$('#pall').textbox('setValue','');
			    	$('#qtypall').textbox('setValue','');
			    	$('#sett_plt').linkbutton('enable');
			    	$('#save_incoming').linkbutton('enable');
				}else{
			    	$('#pall').textbox('setValue',row.PALLET);
			    	$('#qtypall').textbox('setValue',row.QTYPALLET);
			    	$('#sett_plt').linkbutton('disable');
			    	$('#save_incoming').linkbutton('disable');
				}
			}
		}

		function set_pallet(){
			var pall = $('#pall').textbox('getValue');
			var qtypall = $('#qtypall').textbox('getValue');
			var qty_gr = $('#QTY').textbox('getValue').replace(/,/g,'');
			var tot;
			if (pall=='' || qtypall==''){
				$.messager.alert("warning","Data Field Can't Empty!","Warning");
			}else{
				rows = $('#dg_pallet').datagrid('getRows');
				if(rows.length!= parseInt(pall)){
					for(i = 0;i < parseInt($('#pall').textbox('getValue')); i++){
						var itm = $('#ITEM_NO').textbox('getValue');
						var split_itm = itm.split("-");
						if(qty_gr!=0){
							if(i==(parseInt(pall)-1)){
								tot = qty_gr;
								$('#dg_pallet').datagrid('insertRow', {
								    index: i,
								    row: {
										ITEM_NO: split_itm[0],
										DESCRIPTION: split_itm[1],
										PALLET: i+1,
										QTY: tot
									}
								});
							}else{
								tot = parseInt(qty_gr) - parseInt(qtypall);
								$('#dg_pallet').datagrid('insertRow', {
								    index: i,
								    row: {
										ITEM_NO: split_itm[0],
										DESCRIPTION: split_itm[1],
										PALLET: i+1,
										QTY: $('#qtypall').textbox('getValue')
									}
								});
							}
							qty_gr=tot;
						}
					}
				}	
			}
		}

		var gr = '';
		var ln = '';
		var it = '';

		function save_in(){
			$.messager.progress({
			    title:'Please waiting',
			    msg:'Save data...'
			});

			var dataRows = [];
			var i = 0;
			var proc = 0;
			rows = $('#dg_pallet').datagrid('getRows');
			
			for(i=0;i<rows.length;i++){
				$('#dg_pallet').datagrid('endEdit', i);
				dataRows.push({
					wh_grn: $('#gr_no').textbox('getValue'),
					wh_line: $('#line_no').textbox('getValue'),
					wh_qty: $('#QTY').textbox('getValue').replace(/,/g,''),
					wh_item: $('#dg_pallet').datagrid('getData').rows[i].ITEM_NO,
					wh_pallet: $('#dg_pallet').datagrid('getData').rows[i].PALLET,
					wh_qtypallet: $('#dg_pallet').datagrid('getData').rows[i].QTY
				});

				gr = $('#gr_no').textbox('getValue');
				ln = $('#line_no').textbox('getValue');
				it = $('#dg_pallet').datagrid('getData').rows[i].ITEM_NO;
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);

			$.post('wms_save.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Data Saved!','info');
					$.messager.progress('close');
				}else{
					$.post('wms_destroy.php',{gr: gr, ln: ln, item:it},'json');
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}

		function destroy_incoming(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				$.messager.confirm('Confirmation','Are you sure you want to destroy pallet?',function(r){
                    if (r){
                        $.post('wms_destroy.php',{gr:row.GR_NO, ln:row.LINE_NO, item:row.ITEM_NO},function(result){
                            //alert(result);
                            if (result.success){
                                $('#dg').datagrid('reload');    // reload the user data
                            }else{
                                $.messager.show({    // show error message
                                    title: 'Error',
                                    msg: result.errorMsg
                                });
                            }
                        },'json');
                    }
                });
			}
		}

		function popUp(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				$('#dlg_print').dialog('open').dialog('setTitle','Receive Person ('+row.GR_NO+' - '+row.ITEM_NO+')');
				$('#dn_receive').combobox('clear');
				pdf = "?grn="+row.GR_NO+"&item="+row.ITEM_NO+"&line="+row.LINE_NO
			}else{
				$.messager.show({title: 'PDF Report',msg: 'Data Not select'});
			}
		}

		function printpdf(){
			var rec = $('#dn_receive').combobox('getValue');
			if(rec==''){
				$.messager.show({title: 'PDF Report',msg: 'Data Not select'});	
			}else{
				// FORMAT 2: 10x11
				pdf_url = pdf+"&receive="+$('#dn_receive').combobox('getText')
				$('#dlg_print').dialog('close');
				window.open('wms_print2.php'+pdf_url);
			}
		}
	</script>
    </body>
</html>