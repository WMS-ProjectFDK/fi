<?php
ini_set('memory_limit', '-1');
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");

$Bulan = isset($_REQUEST['Bulan']) ? strval($_REQUEST['Bulan']) : '';


$bulan1 = 'SAFETY STOCK '.strtoupper(date('F')).'';
$bulan2 = 'SAFETY STOCK '.strtoupper(date('F', strtotime('+1 month'))).'';
$bulan3 = 'SAFETY STOCK '.strtoupper(date('F', strtotime('+2 month'))).'';

$bulan1x = date('n');
$bulan2x = date('n', strtotime('+1 month'));
$bulan3x = date('n', strtotime('+2 month'));

$sql1 = "call {ZSP_SAFETY_STOCK_1}";
$stmt = sqlsrv_query($connect, $sql1);

$sql = "select zx.item_no,
    i.description,
    qty,
    qty_2,
    100*(1-(qty/qty_2)) persen2,
    CAST(w.this_inventory as decimal(18,2)) as this_inventory
from (select distinct item_no from ztb_safety_stock_1 where period in ( $bulan1x,$bulan2x)) zx 
left outer join (select qty ,item_no from ztb_safety_stock_1 where period =  $bulan1x) z on zx.item_no = z.item_no 
left outer join item i on zx.item_no = i.item_no left outer join whinventory w on zx.item_no = w.item_no
left outer join (select qty qty_2,item_no from ztb_safety_stock_1 where period = $bulan2x) z2 on zx.item_no = z2.item_no";

// echo $sql.'<br/>';
$result = sqlsrv_query($connect, strtoupper($sql));

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();
 
// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'ITEM NO')
            ->setCellValue('C1', 'DESCRIPTION')
          
            ->setCellValue('D1',  $bulan1)
            ->setCellValue('E1',  $bulan2)
            ->setCellValue('F1',  'VARIANCE');
            
foreach(range('A','F') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A1:F1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));

$sheet->getStyle('A1:F1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:F1')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);

$noUrut = 1;    
$no=2;
$p = '';
$rev = 0;
while ($data=sqlsrv_fetch_object($result)) {
    $q = $data->QTY;
    $data->QTY = number_format($q);

    $q2 = $data->QTY_2;
    if ($q < $q2){
        $data->QTY_2 = '<p>'.number_format($q2).' <sub style="color:Tomato;"> ( &uarr; '. $data->PERSEN2 .'% )</sub></p>';
        //number_format($q2);
    }else{
        $data->QTY_2 = '<p>'.number_format($q2).' <sub style="color:DodgerBlue;"> ( &darr; '. $data->PERSEN2 .'% )</sub></p>';
        //number_format($q2);
    };

    $t = $data->THIS_INVENTORY;
    $data->THIS_INVENTORY = number_format($t);

    $s = $data->PURCHASEQTY *-1;
    if ($s > 0) {
        $data->PURCHASEQTY = '<p style="color:DodgerBlue;"> '.number_format($s).' <sub style="color:DodgerBlue;"> (Safe) </sub></p>';
    }else {
        $data->PURCHASEQTY = '<p style="color:Tomato;">'.number_format($s).'<sub style="color:Tomato;"> (Purchase) </sub></p></p>';
    };

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $noUrut)
                ->setCellValue('B'.$no, $data->ITEM_NO)
                ->setCellValue('C'.$no, $data->DESCRIPTION)
                ->setCellValue('D'.$no, number_format($q))
              
                ->setCellValue('E'.$no, number_format($q2))
                ->setCellValue('F'.$no, number_format($data->PERSEN2).'%');
    
    if($no % 2 == 0){
        $sheet->getStyle('A'.$no.':F'.$no)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E2EFDA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
    }else{
        $sheet->getStyle('A'.$no.':F'.$no)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FCE4D6')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
    }

    $objPHPExcel->getActiveSheet()->getStyle('D'.$no.':F'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $no++;      $noUrut++;
}   

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('SAFETY_STOCK');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace( __FILE__,$_SERVER['DOCUMMENT_ROOT'].'E:\xampp/htdocs/fi/forms/mps/SAFETY_STOCK.xls',__FILE__));

/* START BODY EMAIL*/
date_default_timezone_set('Etc/UTC');
require_once '../../class/PHPMailer/PHPMailerAutoload.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "smtp01.fdk.co.jp";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
//$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "do.not.reply.fdkindonesia";
//Password to use for SMTP authentication
// $mail->Password = "V6iT7n8U";
//Set who the message is to be sent from
$mail->setFrom('do.not.reply.fdkindonesia@fdk.co.jp', 'FDK INDONESIA');

$mail->addAddress('ueng.hernama@fdk.co.jp', 'ueng.hernama@fdk.co.jp');

$mail->addAddress('yoga.kristianto@fdk.co.jp', 'yoga.kristianto@fdk.co.jp');
$mail->addAddress('lutfi.sulthony@fdk.co.jp', 'lutfi.sulthony@fdk.co.jp');
$mail->addAddress('agung.mardiansyah@fdk.co.jp', 'agung.mardiansyah@fdk.co.jp');
$mail->addAddress('firmandita@fdk.co.jp', 'firmandita@fdk.co.jp');
$mail->addAddress('leslie.avanti@fdk.co.jp', 'leslie.avanti@fdk.co.jp');
$mail->addAddress('anggari.nugraheni@fdk.co.jp', 'anggari.nugraheni@fdk.co.jp');
$mail->addAddress('agung.kurniawan@fdk.co.jp', 'agung.kurniawan@fdk.co.jp');

$mail->Subject = 'SAFETY STOCK REPORT';
$mail->AddEmbeddedImage("../../images/logo-print4.png", "my-attach", "../../images/logo-print4.png");

$message = '<!DOCTYPE>
<html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <title>SAFETY STOCK REPORT </title>
        <style>
            table {
                border-collapse: collapse;
            }

            table, td, th {
                border: 1px solid black;
                font-family: Verdana, Geneva, sans-serif; 
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <div style="position:absolute;font-family: Verdana, Geneva, sans-serif; font-size: 12px;">
          <p>Dear All,</p>';

$bulan1x = date('F');
$bulan2x = date('F', strtotime('+1 month'));
$bulan3x = date('F', strtotime('+2 month'));

$message.="<p>Please see the attachment of safet stock for month $bulan1x and $bulan2x <p>";

$message.='
<p>Do not reply this email.<br/><br/><br/>
Thanks and Regards,<br/>
<img src="cid:my-attach" width="400" height="80"/></p>';
$message.='
        </div>
    </body>
</html>';
/*END BODY EMAIL*/

//echo $message;
$mail->msgHTML($message);

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
$mail->AddAttachment('E:\xampp/htdocs/fi/forms/mps/SAFETY_STOCK.xls');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
?>