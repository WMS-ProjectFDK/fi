<html>
<head>
	<!-- jQuery - the core -->
	<script src="../js/jquery-1.8.1.min.js" type="text/javascript"></script>
	<!-- Sliding effect -->
	<script src="../js/slide.js" type="text/javascript"></script>

	<style>
		.style4 {
		font-size: 11px;
		color: #CC0000;
		}
	</style>
</head>
<body>
	<canvas id="clock" width="100" height="100"></canvas>
	<div style="margin: 0px 0px;">
		<span align="center" id="demo" width="100" height="100"></span>
		<p><b>AUTO SEND MAIL..</b></p>
	</div>

<script type="text/javascript">
	var myVar = setInterval(function(){myTimer()},1000);
	// var myVar2 = setInterval(function(){myTimer2()},1000*60*5);

	function myTimer(){
		$.ajax({
			type: 'GET',
			url: 'migration_spareparts.php'
		});
	}
	
	// function myTimer(){
	//     var d = new Date();
	//     var n = parseInt(d.getDate());
	    
	//     document.getElementById("demo").innerHTML = d.toLocaleTimeString().replace('.',':').replace('.',':');

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '2:00:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'BomMpsInventory.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '3:00:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'proses_delivery_update.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '4:00:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'execute_sp.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '7:00:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'finishing_report_excel.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '7:05:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'label_mail_update.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '7:10:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'finishing_report_mail.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '7:13:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'label_mail_excel.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '7:15:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'label_mail.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '7:30:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'mrp_run_get.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '8:00:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'shipping_mail.php'
	// 		});
	// 	}
		
	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '9:00:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'send_mail_Inventory.php'
	// 		});
	// 	}
		
	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '9:15:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'send_mail_excel.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '10:00:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'send_mail.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '10:15:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'semi_batt_mail.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '11:30:00 AM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'execute_sp.php'
	// 		});
	// 	}

	// 	if(n == 1){
	// 		if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '3:00:00 AM'){
	// 		    $.ajax({
	// 				type: 'GET',
	// 				url: 'safety_stock_auto.php'
	// 			});
	// 		}

	// 		if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '8:30:00 AM'){
	// 		    $.ajax({
	// 				type: 'GET',
	// 				url: 'fg_execute.php'
	// 			});
	// 		}		

	// 		if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '8:40:00 AM'){
	// 		    $.ajax({
	// 				type: 'GET',
	// 				url: 'send_mail_fg_new.php'
	// 			});
	// 		}
	// 	}else{
	// 		if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '2:00:00 PM'){
	// 		    $.ajax({
	// 				type: 'GET',
	// 				url: 'fg_execute.php'
	// 			});
	// 		}		

	// 		if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '3:20:00 PM'){
	// 		    $.ajax({
	// 				type: 'GET',
	// 				url: 'send_mail_fg_new.php'
	// 			});
	// 		}
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '1:00:00 PM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'spareparts_PO_mail_excel.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '1:05:00 PM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'spareparts_PO_mail.php'
	// 		});
	// 	}

	// 	if (d.toLocaleTimeString().replace('.',':').replace('.',':') == '3:00:00 PM'){
	// 	    $.ajax({
	// 			type: 'GET',
	// 			url: 'shipping_mail.php'
	// 		});
	// 	}

	// }

	window.onload = function(){
	function draw(){
		var ctx = document.getElementById('clock').getContext('2d');
		ctx.strokeStyle = "rgba(0, 0, 0, 1)";
		ctx.clearRect(0, 0, 100, 100);
		ctx.beginPath();
		ctx.lineWidth = 1;
		ctx.arc(50, 50, 48, 0, Math.PI * 2, true);
		ctx.stroke();
		var i; 
		for(i=0; i < 360; i+=6){
			ctx.lineWidth = ((i % 30)==0)?3:1;
			ctx.strokeStyle = ((i % 30)==0)?"rgb(200,0,0)":"rgb(0,0,0)";
			var r = i * Math.PI / 180;
			ctx.beginPath();
			ctx.moveTo(50+(45 * Math.sin(r)), 50+(45 * Math.cos(r)));
			ctx.lineTo(50+((((i % 30)==0)?37:40) * Math.sin(r)),
			50+((((i % 30)==0)?37:40) * Math.cos(r)));
			ctx.closePath();
			ctx.stroke();
		}
		ctx.strokeStyle = "rgba(32, 32, 32, 0.6)";

		// hour
		var d = new Date();
		var h = (d.getHours() % 12) + (d.getMinutes() / 60);
		ctx.lineWidth = 4;
		ctx.beginPath();
		ctx.moveTo(50+(25 * Math.sin(h * 30 * Math.PI / 180)),
		50+(-25 * Math.cos(h * 30 * Math.PI / 180)));
		ctx.lineTo(50+(5 * Math.sin((h+6) * 30 * Math.PI / 180)),
		50+(-5 * Math.cos((h+6) * 30 * Math.PI / 180)));
		ctx.closePath(); ctx.stroke();

		//minute
		var m = d.getMinutes() + (d.getSeconds() / 60);
		ctx.strokeStyle = "rgba(32, 32, 62, 0.8)";
		ctx.lineWidth = 2;
		ctx.beginPath();
		ctx.moveTo(50+(38 * Math.sin(m * 6 * Math.PI / 180)),
		50+(-38 * Math.cos(m * 6 * Math.PI / 180)));
		ctx.lineTo(50+(3 * Math.sin((m+30) * 6 * Math.PI / 180)),
		50+(-3 * Math.cos((m+30) * 6 * Math.PI / 180)));
		ctx.closePath();
		ctx.stroke();

		//second
		var s = d.getSeconds() + (d.getMilliseconds() / 1000);
		ctx.strokeStyle = "rgba(0, 255, 0, 0.7)";
		ctx.lineWidth = 1;
		ctx.beginPath();
		ctx.moveTo(50+(45 * Math.sin(s * 6 * Math.PI / 180)),
		50+(-45 * Math.cos(s * 6 * Math.PI / 180)));
		ctx.lineTo(50+(10 * Math.sin((s+30) * 6 * Math.PI / 180)),
		50+(-10 * Math.cos((s+30) * 6 * Math.PI / 180)));
		ctx.closePath(); ctx.stroke(); 
	} 
	setInterval(draw, 1000); }
</script>

</body> 
</html>