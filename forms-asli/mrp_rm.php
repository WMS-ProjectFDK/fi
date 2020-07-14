<?php
require("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RM Transaction History</title>
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
<script type="text/javascript" src="../js/jquery.easyui.patch.js"></script>
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

<div id="toolbar" style="padding:3px 3px;">	
	<div class="fitem">
		<span style="width:10px;display:inline-block;"></span>
		<span style="width:110px;display:inline-block;">Search Tipe</span>
		<input style="width:200px;" name="cmb_type" id="cmb_type" class="easyui-combobox" data-options="url:'json/json_groupRM.php', method:'get', valueField:'tipe', textField:'tipe', panelHeight:'50px'" />
		<span style="width:30px;display:inline-block;"></span>
		<span style="width:80px;display:inline-block;">Item No.</span>
		<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_groupRM_item.php', method:'get', valueField:'item_no', textField:'id_name_item', panelHeight:'100px',
		onSelect:function(rec){
			var spl = rec.id_name_item;
			var sp = spl.split(' - ');
			$('#txt_item_name').textbox('setValue', sp[1]);
		}" ></select>
		<input style="width:280px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
		<a href="javascript:void(0)" style="width: 90px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> SEARCH</a>
		<a href="javascript:void(0)" id="plan_btn" style="width: 90px;" class="easyui-linkbutton c2" onclick="open_plan()" disabled="true"><i class="fa fa-list" aria-hidden="true"></i> PLAN</a><!-- mrp_rm_plan.php -->
	</div>
</div>

<table id="dg" title="RM Transaction History" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" singleSelect= 'true'>
	<thead frozen="true">
		<tr>
			<th field="NO" width="40" halign="center" align="center"><b>NO</b></th>
			<th field="TIPE" width="100" halign="center" align="center"><b>TYPE</b></th>
			<th field="DESC" width="200" halign="center"},><b>ITEM NO.</b></th>
			<th field="KETERANGAN" width="110" halign="center"><b>REMARK</b></th>
		</tr>
	</thead>
	<thead>
		<tr>
			<th id="c1" field="TANGGAL1" width="78" halign="center" align="right"><b>1 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c2" field="TANGGAL2" width="78" halign="center" align="right"><b>2 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c3" field="TANGGAL3" width="78" halign="center" align="right"><b>3 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c4" field="TANGGAL4" width="78" halign="center" align="right"><b>4 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c5" field="TANGGAL5" width="78" halign="center" align="right"><b>5 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c6" field="TANGGAL6" width="78" halign="center" align="right"><b>6 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c7" field="TANGGAL7" width="78" halign="center" align="right"><b>7 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c8" field="TANGGAL8" width="78" halign="center" align="right"><b>8 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c9" field="TANGGAL9" width="78" halign="center" align="right"><b>9 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c10" field="TANGGAL0" width="78"  halign="center" align="right"><b>10 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c11" field="TANGGAL11" width="78" halign="center" align="right"><b>11 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c12" field="TANGGAL12" width="78" halign="center" align="right"><b>12 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c13" field="TANGGAL13" width="78" halign="center" align="right"><b>13 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c14" field="TANGGAL14" width="78" halign="center" align="right"><b>14 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c15" field="TANGGAL15" width="78" halign="center" align="right"><b>15 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c16" field="TANGGAL16" width="78" halign="center" align="right"><b>16 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c17" field="TANGGAL17" width="78" halign="center" align="right"><b>17 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c18" field="TANGGAL18" width="78" halign="center" align="right"><b>18 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c19" field="TANGGAL19" width="78" halign="center" align="right"><b>19 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c20" field="TANGGAL20" width="78" halign="center" align="right"><b>20 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c21" field="TANGGAL21" width="78" halign="center" align="right"><b>21 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c22" field="TANGGAL22" width="78" halign="center" align="right"><b>22 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c23" field="TANGGAL23" width="78" halign="center" align="right"><b>23 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c24" field="TANGGAL24" width="78" halign="center" align="right"><b>24 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c25" field="TANGGAL25" width="78" halign="center" align="right"><b>25 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c26" field="TANGGAL26" width="78" halign="center" align="right"><b>26 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c27" field="TANGGAL27" width="78" halign="center" align="right"><b>27 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c28" field="TANGGAL28" width="78" halign="center" align="right"><b>28 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c29" field="TANGGAL29" width="78" halign="center" align="right"><b>29 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c30" field="TANGGAL30" width="78" halign="center" align="right"><b>30 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
			<th id="c31" field="TANGGAL31" width="78" halign="center" align="right"><b>31 <br/><?php echo strtoupper(date('M-Y')); ?></b></th>
		</tr>
	</thead>
</table>

<!-- START VIEW INFO PLAN ASSEMBLING-->
<div id='dlg_viewPLAN' class="easyui-dialog" style="width:1050px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewPLAN" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>
<!-- END VIEW INFO PLAN ASSEMBLING-->

<!-- START VIEW INFO DO-->
<div id='dlg_viewDO' class="easyui-dialog" style="width:850px;height:200px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewDO" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>
<!-- END VIEW INFO PO-->

<!-- START VIEW INFO PO-->
<div id='dlg_viewGR' class="easyui-dialog" style="width:100%;height:200px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewGR" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>
<!-- END VIEW INFO PO-->

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
		}else{
			return new Date();
		}
	}

	var month = ["-","JAN", "FEB", "MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
	var d = new Date();
	var mm = d.getMonth() + 1;
	var y = d.getFullYear();

	function info_assy(a,b,c){
		var dd = a;
		var tgl = y+'-'+mm+'-'+dd;
		var month_v = dd+'-'+month[parseInt(mm)]+'-'+y;

		if (c == 0 || c == null){
			$.messager.show({
                title: 'INFORMATION',
                msg: 'Value is null'
            });
		}else{
			//alert('mrp_rm_plan_info_ASSY.php?item_no='+b+'&tgl_plan='+tgl);
			$('#dlg_viewPLAN').dialog('open').dialog('setTitle','VIEW INFO ASSEMBLY PLAN ('+month_v+')');
			$('#dg_viewPLAN').datagrid({
				url: 'mrp_rm_plan_info_ASSY.php?item_no='+b+'&tgl_plan='+tgl,
				singleSelect: true,
				rownumbers: true,
			    columns:[[
				    {field:'ID_PLAN', title:'ID<br/>PLAN', width: 120, halign: 'center'},
				    {field:'ASSY_LINE', title:'ASSY_LINE', width:90, halign: 'center', align: 'center'},
				    {field:'CELL_TYPE', title:'CELL TYPE', width: 90, halign: 'center', align: 'center'},
				    {field:'QTY', title:'QTY PLAN', width: 80, halign: 'center', align: 'right'},
				    {field:'ITEM_NO', title:'ITEM NO.', width: 80, halign: 'center', align: 'center'},
				    {field:'DESCRIPTION', title:'DESCRIPTION', width: 300, halign: 'center'},
				    {field:'KONVERSI', title:'KONVERSI', width: 100, halign: 'center', align: 'right'},
				    {field:'USAGE', title:'USAGE', width: 120, halign: 'center', align: 'right'}
				]]
			});
		}
	}

	function info_do(d,e,f){
		var dd = d;
		var dm = new Date();
		var mm = dm.getMonth() + 1;
		var y = dm.getFullYear();
		var tgl = y+'-'+(mm<10?('0'+mm):mm)+'-'+(dd<10?('0'+dd):dd);
		var month_v = dd+'-'+month[parseInt(mm)]+'-'+y;

		if (f == 0 || f == null){
			$.messager.show({
                title: 'INFORMATION',
                msg: 'Value is null'
            });
		}else{
			//alert('mrp_rm_plan_info_DO.php?item_no='+e+'&tgl_plan='+tgl);
			$('#dlg_viewDO').dialog('open').dialog('setTitle','VIEW INFO MATERIAL TRANSACTION ('+month_v+')');
			$('#dg_viewDO').datagrid({
				url: 'mrp_rm_plan_info_DO.php?item_no='+e+'&tgl_plan='+tgl,
				singleSelect: true,
				rownumbers: true,
			    columns:[[
				    {field:'SLIP_NO', title:'SLIP NO.', width:120, halign: 'center', align: 'center'},
				    {field:'SLIP_DATE', title:'SLIP DATE', width: 90, halign: 'center', align: 'center'},
				    {field:'ITEM_NO', title:'ITEM NO.', width: 80, halign: 'center', align: 'center'},
				    {field:'DESCRIPTION', title:'DESCRIPTION', width: 300, halign: 'center'},
				    {field:'SLIP_QUANTITY', title:'QTY', width: 100, halign: 'center', align: 'right'},
				    {field:'RACK', title:'RACK<br/>ADDRESS', width: 120, halign: 'center', align: 'center'}
				]]
			});
		}
	}

	function info_po(g,h,i){
		var dd = g;
		var tgl = y+'-'+(mm<10?('0'+mm):mm)+'-'+(dd<10?('0'+dd):dd);
		var month_v = dd+'-'+month[parseInt(mm)]+'-'+y;

		if (i == 0 || i == null){
			$.messager.show({
                title: 'INFORMATION',
                msg: 'Value is null'
            });
		}else{
			//alert('mrp_rm_plan_info_GR.php?item_no='+h+'&tgl_plan='+tgl);
			$('#dlg_viewGR').dialog('open').dialog('setTitle','VIEW INFO GOODS RECEIVE ('+month_v+')');
			$('#dg_viewGR').datagrid({
				url: 'mrp_rm_plan_info_GR.php?item_no='+h+'&tgl_plan='+tgl,
				singleSelect: true,
				rownumbers: true,
			    columns:[[
				    {field:'GR_NO', title:'GOODS RECEIVE<br/>NO.', width:150, halign: 'center', align: 'center'},
				    {field:'GR_DATE', title:'GOODS RECEIVE<br/>DATE', width: 110, halign: 'center', align: 'center'},
				    {field:'ITEM_NO', title:'ITEM<br/>DESCRIPTION', width: 300, halign: 'center'},
				    {field:'QTY', title:'QTY', width: 100, halign: 'center', align: 'right'},
				    {field:'UNIT', title:'UoM', width: 50, halign: 'center', align: 'center'},
				    {field:'PO_NO', title:'PO NO.', width:120, halign: 'center', align: 'center'},
				    {field:'COMPANY', title:'COMPANY', width: 250, halign: 'center'},
				    {field:'ETA', title:'ETA DATE', width: 100, halign: 'center', align: 'center'},
				    {field:'PRF_NO', title:'PRF NO.', width: 120, halign: 'center', align: 'center'}
				]]
			});
		}
	}

	var pdf_url='';

	function filterData(){
		var ty = $('#cmb_type').combobox('getValue');
		var it = $('#cmb_item_no').combobox('getValue');
		var itn = $('#cmb_item_no').combobox('getText');
		$('#plan_btn').linkbutton('enable');

		$('#dg').datagrid('load', {
			cmb_type: $('#cmb_type').combobox('getValue'),
			cmb_item_no: $('#cmb_item_no').combobox('getValue')
		});

		$('#dg').datagrid({
	    	url:'mrp_rm_get.php',
	    	onLoadSuccess:function(){
			    $(this).datagrid('getPanel').find('.easyui-tooltip').tooltip({
			    	position: 'right',
	                content: '<span style="color:#fff">This is the tooltip message.</span>',
	                onShow: function(){
	                    $(this).tooltip('tip').css({
	                        backgroundColor: '#666',
	                        borderColor: '#666'
	                    });
	                }
			    });
		  	}
	    });

		pdf_url = '?cmb_type='+ty+'&cmb_item_no='+it+'&item_name='+itn+'&from=MRP';
		//alert(pdf_url);
		var today = new Date();
		var dd = parseInt(today.getDate());

		if (dd==10){
			var ddt = 'TANGGAL0';
		}else{
			var ddt = 'TANGGAL'+dd;
		}
		
		var col = $('#dg').datagrid('getColumnOption',ddt);
		col.styler = function(){
			return 'background-color:#AAFFFF; color: #000000; font-weight:bold;';
		};

		$('#dg').datagrid({
			rowStyler:function(index,row){
			    if (row.KETERANGAN == 'E.STOCK'){
			        return 'background-color:#990000;color:white;font-weight:bold;';
			    }
			}
		});
	}

	function open_plan(){
		//document.getElementById("plan_btn").href="mrp_rm_plan.php"+pdf_url; 
		var url = 'mrp_rm_plan.php'+encodeURIComponent(pdf_url);
		window.open(decodeURIComponent(url));
		
	}
</script>
</body>
</html>