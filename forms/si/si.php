<?php 
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>SHIPPING INSTRUCTION</title>

    <link rel="icon" type="image/png" href="../../favicon.png">

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
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
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
	<?php include ('../../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name)); ?>
	
	<table id="dg" title="SHIPPING INSTRUCTION" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;"></table>
	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%; height:70px; float:left;"><legend>SHIPPING INSTRUCTION FILTER</legend>
			<div style="float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CR Date</span>
					<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					to 
					<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
                </div>
                <div class="fitem">
					<input style="width:310px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" placeholder="search SI NO. or consignee or customer po no." autofocus/>	
					<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</a>
					<a href="javascript:void(0)" style="width: 100px;" id="add" class="easyui-linkbutton c2" onclick="addPRF()"><i class="fa fa-plus" aria-hidden="true"></i> ADD SI</a>
					<a href="javascript:void(0)" style="width: 100px;" id="edit" class="easyui-linkbutton c2" onclick="editPRF()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT SI</a>
					<a href="javascript:void(0)" style="width: 100px;" id="delete" class="easyui-linkbutton c2" onclick="destroyPRF()"><i class="fa fa-trash" aria-hidden="true"></i> DELETE SI</a>
				</div>
            </div>
		</fieldset>
		<div style="clear:both;"></div>
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

				if (src=='') {
					filterData();
				};
		    }
		}

        function access_log(){
			//ADD//UPDATE/T
			//DELETE/T
			//PRINT/T

			var add = "<?=$exp[0]?>";
			var upd = "<?=$exp[1]?>";
			var del = "<?=$exp[2]?>";
			var prn = "<?=$exp[4]?>";

			if (add == 'ADD/T'){
				$('#add').linkbutton('enable');
			}else{
				$('#add').linkbutton('disable');
			}

			if (upd == 'UPDATE/T'){
				$('#edit').linkbutton('enable');
			}else{
				$('#edit').linkbutton('disable');
			}

			if (del == 'DELETE/T'){
				$('#delete').linkbutton('enable');
			}else{
				$('#delete').linkbutton('disable');
			}

			if (prn == 'PRINT/T'){
				$('#print').linkbutton('enable');
			}else{
				$('#print').linkbutton('disable');
			}			
		}

        $(function(){
            access_log();

            $('#date_awal').datebox('disable');
			$('#date_akhir').datebox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_awal').datebox('disable');
					$('#date_akhir').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_awal').datebox('enable');
					$('#date_akhir').datebox('enable');
				};
			})

			$('#cmb_si_no').combobox('disable');
			$('#ck_si_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_si_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_si_no').combobox('enable');
				};
			})

			$('#consignee_name').combobox('disable');
			$('#ck_consignee').change(function(){
				if ($(this).is(':checked')) {
					$('#consignee_name').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#consignee_name').combobox('enable');
				};
			})

            $('#cmb_cust_po').combobox('disable');
			$('#ck_cust_po').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_cust_po').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_cust_po').combobox('enable');
				};
			})

            $('#dg').datagrid({
				url:'si_get.php',
		    	singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
				sortName: 'prf_date',
				sortOrder: 'asc',
			    columns:[[
				    {field:'SI_NO',title:'PRF NO.',width:75, halign: 'center', align: 'center'},
				    {field:'CUST_SI_NO',title:'PRF DATE',width:60, halign: 'center', align: 'center', sortable:true},
				    {field:'PRF_DATE_1',title:'PRF DATE',width:60, halign: 'center', align: 'center', sortable:true, hidden: true},
				    {field:'REQUIRE_PERSON_CODE',title:'Require<br/>Person',width:60, halign: 'center', align: 'center'},
				    {field:'APPROVAL_DATE',title:'Approval<br/>Date',width:60, halign: 'center', align: 'center'},
				    {field:'REMARK',title:'REMARK', width:200, halign: 'center'},
				    {field:'STATUS',title:'STATUS', width:70, halign: 'center', align: 'center'},
				    {field:'STS_DSIGN',title:'STATUS<br/>DESIGN', width:70, halign: 'center', align: 'center'},
				    {field:'JUMLAH_PO',title:'STATUS PO', width:100, halign: 'center', align: 'center'},
				    {field:'STS', hidden: true},
				    {field:'JUM_PO', hidden: true},
				    {field:'CUSTOMER_PO_NO', hidden: true},
				    {field:'STS_DSIGN', hidden: true}
			    ]]
            });
        });

        function filterData(){
            var ck_date = 'false';
			var ck_si_no = 'false';
			var ck_consignee = 'false';
            var ck_cust_po = 'false';
			var flag = 0;

            if($('#ck_date').attr("checked")){
				ck_date='true';
				flag += 1;
			}

			if($('#ck_si_no').attr("checked")){
				ck_si_no='true';
				flag += 1;
			}
			
            if($('#ck_consignee').attr("checked")){
				ck_consignee='true';
				flag += 1;
			}

            if($('#ck_cust_po').attr("checked")){
				ck_cust_po='true';
				flag += 1;
			}
			
			if(flag == 4) {
				$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
			}

			$('#dg').datagrid('load', {
				date_awal: $('#date_awal').datebox('getValue'),
				date_akhir: $('#date_akhir').datebox('getValue'),
				ck_date: ck_date,
				cmb_item_no: $('#cmb_si_no').combobox('getValue'),
				ck_si_no: ck_si_no,
                cmb_consignee: $('#consignee_name').combobox('getValue'),
				ck_consignee: ck_consignee,
                cmb_cust_po: $('#cmb_cust_po').combobox('getValue'),
				ck_cust_po: ck_cust_po,
                src: ''
			});

		   	$('#dg').datagrid('enableFilter');
        }
    </Script>
	</body>
    </html>