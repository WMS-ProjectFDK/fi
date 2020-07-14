<!doctype html>
<html>
<head>
	<title>Headsontable Contoh</title>

	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="http://handsontable.com/dist/handsontable.full.js"></script>
	<link rel="stylesheet" media="screen" href="http://handsontable.com/dist/handsontable.full.css">
	<script src="http://handsontable.com/demo/js/samples.js"></script>
	<script src="http://handsontable.com/demo/js/samples.js"></script>
	<script src="http://handsontable.com/demo/js/samples.js"></script>
	<style type="text/css">
		body {
			background: white; 
			margin: 20px;
		}
		h2 {margin: 20px 0;
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function(){
			var data1 = [
		    ["", "Maserati", "Mazda", "Mercedes", "Mini", "Mitsubishi"],
		    ["2009", 0, 2941, 4303, 354, 5814],
		    ["2010", 5, 2905, 2867, 412, 5284],
		    ["2011", 4, 2517, 4822, 552, 6127],
		    ["2012", 2, 2422, 5399, 776, 4151]
		  ];

		  var container=document.getElementById('example');

		  var hot = new Handsontable(container,{
		  	//data: data1,
		  	minSpareRows: 1,
		  	colHeaders: true,
		  	contextMenu: true
		  });

		 
		  Handsontable.Dom.addEvent(load,'click', function (){
		    ajax(
		      "json/load.json",
		      'GET',
		      function (res) {
		        var data = JSON.parse(res.response);
		        hot1.loadData(data.data);
		        exapmleConsole.innerText = 'Data loaded';
		      }
		    );
		  });
		});
	</script>
	
</head>
<body>
	<h2>Save Data Dengan Handsontable</h2>
	<p>
		<button name="load" id="load">Load</button>
		<button name="save" id="save">Save</button>
	</p>

	<div class="handsontable" id="example"></div>
</body>
</html>