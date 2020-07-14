<?php		
	include("../koneksi.php");
	$submit=$_POST['submityesno'];
	if($submit=="1"){ //input parent
		////$kode_par=$_POST['par_id'];
		$name=$_POST['par_name'];
		$desc=$_POST['par_desc'];	
		$url=$_POST['par_url'];	
		
		$cek_id="SELECT max(MENU_ID)+1 as menu_id FROM master_menu";
		$query_cek=mysql_query($cek_id);
		$row_cek=mysql_fetch_array($query_cek);
		if(empty($row_cek[menu_id])) $menu_id=1;
		else $menu_id= $row_cek[menu_id];
		
		$sudah=0;
		$cek="SELECT distinct KODE_MENU FROM MASTER_MENU";
		$query=mysql_query($cek);
		$jml=mysql_num_rows($query);
		$jml=$jml+1;
		if($jml<10) $j_code="00".$jml;
		else if($jml>9 && $jml<100) $j_code="0".$jml;
		else $j_code=$jml;
		$code="MNU".$j_code;
			
		while($sudah!=1){
			$sql_check="SELECT KODE_MENU FROM MASTER_MENU WHERE KODE_MENU='$code'";
			$query_check=mysql_query($sql_check);
			$jml_check=mysql_num_rows($query_check);
			$jml_check+1;
			if($jml_check!=0){
				$jml=$jml+1;
				if($jml<10) $j_code="00".$jml;
				else if($jml>9 && $jml<100) $j_code="0".$jml;
				else $j_code=$jml;
				$code="MNU".$j_code;
			}else if($jml_check==0){
				$sql_insert="INSERT INTO master_menu (MENU_ID, PARENT_ID, NAMA_MENU, DESC_MENU, LEVEL_MENU, KODE_MENU,URL) VALUES ('$menu_id','0','$name','$desc','1','$code','$url')";
//						echo "<br>".$sql_insert;
				$query_insert=mysql_query($sql_insert);
				if($query_insert){ 
					$sudah=1;	 ?>
					<script type="text/javascript">
						alert("Data parent menu sudah disimpan!");
						location.href="form_menu.php";
					</script>		
		<?php	}//end insert				
			}//end else jml_check==0
		}//end while sudah != 1			
	}else if ($submit=="2"){ //input sub module
		$kode_par=$_POST['parent_id'];
		//$kode_sub=$_POST['sub_id'];
		$sub_name=$_POST['sub_name'];
		$desc=$_POST['sub_desc'];
		$url=$_POST['sub_url'];
		
		$sql_parent="SELECT menu_id FROM master_menu WHERE kode_menu='$kode_par'";
		$query_parent=mysql_query($sql_parent);
		$row_parent=mysql_fetch_array($query_parent);
		$parent=$row_parent[menu_id];
		
		$cek_id="SELECT max(menu_id)+1 as menu_id FROM master_menu";
		$query_cek=mysql_query($cek_id);
		$row_cek=mysql_fetch_array($query_cek);
		if(empty($row_cek[menu_id])) $row_cek[menu_id]=0;
		else $menu_id= $row_cek[menu_id];
		
		$sudah=0;
		$cek="SELECT distinct KODE_MENU FROM MASTER_MENU";
		$query=mysql_query($cek);
		$jml=mysql_num_rows($query);
		$jml=$jml+1;
		if($jml<10) $j_code="00".$jml;
		else if($jml>9 && $jml<100) $j_code="0".$jml;
		else $j_code=$jml;
		$code="MNU".$j_code;
			
		while($sudah!=1){
			$sql_check="SELECT KODE_MENU FROM MASTER_MENU WHERE KODE_MENU='$code'";
			$query_check=mysql_query($sql_check);
			$jml_check=mysql_num_rows($query_check);
			$jml_check+1;
			if($jml_check!=0){
				$jml=$jml+1;
				if($jml<10) $j_code="00".$jml;
				else if($jml>9 && $jml<100) $j_code="0".$jml;
				else $j_code=$jml;
				$code="MNU".$j_code;
			}else if($jml_check==0){
				$sql_insert="INSERT INTO master_menu (menu_id, parent_id, nama_menu, desc_menu, level_menu, kode_menu,url) VALUES ('$menu_id','$parent','$sub_name','$desc','2','$code','$url')";
//						echo "<br>".$sql_insert;
				$query_insert=mysql_query($sql_insert);
				if($query_insert){ 
					$sudah=1;	 ?>
					<script type="text/javascript">
						alert("Data sub menu sudah disimpan!");
						location.href="form_menu.php";
					</script>		
		<?php	}//end insert				
			}//end else jml_check==0
		}//end while sudah != 1			
		
	}else if ($submit=="3"){ //input child sub module
		$id=$_POST['parent_id1'];
		$kode_sub2=$_POST['sub_id2'];
		//$kode_child=$_POST['child_sub_id'];
		$child_name=$_POST['child_sub_name'];
		$child_desc=$_POST['child_sub_desc'];
		$url=$_POST['child_sub_url'];
		//echo $id." -- ".$kode_sub2." -- ".$url;
		$sql_parent="SELECT menu_id FROM master_menu WHERE kode_menu='$kode_sub2'";
		$query_parent=mysql_query($sql_parent);
		$row_parent=mysql_fetch_array($query_parent);
		$parent=$row_parent[menu_id];
		//echo $sql_parent;
		
		$cek_id="SELECT max(menu_id)+1 as menu_id FROM master_menu";
		$query_cek=mysql_query($cek_id);
		$row_cek=mysql_fetch_array($query_cek);
		if(empty($row_cek[menu_id])) $row_cek[menu_id]=0;
		else $menu_id= $row_cek[menu_id];
		
		$sudah=0;
		$cek="SELECT distinct KODE_MENU FROM MASTER_MENU";
		$query=mysql_query($cek);
		$jml=mysql_num_rows($query);
		$jml=$jml+1;
		if($jml<10) $j_code="00".$jml;
		else if($jml>9 && $jml<100) $j_code="0".$jml;
		else $j_code=$jml;
		$code="MNU".$j_code;
			
		while($sudah!=1){
			$sql_check="SELECT KODE_MENU FROM MASTER_MENU WHERE KODE_MENU='$code'";
			$query_check=mysql_query($sql_check);
			$jml_check=mysql_num_rows($query_check);
			$jml_check+1;
			if($jml_check!=0){
				$jml=$jml+1;
				if($jml<10) $j_code="00".$jml;
				else if($jml>9 && $jml<100) $j_code="0".$jml;
				else $j_code=$jml;
				$code="MNU".$j_code;
			}else if($jml_check==0){
				$sql_insert="INSERT INTO master_menu (menu_id, parent_id, nama_menu, desc_menu, level_menu, kode_menu,url) VALUES ('$menu_id','$parent','$child_name','$child_desc','3','$code','$url')";
				//echo $sql_insert; 
				$query_insert=mysql_query($sql_insert);
				if($query_insert){ 
					$sudah=1;	 ?>
					<script type="text/javascript">
						alert("Data sub child menu sudah disimpan!");
						location.href="form_menu.php";
					</script>		
		<?php	}//end insert				
			}//end else jml_check==0
		}//end while sudah != 1					
	}//end if submit
	?>
