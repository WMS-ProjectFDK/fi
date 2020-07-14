<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SPAREPART ASSY MONITORING SYSTEM</title>
<link rel="icon" type="../../image/png" href="../../favicon.png">
<link rel="icon" type="../image/png" href="../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
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
<body class="easyui-layout">
		<div data-options="region:'north',title:'SPAREPART ASSY MONITORING SYSTEM', iconCls: 'icon-save', collapsible: false"></div>
        <div data-options="region:'west',collapsible: false" style="width:17%;" align="center">
       		<br>
       		<div class="fitem" style='padding: 3px;'>
       		 	<a href="javascript:void(0)" id="input_ng" class="easyui-linkbutton c6" onclick="spare_in()" plain="true" style="width:200px;height:30px;font-weight: bold;" > MASTER SPAREPART</a>
       		</div>
        	<div class="fitem" style='padding: 3px;'>
        		<a href="javascript:void(0)" id="input_ng" class="easyui-linkbutton c6" onclick="spare_history()" plain="true" style="width:200px;height:30px;font-weight: bold;" > REPLACEMEMT HISTORY</a>
        	</div>
        	<div class="fitem" style='padding: 3px;'>
        		<a href="javascript:void(0)" id="input_ng" class="easyui-linkbutton c5" onclick="javascript:window.close();" plain="true" style="width:200px;height:30px;font-weight: bold;" > BACK TO MENU KANBAN</a>
        	</div>
        </div>
        <div data-options="region:'center'" id="main" style="width:90%; padding: 5px 5px;">
            <!-- START INPUT SPAREPART -->
            <div id="spare_in" style="width: 100%; height: 100%; display: none;">
                <table id="dg_spare" class="easyui-datagrid" toolbar="#toolbar_spare" title="MASTER SPAREPART" style="width:auto;height:auto;border-radius: 10px;" rownumbers="true" singleSelect="true">
                </table>
                <div id="toolbar_spare" style="padding:5px 5px;">
                    <div class="fitem">
                        <span style="width:70px;display:inline-block;">LINE</span>
                        <input style="width:90px;" name="cmb_line" id="cmb_line" class="easyui-combobox" data-options=" url:'json/line.php', method:'get', valueField:'line', textField:'line', panelHeight:'75px',
                        onSelect:function(rec){
                            var l = rec.line;
                            var ln = l.replace('#','-');

                            $('#cmb_mach_add').combobox('setValue', '');
                            var link = 'json/mach.php?line='+ln;
                            $('#cmb_mach').combobox('reload', link);
                        }
                        " required="true">
                        <span style="width:100px;display:inline-block;"></span>
                    </div>
                    <div class="fitem">
                        <span style="width:70px;display:inline-block;">MACHINE</span>
                        <input style="width:180px;" class="easyui-combobox" id="cmb_mach" name="cmb_mach" data-options="valueField:'id_m', textField:'mach', panelHeight:'75px',
                        onSelect:function(rec){
                            $('#filter').linkbutton('enable');
                        }" required="true">
                    </div>
                    <div class="fitem">
                        <a href="javascript:void(0)" id="filter" style="width: 255px;" class="easyui-linkbutton c2" disabled="true" onclick="filterData_spare();"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
                        <a href="javascript:void(0)" id="add" style="width: 150px;" id="add" class="easyui-linkbutton c2" onclick="add_spare()"><i class="fa fa-plus" aria-hidden="true"></i> ADD SPAREPART</a>
                        <a href="javascript:void(0)" id="edit" style="width: 150px;" id="edit" class="easyui-linkbutton c2" disabled="true" onclick="edit_spare()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT SPAREPART</a>
                        <a href="javascript:void(0)" id="repl" style="width: 200px;" id="edit" class="easyui-linkbutton c2" disabled="true" onclick="replace_spare()"><i class="fa fa-pencil" aria-hidden="true"></i> INPUT REPLACEMENT DATE</a>
                    </div>
                </div>
            </div>
            <!-- END INPUT SPAREPART -->

            <!-- START DIALOG ADD -->
            <div id="dlg_add" class="easyui-dialog" style="width:500px;height:auto;padding:5px 5px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true, position: 'center'">
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">LINE</span>
                    <input style="width:90px;" name="cmb_line_add" id="cmb_line_add" class="easyui-combobox" data-options=" url:'json/line.php', method:'get', valueField:'line', textField:'line', panelHeight:'75px',
                    onSelect:function(rec){
                        var l = rec.line;
                        var ln = l.replace('#','-');

                        $('#cmb_mach_add').combobox('setValue', '');
                        var link = 'json/mach.php?line='+ln;
                        $('#cmb_mach_add').combobox('reload', link);
                    }
                    " required="true">
                    <span style="width:100px;display:inline-block;"></span>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">MACHINE</span>
                    <input style="width:150px;" class="easyui-combobox" id="cmb_mach_add" name="cmb_mach_add" data-options="valueField:'id_m', textField:'mach', panelHeight:'75px'" required="true">
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">PART NAME</span>
                    <input required="true"  data-options="multiline:true" style="width:300px;height:100px" name="nama_part_add" id="nama_part_add" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">ITEM No.</span>
                    <input required="true" style="width:300px;" name="part_no_add" id="part_no_add" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">DRAWING No.</span>
                    <input required="true" style="width:300px;" name="drawing_no_add" id="drawing_no_add" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">UNIT QTY</span>
                    <input required="true" style="width:150px;" name="unit_add" id="unit_add" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">LIFETIME (Pcs)</span>
                    <input required="true" style="width:150px;" name="lifetime_add" id="lifetime_add" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">LEED TIME (week)</span>
                    <input required="true" style="width:150px;" name="leadtime_add" id="leadtime_add" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">LAST REPLACEMENT</span>
                    <input style="width:150px;" name="date_replace_add" id="date_replace_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
                </div>
            </div>
            <div id="dlg-buttons-add">
                <a href="javascript:void(0)" id="save_add" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveAdd()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel_add" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
            </div>
            <!-- END DIALOG ADD -->

            <!-- START DIALOG EDIT -->
            <div id="dlg_edit" class="easyui-dialog" style="width:500px;height:auto;padding:5px 5px" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true, position: 'center'">
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">PART NAME</span>
                    <input required="true"  data-options="multiline:true" style="width:300px;height:100px" name="nama_part_edit" id="nama_part_edit" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">ITEM No.</span>
                    <input required="true" style="width:300px;" name="part_no_edit" id="part_no_edit" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">DRAWING No.</span>
                    <input required="true" style="width:300px;" name="drawing_no_edit" id="drawing_no_edit" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">UNIT QTY</span>
                    <input required="true" style="width:150px;" name="unit_edit" id="unit_edit" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">LIFETIME (Pcs)</span>
                    <input required="true" style="width:150px;" name="lifetime_edit" id="lifetime_edit" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">LEED TIME (week)</span>
                    <input required="true" style="width:150px;" name="leadtime_edit" id="leadtime_edit" class="easyui-textbox"/>
                </div>
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">LAST REPLACEMENT</span>
                    <input style="width:150px;" name="date_replace_edit" id="date_replace_edit" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
                </div>
            </div>
            <div id="dlg-buttons-edit">
                <a href="javascript:void(0)" id="save_edit" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveEdit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel_edit" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
            </div>
            <!-- END DIALOG EDIT -->

            <!-- START DIALOG REPLACEMENT DATE -->
            <div id="dlg_replace" class="easyui-dialog" style="width:500px;height:auto;padding:5px 5px" closed="true" buttons="#dlg-buttons-replace" data-options="modal:true, position: 'center'">
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">REPLACEMENT DATE</span>
                    <input style="width:150px;" name="replace_date" id="replace_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
                </div>
                <!-- <div class="fitem">
                    <span style="width:150px;display:inline-block;">ACTUAL PCS FROM DATE</span>
                    <input required="true" style="width:150px;" name="replace_act_pcs" id="replace_act_pcs" class="easyui-textbox"/>
                </div> -->
                <div class="fitem">
                    <span style="width:150px;display:inline-block;">PIC</span>
                    <input required="true" style="width:300px;" name="replace_pic" id="replace_pic" class="easyui-textbox"/>
                </div>
            </div>
            <div id="dlg-buttons-replace">
                <a href="javascript:void(0)" id="save_replace" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveReplace()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel_replace" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_replace').dialog('close')" style="width:90px">Cancel</a>
            </div>
            <!-- END DIALOG REPLACEMENT DATE -->

            <!-- START HISTORY SPAREPART -->
            <div id="spare_history" style="width: 100%; height: 100%;display:none">
                <table id="dg_spare_history" class="easyui-datagrid" toolbar="#toolbar_spare_history" title="HISTORY SPAREPART" style="width:auto;height:auto;border-radius: 10px;" rownumbers="true" singleSelect="true">
                </table>
                <div id="toolbar_spare_history" style="padding:5px 5px;">
                    <div class="fitem">
                        <span style="width:70px;display:inline-block;">LINE</span>
                        <input style="width:90px;" name="cmb_line_history" id="cmb_line_history" class="easyui-combobox" data-options=" url:'json/line.php', method:'get', valueField:'line', textField:'line', panelHeight:'75px',
                        onSelect:function(rec){
                            var l = rec.line;
                            var ln = l.replace('#','-');

                            $('#cmb_mach_history').combobox('setValue', '');
                            var link = 'json/mach.php?line='+ln;
                            $('#cmb_mach_history').combobox('reload', link);
                        }
                        " required="true">
                        <span style="width:100px;display:inline-block;"></span>
                    </div>
                    <div class="fitem">
                        <span style="width:70px;display:inline-block;">MACHINE</span>
                        <input style="width:180px;" class="easyui-combobox" id="cmb_mach_history" name="cmb_mach_history" data-options="valueField:'id_m', textField:'mach', panelHeight:'75px',
                        onSelect:function(rec){
                            $('#filter_history').linkbutton('enable');
                        }" required="true">
                    </div>
                    <div class="fitem">
                        <a href="javascript:void(0)" id="filter_history" style="width: 255px;" class="easyui-linkbutton c2" disabled="true" onclick="filterData_history();"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
                    </div>
                </div>
            </div>
            <!-- END HISTORY SPAREPART -->
        </div>
        <div data-options="region:'south', collapsible: false" style="height: 19px; background-color: #ED1C24; color: #FFFFFF;" align="center" valign='middle'>
        	<span><i>&copy; Copyright 2019, PT. FDK INDONESIA</i></span>
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

            function spare_in(){
                var a = document.getElementById("spare_in");
                var e = document.getElementById("spare_history");

                a.style.display = 'block';
                e.style.display = 'none';

                $('#dg_spare').datagrid( {
                    url: 'spare_get.php',
                    fitColumns: true,
                    columns:[[
                        {field:'machine',title:'MACHINE', width:80, halign:'center'},
                        {field:'line',title:'LINE',width:40,halign:'center', align:'center'},
                        {field:'nama_part',title:'PART',width:200,halign:'center'},
                        {field:'unit_qty',title:'UNIT<br>QTY',width:40,halign:'center', align:'right'},
                        {field:'tgl_ganti',title:'LAST<br>REPLACEMENT',width:70,halign:'center', align:'center'},
                        {field:'lifetime_r',title:'LIFE TIME (Pcs)',width:70,halign:'center', align:'right'},
                        {field:'lifetime_c',title:'CURENT<br>LIFE TIME (Pcs)',width:70,halign:'center', align:'right'},
                        {field:'estimation_replacment',title:'ESTIMATION<br>REPLACE DATE',width:70,halign:'center', align:'center'},
                        {field:'status',title:'STATUS',width:70,halign:'center',align:'center'}
                    ]]
                });
            }

            function add_spare(){
                $('#dlg_add').dialog('open').dialog('setTitle','ADD SPARE PART');
            }

            function simpan(a){
                var dataRows = [];

                if (a == 'add'){
                    if($('#cmb_line_add').combobox('getValue')==''){
                        $.messager.alert('Warning','Please select line assy','warning');
                    }else if($('#cmb_mach_add').combobox('getValue')==''){
                        $.messager.alert('Warning','Please select machine','warning');
                    }else if($('#nama_part_add').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select part name','warning');
                    }else if($('#part_no_add').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select part no.','warning');
                    }else if($('#drawing_no_add').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select drawing no.','warning');
                    }else if($('#unit_add').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select unit','warning');
                    }else if($('#lifetime_add').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select life time','warning');
                    }else if($('#leadtime_add').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select lead time','warning');
                    }else{
                        var l_add = $('#cmb_line_add').combobox('getValue');
                        var linAdd = l_add.replace('#','-');

                        dataRows.push({
                            add_stss: 'ADD',
                            add_idno: '-',
                            add_line: linAdd,
                            add_mach: $('#cmb_mach_add').combobox('getValue'),
                            add_part: $('#nama_part_add').textbox('getValue'),
                            add_pano: $('#part_no_add').textbox('getValue'),
                            add_draw: $('#drawing_no_add').textbox('getValue'),
                            add_unit: $('#unit_add').textbox('getValue'),
                            add_life: $('#lifetime_add').textbox('getValue'),
                            add_lead: $('#leadtime_add').textbox('getValue'),
                            add_repl: $('#date_replace_add').textbox('getValue')
                        });

                        var myJSON=JSON.stringify(dataRows);
                        var str_unescape=unescape(myJSON);

                        console.log(unescape(str_unescape));
                        $.post('spare_save.php',{
                            data: unescape(str_unescape)
                        }).done(function(res){
                            if(res == '"success"'){
                                $('#dlg_add').dialog('close');
                                $('#dg_spare').datagrid('reload');
                                $.messager.alert('INFORMATION','Insert Data Success..!!','info');
                                $.messager.progress('close');
                            }else{
                                console.log(res);
                                $.messager.alert('ERROR',res,'warning');
                                $.messager.progress('close');
                            }
                        });
                    }
                }else if(a=='edit'){
                    if($('#nama_part_edit').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select part name','warning');
                    }else if($('#part_no_edit').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select part no.','warning');
                    }else if($('#drawing_no_edit').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select drawing no.','warning');
                    }else if($('#unit_edit').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select unit','warning');
                    }else if($('#lifetime_edit').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select life time','warning');
                    }else if($('#leadtime_edit').textbox('getValue')==''){
                        $.messager.alert('Warning','Please select lead time','warning');
                    }else{
                        dataRows.push({
                            add_stss: 'EDIT',
                            add_idno: id_edit,
                            add_line: '-',
                            add_mach: '-',
                            add_part: $('#nama_part_edit').textbox('getValue'),
                            add_pano: $('#part_no_edit').textbox('getValue'),
                            add_draw: $('#drawing_no_edit').textbox('getValue'),
                            add_unit: $('#unit_edit').textbox('getValue'),
                            add_life: $('#lifetime_edit').textbox('getValue'),
                            add_lead: $('#leadtime_edit').textbox('getValue'),
                            add_repl: $('#date_replace_edit').textbox('getValue')
                        });

                        var myJSON=JSON.stringify(dataRows);
                        var str_unescape=unescape(myJSON);

                        console.log(unescape(str_unescape));
                        $.post('spare_save.php',{
                            data: unescape(str_unescape)
                        }).done(function(res){
                            if(res == '"success"'){
                                $('#dlg_edit').dialog('close');
                                $('#dg_spare').datagrid('reload');
                                $.messager.alert('INFORMATION','Update Data Success..!!','info');
                                $.messager.progress('close');
                            }else{
                                console.log(res);
                                $.messager.alert('ERROR',res,'warning');
                                $.messager.progress('close');
                            }
                        });
                    }
                }else if(a == 'replace'){
                    dataRows.push({
                        add_stss: 'REPLACE',
                        add_idno: id_replace,
                        add_line: '-',
                        add_mach: '-',
                        add_part: '-',
                        add_pano: '-',
                        add_draw: '-',
                        add_unit: '-',
                        add_life: $('#replace_pic').textbox('getValue'),
                        // add_lead: $('#replace_act_pcs').textbox('getValue'),
                        add_repl: $('#replace_date').textbox('getValue')
                    });

                    var myJSON=JSON.stringify(dataRows);
                    var str_unescape=unescape(myJSON);

                    console.log(unescape(str_unescape));
                    $.post('spare_save.php',{
                        data: unescape(str_unescape)
                    }).done(function(res){
                        if(res == '"success"'){
                            $('#dlg_replace').dialog('close');
                            $('#dg_spare').datagrid('reload');
                            $.messager.alert('INFORMATION','Update REPLACE DATE Success..!!','info');
                            $.messager.progress('close');
                        }else{
                            console.log(res);
                            $.messager.alert('ERROR',res,'warning');
                            $.messager.progress('close');
                        }
                    });
                } 
            }

            function saveAdd(){
                $.messager.progress({
                    title:'Please waiting',
                    msg:'Save data...'
                });

                //$('#save_add').linkbutton('disable');
                //$('#cancel_add').linkbutton('disable');
                simpan('add');
            }

            var id_edit=0;
            function edit_spare(){
                var row = $('#dg_spare').datagrid('getSelected');
                if (row){
                    id_edit = row.id_part;
                    $('#dlg_edit').dialog('open').dialog('setTitle','EDIT '+row.nama_part);
                    $('#nama_part_edit').textbox('setValue',row.nama_part);
                    $('#part_no_edit').textbox('setValue',row.item_no);
                    $('#drawing_no_edit').textbox('setValue',row.drawing_no);
                    $('#unit_edit').textbox('setValue',row.unit_qty);
                    $('#lifetime_edit').textbox('setValue',row.lifetime);
                    $('#leadtime_edit').textbox('setValue',row.leadtime_week);
                    $('#date_replace_edit').textbox('setValue',row.tgl_ganti);
                }    
            }

            function saveEdit(){
                $.messager.progress({
                    title:'Please waiting',
                    msg:'Save data...'
                });

                //$('#save_edit').linkbutton('disable');
                //$('#cancel_edit').linkbutton('disable');
                simpan('edit');
            }

            var id_replace=0;
            function replace_spare(){
                var row = $('#dg_spare').datagrid('getSelected');
                if (row){
                    id_replace = row.id_part;
                    $('#dlg_replace').dialog('open').dialog('setTitle','REPLACEMENT DATE '+row.nama_part);
                }
            }

            function saveReplace(){
                $.messager.progress({
                    title:'Please waiting',
                    msg:'Save data...'
                });

                //$('#save_edit').linkbutton('disable');
                //$('#cancel_edit').linkbutton('disable');
                simpan('replace');   
            }

            function filterData_spare(){
                var l = $('#cmb_line').combobox('getValue');
                var lin = l.replace('#','-');
                var mch = $('#cmb_mach').combobox('getValue');

                if (lin == '' || mch == ''){
                    $.messager.alert('WARNING','LINE ASSY DAN MACHINE TIDAK BOLEH KOSONG','warning');
                }else{
                    $('#dg_spare').datagrid('load', {
                        line: lin,
                        machine: mch
                    });

                    url = "?line="+lin+"&machine="+mch;
                    console.log(url);
                    $('#dg_spare').datagrid('enableFilter');
                
                    $('#edit').linkbutton('enable');
                    $('#repl').linkbutton('enable');

                    //$('#cmb_line').combobox('setValue', '');
                    //$('#cmb_mach').combobox('setValue', '');
                    $('#filter').linkbutton('disable');
                }
            }

            /*----------------------------------------------- HISTORY -------------------------------------------*/
            function spare_history(){
                var a = document.getElementById("spare_in");
                var e = document.getElementById("spare_history");

                a.style.display = 'none';
                e.style.display = 'block';

                $('#dg_spare_history').datagrid( {
                    url: 'spare_get_history.php',
                    fitColumns: true,
                    columns:[[
                        {field:'machine',title:'MACHINE', width:80, halign:'center'},
                        {field:'line',title:'LINE',width:40,halign:'center', align:'center'},
                        {field:'nama_part',title:'PART',width:200,halign:'center'},
                        {field:'tgl_ganti',title:'LAST<br>REPLACEMENT',width:70,halign:'center', align:'center'},
                        {field:'lifetime',title:'LIFE TIME (Pcs)',width:70,halign:'center', align:'right'},
                        {field:'aktual_pc',title:'ACTUAL PCS',width:70,halign:'center', align:'right'},
                        {field:'pic',title:'PIC',width:120,halign:'center'}
                    ]]
                });
            }

            function filterData_history(){
                var lh = $('#cmb_line_history').combobox('getValue');
                var linh = lh.replace('#','-');
                var mchh = $('#cmb_mach_history').combobox('getValue');

                if (linh == '' || mchh == ''){
                    $.messager.alert('WARNING','LINE ASSY DAN MACHINE TIDAK BOLEH KOSONG','warning');
                }else{
                    $('#dg_spare_history').datagrid('load', {
                        line: linh,
                        machine: mchh
                    });

                    url = "?line="+linh+"&machine="+mchh;
                    $('#dg_spare_history').datagrid('enableFilter');

                    //$('#cmb_line_history').combobox('setValue', '');
                    //$('#cmb_mach_history').combobox('setValue', '');
                    $('#filter_history').linkbutton('disable');
                }
            }
        </script>
	</body>
</html>