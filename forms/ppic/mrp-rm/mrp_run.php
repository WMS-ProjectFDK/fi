<?php 
error_reporting(0);
include("../../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];

$arrBulan = array('1' => 'JAN',
				  '2' => 'FEB',
				  '3' => 'MAR',
				  '4' => 'APR',
				  '5' => 'MAY', 
				  '6' => 'JUN',
         		  '7' => 'JUL',
         		  '8' => 'AUG',
         		  '9' => 'SEP',
         		  '10' => 'OCT',
         		  '11' => 'NOV',
         		  '12' => 'DEC');

$b1 = intval(date('m'));
$t1 = intval(date('Y'));
if($b1 == 12){
	$b2 = 1;		$b3=2;
	$t2 = $t1+1;	$t3=$t1+1;
}else{
	$b2 = $b1+1;	$b3=$b1+2;
	$t2 = $t1;		$t3=$t1;
};

if($b1 == 11){		
	$b3=1;
	$t3=$t1+1;
}



$q= "
SELECT aa.bulan+aa.tahun as period, aa.bulan, aa.tahun, bb.tot, 
bb.upload_time, case when bb.tot=0 then 'N' else 'Y' end as KET
FROM (
	select distinct bulan, tahun from ztb_assy_plan
	where bulan = 7 and tahun=2020 OR bulan = 8 and tahun=2020 OR bulan = 9 and tahun=2020
	) aa
	left outer join
	(select bulan, tahun, upload_time, coalesce(count(distinct tanggal),0) as tot from ztb_assy_plan where used=1 
	group by bulan, tahun, upload_time) bb 
	on aa.bulan=bb.bulan AND aa.tahun=bb.tahun
";

$sq = "
select substring(assy_line, 0, 4)+cell_type as Grade,
sum(qty) TotalProduksi, upload_time,bulan,tahun,tot ,
case when tot=0 then 'N' else 'Y' end as ket
  from ztb_assy_plan aa
  left outer join
	(select bulan bulanx, tahun tahunx, upload_time up, coalesce(count(distinct tanggal),0) as tot from ztb_assy_plan where used=1 
	group by bulan, tahun, upload_time) bb 
	on aa.bulan=bb.bulanx AND aa.tahun=bb.tahunx
	where ((bulan = 7 and tahun=2020) OR (bulan = 8 and tahun=2020) OR (bulan = 9 and tahun=2020))  and used=1 
  group by bulan,tahun,upload_time,substring(assy_line, 0, 4)+cell_type,tot";


$data = sqlsrv_query($connect, strtoupper($q));
$rowno=0;
$items = array();
$sts = 1;
while($row = sqlsrv_fetch_object($data)) {
	array_push($items, $row);
	$n_ket = $items->KET;
	if ($n_ket == 'N'){
		$sts = 0;
	}
	$rowno++;
}
// echo json_encode($items);
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>MRP</title>
    <link rel="icon" type="image/png" href="../../../favicon.png">
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script> 
    <link rel="stylesheet" type="text/css" href="../../../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../../../themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../../../themes/color.css">
    <script type="text/javascript" src="../../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../../../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../../../js/datagrid-filter.js"></script>
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
	<?php 
	include ('../../../ico_logout.php');
	?>
	
	<div id="toolbar" style="margin-top: 5px;margin-bottom: 5px;width: 100%;">
		<div align="center" class="fitem" style="width: 100%;">
			<a href="javascript:void(0)" id="start_mrp" style="width:150px;height:60px;" class="easyui-linkbutton c2" onclick="run_mrp()" ><i class="fa fa-play"></i><br/>START</a>
		</div>
		<br/>
		<div id="progress" class="easyui-progressbar" style="margin-left: 20px; width: 96%;"></div><br/>
		<div style="margin-left: 20px; height:375px; width: 96%;">
			<fieldset style="float:left;width:380px;height:350px;border-radius:4px;"><legend><span class="style3"><strong><?php echo $arrBulan[$b1]; ?></strong></span>
			</legend>
				<label><input type="checkbox" name="ck_cr_b1" id="ck_cr_b1" >Lock <?php echo $arrBulan[$b1]; ?> </input></label>
				<div class="fitem">
    				<span style="width:110px;display:inline-block;">Upload Time :</span>
    				<span id="upd1" style="width:250px;display:inline-block;"><?php echo $items[0]->UPLOAD_TIME; ?></span>
    			</div>
    			<div class="fitem">
	    			<span style="width:110px;display:inline-block;">Work Day :</span>
	    			<span id="wd1" style="width:250px;display:inline-block;"><?php echo $items[0]->TOT; ?></span>
	    			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_assy1()" style="width:200px;"><i class="fa fa-download" aria-hidden="true"></i> Download Assembly Plan</a>
	    		</div>
	    		<div class="fitem">
	    			<table id="dg1" title="Summary Production " class="easyui-datagrid" style="width:96%;height:250px;" rownumbers="true"> </table>
	    		</div>
			</fieldset>

			<fieldset style="position:absolute;margin-left:410px;height:350px;border-radius:4px;width: 405px;"><legend><span class="style3"><strong><?php echo $arrBulan[$b2]; ?></strong></span></legend>
				<label><input type="checkbox" name="ck_cr_b2" id="ck_cr_b2" >Lock <?php echo $arrBulan[$b2]; ?> </input></label>
				<div class="fitem">
    				<span style="width:110px;display:inline-block;">Upload Time :</span>
    				<span id="upd2" style="width:250px;display:inline-block;"><?php echo $items[1]->UPLOAD_TIME; ?></span>
    			</div>
    			<div class="fitem">
	    			<span style="width:110px;display:inline-block;">Work Day :</span>
	    			<span id="wd2" style="width:250px;display:inline-block;"><?php echo $items[1]->TOT; ?></span>
	    			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_assy2()" style="width:200px;"><i class="fa fa-download" aria-hidden="true"></i> Download Assembly Plan</a>
	    		</div>
	    		<div class="fitem">
	    			<table id="dg2" title="Summary Production" class="easyui-datagrid" style="width:96%;height:250px;" rownumbers="true"> </table>
	    		</div>
			</fieldset>

			<fieldset style="margin-left: 845px;border-radius:4px;height:350px;width: 380px;"><legend><span class="style3"><strong><?php echo $arrBulan[$b3]; ?></strong></span></legend>
				<label><input type="checkbox" name="ck_cr_b3" id="ck_cr_b3" >Lock <?php echo $arrBulan[$b3]; ?> </input></label>
				<div class="fitem">
    				<span style="width:110px;display:inline-block;">Upload Time :</span>
    				<span id="upd3" style="width:250px;display:inline-block;"><?php echo $items[2]->UPLOAD_TIME; ?></span>
    			</div>
    			<div class="fitem">
	    			<span style="width:110px;display:inline-block;">Work Day :</span>
	    			<span id="wd3" style="width:250px;display:inline-block;"><?php echo $items[2]->TOT; ?></span>
	    			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_assy3()" style="width:200px;"><i class="fa fa-download" aria-hidden="true"></i> Download Assembly Plan</a>
	    		</div>
	    		<div class="fitem">
	    			<table id="dg3" title="Summary Production" class="easyui-datagrid" style="width:96%;height:250px;" rownumbers="true"> </table>
	    		</div>
			</fieldset>
		</div>
	</div>
	<table id="dg" title="MATERIAL REQUIREMENT PLAN" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;"></table>

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
			if(<?php echo $sts; ?> == 1 && <?php echo count($items); ?> == 3){
				$('#start_mrp').linkbutton('enable');	
			}else{
				$('#start_mrp').linkbutton('disable');
			}

			$('#dg1').datagrid('load', {
				Bulan: <?php Print($b1); ?>,
				Tahun: <?php Print($t1); ?>
			});

			$('#dg1').datagrid( {
				url: 'mrp_run_detail_get.php',
				singleSelect: true,
				columns:[[
					{field:'CELL_GRADE',title:'CELL_GRADE', halign:'center', width:150},
					{field:'QUANTITY', title:'QUANTITY', halign:'center',align:'right', width:150}
				]]
			})	

			$('#dg2').datagrid('load', {
				Bulan: <?php Print($b2); ?>,
				Tahun: <?php Print($t2); ?>
			});

			$('#dg2').datagrid( {			
				url: 'mrp_run_detail_get.php',
				singleSelect: true,
				
				columns:[[
					{field:'CELL_GRADE',title:'CELL & GRADE', halign:'center', width:150},
					{field:'QUANTITY', title:'QUANTITY', halign:'center',align:'right', width:150}
				]]
			})

			$('#dg3').datagrid('load', {
				Bulan: <?php Print($b3); ?>,
				Tahun: <?php Print($t3); ?>
			});

			$('#dg3').datagrid( {
				url: 'mrp_run_detail_get.php',		
				singleSelect: true,
				columns:[[
					{field:'CELL_GRADE',title:'CELL & GRADE', halign:'center', width:150},
					{field:'QUANTITY', title:'QUANTITY', halign:'center',align:'right', width:150}
				]]
			})
		});

		var n = 0;
		
		function cek_jumlah(){
			$.ajax({
				type: 'GET',
				url: '../../json/json_jumlah_mrp.php',
				success: function(data){
					n = data[0].persen;
				}
			});
		}

		function p_bar(){
			var value = $('#progress').progressbar('getValue');
            if (value < 57){
                value = n;
                $('#progress').progressbar('setValue', value);
                setTimeout(arguments.callee, 200);
            }else{
            	$.messager.alert('INFORMATION','MRP FINISH','info');
            	$('#progress').progressbar('setValue', 0);
            	$('#start_mrp').linkbutton('enable');
            	window.location.href = 'ito_state.php';
            }
		}

		function run_mrp(){
			if ($('#ck_cr_b1').attr("checked") && $('#ck_cr_b2').attr("checked") && $('#ck_cr_b3').attr("checked")) {
				$('#start_mrp').linkbutton('disable');
				$.post('mrp_run_get.php');
				setInterval(function(){ cek_jumlah() }, 1000);
				p_bar();
			}else{
				alert("Please Lock The Plan First !!");
			};
        }

        function print_assy1(){
			pdf_url = "?pl_bulan="+<?php Print($b1); ?>+
					"&pl_tahun="+<?php Print($t1); ?>+
					"&pl_cdate="+false+
					"&pl_aline="+1+
					"&pl_cline="+true+
					"&pl_cltyp="+1+
					"&pl_cktyp="+true+
					"&pl_revis="+
					"&pl_crev="+true+
					"&pl_day="+1+
					"&pl_cday="+true+
					"&pl_cuse="+true;
			window.open('assy_plan_xls.php'+pdf_url, '_blank');
		}

		function print_assy2(){
			pdf_url = "?pl_bulan="+<?php Print($b2); ?>+
		   			  "&pl_tahun="+<?php Print($t2); ?>+
		   			  "&pl_cdate="+false+
					  "&pl_aline="+1+
					  "&pl_cline="+true+
					  "&pl_cltyp="+1+
					  "&pl_cktyp="+true+
					  "&pl_revis="+
					  "&pl_crev="+true+
					  "&pl_day="+1+
					  "&pl_cday="+true+
					  "&pl_cuse="+true;
			window.open('assy_plan_xls.php'+pdf_url, '_blank');
		}

		function print_assy3(){
			pdf_url = "?pl_bulan="+<?php Print($b3); ?>+
		   			  "&pl_tahun="+<?php Print($t3); ?>+
		   			  "&pl_cdate="+false+
					  "&pl_aline="+1+
					  "&pl_cline="+true+
					  "&pl_cltyp="+1+
					  "&pl_cktyp="+true+
					  "&pl_revis="+
					  "&pl_crev="+true+
					  "&pl_day="+1+
					  "&pl_cday="+true+
					  "&pl_cuse="+true;
			window.open('assy_plan_xls.php'+pdf_url, '_blank');
		}
	</script>
    </body>
    </html>