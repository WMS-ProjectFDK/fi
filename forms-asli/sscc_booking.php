<?php 
session_start();
include("../connect/conn.php");
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
if ($varConn=='Y'){
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
<title>MATERIALS TRANSACTION APPROVE</title>
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
</style>
</head>
    <body>
	<?php include ('../ico_logout.php');?>
		<div>
		 <fieldset style="position: absolute; float: left;margin-left:5px;border-radius:4px;width: 37%;height: 465px;"><legend><span class="style3"><strong>BOOKING NO. AND CONTAINER NO.</strong></span></legend>
		 	<div class="fitem">
				<span style="width:100px;display:inline-block;">BOOKING NO.</span>
				<input style="width:385px;height: 20px;border: 1px solid #0099FF;border-radius: 5px;" name="booking_no" id="booking_no" onkeypress="filter(event)" required="" autofocus="autofocus" />
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;"></span>
				<span style="color: red; font-size: 8px;">*: press enter to input container no.</span>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">CONTAINER NO.</span>
				<input style="width:350px;height: 20px;border: 1px solid #0099FF;border-radius: 5px;" name="container_no" id="container_no" onkeypress="filter2(event)" required=""/>
				<a href="javascript:void(0)" style="width: 30px;" id="search_all_wo_btn" iconCls='icon-add' class="easyui-linkbutton c2" onclick="add_wo()"></a>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;"></span>
				<span style="color: red; font-size: 8px;">*: press enter to lock booking</span>
			</div>
			<div class="fitem">
				<a href="javascript:void(0)" style="width: 205px;" id="search_all_wo_btn" iconCls='icon-add' class="easyui-linkbutton c2" onclick="search_book_all()" disabled="true" >SEARCH ALL BOOKING</a>
				<a href="javascript:void(0)" style="width: 150px;" id="search_all_wo_btn" iconCls='icon-cancel' class="easyui-linkbutton c2" onclick="delete_book()" disabled="true">DELETE BOOKING</a>
			</div>
			<hr/>
			<br/>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">WO NO.</span>
				<input style="width:385px;" name="wo_no" id="wo_no" class="easyui-textbox" disabled="true" />
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">ITEM NO.</span>
				<input style="width:100px;" name="item_no" id="item_no" class="easyui-textbox" disabled="true" />
				<input style="width:280px;" name="item_name" id="item_name" class="easyui-textbox" disabled="true"/>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">ASIN</span>
				<input style="width:385px;" name="asin" id="asin" class="easyui-textbox" disabled="true"/>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">AMAZON PO</span>
				<input style="width:385px;" name="amz_po" id="amz_po" class="easyui-textbox" disabled="true"/>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">TOTAL PALLET</span>
				<input style="width:100px;" name="total_plt" id="total_plt" class="easyui-textbox" disabled="true"/>
			</div>
			<div class="fitem">
				<a href="javascript:void(0)" style="width: 205px;" id="add_woSSCC" iconCls='icon-add' class="easyui-linkbutton c2" onclick="add_wo_sscc()" disabled="true"> ADD PALLET </a>
			</div>
			<br/>
			<hr/>
			<br/>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" id="sscc_xls_btn" onclick="sscc_xls()" iconCls="icon-excel" plain="true" style="width:205px" disabled="true" >DOWNLOAD TO EXCEL</a>
		 </fieldset>

		 <fieldset style="position:absolute;margin-left:40%;border-radius:4px;width: 55%;height: 465px;"><legend><span class="style3"><strong>LIST OF IN BOOKING</strong></span></legend>
			<table id="dg" class="easyui-datagrid" data-options="method: 'get', fitColumns: false, height: '450',width:'100%', singleSelect: true, rownumbers: true">
		        <thead>
		            <tr>
						<th halign="center" data-options="field:'ID', hidden: true">ID</th>
						<th halign="center" data-options="field:'BOOKING', width: 90">BOOKING</th>
						<th halign="center" data-options="field:'CONTAINER', width: 90">CONTAINER</th>
						<th halign="center" data-options="field:'WO', width: 150">WO NO.</th>
						<th halign="center" data-options="field:'ITEM', width: 50, align:'center'">ITEM<br/>NO.</th>
						<th halign="center" data-options="field:'DESCRIPTION', width: 200">BRAND</th>
						<th halign="center" data-options="field:'PO', width: 80">PO</th>
						<th halign="center" data-options="field:'ASIN', width: 100">ASIN</th>
						<th halign="center" data-options="field:'PALLET', width: 50, align:'center'">PALLET<br/>NO.</th>
		            </tr>
		        </thead>
		    </table>
		</fieldset>

		<!-- SETT SEARCH WO -->
		<div id="dlg_wo" class="easyui-dialog" style="width: 1200px;height: 400px;" closed="true" closable="true" data-options="modal:true">
			<table id="dg_wo" class="easyui-datagrid" toolbar="#toolbar_wo" style="width:100%;height:100%;border-radius: 10px;"></table>
		</div>
		<div id="toolbar_wo" style="padding: 5px 5px;">
			<span style="width:80px;display:inline-block;">Search</span>
			<input style="width:300px;height: 20px;border: 1px solid #0099FF;border-radius: 5px;" name="search_wo" id="search_wo" onkeypress="searchWoKeypress(event)" required="true" />
		</div>
		<!-- END SEARCH WO -->

		<!-- SETT WO SSCC -->
		<div id="dlg_wosscc" class="easyui-dialog" style="width: 500px;height: 500px;" closed="true" closable="true" data-options="modal:true">
			<table id="dg_wosscc" class="easyui-datagrid" style="width:100%;height:91%;border-radius: 10px;"></table>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 5px 5px;">
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="add_pallet()" iconCls="icon-add" style="width:140px"> ADD PALLET </a>
			</div>
		</div>
		<!-- END WO SSCC -->

	</div>

	<script type="text/javascript">
		var src='';			var src2='';
		function filter(event){
			src = document.getElementById('booking_no').value;
			var search1 = src.toUpperCase();
			document.getElementById('booking_no').value = search1;
			
		    if(event.keyCode == 13 || event.which == 13){
				src = document.getElementById('booking_no').value;
				// alert(src);
				document.getElementById('container_no').focus();
		    }
		}

		function filter2(event){
			src2 = document.getElementById('container_no').value;
			var search2 = src2.toUpperCase();
			document.getElementById('container_no').value = search2;
			
		    if(event.keyCode == 13 || event.which == 13){
				src2 = document.getElementById('container_no').value;
				
				var n1 = document.getElementById('booking_no').value;
				var n2 = document.getElementById('container_no').value;

				if (n1!='' || n2!=''){
					$('#dlg_wo').dialog('open').dialog('setTitle','SEARCH WO');
					document.getElementById('search_wo').focus();
				}else{
					$.messager.alert("INFORMATION",'booking no. or container no. not found','info');
				}
		    }
		}

		function add_wo(){
			var n1 = document.getElementById('booking_no').value;
			var n2 = document.getElementById('container_no').value;

			if (n1!='' || n2!=''){
				$('#dlg_wo').dialog('open').dialog('setTitle','SEARCH WO');
				document.getElementById('search_wo').focus();
			}else{
				$.messager.alert("INFORMATION",'booking no. or container no. not found','info');
			}
		}

		function searchWoKeypress(event){
			var sch = document.getElementById('search_wo').value;
			var searchWO = sch.toUpperCase();
			document.getElementById('search_wo').value = searchWO;
			
		    if(event.keyCode == 13 || event.which == 13){
				var src = document.getElementById('search_wo').value;

				$('#dg_wo').datagrid('load', {
					src: src
				});

				$('#dg_wo').datagrid('enableFilter');
		    }
		}

		$(function(){
			$('#dg_wo').datagrid({
				url:'sscc_booking_getWo.php',
		    	singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
			    columns:[[
				    {field:'WORK_ORDER',title:'WO<br/>NO.',width:100, halign: 'center'},
				    {field:'ITEM_NO',title:'ITEM<br/>NO',width:70, halign: 'center', align: 'center'},
				    {field:'DESCRIPTION',title:'DESCRIPTION',width:170, halign: 'center'},
				    {field:'ASIN',title:'ASIN',width:80, halign: 'center', align:'center'},
				    {field:'AMAZON_PO_NO',title:'AMAZON<br>PO NO.',width:80, halign: 'center', align: 'center'},
				    {field:'TOTALPALLET',title:'TOTAL PALLET',width:80, halign: 'center', align:'right'}
			    ]],
			    onDblClickRow:function(id,row){
	            	var rows = $('#dg_wo').datagrid('getSelected');

					$('#wo_no').textbox('setValue',rows.WORK_ORDER);
					$('#item_no').textbox('setValue',rows.ITEM_NO);
					$('#item_name').textbox('setValue',rows.DESCRIPTION);
					$('#asin').textbox('setValue',rows.ASIN);
					$('#amz_po').textbox('setValue',rows.AMAZON_PO_NO);
					$('#total_plt').textbox('setValue',rows.TOTALPALLET);

	            	$('#dlg_wo').dialog('close');
	            	$('#search_wo_btn').linkbutton('enable');
					$('#add_woSSCC').linkbutton('enable');
				}
			});

			$('#dg_wosscc').datagrid({
				fitColumns: true,
				singleSelect: false,
				checkOnSelect: true,
	    		selectOnCheck: true,
				columns:[[
					{field:'ck', checkbox:true, halign: 'center'},
					{field:'pallet',title:'PALLET',width:35, halign: 'center'},
					{field:'booking',title:'BOOKING',width:120, halign: 'center'},
					{field:'container',title:'CONTAINER',width:120, halign: 'center'},
					{field:'wo_no', hidden: true},
					{field:'item_no', hidden: true},
					{field:'item_name', hidden: true},
					{field:'asin', hidden: true},
					{field:'amz_po', hidden: true}
				]]
			});
		});

		function add_wo_sscc(){
			$('#dlg_wosscc').dialog('open').dialog('setTitle','SELECT PALLET');
			
			var arr = [];
			var total = $('#total_plt').textbox('getValue');
			var n1 = document.getElementById('booking_no').value;
			var n2 = document.getElementById('container_no').value;
			var wo_no = $('#wo_no').textbox('getValue');
			var item_no = $('#item_no').textbox('getValue');
			var item_name = $('#item_name').textbox('getValue');
			var asin = $('#asin').textbox('getValue');
			var amz_po = $('#amz_po').textbox('getValue');
			
			for(i=1; i<=total; i++){
				arr.push({pallet: i, 
						  booking: n1, 
						  container: n2,
						  wo_no: wo_no, 
						  item_no: item_no, 
						  item_name: item_name, 
						  asin: asin, 
						  amz_po: amz_po
						});				
			}

			$('#dg_wosscc').datagrid('loadData', arr);
			//console.log(arr);
		}

		function add_pallet(){
			var ArrApprove = [];
			var rows = $('#dg_wosscc').datagrid('getSelections');
			var count = 0;

			for(i=0;i<rows.length;i++){
				$('#dg_wosscc').datagrid('endEdit',i);

				var t = $('#dg').datagrid('getRows');
				var total = t.length;
			   	var idxfield=0;
			   	var j = 0;
			   	if (parseInt(total) == 0) {
					idxfield=0;
				}else{
					idxfield=total+1;
					for (j=0; j < total; j++) {
						var id = $('#dg').datagrid('getData').rows[j].ID;
						if (id == rows[i].wo_no+rows[i].pallet){
							count++;
						};
					};
				}

				if (count>0) {
					$.messager.alert('Warning','PALLET NO. Present','warning');
				}else{
					$('#dg').datagrid('insertRow',{
						index: idxfield,
						row: {
							ID: rows[i].wo_no+rows[i].pallet,
							BOOKING: rows[i].booking,	
							CONTAINER: rows[i].container,
							WO: rows[i].wo_no ,
							ITEM: rows[i].item_no ,
							DESCRIPTION: rows[i].item_name ,
							PO: rows[i].amz_po,
							ASIN: rows[i].asin,
							PALLET: rows[i].pallet 
						}
					});

					ArrApprove.push({ID: rows[i].wo_no+rows[i].pallet});
				}
				
			}

			$('#dlg_wosscc').dialog('close');
			$('#sscc_xls_btn').linkbutton('enable');

			//console.log(ArrApprove);
		}

		function sscc_xls(){
			$.messager.progress({
                title:'Please waiting',
                msg:'Loading data...'
            });

			var dataCarton = [];
			var dataPallet = [];
			var t = $('#dg').datagrid('getRows');
			var total = t.length;
			var w = '';		var p = '';		var c = '';		var b = '';

			// loop pallet
			for(i=0;i<total;i++){
				$('#dg').datagrid('endEdit',i);
				w = $('#dg').datagrid('getData').rows[i].WO.trim();
				p = $('#dg').datagrid('getData').rows[i].PALLET;
				c = $('#dg').datagrid('getData').rows[i].CONTAINER.trim();
				b = $('#dg').datagrid('getData').rows[i].BOOKING.trim();

				dataCarton.push({
			 		crt_wo: w,
			 		crt_plt: p,
			 		crt_book: $('#dg').datagrid('getData').rows[i].BOOKING.trim(),
			 		crt_cont: $('#dg').datagrid('getData').rows[i].CONTAINER.trim(),
			 		crt_po: $('#dg').datagrid('getData').rows[i].PO.trim(),
			 		crt_asin: $('#dg').datagrid('getData').rows[i].ASIN.trim()
			 	});

				dataPallet.push({
					plt_Booking: $('#dg').datagrid('getData').rows[i].BOOKING.trim(),
					plt_Container: $('#dg').datagrid('getData').rows[i].CONTAINER.trim(),
					plt_WO: $('#dg').datagrid('getData').rows[i].WO.trim(),
					plt_Item: $('#dg').datagrid('getData').rows[i].ITEM,
					plt_PO: $('#dg').datagrid('getData').rows[i].PO.trim(),
					plt_ASIN: $('#dg').datagrid('getData').rows[i].ASIN.trim(),
					plt_PALLET: $('#dg').datagrid('getData').rows[i].PALLET,
					plt_ID: $('#dg').datagrid('getData').rows[i].WO+$('#dg').datagrid('getData').rows[i].PALLET
				});
			}

			$.messager.progress({
                title:'Please waiting',
                msg:'Processing data...'
            });

			// PALLET
			var myJSON=JSON.stringify(dataPallet);
			var str_unescape=unescape(myJSON);

			console.log('sscc_booking_save_pallet.php?data='+unescape(str_unescape));

			$.post('sscc_booking_save_pallet.php',{
				data: unescape(str_unescape),
				bookingHeader: b,
				containerHeader: c
			}).done(function(res){
				$.messager.progress({
	                title:'Please waiting',
	                msg:'Saving Pallet data...'
	            });
				// if(res != '"success"'){
				// 	$.messager.alert('ERROR',res,'warning');
				// 	$.messager.progress('close');
				// }
			});

			var myJSON2=JSON.stringify(dataCarton);
			var str_unescape2=unescape(myJSON2).trim();

			console.log('sscc_booking_save_carton.php?data='+unescape(str_unescape2)+'&bookingHeader='+b+'&containerHeader='+c+'&asinHeader='+$("#dg").datagrid("getData").rows[1].ASIN.trim());

			$.post('sscc_booking_save_carton.php',{
				data: unescape(str_unescape2),
				bookingHeader: b,
				containerHeader: c,
				asinHeader: $('#dg').datagrid('getData').rows[1].ASIN.trim()
			}).done(function(res){
				$.messager.progress({
	                title:'Please waiting',
	                msg:'Saving carton data...'
	            });
				$.messager.progress('close');

				$.messager.confirm('Confirm','Do you want to download file to excel?',function(r){
					if(r){
						download_excel(b,c);
					}
				})
			});
		}

		function download_excel(a,b){
			$.messager.progress({
                title:'Please waiting',
                msg:'Saving Pallet data...'
            });

            window.open('sscc_booking_download_xls.php?book='+a+'&cont='+b);
			$.messager.progress('close');
		}

	</script>
	</body>
    </html>
<?php }else{	
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}