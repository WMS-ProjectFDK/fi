<?php
session_start();
error_reporting(0);
include("../../connect/conn.php");
date_default_timezone_set('Asia/Jakarta');
$date=date("d M y / H:i:s",time());
$nama_user = $_SESSION['name_wms'];

$po = isset($_REQUEST['po']) ? strval($_REQUEST['po']) : '';
$by = isset($_REQUEST['by']) ? strval($_REQUEST['by']) : '';

if($by == 'check_line'){
	$order = "order by a.line_no asc";
}else{
	$order = "order by a.eta asc";
}

$sql_h = "select 
    SUPPLIER_CODE,PO_NO,cast(PO_DATE as varchar(10)) as PO_DATE,c.CURR_CODE,EX_RATE,a.TTERM,case when a.PDAYS = 0 then '' else cast(a.PDAYS as varchar(10)) end PDAYS,a.PDESC,REQ,ETA,MARKS1,REVISE,AMT_O,AMT_L,FDK_PERSON_CODE,a.ATTN,PERSON_CODE,a.CC,DATE_TYPE,PBY,a.UPTO_DATE,a.REG_DATE,SECTION_CODE,SHIPTO_CODE,TRANSPORT,DI_OUTPUT_TYPE,FREIGHT,a.VAT,BTT,NBT,a.UPTO_DATE,a.REG_DATE,c.DELETE_TYPE,c.COMPANY_CODE,COMPANY_TYPE,COMPANY,ADDRESS1,ADDRESS2,ADDRESS3,ADDRESS4,a.ATTN,TEL_NO,FAX_NO,ZIP_CODE,cou.COUNTRY_CODE,c.CURR_CODE,COMPANY_SHORT,TAXPAYER_NO,E_MAIL,QUOT_SALE_CODE,FORECAST_FLG,ACCPAC_COMPANY_CODE,BONDED_TYPE,BC_DOC,BC_DOC_REVERSE,
    a.tterm as trade_term, cou.country, c.curr_short, c.curr_mark, 
	(select coalesce(count(CARVED_STAMP),0) from po_details s where s.po_no = a.po_no) as date_code,
	replace(reason1,'CHAR(13)+CHAR(10)','<br/>') as reason1,
	replace(remark1,'CHAR(13)+CHAR(10)','<br/>') as remark1,
  	(select count(*) from po_details where po_no=a.po_no) as jum_dtl,
  	case when a.REVISE is null then 'N' else a.revise end as revise
	from po_header a
	left join company com on a.supplier_code=com.company_code
	left join country cou on com.country_code=cou.country_code
	left join currency c on a.curr_code= c.curr_code
	where a.po_no='$po'";
// echo $sql_h;
$head = sqlsrv_query($connect, strtoupper($sql_h));
$dt_h = sqlsrv_fetch_object($head);
$dc = $dt_h->DATE_CODE;
$j = $dt_h->JUM_DTL;

if($dt_h->TRANSPORT == '1'){
	$trans = "AIR";
}elseif($dt_h->TRANSPORT=='2'){
	$trans = "OCEAN";
}elseif($dt_h->TRANSPORT=='3'){
	$trans = "TRUCK";
}

if ($dc!=0){
	$t_h = "<th valign='middle' align='center' style='font-size:12px;width:255px;height:35px;'>ITEM DESCRIPTION<br/>ITEM NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:60px;height:35px;'>DATE<br/>CODE</th>";
}else{
	$t_h = "<th valign='middle' align='center' style='font-size:12px;width:320px;height:25px;'>ITEM DESCRIPTION<br/>ITEM NO.</th>";
}

$qry = "select a.line_no, a.item_no, b.item, b.description, cast(a.eta as varchar(10)) as eta, a.qty, a.uom_q, c.unit_pl, c.unit, a.u_price, 
	po.curr_code,e.curr_short,e.curr_mark, a.amt_l, a.amt_o, a.CARVED_STAMP
	from po_details a
	left join po_header po on a.po_no=po.po_no
	left join item b on a.item_no=b.item_no
	left join unit c on a.uom_q=c.unit_code
	left join country d on a.origin_code= d.country_code
	left join currency e on po.curr_code=e.curr_code
	where po.po_no='$po'
	$order ";
$result = sqlsrv_query($connect, strtoupper($qry));

$content = "
<page> 
    <page_header> 
        <div style='float:left;margin-top:0px;width:20%;'>
            <img src='../../images/logo-print4.png' alt='#' style='width:300px;height: 70px'/><br/>
        </div>
        <div style='position:absolute;margin-top:0;width:50%;margin-left:210px;' align='center'>
            <h2 align='center'>PURCHASE ORDER<br></h2>
        </div>
        <div style='position:absolute;margin-top:0;margin-left:620px;font-size:9px'>
            <p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
        </div>
        <table border=1 align='center' style='width:100%;font-size:12px;'>
            <tr>
                <td valign='top' style='font-size:10px;width:350px;height:25px;'>
                    <table>
                        <tr>
                        <td style='font-size:12px;border:0px solid #ffffff;'><b>TO</b></td>
                        <td style='font-size:12px;border:0px solid #ffffff;'>:</td>
                        <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".$dt_h->COMPANY."</td>
                        </tr>
                        <tr>
                            <td style='font-size:12px;border:0px solid #ffffff;'></td>
                            <td style='font-size:12px;border:0px solid #ffffff;'></td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".wordwrap($dt_h->ADDRESS1, 36, '<br />', true)."</td>
                        </tr>
                        <tr>
                            <td style='font-size:12px;border:0px solid #ffffff;'></td>
                            <td style='font-size:12px;border:0px solid #ffffff;'></td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".wordwrap($dt_h->ADDRESS2, 36, '<br />', true)."</td>
                        </tr>
                        <tr>
                            <td style='font-size:12px;border:0px solid #ffffff;'>ATTN</td>
                            <td style='font-size:12px;border:0px solid #ffffff;'>:</td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".$dt_h->ATTN."</td>
                        </tr>
                        <tr>
                            <td style='font-size:12px;border:0px solid #ffffff;'>TEL</td>
                            <td style='font-size:12px;border:0px solid #ffffff;'>:</td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".$dt_h->TEL_NO."</td>
                        </tr>
                        <tr>
                            <td style='font-size:12px;border:0px solid #ffffff;'>FAX</td>
                            <td style='font-size:12px;border:0px solid #ffffff;'>:</td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".$dt_h->FAX_NO."</td>
                        </tr>
                    </table>
                </td>
                <td style='border-top:5px solid #ffffff;border-bottom:5px solid #ffffff;'></td>
                <td valign='top' style='font-size:10px;width:340px;height:25px;'>
                    <table>
                        <tr>
                        <td style='font-size:12px;border:0px solid #ffffff;'><b>PO NO.</b></td>
                        <td style='font-size:12px;border:0px solid #ffffff;'>:</td>
                        <td style='width:250px;font-size:12px;border:0px solid #ffffff;'>".$dt_h->PO_NO."</td>
                        </tr>
                        <tr>
                        <td style='font-size:12px;border:0px solid #ffffff;'><b>PO DATE</b></td>
                        <td style='font-size:12px;border:0px solid #ffffff;'>:</td>
                        <td style='width:50px;font-size:12px;border:0px solid #ffffff;'>".$dt_h->PO_DATE."</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign='top' style='font-size:10px;width:350px;height:25px;'>
                    <table>
                        <tr>
                        <td style='border:0px solid #ffffff;'><b>SHIP TO</b></td>
                        <td style='border:0px solid #ffffff;'>:</td>
                        <td style='border:0px solid #ffffff;'>PT RAYOVAC BATTERY INDONESIA</td>
                        </tr>
                        <tr>
                        <td style='border:0px solid #ffffff;'></td>
                        <td style='border:0px solid #ffffff;'></td>
                        <td style='border:0px solid #ffffff;'>Kawasan Industri MM 2100, Blok MM-1 Jatiwangi,</td>
                        </tr>
                        <tr>
                        <td style='border:0px solid #ffffff;'></td>
                        <td style='border:0px solid #ffffff;'></td>
                        <td style='border:0px solid #ffffff;'>Cikarang Barat, Bekasi, Jawa Barat 17520 - Indonesia</td>
                        </tr>
                    </table>
                </td>
                <td style='border-top:5px solid #ffffff;border-bottom:5px solid #ffffff;'></td>
                <td valign='top' style='font-size:10px;width:350px;height:25px;'>
                    <table>
                        <tr>
                        <td style='border:0px solid #ffffff;'><b>REQUESTOR</b></td>
                        <td style='border:0px solid #ffffff;'>:</td>
                        <td style='border:0px solid #ffffff;'>".$dt_h->PERSON_CODE."</td>
                        </tr>
                        <tr>
                        <td style='border:0px solid #ffffff;'><b>COUNTRY OF ORIGIN</b></td>
                        <td style='border:0px solid #ffffff;'>:</td>
                        <td style='border:0px solid #ffffff;'>".$dt_h->COUNTRY."</td>
                        </tr>
                        <tr>
                        <td style='border:0px solid #ffffff;'><b>SHIPMENT</b></td>
                        <td style='border:0px solid #ffffff;'>:</td>
                        <td style='border:0px solid #ffffff;'>".$trans."</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign='top' style='font-size:10px;width:350px;height:25px;'>
                    <table>
                        <tr>
                        <td style='border:0px solid #ffffff;'><b>PAYMENT TERMS</b></td>
                        <td style='border:0px solid #ffffff;'>:</td>
                        <td style='width:170px;border:0px solid #ffffff;'>".$dt_h->PBY." ".$dt_h->PDAYS." ".$dt_h->PDESC."</td>
                        </tr>
                    </table>
                </td>
                <td style='border-top:5px solid #ffffff;border-bottom:5px solid #ffffff;'></td>
                <td valign='top' style='font-size:10px;width:350px;height:25px;'>
                    <table>
                        <tr>
                        <td style='border:0px solid #ffffff;'><b>TRADE TERMS</b></td>
                        <td style='border:0px solid #ffffff;'>:</td>
                        <td style='border:0px solid #ffffff;'>".$dt_h->TRADE_TERM."</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table border=1 align='center' style='width:100%;font-size:12px;'>
            <tr>
                <th valign='middle' align='center' style='font-size:12px;width:30px;height:25px;'>NO</th>
                ".$t_h."
                <th valign='middle' align='center' style='font-size:12px;width:60px;height:25px;'>E.T.A</th>
                <th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>QTY</th>
                <th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>UNIT PRICE<br/>".$dt_h->CURR_SHORT."</th>
                <th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>AMOUNT</th>
            </tr>
        </table>
    </page_header>
        
    <table border=1 align='center' style='width:100%;font-size:12px;margin-top:5px;'>";

    $total=0;
    while ($data=sqlsrv_fetch_object($result)){
        $content .= "
        <tr>
            <td valign='middle' align='center' style='font-size:12px;width:30px;height:25px;'>NO</td>
            ".$t_h."
            <td valign='middle' align='center' style='font-size:12px;width:60px;height:25px;'>E.T.A</td>
            <td valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>QTY</td>
            <td valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>UNIT PRICE<br/>".$dt_h->CURR_SHORT."</td>
            <td valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>AMOUNT</td>
        </tr>
        ";
    }

    $content .= "
    </table>

    <page_footer> 
        <div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>
            page [[page_cu]] of [[page_nb]]
        </div>
    </page_footer> 
    <br/><br/><br/><br/>
    <HR>
</page>";

$content .= "";


require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('PO-COBA.pdf');
// echo  $content;
?>