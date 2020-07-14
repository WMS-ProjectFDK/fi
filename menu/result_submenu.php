<?php
include("../koneksi.php");
$q=$_GET["q"];
$parent=$_GET["parent"];
$id="sub_id2";
$name="sub_name1";
$h_name="h_".$name;
$h_id="h_".$id;

?>
<HTML>
<HEAD>
<TITLE>Item List</TITLE>
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
<TABLE BORDER="1" align="center" CELLPADDING="0" CELLSPACING="0" bordercolor="#006633" bgcolor="#fff9f3">
<TR bgcolor="#99CC66">
	<td align="center" nowrap="nowrap"><span class="style3 style1">Kode Item</span></td>
	<td align="center" nowrap="nowrap"><span class="style3 style1">Nama Item</span></td>
	<td align="center" nowrap="nowrap"><span class="style3">&nbsp;</span></td>
</TR>
<?  //SELECT DISTINCT * FROM MASTER_ALL_ITEM WHERE lower(NAMA_ITEM) like '$tmp_kata_proc' 
	$kata_proc=strtolower($q);
	$tmp_kata_proc="%".$kata_proc."%";
	$query = "SELECT DISTINCT kode_menu, nama_menu, parent_id FROM MASTER_MENU WHERE level_menu='2' AND parent_id='$parent' AND nama_menu like '$tmp_kata_proc' ORDER BY NAMA_MENU ASC";
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
      <TD nowrap="nowrap"><span class="style3 style1"><? echo $row[nama_menu]; ?></span></TD>
	  <td nowrap="nowrap"><span class="style3 style1"><input name="choose" type="button" value="Choose" onClick="javascript:pick('<?php echo $row[kode_menu]; ?>','<?php  echo $id; ?>','<?php echo $h_id; ?>','<?php echo $name; ?>','<?php echo $h_name; ?>','<?php echo $row[nama_menu]; ?>')"></span></td>
	</tr>
<?	
			$x++;
			$no++;
		}//while ($row=mysql_fetch_row($result));	
?>
</TABLE>
</body>
</HTML>
