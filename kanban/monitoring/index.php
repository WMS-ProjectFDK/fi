<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monitoring Assembling</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
    <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
     <script type="text/javascript" src="canvasjs.min.js"></script>
      <script type="text/javascript" src="jquery.canvasjs.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
	
	<style>
	*{
	font-size:11px;
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
	.datagrid-header .datagrid-cell span{
	    font-weight: bold;
	    color: black;
	    font-size:100px;
	}
	</style>
	
</head>
<body>
	<div class="fitem" align="center">
		<span style="display:inline-block;font-size: 25px;font-weight: bold;">Realtime Monitoring Stop Time Assembly Line</span> 
	</div>
	<div id="toolbar">
		<fieldset style="width:605px;height: 30px;border-radius:3px;float:left;">
			<div class="fitem" align="left">
				<span  style="width:130px;display:inline-block;font-size: 25px;font-weight: bold;">Date :</span>
				<span  style="width:205px;height: 30px;font-size: 25px;font-weight: bold;" name="tanggal" id="tanggal1" > </span>
			</div>
		</fieldset>
		<fieldset style="height: 30px;border-radius:3px;margin-left:505px;">
			<div class="fitem" align="left">
				<span  style="width:130px;display:inline-block;font-size: 25px;font-weight: bold;">Time :</span>
				<span  style="width:205px;height: 30px;font-size: 25px;font-weight: bold;" name="tanggal" id="waktu"> </span>
			</div>	
		</fieldset>
	</div>

	<div id="toolbar" align="center">
		<fieldset style="width:auto;height: 30px;border-radius:3px;" id="fld">
			<span style="display:inline-block;font-size: 25px;font-weight: bold;" id="INFORMASI">INFORMATION</span> 
		</fieldset>
	</div>
		
	<table id="dg"  halign="center" class="easyui-datagrid" url= "json_view_monitor_all.php"  style="width:100%;height:410px;font-family:arial;" rownumbers="true" fitColumns="true">
		<thead>
		<tr>
			<th  width="25"  align="center" valign="top" field="LINE" formatter="formatField"><b><span style="font-size: 22px;">LINE</span></b></th>
			<th  width="45"  align="center" field="STS" data-options="styler:cellStyler2" formatter="formatField"><b><span style="font-size: 22px">STATUS</span></b></th>
			<th  width="40" halign="center" align="center" field="STARTS" formatter="formatField"><b><span style="font-size: 22px">WORKING TIME</span></b></th>
			<th  width="35" halign="center" align="center" field="STOPS" formatter="formatField"><b><span style="font-size: 22px">STOP TIME</span></b></th>
			<th  width="50" halign="center" align="center" field="RASIO" data-options="styler:cellStyler3" formatter="formatField"><b><span style="font-size: 25px">OPT TIME RATIO</span></b></th>
			<th  width="30" halign="center" align="center" field="LINK" formatter="formatField"><b><span style="font-size: 22px">DETAILS</span></b></th>
		</tr>
		</thead>
	</table>

	<div id="chartContainer" style="height: 300px; width: 100%;"></div>
	<span align="center" id="demo" width="100" height="100"></span>

<script type="text/javascript">
	$(function(){
		$('#dg').datagrid({
			onLoadSuccess:function(){
			var dg = $(this);
			var opts = $(dg).datagrid('options');
			opts.finder.getTr(dg[0], 1000, 'allbody').css('height', '50px');	
			}
		});

	});

	function cellStyler2(value,row,index){
	 	if (row.STS == 'RUNNING') {
	        return 'background-color:green;color:white;';
	    }else if (row.STS == 'STOP'){
	    	return 'background-color:red;color:white;';
	    }else {
	    	return 'background-color:yellow;color:black;';
	    }
	}

	function cellStyler3(value,row,index){
		if (row.RASIO1 > 70) {
	        return 'background-color:green;color:white;';
	    }else if (row.RASIO1 < 60 ){
	    	return 'background-color:red;color:white;';
	    }else {
	    	return 'background-color:yellow;color:black;';
	    }
	}

	function formatField(value){
		return '<span style="font-size:25px">'+value+'</span>';
	}

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

	var totalstop = 0;
	var a = 1;
	var b = 1;
	var c = 1;
	var d = 1;
	var e = 1;
	var f = 1;
	var g = 1;
	var h = 1;
	var i = 1;
	var j = 1;
	var k = 1;
	var l = 1;
	var m = 1;
	var mld = 0;
	var loop = 1;
	var ref=1;
	var myVar = setInterval(function(){myTimer()},1000);
	var vRasio=0;

	function myTimer(){
	    var d = new Date();
	    var n = parseInt(d.getDate());
 		var pesan = '';	
        var data;
        var sts = 'RUNNING'
		$.ajax({
			type: 'GET',
			dataType: "json",
			url: 'json_view_monitor_all.php',
			data: data,
			success: function (data) {
				a++; b++; c++; d++; e++; f++; g++;
				h++; i++; j++; k++; l++; m++;
				ref++; loop++; totalstop = 0;

				document.getElementById("waktu").innerHTML = data[0].WKT;
	    		document.getElementById("tanggal1").innerHTML = data[0].TGL;

	    		vrasio = data[0].RASIO;
		    	if (a == 4){
	    			$('#dg').datagrid('reload');
	    			a = 0;
	    		};

				if (ref > 300){
					if (totalstop == 0) {
						window.location.reload(true);
						ref = 0;	
					}
				};

				if (loop  > 4){
					loop = 0;
		 
					var chart = new CanvasJS.Chart("chartContainer", {
						title: {text: "OPERATION TIME RATIO GRAPH"},
						axisY: {title: "Process in Percent",},
						data: [{
							type: "column",	
							yValueFormatString: "##.## %",
							indexLabel: "{y}",
							dataPoints: [
								{ label: "3#1 Rasio", y: parseInt(data[0].RASIO31)/100 },
								{ label: "3#2 Rasio", y: parseInt(data[0].RASIO32)/100 },
								{ label: "6#1 Rasio", y: parseInt(data[0].RASIO61)/100 },
								{ label: "6#2 Rasio", y: parseInt(data[0].RASIO62)/100 },
								{ label: "6#3 Rasio", y: parseInt(data[0].RASIO63)/100 },
								{ label: "6#4 Rasio", y: parseInt(data[0].RASIO64)/100 },
								{ label: "6#6 Rasio", y: parseInt(data[0].RASIO66)/100 }
							]
						}]
					});
		            chart.render();
		        };
			}
		});
	}

	function increment(){
	    i = i % 360 + 1;
	}

	function fancyTimeFormat(time){   
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