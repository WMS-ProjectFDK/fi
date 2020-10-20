<?php 
//error_reporting(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$dn = isset($_REQUEST['dn']) ? strval($_REQUEST['dn']) : '';

$sql_h = "select dnh.dn_no, CONVERT(varchar,dnh.dn_date,107) as dn_date, dnh.customer_code, com.COMPANY, 
	sum(dnd.AMT_O) as AMT_O, count(dnd.line_no) as ITEM, dnh.ATTN, dnh.DATE_SHIPMENT, dnh.DATE_EX_FACTORY, dnh.REMARK, dnh.BANK_SEQ, 
	dnh.SIGNATURE_NAME, dnd.CURR_CODE, cr.CURR_MARK,
	com.ADDRESS1, com.ADDRESS2, com.ADDRESS3, com.ADDRESS4,
	bnk.BANK, bnk.ADDRESS1 as bnk_addr1, bnk.ADDRESS2 as bnk_addr2, bnk.ADDRESS3 as bnk_addr3, bnk.ADDRESS4 as bnk_addr4, bnk.account_no
	from dn_header dnh
	inner join dn_details dnd on dnh.DN_NO=dnd.DN_NO
	left join company com on dnh.CUSTOMER_CODE=com.COMPANY_CODE
	left join bank bnk on dnh.bank_seq = bnk.BANK_SEQ
	left join CURRENCY cr on dnd.CURR_CODE = cr.CURR_CODE
	where dnh.dn_no='$dn'
	group by dnh.dn_no, dnh.dn_date, dnh.customer_code, com.COMPANY, dnh.ATTN, dnh.DATE_SHIPMENT, dnh.DATE_EX_FACTORY, dnh.REMARK, dnh.BANK_SEQ, 
	dnh.SIGNATURE_NAME, dnd.CURR_CODE, cr.CURR_MARK,
	com.ADDRESS1, com.ADDRESS2, com.ADDRESS3, com.ADDRESS4,
	bnk.BANK, bnk.ADDRESS1, bnk.ADDRESS2, bnk.ADDRESS3, bnk.ADDRESS4, bnk.account_no
	ORDER BY dnh.dn_date desc";
// echo $sql_h;
$head = sqlsrv_query($connect, strtoupper($sql_h));
$dt_h = sqlsrv_fetch_object($head);

$sql = "select dn_h.CUSTOMER_CODE, cp.COMPANY, cp.ADDRESS1, cp.ADDRESS2, dn_h.ATTN, dn_h.DN_NO, 
	convert(varchar,dn_h.DN_DATE, 107) as DN_DATE_FORMAT, dn_h.DN_DESCRIPTION, dn_h.DATE_SHIPMENT,
	dn_h.DATE_EX_FACTORY, dn_h.REMARK, dn_d.LINE_NO, dn_d.INV_NO, 
	convert(varchar,iv_h.BL_DATE, 107) as BL_DATE_FORMAT, substring(dn_d.PO_NO_DESC, 1, 100) as PO_NO_DESC, dn_d.SHIP_NAME, 
	CONVERT(varchar,dn_d.ETD, 107) as ETD_FORMAT,
	CONVERT(varchar,dn_d.ETA, 107) as ETA_FORMAT,
	cr.CURR_MARK, cast(dn_d.AMT_O as decimal(18,5)) as AMT_O_FORMAT, pg.PAGE, bk.BANK, bk.ADDRESS1 as BANK_ADDR1, bk.ADDRESS2 as BANK_ADDR2,
	bk.ACCOUNT_NO, bk.CURR_MARK as BANK_CURR_MARK, dn_h.SIGNATURE_NAME
	from DN_HEADER dn_h,
	DN_DETAILS dn_d,
	DO_HEADER iv_h,
	COMPANY cp,
	CURRENCY cr,
	(select DN_NO, count(*)/15+0.94 as PAGE
	from DN_DETAILS
	where DN_NO in ('$dn')
	group by DN_NO
	) pg,
	(select b.*, c.CURR_MARK
	from BANK b,
	CURRENCY c
	where  b.CURR_CODE = c.CURR_CODE
	) bk
	where dn_h.DN_NO in ('$dn')
	and dn_h.DN_NO         = dn_d.DN_NO
	and dn_d.INV_NO        = iv_h.DO_NO
	and dn_h.DN_NO         = pg.DN_NO
	and dn_h.CUSTOMER_CODE = cp.COMPANY_CODE
	and dn_h.BANK_SEQ      = bk.BANK_SEQ
	and dn_d.CURR_CODE     = cr.CURR_CODE
	order by dn_h.CUSTOMER_CODE, dn_h.DN_DATE, dn_h.DN_NO, dn_d.LINE_NO";
$result = sqlsrv_query($connect, strtoupper($sql));

$date=date("d M y / H:i:s",time());
$content = "	
<style>
	table {
		border-collapse: collapse;
		padding:4px;
		font-size:11px;
	}
	table, th, td {
		border: 1px solid #5A5A5A;	
	}
	th {
		//background-color: #4bd2fe;
		color: black;
	}
	.brd {
		border:none;
	}
</style>
<page>
	<div style='position:absolute;margin-top:0px;'>
		<img src='../../images/logo-print4.png' alt='#' style='width:350px;height: 50px'/>
	</div>	

	<div style='margin-top:0;margin-left:630px;font-size:9px'>
		<p align='' >Bekasi, ".$date."<br>Print By : ".substr($nama_user,0,14)."</p>
	</div>
	
	<page_footer>
		<div style='width:100%; text-align:right; margin-bottom:100%; font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	<br/>
	<hr>
	<div style='margin: 40px 5px 100px 0px;position:absolute;width:100%; padding-bottom:25px;'>
		<h3 align='center'>DEBIT NOTE<br></h3>
		<div>
			<table>
				<tr>
					<td style='width:400px;border:0px solid #ffffff'>
						<table >
							<tr>
								<td style='border:0px solid #ffffff'>Messrs, </td>
								<td style='border:0px solid #ffffff'><b>".$dt_h->COMPANY."</b></td></tr>
							<tr>
								<td style='border:0px solid #ffffff'></td>
								<td style='border:0px solid #ffffff'>".$dt_h->ADDRESS1."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'></td>
								<td style='border:0px solid #ffffff'>".$dt_h->ADDRESS2."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'></td>
								<td style='border:0px solid #ffffff'>".$dt_h->ADDRESS3."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'></td>
								<td style='border:0px solid #ffffff'>".$dt_h->ADDRESS4."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'>ATTN :</td>
								<td style='border:0px solid #ffffff'>".$dt_h->ATTN."</td>
							</tr>
						</table>
					</td>
					<td style='width:330px;border:0px solid #ffffff'>
						<table>
							<tr><td style='border:0px solid #ffffff'>NO : ".$dt_h->DN_NO."</td></tr>
							<tr><td style='border:0px solid #ffffff'>Bekasi, ".$dt_h->DN_DATE."</td></tr>
						</table>
					</td>
				</tr>
				<tr><td colspan=2  style='border-left:0px solid #ffffff;border-right:0px solid #ffffff'></td></tr>
				<tr>
					<td align='center' style='width:400px;'><b>DESCRIPTION</b></td>
					<td align='center' style='width:330px;'><b>AMOUNT</b></td>
				</tr>
				<tr>
					<td style='width:400px;'>
						<table>
							<tr>
								<td style='border:0px solid #ffffff'>DATE OF SHIPMENT</td>
								<td style='border:0px solid #ffffff'>: ".$dt_h->DATE_SHIPMENT."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'>DATE OF EX_FACTORY</td>
								<td style='border:0px solid #ffffff'>: ".$dt_h->DATE_EX_FACTORY."</td>
							</tr>
							<tr><td colspan=2 align='center' style='border:0px;'>Details : Attached</td></tr>
						</table>
					</td>
					<td align='center' style='width:330px;'>
						<table align='center'>
							<tr>
								<td style='width:30px;border:0px solid #ffffff'>".$dt_h->CURR_MARK."</td>
								<td style='width:30px;border:0px solid #ffffff'>".number_format($dt_h->AMT_O,2)."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=2>
						<table>
							<tr>
								<td style='border:0px solid #ffffff'>Remarks</td>
								<td style='border:0px solid #ffffff'>: ".$dt_h->REMARK."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'>TO</td>
								<td style='border:0px solid #ffffff'>: <b>".$dt_h->BANK."</b></td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'></td>
								<td style='border:0px solid #ffffff'>&nbsp;&nbsp;".$dt_h->BNK_ADDR1."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'></td>
								<td style='border:0px solid #ffffff'>&nbsp;&nbsp;".$dt_h->BNK_ADDR2."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'></td>
								<td style='border:0px solid #ffffff'>&nbsp;&nbsp;".$dt_h->BNK_ADDR3."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'></td>
								<td style='border:0px solid #ffffff'>&nbsp;&nbsp;".$dt_h->BNK_ADDR4."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff'>ACCOUNT NO.</td>
								<td style='border:0px solid #ffffff'>: ".$dt_h->ACCOUNT_NO." (".$dt_h->CURR_MARK.")</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=2 valign:'bottom' style='border:0px solid #ffffff;height:150px;'></td>
				</tr>
				<tr>
					<td colspan=2 style='border:0px solid #ffffff'>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
						PT. FDK INDONESIA
						<br/><br/><br/><br/><br/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<b><u>".$dt_h->SIGNATURE_NAME."</u></b>
					</td>
				</tr>
			</table>
		</div>
	</div>
</page>";


$content .= " 
<page>
	<page_footer>
	<div style='width:100%; text-align:right; margin-bottom:100%; font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
	</page_footer>

	<div style='margin: 40px 5px 100px 0px;position:absolute;width:100%; padding-bottom:25px;'>
		<div>
			<table align='center' style='margin: 0px; padding-bottom:50px;'>
				<thead>
				<tr>
					<th valign='middle' align='center' style='font-size:12px;width:50px;height:35px;'>ITEM</th>
					<th valign='middle' align='center' style='font-size:12px;width:550px;height:35px;'>DESCRIPTION</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:35px;'>QTY</th>
				</tr>
				</thead> ";
while ($data=sqlsrv_fetch_object($result)){
$content .= "
				<tr>
					<td valign='middle' align='center' style='width:50px;font-size:12px;height:25px;'>".$data->LINE_NO."</td>
					<td valign='middle' align='left' style='width:550px;font-size:12px;height:25px;'>
						<table>
							<tr>
								<td style='border:0px solid #ffffff;'>Invoice No.</td>
								<td style='border:0px solid #ffffff;width:400px;'>: ".$data->INV_NO."</td>
								<td style='border:0px solid #ffffff;'>Date ".$data->BL_DATE_FORMAT."</td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff;'>Po No.</td>
								<td style='border:0px solid #ffffff;width:400px;'>: ".$data->PO_NO_DESC."</td>
								<td style='border:0px solid #ffffff;'></td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff;'>Vessel</td>
								<td style='border:0px solid #ffffff;width:400px;'>: ".$data->SHIP_NAME."</td>
								<td style='border:0px solid #ffffff;'></td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff;'>ETD</td>
								<td style='border:0px solid #ffffff;width:400px;'>: ".$data->ETD_FORMAT."</td>
								<td style='border:0px solid #ffffff;'></td>
							</tr>
							<tr>
								<td style='border:0px solid #ffffff;'>ETA</td>
								<td style='border:0px solid #ffffff;width:400px;'>: ".$data->ETA_FORMAT."</td>
								<td style='border:0px solid #ffffff;'></td>
							</tr>
						</table>
					</td>
					<td valign='middle' align='right' style='width:100px;font-size:12px;height:25px;'>
						<table>
							<tr>
								<td align='left' style='width:20px;border:0px solid #ffffff'>".$data->CURR_MARK."</td>
								<td align='right' style='width:70px;border:0px solid #ffffff'>".number_format($data->AMT_O_FORMAT,2)."</td>
							</tr>
						</table>
					</td>
				</tr>
";
}

$content .="				
			</table>
		</div>
	</div>
</page>

";

require_once(dirname(__FILE__).'../../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('DN-'.$dn.'.pdf');
	// echo  $content;
?>