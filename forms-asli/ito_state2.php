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
<title>ITO STATUS</title>
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
  <fieldset style="float:left;width:540px;height: 90px;border-radius:4px;"><legend><span class="style3"><strong>Material Filter</strong></span></legend>
	<div style="width:540px;float:left">
		<!-- <div class="fitem">
			<span style="width:10px;display:inline-block;"></span>
			<span style="width:110px;display:inline-block;">Search Tipe</span>
			<input style="width:200px;" name="cmb_type" id="cmb_type" class="easyui-combobox" data-options="url:'json/json_groupRM.php', method:'get', valueField:'tipe', textField:'tipe', panelHeight:'50px'"/>
			<label><input type="checkbox" name="ck_type" id="ck_type" checked="true">All</input></label>
		</div> -->
		<div class="fitem">	
			<span style="width:10px;display:inline-block;"></span>
			<span style="width:110px;display:inline-block;">Item No.</span>
			<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_groupRM_item.php', method:'get', valueField:'item_no', textField:'id_name_item', panelHeight:'100px',
			onSelect:function(rec){
				var spl = rec.id_name_item;
				var sp = spl.split(' - ');
				$('#txt_item_name').textbox('setValue', sp[1]);}">
			</select>
			<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
		</div>
		<div class="fitem">
			<span style="width:125px;display:inline-block;"></span>
			<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
		</div>	
	</div>
  </fieldset>
  <fieldset style="position:absolute;margin-left:565px;margin-top: 7px;border-radius:4px;width: 500px;height: 83px;">
  	<div class="fitem">
		<span style="width:10px;display:inline-block;"></span>
		<span style="width:110px;display:inline-block;">View Plan (Day)</span>
		<!-- <input style="width:200px;" name="cmb_type" id="cmb_type" class="easyui-combobox" data-options="url:'json/json_groupRM.php', method:'get', valueField:'tipe', textField:'tipe', panelHeight:'50px'" required=""/> -->
		<select style="width:100px;" name="cmb_day" id="cmb_day" class="easyui-combobox" >
			<option value="" selected="selected"></option>
			<option value="5">5</option>
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option value="40">40</option>
			<option value="50">50</option>
			<option value="60">60</option>
			<option value="70">70</option>
			<option value="80">80</option>
		</select>
		<label><input type="checkbox" name="ck_day" id="ck_day" checked="true">All</input></label>
	</div>
	<div class="fitem">
	  <fieldset style="border-radius: 4px; margin-left: 15px; margin-right: 15px;"><legend><span class="style3"><strong> ITO STATUS </strong></span></legend>	
		<div align="center">
			<span style="width:100px;display:inline-block;"><input type="checkbox" name="ck_s" id="ck_s" checked="true">SHORTAGE</input></span>
			<span style="width:100px;display:inline-block;"><input type="checkbox" name="ck_a" id="ck_a" checked="true">ACHIEVED</input></span>
			<span style="width:100px;display:inline-block;"><input type="checkbox" name="ck_o" id="ck_o" checked="true">OVER</input></span>
	  	</div>
	  </fieldset>
	</div>
  </fieldset>
  <fieldset style="height: 83px;margin-left: 1090px;margin-top: 7px;border-radius:4px;">
  	<div class="fitem" align="center">	
		<a href="javascript:void(0)" style="width: 140px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> SEARCH</a>
	</div>
  </fieldset>
</div>

<table id="dg" title="ITO STATUS" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" singleSelect= 'true'></table>
<div style="margin-top: 5px; width: 100%;">
	<div style="float: left;width:100px;height: 17px;border-radius:4px; background-color: red; color: white; text-align: center;"><b>SHORTAGE</b></div>
	<div style="position:absolute; margin-left: 110px; width:100px;height: 17px;border-radius:4px; background-color: green; color: white; text-align: center;"><b>ACHIEVED</b></div>
	<div style="margin-left: 220px;width:100px;height: 17px;border-radius:4px; background-color: blue; color: white; text-align: center;"><b>OVER</b></div>
	<div style='width:100%; text-align:right; margin-bottom:100%;font-size:10px;'> *): double click to view Plan</div>
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
		}else{
			return new Date();
		}
	}

	$(function(){
		/*$('#cmb_type').combobox('disable');
		$('#ck_type').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_type').combobox('disable');
			}else{
				$('#cmb_type').combobox('enable');
			}
		});*/

		$('#cmb_item_no').combobox('disable');
		$('#ck_item_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_item_no').combobox('disable');
			}else{
				$('#cmb_item_no').combobox('enable');
			}
		});

		$('#cmb_day').combobox('disable');
		$('#ck_day').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_day').combobox('disable');
			}else{
				$('#cmb_day').combobox('enable');
			}
		});
	});

	function AddDateII(count) {
	    var someDate = new Date();
	    someDate.setDate(someDate.getDate() + count); 
	    var dd = someDate.getDate();
	    var mm = someDate.getMonth() + 1;
	    var y = someDate.getFullYear();
	    var month = ["-","JAN", "FEB", "MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
	    var someFormattedDate = dd + '<br/>'+ month[parseInt(mm)] + '-'+ y;
	  	return someFormattedDate
	}

	var pdf_url='';
	var sts = '';

	function filterData(){
		var ck_item_no = "false";
		var ck_day = "false";
		var ck_s = "false";
		var ck_a = "false";
		var ck_o = "false";

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
		};

		if ($('#ck_day').attr("checked")) {
			ck_day = "true";
		};

		if ($('#ck_s').attr("checked")) {
			if ($('#ck_a').attr("checked")) {
				if ($('#ck_o').attr("checked")) {
					sts = "T-T-T";
				}else{
					sts = "T-T-F";
				}
			}else{
				if ($('#ck_o').attr("checked")) {
					sts = "T-F-T";
				}else{
					sts = "T-F-F";
				}
			}
		}else{
			if ($('#ck_a').attr("checked")) {
				if ($('#ck_o').attr("checked")) {
					sts = "F-T-T";
				}else{
					sts = "F-T-F";
				}
			}else{
				if ($('#ck_o').attr("checked")) {
					sts = "F-F-T";
				}else{
					sts = "F-F-F";
				}
			}
		};

		$('#dg').datagrid('load', {
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no,
			cmb_day: $('#cmb_day').combobox('getValue'),
			ck_day: ck_day,
			sts_ito: sts
		});
		
		$('#dg').datagrid({
	    	url:'ito_state_get.php',
	    	singleSelect: true,
	    	rownumbers: true,
	    	frozenColumns:[[
				{field:'ITEM_NO',title:'<b>ITEM NO.</b>',width:80, halign: 'center'},
				{field:'TIPE',title:'<b>TIPE</b>',width:100, halign: 'center'},
				{field:'ITEM_DESC',title:'<b>ITEM<br/>DESCRIPTION</b>',width:200, halign: 'center'},
				{field:'MIN',title:'<b>MIN</b>',width:50, halign: 'center', align: 'center'},
				{field:'STD',title:'<b>STD</b>',width:50, halign: 'center', align: 'center'},
				{field:'MAX',title:'<b>MAX</b>',width:50, halign: 'center', align: 'center'},
				{field:'AVG',title:'<b>AVG</b>',width:50, halign: 'center', align: 'center'}
			]],
			columns:[[
				{field:'N_1',title: '<b>'+AddDateII(1)+'</b>', width:75, halign: 'center', align: 'right'},
				{field:'N_2',title: '<b>'+AddDateII(2)+'</b>', width:75, halign: 'center', align: 'right'},
				{field:'N_3',title: '<b>'+AddDateII(3)+'</b>', width:75, halign: 'center', align: 'right'},
				{field:'N_4',title: '<b>'+AddDateII(4)+'</b>', width:75, halign: 'center', align: 'right'},
				{field:'N_5',title: '<b>'+AddDateII(5)+'</b>', width:75, halign: 'center', align: 'right'},
				{field:'N_6',title: '<b>'+AddDateII(6)+'</b>', width:75, halign: 'center', align: 'right'},
				{field:'N_7',title: '<b>'+AddDateII(7)+'</b>', width:75, halign: 'center', align: 'right'},
				{field:'N_8',title: '<b>'+AddDateII(8)+'</b>', width:75, halign: 'center', align: 'right'},
				{field:'N_9',title: '<b>'+AddDateII(9)+'</b>', width:75, halign: 'center', align: 'right'},
				{field:'N_10',title: '<b>'+AddDateII(10)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_11',title: '<b>'+AddDateII(11)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_12',title: '<b>'+AddDateII(12)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_13',title: '<b>'+AddDateII(13)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_14',title: '<b>'+AddDateII(14)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_15',title: '<b>'+AddDateII(15)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_16',title: '<b>'+AddDateII(16)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_17',title: '<b>'+AddDateII(17)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_18',title: '<b>'+AddDateII(18)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_19',title: '<b>'+AddDateII(19)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_20',title: '<b>'+AddDateII(20)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_21',title: '<b>'+AddDateII(21)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_22',title: '<b>'+AddDateII(22)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_23',title: '<b>'+AddDateII(23)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_24',title: '<b>'+AddDateII(24)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_25',title: '<b>'+AddDateII(25)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_26',title: '<b>'+AddDateII(26)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_27',title: '<b>'+AddDateII(27)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_28',title: '<b>'+AddDateII(28)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_29',title: '<b>'+AddDateII(29)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_30',title: '<b>'+AddDateII(30)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_31',title: '<b>'+AddDateII(31)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_32',title: '<b>'+AddDateII(32)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_33',title: '<b>'+AddDateII(33)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_34',title: '<b>'+AddDateII(34)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_35',title: '<b>'+AddDateII(35)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_36',title: '<b>'+AddDateII(36)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_37',title: '<b>'+AddDateII(37)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_38',title: '<b>'+AddDateII(38)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_39',title: '<b>'+AddDateII(39)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_40',title: '<b>'+AddDateII(40)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_41',title: '<b>'+AddDateII(41)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_42',title: '<b>'+AddDateII(42)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_43',title: '<b>'+AddDateII(43)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_44',title: '<b>'+AddDateII(44)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_45',title: '<b>'+AddDateII(45)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_46',title: '<b>'+AddDateII(46)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_47',title: '<b>'+AddDateII(47)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_48',title: '<b>'+AddDateII(48)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_49',title: '<b>'+AddDateII(49)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_50',title: '<b>'+AddDateII(50)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_51',title: '<b>'+AddDateII(51)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_52',title: '<b>'+AddDateII(52)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_53',title: '<b>'+AddDateII(53)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_54',title: '<b>'+AddDateII(54)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_55',title: '<b>'+AddDateII(55)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_56',title: '<b>'+AddDateII(56)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_57',title: '<b>'+AddDateII(57)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_58',title: '<b>'+AddDateII(58)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_59',title: '<b>'+AddDateII(59)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_60',title: '<b>'+AddDateII(60)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_61',title: '<b>'+AddDateII(61)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_62',title: '<b>'+AddDateII(62)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_63',title: '<b>'+AddDateII(63)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_64',title: '<b>'+AddDateII(64)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_65',title: '<b>'+AddDateII(65)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_66',title: '<b>'+AddDateII(66)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_67',title: '<b>'+AddDateII(67)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_68',title: '<b>'+AddDateII(68)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_69',title: '<b>'+AddDateII(69)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_70',title: '<b>'+AddDateII(70)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_71',title: '<b>'+AddDateII(71)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_72',title: '<b>'+AddDateII(72)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_73',title: '<b>'+AddDateII(73)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_74',title: '<b>'+AddDateII(74)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_75',title: '<b>'+AddDateII(75)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_76',title: '<b>'+AddDateII(76)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_77',title: '<b>'+AddDateII(77)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_78',title: '<b>'+AddDateII(78)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_79',title: '<b>'+AddDateII(79)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_80',title: '<b>'+AddDateII(80)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_81',title: '<b>'+AddDateII(81)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_82',title: '<b>'+AddDateII(82)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_83',title: '<b>'+AddDateII(83)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_84',title: '<b>'+AddDateII(84)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_85',title: '<b>'+AddDateII(85)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_86',title: '<b>'+AddDateII(86)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_87',title: '<b>'+AddDateII(87)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_88',title: '<b>'+AddDateII(88)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_89',title: '<b>'+AddDateII(89)+'</b>', width:100, halign: 'center', align: 'right'},
				{field:'N_90',title: '<b>'+AddDateII(90)+'</b>', width:100, halign: 'center', align: 'right'}
			]],
			onDblClickRow:function(row){
				var row = $('#dg').datagrid('getSelected');
				var ty = row.TIPE;
				var item = row.ITEM_NO;
				var item_desc = row.ITEM_DESC;
				//alert(ty);
				pdf_url = '?cmb_type='+ty+'&cmb_item_no='+item+'&item_name='+item_desc+'&from=ITO';
				var url = 'mrp_rm_plan.php'+encodeURIComponent(pdf_url);
				window.open(decodeURIComponent(url));
			}
	    });

		var view_p = parseInt($('#cmb_day').combobox('getValue'));
		if (ck_day != 'true'){
			if(view_p == 5){
				$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('hideColumn','N_31');			$('#dg').datagrid('hideColumn','N_61');
				$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('hideColumn','N_32');			$('#dg').datagrid('hideColumn','N_62');
				$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('hideColumn','N_33');			$('#dg').datagrid('hideColumn','N_63');
				$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('hideColumn','N_34');			$('#dg').datagrid('hideColumn','N_64');
				$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('hideColumn','N_35');			$('#dg').datagrid('hideColumn','N_65');
				$('#dg').datagrid('hideColumn','N_6');			$('#dg').datagrid('hideColumn','N_36');			$('#dg').datagrid('hideColumn','N_66');
				$('#dg').datagrid('hideColumn','N_7');			$('#dg').datagrid('hideColumn','N_37');			$('#dg').datagrid('hideColumn','N_67');
				$('#dg').datagrid('hideColumn','N_8');			$('#dg').datagrid('hideColumn','N_38');			$('#dg').datagrid('hideColumn','N_68');
				$('#dg').datagrid('hideColumn','N_9');			$('#dg').datagrid('hideColumn','N_39');			$('#dg').datagrid('hideColumn','N_69');
				$('#dg').datagrid('hideColumn','N_10');			$('#dg').datagrid('hideColumn','N_40');			$('#dg').datagrid('hideColumn','N_70');
				$('#dg').datagrid('hideColumn','N_11');			$('#dg').datagrid('hideColumn','N_41');			$('#dg').datagrid('hideColumn','N_71');
				$('#dg').datagrid('hideColumn','N_12');			$('#dg').datagrid('hideColumn','N_42');			$('#dg').datagrid('hideColumn','N_72');
				$('#dg').datagrid('hideColumn','N_13');			$('#dg').datagrid('hideColumn','N_43');			$('#dg').datagrid('hideColumn','N_73');
				$('#dg').datagrid('hideColumn','N_14');			$('#dg').datagrid('hideColumn','N_44');			$('#dg').datagrid('hideColumn','N_74');
				$('#dg').datagrid('hideColumn','N_15');			$('#dg').datagrid('hideColumn','N_45');			$('#dg').datagrid('hideColumn','N_75');
				$('#dg').datagrid('hideColumn','N_16');			$('#dg').datagrid('hideColumn','N_46');			$('#dg').datagrid('hideColumn','N_76');
				$('#dg').datagrid('hideColumn','N_17');			$('#dg').datagrid('hideColumn','N_47');			$('#dg').datagrid('hideColumn','N_77');
				$('#dg').datagrid('hideColumn','N_18');			$('#dg').datagrid('hideColumn','N_48');			$('#dg').datagrid('hideColumn','N_78');
				$('#dg').datagrid('hideColumn','N_19');			$('#dg').datagrid('hideColumn','N_49');			$('#dg').datagrid('hideColumn','N_79');
				$('#dg').datagrid('hideColumn','N_20');			$('#dg').datagrid('hideColumn','N_50');			$('#dg').datagrid('hideColumn','N_80');
				$('#dg').datagrid('hideColumn','N_21');			$('#dg').datagrid('hideColumn','N_51');			$('#dg').datagrid('hideColumn','N_81');
				$('#dg').datagrid('hideColumn','N_22');			$('#dg').datagrid('hideColumn','N_52');			$('#dg').datagrid('hideColumn','N_82');
				$('#dg').datagrid('hideColumn','N_23');			$('#dg').datagrid('hideColumn','N_53');			$('#dg').datagrid('hideColumn','N_83');
				$('#dg').datagrid('hideColumn','N_24');			$('#dg').datagrid('hideColumn','N_54');			$('#dg').datagrid('hideColumn','N_84');
				$('#dg').datagrid('hideColumn','N_25');			$('#dg').datagrid('hideColumn','N_55');			$('#dg').datagrid('hideColumn','N_85');
				$('#dg').datagrid('hideColumn','N_26');			$('#dg').datagrid('hideColumn','N_56');			$('#dg').datagrid('hideColumn','N_86');
				$('#dg').datagrid('hideColumn','N_27');			$('#dg').datagrid('hideColumn','N_57');			$('#dg').datagrid('hideColumn','N_87');
				$('#dg').datagrid('hideColumn','N_28');			$('#dg').datagrid('hideColumn','N_58');			$('#dg').datagrid('hideColumn','N_88');
				$('#dg').datagrid('hideColumn','N_29');			$('#dg').datagrid('hideColumn','N_59');			$('#dg').datagrid('hideColumn','N_89');
				$('#dg').datagrid('hideColumn','N_30');			$('#dg').datagrid('hideColumn','N_60');			$('#dg').datagrid('hideColumn','N_90');
			}else if (view_p == 10){
				$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('hideColumn','N_31');			$('#dg').datagrid('hideColumn','N_61');
				$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('hideColumn','N_32');			$('#dg').datagrid('hideColumn','N_62');
				$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('hideColumn','N_33');			$('#dg').datagrid('hideColumn','N_63');
				$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('hideColumn','N_34');			$('#dg').datagrid('hideColumn','N_64');
				$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('hideColumn','N_35');			$('#dg').datagrid('hideColumn','N_65');
				$('#dg').datagrid('showColumn','N_6');			$('#dg').datagrid('hideColumn','N_36');			$('#dg').datagrid('hideColumn','N_66');
				$('#dg').datagrid('showColumn','N_7');			$('#dg').datagrid('hideColumn','N_37');			$('#dg').datagrid('hideColumn','N_67');
				$('#dg').datagrid('showColumn','N_8');			$('#dg').datagrid('hideColumn','N_38');			$('#dg').datagrid('hideColumn','N_68');
				$('#dg').datagrid('showColumn','N_9');			$('#dg').datagrid('hideColumn','N_39');			$('#dg').datagrid('hideColumn','N_69');
				$('#dg').datagrid('showColumn','N_10');			$('#dg').datagrid('hideColumn','N_40');			$('#dg').datagrid('hideColumn','N_70');
				$('#dg').datagrid('hideColumn','N_11');			$('#dg').datagrid('hideColumn','N_41');			$('#dg').datagrid('hideColumn','N_71');
				$('#dg').datagrid('hideColumn','N_12');			$('#dg').datagrid('hideColumn','N_42');			$('#dg').datagrid('hideColumn','N_72');
				$('#dg').datagrid('hideColumn','N_13');			$('#dg').datagrid('hideColumn','N_43');			$('#dg').datagrid('hideColumn','N_73');
				$('#dg').datagrid('hideColumn','N_14');			$('#dg').datagrid('hideColumn','N_44');			$('#dg').datagrid('hideColumn','N_74');
				$('#dg').datagrid('hideColumn','N_15');			$('#dg').datagrid('hideColumn','N_45');			$('#dg').datagrid('hideColumn','N_75');
				$('#dg').datagrid('hideColumn','N_16');			$('#dg').datagrid('hideColumn','N_46');			$('#dg').datagrid('hideColumn','N_76');
				$('#dg').datagrid('hideColumn','N_17');			$('#dg').datagrid('hideColumn','N_47');			$('#dg').datagrid('hideColumn','N_77');
				$('#dg').datagrid('hideColumn','N_18');			$('#dg').datagrid('hideColumn','N_48');			$('#dg').datagrid('hideColumn','N_78');
				$('#dg').datagrid('hideColumn','N_19');			$('#dg').datagrid('hideColumn','N_49');			$('#dg').datagrid('hideColumn','N_79');
				$('#dg').datagrid('hideColumn','N_20');			$('#dg').datagrid('hideColumn','N_50');			$('#dg').datagrid('hideColumn','N_80');
				$('#dg').datagrid('hideColumn','N_21');			$('#dg').datagrid('hideColumn','N_51');			$('#dg').datagrid('hideColumn','N_81');
				$('#dg').datagrid('hideColumn','N_22');			$('#dg').datagrid('hideColumn','N_52');			$('#dg').datagrid('hideColumn','N_82');
				$('#dg').datagrid('hideColumn','N_23');			$('#dg').datagrid('hideColumn','N_53');			$('#dg').datagrid('hideColumn','N_83');
				$('#dg').datagrid('hideColumn','N_24');			$('#dg').datagrid('hideColumn','N_54');			$('#dg').datagrid('hideColumn','N_84');
				$('#dg').datagrid('hideColumn','N_25');			$('#dg').datagrid('hideColumn','N_55');			$('#dg').datagrid('hideColumn','N_85');
				$('#dg').datagrid('hideColumn','N_26');			$('#dg').datagrid('hideColumn','N_56');			$('#dg').datagrid('hideColumn','N_86');
				$('#dg').datagrid('hideColumn','N_27');			$('#dg').datagrid('hideColumn','N_57');			$('#dg').datagrid('hideColumn','N_87');
				$('#dg').datagrid('hideColumn','N_28');			$('#dg').datagrid('hideColumn','N_58');			$('#dg').datagrid('hideColumn','N_88');
				$('#dg').datagrid('hideColumn','N_29');			$('#dg').datagrid('hideColumn','N_59');			$('#dg').datagrid('hideColumn','N_89');
				$('#dg').datagrid('hideColumn','N_30');			$('#dg').datagrid('hideColumn','N_60');			$('#dg').datagrid('hideColumn','N_90');
			}else if (view_p == 20){
				$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('hideColumn','N_31');			$('#dg').datagrid('hideColumn','N_61');
				$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('hideColumn','N_32');			$('#dg').datagrid('hideColumn','N_62');
				$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('hideColumn','N_33');			$('#dg').datagrid('hideColumn','N_63');
				$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('hideColumn','N_34');			$('#dg').datagrid('hideColumn','N_64');
				$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('hideColumn','N_35');			$('#dg').datagrid('hideColumn','N_65');
				$('#dg').datagrid('showColumn','N_6');			$('#dg').datagrid('hideColumn','N_36');			$('#dg').datagrid('hideColumn','N_66');
				$('#dg').datagrid('showColumn','N_7');			$('#dg').datagrid('hideColumn','N_37');			$('#dg').datagrid('hideColumn','N_67');
				$('#dg').datagrid('showColumn','N_8');			$('#dg').datagrid('hideColumn','N_38');			$('#dg').datagrid('hideColumn','N_68');
				$('#dg').datagrid('showColumn','N_9');			$('#dg').datagrid('hideColumn','N_39');			$('#dg').datagrid('hideColumn','N_69');
				$('#dg').datagrid('showColumn','N_10');			$('#dg').datagrid('hideColumn','N_40');			$('#dg').datagrid('hideColumn','N_70');
				$('#dg').datagrid('showColumn','N_11');			$('#dg').datagrid('hideColumn','N_41');			$('#dg').datagrid('hideColumn','N_71');
				$('#dg').datagrid('showColumn','N_12');			$('#dg').datagrid('hideColumn','N_42');			$('#dg').datagrid('hideColumn','N_72');
				$('#dg').datagrid('showColumn','N_13');			$('#dg').datagrid('hideColumn','N_43');			$('#dg').datagrid('hideColumn','N_73');
				$('#dg').datagrid('showColumn','N_14');			$('#dg').datagrid('hideColumn','N_44');			$('#dg').datagrid('hideColumn','N_74');
				$('#dg').datagrid('showColumn','N_15');			$('#dg').datagrid('hideColumn','N_45');			$('#dg').datagrid('hideColumn','N_75');
				$('#dg').datagrid('showColumn','N_16');			$('#dg').datagrid('hideColumn','N_46');			$('#dg').datagrid('hideColumn','N_76');
				$('#dg').datagrid('showColumn','N_17');			$('#dg').datagrid('hideColumn','N_47');			$('#dg').datagrid('hideColumn','N_77');
				$('#dg').datagrid('showColumn','N_18');			$('#dg').datagrid('hideColumn','N_48');			$('#dg').datagrid('hideColumn','N_78');
				$('#dg').datagrid('showColumn','N_19');			$('#dg').datagrid('hideColumn','N_49');			$('#dg').datagrid('hideColumn','N_79');
				$('#dg').datagrid('showColumn','N_20');			$('#dg').datagrid('hideColumn','N_50');			$('#dg').datagrid('hideColumn','N_80');
				$('#dg').datagrid('hideColumn','N_21');			$('#dg').datagrid('hideColumn','N_51');			$('#dg').datagrid('hideColumn','N_81');
				$('#dg').datagrid('hideColumn','N_22');			$('#dg').datagrid('hideColumn','N_52');			$('#dg').datagrid('hideColumn','N_82');
				$('#dg').datagrid('hideColumn','N_23');			$('#dg').datagrid('hideColumn','N_53');			$('#dg').datagrid('hideColumn','N_83');
				$('#dg').datagrid('hideColumn','N_24');			$('#dg').datagrid('hideColumn','N_54');			$('#dg').datagrid('hideColumn','N_84');
				$('#dg').datagrid('hideColumn','N_25');			$('#dg').datagrid('hideColumn','N_55');			$('#dg').datagrid('hideColumn','N_85');
				$('#dg').datagrid('hideColumn','N_26');			$('#dg').datagrid('hideColumn','N_56');			$('#dg').datagrid('hideColumn','N_86');
				$('#dg').datagrid('hideColumn','N_27');			$('#dg').datagrid('hideColumn','N_57');			$('#dg').datagrid('hideColumn','N_87');
				$('#dg').datagrid('hideColumn','N_28');			$('#dg').datagrid('hideColumn','N_58');			$('#dg').datagrid('hideColumn','N_88');
				$('#dg').datagrid('hideColumn','N_29');			$('#dg').datagrid('hideColumn','N_59');			$('#dg').datagrid('hideColumn','N_89');
				$('#dg').datagrid('hideColumn','N_30');			$('#dg').datagrid('hideColumn','N_60');			$('#dg').datagrid('hideColumn','N_90');
			}else if (view_p == 30){
				$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('hideColumn','N_31');			$('#dg').datagrid('hideColumn','N_61');
				$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('hideColumn','N_32');			$('#dg').datagrid('hideColumn','N_62');
				$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('hideColumn','N_33');			$('#dg').datagrid('hideColumn','N_63');
				$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('hideColumn','N_34');			$('#dg').datagrid('hideColumn','N_64');
				$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('hideColumn','N_35');			$('#dg').datagrid('hideColumn','N_65');
				$('#dg').datagrid('showColumn','N_6');			$('#dg').datagrid('hideColumn','N_36');			$('#dg').datagrid('hideColumn','N_66');
				$('#dg').datagrid('showColumn','N_7');			$('#dg').datagrid('hideColumn','N_37');			$('#dg').datagrid('hideColumn','N_67');
				$('#dg').datagrid('showColumn','N_8');			$('#dg').datagrid('hideColumn','N_38');			$('#dg').datagrid('hideColumn','N_68');
				$('#dg').datagrid('showColumn','N_9');			$('#dg').datagrid('hideColumn','N_39');			$('#dg').datagrid('hideColumn','N_69');
				$('#dg').datagrid('showColumn','N_10');			$('#dg').datagrid('hideColumn','N_40');			$('#dg').datagrid('hideColumn','N_70');
				$('#dg').datagrid('showColumn','N_11');			$('#dg').datagrid('hideColumn','N_41');			$('#dg').datagrid('hideColumn','N_71');
				$('#dg').datagrid('showColumn','N_12');			$('#dg').datagrid('hideColumn','N_42');			$('#dg').datagrid('hideColumn','N_72');
				$('#dg').datagrid('showColumn','N_13');			$('#dg').datagrid('hideColumn','N_43');			$('#dg').datagrid('hideColumn','N_73');
				$('#dg').datagrid('showColumn','N_14');			$('#dg').datagrid('hideColumn','N_44');			$('#dg').datagrid('hideColumn','N_74');
				$('#dg').datagrid('showColumn','N_15');			$('#dg').datagrid('hideColumn','N_45');			$('#dg').datagrid('hideColumn','N_75');
				$('#dg').datagrid('showColumn','N_16');			$('#dg').datagrid('hideColumn','N_46');			$('#dg').datagrid('hideColumn','N_76');
				$('#dg').datagrid('showColumn','N_17');			$('#dg').datagrid('hideColumn','N_47');			$('#dg').datagrid('hideColumn','N_77');
				$('#dg').datagrid('showColumn','N_18');			$('#dg').datagrid('hideColumn','N_48');			$('#dg').datagrid('hideColumn','N_78');
				$('#dg').datagrid('showColumn','N_19');			$('#dg').datagrid('hideColumn','N_49');			$('#dg').datagrid('hideColumn','N_79');
				$('#dg').datagrid('showColumn','N_20');			$('#dg').datagrid('hideColumn','N_50');			$('#dg').datagrid('hideColumn','N_80');
				$('#dg').datagrid('showColumn','N_21');			$('#dg').datagrid('hideColumn','N_51');			$('#dg').datagrid('hideColumn','N_81');
				$('#dg').datagrid('showColumn','N_22');			$('#dg').datagrid('hideColumn','N_52');			$('#dg').datagrid('hideColumn','N_82');
				$('#dg').datagrid('showColumn','N_23');			$('#dg').datagrid('hideColumn','N_53');			$('#dg').datagrid('hideColumn','N_83');
				$('#dg').datagrid('showColumn','N_24');			$('#dg').datagrid('hideColumn','N_54');			$('#dg').datagrid('hideColumn','N_84');
				$('#dg').datagrid('showColumn','N_25');			$('#dg').datagrid('hideColumn','N_55');			$('#dg').datagrid('hideColumn','N_85');
				$('#dg').datagrid('showColumn','N_26');			$('#dg').datagrid('hideColumn','N_56');			$('#dg').datagrid('hideColumn','N_86');
				$('#dg').datagrid('showColumn','N_27');			$('#dg').datagrid('hideColumn','N_57');			$('#dg').datagrid('hideColumn','N_87');
				$('#dg').datagrid('showColumn','N_28');			$('#dg').datagrid('hideColumn','N_58');			$('#dg').datagrid('hideColumn','N_88');
				$('#dg').datagrid('showColumn','N_29');			$('#dg').datagrid('hideColumn','N_59');			$('#dg').datagrid('hideColumn','N_89');
				$('#dg').datagrid('showColumn','N_30');			$('#dg').datagrid('hideColumn','N_60');			$('#dg').datagrid('hideColumn','N_90');
			}else if (view_p == 40){
				$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('showColumn','N_31');			$('#dg').datagrid('hideColumn','N_61');
				$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('showColumn','N_32');			$('#dg').datagrid('hideColumn','N_62');
				$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('showColumn','N_33');			$('#dg').datagrid('hideColumn','N_63');
				$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('showColumn','N_34');			$('#dg').datagrid('hideColumn','N_64');
				$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('showColumn','N_35');			$('#dg').datagrid('hideColumn','N_65');
				$('#dg').datagrid('hideColumn','N_6');			$('#dg').datagrid('showColumn','N_36');			$('#dg').datagrid('hideColumn','N_66');
				$('#dg').datagrid('showColumn','N_7');			$('#dg').datagrid('showColumn','N_37');			$('#dg').datagrid('hideColumn','N_67');
				$('#dg').datagrid('showColumn','N_8');			$('#dg').datagrid('showColumn','N_38');			$('#dg').datagrid('hideColumn','N_68');
				$('#dg').datagrid('showColumn','N_9');			$('#dg').datagrid('showColumn','N_39');			$('#dg').datagrid('hideColumn','N_69');
				$('#dg').datagrid('showColumn','N_10');			$('#dg').datagrid('showColumn','N_40');			$('#dg').datagrid('hideColumn','N_70');
				$('#dg').datagrid('showColumn','N_11');			$('#dg').datagrid('hideColumn','N_41');			$('#dg').datagrid('hideColumn','N_71');
				$('#dg').datagrid('showColumn','N_12');			$('#dg').datagrid('hideColumn','N_42');			$('#dg').datagrid('hideColumn','N_72');
				$('#dg').datagrid('showColumn','N_13');			$('#dg').datagrid('hideColumn','N_43');			$('#dg').datagrid('hideColumn','N_73');
				$('#dg').datagrid('showColumn','N_14');			$('#dg').datagrid('hideColumn','N_44');			$('#dg').datagrid('hideColumn','N_74');
				$('#dg').datagrid('showColumn','N_15');			$('#dg').datagrid('hideColumn','N_45');			$('#dg').datagrid('hideColumn','N_75');
				$('#dg').datagrid('showColumn','N_16');			$('#dg').datagrid('hideColumn','N_46');			$('#dg').datagrid('hideColumn','N_76');
				$('#dg').datagrid('showColumn','N_17');			$('#dg').datagrid('hideColumn','N_47');			$('#dg').datagrid('hideColumn','N_77');
				$('#dg').datagrid('showColumn','N_18');			$('#dg').datagrid('hideColumn','N_48');			$('#dg').datagrid('hideColumn','N_78');
				$('#dg').datagrid('showColumn','N_19');			$('#dg').datagrid('hideColumn','N_49');			$('#dg').datagrid('hideColumn','N_79');
				$('#dg').datagrid('showColumn','N_20');			$('#dg').datagrid('hideColumn','N_50');			$('#dg').datagrid('hideColumn','N_80');
				$('#dg').datagrid('showColumn','N_21');			$('#dg').datagrid('hideColumn','N_51');			$('#dg').datagrid('hideColumn','N_81');
				$('#dg').datagrid('showColumn','N_22');			$('#dg').datagrid('hideColumn','N_52');			$('#dg').datagrid('hideColumn','N_82');
				$('#dg').datagrid('showColumn','N_23');			$('#dg').datagrid('hideColumn','N_53');			$('#dg').datagrid('hideColumn','N_83');
				$('#dg').datagrid('showColumn','N_24');			$('#dg').datagrid('hideColumn','N_54');			$('#dg').datagrid('hideColumn','N_84');
				$('#dg').datagrid('showColumn','N_25');			$('#dg').datagrid('hideColumn','N_55');			$('#dg').datagrid('hideColumn','N_85');
				$('#dg').datagrid('showColumn','N_26');			$('#dg').datagrid('hideColumn','N_56');			$('#dg').datagrid('hideColumn','N_86');
				$('#dg').datagrid('showColumn','N_27');			$('#dg').datagrid('hideColumn','N_57');			$('#dg').datagrid('hideColumn','N_87');
				$('#dg').datagrid('showColumn','N_28');			$('#dg').datagrid('hideColumn','N_58');			$('#dg').datagrid('hideColumn','N_88');
				$('#dg').datagrid('showColumn','N_29');			$('#dg').datagrid('hideColumn','N_59');			$('#dg').datagrid('hideColumn','N_89');
				$('#dg').datagrid('showColumn','N_30');			$('#dg').datagrid('hideColumn','N_60');			$('#dg').datagrid('hideColumn','N_90');
			}else if (view_p == 50){
				$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('showColumn','N_31');			$('#dg').datagrid('hideColumn','N_61');
				$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('showColumn','N_32');			$('#dg').datagrid('hideColumn','N_62');
				$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('showColumn','N_33');			$('#dg').datagrid('hideColumn','N_63');
				$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('showColumn','N_34');			$('#dg').datagrid('hideColumn','N_64');
				$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('showColumn','N_35');			$('#dg').datagrid('hideColumn','N_65');
				$('#dg').datagrid('showColumn','N_6');			$('#dg').datagrid('showColumn','N_36');			$('#dg').datagrid('hideColumn','N_66');
				$('#dg').datagrid('showColumn','N_7');			$('#dg').datagrid('showColumn','N_37');			$('#dg').datagrid('hideColumn','N_67');
				$('#dg').datagrid('showColumn','N_8');			$('#dg').datagrid('showColumn','N_38');			$('#dg').datagrid('hideColumn','N_68');
				$('#dg').datagrid('showColumn','N_9');			$('#dg').datagrid('showColumn','N_39');			$('#dg').datagrid('hideColumn','N_69');
				$('#dg').datagrid('showColumn','N_10');			$('#dg').datagrid('showColumn','N_40');			$('#dg').datagrid('hideColumn','N_70');
				$('#dg').datagrid('showColumn','N_11');			$('#dg').datagrid('showColumn','N_41');			$('#dg').datagrid('hideColumn','N_71');
				$('#dg').datagrid('showColumn','N_12');			$('#dg').datagrid('showColumn','N_42');			$('#dg').datagrid('hideColumn','N_72');
				$('#dg').datagrid('showColumn','N_13');			$('#dg').datagrid('showColumn','N_43');			$('#dg').datagrid('hideColumn','N_73');
				$('#dg').datagrid('showColumn','N_14');			$('#dg').datagrid('showColumn','N_44');			$('#dg').datagrid('hideColumn','N_74');
				$('#dg').datagrid('showColumn','N_15');			$('#dg').datagrid('showColumn','N_45');			$('#dg').datagrid('hideColumn','N_75');
				$('#dg').datagrid('showColumn','N_16');			$('#dg').datagrid('showColumn','N_46');			$('#dg').datagrid('hideColumn','N_76');
				$('#dg').datagrid('showColumn','N_17');			$('#dg').datagrid('showColumn','N_47');			$('#dg').datagrid('hideColumn','N_77');
				$('#dg').datagrid('showColumn','N_18');			$('#dg').datagrid('showColumn','N_48');			$('#dg').datagrid('hideColumn','N_78');
				$('#dg').datagrid('showColumn','N_19');			$('#dg').datagrid('showColumn','N_49');			$('#dg').datagrid('hideColumn','N_79');
				$('#dg').datagrid('showColumn','N_20');			$('#dg').datagrid('showColumn','N_50');			$('#dg').datagrid('hideColumn','N_80');
				$('#dg').datagrid('showColumn','N_21');			$('#dg').datagrid('hideColumn','N_51');			$('#dg').datagrid('hideColumn','N_81');
				$('#dg').datagrid('showColumn','N_22');			$('#dg').datagrid('hideColumn','N_52');			$('#dg').datagrid('hideColumn','N_82');
				$('#dg').datagrid('showColumn','N_23');			$('#dg').datagrid('hideColumn','N_53');			$('#dg').datagrid('hideColumn','N_83');
				$('#dg').datagrid('showColumn','N_24');			$('#dg').datagrid('hideColumn','N_54');			$('#dg').datagrid('hideColumn','N_84');
				$('#dg').datagrid('showColumn','N_25');			$('#dg').datagrid('hideColumn','N_55');			$('#dg').datagrid('hideColumn','N_85');
				$('#dg').datagrid('showColumn','N_26');			$('#dg').datagrid('hideColumn','N_56');			$('#dg').datagrid('hideColumn','N_86');
				$('#dg').datagrid('showColumn','N_27');			$('#dg').datagrid('hideColumn','N_57');			$('#dg').datagrid('hideColumn','N_87');
				$('#dg').datagrid('showColumn','N_28');			$('#dg').datagrid('hideColumn','N_58');			$('#dg').datagrid('hideColumn','N_88');
				$('#dg').datagrid('showColumn','N_29');			$('#dg').datagrid('hideColumn','N_59');			$('#dg').datagrid('hideColumn','N_89');
				$('#dg').datagrid('showColumn','N_30');			$('#dg').datagrid('hideColumn','N_60');			$('#dg').datagrid('hideColumn','N_90');
			}else if (view_p == 60){
				$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('showColumn','N_31');			$('#dg').datagrid('hideColumn','N_61');
				$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('showColumn','N_32');			$('#dg').datagrid('hideColumn','N_62');
				$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('showColumn','N_33');			$('#dg').datagrid('hideColumn','N_63');
				$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('showColumn','N_34');			$('#dg').datagrid('hideColumn','N_64');
				$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('showColumn','N_35');			$('#dg').datagrid('hideColumn','N_65');
				$('#dg').datagrid('showColumn','N_6');			$('#dg').datagrid('showColumn','N_36');			$('#dg').datagrid('hideColumn','N_66');
				$('#dg').datagrid('showColumn','N_7');			$('#dg').datagrid('showColumn','N_37');			$('#dg').datagrid('hideColumn','N_67');
				$('#dg').datagrid('showColumn','N_8');			$('#dg').datagrid('showColumn','N_38');			$('#dg').datagrid('hideColumn','N_68');
				$('#dg').datagrid('showColumn','N_9');			$('#dg').datagrid('showColumn','N_39');			$('#dg').datagrid('hideColumn','N_69');
				$('#dg').datagrid('showColumn','N_10');			$('#dg').datagrid('showColumn','N_40');			$('#dg').datagrid('hideColumn','N_70');
				$('#dg').datagrid('showColumn','N_11');			$('#dg').datagrid('showColumn','N_41');			$('#dg').datagrid('hideColumn','N_71');
				$('#dg').datagrid('showColumn','N_12');			$('#dg').datagrid('showColumn','N_42');			$('#dg').datagrid('hideColumn','N_72');
				$('#dg').datagrid('showColumn','N_13');			$('#dg').datagrid('showColumn','N_43');			$('#dg').datagrid('hideColumn','N_73');
				$('#dg').datagrid('showColumn','N_14');			$('#dg').datagrid('showColumn','N_44');			$('#dg').datagrid('hideColumn','N_74');
				$('#dg').datagrid('showColumn','N_15');			$('#dg').datagrid('showColumn','N_45');			$('#dg').datagrid('hideColumn','N_75');
				$('#dg').datagrid('showColumn','N_16');			$('#dg').datagrid('showColumn','N_46');			$('#dg').datagrid('hideColumn','N_76');
				$('#dg').datagrid('showColumn','N_17');			$('#dg').datagrid('showColumn','N_47');			$('#dg').datagrid('hideColumn','N_77');
				$('#dg').datagrid('showColumn','N_18');			$('#dg').datagrid('showColumn','N_48');			$('#dg').datagrid('hideColumn','N_78');
				$('#dg').datagrid('showColumn','N_19');			$('#dg').datagrid('showColumn','N_49');			$('#dg').datagrid('hideColumn','N_79');
				$('#dg').datagrid('showColumn','N_20');			$('#dg').datagrid('showColumn','N_50');			$('#dg').datagrid('hideColumn','N_80');
				$('#dg').datagrid('showColumn','N_21');			$('#dg').datagrid('showColumn','N_51');			$('#dg').datagrid('hideColumn','N_81');
				$('#dg').datagrid('showColumn','N_22');			$('#dg').datagrid('showColumn','N_52');			$('#dg').datagrid('hideColumn','N_82');
				$('#dg').datagrid('showColumn','N_23');			$('#dg').datagrid('showColumn','N_53');			$('#dg').datagrid('hideColumn','N_83');
				$('#dg').datagrid('showColumn','N_24');			$('#dg').datagrid('showColumn','N_54');			$('#dg').datagrid('hideColumn','N_84');
				$('#dg').datagrid('showColumn','N_25');			$('#dg').datagrid('showColumn','N_55');			$('#dg').datagrid('hideColumn','N_85');
				$('#dg').datagrid('showColumn','N_26');			$('#dg').datagrid('showColumn','N_56');			$('#dg').datagrid('hideColumn','N_86');
				$('#dg').datagrid('showColumn','N_27');			$('#dg').datagrid('showColumn','N_57');			$('#dg').datagrid('hideColumn','N_87');
				$('#dg').datagrid('showColumn','N_28');			$('#dg').datagrid('showColumn','N_58');			$('#dg').datagrid('hideColumn','N_88');
				$('#dg').datagrid('showColumn','N_29');			$('#dg').datagrid('showColumn','N_59');			$('#dg').datagrid('hideColumn','N_89');
				$('#dg').datagrid('showColumn','N_30');			$('#dg').datagrid('showColumn','N_60');			$('#dg').datagrid('hideColumn','N_90');
			}else if (view_p == 70){
				$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('showColumn','N_31');			$('#dg').datagrid('showColumn','N_61');
				$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('showColumn','N_32');			$('#dg').datagrid('showColumn','N_62');
				$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('showColumn','N_33');			$('#dg').datagrid('showColumn','N_63');
				$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('showColumn','N_34');			$('#dg').datagrid('showColumn','N_64');
				$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('showColumn','N_35');			$('#dg').datagrid('showColumn','N_65');
				$('#dg').datagrid('showColumn','N_6');			$('#dg').datagrid('showColumn','N_36');			$('#dg').datagrid('showColumn','N_66');
				$('#dg').datagrid('showColumn','N_7');			$('#dg').datagrid('showColumn','N_37');			$('#dg').datagrid('showColumn','N_67');
				$('#dg').datagrid('showColumn','N_8');			$('#dg').datagrid('showColumn','N_38');			$('#dg').datagrid('showColumn','N_68');
				$('#dg').datagrid('showColumn','N_9');			$('#dg').datagrid('showColumn','N_39');			$('#dg').datagrid('showColumn','N_69');
				$('#dg').datagrid('showColumn','N_10');			$('#dg').datagrid('showColumn','N_40');			$('#dg').datagrid('showColumn','N_70');
				$('#dg').datagrid('showColumn','N_11');			$('#dg').datagrid('showColumn','N_41');			$('#dg').datagrid('hideColumn','N_71');
				$('#dg').datagrid('showColumn','N_12');			$('#dg').datagrid('showColumn','N_42');			$('#dg').datagrid('hideColumn','N_72');
				$('#dg').datagrid('showColumn','N_13');			$('#dg').datagrid('showColumn','N_43');			$('#dg').datagrid('hideColumn','N_73');
				$('#dg').datagrid('showColumn','N_14');			$('#dg').datagrid('showColumn','N_44');			$('#dg').datagrid('hideColumn','N_74');
				$('#dg').datagrid('showColumn','N_15');			$('#dg').datagrid('showColumn','N_45');			$('#dg').datagrid('hideColumn','N_75');
				$('#dg').datagrid('showColumn','N_16');			$('#dg').datagrid('showColumn','N_46');			$('#dg').datagrid('hideColumn','N_76');
				$('#dg').datagrid('showColumn','N_17');			$('#dg').datagrid('showColumn','N_47');			$('#dg').datagrid('hideColumn','N_77');
				$('#dg').datagrid('showColumn','N_18');			$('#dg').datagrid('showColumn','N_48');			$('#dg').datagrid('hideColumn','N_78');
				$('#dg').datagrid('showColumn','N_19');			$('#dg').datagrid('showColumn','N_49');			$('#dg').datagrid('hideColumn','N_79');
				$('#dg').datagrid('showColumn','N_20');			$('#dg').datagrid('showColumn','N_50');			$('#dg').datagrid('hideColumn','N_80');
				$('#dg').datagrid('showColumn','N_21');			$('#dg').datagrid('showColumn','N_51');			$('#dg').datagrid('hideColumn','N_81');
				$('#dg').datagrid('showColumn','N_22');			$('#dg').datagrid('showColumn','N_52');			$('#dg').datagrid('hideColumn','N_82');
				$('#dg').datagrid('showColumn','N_23');			$('#dg').datagrid('showColumn','N_53');			$('#dg').datagrid('hideColumn','N_83');
				$('#dg').datagrid('showColumn','N_24');			$('#dg').datagrid('showColumn','N_54');			$('#dg').datagrid('hideColumn','N_84');
				$('#dg').datagrid('showColumn','N_25');			$('#dg').datagrid('showColumn','N_55');			$('#dg').datagrid('hideColumn','N_85');
				$('#dg').datagrid('showColumn','N_26');			$('#dg').datagrid('showColumn','N_56');			$('#dg').datagrid('hideColumn','N_86');
				$('#dg').datagrid('showColumn','N_27');			$('#dg').datagrid('showColumn','N_57');			$('#dg').datagrid('hideColumn','N_87');
				$('#dg').datagrid('showColumn','N_28');			$('#dg').datagrid('showColumn','N_58');			$('#dg').datagrid('hideColumn','N_88');
				$('#dg').datagrid('showColumn','N_29');			$('#dg').datagrid('showColumn','N_59');			$('#dg').datagrid('hideColumn','N_89');
				$('#dg').datagrid('showColumn','N_30');			$('#dg').datagrid('showColumn','N_60');			$('#dg').datagrid('hideColumn','N_90');
			}else if (view_p == 80){
				$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('showColumn','N_31');			$('#dg').datagrid('showColumn','N_61');
				$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('showColumn','N_32');			$('#dg').datagrid('showColumn','N_62');
				$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('showColumn','N_33');			$('#dg').datagrid('showColumn','N_63');
				$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('showColumn','N_34');			$('#dg').datagrid('showColumn','N_64');
				$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('showColumn','N_35');			$('#dg').datagrid('showColumn','N_65');
				$('#dg').datagrid('showColumn','N_6');			$('#dg').datagrid('showColumn','N_36');			$('#dg').datagrid('showColumn','N_66');
				$('#dg').datagrid('showColumn','N_7');			$('#dg').datagrid('showColumn','N_37');			$('#dg').datagrid('showColumn','N_67');
				$('#dg').datagrid('showColumn','N_8');			$('#dg').datagrid('showColumn','N_38');			$('#dg').datagrid('showColumn','N_68');
				$('#dg').datagrid('showColumn','N_9');			$('#dg').datagrid('showColumn','N_39');			$('#dg').datagrid('showColumn','N_69');
				$('#dg').datagrid('showColumn','N_10');			$('#dg').datagrid('showColumn','N_40');			$('#dg').datagrid('showColumn','N_70');
				$('#dg').datagrid('showColumn','N_11');			$('#dg').datagrid('showColumn','N_41');			$('#dg').datagrid('showColumn','N_71');
				$('#dg').datagrid('showColumn','N_12');			$('#dg').datagrid('showColumn','N_42');			$('#dg').datagrid('showColumn','N_72');
				$('#dg').datagrid('showColumn','N_13');			$('#dg').datagrid('showColumn','N_43');			$('#dg').datagrid('showColumn','N_73');
				$('#dg').datagrid('showColumn','N_14');			$('#dg').datagrid('showColumn','N_44');			$('#dg').datagrid('showColumn','N_74');
				$('#dg').datagrid('showColumn','N_15');			$('#dg').datagrid('showColumn','N_45');			$('#dg').datagrid('showColumn','N_75');
				$('#dg').datagrid('showColumn','N_16');			$('#dg').datagrid('showColumn','N_46');			$('#dg').datagrid('showColumn','N_76');
				$('#dg').datagrid('showColumn','N_17');			$('#dg').datagrid('showColumn','N_47');			$('#dg').datagrid('showColumn','N_77');
				$('#dg').datagrid('showColumn','N_18');			$('#dg').datagrid('showColumn','N_48');			$('#dg').datagrid('showColumn','N_78');
				$('#dg').datagrid('showColumn','N_19');			$('#dg').datagrid('showColumn','N_49');			$('#dg').datagrid('showColumn','N_79');
				$('#dg').datagrid('showColumn','N_20');			$('#dg').datagrid('showColumn','N_50');			$('#dg').datagrid('showColumn','N_80');
				$('#dg').datagrid('showColumn','N_21');			$('#dg').datagrid('showColumn','N_51');			$('#dg').datagrid('hideColumn','N_81');
				$('#dg').datagrid('showColumn','N_22');			$('#dg').datagrid('showColumn','N_52');			$('#dg').datagrid('hideColumn','N_82');
				$('#dg').datagrid('showColumn','N_23');			$('#dg').datagrid('showColumn','N_53');			$('#dg').datagrid('hideColumn','N_83');
				$('#dg').datagrid('showColumn','N_24');			$('#dg').datagrid('showColumn','N_54');			$('#dg').datagrid('hideColumn','N_84');
				$('#dg').datagrid('showColumn','N_25');			$('#dg').datagrid('showColumn','N_55');			$('#dg').datagrid('hideColumn','N_85');
				$('#dg').datagrid('showColumn','N_26');			$('#dg').datagrid('showColumn','N_56');			$('#dg').datagrid('hideColumn','N_86');
				$('#dg').datagrid('showColumn','N_27');			$('#dg').datagrid('showColumn','N_57');			$('#dg').datagrid('hideColumn','N_87');
				$('#dg').datagrid('showColumn','N_28');			$('#dg').datagrid('showColumn','N_58');			$('#dg').datagrid('hideColumn','N_88');
				$('#dg').datagrid('showColumn','N_29');			$('#dg').datagrid('showColumn','N_59');			$('#dg').datagrid('hideColumn','N_89');
				$('#dg').datagrid('showColumn','N_30');			$('#dg').datagrid('showColumn','N_60');			$('#dg').datagrid('hideColumn','N_90');
			}
		}else{
			$('#dg').datagrid('showColumn','N_1');			$('#dg').datagrid('showColumn','N_31');			$('#dg').datagrid('showColumn','N_61');
			$('#dg').datagrid('showColumn','N_2');			$('#dg').datagrid('showColumn','N_32');			$('#dg').datagrid('showColumn','N_62');
			$('#dg').datagrid('showColumn','N_3');			$('#dg').datagrid('showColumn','N_33');			$('#dg').datagrid('showColumn','N_63');
			$('#dg').datagrid('showColumn','N_4');			$('#dg').datagrid('showColumn','N_34');			$('#dg').datagrid('showColumn','N_64');
			$('#dg').datagrid('showColumn','N_5');			$('#dg').datagrid('showColumn','N_35');			$('#dg').datagrid('showColumn','N_65');
			$('#dg').datagrid('showColumn','N_6');			$('#dg').datagrid('showColumn','N_36');			$('#dg').datagrid('showColumn','N_66');
			$('#dg').datagrid('showColumn','N_7');			$('#dg').datagrid('showColumn','N_37');			$('#dg').datagrid('showColumn','N_67');
			$('#dg').datagrid('showColumn','N_8');			$('#dg').datagrid('showColumn','N_38');			$('#dg').datagrid('showColumn','N_68');
			$('#dg').datagrid('showColumn','N_9');			$('#dg').datagrid('showColumn','N_39');			$('#dg').datagrid('showColumn','N_69');
			$('#dg').datagrid('showColumn','N_10');			$('#dg').datagrid('showColumn','N_40');			$('#dg').datagrid('showColumn','N_70');
			$('#dg').datagrid('showColumn','N_11');			$('#dg').datagrid('showColumn','N_41');			$('#dg').datagrid('showColumn','N_71');
			$('#dg').datagrid('showColumn','N_12');			$('#dg').datagrid('showColumn','N_42');			$('#dg').datagrid('showColumn','N_72');
			$('#dg').datagrid('showColumn','N_13');			$('#dg').datagrid('showColumn','N_43');			$('#dg').datagrid('showColumn','N_73');
			$('#dg').datagrid('showColumn','N_14');			$('#dg').datagrid('showColumn','N_44');			$('#dg').datagrid('showColumn','N_74');
			$('#dg').datagrid('showColumn','N_15');			$('#dg').datagrid('showColumn','N_45');			$('#dg').datagrid('showColumn','N_75');
			$('#dg').datagrid('showColumn','N_16');			$('#dg').datagrid('showColumn','N_46');			$('#dg').datagrid('showColumn','N_76');
			$('#dg').datagrid('showColumn','N_17');			$('#dg').datagrid('showColumn','N_47');			$('#dg').datagrid('showColumn','N_77');
			$('#dg').datagrid('showColumn','N_18');			$('#dg').datagrid('showColumn','N_48');			$('#dg').datagrid('showColumn','N_78');
			$('#dg').datagrid('showColumn','N_19');			$('#dg').datagrid('showColumn','N_49');			$('#dg').datagrid('showColumn','N_79');
			$('#dg').datagrid('showColumn','N_20');			$('#dg').datagrid('showColumn','N_50');			$('#dg').datagrid('showColumn','N_80');
			$('#dg').datagrid('showColumn','N_21');			$('#dg').datagrid('showColumn','N_51');			$('#dg').datagrid('showColumn','N_81');
			$('#dg').datagrid('showColumn','N_22');			$('#dg').datagrid('showColumn','N_52');			$('#dg').datagrid('showColumn','N_82');
			$('#dg').datagrid('showColumn','N_23');			$('#dg').datagrid('showColumn','N_53');			$('#dg').datagrid('showColumn','N_83');
			$('#dg').datagrid('showColumn','N_24');			$('#dg').datagrid('showColumn','N_54');			$('#dg').datagrid('showColumn','N_84');
			$('#dg').datagrid('showColumn','N_25');			$('#dg').datagrid('showColumn','N_55');			$('#dg').datagrid('showColumn','N_85');
			$('#dg').datagrid('showColumn','N_26');			$('#dg').datagrid('showColumn','N_56');			$('#dg').datagrid('showColumn','N_86');
			$('#dg').datagrid('showColumn','N_27');			$('#dg').datagrid('showColumn','N_57');			$('#dg').datagrid('showColumn','N_87');
			$('#dg').datagrid('showColumn','N_28');			$('#dg').datagrid('showColumn','N_58');			$('#dg').datagrid('showColumn','N_88');
			$('#dg').datagrid('showColumn','N_29');			$('#dg').datagrid('showColumn','N_59');			$('#dg').datagrid('showColumn','N_89');
			$('#dg').datagrid('showColumn','N_30');			$('#dg').datagrid('showColumn','N_60');			$('#dg').datagrid('showColumn','N_90');
		}

	    var dg = $('#dg').datagrid();
		dg.datagrid('enableFilter');
	}
</script>
</body>
</html>