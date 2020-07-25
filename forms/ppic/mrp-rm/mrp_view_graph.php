<?php

session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';

?>

     

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monitoring Assembling</title>
    <link rel="icon" type="image/png" href="../favicon.png">
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../themes/color.css" />
    <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
     <script type="text/javascript" src="../js/canvasjs.min.js"></script>
      <script type="text/javascript" src="../js/jquery.canvasjs.min.js"></script>
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
	
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

	
	
			

	<!-- 	 <div id="toolbar">
			<fieldset style="width:700px;height: 70px;border-radius:3px;float:left;">
				<div class="fitem">
						
						<span  style="width:130px;display:inline-block;">Assembling Line :</span>
						<select style="width:150px;" name="cmb_asy_line" id="cmb_asy_line" required="">
	    					<option value="" selected="true">-- silahkan pilih --</option>
	    					<option value="LR31">LR32</option>
	    					<option value="LR32">LR31</option>
	    				</select>

				</div>

				

				<div class="fitem">
					   <button type="button" style="width:150px;height:45px">Show Control</button> 
				</div>
			</fieldset>



			

			


			
		</div> -->
		<span id="spandata" value="123"> <?  echo $item_no ?></span>
		<div id="chartContainer" style="height: 300px; width: 100%;"></div>
		<div id="chartContainer2" style="height: 300px; width: 100%;" value=1130014></div>
		<span align="center" id="demo" width="100" height="100"></span>
</body>
</html>

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
var date = new Date();

var y = date.getFullYear();
var m = date.getMonth();
var d = date.getDate();




var d1 = new Date(y,m,d+1);

var d2 = new Date(y,m,d+2);
var d3 = new Date(y,m,d+3);
var d4 = new Date(y,m,d+4);
var d5 = new Date(y,m,d+5);
var d6 = new Date(y,m,d+6);
var d7 = new Date(y,m,d+7);
var d8 = new Date(y,m,d+8);
var d9 = new Date(y,m,d+9);
var d10 = new Date(y,m,d+10);
var d11 = new Date(y,m,d+11);
var d12 = new Date(y,m,d+12);
var d13 = new Date(y,m,d+13);
var d14 = new Date(y,m,d+14);
var d15 = new Date(y,m,d+15);
var d16 = new Date(y,m,d+16);
var d17 = new Date(y,m,d+17);
var d18 = new Date(y,m,d+18);
var d19 = new Date(y,m,d+19);
var d20 = new Date(y,m,d+20);
var d21 = new Date(y,m,d+21);
var d22 = new Date(y,m,d+22);
var d23 = new Date(y,m,d+23);
var d24 = new Date(y,m,d+24);
var d25 = new Date(y,m,d+25);
var d26 = new Date(y,m,d+26);
var d27 = new Date(y,m,d+27);
var d28 = new Date(y,m,d+28);
var d29 = new Date(y,m,d+29);
var d30 = new Date(y,m,d+30);
var d31 = new Date(y,m,d+31);
var d32 = new Date(y,m,d+32);
var d33 = new Date(y,m,d+33);
var d34 = new Date(y,m,d+34);
var d35 = new Date(y,m,d+35);
var d36 = new Date(y,m,d+36);
var d37 = new Date(y,m,d+37);
var d38 = new Date(y,m,d+38);
var d39 = new Date(y,m,d+39);
var d40 = new Date(y,m,d+40);
var d41 = new Date(y,m,d+41);
var d42 = new Date(y,m,d+42);
var d43 = new Date(y,m,d+43);
var d44 = new Date(y,m,d+44);
var d45 = new Date(y,m,d+45);
var d46 = new Date(y,m,d+46);
var d47 = new Date(y,m,d+47);
var d48 = new Date(y,m,d+48);
var d49 = new Date(y,m,d+49);
var d40 = new Date(y,m,d+40);
var d41 = new Date(y,m,d+41);
var d42 = new Date(y,m,d+42);
var d43 = new Date(y,m,d+43);
var d44 = new Date(y,m,d+44);
var d45 = new Date(y,m,d+45);
var d46 = new Date(y,m,d+46);
var d47 = new Date(y,m,d+47);
var d48 = new Date(y,m,d+48);
var d49 = new Date(y,m,d+49);
var d50 = new Date(y,m,d+50);
var d51 = new Date(y,m,d+51);
var d52 = new Date(y,m,d+52);
var d53 = new Date(y,m,d+53);
var d54 = new Date(y,m,d+54);
var d55 = new Date(y,m,d+55);
var d56 = new Date(y,m,d+56);
var d57 = new Date(y,m,d+57);
var d58 = new Date(y,m,d+58);
var d59 = new Date(y,m,d+59);
var d60 = new Date(y,m,d+60);
var d61 = new Date(y,m,d+61);
var d62 = new Date(y,m,d+62);
var d63 = new Date(y,m,d+63);
var d64 = new Date(y,m,d+64);
var d65 = new Date(y,m,d+65);
var d66 = new Date(y,m,d+66);
var d67 = new Date(y,m,d+67);
var d68 = new Date(y,m,d+68);
var d69 = new Date(y,m,d+69);
var d70 = new Date(y,m,d+70);
var d71 = new Date(y,m,d+71);
var d72 = new Date(y,m,d+72);
var d73 = new Date(y,m,d+73);
var d74 = new Date(y,m,d+74);
var d75 = new Date(y,m,d+75);
var d76 = new Date(y,m,d+76);
var d77 = new Date(y,m,d+77);
var d78 = new Date(y,m,d+78);
var d79 = new Date(y,m,d+79);
var d80 = new Date(y,m,d+80);
var d81 = new Date(y,m,d+81);
var d82 = new Date(y,m,d+82);
var d83 = new Date(y,m,d+83);
var d84 = new Date(y,m,d+84);
var d85 = new Date(y,m,d+85);
var d86 = new Date(y,m,d+86);
var d87 = new Date(y,m,d+87);
var d88 = new Date(y,m,d+88);
var d89 = new Date(y,m,d+89);
var d90 = new Date(y,m,d+90);

	var month_names = ["Jan", "Feb", "Mar", 
    "Apr", "May", "Jun", "Jul", "Aug", "Sep", 
    "Oct", "Nov", "Dec"];
    
 function cfd(d){

 	var today = d;
    var day = today.getDate();
    var month_index = today.getMonth();
    var year = today.getFullYear();

 	return day + "-" + month_names[month_index];
 } ;  
   
   



// function myTimer(){
window.onload = function () {
	    // var d = new Date();
	    // var n = parseInt(d.getDate());
	   

	    // document.getElementById("waktu").value = d.toLocaleTimeString().replace('.',':').replace('.',':');
	   
	    //alert(d.toLocaleTimeString().replace('.',':').replace('.',':'));
 		var pesan = '';	
        
        var data;
        var sts = 'RUNNING'
		$.ajax({
			type: 'GET',
			dataType: "json",
			url: 'view_mrp_get.php?item_no=1130014',
			data: data,
			success: function (data) {

			

			// ####################################################### CHART 2 $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
			var namaitem = "MATERIAL INVENTORY FOR  " + data[0].ITEM_DESC ;
		
			
			var chart = new CanvasJS.Chart("chartContainer", {
				animationEnabled: true,
				exportEnabled: true,
				title:{
					text: namaitem  

				}, 
				axisY:{
					title: "ITO Days",
					labelFontSize: 12,
				},
				axisX:{
					title: "Date",
					labelFontSize: 10,
				},


				toolTip: {
					shared: true
				},
				
				data: [
				
				{        
					type: "line",  
					name: "Max ITO Days",  

					showInLegend: true,
					dataPoints: [
						{ label: d1.toLocaleDateString(), y: parseInt(data[1].N_1) },     
						{ label: d2.toLocaleDateString(), y: parseInt(data[1].N_2) },     
						{ label: d3.toLocaleDateString(), y: parseInt(data[1].N_3) },     
						{ label: d4.toLocaleDateString(), y: parseInt(data[1].N_4) },     
						{ label: d5.toLocaleDateString(), y: parseInt(data[1].N_5) },
						{ label: d6.toLocaleDateString(), y: parseInt(data[1].N_6) },
						{ label: d7.toLocaleDateString(), y: parseInt(data[1].N_7) },     
						{ label: d8.toLocaleDateString(), y: parseInt(data[1].N_8) },     
						{ label: d9.toLocaleDateString(), y: parseInt(data[1].N_9) },     
						{ label: d10.toLocaleDateString(), y: parseInt(data[1].N_10) },
						{ label: d11.toLocaleDateString(), y: parseInt(data[1].N_11) },     
						{ label: d12.toLocaleDateString(), y: parseInt(data[1].N_12) },     
						{ label: d13.toLocaleDateString(), y: parseInt(data[1].N_13) },     
						{ label: d14.toLocaleDateString(), y: parseInt(data[1].N_14) },     
						{ label: d15.toLocaleDateString(), y: parseInt(data[1].N_15) },
						{ label: d16.toLocaleDateString(), y: parseInt(data[1].N_16) },
						{ label: d17.toLocaleDateString(), y: parseInt(data[1].N_17) },     
						{ label: d18.toLocaleDateString(), y: parseInt(data[1].N_18) },     
						{ label: d19.toLocaleDateString(), y: parseInt(data[1].N_19) }, 
						{ label: d20.toLocaleDateString(), y: parseInt(data[1].N_20) },
						{ label: d21.toLocaleDateString(), y: parseInt(data[1].N_21) },
						{ label: d22.toLocaleDateString(), y: parseInt(data[1].N_22) },
						{ label: d23.toLocaleDateString(), y: parseInt(data[1].N_23) },
						{ label: d24.toLocaleDateString(), y: parseInt(data[1].N_24) },
						{ label: d25.toLocaleDateString(), y: parseInt(data[1].N_25) },
						{ label: d26.toLocaleDateString(), y: parseInt(data[1].N_26) },
						{ label: d27.toLocaleDateString(), y: parseInt(data[1].N_27) },
						{ label: d28.toLocaleDateString(), y: parseInt(data[1].N_28) },
						{ label: d29.toLocaleDateString(), y: parseInt(data[1].N_29) },
						{ label: d30.toLocaleDateString(), y: parseInt(data[1].N_30) }, 
						{ label: d31.toLocaleDateString(), y: parseInt(data[1].N_31) }, 
						{ label: d32.toLocaleDateString(), y: parseInt(data[1].N_32) }, 
						{ label: d33.toLocaleDateString(), y: parseInt(data[1].N_33) }, 
						{ label: d34.toLocaleDateString(), y: parseInt(data[1].N_34) }, 
						{ label: d35.toLocaleDateString(), y: parseInt(data[1].N_35) }, 
						{ label: d36.toLocaleDateString(), y: parseInt(data[1].N_36) }, 
						{ label: d37.toLocaleDateString(), y: parseInt(data[1].N_37) }, 
						{ label: d38.toLocaleDateString(), y: parseInt(data[1].N_38) }, 
						{ label: d39.toLocaleDateString(), y: parseInt(data[1].N_39) }, 
						{ label: d40.toLocaleDateString(), y: parseInt(data[1].N_40) }, 
						{ label: d41.toLocaleDateString(), y: parseInt(data[1].N_41) }, 
						{ label: d42.toLocaleDateString(), y: parseInt(data[1].N_42) }, 
						{ label: d43.toLocaleDateString(), y: parseInt(data[1].N_43) }, 
						{ label: d44.toLocaleDateString(), y: parseInt(data[1].N_44) }, 
						{ label: d45.toLocaleDateString(), y: parseInt(data[1].N_45) }, 
						{ label: d46.toLocaleDateString(), y: parseInt(data[1].N_46) }, 
						{ label: d47.toLocaleDateString(), y: parseInt(data[1].N_47) }, 
						{ label: d48.toLocaleDateString(), y: parseInt(data[1].N_48) }, 
						{ label: d49.toLocaleDateString(), y: parseInt(data[1].N_49) }, 
						{ label: d50.toLocaleDateString(), y: parseInt(data[1].N_50) },
						{ label: d51.toLocaleDateString(), y: parseInt(data[1].N_51) },
						{ label: d52.toLocaleDateString(), y: parseInt(data[1].N_52) },
						{ label: d53.toLocaleDateString(), y: parseInt(data[1].N_53) },
						{ label: d54.toLocaleDateString(), y: parseInt(data[1].N_54) },
						{ label: d55.toLocaleDateString(), y: parseInt(data[1].N_55) },
						{ label: d56.toLocaleDateString(), y: parseInt(data[1].N_56) },
						{ label: d57.toLocaleDateString(), y: parseInt(data[1].N_57) },
						{ label: d58.toLocaleDateString(), y: parseInt(data[1].N_58) },
						{ label: d59.toLocaleDateString(), y: parseInt(data[1].N_59) },
						{ label: d60.toLocaleDateString(), y: parseInt(data[1].N_60) },
						{ label: d61.toLocaleDateString(), y: parseInt(data[1].N_61) },
						{ label: d62.toLocaleDateString(), y: parseInt(data[1].N_62) },
						{ label: d63.toLocaleDateString(), y: parseInt(data[1].N_63) },
						{ label: d64.toLocaleDateString(), y: parseInt(data[1].N_64) },
						{ label: d65.toLocaleDateString(), y: parseInt(data[1].N_65) },
						{ label: d66.toLocaleDateString(), y: parseInt(data[1].N_66) },
						{ label: d67.toLocaleDateString(), y: parseInt(data[1].N_67) },
						{ label: d68.toLocaleDateString(), y: parseInt(data[1].N_68) },
						{ label: d69.toLocaleDateString(), y: parseInt(data[1].N_69) },
						{ label: d70.toLocaleDateString(), y: parseInt(data[1].N_70) },
						{ label: d71.toLocaleDateString(), y: parseInt(data[1].N_71) },
						{ label: d72.toLocaleDateString(), y: parseInt(data[1].N_72) },
						{ label: d73.toLocaleDateString(), y: parseInt(data[1].N_73) },
						{ label: d74.toLocaleDateString(), y: parseInt(data[1].N_74) },
						{ label: d75.toLocaleDateString(), y: parseInt(data[1].N_75) },
						{ label: d76.toLocaleDateString(), y: parseInt(data[1].N_76) },
						{ label: d77.toLocaleDateString(), y: parseInt(data[1].N_77) },
						{ label: d78.toLocaleDateString(), y: parseInt(data[1].N_78) },
						{ label: d79.toLocaleDateString(), y: parseInt(data[1].N_79) },
						{ label: d80.toLocaleDateString(), y: parseInt(data[1].N_80) },
						{ label: d81.toLocaleDateString(), y: parseInt(data[1].N_81) },
						{ label: d82.toLocaleDateString(), y: parseInt(data[1].N_82) },
						{ label: d83.toLocaleDateString(), y: parseInt(data[1].N_83) },
						{ label: d84.toLocaleDateString(), y: parseInt(data[1].N_84) },
						{ label: d85.toLocaleDateString(), y: parseInt(data[1].N_85) },
						{ label: d86.toLocaleDateString(), y: parseInt(data[1].N_86) },
						{ label: d87.toLocaleDateString(), y: parseInt(data[1].N_87) },
						{ label: d88.toLocaleDateString(), y: parseInt(data[1].N_88) },
						{ label: d89.toLocaleDateString(), y: parseInt(data[1].N_89) },
						{ label: d90.toLocaleDateString(), y: parseInt(data[1].N_90) }
					]
				}, 
				{        
					type: "spline",
					name: "ITO Days",        
					showInLegend: true,
					dataPoints: [
							{ label: d1.toLocaleDateString(), y: parseInt(data[2].N_1) },     
						{ label: d2.toLocaleDateString(), y: parseInt(data[2].N_2) },     
						{ label: d3.toLocaleDateString(), y: parseInt(data[2].N_3) },     
						{ label: d4.toLocaleDateString(), y: parseInt(data[2].N_4) },     
						{ label: d5.toLocaleDateString(), y: parseInt(data[2].N_5) },
						{ label: d6.toLocaleDateString(), y: parseInt(data[2].N_6) },
						{ label: d7.toLocaleDateString(), y: parseInt(data[2].N_7) },     
						{ label: d8.toLocaleDateString(), y: parseInt(data[2].N_8) },     
						{ label: d9.toLocaleDateString(), y: parseInt(data[2].N_9) },     
						{ label: d10.toLocaleDateString(), y: parseInt(data[1].N_10) },
						{ label: d11.toLocaleDateString(), y: parseInt(data[2].N_11) },     
						{ label: d12.toLocaleDateString(), y: parseInt(data[2].N_12) },     
						{ label: d13.toLocaleDateString(), y: parseInt(data[2].N_13) },     
						{ label: d14.toLocaleDateString(), y: parseInt(data[2].N_14) },     
						{ label: d15.toLocaleDateString(), y: parseInt(data[2].N_15) },
						{ label: d16.toLocaleDateString(), y: parseInt(data[2].N_16) },
						{ label: d17.toLocaleDateString(), y: parseInt(data[2].N_17) },     
						{ label: d18.toLocaleDateString(), y: parseInt(data[2].N_18) },     
						{ label: d19.toLocaleDateString(), y: parseInt(data[2].N_19) }, 
						{ label: d20.toLocaleDateString(), y: parseInt(data[2].N_20) },
						{ label: d21.toLocaleDateString(), y: parseInt(data[2].N_21) },
						{ label: d22.toLocaleDateString(), y: parseInt(data[2].N_22) },
						{ label: d23.toLocaleDateString(), y: parseInt(data[2].N_23) },
						{ label: d24.toLocaleDateString(), y: parseInt(data[2].N_24) },
						{ label: d25.toLocaleDateString(), y: parseInt(data[2].N_25) },
						{ label: d26.toLocaleDateString(), y: parseInt(data[2].N_26) },
						{ label: d27.toLocaleDateString(), y: parseInt(data[2].N_27) },
						{ label: d28.toLocaleDateString(), y: parseInt(data[2].N_28) },
						{ label: d29.toLocaleDateString(), y: parseInt(data[2].N_29) },
						{ label: d30.toLocaleDateString(), y: parseInt(data[2].N_30) }, 
						{ label: d31.toLocaleDateString(), y: parseInt(data[2].N_31) }, 
						{ label: d32.toLocaleDateString(), y: parseInt(data[2].N_32) }, 
						{ label: d33.toLocaleDateString(), y: parseInt(data[2].N_33) }, 
						{ label: d34.toLocaleDateString(), y: parseInt(data[2].N_34) }, 
						{ label: d35.toLocaleDateString(), y: parseInt(data[2].N_35) }, 
						{ label: d36.toLocaleDateString(), y: parseInt(data[2].N_36) }, 
						{ label: d37.toLocaleDateString(), y: parseInt(data[2].N_37) }, 
						{ label: d38.toLocaleDateString(), y: parseInt(data[2].N_38) }, 
						{ label: d39.toLocaleDateString(), y: parseInt(data[2].N_39) }, 
						{ label: d40.toLocaleDateString(), y: parseInt(data[2].N_40) }, 
						{ label: d41.toLocaleDateString(), y: parseInt(data[2].N_41) }, 
						{ label: d42.toLocaleDateString(), y: parseInt(data[2].N_42) }, 
						{ label: d43.toLocaleDateString(), y: parseInt(data[2].N_43) }, 
						{ label: d44.toLocaleDateString(), y: parseInt(data[2].N_44) }, 
						{ label: d45.toLocaleDateString(), y: parseInt(data[2].N_45) }, 
						{ label: d46.toLocaleDateString(), y: parseInt(data[2].N_46) }, 
						{ label: d47.toLocaleDateString(), y: parseInt(data[2].N_47) }, 
						{ label: d48.toLocaleDateString(), y: parseInt(data[2].N_48) }, 
						{ label: d49.toLocaleDateString(), y: parseInt(data[2].N_49) }, 
						{ label: d50.toLocaleDateString(), y: parseInt(data[2].N_50) },
						{ label: d51.toLocaleDateString(), y: parseInt(data[2].N_51) },
						{ label: d52.toLocaleDateString(), y: parseInt(data[2].N_52) },
						{ label: d53.toLocaleDateString(), y: parseInt(data[2].N_53) },
						{ label: d54.toLocaleDateString(), y: parseInt(data[2].N_54) },
						{ label: d55.toLocaleDateString(), y: parseInt(data[2].N_55) },
						{ label: d56.toLocaleDateString(), y: parseInt(data[2].N_56) },
						{ label: d57.toLocaleDateString(), y: parseInt(data[2].N_57) },
						{ label: d58.toLocaleDateString(), y: parseInt(data[2].N_58) },
						{ label: d59.toLocaleDateString(), y: parseInt(data[2].N_59) },
						{ label: d60.toLocaleDateString(), y: parseInt(data[2].N_60) },
						{ label: d61.toLocaleDateString(), y: parseInt(data[2].N_61) },
						{ label: d62.toLocaleDateString(), y: parseInt(data[2].N_62) },
						{ label: d63.toLocaleDateString(), y: parseInt(data[2].N_63) },
						{ label: d64.toLocaleDateString(), y: parseInt(data[2].N_64) },
						{ label: d65.toLocaleDateString(), y: parseInt(data[2].N_65) },
						{ label: d66.toLocaleDateString(), y: parseInt(data[2].N_66) },
						{ label: d67.toLocaleDateString(), y: parseInt(data[2].N_67) },
						{ label: d68.toLocaleDateString(), y: parseInt(data[2].N_68) },
						{ label: d69.toLocaleDateString(), y: parseInt(data[2].N_69) },
						{ label: d70.toLocaleDateString(), y: parseInt(data[2].N_70) },
						{ label: d71.toLocaleDateString(), y: parseInt(data[2].N_71) },
						{ label: d72.toLocaleDateString(), y: parseInt(data[2].N_72) },
						{ label: d73.toLocaleDateString(), y: parseInt(data[2].N_73) },
						{ label: d74.toLocaleDateString(), y: parseInt(data[2].N_74) },
						{ label: d75.toLocaleDateString(), y: parseInt(data[2].N_75) },
						{ label: d76.toLocaleDateString(), y: parseInt(data[2].N_76) },
						{ label: d77.toLocaleDateString(), y: parseInt(data[2].N_77) },
						{ label: d78.toLocaleDateString(), y: parseInt(data[2].N_78) },
						{ label: d79.toLocaleDateString(), y: parseInt(data[2].N_79) },
						{ label: d80.toLocaleDateString(), y: parseInt(data[2].N_80) },
						{ label: d81.toLocaleDateString(), y: parseInt(data[2].N_81) },
						{ label: d82.toLocaleDateString(), y: parseInt(data[2].N_82) },
						{ label: d83.toLocaleDateString(), y: parseInt(data[2].N_83) },
						{ label: d84.toLocaleDateString(), y: parseInt(data[2].N_84) },
						{ label: d85.toLocaleDateString(), y: parseInt(data[2].N_85) },
						{ label: d86.toLocaleDateString(), y: parseInt(data[2].N_86) },
						{ label: d87.toLocaleDateString(), y: parseInt(data[2].N_87) },
						{ label: d88.toLocaleDateString(), y: parseInt(data[2].N_88) },
						{ label: d89.toLocaleDateString(), y: parseInt(data[2].N_89) },
						{ label: d90.toLocaleDateString(), y: parseInt(data[2].N_90) }
					]
					
				},
				{        
					type: "line",  
					name: "MIN ITO Days",        
					showInLegend: true,
					dataPoints: [
						{ label: d1.toLocaleDateString(), y: parseInt(data[3].N_1) },     
						{ label: d2.toLocaleDateString(), y: parseInt(data[3].N_2) },     
						{ label: d3.toLocaleDateString(), y: parseInt(data[3].N_3) },     
						{ label: d4.toLocaleDateString(), y: parseInt(data[3].N_4) },     
						{ label: d5.toLocaleDateString(), y: parseInt(data[3].N_5) },
						{ label: d6.toLocaleDateString(), y: parseInt(data[3].N_6) },
						{ label: d7.toLocaleDateString(), y: parseInt(data[3].N_7) },     
						{ label: d8.toLocaleDateString(), y: parseInt(data[3].N_8) },     
						{ label: d9.toLocaleDateString(), y: parseInt(data[3].N_9) },     
						{ label: d10.toLocaleDateString(), y: parseInt(data[3].N_10) },
						{ label: d11.toLocaleDateString(), y: parseInt(data[3].N_11) },     
						{ label: d12.toLocaleDateString(), y: parseInt(data[3].N_12) },     
						{ label: d13.toLocaleDateString(), y: parseInt(data[3].N_13) },     
						{ label: d14.toLocaleDateString(), y: parseInt(data[3].N_14) },     
						{ label: d15.toLocaleDateString(), y: parseInt(data[3].N_15) },
						{ label: d16.toLocaleDateString(), y: parseInt(data[3].N_16) },
						{ label: d17.toLocaleDateString(), y: parseInt(data[3].N_17) },     
						{ label: d18.toLocaleDateString(), y: parseInt(data[3].N_18) },     
						{ label: d19.toLocaleDateString(), y: parseInt(data[3].N_19) }, 
						{ label: d20.toLocaleDateString(), y: parseInt(data[3].N_20) },
						{ label: d21.toLocaleDateString(), y: parseInt(data[3].N_21) },
						{ label: d22.toLocaleDateString(), y: parseInt(data[3].N_22) },
						{ label: d23.toLocaleDateString(), y: parseInt(data[3].N_23) },
						{ label: d24.toLocaleDateString(), y: parseInt(data[3].N_24) },
						{ label: d25.toLocaleDateString(), y: parseInt(data[3].N_25) },
						{ label: d26.toLocaleDateString(), y: parseInt(data[3].N_26) },
						{ label: d27.toLocaleDateString(), y: parseInt(data[3].N_27) },
						{ label: d28.toLocaleDateString(), y: parseInt(data[3].N_28) },
						{ label: d29.toLocaleDateString(), y: parseInt(data[3].N_29) },
						{ label: d30.toLocaleDateString(), y: parseInt(data[3].N_30) }, 
						{ label: d31.toLocaleDateString(), y: parseInt(data[3].N_31) }, 
						{ label: d32.toLocaleDateString(), y: parseInt(data[3].N_32) }, 
						{ label: d33.toLocaleDateString(), y: parseInt(data[3].N_33) }, 
						{ label: d34.toLocaleDateString(), y: parseInt(data[3].N_34) }, 
						{ label: d35.toLocaleDateString(), y: parseInt(data[3].N_35) }, 
						{ label: d36.toLocaleDateString(), y: parseInt(data[3].N_36) }, 
						{ label: d37.toLocaleDateString(), y: parseInt(data[3].N_37) }, 
						{ label: d38.toLocaleDateString(), y: parseInt(data[3].N_38) }, 
						{ label: d39.toLocaleDateString(), y: parseInt(data[3].N_39) }, 
						{ label: d40.toLocaleDateString(), y: parseInt(data[3].N_40) }, 
						{ label: d41.toLocaleDateString(), y: parseInt(data[3].N_41) }, 
						{ label: d42.toLocaleDateString(), y: parseInt(data[3].N_42) }, 
						{ label: d43.toLocaleDateString(), y: parseInt(data[3].N_43) }, 
						{ label: d44.toLocaleDateString(), y: parseInt(data[3].N_44) }, 
						{ label: d45.toLocaleDateString(), y: parseInt(data[3].N_45) }, 
						{ label: d46.toLocaleDateString(), y: parseInt(data[3].N_46) }, 
						{ label: d47.toLocaleDateString(), y: parseInt(data[3].N_47) }, 
						{ label: d48.toLocaleDateString(), y: parseInt(data[3].N_48) }, 
						{ label: d49.toLocaleDateString(), y: parseInt(data[3].N_49) }, 
						{ label: d50.toLocaleDateString(), y: parseInt(data[3].N_50) },
						{ label: d51.toLocaleDateString(), y: parseInt(data[3].N_51) },
						{ label: d52.toLocaleDateString(), y: parseInt(data[3].N_52) },
						{ label: d53.toLocaleDateString(), y: parseInt(data[3].N_53) },
						{ label: d54.toLocaleDateString(), y: parseInt(data[3].N_54) },
						{ label: d55.toLocaleDateString(), y: parseInt(data[3].N_55) },
						{ label: d56.toLocaleDateString(), y: parseInt(data[3].N_56) },
						{ label: d57.toLocaleDateString(), y: parseInt(data[3].N_57) },
						{ label: d58.toLocaleDateString(), y: parseInt(data[3].N_58) },
						{ label: d59.toLocaleDateString(), y: parseInt(data[3].N_59) },
						{ label: d60.toLocaleDateString(), y: parseInt(data[3].N_60) },
						{ label: d61.toLocaleDateString(), y: parseInt(data[3].N_61) },
						{ label: d62.toLocaleDateString(), y: parseInt(data[3].N_62) },
						{ label: d63.toLocaleDateString(), y: parseInt(data[3].N_63) },
						{ label: d64.toLocaleDateString(), y: parseInt(data[3].N_64) },
						{ label: d65.toLocaleDateString(), y: parseInt(data[3].N_65) },
						{ label: d66.toLocaleDateString(), y: parseInt(data[3].N_66) },
						{ label: d67.toLocaleDateString(), y: parseInt(data[3].N_67) },
						{ label: d68.toLocaleDateString(), y: parseInt(data[3].N_68) },
						{ label: d69.toLocaleDateString(), y: parseInt(data[3].N_69) },
						{ label: d70.toLocaleDateString(), y: parseInt(data[3].N_70) },
						{ label: d71.toLocaleDateString(), y: parseInt(data[3].N_71) },
						{ label: d72.toLocaleDateString(), y: parseInt(data[3].N_72) },
						{ label: d73.toLocaleDateString(), y: parseInt(data[3].N_73) },
						{ label: d74.toLocaleDateString(), y: parseInt(data[3].N_74) },
						{ label: d75.toLocaleDateString(), y: parseInt(data[3].N_75) },
						{ label: d76.toLocaleDateString(), y: parseInt(data[3].N_76) },
						{ label: d77.toLocaleDateString(), y: parseInt(data[3].N_77) },
						{ label: d78.toLocaleDateString(), y: parseInt(data[3].N_78) },
						{ label: d79.toLocaleDateString(), y: parseInt(data[3].N_79) },
						{ label: d80.toLocaleDateString(), y: parseInt(data[3].N_80) },
						{ label: d81.toLocaleDateString(), y: parseInt(data[3].N_81) },
						{ label: d82.toLocaleDateString(), y: parseInt(data[3].N_82) },
						{ label: d83.toLocaleDateString(), y: parseInt(data[3].N_83) },
						{ label: d84.toLocaleDateString(), y: parseInt(data[3].N_84) },
						{ label: d85.toLocaleDateString(), y: parseInt(data[3].N_85) },
						{ label: d86.toLocaleDateString(), y: parseInt(data[3].N_86) },
						{ label: d87.toLocaleDateString(), y: parseInt(data[3].N_87) },
						{ label: d88.toLocaleDateString(), y: parseInt(data[3].N_88) },
						{ label: d89.toLocaleDateString(), y: parseInt(data[3].N_89) },
						{ label: d90.toLocaleDateString(), y: parseInt(data[3].N_90) }
					]
					
				}]
			});

			chart.render();
			 					

							
			}
			});


      
}



function increment(){
    i = i % 360 + 1;
    
}

function fancyTimeFormat(time)
{   
    // Hours, minutes and seconds
    var hrs = ~~(time / 3600);
    var mins = ~~((time % 3600) / 60);
    var secs = time % 60;

    // Output like "1:01" or "4:03:59" or "123:03:59"
    var ret = "";

    if (hrs > 0) {
        ret += "" + hrs + ":" + (mins < 10 ? "0" : "");
    }

    ret += "" + mins + ":" + (secs < 10 ? "0" : "");
    ret += "" + secs;
    return ret;
}






</script>  
</body>
    </html>
