<script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.patch.js"></script>
<script type="text/javascript" src="../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../js/jquery.edatagrid.js"></script>
<script language="javascript">
	function formattgl(tgl){
		
	    var hari = tgl.substring(0,2); // sl
	    var bulan = getMonthFromString(tgl.substring(3,6));
	    var tahun = tgl.substring(7,9);

	    if (bulan<10) {
	    	bulan = '0'+bulan;
	    }
	    
		return '20'+tahun+'-'+bulan+'-'+hari;
	};

	function getMonthFromString(mon){
   		return new Date(Date.parse(mon +" 1, 2012")).getMonth()+1
	}	

	alert (formattgl('28-AUG-18'));
</script> 
