<?php 
include("../connect/koneksi.php");

$sql = pg_query("select a.*, b.nama_barang, c.ket_kategori, d.ket_master, e.ket_satuan, b.brg_class, f.nama_customer 
					from fc_customer a left join master_barang b 
					on a.brg_codebarang = b.brg_codebarang 
					left join master_kategori c 
					on b.jenis_kategori=c.jenis_kategori 
					left join product_family d 
					on b.jenis_prodfam = d.jenis_prodfam 
					left join master_satuan e 
					on b.nama_satuan = e.nama_satuan 
					left join master_customer f 
					on a.kode_customer = f.kode_customer");
					
$nourut = 1;

$content = "
	<style> 
		table {
			border-collapse: collapse;
			font-size:11px;
		}
		table, th, td {
			border: 1px solid black;	
		}
		th {
			background-color: #4bd2fe;
			color: black;
		}
	</style>		
	<img src='../images/logo11.png' alt='#' style='width:30%;'/>
	<h2 align='center'>Customer Forecast Report</h2>
		<table align='center'>
			<tr>
				<th valign='middle' align='center' style='width:20px;height:20px;'>No</th>
				<th valign='middle' align='center' style='width:60px;height:20px;'>Trans ID</th>
				<th valign='middle' align='center' style='width:60pxpx;height:20px;'>Trans Date</th>
				<th valign='middle' align='center' style='width:130px;height:20px;'>Customer</th>
				<th valign='middle' align='center' style='width:130px;height:20px;'>Part No.</th>
				<th valign='middle' align='center' style='width:130px;height:20px;'>Part Name</th>
				<th valign='middle' align='center' style='width:80px;height:20px;'>Category</th>
				<th valign='middle' align='center' style='width:80px;height:20px;'>Prod. Fam</th>
				<th valign='middle' align='center' style='width:40px;height:20px;'>UoM</th>
				<th valign='middle' align='center' style='width:30px;height:20px;'>Class</th>
				<th valign='middle' align='center' style='width:30px;height:20px;'>MI 1</th>
				<th valign='middle' align='center' style='width:30px;height:20px;'>MI 2</th>
				<th valign='middle' align='center' style='width:30px;height:20px;'>MI 3</th>
				<th valign='middle' align='center' style='width:30px;height:20px;'>MI 4</th>
				<th valign='middle' align='center' style='width:30px;height:20px;'>MI 5</th>
				<th valign='middle' align='center' style='width:30px;height:20px;'>MI 6</th>
				<th valign='middle' align='center' style='width:50px;height:20px;'>Remarks</th>
			</tr>";

while($row=pg_fetch_array($sql))
{

$fc_trans_code = $row['fc_trans_code'];
$fc_datecreate = $row['fc_datecreate'];
$nama_customer = $row['nama_customer'];
$brg_codebarang = $row['brg_codebarang'];
$nama_barang = $row['nama_barang'];
$ket_kategori = $row['ket_kategori'];
$ket_master = $row['ket_master'];
$ket_satuan = $row['ket_satuan'];
$brg_class = $row['brg_class'];
$fc_bulan1 = $row['fc_bulan1'];
$fc_bulan2 = $row['fc_bulan2'];
$fc_bulan3 = $row['fc_bulan3'];
$fc_bulan4 = $row['fc_bulan4'];
$fc_bulan5 = $row['fc_bulan5'];
$fc_bulan6 = $row['fc_bulan6'];
$fc_keterangan = $row['fc_keterangan'];


$content .= "
	<tr>
		<td align='center'>$nourut</td>
		<td align='center'>$fc_trans_code</td>
		<td align='center'>$fc_datecreate</td>
		<td align='center'>$nama_customer</td>
		<td align='center'>$brg_codebarang</td>
		<td align='center'>$nama_barang</td>
		<td align='center'>$ket_kategori</td>
		<td align='center'>$ket_master</td>
		<td align='center'>$ket_satuan</td>
		<td align='center'>$brg_class</td>
		<td align='center'>$fc_bulan1</td>
		<td align='center'>$fc_bulan2</td>
		<td align='center'>$fc_bulan3</td>
		<td align='center'>$fc_bulan4</td>
		<td align='center'>$fc_bulan5</td>
		<td align='center'>$fc_bulan6</td>
		<td align='center'>$fc_keterangan</td>
	</tr>
";
	$nourut++;
}
$content .= "</table>";
    require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF('L','A4','en');
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('forecast_report.pdf');
	
?>

