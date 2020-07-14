<?php include("../connect/koneksi.php");
session_start();

?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>RECEIVING REPORT</title>
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
	<div id="toolbar">
		<a href="javascript:void(0)" id="addDN" class="easyui-linkbutton" plain="true" iconCls="icon-add" onclick="add_rr()">Add</a>
		<a href="javascript:void(0)" id="editDN" class="easyui-linkbutton" plain="true" iconCls="icon-edit" onclick="edit_rr()">Edit</a>
		<a href="javascript:void(0)" id="deleteDN" class="easyui-linkbutton" plain="true" iconCls="icon-remove" onclick="hapus_rr()">Delete</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="pdf_all_rr()">PDF</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-print" onclick="pdf_detail_rr()">PDF Detail</a>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:1070px;"><legend>Filter Supplier</legend>
		<div style="width:380px; float:left;">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Period</span>
				<input style="width:91px;" name="dn_periode_awal" id="dn_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
				to 
				<input style="width:91px;" name="dn_periode_akhir" id="dn_periode_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
				<input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Supplier</span>
				<select style="width:202px;" name="dn_supp" id="dn_supp" class="easyui-combobox" data-options=" url:'json/json_supplier.php',method:'get',valueField:'kode_supplier',textField:'nama_supplier', panelHeight:'150px'"></select>
				<input type="checkbox" name="ck_supp" id="ck_supp" checked="true">All</input>
			</div>				
		</div>
		<div class="fitem" style="margin-left:105px;">
			<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton" onClick="filterData()" style="width:100px;">FILTER</a>
		</div>
		</fieldset>
	</div>
	<table id="dg" title="RECEIVING REPORT" class="easyui-datagrid" toolbar="#toolbar	" style="width:1100px;height:500px;"></table>

	<!-- START ADD -->
	<div id="dlg" class="easyui-dialog" style="width:950px;height:470px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:350px; float:left;">
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Date</span>
				<input style="width:100px;" name="a_tgl" id="a_tgl" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Receiving No.</span>
				<!-- <select style="width:202px;" name="a_rec_no" id="a_rec_no" class="easyui-combobox" 
				data-options=" url:'json/json_receive_report.php',method:'get',
				valueField:'rec_no',textField:'rec_no', panelHeight:'150px'

				"></select> -->
	            <input id="a_rec_no" name="a_rec_no" style="width:230px" required="true">
			</div>
			<div class="fitem"></div><br/>
			<div class="fitem"></div><div class="fitem"></div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:500px;margin-left: 370px;">
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Receiving Report No.</span>
				<input required="true" style="width:150px;" name="a_rr_no" id="a_rr_no" class="easyui-textbox" disabled="disabled" data-options="" />
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">PO No.</span>
				<input style="width:300px;" name="a_po_no" id="a_po_no" class="easyui-textbox" disabled="disabled" data-options=""/>
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Supplier</span>
				<input style="width:300px;" name="a_supp" id="a_supp" class="easyui-textbox" disabled="disabled" data-options="" />
			</div>
			<div class="fitem"></div>
		</fieldset>
		<div style="clear:both;margin-bottom:15px;"></div>
		<table id="dg_entry" class="easyui-datagrid" style="width:900px;height:250px;"></table>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveAdd()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<!-- END ADD -->

	<!-- START EDIT -->
	<div id="dlg_edit" class="easyui-dialog" style="width:950px;height:470px;padding:10px 20px" closed="true" buttons="#dlg_e-buttons">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:350px; float:left;">
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Date</span>
				<input style="width:100px;" name="e_tgl" id="e_tgl" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Receiving No.</span>
				<input id="e_rec_no" name="e_rec_no" style="width:230px" disabled="disabled" required="true">
			</div>
			<div class="fitem"></div><br/>
			<div class="fitem"></div><div class="fitem"></div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:500px;margin-left: 370px;">
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Receiving Report No.</span>
				<input required="true" style="width:150px;" name="e_rr_no" id="e_rr_no" class="easyui-textbox" disabled="disabled" data-options="" />
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">PO No.</span>
				<input style="width:300px;" name="e_po_no" id="e_po_no" class="easyui-textbox" disabled="disabled" data-options=""/>
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Supplier</span>
				<input style="width:300px;" name="e_supp" id="e_supp" class="easyui-textbox" disabled="disabled" data-options="" />
			</div>
			<div class="fitem"></div>
		</fieldset>
		<div style="clear:both;margin-bottom:15px;"></div>
		<table id="dg_entry_edit" class="easyui-datagrid" style="width:900px;height:250px;"></table>
	</div>
	<div id="dlg_e-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveEdit()" style="width:90px">Save</a>
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

		/*$('#a_rec_no').combogrid({
    		panelWidth:450,
		    url: 'json/json_receive_report.php',
		    idField:'rec_no',
		    textField:'rec_no',
		    mode:'remote',
		    fitColumns:true,
		    columns:[[
			    {field:'rec_no',title:'Doc. No.',width:150,halign:'center'},
                {field:'po_no',title:'PO No.',width:140,halign:'center'},
                {field:'supplier',title:'Supplier',width:250,halign:'center'}
			]],
			
			onClickRow: function(rec){
				var g = $('#a_rec_no').combogrid('grid');
				var r = g.datagrid('getSelected');
				$('#a_po_no').textbox('setValue',r.po_no);
				$('#a_supp').textbox('setValue', r.supplier);
				$('#dg_entry').datagrid({
					url: 'receive_report_add_get.php?rec_no='+$('#a_rec_no').combogrid('getValue')+'&po_no='+$('#a_po_no').textbox('getText')
						+'&supp='+$('#a_supp').textbox('getText')
				});
			}

   		});*/

   		$('#e_rec_no').combogrid({
    		panelWidth:450,
		    url: 'json/json_receive_report.php',
		    idField:'rec_no',
		    textField:'rec_no',
		    mode:'remote',
		    fitColumns:true,
		    columns:[[
			    {field:'rec_no',title:'Doc. No.',width:150,halign:'center'},
                {field:'po_no',title:'PO No.',width:140,halign:'center'},
                {field:'supplier',title:'Supplier',width:250,halign:'center'}
			]],
			
			onClickRow: function(rec){
				var g = $('#e_rec_no').combogrid('grid');
				var r = g.datagrid('getSelected');
				$('#e_po_no').textbox('setValue',r.po_no);
				$('#e_supp').textbox('setValue', r.supplier);
				$('#dg_entry').datagrid({
					url: 'receive_report_add_get.php?rec_no='+$('#e_rec_no').combogrid('getValue')+'&po_no='+$('#a_po_no').textbox('getText')
						+'&supp='+$('#e_supp').textbox('getText')
				});
			}

   		});

		$(function(){
			$('#dn_periode_awal').datebox('disable');
			$('#dn_periode_akhir').datebox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#dn_periode_awal').datebox('disable');
					$('#dn_periode_akhir').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#dn_periode_awal').datebox('enable');
					$('#dn_periode_akhir').datebox('enable');
				};
			})

			$('#dn_supp').combobox('disable');
			$('#ck_supp').change(function(){
				if ($(this).is(':checked')) {
					$('#dn_supp').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					//alert('HOREEEEEE!!!');
					$('#dn_supp').combobox('enable');					
				};
			})

			$('#a_rec_no').combogrid({

				/*var g = $('#a_rec_no').combogrid('grid');
					var r = g.datagrid('getSelected');
					$('#a_po_no').textbox('setValue',r.po_no);
					$('#a_supp').textbox('setValue', r.supplier);
					$('#dg_entry').datagrid({
						url: 'receive_report_add_get.php?rec_no='+$('#a_rec_no').combogrid('getValue')+'&po_no='+$('#a_po_no').textbox('getText')
							+'&supp='+$('#a_supp').textbox('getText')
				});*/
				panelWidth:450,
			    url: 'json/json_receive_report.php',
			    idField:'rec_no',
			    textField:'rec_no',
			    mode:'remote',
			    fitColumns:true,
			    columns:[[
				    {field:'rec_no',title:'Doc. No.',width:150,halign:'center'},
	                {field:'po_no',title:'PO No.',width:140,halign:'center'},
	                {field:'supplier',title:'Supplier',width:250,halign:'center'}
				]],

				onClickRow: function(rec){
					var g = $('#a_rec_no').combogrid('grid');
					var r = g.datagrid('getSelected');
					$('#a_po_no').textbox('setValue',r.po_no);
					$('#a_supp').textbox('setValue', r.supplier);
					$('#dg_entry').datagrid({
							url: 'receive_report_add_get.php?rec_no='+$('#a_rec_no').combogrid('getValue')+'&po_no='+$('#a_po_no').textbox('getText')
								+'&supp='+$('#a_supp').textbox('getText')
					});
				}
			})

			$('#dg').datagrid({
			    url:'receive_report_get.php',
			    toolbar: '#toolbar',
			    singleSelect: true,
				pagination: true,
				rownumbers: true,
				sortName: 'receive_date',
				sortOrder: 'desc',
			    columns:[[
			    	{field:'receive_date',title:'Date',halign:'center', width:100},
				    {field:'rr_no',title:'Receiving<br>Report No.',width:160, halign:'center', sortable: true},
				    {field:'nama_supplier',title:'Supplier', halign:'center', width:300},
				    {field:'rec_no',title:'Receiving No.',halign:'center', width:300},
				    {field:'last_update',title:'Entry Date', halign:'center', width:100},
				    {field:'user_entry',title:'User',halign:'center', width:60},
				    {field:'po_no',title:'po_no',halign:'center', width:70, hidden:true}
			    ]],
				onClickRow: function(rec){
					var row = $('#dg').datagrid('getSelected');
				},
				view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<div style="padding:10px" id="tbdetail'+rowIndex+'"></div><div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg" width="700px"></table></div>';
					},
			   
				onExpandRow: function(index,row){
					var uri_doc = encodeURIComponent(row.rr_no);
					var gcd_doc = $.trim(row.rec_no);
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');

					listbrg.datagrid({
						title: 'Receiving Report Detail (RR No.: '+row.rr_no+')',
						url:'receive_report_detail_get.php?rr_no='+uri_doc,
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
							{field:'serial_no', title:'Serial No.', halign:'center', align:'left', width:100},
							{field:'part_no', title:'Part No.', halign:'center', align:'left', width:100},
							{field:'part_name', halign:'center', title:'Part Name', width:310},
							{field:'qty_trans', title:'QTY Delivery',align:'right', width:80},
							{field:'qty_reject', title:'Qty Ng',align:'right', width:80}
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
				
		    });

		    $('#dg_entry').datagrid({
				url:'',
			    toolbar: '#entoolbar',
			    singleSelect: true,
				pagination: true,
				rownumbers: true,
				//remoteFilter: true,
				fitColumns: true,
				sortName: 'part_no',
				sortOrder: 'asc',
			    columns:[[
					{field:'serial_no',title:'Serial No', halign:'center', width:180, sortable: true},
				    {field:'part_no', title:'Part No', halign:'center', width:100}, 
				    {field:'part_name',title:'Part Name', halign:'center', width:280, sortable: true},
					{field:'stock_in', title:'Qty Receiving', halign:'center', width:100, align:'right', editor:{type:'numberbox',options:{disabled:true}}},
					{field:'qty_reject', title:'Qty Ng', halign:'center', width:100, align: 'right', editor:{type:'numberbox'}},
					{field:'supplier_name', title:'supplier', halign:'center', hidden: true}, 
					{field:'nama_supplier', title:'Supplier Name', halign:'center', hidden: true} //, hidden: true
			    ]],
			    //<th data-options="field:'unitcost',width:80,align:'right',editor:'numberbox'">Unit Cost</th>
			    //{field:'d_pu_rate_tax',title:'Exchange Rate', align:'right', width:150, editor:{type:'numberbox',options:{precision:2, groupSeparator:',', value:'12.34'}}, halign: 'center'}
			    //{field:'po_qty', halign:'center', title:'Qty',width:80, align:'right', editor:{type:'numberbox',options:{precision:2}}},
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
			    },
			    onBeginEdit:function(rowIndex){
			    	var editors = $('#dg_entry').datagrid('getEditors', rowIndex);
			       	var n1 = $(editors[0].target);
			        var n2 = $(editors[1].target);
			        var n3 = $(editors[2].target);
			        var temp = $('#dg_entry').datagrid('getData').rows[rowIndex].temp;
			        	n1.add(n2).numberbox({
			        		onChange:function(index, row){

				        		if (parseInt(n2.numberbox('getValue')) > parseInt(n1.numberbox('getValue'))) {
				        			var c = 1;
				        			n3.numberbox('setValue', c);
				        			alert("Qty Ng exceed Qty Delivery!");
				        		};
			        			
			        			/*var c = parseInt(n1.numberbox('getValue')) - parseInt(n2.numberbox('getValue'));*/
			        			
			        		}
			        	});
			    }
			});

			$('#dg_entry_edit').datagrid({
				url:'',
			    toolbar: '#entoolbar',
			    singleSelect: true,
				pagination: true,
				rownumbers: true,
				//remoteFilter: true,
				fitColumns: true,
				sortName: 'part_no',
				sortOrder: 'asc',
			    columns:[[
					{field:'serial_no',title:'Serial No', halign:'center', width:180, sortable: true},
				    {field:'part_no', title:'Part No', halign:'center', width:100}, 
				    {field:'part_name',title:'Part Name', halign:'center', width:280, sortable: true},
					{field:'stock_in', title:'Qty Receiving', halign:'center', width:100, align:'right', editor:{type:'numberbox',options:{disabled:true}}},
					{field:'qty_reject', title:'Qty Ng', halign:'center', width:100, align: 'right', editor:{type:'numberbox'}},
					{field:'kode_supp', title:'supplier', halign:'center', hidden: true}, 
					{field:'nama_supplier', title:'Supplier Name', halign:'center', hidden: true} //, hidden: true
			    ]],
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
			    },
			    onBeginEdit:function(rowIndex){
			    	var editors = $('#dg_entry').datagrid('getEditors', rowIndex);
			       	var n1 = $(editors[0].target);
			        var n2 = $(editors[1].target);
			        var n3 = $(editors[2].target);
			        var temp = $('#dg_entry').datagrid('getData').rows[rowIndex].temp;
			        	n1.add(n2).numberbox({
			        		onChange:function(index, row){

				        		if (parseInt(n2.numberbox('getValue')) > parseInt(n1.numberbox('getValue'))) {
				        			var c = 1;
				        			n3.numberbox('setValue', c);
				        			alert("Qty Ng exceed Qty Delivery!");
				        		};
			        			
			        			/*var c = parseInt(n1.numberbox('getValue')) - parseInt(n2.numberbox('getValue'));*/
			        			
			        		}
			        	});
			    }
			});
		})

		var ppdf='';

		function filterData(){
			var ck_date = 'false';
			var ck_supp = 'false';

			if ($('#ck_date').attr("checked")) {
				ck_date = "true";
			}
			if($('#ck_supp').attr("checked")) {
				ck_supp = "true";
			}
			$('#dg').datagrid('load', {
				pd_periode_awal: $('#dn_periode_awal').datebox('getValue'),
				pd_periode_akhir: $('#dn_periode_akhir').datebox('getValue'),
				ck_date: ck_date,
				dn_supp: $('#dn_supp').combobox('getValue'),
				ck_supp: ck_supp
			});

			ppdf = "?pd_periode_awal="+$('#dn_periode_awal').datebox('getValue')+
				"&pd_periode_akhir="+$('#dn_periode_akhir').datebox('getValue')+
				"&ck_date="+ck_date+
				"&dn_supp="+$('#dn_supp').combobox('getValue')+
				"&ck_supp="+ck_supp
		}

		$(function(){
			filterData();
		})

		function add_rr(){
			$('#dlg').dialog('open').dialog('setTitle','Add Receiving Report');
			$('#a_tgl').datebox('setValue','<?=date('Y-m-d')?>');
			$('#a_rec_no').textbox('setValue', '');
			$('#a_po_no').textbox('setValue', '');
			$('#a_supp').textbox('setValue', '');
			$('#a_rr_no').textbox('setValue', '');
			$('#dg_entry').datagrid('loadData',[]);
		}

		function simpan(){
			var tgl = $('#a_tgl').datebox('getValue');
			var rc = $('#a_rec_no').textbox('getValue');
			var rr = $('#a_rr_no').textbox('getText');
			var pono = $('#a_po_no').textbox('getText');

			if(rr=='' || tgl=='' || rc=='') {
				$.messager.alert("warning","Required Field Can't Empty!","Warning");
			}else{
				var total1 = $('#dg_entry').datagrid('getData').total;
				for(i=0;i<total1;i++){
					$('#dg_entry').datagrid('endEdit',i);
					$.post('receive_report_save.php',{
						tgl: $('#a_tgl').datebox('getValue'),
						rr_no: $('#a_rr_no').textbox('getText'),
						po_no: $('#a_po_no').textbox('getText'),
						rec: $('#a_rec_no').textbox('getValue'),

						serial_no: $('#dg_entry').datagrid('getData').rows[i].serial_no,
						part_no: $('#dg_entry').datagrid('getData').rows[i].part_no,
						part_name: $('#dg_entry').datagrid('getData').rows[i].part_name,
						stock_in: $('#dg_entry').datagrid('getData').rows[i].stock_in,
						qty_reject: $('#dg_entry').datagrid('getData').rows[i].qty_reject,
						supplier_name: $('#dg_entry').datagrid('getData').rows[i].supplier_name
					}).done(function(res){
						//alert(res);
						$('#dlg').dialog('close');
						$('#dg').datagrid('reload');
					})
				}
			}
		}

		function saveAdd(){
			var url='';
			$.ajax({
				type: 'GET',
				url: 'json/json_kode_rr.php',
				data: { kode:'kode' },
				success: function(data){
					$('#a_rr_no').textbox('setValue', data[0].kode);
					simpan();
				}
			});
		}

		function edit_rr(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				$('#dlg_edit').dialog('open').dialog('setTitle', 'EDIT RECEIVE REPORT');
				$('#e_tgl').datebox('setValue', row.receive_date);
				$('#e_rec_no').textbox('setValue',row.rec_no);
				$('#e_rr_no').textbox('setValue',row.rr_no);
				$('#e_po_no').textbox('setValue',row.po_no);
				$('#e_supp').textbox('setValue',row.nama_supplier);
				$('#dg_entry_edit').datagrid('loadData', []);
				$('#dg_entry_edit').datagrid({
					url:'receive_report_getedit.php?rr_no='+row.rr_no 
				});
			}
		}

		function saveEdit(){
			var total2 = $('#dg_entry_edit').datagrid('getData').total;
			for(i=0;i<total2;i++){
				$('#dg_entry_edit').datagrid('endEdit',i);
				$.post('receive_report_update.php',{
					tgl: $('#e_tgl').datebox('getValue'),
					rr_no: $('#e_rr_no').textbox('getText'),
					po_no: $('#e_po_no').textbox('getText'),
					rec: $('#e_rec_no').textbox('getValue'),

					serial_no: $('#dg_entry_edit').datagrid('getData').rows[i].serial_no,
					part_no: $('#dg_entry_edit').datagrid('getData').rows[i].part_no,
					part_name: $('#dg_entry_edit').datagrid('getData').rows[i].part_name,
					stock_in: $('#dg_entry_edit').datagrid('getData').rows[i].stock_in,
					qty_reject: $('#dg_entry_edit').datagrid('getData').rows[i].qty_reject,
					supplier_name: $('#dg_entry_edit').datagrid('getData').rows[i].supplier_name
				}).done(function(res){
					//alert(res);
					$('#dlg_edit').dialog('close');
					$('#dg').datagrid('reload');
				})
			}
		}

		function hapus_rr(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				$.messager.confirm('Confirm','Are you sure you want to Delete?',function(r){
					if (r){
						$.post('receive_report_delete.php',{rr_no:row.rr_no},function(result){
							if (result.success){
								$('#dg').datagrid('reload');
							} else {
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

		function pdf_all_rr(){
			if(ppdf=='') {
				$.messager.show({
					title: 'PDF Report',
					msg: 'Data Not Defined'
				});
			} else {
				window.open('receive_report_pdf.php'+ppdf);
			}
		}

		function pdf_detail_rr(){
			if(ppdf=='') {
				$.messager.show({
					title: 'PDF Report',
					msg: 'Data Not Defined'
				});
			} else {
				window.open('receive_report_dtl_pdf.php'+ppdf);
			}
		}
	</script>
    </body>
    </html>