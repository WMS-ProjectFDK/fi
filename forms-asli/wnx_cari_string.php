 <?php
// fungsi stristr PHP
// kumpulan string
$string = "01/FILR/19";

//keyword pencarian
$key = "RPLC";

// lakukan pencarian string
$cari = stristr($string,$key);

//tampilkan
echo" String awal = $string </br>";
echo" String pencarian = $cari";

?>