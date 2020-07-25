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
	<fieldset style="float:left;width:500px;border-radius:4px;height: 65px;">
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
	<fieldset style="position:absolute;margin-left:525px;border-radius:4px;width: 470px;height: 65px;">
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
	<fieldset style="margin-left: 1020px;border-radius:4px;height: 65px;">
		<div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 90px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> SEARCH</a>
		</div>
		<!-- <div class="fitem" align="center">
			<a href="javascript:void(0)" id="plan_btn" style="width: 90px;" class="easyui-linkbutton c2" onclick="open_plan()" disabled="true"><i class="fa fa-list" aria-hidden="true"></i> VIEW PLAN</a><br/>
		</div> -->
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
	});

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

	var pdf_url='';

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

		$('#dg').datagrid({
	    	url:'mrp_pm_get.php',
	    	singleSelect: true,
	    	rownumbers: true,
	    	frozenColumns:[[
	    		{field:'ITEM_NO',title:'<b>ITEM NO.</b>',width:80, halign: 'center'},
				// {field:'ITEM_DESC',title:'<b>ITEM<br/>DESCRIPTION</b>',width:250, halign: 'center'},
				{field:'DESCRIPTION',title:'<b>DESCRIPTION<br/>(INVENTORY TODAY)</b>',width:350, halign: 'center'}
			]],
			columns:[[
			{field:'N_1',title: '<b>'+AddDate(1)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_2',title: '<b>'+AddDate(2)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_3',title: '<b>'+AddDate(3)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_4',title: '<b>'+AddDate(4)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_5',title: '<b>'+AddDate(5)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_6',title: '<b>'+AddDate(6)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_7',title: '<b>'+AddDate(7)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_8',title: '<b>'+AddDate(8)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_9',title: '<b>'+AddDate(9)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_10',title: '<b>'+AddDate(10)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_11',title: '<b>'+AddDate(11)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_12',title: '<b>'+AddDate(12)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_13',title: '<b>'+AddDate(13)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_14',title: '<b>'+AddDate(14)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_15',title: '<b>'+AddDate(15)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_16',title: '<b>'+AddDate(16)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_17',title: '<b>'+AddDate(17)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_18',title: '<b>'+AddDate(18)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_19',title: '<b>'+AddDate(19)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_20',title: '<b>'+AddDate(20)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_21',title: '<b>'+AddDate(21)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_22',title: '<b>'+AddDate(22)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_23',title: '<b>'+AddDate(23)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_24',title: '<b>'+AddDate(24)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_25',title: '<b>'+AddDate(25)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_26',title: '<b>'+AddDate(26)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_27',title: '<b>'+AddDate(27)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_28',title: '<b>'+AddDate(28)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_29',title: '<b>'+AddDate(29)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_30',title: '<b>'+AddDate(30)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_31',title: '<b>'+AddDate(31)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_32',title: '<b>'+AddDate(32)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_33',title: '<b>'+AddDate(33)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_34',title: '<b>'+AddDate(34)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_35',title: '<b>'+AddDate(35)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_36',title: '<b>'+AddDate(36)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_37',title: '<b>'+AddDate(37)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_38',title: '<b>'+AddDate(38)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_39',title: '<b>'+AddDate(39)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_40',title: '<b>'+AddDate(40)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_41',title: '<b>'+AddDate(41)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_42',title: '<b>'+AddDate(42)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_43',title: '<b>'+AddDate(43)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_44',title: '<b>'+AddDate(44)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_45',title: '<b>'+AddDate(45)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_46',title: '<b>'+AddDate(46)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_47',title: '<b>'+AddDate(47)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_48',title: '<b>'+AddDate(48)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_49',title: '<b>'+AddDate(49)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_50',title: '<b>'+AddDate(50)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_51',title: '<b>'+AddDate(51)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_52',title: '<b>'+AddDate(52)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_53',title: '<b>'+AddDate(53)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_54',title: '<b>'+AddDate(54)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_55',title: '<b>'+AddDate(55)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_56',title: '<b>'+AddDate(56)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_57',title: '<b>'+AddDate(57)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_58',title: '<b>'+AddDate(58)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_59',title: '<b>'+AddDate(59)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_60',title: '<b>'+AddDate(60)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_61',title: '<b>'+AddDate(61)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_62',title: '<b>'+AddDate(62)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_63',title: '<b>'+AddDate(63)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_64',title: '<b>'+AddDate(64)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_65',title: '<b>'+AddDate(65)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_66',title: '<b>'+AddDate(66)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_67',title: '<b>'+AddDate(67)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_68',title: '<b>'+AddDate(68)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_69',title: '<b>'+AddDate(69)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_70',title: '<b>'+AddDate(70)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_71',title: '<b>'+AddDate(71)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_72',title: '<b>'+AddDate(72)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_73',title: '<b>'+AddDate(73)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_74',title: '<b>'+AddDate(74)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_75',title: '<b>'+AddDate(75)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_76',title: '<b>'+AddDate(76)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_77',title: '<b>'+AddDate(77)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_78',title: '<b>'+AddDate(78)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_79',title: '<b>'+AddDate(79)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_80',title: '<b>'+AddDate(80)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_81',title: '<b>'+AddDate(81)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_82',title: '<b>'+AddDate(82)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_83',title: '<b>'+AddDate(83)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_84',title: '<b>'+AddDate(84)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_85',title: '<b>'+AddDate(85)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_86',title: '<b>'+AddDate(86)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_87',title: '<b>'+AddDate(87)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_88',title: '<b>'+AddDate(88)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_89',title: '<b>'+AddDate(89)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue},
				{field:'N_90',title: '<b>'+AddDate(90)+'</b>', width:100, halign: 'center', align: 'right',formatter: formatvalue}
			]],
			onDblClickRow:function(row){
				var row = $('#dg').datagrid('getSelected');
				var item = row.ITEM_NO;
				var url1 = '?cmb_item_no='+item+'&from=ITO';
				var url2 = 'mrp_pm_plan.php'+encodeURIComponent(url1);
				window.open(decodeURIComponent(url2));
			}
	    });

		$('#dg').datagrid('enableFilter');

		pdf_url = '?cmb_week='+$('#cmb_week_no').combobox('getValue')+'&ck_week='+ck_week+
				  '&cmb_fg='+$('#cmb_upper_item_no').combobox('getValue')+'&ck_fg='+ck_fg+
				  '&from=MRP';

		var view_p = parseInt($('#cmb_week_no').combobox('getValue'));
		var view_tot = view_p*7;
		if (ck_week != 'true'){
			for (var i=1; i<=view_tot; i++) {
				$('#dg').datagrid('showColumn','N_'+i);
			}

			for (var j=view_tot+1; j<=90; j++) {
				$('#dg').datagrid('hideColumn','N_'+j);
			}
		}
	}

	function open_plan(){
		var url = 'mrp_pm_plan.php'+encodeURIComponent(pdf_url);
		window.open(decodeURIComponent(url));
	}

	  function formatvalue(val,row){
            if (val < 0){
                return '<span style="color:red;">('+formatter.format(val)+')</span>';
            } else {
                return formatter.format(val);
            }
        }
var formatter = new Intl.NumberFormat('en-US', {
  maximumSignificantDigits: 3
});
</script>
</body>
</html>