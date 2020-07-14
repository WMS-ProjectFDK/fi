<?php
$i_gr = htmlspecialchars($_REQUEST['ITEM_GRP']);
$i_st = htmlspecialchars($_REQUEST['ITEM_ASSET']);
$i_no = htmlspecialchars($_REQUEST['ITEM_NO']);
$i_nm = htmlspecialchars($_REQUEST['ITEM_NAME']);
$i_uo = htmlspecialchars($_REQUEST['ITEM_UOM']);
$i_sp = htmlspecialchars($_REQUEST['ITEM_SPEC']);
$i_cr = htmlspecialchars($_REQUEST['ITEM_CURR']);
$i_ty = htmlspecialchars($_REQUEST['ITEM_TYPE']);
$i_sf = htmlspecialchars($_REQUEST['ITEM_SAFETY']);

include("../connect/conn2.php");

$dt = date('Ymd');
$date = date('Y-m-d H:i:S');
$cde = $i_gr.'-0'.$i_st.'-';
$cek = "select coalesce(lpad(cast(cast(substr(max(item_no),7,5) as number)+1 as varchar2(5)),5,'0'),'00001') as code from item where substr(item_no, 1, 6)='$cde'";
$cekNya = oci_parse($connect, $cek);
oci_execute($cekNya);
$row = oci_fetch_array($cekNya);
$code = $cde.$row[0];
//echo $code;
$sql = "insert into item (item_no,description,description_org,class_code,uom_q,curr_code,section_code,item_type1,safety_stock,stock_subject_code)
	VALUES('$code','$i_nm','$i_sp','111000','$i_uo','$i_cr','100','ALKALINE',$i_sf,'$i_st')";
$result = oci_parse($connect, $sql);
oci_execute($result);
if ($result){
	$nama_file = $_FILES['ITEM_UPLOAD']['name'];
	$ukuran_file = $_FILES['ITEM_UPLOAD']['size'];
	$tipe_file = $_FILES['ITEM_UPLOAD']['type'];
	$tmp_file = $_FILES['ITEM_UPLOAD']['tmp_name'];
	// Set path folder tempat menyimpan gambarnya
	$path = "upload/".$nama_file;
	if($tipe_file == "image/jpeg" || $tipe_file == "image/png"){ // Cek apakah tipe file yang diupload adalah JPG / JPEG / PNG
	  if($ukuran_file <= 2000000){ // Cek apakah ukuran file yang diupload kurang dari sama dengan 2MB
	    if(move_uploaded_file($tmp_file, $path)){ // Cek apakah gambar berhasil diupload atau tidak
	      $query = "INSERT INTO ZTB_PRF_ITEM(item_no, name_image,size_image,type_image,item_group) VALUES('$code','".$nama_file."','".$ukuran_file."','".$tipe_file."','$i_gr')";
	      $sql = oci_parse($connect, $query);
		  oci_execute($sql);
	      
	      if($sql){ // Cek jika proses simpan ke database sukses atau tidak
	        echo json_encode(array('Success'=>'Item No. '.$code.' Data Saved.'));
	      }else{
	        echo json_encode(array('errorMsg'=>'Some errors occured.'));
	      }
	    }else{
	      echo json_encode(array('errorMsg'=>'Some errors occured.'));
	    }
	  }else{
	    echo json_encode(array('errorMsg'=>'Some errors occured.'));
	  }
	}else{
	  echo json_encode(array('errorMsg'=>'Some errors occured.'));
	}	
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>