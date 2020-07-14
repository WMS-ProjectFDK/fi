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
      <script type="text/javascript" src="canvasjs.min.js"></script>
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
		<span style="display:inline-block;font-size: 25px;font-weight: bold;">REALTIME MONITORING STOP TIME SEPARATOR</span> 
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
		
	<table id="dg"  halign="center" class="easyui-datagrid" url= "result.json"  style="width:100%;height:410px;font-family:arial;" rownumbers="true" fitColumns="true">
		<thead>
		<tr>
			<th  width="45"  align="center" valign="top" field="LINE" formatter="formatField"><b><span style="font-size: 22px;">LINE</span></b></th>
			<th  width="45"  align="center" field="STS" data-options="styler:cellStyler2" formatter="formatField"><b><span style="font-size: 22px">STATUS</span></b></th>
			<th  width="40" halign="center" align="center" field="STARTS" formatter="formatField"><b><span style="font-size: 22px">WORKING TIME</span></b></th>
			<th  width="35" halign="center" align="center" field="STOPS" formatter="formatField"><b><span style="font-size: 22px">STOP TIME</span></b></th>
			<th  width="50" halign="center" align="center" field="RASIO" data-options="styler:cellStyler3" formatter="formatField"><b><span style="font-size: 25px">OPT TIME RATIO</span></b></th>
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

		var myVar = setInterval(function(){myTimer()},1000);
		var myVar2 = setInterval(function(){myTimer2()},1000*60);
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
	var vRasio=0;

	function myTimer2(){
		$.ajax({
			type: 'GET',
			dataType: "json",
			url: 'json_view_monitor_all.php'
		});
	}

	function myTimer(){
	    var d = new Date();
	    var n = parseInt(d.getDate());
 		var pesan = '';	
        var data;
        var sts = 'RUNNING'
		$.ajax({
			type: 'GET',
			dataType: "json",
			url: 'result.json',
			data: data,
			success: function (data) {
				a++; b++; c++; d++; e++; f++; g++;
				h++; i++; j++; k++; l++; m++;
				ref++; loop++; totalstop = 0;

				var d = new Date();
				var yy = d.getFullYear();
				var mm = d.getMonth()+1;
				var dd = d.getDate();

				document.getElementById("tanggal1").innerHTML = yy+'-'+mm+'-'+dd;
		        document.getElementById("waktu").innerHTML = d.toLocaleTimeString().replace('.',':').replace('.',':');

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
								{ label: "3#1 1A Rasio", y: parseInt(data[0].RASIO_LR03_1_1A)/100 },
								{ label: "3#1 1B Rasio", y: parseInt(data[0].RASIO_LR03_1_1B)/100 },
								{ label: "3#1 2A Rasio", y: parseInt(data[0].RASIO_LR03_1_2A)/100 },
								{ label: "3#1 2B Rasio", y: parseInt(data[0].RASIO_LR03_1_2B)/100 },
								{ label: "3#1 3A Rasio", y: parseInt(data[0].RASIO_LR03_1_3A)/100 },
								{ label: "3#1 3B Rasio", y: parseInt(data[0].RASIO_LR03_1_3B)/100 },
								{ label: "3#1 4A Rasio", y: parseInt(data[0].RASIO_LR03_1_4A)/100 },
								{ label: "3#1 4B Rasio", y: parseInt(data[0].RASIO_LR03_1_4B)/100 },
								{ label: "3#1 5A Rasio", y: parseInt(data[0].RASIO_LR03_1_5A)/100 },
								{ label: "3#1 5B Rasio", y: parseInt(data[0].RASIO_LR03_1_5B)/100 },
								{ label: "6#1 1A Rasio", y: parseInt(data[0].RASIO_LR6_1_1A)/100 },
								{ label: "6#1 1B Rasio", y: parseInt(data[0].RASIO_LR6_1_1B)/100 },
								{ label: "6#1 2A Rasio", y: parseInt(data[0].RASIO_LR6_1_2A)/100 },
								{ label: "6#1 2B Rasio", y: parseInt(data[0].RASIO_LR6_1_2B)/100 },
								{ label: "6#1 3A Rasio", y: parseInt(data[0].RASIO_LR6_1_3A)/100 },
								{ label: "6#1 3B Rasio", y: parseInt(data[0].RASIO_LR6_1_3B)/100 },
								{ label: "6#1 4A Rasio", y: parseInt(data[0].RASIO_LR6_1_4A)/100 },
								{ label: "6#1 4B Rasio", y: parseInt(data[0].RASIO_LR6_1_4B)/100 },
								{ label: "6#2 1A Rasio", y: parseInt(data[0].RASIO_LR6_2_1A)/100 },
								{ label: "6#2 1B Rasio", y: parseInt(data[0].RASIO_LR6_2_1B)/100 },
								{ label: "6#2 2A Rasio", y: parseInt(data[0].RASIO_LR6_2_2A)/100 },
								{ label: "6#2 2B Rasio", y: parseInt(data[0].RASIO_LR6_2_2B)/100 },
								{ label: "6#2 3A Rasio", y: parseInt(data[0].RASIO_LR6_2_3A)/100 },
								{ label: "6#2 3B Rasio", y: parseInt(data[0].RASIO_LR6_2_3B)/100 },
								{ label: "6#2 4A Rasio", y: parseInt(data[0].RASIO_LR6_2_4A)/100 },
								{ label: "6#2 4B Rasio", y: parseInt(data[0].RASIO_LR6_2_4B)/100 },
								{ label: "6#3 1A Rasio", y: parseInt(data[0].RASIO_LR6_3_1A)/100 },
								{ label: "6#3 1B Rasio", y: parseInt(data[0].RASIO_LR6_3_1B)/100 },
								{ label: "6#3 2A Rasio", y: parseInt(data[0].RASIO_LR6_3_2A)/100 },
								{ label: "6#3 2B Rasio", y: parseInt(data[0].RASIO_LR6_3_2B)/100 },
								{ label: "6#3 3A Rasio", y: parseInt(data[0].RASIO_LR6_3_3A)/100 },
								{ label: "6#3 3B Rasio", y: parseInt(data[0].RASIO_LR6_3_3B)/100 },
								{ label: "6#3 4A Rasio", y: parseInt(data[0].RASIO_LR6_3_4A)/100 },
								{ label: "6#3 4B Rasio", y: parseInt(data[0].RASIO_LR6_3_4B)/100 },
								{ label: "6#4 1A Rasio", y: parseInt(data[0].RASIO_LR6_4_1A)/100 },
								{ label: "6#4 1B Rasio", y: parseInt(data[0].RASIO_LR6_4_1B)/100 },
								{ label: "6#4 2A Rasio", y: parseInt(data[0].RASIO_LR6_4_2A)/100 },
								{ label: "6#4 2B Rasio", y: parseInt(data[0].RASIO_LR6_4_2B)/100 },
								{ label: "6#4 3A Rasio", y: parseInt(data[0].RASIO_LR6_4_3A)/100 },
								{ label: "6#4 3B Rasio", y: parseInt(data[0].RASIO_LR6_4_3B)/100 },
								{ label: "6#4 3B Rasio", y: parseInt(data[0].RASIO_LR6_4_3B)/100 },
								{ label: "6#4 4A Rasio", y: parseInt(data[0].RASIO_LR6_4_4A)/100 },
								{ label: "6#4 4B Rasio", y: parseInt(data[0].RASIO_LR6_4_4B)/100 },
								{ label: "6#4 5A Rasio", y: parseInt(data[0].RASIO_LR6_4_5A)/100 },
								{ label: "6#4 5B Rasio", y: parseInt(data[0].RASIO_LR6_4_5B)/100 }
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