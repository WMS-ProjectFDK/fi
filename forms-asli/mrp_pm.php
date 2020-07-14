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
<title>Pack Transaction History</title>
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
	<fieldset style="float:left;width:500px;border-radius:4px;height: 75px;">
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Weeks</span>
			<select style="width:70px;" name="cmb_week_no" id="cmb_week_no" class="easyui-combobox">
				<option selected="" value=""></option>
				<?php for ($i=1; $i<=15 ; $i++) { echo "<option value=".$i.">$i</option>";}?>
			</select>
			<label><input type="checkbox" name="ck_week" id="ck_week" checked="true">All</input></label>
		</div>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item Finish Goods</span>
			<select style="width:330px;" name="cmb_upper_item_no" id="cmb_upper_item_no" class="easyui-combobox" 
				data-options=" url:'json/json_upper_item.php', method:'get', valueField:'item_no', textField:'id_name_item', panelHeight:'100px'" ></select>
			<label><input type="checkbox" name="ck_fg" id="ck_fg" checked="true">All</input></label>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:525px;border-radius:4px;width: 470px;height: 75px;">
		<div class="fitem">
			<span style="width:85px;display:inline-block;">Item No.</span>
			<select style="width:330px;" name="cmb_item" id="cmb_item" class="easyui-combobox" data-options=" url:'json/json_item_pm.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
			onSelect:function(rec){
				//alert(rec.id_name_item);
				var spl = rec.id_name_item;
				var sp = spl.split(' - ');
				$('#txt_item_name').textbox('setValue', sp[1]);
			}"></select>
			<label><input type="checkbox" name="ck_item" id="ck_item" checked="true">All</input></label>
		</div>
		<div class="fitem">
			<span style="width:85px;display:inline-block;">Item Name</span>
			<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
		</div>
	</fieldset>
	<fieldset style="margin-left: 1020px;border-radius:4px;height: 75px;">
		<div class="fitem" align="left">
			<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> SEARCH</a>
		</div>
		<div class="fitem" align="left">
			<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="download_to_xls()"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" onclick="RunMRP()"><i class="fa fa-play" aria-hidden="true"></i> Run MRP</a>
		</div>
		<div class="fitem" align="left">
			<span style="width:250px;font-size: 10px; color: blue" id = "upload_last"></span>
		</div>
	</fieldset>
</div>

<table id="dg" title="MRP PACKAGING" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" singleSelect= 'true'></table>
<span style="display: :inline-block; font-size: 8px; color:red">*) double click in row to view details MRP Packaging</span>

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
	$(function(){
		$('#cmb_week_no').combobox('disable');
		$('#ck_week').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_week_no').combobox('disable');
			}else{
				$('#cmb_week_no').combobox('enable');
			}
		});

		$('#cmb_upper_item_no').combobox('disable');
		$('#ck_fg').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_upper_item_no').combobox('disable');
			}else{
				$('#cmb_upper_item_no').combobox('enable');
			}
		});

		$('#cmb_item').combobox('disable');
		$('#ck_item').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_item').combobox('disable');
			}else{
				$('#cmb_item').combobox('enable');
			}
		});

		var data;
			$.ajax({
			type: 'GET',
			dataType: "json",
			url: 'json/json_last_mrp_pck_upload.php',
			data: data,
			success: function (data) {
				
				document.getElementById("upload_last").innerHTML = 'Last Run MRP ( ' + data[0].DEL_DATE+ ' )';
				}
			});
	});

	function RunMRP(){
		if (confirm("This process will takes time around 20-25 minutes, also MRP data now will be erased. if you agree please confirm ?")) {
			alert("Please do not close browser while MRP running.");
		    window.open('http://172.23.225.85/wms/schedule/execute_sp.php');
		} else {
		    alert("Process cancelled");
		}
		
	}

	function AddDate(count) {
	    var someDate = new Date();
	    someDate.setDate(someDate.getDate() + count); 
	    var weekday = new Array(7);
		weekday[0] = "Sun";
		weekday[1] = "Mon";
		weekday[2] = "Tue";
		weekday[3] = "Wed";
		weekday[4] = "Thu";
		weekday[5] = "Fri";
		weekday[6] = "Sat";
		var hr = weekday[someDate.getDay()];
	    var dd = someDate.getDate();
	    var mm = someDate.getMonth() + 1;
	    var yy = someDate.getFullYear();
	    var y = parseInt(yy)-2000;
	    var month = ["-","JAN", "FEB", "MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
	    var someFormattedDate = hr+'<br/>'+dd+'-'+ month[parseInt(mm)]+'-'+y;
	  	return someFormattedDate
	}

	var pdf_url='';		var listbrg = '';

	function filterData(){
		var ck_week = "false";
		var ck_fg = "false";
		var ck_item = "false";

		if ($('#ck_week').attr("checked")) {
			ck_week = "true";
		}

		if ($('#ck_fg').attr("checked")) {
			ck_fg = "true";
		};
		
		if($('#ck_item').attr("checked")) {
			ck_item = "true";
		}

		$('#plan_btn').linkbutton('enable');

		$('#dg').datagrid('load', {
			cmb_week: $('#cmb_week_no').combobox('getValue'),
			ck_week: ck_week,
			cmb_fg: $('#cmb_upper_item_no').combobox('getValue'),
			ck_fg: ck_fg,
			cmb_item: $('#cmb_item').combobox('getValue'),
			ck_item: ck_item
		});

		pdf_url = '?cmb_week='+$('#cmb_week_no').combobox('getValue')+
				'&ck_week='+ck_week+
				'&cmb_fg='+$('#cmb_upper_item_no').combobox('getValue')+
				'&ck_fg='+ck_fg+
				'&cmb_item='+$('#cmb_item').combobox('getValue')+
				'&ck_item='+ck_item
		
		$('#dg').datagrid({
	    	url:'mrp_pm_get.php',
	    	singleSelect: true,
		    fitColumns: true,
			rownumbers: true,
		    columns:[[
	    		{field:'ITEM_NO',title:'<b>ITEM NO.</b>',width:80, halign: 'center'},
				{field:'ITEM_NAME',title:'<b>DESCRIPTION</b>',width:350, halign: 'center'},
				{field:'LEVEL_NO', title:'<b>LEVEL NO.</b>',width:80, halign: 'center', align: 'center'},
				{field:'THIS_INVENTORY',title:'<b>INVENTORY TODAY</b>',width:100, halign: 'center', align: 'right'}
			]],
			view: detailview,
			detailFormatter: function(rowIndex, rowData){
				return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
			},
			onExpandRow: function(index,row){
				console.log('mrp_pm_get_detail.php'+pdf_url+'&item='+row.ITEM_NO+'&level='+row.LEVEL_NO);
				listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
				listbrg.datagrid({
                	title: 'Finish Goods Detail ('+row.ITEM_NAME+')',
                	url:'mrp_pm_get_detail.php'+pdf_url+'&item='+row.ITEM_NO+'&level='+row.LEVEL_NO,
					toolbar: '#ddv'+index,
					loadMsg:'load data ...',
					height:'auto',
					singleSelect: true,
			    	rownumbers: true,
			    	frozenColumns:[[
			    		{field:'ITEM_NO',title:'<b>ITEM NO.</b>',width:80, halign: 'center'},
						{field:'DESCRIPTION',title:'<b>DESCRIPTION<br/>(INVENTORY TODAY)</b>',width:350, halign: 'center'},
						{field:'BALANCE',title:'<b>BALANCE</b>',width:90, halign: 'center', align: 'right'}
					]],
					columns:[[
						{field:'NBAL_1',title: '<b>'+AddDate(1)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_2',title: '<b>'+AddDate(2)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_3',title: '<b>'+AddDate(3)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_4',title: '<b>'+AddDate(4)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_5',title: '<b>'+AddDate(5)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_6',title: '<b>'+AddDate(6)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_7',title: '<b>'+AddDate(7)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_8',title: '<b>'+AddDate(8)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_9',title: '<b>'+AddDate(9)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_10',title: '<b>'+AddDate(10)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_11',title: '<b>'+AddDate(11)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_12',title: '<b>'+AddDate(12)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_13',title: '<b>'+AddDate(13)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_14',title: '<b>'+AddDate(14)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_15',title: '<b>'+AddDate(15)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_16',title: '<b>'+AddDate(16)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_17',title: '<b>'+AddDate(17)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_18',title: '<b>'+AddDate(18)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_19',title: '<b>'+AddDate(19)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_20',title: '<b>'+AddDate(20)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_21',title: '<b>'+AddDate(21)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_22',title: '<b>'+AddDate(22)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_23',title: '<b>'+AddDate(23)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_24',title: '<b>'+AddDate(24)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_25',title: '<b>'+AddDate(25)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_26',title: '<b>'+AddDate(26)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_27',title: '<b>'+AddDate(27)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_28',title: '<b>'+AddDate(28)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_29',title: '<b>'+AddDate(29)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_30',title: '<b>'+AddDate(30)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_31',title: '<b>'+AddDate(31)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_32',title: '<b>'+AddDate(32)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_33',title: '<b>'+AddDate(33)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_34',title: '<b>'+AddDate(34)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_35',title: '<b>'+AddDate(35)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_36',title: '<b>'+AddDate(36)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_37',title: '<b>'+AddDate(37)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_38',title: '<b>'+AddDate(38)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_39',title: '<b>'+AddDate(39)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_40',title: '<b>'+AddDate(40)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_41',title: '<b>'+AddDate(41)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_42',title: '<b>'+AddDate(42)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_43',title: '<b>'+AddDate(43)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_44',title: '<b>'+AddDate(44)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_45',title: '<b>'+AddDate(45)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_46',title: '<b>'+AddDate(46)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_47',title: '<b>'+AddDate(47)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_48',title: '<b>'+AddDate(48)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_49',title: '<b>'+AddDate(49)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_50',title: '<b>'+AddDate(50)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_51',title: '<b>'+AddDate(51)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_52',title: '<b>'+AddDate(52)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_53',title: '<b>'+AddDate(53)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_54',title: '<b>'+AddDate(54)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_55',title: '<b>'+AddDate(55)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_56',title: '<b>'+AddDate(56)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_57',title: '<b>'+AddDate(57)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_58',title: '<b>'+AddDate(58)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_59',title: '<b>'+AddDate(59)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_60',title: '<b>'+AddDate(60)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_61',title: '<b>'+AddDate(61)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_62',title: '<b>'+AddDate(62)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_63',title: '<b>'+AddDate(63)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_64',title: '<b>'+AddDate(64)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_65',title: '<b>'+AddDate(65)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_66',title: '<b>'+AddDate(66)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_67',title: '<b>'+AddDate(67)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_68',title: '<b>'+AddDate(68)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_69',title: '<b>'+AddDate(69)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_70',title: '<b>'+AddDate(70)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_71',title: '<b>'+AddDate(71)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_72',title: '<b>'+AddDate(72)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_73',title: '<b>'+AddDate(73)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_74',title: '<b>'+AddDate(74)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_75',title: '<b>'+AddDate(75)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_76',title: '<b>'+AddDate(76)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_77',title: '<b>'+AddDate(77)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_78',title: '<b>'+AddDate(78)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_79',title: '<b>'+AddDate(79)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_80',title: '<b>'+AddDate(80)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_81',title: '<b>'+AddDate(81)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_82',title: '<b>'+AddDate(82)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_83',title: '<b>'+AddDate(83)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_84',title: '<b>'+AddDate(84)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_85',title: '<b>'+AddDate(85)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_86',title: '<b>'+AddDate(86)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_87',title: '<b>'+AddDate(87)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_88',title: '<b>'+AddDate(88)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_89',title: '<b>'+AddDate(89)+'</b>', width:100, halign: 'center', align: 'right'},
						{field:'NBAL_90',title: '<b>'+AddDate(90)+'</b>', width:100, halign: 'center', align: 'right'}
					]],
					onResize:function(){
						$('#dg').datagrid('fixDetailRowHeight',index);
					},
					onLoadSuccess:function(){
						setTimeout(function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},0);
					},
					onDblClickRow:function(row){
						var row = $(this).datagrid('getSelected');
						var item = row.ITEM_NO;
						var url1 = '?upper_item_no=&cmb_item_no='+item+'&from=ITO';
						var url2 = 'mrp_pm_plan.php'+encodeURIComponent(url1);
						window.open(decodeURIComponent(url2));
					}
                });

				var view_p = parseInt($('#cmb_week_no').combobox('getValue'));
				var view_tot = view_p*7;
				if (ck_week != 'true'){
					for (var i=1; i<=view_tot; i++) {
						listbrg.datagrid('showColumn','N_'+i);
					}

					for (var j=view_tot+1; j<=90; j++) {
						listbrg.datagrid('hideColumn','N_'+j);
					}
				}
			},
			onDblClickRow:function(row){
				var row = $('#dg').datagrid('getSelected');
				var item = row.ITEM_NO;
				var url1 = '?upper_item_no='+item+'&cmb_item_no=&from=ITO';
				var url2 = 'mrp_pm_plan.php'+encodeURIComponent(url1);
				window.open(decodeURIComponent(url2));
			}
	    });

		$('#dg').datagrid('enableFilter');
	}

	/*function formatvalue(val,row){
        if (val < 0){
            return '<span style="color:red;">('+formatter.format(val)+')</span>';
        } else {
            return formatter.format(val);
        }
    }
	var formatter = new Intl.NumberFormat('en-US', {maximumSignificantDigits: 3});*/

	var get_url = '';
	
	function download_to_xls(){
		alert("Process will takes maximum 5 minutes, please wait..");
		var ck_week = "false";
		var ck_fg = "false";
		var ck_item = "false";

		if ($('#ck_week').attr("checked")) {
			ck_week = "true";
		}

		if ($('#ck_fg').attr("checked")) {
			ck_fg = "true";
		};
		
		if($('#ck_item').attr("checked")) {
			ck_item = "true";
		}

		get_url = '?cmb_week='+$('#cmb_week_no').combobox('getValue')+
				  '&ck_week='+ck_week+
				  '&cmb_fg='+$('#cmb_upper_item_no').combobox('getValue')+
				  '&ck_fg='+ck_fg+
				  '&cmb_item='+$('#cmb_item').combobox('getValue')+
				  '&ck_item='+ck_item


		if(get_url==''){
			$.messager.show({title: 'MRP Packaging',msg: 'Filter Data Not found'});	
		}else{
			console.log('mrp_pm_print_xls.php'+get_url);
			window.open('mrp_pm_print_xls.php'+get_url);
		}
	}

</script>
</body>
</html>