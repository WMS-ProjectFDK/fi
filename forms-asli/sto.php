<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../favicon.png">
    <title>Stock Taking Opname</title>
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
	.style4 {
	font-size: 11px;
	color: #CC0000;
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
		width: 100%;
	}
	.board_1 {
		position: absolute;
		margin-left:615px;
		top: 0px;
		border-style: solid;
		border-width: 0px;
	}
	.board_2 {
		position: absolute;
		margin-left:615px;
		top: 51px;
		border-style: solid;
		border-width: 0px;
	}
	.button_filter {
		position: absolute;
		margin-left:135px;
		top: 120px;
	}
	.button_generate {
		position: absolute;
		margin-left:100px;
		top: 134px;
	}
	.do_field{
		width:28%;
		/*height: 190px;
		border: 1px solid black;*/
		float: left;
		margin: 0 30px 0 0;
	}
	.clear{
		clear: both;
	}
	</style>
</head>
<body>
	<?php include ('../ico_logout.php');
	?>

	<div id="toolbar">
		<fieldset style="float:left;width:360px;height: 50px; border-radius:4px;"><legend><span class="style3"><strong>STO Filter</strong></span></legend>
			<div class="fitem">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Cut Off Date</span>
					<input style="width:115px;" name="cutoff_date" id="cutoff_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="" />
					<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="filterData()" style="width:150px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
					<span style="width:80px;display:inline-block;"></span>
				</div>
			</div>
		</fieldset>
		<fieldset style="margin-left:380px;border-radius:4px;width: 620px;height: 50px;"><legend><span class="style3"><strong>Select Print</strong></span></legend>
		  <div class="fitem" align="center">
		   <a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="pdf_sto()"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Print STO</a>
		   <a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="pdf_sto_detail()"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Print STO Detail</a>
		   <a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="xl_sto()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Print STO</a>
		   <a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="xl_sto_detail()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Print STO Detail</a>
		  </div>	
		</fieldset>
	</div>
	<table id="dg" title="STOCK TAKING OPNAME" class="easyui-datagrid" style="width: 100%;height:500px;"></table>

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

		var pdf_url='';

		$('#dg').datagrid({
			url:'sto_get.php',
		    toolbar: '#toolbar',
		    rownumbers: true,
		    singleSelect: true,
		    fitColumns: true,
			sortName: '',
			sortOrder: 'asc',
		    columns:[[
			    {field:'CUTOFF', hidden: true},
				{field:'ITEM_NO',title:'ITEM NO.',width:80, halign:'center', sortable: true, align:'left'},
			    {field:'DESCRIPTION',title:'ITEM NAME',width:250, halign:'center', align:'left'},
			    {field:'TOTAL',title:'STOCK',width:80, halign:'center', align:'right'},
			    {field:'UNIT',title:'UoM',width:50, halign:'center', align:'center'}
		    ]],
			onClickRow: function(rec){
				var row = $('#dg').datagrid('getSelected');
			},
			view: detailview,
			detailFormatter: function(rowIndex, rowData){
				return '<div style="padding:2px" id="tbdetail'+rowIndex+'"></div><div style="padding:0px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
				},
		    onExpandRow: function(index,row){
					var uri_doc = encodeURIComponent(row.ITEM_NO);
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');

					listbrg.datagrid({
						title: 'STOCK DETAIL (ITEM NO.: '+row.ITEM_NO+'-'+row.DESCRIPTION+')',
						url:'sto_detail_get.php?item_no='+uri_doc+'&tgl='+row.CUTOFF,
						toolbar: '#toolbar'+index,
						singleSelect:true,
						rownumbers:true,
						fitColumns: true,
						loadMsg:'Please Wait...',
						height:'auto',
						rownumbers: true,
						rowStyler: function(index, row){
						},
						columns:[[
							{field:'ID', title:'INCOMING<br/>NO.', halign:'center', align:'center', width:40},
							{field:'GR_NO', title:'GOODS RECEIVE<br>NO.', halign:'center', width:100},
							{field:'GR_DATE', title:'GOODS RECEIVE<br>DATE', halign:'center', align:'center', width:100},
							{field:'PALLET', title:'PALLET',halign:'center', align:'center', width:40},
							{field:'QTY', title:'QTY',align:'right', width:100},
							{field:'RACK', title:'RACK', halign:'center', align:'center', width:80},
							{field:'WAREHOUSE', title:'WAREHOUSE',halign:'center', align:'center', width:100}
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

		function filterData(){
			if($('#cutoff_date').datebox('getValue')==''){
				$.messager.show({title: 'warning',msg: 'Please select cutt off date'});
			}else{
				$('#dg').datagrid('load', {
					cutoff_date: $('#cutoff_date').datebox('getValue')
				});	
			}
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');

			pdf_url = "?tanggal="+$('#cutoff_date').datebox('getValue');
		}

		function pdf_sto(){
			if(pdf_url==''){
				$.messager.show({title: 'warning',msg: 'cutt off date not Found'});
			}else{
				window.open('sto_pdf.php'+pdf_url, '_blank');
			}
		}

		function pdf_sto_detail(){
			if(pdf_url==''){
				$.messager.show({title: 'warning',msg: 'cutt off date not Found'});
			}else{
				window.open('sto_pdf_detail.php'+pdf_url, '_blank');
			}
		}

		function xl_sto(){
			if(pdf_url==''){
				$.messager.show({title: 'warning',msg: 'cutt off date not Found'});
			}else{
				window.open('sto_excel.php'+pdf_url, '_blank');
			}
		}

		function xl_sto_detail(){
			if(pdf_url==''){
				$.messager.show({title: 'warning',msg: 'cutt off date not Found'});
			}else{
				window.open('sto_excel_detail.php'+pdf_url, '_blank');
			}	
		}
	</script>
</body>
</html>
