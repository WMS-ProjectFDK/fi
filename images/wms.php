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
    <link rel="stylesheet" type="text/css" href="../themes/icon.css">
    <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../js/datagrid-filter.js"></script>
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
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:1070px;"><legend>Filter Supplier</legend>
		<div style="width:380px; float:left;">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Period</span>
				<input style="width:91px;" name="dn_periode_awal" id="dn_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required/>
				<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton" onClick="filterData()" style="width:100px;">FILTER</a>
				<a href="javascript:void(0)" id="printpdf" class="easyui-linkbutton" iconCls='icon-tip' onclick="view_bgn()">Print</a>	
			</div>
		</div>
		</fieldset>
	</div>
	<table id="dg" title="RECEIVING REPORT" class="easyui-datagrid" toolbar="#toolbar	" style="width:1100px;height:500px;"></table>

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
			$('#printpdf').hide();
		})

		function filterData(){
			var t = $('#dn_periode_awal').datebox('getValue');
			if(t==''){
				$.messager.show({title: 'warning',msg: 'Please select plan order period'});
			}else{
				$('#printpdf').show();
				$('#dg').datagrid('load', {
					pd_periode_awal: $('#dn_periode_awal').datebox('getValue')
				});

				$('#dg').datagrid({
				    url:'wms_get.php',
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
					    {field:'last_update',title:'Entry Date', halign:'center', width:100, editor:{type:'textbox'}},
					    {field:'user_entry',title:'User',halign:'center', width:60, editor:{type:'textbox'}},
					    {field:'po_no',title:'po_no',halign:'center', width:70, hidden:true}
				    ]],
					onDblClickRow:function(rowIndex){
				    	$(this).datagrid('beginEdit', rowIndex);
				    	var code = $(this).datagrid('getEditor',{index:rowIndex, field:'po_partno'});
				    	var cd = $(code.target).val();

				    	//alert(cd);

				    	if ($.trim(cd)!='NS') {
				    		var editors = $(this).datagrid('getEditors', rowIndex);
				    		var price = $(editors[5].target);
				    		var cur = $(editors[4].target);

				    		price.numberbox('disable');
				    		cur.combobox('disable');
				    	};
				    }
			    });

				ppdf = "?pd_periode_awal="+$('#dn_periode_awal').datebox('getValue')+
					"&pd_periode_akhir="+$('#dn_periode_akhir').datebox('getValue')+
					"&ck_date="+ck_date+
					"&dn_supp="+$('#dn_supp').combobox('getValue')+
					"&ck_supp="+ck_supp
			}
		}
	</script>
    </body>
    </html>