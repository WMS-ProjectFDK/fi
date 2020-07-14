
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="300">
    <title>Monitoring Finishing</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
    <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="canvasjs.min.js"></script>
    <!-- <script type="text/javascript" src="jquery.canvasjs.min.js"></script> -->
    <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
	
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
	.blink-replace{
		display: inline-block;
		animation: blinkingBackground 1s infinite;
	}
	@keyframes blinkingBackgroundReplace{
		50% { background-color: #FF0000;}
	}

	@keyframes blinkingBackgroundOrder{
		50% { background-color: #FFFF00;}
	}

	.blink-order-replacement{
		animation: blinkingBackgroundReplace 1s infinite;
	}

	.blink-order{
		animation: blinkingBackgroundOrder 1s infinite;
	}
	</style>
</head>
<body>
	<div class="fitem" align="center">
		<span style="display:inline-block;font-size: 25px;font-weight: bold;">REALTIME MONITORING STOP TIME AUTOSHRINK LR6 LINE</span> 
	</div>

	<div id="toolbar">
		<fieldset style="width:605px;height: 30px;border-radius:3px;float:left;">
			<div class="fitem" align="left">
				<span  style="width:130px;display:inline-block;font-size: 25px;font-weight: bold;">DATE :</span>
				<span  style="width:205px;font-size: 25px;font-weight: bold;" name="tanggal" id="tanggal1" > </span>
			</div>
			<div class="fitem" align="center"></div>	
		</fieldset>

		<fieldset style="height: 30px;border-radius:3px;margin-left:505px;">
			<div class="fitem" align="left">
				<span  style="width:130px;display:inline-block;font-size: 25px;font-weight: bold;">TIME :</span>
				<span  style="width:205px;font-size: 25px;font-weight: bold;" name="tanggal" id="waktu"> </span>
			</div>
			<div class="fitem" align="center"></div>	
		</fieldset>
	</div>

	<div id="toolbar" align="center">
		<fieldset style="width:auto;height: 30px;border-radius:3px;" id="fld">
			<span style="display:inline-block;font-size: 30px;font-weight: bold;" id="INFORMASI">INFORMASI</span> 
		</fieldset>
	</div>
		
	<div id="toolbar">
		<fieldset style="width:400px;height: 40px;border-radius:3px;float:left;text-align:center" >
			<div align="center">
				<span  style="width:400px;display:inline-block;font-size: 20px;font-weight: bold;">TOTAL WORKING TIME</span>
				<br>
				<span style="width:200px;height: 40px;font-size: 20px;text-align:center;font-weight: bold;" name="tanggal" id="jalan"> </span>
			</div>	
		</fieldset>
		<fieldset style="height: 40px;border-radius:2px;float:left;" id="fldstop">
			<div align="center">
				<span  style="width:400px;display:inline-block;font-size: 20px;font-weight: bold;">TOTAL LINE STOP (NO OUTPUT)</span>
				<br>
				<span  style="width:155px;height: 40px;font-size: 20px;text-align: center;font-weight: bold;" name="tanggal" id="stop"> </span>
			</div>	
		</fieldset>
		<fieldset style="height: 40px;border-radius:2px;margin-left:520px;" id="fldrasio">
			<div align="center">
				<span  style="width:350px;display:inline-block;font-size: 20px;font-weight: bold;">OPERATION TIME RATIO</span>
				<br>
				<span  style="width:70px;height: 40px;font-size: 20px;text-align: center;font-weight: bold;" name="tanggal" id="rasio"> </span>
			</div>	
		</fieldset>
	</div>
	<div id="toolbar" align="left">
		<fieldset style="width:auto;height: auto;border-radius:3px;">
			<span align="center" style="display:inline-block;font-size: 20px;font-weight: bold;">PROSES DETAIL</span> 
			<div align="left">
				<span  style="width:250px;display:inline-block;font-size: 20px;font-weight: bold;">Process name :</span>
				<div style="width:330px;height: 30px;display:inline-block;text-align:center;vertical-align: middle;background-color: #00AAFF;margin-left: 2px;" id="1">
					<a href="#" style="font-size: 20px;text-decoration: none; color: black;" onclick="machine('1')"><b>UNCASING</b></a>
				</div>
				<div style="width:330px;height: 30px;display:inline-block;text-align:center;vertical-align: middle;background-color: #00AAFF;margin-left: 2px;" id="2">
					<a href="#" style="font-size: 20px;text-decoration: none; color: black;" onclick="machine('2')"><b>SHRINK CUTING</b></a>
				</div>
				<div style="width:330px;height: 30px;display:inline-block;text-align: center;vertical-align: middle;background-color: #00AAFF;margin-left: 2px;" id="3"> 
					<a href="#" style="font-size: 20px;text-decoration: none; color: black;" onclick="machine('3')"><b>CASING LOADER</b></a>
				</div>
			</div>	
			<div align="left">
				<span  style="width:252px;display:inline-block;font-size: 20px;font-weight: bold;">Stop Time :</span>
				<input type="text" style="width:328px;height: 30px;background-color: rgb(0,255,0);font-size: 25px;text-align: center;" id="uncasing"> </input>
				<input type="text" style="width:328px;height: 30px;background-color: rgb(0,255,0);font-size: 25px;text-align: center;" id="shrink_cuting"> </input>
				<input type="text" style="width:328px;height: 30px;background-color: rgb(0,255,0);font-size: 25px;text-align: center;" id="casing_loader"> </input>
			</div>
			<div align="left"  style="margin-top: 10px;">
			<div align="left">
				<span  style="width:250px;display:inline-block;font-size: 14px;font-weight: bold;">SHIFT :</span>
				<div style="width:163px;height: 15px;display:inline-block;text-align:center;vertical-align: middle;background-color: #00AAFF;margin-left: 1.5px;" id="s1">I</div>
				<div style="width:163px;height: 15px;display:inline-block;text-align:center;vertical-align: middle;background-color: #00AAFF;margin-left: 1.5px;" id="s2">II</div>
				<div style="width:163px;height: 15px;display:inline-block;text-align: center;vertical-align: middle;background-color: #00AAFF;margin-left: 1.5px;" id="s3">III</div>
				<div style="width:163px;height: 15px;display:inline-block;text-align: center;vertical-align: middle;background-color: #00AAFF;margin-left: 1px;" id="s4">TOTAL</div>
			</div>
			<div align="left">
				<span  style="width:252px;display:inline-block;font-size: 14px;font-weight: bold;">OUTPUT (QTY) :</span>
				<input type="text" style="width:160px;height: 30px;background-color: rgb(0,255,0);font-size: 18px;text-align: right;font-weight: bold;" id="s_1"></input>
				<input type="text" style="width:160px;height: 30px;background-color: rgb(0,255,0);font-size: 18px;text-align: right;font-weight: bold;" id="s_2"></input>
				<input type="text" style="width:160px;height: 30px;background-color: rgb(0,255,0);font-size: 18px;text-align: right;font-weight: bold;" id="s_3"></input>
				<input type="text" style="width:160px;height: 30px;background-color: rgb(0,255,0);font-size: 18px;text-align: right;font-weight: bold;" id="s_4"></input>
			</div>
		</fieldset>
	</div>
	<div id="chartContainer" style="height: 300px; width: 100%;"></div>
	<span align="center" id="demo" width="100" height="100"></span>
</body>
</html>

<script type="text/javascript">
	var totalstop = 0;
	var a = 1;
	var b = 1;
	var c = 1;

	var sA = 1;			var sZ = 1;
	var mld = 0;		var loop = 1;
	var ref = 1;		var vRasio = 0;
	var myVar = setInterval(function(){myTimer()},1000);

	function myTimer(){
	    var d = new Date();
	    var n = parseInt(d.getDate());
 		var pesan = '';	
        var data;
        var sts = 'RUNNING'

        $.ajax({
			type: 'GET',
			dataType: "json",
			url: 'json_view_output.php',
			data: data,
			success: function (data) {
				document.getElementById("s_1").value = data[0].shift_1+' ';
				document.getElementById("s_2").value = data[0].shift_2+' ';
				document.getElementById("s_3").value = data[0].shift_3+' ';
				document.getElementById("s_4").value = data[0].total+' ';
			}
		});

		$.ajax({
			type: 'GET',
			dataType: "json",
			url: 'json_view_monitoring.php?line=autoshrink',
			data: data,
			success: function (data) {
				document.getElementById("fldstop").style.backgroundColor = "white"
				document.getElementById("fldrasio").style.backgroundColor = "white"
				document.getElementById("fldrasio").style.color = "black"
				document.getElementById("fld").style.backgroundColor = "green"
				document.getElementById("INFORMASI").style.color = "white"
				document.getElementById("fldstop").style.color = "black"
				  
				a++;
				b++;
				c++;

				sA++;		sZ++;
				ref++;		loop++;
				totalstop = 0;

				//TIME
				document.getElementById("waktu").innerHTML = data[0].WKT;
	    		document.getElementById("tanggal1").innerHTML = data[0].TGL;

	    		//RASIO
	    		vrasio = data[0].CEKRASIO;
	    		if (vrasio  < 60){
	    			document.getElementById("fldrasio").style.backgroundColor = "RED"
	    			document.getElementById("fldrasio").style.color = "white"
	    		}else if(vrasio >= 60 & vrasio < 75){
	    			document.getElementById("fldrasio").style.backgroundColor = "yellow"
	    			document.getElementById("fldrasio").style.color = "black"
	    		}else if(vrasio >75){
	    			document.getElementById("fldrasio").style.backgroundColor = "Green"
	    			document.getElementById("fldrasio").style.color = "white"				
	    		};

				//START & STOP
				if (data[0].S_START == 0 & data[0].S_STOP ==0) {
					sts='PERMISIBLE STOP';
					document.getElementById("fld").style.backgroundColor = "yellow"
					document.getElementById("INFORMASI").style.color = "black"
					sA=0;
				}else if (data[0].S_START == 1 & data[0].S_STOP == 1) {
					cek = fancyTimeFormat(data[0].R_STOP)
					sts='STOP (NO OUTPUT)' + ' ' + cek;
					document.getElementById("fld").style.backgroundColor = "red"
					document.getElementById("INFORMASI").style.color = "black"
					document.getElementById("fldstop").style.backgroundColor = "red"
					document.getElementById("fldstop").style.color = "white"
					totalstop ++;
				}else{
					sA=0;
				}

				//mach-1
				if(data[0].S_UNCASING == false){
				  	document.getElementById("uncasing").style.backgroundColor = "green"
				  	document.getElementById("uncasing").style.color = "black"
				  	document.getElementById("uncasing").value	= fancyTimeFormat(data[0].R_UNCASING)
				  	a=0;
				}else{
				  	document.getElementById("uncasing").style.backgroundColor = "red"
				  	document.getElementById("uncasing").style.color = "white"
				  	document.getElementById("uncasing").value = fancyTimeFormat(data[0].R_UNCASING)
				  	totalstop ++;
				}

				//mach-2
				if(data[0].S_SHRINK_CUTTING == false){
				  	document.getElementById("shrink_cuting").style.backgroundColor = "green"
				  	document.getElementById("shrink_cuting").style.color = "black"
				  	document.getElementById("shrink_cuting").value	= fancyTimeFormat(data[0].R_SHRINK_CUTTING)
				  	b=0;
				}else{
				  	document.getElementById("shrink_cuting").style.backgroundColor = "red"
				  	document.getElementById("shrink_cuting").style.color = "white"
				  	document.getElementById("shrink_cuting").value = fancyTimeFormat(data[0].R_SHRINK_CUTTING)
				  	totalstop ++;
				}

				//mach-3
				if(data[0].S_CASING_LOADER == false){
				  	document.getElementById("casing_loader").style.backgroundColor = "green"
				  	document.getElementById("casing_loader").style.color = "black"
				  	document.getElementById("casing_loader").value = fancyTimeFormat(data[0].R_CASING_LOADER)
				  	c=0;
				}else{
				  	document.getElementById("casing_loader").style.backgroundColor = "red"
				  	document.getElementById("casing_loader").style.color = "white"
				  	document.getElementById("casing_loader").value = fancyTimeFormat(data[0].R_CASING_LOADER)
				  	totalstop ++;
				}

				document.getElementById("jalan").innerHTML = fancyTimeFormat(data[0].CEKJALAN);
                document.getElementById("stop").innerHTML  = fancyTimeFormat(data[0].CEKSTOP);
                document.getElementById("rasio").innerHTML = data[0].CEKRASIO + '%';
                document.getElementById("INFORMASI").innerHTML = sts;

				if (ref > 300){
					if (totalstop == 0) {
						window.location.reload(true);
						ref = 0;	
					}
				}

				if (loop  > 4){
					loop = 0;
				 
					var chart = new CanvasJS.Chart("chartContainer", {
						title: {
							text: "Stop Process In Autoshrink LR6 Line"
						},
						axisY: {
							title: "Process in Second",
						},
						data: [{
							type: "column",	
							yValueFormatString: "## Sec",
							indexLabel: "{y}",
							dataPoints: [
								{label: "UNCASING", y: parseInt(data[0].T_UNCASING)},
								{label: "SHRINK CUTTING", y: parseInt(data[0].T_SHRINK_CUTTING)},
								{label: "CASING LOAD", y: parseInt(data[0].T_CASING_LOADER)}
							]
						}]
					});
				    chart.render();
				}
			}
		});
	}

	// function myTimer2(){
	// 	$.ajax({
	// 		type: 'GET',
	// 		url: '_getPart.php?line=LR03-1'
	// 	});
	// }

	// function myTimer3(){
	// 	$('#dg').datagrid({
	// 		url : '_getPartOrder.php?line=LR03-1'
	// 	});
	// }

	// function increment(){
	//     i = i % 360 + 1; 
	// }

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