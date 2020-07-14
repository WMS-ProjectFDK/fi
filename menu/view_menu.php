<?php include("../koneksi.php"); 
session_start(); //Gunakan variabel session pada halaman ini. Fungsi ini harus diletakkan pada bagian atas halaman.
$ses_id = $_SESSION['id_user'];
$sql_user="SELECT NIK FROM USER WHERE USER_ID='$ses_id'";
$query_user=mysql_query($sql_user);
$row_user=mysql_fetch_array($query_user);

$sql_log="SELECT NAMA_LENGKAP, DEPARTEMENT, JABATAN FROM MAN_POWER WHERE NIK='$row_user[NIK]'";
$query_log=mysql_query($sql_log);
$row_log=mysql_fetch_array($query_log);
$nm_login=$row_log[NAMA_LENGKAP];
$dept_login=$row_log[DEPARTEMENT];
$jab_log=$row_log[JABATAN];
if($jab_log=="1") $jab_login="Operator";
else if($jab_log=="2") $jab_login="Staff";
else if($jab_log=="3") $jab_login="Supervisor";
else if($jab_log=="4") $jab_login="Departement Head";
else if($jab_log=="5") $jab_login="Manager";
else if($jab_log=="6") $jab_login="Direktur";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>User Privillege</title>
<link rel="stylesheet" type="text/css" href="../grid/gt_grid.css" />

<style type="text/css">
	#commentForm { width: 100%; }
	#commentForm label { width: 240px; }
	#commentForm label.error, #commentForm button.error, #commentForm input.submit { margin-left: 5px; }
.style1 {color: #0000FF}
.style3 {
	font-size: 13px;
	color: #000000;
	font-weight: bold;
}
.style4 {
	font-size: 11px;
	color: #CC0000;
}
* { font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px; }
</style>
</head>
<script type="text/javascript">
 function confirmLogOut()
 {
	var is_confirmed;
	is_confirmed = window.confirm("End current session?");
	return is_confirmed;
 }
function setStatus(index,cek){
		var check=document.getElementById('cek'+index).value;
		var h_check=document.getElementById('hid_cek'+index).value;
		if(cek=="1"){
			document.getElementById('hid_cek'+index).value = 0;
			document.getElementById('cek'+index).value = 0;
		}else{
			document.getElementById('hid_cek'+index).value = 1;
			document.getElementById('cek'+index).value = 1;
		}
	}
</script>
<body>
<?php if($ses_id!=""){ ?>
<table width="100%">
		<tr>
			<td align="left" width="96%" valign="middle"><marquee><span class="style4"><?php echo "Welcome ".$nm_login.", you are login as ".$jab_login." ".$dept_login;?></span></marquee></td>
			<td align="right" width="4%" nowrap="nowrap" valign="middle"><a href="../index.php?act=logout" onClick="return confirmLogOut()" target="_top"><img src="../image/logout.gif" alt="End current session" width="22" height="20" border="0" /></a>&nbsp;<a href="../dashboard.php"><img src="../image/house.png" alt="Back to Home" width="22" height="20" border="0" /></a></td>
		</tr>
	</table>
<?php 
		$user_id=$_POST['user_id'];
		$user_name=$_POST['user_name'];			
?>
		<form id="commentForm" nama="data_priv" action="privillege.php" method="post">
		<fieldset>	
	<legend><span class="style3">View Menu</span></legend>
		<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#006633" bgcolor="#fff9f3">
			<tr bgcolor="#99CC66" background="../grid/skin/default/images/hd_row_bg.gif">
				<th>Kode Module</th>
				<th>Module</th>
				<th>Kode Sub Module</th>
				<th>Sub Module</th>
				<th>Kode Child Sub Module</th>
				<th>Child Sub Module</th>
			</tr>
	<?php 	$parent_awal=""; $sub_awal=""; $i=0;  $j=0; $parent="";
			$jml_total=0; $jml=0; $jml2=0; $jml3=0; $baris=0; $baris2=0;
			$sql="SELECT kode_menu, nama_menu, level_menu, parent_id, menu_id FROM master_menu WHERE parent_id='0' ORDER BY MENU_ID, PARENT_ID";
			$query=mysql_query($sql);
			$jml=mysql_num_rows($query);
			if($jml!=0){
				while($row=mysql_fetch_array($query)){
					$parent_awal=$row[menu_id];
					$parent[$jml_total]=$row[menu_id];
					$jml_total=$jml_total+1;
//					echo "<br>".$row[kode_menu]." --> ".$row[nama_menu]." --> ".$row[menu_id];
					$sql2="SELECT kode_menu, nama_menu, level_menu, parent_id, menu_id FROM master_menu WHERE parent_id='$row[menu_id]' ORDER BY MENU_ID, PARENT_ID";				
					$query2=mysql_query($sql2);
					$jml2=mysql_num_rows($query2);
					if($jml2!=0){ //jika punya sub maka d tambah sesuai sub yg d miliki, jika tidak hanya d tambah 1		
						$jml_total=$jml_total-1;
						while($row2=mysql_fetch_array($query2)){
							$sub_awal=$row2[menu_id];
							$anak[$jml_total]=$row2[menu_id];
							if($parent_awal!=$row[menu_id]){
								$parent[$jml_total+1]=$row[menu_id];
							}else{
								$parent[$jml_total+1]="";
							}
							$jml_total=$jml_total+1;
//							echo "<br>".$row2[kode_menu]." --> ".$row2[nama_menu]." --> ".$row2[menu_id];
							$sql3="SELECT kode_menu, nama_menu, level_menu, parent_id, menu_id FROM master_menu WHERE parent_id='$row2[menu_id]' ORDER BY MENU_ID, PARENT_ID";				
							$query3=mysql_query($sql3);
							$jml3=mysql_num_rows($query3);
							//$cucu[$i]=$jml3;
							$j=0;
							if($jml3!=0){
								$jml_total=$jml_total-1;
								while($row3=mysql_fetch_array($query3)){
//									echo "<br>".$row3[kode_menu]." --> ".$row3[nama_menu]." --> ".$row3[menu_id];
									$cucu[$jml_total]=$row3[menu_id];
									if($parent_awal!=$row[menu_id])
										$parent[$jml_total+1]=$row[menu_id];
									else
										$parent[$jml_total+1]="";
									
									if($sub_awal!=$row2[menu_id])
										$anak[$jml_total+1]=$row2[menu_id];
									else
										$anak[$jml_total+1]="";
									$jml_total=$jml_total+1;
									
								}//end while $row3
							}//end if jml3
						}//end while $row2
						
					}//end if($jml2!=0)
					$i++;
				}//end while $row
			}//end if($jml!=0)
			$h=0;
			while($h < $jml_total){ 
				$a=0; 
			?>
				<tr>
					<td><?php if($parent[$h]!=""){
							$sql_par="SELECT kode_menu, nama_menu from master_menu WHERE menu_id='$parent[$h]'";
							$query_par=mysql_query($sql_par);
							$row_par=mysql_fetch_array($query_par);
							$kode_par=$row_par[kode_menu];
							$nama_par=$row_par[nama_menu];
						}else{
							$kode_par="";
							$nama_par="";
						}
						echo $kode_par;
					?></td>
					<td><?php echo $nama_par;?></td>
					<td><?php if($anak[$h]!=""){
							$sql_anak="SELECT kode_menu, nama_menu from master_menu WHERE menu_id='$anak[$h]'";
							$query_anak=mysql_query($sql_anak);
							$row_anak=mysql_fetch_array($query_anak);
							$kode_anak=$row_anak[kode_menu];
							$nama_anak=$row_anak[nama_menu];
						}else{
							$kode_anak="";
							$nama_anak="";
						}
						echo $kode_anak;
					?></td>
					<td><?php echo $nama_anak; ?></td>
					<td><?php if($cucu[$h]!=""){
							$sql_cucu="SELECT kode_menu, nama_menu from master_menu WHERE menu_id='$cucu[$h]'";
							$query_cucu=mysql_query($sql_cucu);
							$row_cucu=mysql_fetch_array($query_cucu);
							$kode_cucu=$row_cucu[kode_menu];
							$nama_cucu=$row_cucu[nama_menu];
						}else{
							$kode_cucu="";
							$nama_cucu="";
						}
						echo $kode_cucu;
					?></td>
					<td><?php echo $nama_cucu;?></td>
					<?php if($cucu[$h]!=""){
							$priv_menu=$cucu[$h];
							$kode_menus=$kode_cucu;
						}else if($anak[$h]!=""){
							$priv_menu=$anak[$h];
							$kode_menus=$kode_anak;
						}else{
							$priv_menu=$parent[$h];
							$kode_menus=$kode_par; 
						} 
						$sql_cek="SELECT status_access FROM user_privillege WHERE user_id='$user_id' AND menu_id='$priv_menu'";
						$query_cek=mysql_query($sql_cek);
						$num_cek=mysql_num_rows($query_cek);
						if($num_cek!=0)
							$row_cek=mysql_fetch_array($query_cek);
					?>
				</tr>	
			
	<?php 	$h++; $a++;
			}
	?>					
		</table>	
		<br />
		<div align="center">
			<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>" />
			<input type="hidden" name="user_name" id="user_name" value="<?php echo $user_name;?>" />		
			<input type="hidden" name="jml_total" id="jml_total" value="<?php echo $h; ?>" />
			<input type="submit" name="save" id="save" value="Save" />
		</div>
		</fieldset>
	</form>	
	<?php }//end session ?>
</body>
</html>

