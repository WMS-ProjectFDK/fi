<?php
require_once '../class/phpexcel/PHPExcel.php';
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
$jumlah = pg_num_rows($sql);
 
// Create new PHPExcel object
$objPHPExcel = new PHPExcel(); 
 
// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A6', 'No')
            ->setCellValue('B6', 'Trans ID')
            ->setCellValue('C6', 'Trans Date')
            ->setCellValue('D6', 'Customer')
            ->setCellValue('E6', 'Part No.') 
            ->setCellValue('F6', 'Part Name')
            ->setCellValue('G6', 'Category')
            ->setCellValue('H6', 'Prod. Fam')
            ->setCellValue('I6', 'UoM')
            ->setCellValue('J6', 'Class')
            ->setCellValue('K6', 'MI 1')
            ->setCellValue('L6', 'MI 2')
            ->setCellValue('M6', 'MI 3')
            ->setCellValue('N6', 'MI 4')
            ->setCellValue('O6', 'MI 5') 
            ->setCellValue('P6', 'MI 6')
            ->setCellValue('Q6', 'Remarks');
			
$noUrut = 1;	
$no=7;		
while($row=pg_fetch_array($sql))
{
$noUrut++;
$fc_trans_code = rtrim($row['fc_trans_code']);
$fc_datecreate = rtrim($row['fc_datecreate']);
$nama_customer = rtrim($row['nama_customer']);
$brg_codebarang = rtrim($row['brg_codebarang']);
$nama_barang = rtrim($row['nama_barang']);
$ket_kategori = rtrim($row['ket_kategori']);
$ket_master = rtrim($row['ket_master']);
$ket_satuan = rtrim($row['ket_satuan']);
$brg_class = rtrim($row['brg_class']);
$fc_bulan1 = rtrim($row['fc_bulan1']);
$fc_bulan2 = rtrim($row['fc_bulan2']);
$fc_bulan3 = rtrim($row['fc_bulan3']);
$fc_bulan4 = rtrim($row['fc_bulan4']);
$fc_bulan5 = rtrim($row['fc_bulan5']);
$fc_bulan6 = rtrim($row['fc_bulan6']);
$fc_keterangan = rtrim($row['fc_keterangan']);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no, ($noUrut)-1)
            ->setCellValue('B'.$no, $fc_trans_code)
            ->setCellValue('C'.$no, $fc_datecreate)
            ->setCellValue('D'.$no, $nama_customer)
            ->setCellValue('E'.$no, $brg_codebarang)
            ->setCellValue('F'.$no, $nama_barang)
            ->setCellValue('G'.$no, $ket_kategori)
            ->setCellValue('H'.$no, $ket_master)
            ->setCellValue('I'.$no, $ket_satuan)
            ->setCellValue('J'.$no, $brg_class)
            ->setCellValue('K'.$no, $fc_bulan1)
            ->setCellValue('L'.$no, $fc_bulan2)
            ->setCellValue('M'.$no, $fc_bulan3)
            ->setCellValue('N'.$no, $fc_bulan4)
            ->setCellValue('O'.$no, $fc_bulan5)
            ->setCellValue('P'.$no, $fc_bulan6)
            ->setCellValue('Q'.$no, $fc_keterangan);

			$no++;
}

for($col = 'A'; $col !== 'Q'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('CUSTOMER FORECAST');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 // Menambahkan file gambar pada document excel pada kolom B2
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('SITA');
$objDrawing->setDescription('SITA');
$objDrawing->setPath('../images/logo11.png');
$objDrawing->setWidth('500px');
$objDrawing->setCoordinates('B2');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="customer_forecast.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>