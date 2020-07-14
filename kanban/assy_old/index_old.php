<?php 
error_reporting(0);
session_start();
$id_kanban = $_SESSION['id_kanban'];
$user_name = strtoupper($_SESSION['name_kanban']);
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Assembly Kanban System</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
  	<link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
    <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../../js/datagrid-filter.js"></script>
	<script type="text/javascript" src="../../js/datagrid-detailview.js"></script>
	<script type="text/javascript" src="../../js/jquery.edatagrid.js"></script>
	<script type="text/javascript" src="my.js"></script>

	<style>
	*{
	font-size:12px;
	}
	body {
		font-family:verdana,helvetica,arial,sans-serif;
		padding:20px;
		font-size:16px;
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
		<div id="p" class="easyui-panel" title="ASSEMBLY KANBAN SYSTEM" data-options="iconCls:'icon-save', tools:'#tt', footer:'#footer'"
			style="width:100%;height:638px;padding:5px;">
			<table id="dg_in" class="easyui-datagrid" toolbar="#toolbar_in" style="width:100%;height:370px;"></table>
			<div id="toolbar_in">
				<div style="width:580px; float:left;padding: 10px;" class="style3">
					<div class="fitem" style="padding: 5px;">
						<span style="width:100px;display:inline-block;font-size: 25px;font-weight: bold;">SCAN</span> 
						<input 
							style="width:450px; height: 35px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
							onkeypress="scan(event)" name="scn" id="scn" type="text"
							autofocus="autofocus"
						/>
					</div>
					<div class="fitem">
						<span style="width:105px;display:inline-block;font-weight: bold;"></span>
						<span style="width:120px;display:inline-block;font-weight: bold;">Assy Line</span>
						<span style="width:120px;display:inline-block;font-weight: bold;">Cell Type</span>
						<span style="width:80px;display:inline-block;font-weight: bold;">Pallet</span>
						<span style="width:120px;display:inline-block;font-weight: bold;">Tanggal Produksi</span>
					</div>
					<div class="fitem">
						<span style="width:105px;display:inline-block;"></span>
						<input style="width:120px;height: 30px;" name="assy_line" id="assy_line" class="easyui-textbox" disabled=""/>
						<input style="width:120px;height: 30px;" name="cell_type" id="cell_type" class="easyui-textbox" disabled=""/>
						<input style="width:80px;height: 30px;" name="pallet" id="pallet" class="easyui-textbox" disabled=""/>
						<input style="width:120px;height: 30px;" name="tanggal_produksi" id="tanggal_produksi" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser"/>
					</div>
				</div>

				<div style="position:absolute;margin-left:600px;border-radius:4px;width: 400px;" class="style3">
					<div class="fitem">
						<span style="width:340px;display:inline-block;font-size: 16px;font-weight: bold;"><?=$id_kanban.' - '.$user_name?></span>
					</div>
					<div class="fitem">
						<span style="width:80px;display:inline-block;font-weight: bold;">QTY JALAN</span>
						<input style="width:100px;height: 25px;" name="qty_berjalan" id="qty_berjalan" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','" disabled=""/>
					</div>
					<div class="fitem">
						<span style="width:80px;display:inline-block;font-weight: bold;">QTY</span>
						<input style="width:100px;height: 25px;" name="qty_perpallet" id="qty_perpallet" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','" disabled=""/>
						<span style="width:30px;display:inline-block;font-weight: bold;">ACT</span>
						<input style="width:130px;height: 20px;border-radius: 5px; font-size: 16px;" name="qty_act_perpallet" id="qty_act_perpallet" 
							onkeypress="qty_act(event)"/>
					</div>
					<div class="fitem">
						<span style="width:80px;display:inline-block;font-weight: bold;">QTY BOX</span>
						<input style="width:100px;height: 25px;" name="qty_perbox" id="qty_perbox" class="easyui-numberbox" disabled="" data-options="min:0,precision:0,groupSeparator:','"/>
						<span style="width:30px;display:inline-block;font-weight: bold;">ACT</span>
						<input style="width:60px;height: 20px;border-radius: 5px;font-size: 16px;" name="qty_act_perbox" id="qty_act_perbox"
							onkeypress="qty_act_box(event)"/>
						<span style="display:inline-block;font-weight: bold;">@</span>
						<input style="width:50px;height: 25px;" name="qty_box" id="qty_box" class="easyui-numberbox" disabled="" data-options="min:0,precision:0,groupSeparator:','"/>
					</div>
				</div>

				<div style="height: 120px;" >
					<div style="margin-left:960px;">
					   	<div class="fitem">
						   <a href="javascript:void(0)" id="mulai" class="easyui-linkbutton c2" onclick="start()" style="width:110px;height: 50px;font-weight: bold;" disabled="">MULAI</a>
						   <input style="width:200px;;height: 40px;font-size: 33px;" name="date_mulai" id="date_mulai" class="easyui-textbox" disabled=""/>
						</div>
						<div class="fitem">
						   <a href="javascript:void(0)" id="akhir" class="easyui-linkbutton c2" onclick="finish()" style="width:110px;height: 50px;font-weight: bold;" disabled="">SELESAI</a>
						   <input style="width:200px;;height: 40px;" name="date_akhir" id="date_akhir" class="easyui-textbox" disabled=""/>
						</div>
					</div>
				</div>
			</div>
			
			<div class="fitem"></div>

			<table id="dg_ng" class="easyui-datagrid" toolbar="#toolbar_ng" style="width:100%;height:180px;"></table>
			<div id="toolbar_ng">
				<div class="fitem" style="padding: 5px;">
					<span style="width:80px; height:35px; display:inline-block;font-weight: bold;">Proses</span>
					<select style="width:200px; height:35px;" name="cmb_ng_proses" id="cmb_ng_proses" class="easyui-combobox" 
						data-options="url:'../../forms/json/json_ng_proses.php',method:'get',valueField:'ng_proses_id',textField:'ng_proses_name', panelHeight:'150px',
									  onSelect: function(rec){
									  	var link = '../../forms/json/json_ng_name.php?ng_pro='+rec.ng_proses_id;
									  	$('#cmb_ng').combobox('reload', link);
									  }
						"
					disabled="" required=""></select>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:80px; height:35px; display:inline-block;font-weight: bold;">Trouble</span>
					<select style="width:200px; height:35px;" name="cmb_ng" id="cmb_ng" class="easyui-combobox" 
						data-options="method:'get',valueField:'ng_id',textField:'ng_name', panelHeight:'150px'"
					disabled="" required=""></select>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:50px;display:inline-block;font-weight: bold;">Menit </span>
					<input style="width:100px;height:35px;" name="qty_ng" id="qty_ng" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','" disabled=""/>
					<span style="width:50px;display:inline-block;"></span>
					<a href="javascript:void(0)" id="input_ng" class="easyui-linkbutton c2" onclick="in_ng()" plain="true" style="width:100px; height:35px;font-weight: bold;" disabled=""> INPUT</a>
					<span style="width:120px;display:inline-block;"></span>
					<a href="javascript:void(0)" id="viewer" class="easyui-linkbutton c2" onclick="viewer()" plain="true" style="width:150px; height:35px;font-weight: bold;"> LIHAT HASIL LAIN</a>
				</div>
			</div>
		</div>
		<div id="tt">
	        <a href="javascript:void(0)" onclick="logOut()"> 
	        	<i class="fa fa-power-off fa-lg" aria-hidden="true"></i>
	        </a>
	    </div>

	    <div id='dlg' class="easyui-dialog" style="width:1100px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons" data-options="modal:true">
		    <table id="dg_view" class="easyui-datagrid" toolbar="#toolbar_view" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>

		<div id="toolbar_view" style="padding: 5px 5px;">
			<span style="width:80px;display:inline-block;">Assy LIne</span>
			<input style="width:100px;" name="cmb_Line" id="cmb_Line" class="easyui-combobox" 
				data-options="url:'../../forms/json/json_AsyLine.php', method:'get', valueField:'assy_line', textField:'assy_line', panelHeight:'160px'" required="" 
			/>
			<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_view_line()">SEARCH ITEM</a>
		</div>

		<div id="footer" style="padding:5px;" align="center">
	        <small><i>&copy; Copyright 2017, PT. FDK INDONESIA</i></small>
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

		function viewer(){
			var ln = $('#cmb_Line').combobox('getValue');
			$('#dlg').dialog('open').dialog('setTitle','VIEW RESULT OTHER LINE');
			$('#dg_view').datagrid('load',{line: ln});
			var dg = $('#dg_view').datagrid();
	        dg.datagrid('enableFilter');
			$('#dg_view').datagrid({
				fitColumns: true,
				columns:[[
					{field:'NAME',title:'USER',width:140,halign:'center'},
	                {field:'ASSY_LINE',title:'ASSEMBLY LINE',width:100,halign:'center', align:'center'},
	                {field:'CELL_TYPE',title:'CELL TYPE',width:100,halign:'center', align:'center'},
	                {field:'PALLET',title:'PALLET',width:50,halign:'center', align:'center'},
	                {field:'TANGGAL_PRODUKSI',title:'TANGGAL PRODUKSI',width:150,halign:'center', align:'center'},
	               	{field:'QTY_ACT_PERPALLET',title:'QTY/PALLET',width:100,halign:'center', align:'right'},
	               	{field:'QTY_ACT_PERBOX',title:'QTY/BOX',width:100,halign:'center', align:'right'},
	               	{field:'START_DATE',title:'MULAI',width:150,halign:'center', align:'center'},
	               	{field:'END_DATE',title:'AKHIR',width:150,halign:'center', align:'center'}
	            ]]
	        });
		}

		function search_view_line(){
			var ln = $('#cmb_Line').combobox('getValue');
			if(ln == ''){
				$.messager.alert('INFORMATION','FIELD TIDAK BOLEH KOSONG..!!','info');
			}else{
				$('#dg_view').datagrid('load',{line: ln});
				$('#dg_view').datagrid({url: 'result_line.php',});
				$('#cmb_Line').combobox('setValue','');
			}
		}

		$(function(){
			$('#assy_line').textbox('textbox').css('font-size','16px');
			$('#cell_type').textbox('textbox').css('font-size','16px');
			$('#pallet').textbox('textbox').css('font-size','16px');
			$('#tanggal_produksi').datebox('textbox').css('font-size','16px');
			$('#qty_berjalan').numberbox('textbox').css('font-size','16px');
			$('#qty_perpallet').numberbox('textbox').css('font-size','16px');
			//document.getElementById('qty_act_perpallet').css('font-size','16px');
			$('#qty_perbox').numberbox('textbox').css('font-size','16px');
			//document.getElementById('qty_act_perbox').css('font-size','16px');
			$('#qty_box').numberbox('textbox').css('font-size','16px');
			$('#date_akhir').textbox('textbox').css('font-size','18px');
			$('#cmb_ng_proses').combobox('textbox').css('font-size','18px');
			$('#cmb_ng').combobox('textbox').css('font-size','18px');
			$('#qty_ng').numberbox('textbox').css('font-size','18px');
			requestFullScreen();
		})

		function requestFullScreen() {
	      var el = document.body;

	      // Supports most browsers and their versions.
	      var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen 
	      || el.mozRequestFullScreen || el.msRequestFullScreen;

	      if (requestMethod) {

	        // Native full screen.
	        requestMethod.call(el);

	      } else if (typeof window.ActiveXObject !== "undefined") {

	        // Older IE.
	        var wscript = new ActiveXObject("WScript.Shell");

	        if (wscript !== null) {
	          wscript.SendKeys("{F11}");
	        }
	      }
	    }

		$('#dg_in').datagrid({
			url: 'get.php',
			fitColumns: true,
			singleSelect: true,
			rownumbers: true,
			columns:[[
				{field:'ID',title:'ID',width:100,halign:'center', align:'center'},
                {field:'ASSY_LINE',title:'ASSEMBLY LINE',width:100,halign:'center', align:'center'},
                {field:'CELL_TYPE',title:'CELL TYPE',width:100,halign:'center', align:'center'},
                {field:'PALLET',title:'PALLET',width:50,halign:'center', align:'center'},
                {field:'TANGGAL_PRODUKSI',title:'TANGGAL PRODUKSI',width:150,halign:'center', align:'center'},
               	{field:'QTY_ACT_PERPALLET',title:'QTY/PALLET',width:100,halign:'center', align:'right'},
               	{field:'QTY_ACT_PERBOX',title:'QTY/BOX',width:100,halign:'center', align:'right'},
               	{field:'START_DATE',title:'MULAI',width:150,halign:'center', align:'center'},
               	{field:'END_DATE',title:'AKHIR',width:150,halign:'center', align:'center'}
            ]]
        });

        $('#dg_ng').datagrid({
        	url: 'get_ng.php',
        	fitColumns: true,
        	rownumbers: true,
			columns:[[
                {field:'ASSY_LINE',title:'ASSEMBLY LINE',width:100,halign:'center', align:'center'},
                {field:'CELL_TYPE',title:'CELL TYPE',width:100,halign:'center', align:'center'},
                {field:'PALLET',title:'PALLET',width:50,halign:'center', align:'center'},
                {field:'TANGGAL_PRODUKSI',title:'TANGGAL PRODUKSI',width:150,halign:'center', align:'center'},
                {field:'NG_PROSES',title:'PROSES',width:150,halign:'center'},
                {field:'NG_NAME',title:'Trouble',width:200,halign:'center'},
                {field:'NG_QTY',title:'MENIT',width:100,halign:'center', align:'right'}
            ]]
        });

	    function scan(event){
	        if(event.keyCode == 13 || event.which == 13){
	            var sc = document.getElementById('scn').value;
	            //alert(sc);
	            var split = sc.split(",");
	            var date_prod = split[0].split("/");
	            var h = parseInt(date_prod[0]);
	            var b = parseInt(date_prod[1]);
	            var th = date_prod[2];

	            if(b<10){
	            	bl='0'+b;
	            }else{
	            	bl=b;
	            }

	            if(h<10){
	            	hr='0'+h;
	            }else{
	            	hr=h;
	            }

	            var tgl_p = th+'-'+bl+'-'+hr;
	            var asyln=split[1].replace('#','-');

				$.ajax({
					type: 'GET',
					url: '../../forms/json/json_cek_qty_kanban.php?assy_ln='+asyln+'&celtyp='+split[2]+'&plt='+split[5]+'&tgl_pro='+tgl_p,
					success: function(data){
						$('#qty_berjalan').numberbox('setValue',data[0].QTY);
						document.getElementById('qty_act_perpallet').value = split[4]-data[0].QTY;
					}
				});

	            $('#assy_line').textbox('setValue',split[1]);
				$('#cell_type').textbox('setValue',split[2]);
				$('#pallet').textbox('setValue',split[5]);
				$('#tanggal_produksi').datebox('setValue',tgl_p);
				$('#qty_perpallet').numberbox('setValue',split[4]);
				$('#qty_perbox').numberbox('setValue',split[3]);
				document.getElementById('qty_act_perbox').value = split[3];
				$('#qty_box').numberbox('setValue',split[6]);

				$('#mulai').linkbutton('enable');
				$('#akhir').linkbutton('enable');
				$('#cmb_ng_proses').combobox('enable');	
				$('#cmb_ng').combobox('enable');
				$('#qty_ng').numberbox('enable');
				$('#input_ng').linkbutton('enable');

				$('#qty_ng').numberbox('setValue',0);

				document.getElementById('scn').value='';
	        }
	    }

        function qty_act(event){
        	if(event.keyCode == 13 || event.which == 13){
        		var a = parseInt(document.getElementById('qty_act_perpallet').value);
        		var b = parseInt($('#qty_box').numberbox('getValue'));
        		var c = a/b;
        		document.getElementById('qty_act_perbox').value = Math.ceil(c);
        	}
        }

        function qty_act_box(event){
        	if(event.keyCode == 13 || event.which == 13){
        		var a = parseInt(document.getElementById('qty_act_perbox').value);
        		var b = parseInt($('#qty_box').numberbox('getValue'));
        		var c = a*b;
        		document.getElementById('qty_act_perpallet').value = Math.ceil(c);
        	}
        }

	    function save_mulai(){
    		$.post('save_start.php',{
				assy_line: $('#assy_line').textbox('getValue'),
				cell_type: $('#cell_type').textbox('getValue'),
				pallet: $('#pallet').textbox('getValue'),
				tgl_prod: $('#tanggal_produksi').datebox('getValue'),
				qty_perpallet: $('#qty_perpallet').numberbox('getValue'),
				qty_perbox: $('#qty_perbox').numberbox('getValue'),
				date_mulai: $('#date_mulai').textbox('getValue')
			}).done(function(res){
				//alert(res);
				console.log(res);
				//$('#dg_in').datagrid('reload');
			})
	    }

	    function waktu_awal(){
	    	var qty_j = $('#qty_berjalan').numberbox('getValue').replace(/,/g,'');
	    	var qty_p = $('#qty_perpallet').numberbox('getValue').replace(/,/g,'');
	    	if(qty_j!=qty_p){
	    		$.ajax({
					type: 'GET',
					url: '../../forms/json/json_date_now.php',
					data: { kode:'kode'},
					success: function(data){
						$('#date_mulai').textbox('textbox').css('font-size','18px');
						$('#date_mulai').textbox('setValue',data[0].kode);
						save_mulai();
					}
				});	
	    	}else{
	    		$.messager.alert('INFORMATION','PALLET INI SUDAH DI SCAN','info');
	    		clear();
	    	}
	    	
	    }

	    function start(){
	    	waktu_awal();
	    }

	    function save(){
	    	var dt_z = $('#date_akhir').textbox('getValue');
	    	if(dt_z==''){
	    		$.messager.alert('INFORMATION','waktu selesai masih kosong','info');
	    	}else{
	    		$.post('save.php',{
					assy_line: $('#assy_line').textbox('getValue'),
					cell_type: $('#cell_type').textbox('getValue'),
					pallet: $('#pallet').textbox('getValue'),
					tgl_prod: $('#tanggal_produksi').datebox('getValue'),
					qty_perpallet: $('#qty_perpallet').numberbox('getValue'),
					qty_act_perpallet: document.getElementById('qty_act_perpallet').value,
					qty_perbox: $('#qty_perbox').numberbox('getValue'),
					qty_act_perbox: document.getElementById('qty_act_perbox').value,
					date_mulai: $('#date_mulai').textbox('getValue'),
					date_akhir: $('#date_akhir').textbox('getValue'),
				}).done(function(res){
					//alert(res);
					console.log(res);
					$('#dg_in').datagrid('reload');
				})
	    	}
	    	//$.messager.alert('INFORMATION','Simpan Data Berhasil..!!','info');
	    }

	    function in_ng(){
	    	var ng_p = $('#cmb_ng_proses').combobox('getValue');
	    	var ng_n = $('#cmb_ng').combobox('getValue');
	    	var dt_m = $('#date_mulai').textbox('getValue');


	    	if(ng_p == '' && ng_n == ''){
	    		$.messager.alert('INFORMATION','FIELD TIDAK BOLEH KOSONG..!!','info');
	    	}else{
	    		if(dt_m == ''){
	    			$.messager.alert('INFORMATION','BELUM DIMULAI ..!!, KLIK TOMBOL MULAI' ,'info');
	    		}else{
		    		$.post('save_ng.php',{
			    		assy_line: $('#assy_line').textbox('getValue'),
						cell_type: $('#cell_type').textbox('getValue'),
						pallet: $('#pallet').textbox('getValue'),
						tgl_prod: $('#tanggal_produksi').datebox('getValue'),
						ng_proses: $('#cmb_ng_proses').combobox('getValue'),
						ng_name: $('#cmb_ng').combobox('getValue'),
						ng_qty: $('#qty_ng').numberbox('getValue')
			    	}).done(function(res){
						//alert(res);
						console.log(res);
						$('#dg_ng').datagrid('reload');
					});
				}
	    	}
	    }

	    function clear(){
	    	$('#assy_line').textbox('setValue','');
			$('#cell_type').textbox('setValue','');
			$('#pallet').textbox('setValue','');
			$('#tanggal_produksi').datebox('setValue','');
			$('#qty_perpallet').numberbox('setValue','');
			document.getElementById('qty_act_perpallet').value='';
			$('#qty_perbox').numberbox('setValue','');
			document.getElementById('qty_act_perbox').value='';
			$('#qty_berjalan').numberbox('setValue','');
			$('#mulai').linkbutton('disable');
			$('#akhir').linkbutton('disable');
			$('#date_mulai').textbox('setValue','');
			$('#date_akhir').textbox('setValue','');
			$('#cmb_ng_proses').combobox('setValue','');	
			$('#cmb_ng_proses').combobox('disable');
			$('#cmb_ng').combobox('setValue','');
			$('#cmb_ng').combobox('disable');
			$('#qty_ng').numberbox('setValue',0);
			$('#qty_ng').numberbox('disable');
	    }

	    function waktu_akhir(){
	    	$.ajax({
				type: 'GET',
				url: '../../forms/json/json_date_now.php',
				data: { kode:'kode'},
				success: function(data){
					$('#date_akhir').textbox('setValue',data[0].kode);
					save();
					clear();
					document.getElementById('scn').focus();
				}
			});
			
	    }

	    function finish(){
	    	var dt_a = $('#date_mulai').textbox('getValue');
	    	if (dt_a==''){
	    		$.messager.alert('INFORMATION','WAKTU MULAI TIDAK BOLEH KOSONG..!!','info');
	    	}else{
	    		waktu_akhir();
	    	}
	    }

	    function logOut(){
	    	$.messager.confirm('Confirm','Yakin Akan keluar dari System Kanban?',function(r){
				if(r){
					$.post('logout.php');
					self.location.href = '../index.php';
				}
			});
	    }

	</script>
    </body>
    </html>