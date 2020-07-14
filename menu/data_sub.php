<? include("../koneksi.php");
$parent=$_GET['parent'];
$id=$_GET['id'];
$name=$_GET['name'];
$h_name="h_".$name;
$h_id="h_".$id;
$act = $_GET['act'];
$clear="";

$sql_par="SELECT menu_id FROM master_menu WHERE kode_menu='$parent'";
$query_par=mysql_query($sql_par);
$row_par=mysql_fetch_array($query_par);
$id_par=$row_par[menu_id];
?>

<HTML>
<HEAD>
<TITLE>Sub Module List</TITLE>
<SCRIPT LANGUAGE="JavaScript">
function pick(symbol,id,h_id,name,h_name,nama) {
  if (window.opener && !window.opener.closed){
		window.opener.document.getElementById(id).value = symbol;
		window.opener.document.getElementById(h_id).value = symbol;
		window.opener.document.getElementById(name).value = nama;
		window.opener.document.getElementById(h_name).value = nama;
		window.close();
	}
}
</SCRIPT>
<script type="text/javascript">
var xmlHttp
function showResult(str)
{ 
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}
	var url="result_submenu.php"
	var parent=document.getElementById('par_id').value; 
	url=url+"?q="+str+"&parent="+parent
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 	{ 
 		document.getElementById("livesearch").innerHTML=xmlHttp.responseText
		document.getElementById("livesearch").style.border="1px solid #A5ACB2";
		document.getElementById("alldata").innerHTML="";
 	} 
}
function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
	 {
	 // Firefox, Opera 8.0+, Safari
	 xmlHttp=new XMLHttpRequest();
	 }
	 catch (e)
 	{
		//Internet Explorer
		try
		{
  			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  		}
 		catch (e)
  		{
  			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
 	}
	return xmlHttp;
}

</script>

<style type="text/css">
#livesearch
  {
  margin:0px;
  width:194px;
  }
#txt1
  {
  margin:0px;
  }
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style3 {font-size: 12px}
.style4 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
}
</style>
</head>
<body>

<form>
  <input type="hidden" name="par_id" id="par_id" value="<?php echo $id_par; ?>">
  <span class="style1">Nama Item:</span> 
  <input type="text" id="txt1" size="25" onKeyUp="showResult(this.value)" />
<br><br>
<div id="livesearch"></div>
</form>
<div id="alldata">
<!--<h4 align="left" class="style4">All Data Item</h4>
--><TABLE BORDER="1" align="left" CELLPADDING="0" CELLSPACING="0" bordercolor="#006633" bgcolor="#fff9f3">
<TR bgcolor="#99CC66">
    <td align="center" nowrap="nowrap"><span class="style3 style1">Parent Module Name</span></td>
	<td align="center" nowrap="nowrap"><span class="style3 style1">Sub Module Name</span></td>
	<td align="center" nowrap="nowrap"><span class="style3">&nbsp;</span></td>
</TR>
<?
	$query = "SELECT DISTINCT kode_menu, nama_menu, parent_id FROM MASTER_MENU WHERE level_menu='2' AND parent_id='$id_par' ORDER BY NAMA_MENU ASC";
	$res = mysql_query($query);
	$numrows_jml = mysql_num_rows($res);
	$x=0;
	$no = 1;
	while ($row=mysql_fetch_array($res)){
		$sql_nm = "SELECT kode_menu, nama_menu FROM master_menu where menu_id='$row[parent_id]'";
		$query_nm=mysql_query($sql_nm);
		while($row_nm=mysql_fetch_array($query_nm)){
			$kode_par=$row_nm[kode_menu];
			$nama_par=$row_nm[nama_menu];
		}
	?>
	<tr>
	  <td nowrap="nowrap"><span class="style3 style1"><? echo $nama_par; ?></span></td>
      <td nowrap="nowrap"><span class="style3 style1"><? echo $row[nama_menu]; ?></span></td>
	  <td nowrap="nowrap"><span class="style3 style1"><input name="choose" type="button" value="Choose" onClick="javascript:pick('<?php echo $row[kode_menu]; ?>','<?php  echo $id; ?>','<?php echo $h_id; ?>','<?php echo $name; ?>','<?php echo $h_name; ?>','<?php echo $row[nama_menu]; ?>')"></span></td>
	</tr>
<?	
			$x++;
			$no++;
		}//while ($row=mysql_fetch_row($result));	
	//}//end if($row)
?>
</TABLE>
</div>
<br>&nbsp;
</body>
</html> 
