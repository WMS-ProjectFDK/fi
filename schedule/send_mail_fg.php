<?php
// Create By : Ueng hernama
// Date : 24-oct-2017
// ID = 2
include("../connect/conn.php");
/* CONTENT ATACHMENT*/
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
$qry = "select aa.* from (
		select a.item_no, b.description, a.wo_no,max(a.tanggal) as tgl,sum(a.qty) as qty, b.standard_price, sum(qty)*b.standard_price as amount 
		        from ztb_fg_fifo a
		        inner join item b on a.item_no=b.item_no
		        group by a.item_no, b.description, a.wo_no, b.standard_price
		        order by a.item_no, sum(a.qty) desc
		) aa
		left outer join
		(
		SELECT item_no, sum(qty) as qty_total from ztb_fg_fifo
		group by item_no
		) bb
		on aa.item_no=bb.item_no
		order by bb.qty_total desc";
$result = oci_parse($connect, $qry);
oci_execute($result);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'FINISH GOODS QUANTITY FOR '.date('l, F d, Y H:i:s'));
$objPHPExcel->setActiveSheetIndex()->mergeCells('A1:F1');

$sheet->getStyle('A1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1')->applyFromArray(
    array(
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'ITEM')
            ->setCellValue('B4', 'DESCRIPTION')
            ->setCellValue('C4', 'WO')
            ->setCellValue('D4', 'DATE')
            ->setCellValue('E4', 'TOTAL QTY')
            ->setCellValue('F4', 'AMOUNT BY STANDARD PRICE');

$sheet = $objPHPExcel->getActiveSheet();

foreach(range('A','F') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A4:F4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A4:F4')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A4:F4')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        ),
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$no = 5;        
$tot_qty=0;             $tot_amo = 0;
$tot_qty_item = 0;      $tot_amo_item=0;
$tot_row = 0;           $itm = '';          $itm_desc = '';
while ($row=oci_fetch_object($result)){
    if($no==5){
        $tot_row = $no;                 $no++;
        $itm_desc = $row->ITEM_NO.' - '.$row->DESCRIPTION;
        $tot_qty_item += $row->QTY;     $tot_amo_item += $row->AMOUNT;
    }else{
        if($row->ITEM_NO == $itm){
            $tot_qty_item += $row->QTY;     $tot_amo_item += $row->AMOUNT;
            $itm_desc = $row->ITEM_NO.' - '.$row->DESCRIPTION;
        }else{
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$tot_row, $itm_desc)
                ->setCellValue('E'.$tot_row, $tot_qty_item)
                ->setCellValue('F'.$tot_row, $tot_amo_item);
            
            $objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$tot_row.':D'.$tot_row);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$tot_row.':F'.$tot_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $sheet->getStyle('A'.$tot_row)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->getStyle('A'.$tot_row)->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );
            $sheet->getStyle('A'.$tot_row.':F'.$tot_row)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '00AAFF')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'font'  => array(
                        'bold'  => true,
                        'size'  => 12
                    )
                )
            );

            $tot_qty += $tot_qty_item;             
            $tot_amo += $tot_amo_item;

            $tot_qty_item = 0;
            $tot_amo_item = 0;
            $tot_row = $no;                 $no++;
            $itm = $row->ITEM_NO.' - '.$row->DESCRIPTION;
            $tot_qty_item += $row->QTY;     $tot_amo_item += $row->AMOUNT;
        }
    }

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $row->ITEM_NO)
                ->setCellValue('B'.$no, $row->DESCRIPTION)
                ->setCellValue('C'.$no, $row->WO_NO)
                ->setCellValue('D'.$no, $row->TGL)
                ->setCellValue('E'.$no, $row->QTY)
                ->setCellValue('F'.$no, $row->AMOUNT);

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

    $objPHPExcel->getActiveSheet()->getStyle('E'.$no.':F'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    
    $itm = $row->ITEM_NO;
    $no++;
}

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$tot_row, $itm_desc)
            ->setCellValue('E'.$tot_row, $tot_qty_item)
            ->setCellValue('F'.$tot_row, $tot_amo_item);
        
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$tot_row.':D'.$tot_row);
$objPHPExcel->getActiveSheet()->getStyle('E'.$tot_row.':F'.$tot_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$sheet->getStyle('A'.$tot_row)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A'.$tot_row)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);
$sheet->getStyle('A'.$tot_row.':F'.$tot_row)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '00AAFF')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        ),
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$tot_qty += $tot_qty_item;             
$tot_amo += $tot_amo_item;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'QTY : '.number_format($tot_qty,2));
$objPHPExcel->setActiveSheetIndex()->mergeCells('A2:F2');

$sheet->getStyle('A2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));
$sheet->getStyle('A2')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A2')->applyFromArray(
    array(
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'AMOUNT : '.number_format($tot_amo,2));
$objPHPExcel->setActiveSheetIndex()->mergeCells('A3:F3');

$sheet->getStyle('A3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));
$sheet->getStyle('A3')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A3')->applyFromArray(
    array(
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$objPHPExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('FG REPORT'.date('M'));
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

@ob_start();
// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

$objWriter->save("php://output");
$dataXLS = @ob_get_contents();
@ob_end_clean();
/* END CONTENT ATACHMENT*/


date_default_timezone_set('Etc/UTC');
require_once '../class/PHPMailer/PHPMailerAutoload.php';

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
$mail->Host = "virus.fdk.co.jp";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
//$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "do.not.reply.fdkindonesia";
//Password to use for SMTP authentication
$mail->Password = "fidonot";
//Set who the message is to be sent from
$mail->setFrom('do.not.reply.fdkindonesia@fdk.co.jp', 'FDK INDONESIA');

$mail->addAddress('atsushi.sohara@fdk.co.jp', 'atsushi.sohara@fdk.co.jp');
$mail->addAddress('agusman@fdk.co.jp', 'agusman@fdk.co.jp');
$mail->addAddress('victor.antonio@fdk.co.jp', 'victor.antonio@fdk.co.jp');
$mail->addAddress('lutfi.sulthony@fdk.co.jp', 'lutfi.sulthony@fdk.co.jp');
$mail->addAddress('ari_harari@fdk.co.jp', 'ari_harari@fdk.co.jp');
$mail->addAddress('hengky.catur@fdk.co.jp>hengky','hengky.catur@fdk.co.jp>hengky');
$mail->addAddress('reza@fdk.co.jp', 'reza@fdk.co.jp');
$mail->addAddress('ueng.hernama@fdk.co.jp', 'ueng.hernama@fdk.co.jp');

//Set the subject line
$mail->Subject = 'FINISH GOODS REPORT';
$mail->AddEmbeddedImage("../images/logo-print4.png", "my-attach", "../../images/logo-print4.png");

$message = '<!DOCTYPE>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>FDK-FINISH GOODS REPORT</title>
  <style>
	table {
	    border-collapse: collapse;
	}

	table, td, th {
	    border: 1px solid black;
	}
</style>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
  <h4>Dear All,</h4>
  <p>Please see the result of Finish Goods :<br/></p>';

$message.='<div>
	  <table>
	  	<tr>
	       <th style="background-color: #D2D2D2;width: 250px;" align="center">TOTAL QTY</th>
	       <th style="background-color: #D2D2D2;width: 250px;" align="center">TOTAL AMOUNT</th>
	    </tr>
	    <tr>
			<td style="background-color: #E2EFDA;" align="right">'.number_format($tot_qty,2).'</td>
			<td style="background-color: #E2EFDA;" align="right">'.number_format($tot_amo,2).'</td>
		</tr>
	  </table><br/>';
$message .='
  	  <p>Here is top five finish goods base on quantity, </p>
	  <table>
	  	<tr>
	       <th style="background-color: #D2D2D2;width: 40px;" align="center">NO.</th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">ITEM NO</th>
	       <th style="background-color: #D2D2D2;width: 300px;" align="center">DESCRIPTION</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">QTY</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">AMOUNT</th>
	    </tr>';

$no2=1;
$sql = 'select * from
		(select aa.item_no, bb.description, aa.qty, aa.qty*bb.standard_price as amount  from 
		  (select distinct ff.item_no, sum(ff.qty) as qty from ztb_fg_fifo ff
		   group by ff.item_no) aa 
		  left outer join
		  (select i.item_no, i.description, i.standard_price from item i) bb on aa.item_no = bb.item_no
		   order by aa.qty desc
		) where rownum <=5';
$dt = oci_parse($connect, $sql);
oci_execute($dt);

while ($data=oci_fetch_object($dt)){
	$message.='<tr>
					<td style="background-color: #E2EFDA;">'.$no2.'</td>
					<td style="background-color: #E2EFDA;">'.$data->ITEM_NO.'</td>
					<td style="background-color: #E2EFDA;">'.$data->DESCRIPTION.'</td>
					<td style="background-color: #E2EFDA;" align="right">'.number_format($data->QTY,2).'</td>
					<td style="background-color: #E2EFDA;" align="right">'.number_format($data->AMOUNT,2).'</td>
			   </tr>';
	$no2++;
}

$message.='</table>
		</div>';

$message.='
<p>Do not reply this email.<br/><br/><br/>
Thanks and Regards,<br/>
<img src="cid:my-attach" width="400" height="75"/></p>';
$message.='
		</div>
	</body>
</html>';

$mail->msgHTML($message);

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
$mail->AddStringAttachment($dataXLS, "FG REPORT ".date('M').".xls");
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
?>