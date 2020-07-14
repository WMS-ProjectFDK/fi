<?php include("../koneksi.php"); ?>
<html>
<head>
 <title>Tree</title>
 <style>
  ul{
  	list-style-type:none; /* setiap list dihilangkan penanda setiap list-nya */
  	padding-left: 12px;
  	margin-left: 12px;
  }
  a:link, a:visited{
  	text-decoration: none;
  	font-size: 12px;
  	color: #006;
  }
  a:hover, a:active{
  	text-decoration: underline;
  }
 </style>
 <style type="text/css">
	* { font: 11px/20px Verdana, sans-serif; }
	h4 { font-size: 18px; }
	input { padding: 3px; border: 1px solid #999; }
	input.error, select.error { border: 1px solid red; }
	label.error { color:red; margin-left: 10px; }
	td { padding: 5px; }
 </style>

 <script language="javascript" src="jquery.js"></script> 
 <script language="javascript">
  function openTree(id){
  	// ambil semua tag <ul> yang mengandung attribut parent = id dari link yang dipilih
  	var elm = $('ul[@parent='+id+']'); 
  	if(elm != undefined){ // jika element ditemukan
  	  if(elm.css('display') == 'none'){ // jika element dalam keadaan tidak ditampilkan
  	    elm.show(); // tampilkan element 	  	
  	    $('#img'+id).attr('src','../image/folderopen.jpg'); // ubah gambar menjadi gambar folder sedang terbuka
  	  }else{
  	  	elm.hide(); // sembunyikan element
  	    $('#img'+id).attr('src','../image/folderclose2.jpg'); // ubah gambar menjadi gambar folder sedang tertutup
  	  }
	}
  }
 </script> 
</head>
<body>
 <?php
    /* fungsi ini akan terus di looping secara rekursif agar dapat menampilkan menu dengan format tree (pohon)
     * dengan kedalaman jenjang yang tidak terbatas */
 	function loop($data,$parent){
//		echo "<br> masuk loop";
//		echo "<br> data parent".$parent;
//		echo "<br> data araay : ".$data[0];
 	  if(isset($data[$parent])){ // jika ada anak dari menu maka tampilkan
 	    /* setiap menu ditampilkan dengan tag <ul> dan apabila nilai $parent bukan 0 maka sembunyikan element 
 	     * karena bukan merupakan menu utama melainkan sub menu */
// 	  	echo "<br> masuk if loop";
		$str = '<ul parent="'.$parent.'" style="display:'.($parent>0?'none':'').'">'; 
 	  	foreach($data[$parent] as $value){
 	  	  /* variable $child akan bernilai sebuah string apabila ada sub menu dari masing-masing menu utama
 	  	   * dan akan bernilai negatif apabila tidak ada sub menu */
 	  	  $child = loop($data,$value->MENU_ID); 
 	  	  $str .= '<li>';
 	  	  /* beri tanda sebuah folder dengan warna yang mencolok apabila terdapat sub menu di bawah menu utama 	  	   
 	  	   * dan beri juga event javascript untuk membuka sub menu di dalamnya */
 	  	  $str .= ($child) ? '<a href="javascript:openTree('.$value->MENU_ID.')"><img src="../image/folderclose2.jpg" id="img'.$value->MENU_ID.'" border="0"></a>' : '<img src="../image/folderclose1.jpg">';
 	  	  $str .= '<a href="'.$value->URL.'">'.$value->NAMA_MENU.'</a></li>';
 	  	  if($child) $str .= $child;
		}
		$str .= '</ul>';
		//echo "<br>".$str;
		return $str;
	  }else return false;	  
	}
// 	mysql_connect('localhost','root','');
// 	mysql_select_db('test_tiga');
 	
 	// tampilkan menu di sortir berdasar id dan parent_id agar menu ditampilkan dengan rapih
 	$query = mysql_query('SELECT * FROM master_menu ORDER BY MENU_ID,PARENT_ID');
 	$data = array();
 	while($row = mysql_fetch_object($query)){
	  $data[$row->PARENT_ID][] = $row; // simpan data dari databae ke dalam variable array 3 dimensi di PHP
	}
	echo loop($data,0); // lakukan looping menu utama
 ?>
</body>
</html>
