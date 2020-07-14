<?php
// Create By : Ueng hernama
// Date : 24-oct-2017
// ID = 2
include("../connect/conn.php");
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

// $mail->addAddress('prihartanto@fdk.co.jp', 'prihartanto@fdk.co.jp');
// $mail->addAddress('yoga.kristianto@fdk.co.jp', 'yoga.kristianto@fdk.co.jp');
// $mail->addAddress('ferry.agung@fdk.co.jp', 'ferry.agung@fdk.co.jp');
// $mail->addAddress('garnadibs@fdk.co.jp', 'garnadibs@fdk.co.jp');
// $mail->addAddress('slamet.maryanto@fdk.co.jp', 'slamet.maryanto@fdk.co.jp');
// $mail->addAddress('wahyu@fdk.co.jp', 'wahyu@fdk.co.jp');
// $mail->addAddress('lukman@fdk.co.jp', 'lukman@fdk.co.jp');
// $mail->addAddress('anton.yuhadi@fdk.co.jp', 'anton.yuhadi@fdk.co.jp');
// $mail->addAddress('victor.antonio@fdk.co.jp', 'victor.antonio@fdk.co.jp');
// $mail->addAddress('ari_harari@fdk.co.jp', 'ari_harari@fdk.co.jp');
// $mail->addAddress('santi.cipta@fdk.co.jp', 'santi.cipta@fdk.co.jp');
// $mail->addAddress('budi.setiadi@fdk.co.jp', 'budi.setiadi@fdk.co.jp');

$mail->addAddress('agusman@fdk.co.jp', 'agusman@fdk.co.jp');
$mail->addAddress('anggari.nugraheni@fdk.co.jp', 'rahmat.budiyanto@fdk.co.jp');
$mail->addAddress('anggari.nugraheni@fdk.co.jp', 'anggari.nugraheni@fdk.co.jp');
$mail->addAddress('erike.meindra@fdk.co.jp', 'erike.meindra@fdk.co.jp');//anggari.nugraheni@fdk.co.jp
//$mail->addAddress('reza@fdk.co.jp', 'reza@fdk.co.jp');
$mail->addAddress('agung.kurniawan@fdk.co.jp', 'Agung Kurniawan');
$mail->addAddress('wisnu@fdk.co.jp', 'Wisnu');
$mail->addAddress('ueng.hernama@fdk.co.jp', 'ueng.hernama@fdk.co.jp');
$mail->addAddress('dewi.lestari@fdk.co.jp', 'Dewi Lestari');


// $mail->addAddress('heru.wibowo@fdk.co.jp','heru.wibowo@fdk.co.jp');
// $mail->addAddress('dono@fdk.co.jp', 'dono@fdk.co.jp');
// $mail->addAddress('aris@fdk.co.jp', 'aris@fdk.co.jp');
// $mail->addAddress('setiawan@fdk.co.jp', 'setiawan@fdk.co.jp');
// $mail->addAddress('satrio.adiwibowo@fdk.co.jp', 'satrio.adiwibowo@fdk.co.jp');
// $mail->addAddress('slamet.maryanto@fdk.co.jp', 'slamet.maryanto@fdk.co.jp');
// $mail->addAddress('handiko.haminanto@fdk.co.jp', 'handiko.haminanto@fdk.co.jp');
// $mail->addAddress('ardian.ary@fdk.co.jp', 'ardian.ary@fdk.co.jp');
// $mail->addAddress('widodo@fdk.co.jp', 'widodo@fdk.co.jp');

// $mail->addAddress('wakuda_nobuyuki@fdk.co.jp', 'wakuda_nobuyuki@fdk.co.jp');
// $mail->addAddress('ema@fdk.co.jp', 'ema@fdk.co.jp');
// $mail->addAddress('tuduki@fdk.co.jp', 'tuduki@fdk.co.jp');
// $mail->addAddress('ishimasa@fdk.co.jp', 'ishimasa@fdk.co.jp');
// $mail->addAddress('tsuboi_satoshi@fdk.co.jp', 'tsuboi_satoshi@fdk.co.jp');

// $mail->addAddress('shiba@fdk.co.jp', 'shiba@fdk.co.jp');
// $mail->addAddress('yuji@fdk.co.jp', 'yuji@fdk.co.jp');

// $mail->addAddress('yoshi@fdk.co.jp', 'yoshi@fdk.co.jp');
// $mail->addAddress('hagai@fdk.co.jp', 'hagai@fdk.co.jp');

//Set the subject line
$mail->Subject = 'Shipping Report Item Availablity';
$mail->AddEmbeddedImage("../images/logo-print4.png", "my-attach", "../../images/logo-print4.png");

$arrBln = array('','JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');

$mm1  = $arrBln[intval(date('m')-1)];
$m  = $arrBln[intval(date('m'))];
$m1 = $arrBln[intval(date('m'))+1];
$m2 = $arrBln[intval(date('m'))+2];
$m3 = $arrBln[intval(date('m'))+3];
$m4 = $arrBln[intval(date('m'))+4];


$message = '<!DOCTYPE>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>FDK-SPAREPARTS PURCHASE REPORT</title>
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
<div style="width: 920px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
  <h4>Dear All,</h4>
  <p>Please see shipping information within the item availability data for two weeks shipment.<br/></p>';

//cek bulan lalu yg belom datang
// $cek = "select sum(poh.ex_rate * pod.u_price * pod.bal_qty) as not_arrive from po_details pod
// inner join po_header poh on pod.po_no=poh.po_no
// where to_char(pod.ETA,'YYYYMM') = (select to_char(ADD_MONTHS(sysdate,-1),'YYYYMM') from dual)";
// $dt_cek = oci_parse($connect, $cek);
// oci_execute($dt_cek);
// $data_cek=oci_fetch_object($dt_cek);
// $not_arrive_bulan_lalu = $data_cek->NOT_ARRIVE;


$message .='
  	  <div >
	  <table style="font-size: 10px;">
	  	<tr>';


	       
$message .='
	       <th style="background-color: #EAECEE;width: 300px;" align="center">PO No</th>
	       <th style="background-color: #EAECEE;width: 350px;" align="center">Work Order No</th>
	       <th style="background-color: #EAECEE;width: 150px;" align="center">Item No</th>
	       <th style="background-color: #EAECEE;width: 650px;" align="center">Description</th>
	       <th style="background-color: #EAECEE;width: 150px;" align="center">PPBE No</th>

	       <th style="background-color: #EAECEE;width: 150px;" align="center">ETD</th>
	        <th style="background-color: #EAECEE;width: 150px;" align="center">CR DATE</th>
	       <th style="background-color: #EAECEE;width: 80px;" align="center">Time to Delivery<br/>(Days)</th>
	       <th style="background-color: #EAECEE;width: 150px;" align="center">Total Pallet</th>
	       <th style="background-color: #EAECEE;width: 150px;" align="center">Quantity Order</th>
	       <th style="background-color: #EAECEE;width: 150px;" align="center">Quantity In Label</th>
	       <th style="background-color: #EAECEE;width: 150px;" align="center">Quantity In Packing</th>
	       <th style="background-color: #EAECEE;width: 150px;" align="center">Quantity Kuraire</th>
	       <th style="background-color: #EAECEE;width: 150px;" align="center">Quantity Remain</th>


	      
	      
	    </tr>';

$no=1;
$sql = "
select customer_po_no PO,
        work_no,answer.item_no,item.description,
        case when crs_remark like '%A' then 'Agung' else case when crs_remark like '%W' Then 'Wisnu' else 'Dewi' end end createdBy,crs_remark PPBE,ETD,FLOOR(ETD - sysdate) Hari,qty,nvl(qty_prod,0) qty_prod ,qty - nvl(qty_prod,0) REMAIN,
        mps_header.cr_date,
        ceil(answer.qty/ ii.pallet_pcs) TotalPallet,
        case when nvl(label,0) < nvl(qtypacking,0) then nvl(qtypacking,0) else nvl(label,0)  end TotalLabelProd,
        nvl(qtypacking,0) TotalPacking
from answer
inner join item on answer.item_no = item.item_no 
inner join mps_header on answer.work_no = mps_header.work_order
inner join ztb_item ii on ii.item_no = answer.item_no
left outer join (
            select sum(p.qty_prod) label,p.wo_no from ztb_kanban l
            inner join ztb_m_plan p on l.id = p.id
            group by p.wo_no
)material on material.wo_no = work_no
left outer join (
           select sum(battery_in) qtypacking,wo_no from ztb_kanban_lbl l
          inner join ztb_l_plan p on l.idkanban = p.id
          group by wo_no
)packing on packing.wo_no = work_no
left outer join (select wo_no,sum(slip_quantity) qty_prod from production_income group by wo_no) pi on answer.work_no = pi.wo_no
where answer_no in (
select answer_no from indication where remark = '1' and answer.item_no > 9999
) and FLOOR(ETD - sysdate) < 14 order by createdBy,Hari
";

$dt = oci_parse($connect, $sql);
oci_execute($dt);

$tot_a = 0;		$tot_b = 0;		$tot_c = 0;		$tot_d = 0;		$tot_e = 0; $tot_f = 0; $tot_g = 0;
$no =0;
$oldcreatedby = '';
while ($data=oci_fetch_object($dt)){
	$createdby = $data->CREATEDBY;
	if($oldcreatedby <> $createdby){
	$message.='<tr>
			<td colspan=14 style="background-color: #D2D2D2;" align="center"><b>'.$createdby.' PPBE</b></td>
			</tr>';
	};

    $remain = number_format($data->REMAIN);

   
   if ($no == 1){


	$message.='
					<td style="background-color: #FCF3CF  ;" align="left">'.$data->PO.'&nbsp;</td>
					<td style="background-color: #FCF3CF  ;" align="left">'.$data->WORK_NO.'&nbsp;</td>
					<td style="background-color: #FCF3CF  ;" align="left">'.$data->ITEM_NO.'&nbsp;</td>
					<td style="background-color: #FCF3CF  ;" align="left">'.$data->DESCRIPTION.'&nbsp;</td>
					
					
					<td style="background-color: #FCF3CF  ;" align="left">'.$data->PPBE.'&nbsp;</td>
					<td style="background-color: #FCF3CF  ;" align="left">'.$data->ETD.'&nbsp;</td>
					<td style="background-color: #FCF3CF  ;" align="left">'.$data->CR_DATE.'&nbsp;</td>

					<td style="background-color: #FCF3CF  ;" align="center">'.$data->HARI.'&nbsp;</td>
					<td style="background-color: #FCF3CF  ;" align="right">'.number_format($data->TOTALPALLET).'&nbsp;</td>
					<td style="background-color: #FCF3CF  ;" align="right">'.number_format($data->QTY).'&nbsp;</td>
					<td style="background-color: #FCF3CF  ;" align="right">'.number_format($data->TOTALLABELPROD).'&nbsp;</td>
					<td style="background-color: #FCF3CF  ;" align="right">'.number_format($data->TOTALPACKING).'&nbsp;</td>
					
					<td style="background-color: #FCF3CF  ;" align="right">'.number_format($data->QTY_PROD).'&nbsp;</td>
					
					
			  ';
	} else {

				$message.='
					<td style="background-color: #FDFEFE  ;" align="left">'.$data->PO.'&nbsp;</td>
					<td style="background-color: #FDFEFE  ;" align="left">'.$data->WORK_NO.'&nbsp;</td>
					<td style="background-color: #FDFEFE  ;" align="left">'.$data->ITEM_NO.'&nbsp;</td>
					<td style="background-color: #FDFEFE  ;" align="left">'.$data->DESCRIPTION.'&nbsp;</td>
					
					
					<td style="background-color: #FDFEFE  ;" align="left">'.$data->PPBE.'&nbsp;</td>
					<td style="background-color: #FDFEFE  ;" align="left">'.$data->ETD.'&nbsp;</td>
					<td style="background-color: #FDFEFE  ;" align="left">'.$data->CR_DATE.'&nbsp;</td>

					<td style="background-color: #FDFEFE  ;" align="center">'.$data->HARI.'&nbsp;</td>
					<td style="background-color: #FDFEFE  ;" align="right">'.number_format($data->TOTALPALLET).'&nbsp;</td>
					<td style="background-color: #FDFEFE  ;" align="right">'.number_format($data->QTY).'&nbsp;</td>
					<td style="background-color: #FDFEFE  ;" align="right">'.number_format($data->TOTALLABELPROD).'&nbsp;</td>
					<td style="background-color: #FDFEFE  ;" align="right">'.number_format($data->TOTALPACKING).'&nbsp;</td>
					
					<td style="background-color: #FDFEFE  ;" align="right">'.number_format($data->QTY_PROD).'&nbsp;</td>
					
					
			  ';
	};
	if ($remain == 0) {
    	$message .= '<td style="background-color: #D2D2D2  ;" align="right">'.number_format($data->REMAIN).'&nbsp;</td>  </tr>';
    }else{
    	if ($no == 1){
    			$message .= '<td style="background-color: #FCF3CF  ;" align="right">'.number_format($data->REMAIN).'&nbsp;</td>   </tr>';}
    	else {
    			$message .= '<td style="background-color: #FDFEFE ;" align="right">'.number_format($data->REMAIN).'&nbsp;</td>   </tr>';};
    };    

	$oldcreatedby = $createdby;
	$no++;
	if($no==2)$no=0;
}




$message.='</table>
		</div>';

$message.='

Do not reply this email.<br/><br/><br/>
Thanks and Regards,<br/>
<img src="cid:my-attach" width="400" height="75"/></p>';
$message.='
		</div>
	</body>
</html>';



// $mail->msgHTML($message);
// echo $sql;
// echo $message;

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

// $mail->AddAttachment($dataXLS);
//send the message, check for errors


if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
?>
<!-- <p> Note : if you want to download, please open <a href="http://kanbansvr/wms/schedule/spareparts_PO_mail_excel_download.php">here</a> -->