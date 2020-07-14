<?php include("../../connect/conn.php"); ?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Heating Kanban System</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
  	<link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
    <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
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
		<div id="p" class="easyui-panel" title="HEATING KANBAN SYSTEM" data-options="iconCls:'icon-save', tools:'#tt', footer:'#footer'" style="width:100%;height:640px;padding:5px;">
			<!-- <fieldset style="float:left;width:150px; height: auto;border-radius:4px;"><legend><span class="style3"><strong>IN-HEATING</strong></span></legend>
				<div align="center">
					<i class="fa fa-sign-in fa-5x"></i>
				</div>
				<div class="fitem">
					<div align="center" style="font-weight: bold;font-size: 20px;color: blue;"><span id='in_h'></span></div>
				</div>
			</fieldset> -->
			<div style="position:absolute;margin-left:250px;border-radius:4px;width: 850px;">
				<div class="fitem" style="padding: 5px;">
					<span style="width:300px;display:inline-block;font-size: 18px;font-weight: bold;">SCAN KANBAN ONE WAY<br/>(Kecil)</span> 
					<input style="width:450px; height: 41px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
						onkeypress="scan_assy(event)" name="scn_assy" id="scn_assy" type="text" 
						autofocus="autofocus" />
				</div>
				<div class="fitem" style="padding: 5px;">
					<span style="width:300px;display:inline-block;font-size: 18px;font-weight: bold;">SCAN KANBAN CIRCULATE<br/>(Besar)</span> 
					<input style="width:450px; height: 41px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
						onkeypress="scan_pallet(event)" name="scn_pallet" id="scn_pallet" type="text"/>

				</div>
			<!-- 	<div class="fitem" style="padding: 5px;">
					<span style="width:300px;display:inline-block;font-size: 25px;font-weight: bold;"></span> 
					<a  id="savebtn_Plan" class="easyui-linkbutton c2" onClick="proses()" style="width:450px; height: 41px;"><i class="fa fa-save" aria-hidden="true"></i> Process</a>
						<span style="width:300px;display:inline-block;font-size: 25px;font-weight: bold;"></span> 
					
				</div>	 -->

			</div>

			<!-- <fieldset style="position: absolute; margin-left: 1100px; width:150px;border-radius:4px;"><legend><span><strong>OUT-HEATING</strong></span></legend>
				<div align="center">
					<i class="fa fa-sign-out fa-5x"></i>
				</div>
				<div class="fitem">
					<div align="center" style="font-weight: bold;font-size: 20px; color: blue;">
						<span id="out_h"></span>
					</div>
				</div>
			</fieldset> -->

			<div style="margin-top: 170px;" align="center">
				<div style="float:left;width:550px;">
					<table id="dg_add" title="Data Input" class="easyui-datagrid" style="width:650px;height:330px;border-radius:4px;"></table>
				</div>
				<div align="center">
				<!-- <div style="position: absolute;margin-left: 663px;"> -->
					<table id="dg_details" title="Top 7 Transaction" class="easyui-datagrid" style="width:650px;height:330px;border-radius:4px;"></table>
				</div>


			</div>
			<div style="margin-top: 10px;" align="left">
				<a  id="savebtn_Plan" class="easyui-linkbutton c2" onClick="showdata()" style="width:450px; height: 41px;"><i class="fa fa-save" aria-hidden="true"></i> save</a>
			</div>
			<div id="footer" style="padding:5px;" align="center">
		        <small><i>&copy; Copyright 2018, PT. FDK INDONESIA</i></small>
		    </div>

		    <!-- <div id="dlg_age" class="easyui-dialog" style="width: 150px;height: 200px;" closed="true" data-options="modal:true">
				<div class="fitem">
					<INPUT TYPE='radio' NAME='sheet_type' id='0' VALUE='0' checked/> 0<br/>
					<INPUT TYPE='radio' NAME='sheet_type' id='1' VALUE='1'/> 1<br/>
					<INPUT TYPE='radio' NAME='sheet_type' id='2' VALUE='2'/> 2<br/>
					<INPUT TYPE='radio' NAME='sheet_type' id='7' VALUE='7'/> 7<br/>
					<INPUT TYPE='radio' NAME='sheet_type' id='14' VALUE='14'/> 14<br/>
					<INPUT TYPE='radio' NAME='sheet_type' id='30' VALUE='30'/> 30<br/>
				</div>
				<div class="fitem" align="center">
					<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="printInv" onclick="save_age()"><i class="fa fa-print" aria-hidden="true"></i> save</a>
				</div>
			</div> -->
		</div>

		<script type="text/javascript">
			var sc1 = '';		var sc2 = '';		//var age = '';
			var ins = '';		var outs= '';

			/*function disableTest(){
				document.getElementById("ico_out").disabled = true;
			}*/

			function table_proses(){
				

				$('#dg_details').datagrid('loaded');
				$('#dg_details').datagrid( {
					url: 'heat_details_get.php'
				});
			}
			

		

			$('#dg_details').datagrid( {
				url: 'heat_details_get.php',
				fitColumns: true,
				rownumbers: true,
			    columns:[[
			    	{field:'ID_PRINT',title:'ID PRINT',width:80,halign:'center'},
			    	{field:'ID_PLAN',title:'ID PLAN',width:130,halign:'center'},
	                {field:'ASSY_LINE',title:'ASSEMBLY<br>LINE',width:80,halign:'center'},
	                {field:'CELL_TYPE',title:'CELL TYPE',width:80,halign:'center'},
	                {field:'REMARK', title:'STATUS',width:120,halign:'center'},
	                {field:'UPTO_DATE',title:'DATE TIME',width:120,halign:'center'}
				]]
			});

			$('#dg_add').datagrid( {
				
				fitColumns: true,
				rownumbers: true,
			    columns:[[
			    	{field:'ID_PRINT',title:'ID PRINT',width:80,halign:'center'},
			    	{field:'ID_PLAN',title:'TIPE-AGING',width:130,halign:'center'},
	                {field:'STATUS',title:'STATUS',width:80,halign:'center'}
				]]
			});

		function showdata(){
			var t = $('#dg_add').datagrid('getRows');
			var total = t.length;
			var rows = [];	
			for(i=0;i<total;i++){
				rows.push({
			      ID_PRINT:$('#dg_add').datagrid('getData').rows[i].ID_PRINT,
			      ID_PLAN:$('#dg_add').datagrid('getData').rows[i].ID_PLAN,
				  STATUS:$('#dg_add').datagrid('getData').rows[i].STATUS,	
			    });
			}
			var jsonString = JSON.stringify(rows);
			
			var str_esc=unescape(jsonString);
			
			$.post('save_heating.php',{
					data: unescape(str_esc)
				}).done(function(res){
					alert(res);
				});
			setTimeout(function () { window.location.reload(1); }, 2000);
			document.getElementById('scn_assy').value = '';
			document.getElementById('scn_pallet').value = '';
			
		};
			
			/*function save_age(){
				if(document.getElementById('0').checked == true){
					n_age = document.getElementById('0').value;
				}else if(document.getElementById('1').checked == true){
					n_age = document.getElementById('1').value;
				}else if(document.getElementById('2').checked == true){
					n_age = document.getElementById('2').value;
				}else if(document.getElementById('7').checked == true){
					n_age = document.getElementById('7').value;
				}else if(document.getElementById('14').checked == true){
					n_age = document.getElementById('14').value;
				}else if(document.getElementById('30').checked == true){
					n_age = document.getElementById('30').value;
				}

				age = n_age;
				$('#dlg_age').dialog('close');
				document.getElementById('scn_pallet').focus();
			}*/

			function scan_assy(event){
				var src1 = document.getElementById('scn_assy').value;
				var search1 = src1.toUpperCase();
				document.getElementById('scn_assy').value = search1;

		        if(event.keyCode == 13 || event.which == 13){
		            sc1 = document.getElementById('scn_assy').value;
		            //$('#dlg_age').dialog('open').dialog('setTitle','SET AGING VALUE');
		            document.getElementById('scn_pallet').focus();
		        }
		    }

		    function scan_pallet(event){
		    	if(event.keyCode == 13 || event.which == 13){
		    		var src2 = document.getElementById('scn_pallet').value;
					var search2 = src2.toUpperCase();
					document.getElementById('scn_pallet').value = search2;
		    		sc2 = document.getElementById('scn_pallet').value;

		    		proses();
		    		
		    	}
		    }


		    function insertdg(x,y,z){
		    	var idxfield=0;	


		    	$('#dg_add').datagrid('insertRow',{
						index: idxfield,
						row: {
								ID_PRINT: x,
								ID_PLAN: y,
								STATUS: z
							 }
						});


		    }

		 //    function cek_jumlah(){
			// 	$.ajax({
			// 		type: 'GET',
			// 		url: 'cek_inout_heat.php',
			// 		data: { kode:'kode' },
			// 		success: function(data){
			// 			document.getElementById("in_h").innerHTML = data[0].in;
			// 			document.getElementById("out_h").innerHTML = data[0].out;
			// 		}
			// 	});
			// }

			//var myVar = setInterval(function(){cek_jumlah()},1000);
			//var myData = setInterval(function(){table_proses()},1000);

		    function proses(){
		    	var split1 = sc1.split(",");
		    	var split2 = sc2.split(",");

		    	
		    	if (sc2 == ''){
		    		$.messager.alert('INFORMATION','SCAN KANBAN CIRCULATE TIDAK BOLEH KOSONG','info');
		    	}else{
		    		//CEK STATUS
			    	$.ajax({
						type: 'GET',
						url: 'cek_status.php?id_pallet='+split2[0]+'&id_print='+split1[8],
						success: function(data){
							if(data[0].kode=='IN'){
								insertdg(split1[8],split2[0],'IN')
								// in_heating();
								// table_proses();

							}else if(data[0].kode=='OUT'){
								insertdg(split1[8],split2[0],'OUT')
								// out_heating();
								// table_proses();
							}else if(data[0].kode=='EXISTS'){
								$.messager.alert('INFORMATION','PALLET SUDAH TIDAK ADA DI HEATING ROOM','info');
								// setTimeout(function () { window.location.reload(1); }, 2000);
							}else if(data[0].kode=='ASSEMBLY'){
								$.messager.alert('INFORMATION','PROSES ASSEMBLING BELUM SELESAI','info');
								// setTimeout(function () { window.location.reload(1); }, 2000);
							}
						}
					});
					
					document.getElementById('scn_assy').value = '';
			    	document.getElementById('scn_pallet').value = '';
		    		document.getElementById('scn_assy').focus();
		    	}
		    }

		  //   function in_heating(){
		  //   	var split1 = sc1.split(",");
		  //   	var split2 = sc2.split(",");

		  //   	$.post('save_heating.php',{
		  //   		id_prin: split1[8],
				// 	id_plan: split1[7],
				// 	id_pall: split2[0],
				// 	remarks: 'IN'/*,
				// 	aging : age*/
		  //   	},function(result){
				// 	if(result.successMsg == 'IN-HEATING'){
				// 		$.messager.alert('INFORMATION','IN-HEATING','info');
				// 	}else{
	   //                  $.messager.show({title: 'Error',msg: result.errorMsg});
	   //              }
	   //              setTimeout(function () { window.location.reload(1); }, 2000);
				// },'json');
	   //  		document.getElementById('scn_assy').focus();
		  //   }

		  //   function out_heating(){
		  //   	var split1 = sc1.split(",");
		  //   	var split2 = sc2.split(",");

		  //   	$.post('save_heating.php',{
		  //   		id_prin: split1[8],
				// 	id_plan: split1[7],
				// 	id_pall: split2[0],
				// 	remarks: 'OUT'/*,
				// 	aging : age*/
		  //   	},function(result){
				// 	if(result.successMsg == 'OUT-HEATING'){
				// 		$.messager.alert('INFORMATION','OUT-HEATING','info');
				// 	}else{
	   //                  $.messager.show({title: 'Error',msg: result.errorMsg});
	   //              }
	   //              setTimeout(function () { window.location.reload(1); }, 2000);
				// },'json');
	   //  		document.getElementById('scn_assy').focus();
		  //   }
		</script>
 	</body>
	</html>