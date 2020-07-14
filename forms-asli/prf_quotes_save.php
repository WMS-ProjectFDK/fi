<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn2.php");
	
	$QUOT_NO  = htmlspecialchars($_REQUEST['QUOT_NO22']);
	$ITEM_NO  = htmlspecialchars($_REQUEST['ITEM_NO2']);
	$date_req = htmlspecialchars($_REQUEST['date_req']);

	$cek = "select count(*) from ZTB_PRF_QUOTATION_DETAIL_ITEM where quotation_no='$QUOT_NO' and item_no='$ITEM_NO'";
	$cekNya = oci_parse($connect, $cek);
  	oci_execute($cekNya);
  	$dt = oci_fetch_array($cekNya);

  	if(intval($dt[0])==0){
  		$now=date('Y-m-d');
		$user = $_SESSION['id_wms'];

		$nama_file_1 = $_FILES['ITEM_UPLOAD_1']['name'];
		if($nama_file_1==''){
			$file_1='-';
		}else{
			$file_1=$nama_file_1;
		}
		$ukuran_file_1 = $_FILES['ITEM_UPLOAD_1']['size'];
		$tipe_file_1 = $_FILES['ITEM_UPLOAD_1']['type'];
		$tmp_file_1 = $_FILES['ITEM_UPLOAD_1']['tmp_name'];

		$nama_file_2 = $_FILES['ITEM_UPLOAD_2']['name'];
		if($nama_file_2==''){
			$file_2='-';
		}else{
			$file_2=$nama_file_2;
		}
		$ukuran_file_2 = $_FILES['ITEM_UPLOAD_2']['size'];
		$tipe_file_2 = $_FILES['ITEM_UPLOAD_2']['type'];
		$tmp_file_2 = $_FILES['ITEM_UPLOAD_2']['tmp_name'];

		$nama_file_3 = $_FILES['ITEM_UPLOAD_3']['name'];
		if($nama_file_3==''){
			$file_3='-';
		}else{
			$file_3=$nama_file_3;
		}
		$ukuran_file_3 = $_FILES['ITEM_UPLOAD_3']['size'];
		$tipe_file_3 = $_FILES['ITEM_UPLOAD_3']['type'];
		$tmp_file_3 = $_FILES['ITEM_UPLOAD_3']['tmp_name'];

		$path_1 = "upload/".$nama_file_1;
		$path_2 = "upload/".$nama_file_2;
		$path_3 = "upload/".$nama_file_3;

		move_uploaded_file($tmp_file_1, $path_1);
		move_uploaded_file($tmp_file_2, $path_2);
		move_uploaded_file($tmp_file_3, $path_3);

		$query = "insert into ZTB_PRF_QUOTATION_DETAIL_ITEM (quotation_no, item_no, file_1, file_2, file_3, req_date)
			VALUES('$QUOT_NO', '$ITEM_NO', 'upload/".$file_1."', 'upload/".$file_2."', 'upload/".$file_3."', TO_DATE('$date_req','YYYY-MM-DD'))";
	    $sql = oci_parse($connect, $query);
	  	oci_execute($sql);

		if ($sql){
	        echo json_encode("Success");
	    }else{
	        echo json_encode(array('errorMsg'=>'Error'));
	    }
  	}else{
  		echo json_encode(array('errorMsg'=>'Error'));
  	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>