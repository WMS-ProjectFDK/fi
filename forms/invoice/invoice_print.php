<?php 
//error_reporting(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';
$state = isset($_REQUEST['state']) ? strval($_REQUEST['state']) : '';

if($state == 'packing_list'){
    $name = "PACKING LIST";
}else{
    $name = "INVOICE";
}

$sql_h = "select doh.do_no, doh.inv_no, CAST(doh.inv_date as varchar(10)) inv_date, doh.ship_name, doh.revise_flg, doh.remark, 
    doh.notify, doh.attn  h_attn, doh.address_flg, c1.company, c1.address1, c1.address2, c1.address3, c1.address4,
    c1.attn  com_attn, doh.port_loading port_of_loading, doh.port_discharge port_of_discharge, doh.final_destination,
    CAST(doh.etd as varchar(10)) etd, CAST(doh.eta as varchar(10)) eta, doh.trade_term, doh.lc_no,
    CAST(doh.lc_date as varchar(10)) lc_date, doh.issuing_bank, 
    CAST(doh.due_date as varchar(10)) due_date, doh.pby, doh.pdays, doh.pdesc 
    from do_header doh,
    company c1 
    where doh.do_no = '$do'
    and doh.customer_code = c1.company_code";
$head = sqlsrv_query($connect, strtoupper($sql_h));
$dt_h = sqlsrv_fetch_object($head);

// MARKS
$sql_marks = "select dm2.max_value  max_no, dm1.mark_no, dm1.MARKS 
    from do_marks  dm1,
    (select max(mark_no) max_value,do_no from do_marks group by do_no) dm2 
    where dm1.do_no = dm2.do_no
    and dm1.do_no = '$do'
    order by mark_no";
$dt_marks = sqlsrv_query($connect, strtoupper($sql_marks));
$row_count = sqlsrv_num_rows($dt_marks);
if ($row_count > 0){
    while($dt_m =sqlsrv_fetch_object($dt_marks)){
        $marks = $dt_m->MARKS;
    }
}else{
    $marks = '';
}

// TOTAL GW,NW,MSM
$sql_gw = "select case when d1.total_net = NULL then '0' else LTrim(CAST(d1.total_net as varchar)) end as total_net,
    u1.unit as net_unit,
    case when d1.total_gross = NULL then '0' else LTrim(CAST(d1.total_gross as varchar)) end as total_gross,
    u2.unit as gross_unit,
    LTrim(CAST(d1.total_measurement as varchar)) total_measurement 
    from 
    (select sum(isnull(h.net,0)) total_net,net_uom, 
            sum(isnull(h.gross,0)) total_gross,gross_uom, 
            sum(isnull(h.measurement,0)) total_measurement 
    from do_pl_header h, 
        do_pl_details d 
    where h.do_no = '$do'
    and h.do_no = d.do_no 
    and h.pl_line_no = d.pl_line_no 
    group by h.gross_uom, h.net_uom) d1, 
    unit  u1,
    unit  u2 
    where d1.net_uom   = u1.unit_code
    and d1.gross_uom = u2.unit_code";
$data_gw = sqlsrv_query($connect, strtoupper($sql_gw));
$dt_gw =sqlsrv_fetch_object($data_gw);

// COUNTRY
$sql_cou = "select distinct c.country country
    from country c, item i, do_details dod 
    where dod.origin_code = c.country_code 
    and dod.item_no = i.item_no
    and dod.origin_code = i.origin_code
    and dod.do_no = '$do'";
$data_cou = sqlsrv_query($connect, strtoupper($sql_cou));
$dt_cou = sqlsrv_fetch_object($data_cou);

if($state != 'packing_list'){
    $qry = "select  doh.customer_code, doh.do_no, doh.inv_no, CAST(doh.inv_date as varchar(10)) inv_date, doh.ship_name,
        doh.SHIP_NAME as shipped_per1, dod.ITEM_NO,
        --substring(doh.ship_name,1,dbo.INSTR(doh.ship_name,char(13)+char(10),1,1)-1)  shipped_per1, 
        --substring(doh.ship_name,dbo.INSTR(doh.ship_name,char(13)+char(10),1,1)+2) shipped_per2, 
        doh.remark, doh.notify, doh.attn  h_attn, 
        --decode(isnull(dod.customer_part_no,''),'','','(' + dod.customer_part_no + ')')  customer_part_no, 
        dod.qty, dod.amt_o  amount, dod.u_price  unit_price, dod.remark2, dod.carved_stamp, doh.address_flg, c1.company, 
        c1.address1, c1.address2, c1.address3, c1.address4, c1.attn  com_attn, doh.port_loading port_of_loading, 
        doh.port_discharge port_of_discharge, doh.final_destination, CAST(doh.etd as varchar(10)) etd, 
        CAST(doh.eta as varchar(10)) eta, doh.trade_term, doh.lc_no, CAST(doh.lc_date as varchar(10)) lc_date, doh.issuing_bank, 
        CAST(doh.due_date as varchar(10)) due_date, doh.pby,
        doh.pdays, doh.pdesc, doh.revise_flg, dod.description  class, dod.line_no, i.description   item, i.item   item_name, 
        i.customer_item_name + case   when i.customer_item_no is null then  ''  else '(' + i.customer_item_no + ')' end as customer_item,
        u.unit, u.unit_pl, u2.unit customer_unit, u2.unit_pl  customer_unit_pl, cur.curr_mark, doh.gst_amt_l, 
        case when dat.carved_stamp_count=1 then 'PRINT' else '' end carved_stamp_type,
        bk.BANK, bk.ADDRESS1 as BANK_ADDR1, bk.ADDRESS2 as BANK_ADDR2, bk.ACCOUNT_NO,
        dod.CUSTOMER_PO_NO1,
        dod.qty,
        round(dod.qty / isnull(i.package_unit_number,1),0)  customer_package,
        round(isnull(dod.u_price,0) * isnull(i.package_unit_number,1),6) customer_u_price
        from  do_header  doh, 
        do_details dod, 
        company c1, 
        item i, 
        unit u, 
        unit u2, 
        currency cur, 
        country cou, 
        (select  do_no, count(*)  data_count, sum(case  when carved_stamp is null then 0 else 1 end) carved_stamp_count
        from do_details
        where do_no = '$do'
        group by do_no) dat,
        (select * from BANK where BANK_SEQ <> 1 and delete_flag is null) bk
        where doh.do_no = '$do'
        and doh.do_no = dod.do_no
        and doh.customer_code = c1.company_code
        and dod.item_no = i.item_no
        and dod.item_no is not null
        and dod.origin_code = i.origin_code
        and dod.origin_code = cou.country_code
        and i.uom_q = u.unit_code
        and i.unit_package = u2.unit_code
        and doh.curr_code = cur.curr_code
        and doh.do_no = dat.do_no
        and doh.curr_code = bk.curr_code
        order by dod.line_no";
}else{
    $qry = "select h.do_no, h.pl_line_no, d1.counter, h.description  class, h.case_no,
        RTrim(LTrim(CAST(h.qty as varchar))) total_qty, u1.unit_pl as total_qty_uom,
        RTrim(LTrim(CAST(h.case_total as varchar))) case_total, u2.unit_pl uom_p,
        LTrim(RTrim(CAST(h.net as varchar))) net, CAST(h.net as varchar)  w_net, u5.unit_pl  net_uom,
        LTrim(RTrim(CAST(h.gross as varchar))) gross, u3.unit_pl gross_uom, 
        h.measurement, isnull(RTrim(LTrim(CAST(d.qty as varchar))),0)  case_qty, u4.unit_pl case_uom_q,
        isnull(d.inner_quantity,0)  inner_quantity, isnull(d.inner_package * h.case_total,0) inner_package, u6.unit_pl inner_uom_p
        from do_pl_header h,
        do_pl_details d,
        (select pl_line_no,isnull(count(pl_line_no),0) counter from do_pl_details where do_no = '$do' group by pl_line_no) d1,
        unit  u1,
        unit  u2,
        unit  u3,
        unit  u4,
        unit  u5,
        unit  u6 
        where h.do_no = '$do'
        and h.do_no = d.do_no 
        and h.pl_line_no = d.pl_line_no 
        and h.pl_line_no = d1.pl_line_no 
        and d.uom_q = u1.unit_code
        and h.uom_p = u2.unit_code
        and h.gross_uom = u3.unit_code
        and d.uom_q = u4.unit_code
        and h.net_uom = u5.unit_code
        and d.inner_uom_p = u6.unit_code
        order by h.do_no,h.pl_line_no";
}

$result = sqlsrv_query($connect, strtoupper($qry));

$date=date("d M y / H:i:s",time());
$content = "	
	<style>
		table {
			border-collapse: collapse;
			padding:4px;
			font-size:11px;
		}
		table, th, td {
			border: 1px solid #d0d0d0;	
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
			<img src='../../images/logo-print4.png' alt='#' style='width:300px;height: 70px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:620px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>
			page [[page_cu]] of [[page_nb]]
		</div>
    </page_footer>
	
	<div style='margin-top:20px;margin-bottom:100%;position:absolute;'>
		<h2 align='center'>".$name."<br></h2>
		<table align='left' style='width:800px;font-size:12px;'>
			<tr>
				<td valign='top' style='font-size:10px;width:400px;height:25px;'>
					<table>
			            <tr>
			              <td colspan=2 style='font-size:12px;border:0px solid #ffffff;'><b>SOLD TO :</b></td>
                        </tr>
                        <tr>
                            <td style='width:10px;font-size:12px;border:0px solid #ffffff;'></td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".$dt_h->COMPANY."</td>
                        </tr>
                        <tr>
                            <td style='width:10px;font-size:12px;border:0px solid #ffffff;'></td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".wordwrap($dt_h->ADDRESS1, 36, '<br />', true)."</td>
                        </tr>
                        <tr>
                            <td style='width:10px;font-size:12px;border:0px solid #ffffff;'></td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".wordwrap($dt_h->ADDRESS2, 36, '<br />', true)."</td>
                        </tr>
                        <tr>
                            <td style='width:10px;font-size:12px;border:0px solid #ffffff;'></td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".wordwrap($dt_h->ADDRESS3, 36, '<br />', true)."</td>
                        </tr>
                        <tr>
                            <td style='width:10px;font-size:12px;border:0px solid #ffffff;'></td>
                            <td style='width:330px;font-size:12px;border:0px solid #ffffff;'>".wordwrap($dt_h->ADDRESS4, 36, '<br />', true)."</td>
                        </tr>
			        </table>
			    </td>
				<td style='border-top:5px solid #ffffff;border-bottom:5px solid #ffffff;'></td>
				<td valign='top' style='font-size:10px;width:320px;height:25px;'>
					<table>
			            <tr>
			              <td style='font-size:12px;border:0px solid #ffffff;width:80px;'><b>INVOICE NO.</b></td>
			              <td style='font-size:12px;border:0px solid #ffffff;'>:</td>
			              <td style='width:250px;font-size:12px;border:0px solid #ffffff;width:200px;'>".$dt_h->INV_NO."</td>
			            </tr>
			            <tr>
			              <td style='font-size:12px;border:0px solid #ffffff;width:80px;'><b>DATE</b></td>
			              <td style='font-size:12px;border:0px solid #ffffff;'>:</td>
			              <td style='width:50px;font-size:12px;border:0px solid #ffffff;width:200px;'>".$dt_h->INV_DATE."</td>
                        </tr>
                        <tr>
			              <td style='font-size:12px;border:0px solid #ffffff;width:80px;'><b>PLACE</b></td>
			              <td style='font-size:12px;border:0px solid #ffffff;'>:</td>
			              <td style='width:50px;font-size:12px;border:0px solid #ffffff;width:200px;'>INDONESIA</td>
                        </tr>
                        <tr>
			              <td style='border:0px solid #ffffff;width:80px;'><b>SHIPPED PER</b></td>
			              <td style='border:0px solid #ffffff;'>:</td>
			              <td style='border:0px solid #ffffff;width:200px;'>".wordwrap($dt_h->SHIP_NAME, 30, '<br />', true)."</td>
                        </tr>
                        <tr>
			              <td style='border:0px solid #ffffff;width:80px;'><b>PORT OF LOADING</b></td>
			              <td style='border:0px solid #ffffff;'>:</td>
			              <td style='border:0px solid #ffffff;width:200px;'>".$dt_h->PORT_OF_LOADING."</td>
			            </tr>
			        </table>
				</td>
			</tr>
			<tr>
				<td valign='top' style='font-size:10px;width:400px;height:25px;'>
					<table>
			            <tr>
                          <td colspan=2 style='border:0px solid #ffffff;'><b>SHIPPED/CONSIGNED TO :</b></td>
                        </tr>
                        <tr>
                          <td style='border:0px solid #ffffff;width:10px;'></td>
                          <td style='border:0px solid #ffffff;'>".wordwrap($dt_h->NOTIFY, 30, '<br />', true)."</td>
                        </tr>
			        </table>
				</td>
				<td style='border-top:5px solid #ffffff;border-bottom:5px solid #ffffff;'></td>
				<td valign='top' style='font-size:10px;width:320px;height:25px;'>
                    <table>
                        
			            <tr>
			              <td style='border:0px solid #ffffff;width:80px;'><b>PORT OF DISCHARGE</b></td>
			              <td style='border:0px solid #ffffff;'>:</td>
			              <td style='border:0px solid #ffffff;width:200px;'>".$dt_h->PORT_OF_DISCHARGE."</td>
                        </tr>
                        <tr>
			              <td style='border:0px solid #ffffff;width:80px;'><b>FINAL DESTINATION</b></td>
			              <td style='border:0px solid #ffffff;'>:</td>
			              <td style='border:0px solid #ffffff;width:200px;'>".$dt_h->FINAL_DESTINATION."</td>
                        </tr>
                        <tr>
			              <td style='border:0px solid #ffffff;'><b>E.T.D : </b>".$dt_h->ETD."</td>
			              <td style='border:0px solid #ffffff;'>|</td>
			              <td style='border:0px solid #ffffff;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>E.T.A : </b>".$dt_h->ETA."</td>
                        </tr>
                        <tr>
			              <td style='border:0px solid #ffffff;width:80px;'><b>TRADE TERMS</b></td>
			              <td style='border:0px solid #ffffff;'>:</td>
			              <td style='border:0px solid #ffffff;width:200px;'>".$dt_h->TRADE_TERM."</td>
                        </tr>
			        </table>
				</td>
            </tr>
            <tr>
                <td colspan=3 valign='top' style='font-size:10px;'>
                    <table>
                      <tr>
                        <td style='width:150px;border:0px solid #ffffff;'><b>LC NO.:</b></td>
                        <td style='width:150px;border:0px solid #ffffff;'><b>LC DATE:</b></td>
                        <td style='width:150px;border:0px solid #ffffff;'><b>ISSUING BANK:</b></td>
                      </tr>
                      <tr>
                        <td style='width:150px;border:0px solid #ffffff;'>".$dt_h->LC_NO."</td>
                        <td style='width:150px;border:0px solid #ffffff;'>".$dt_h->LC_DATE."</td>
                        <td style='width:150px;border:0px solid #ffffff;'>".$dt_h->ISSUING_BANK."</td>
                      </tr>
                      <tr>
                        <td style='width:150px;border:0px solid #ffffff;'><b>DUE DATE:</b></td>
                        <td colspan=2 style='width:150px;border:0px solid #ffffff;'><b>PAYMENT:</b></td>
                      </tr>
                      <tr>
                        <td style='width:150px;border:0px solid #ffffff;'>".$dt_h->DUE_DATE."</td>
                        <td colspan=2 style='width:150px;border:0px solid #ffffff;'>".$dt_h->PBY." ".$dt_h->PDAYS." ".$dt_h->PDESC."</td>
                      </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan=3 valign='top' style='height:5px;border-left:0px solid #ffffff;border-right:0px solid #ffffff;'></td>
            </tr>";
if($state != 'packing_list'){
    $content .= "            
            <tr>
                <th colspan=3 valign='top'>
                    <table>
                    <tr>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:25px;height:25px;'>NO.</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:250px;height:25px;'>DESCRIPTION</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>DATE CODE</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>QUANTITY</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>UNIT PRICE</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>AMOUNT</th>
                    </tr>
                    </table>
                </th>
            </tr>";
}else{
    $content .= "            
            <tr>
                <th colspan=3 valign='top'>
                    <table>
                    <tr>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:25px;height:25px;'>C/NO.</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:250px;height:25px;'>DESCRIPTION</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>QUANTITY</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>NET<br/>WEIGHT</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>GROSS<br/>WEIGHT</th>
                        <th valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>MEASUREMENT</th>
                    </tr>
                    </table>
                </th>
            </tr>";
}

$no=1;
$bnk = '';          $bnk_addr1='';      $bnk_addr2='';      $account_no = '';
$tot_amount = 0;    $tot_qty = 0;       $pl = '';           $rmk='';
$tot_gw=0;          $tot_nw=0;
$uom_gw='';         $uom_nw='';

if($state != 'packing_list'){
    while ($data=sqlsrv_fetch_object($result)){
        $bnk = $data->BANK;
        $bnk_addr1 = $data->BANK_ADDR1;
        $bnk_addr2 = $data->BANK_ADDR2;
        $account_no = $data->ACCOUNT_NO;

        $tot_amount += $data->AMOUNT;
        $tot_qty += $data->QTY;
        $pl = $data->UNIT_PL;
        $rmk = $data->REMARK;

        $content .= "     
            <tr>
                <td colspan=3 valign='top'>
                    <table>
                    <tr>
                        <td valign='middle' align='center' style='font-size:12px;width:25px;height:25px;border:0px solid #ffffff;'>".$data->LINE_NO."</td>
                        <td valign='middle' align='left' style='font-size:12px;width:250px;height:25px;border:0px solid #ffffff;'>
                            <p><u>".$data->CLASS."</u><br/>".$data->ITEM." (".$data->ITEM_NO.")</p>
                            <p>CUSTOMER PACKAGE:<br/>P/O: ".$data->CUSTOMER_PO_NO1."</p>
                        </td>
                        <td valign='middle' align='center' style='font-size:12px;width:108px;height:25px;border:0px solid #ffffff;'>
                            <p>".$data->CARVED_STAMP."</p>
                            <p></p>
                        </td>
                        <td valign='middle' align='right' style='font-size:12px;width:108px;height:25px;border:0px solid #ffffff;'>
                            <p>".number_format(($data->QTY))." ".$data->UNIT_PL."</p>
                            <p>".number_format(($data->CUSTOMER_PACKAGE))." PKS</p>
                        </td>
                        <td valign='middle' align='right' style='font-size:12px;width:108px;height:25px;border:0px solid #ffffff;'>
                            <p>".number_format($data->UNIT_PRICE,6)."</p>
                            <p>".number_format($data->CUSTOMER_U_PRICE,6)."</p>
                        </td>
                        <td valign='middle' align='right' style='font-size:12px;width:108px;height:25px;border:0px solid #ffffff;'>
                            <p>".$data->AMOUNT."</p>
                            <p></p>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>";
    }
}else{
    while ($data=sqlsrv_fetch_object($result)){
        $tot_qty += $data->TOTAL_QTY;
        $pl = $data->TOTAL_QTY_UOM;

        $tot_amount += $data->MEASUREMENT;
        $tot_gw += $data->GROSS;
        $tot_nw += $data->NET;

        $uom_gw = $data->GROSS_UOM;
        $uom_nw = $data->NET_UOM;

        $content .= "     
            <tr>
                <td colspan=3 valign='top'>
                    <table>
                    <tr>
                        <td valign='middle' align='center' style='font-size:12px;width:25px;height:25px;border:0px solid #ffffff;'>
                            <p>".$data->CASE_NO."</p>
                        </td>
                        <td valign='middle' align='center' style='font-size:12px;width:250px;height:25px;border:0px solid #ffffff;'>
                            <p>( @".$data->CASE_QTY." ".$data->CASE_UOM_Q." X ".$data->INNER_PACKAGE." ".$data->INNER_UOM_P." )</p>
                        </td>
                        <td valign='middle' align='center' style='font-size:12px;width:108px;height:25px;border:0px solid #ffffff;'>
                            <p></p>
                        </td>
                        <td valign='middle' align='right' style='font-size:12px;width:108px;height:25px;border:0px solid #ffffff;'>
                            <p>".number_format($data->NET,2)." ".$data->NET_UOM."</p>
                        </td>
                        <td valign='middle' align='right' style='font-size:12px;width:108px;height:25px;border:0px solid #ffffff;'>
                            <p>".number_format($data->GROSS,2)." ".$data->GROSS_UOM."</p>
                        </td>
                        <td valign='middle' align='right' style='font-size:12px;width:108px;height:25px;border:0px solid #ffffff;'>
                            <p>".number_format($data->MEASUREMENT,3)." M3</p>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>";
    }
}

$content .=  "
            <tr>
                <td colspan=3 valign='top'>
                    <b>SHIPPING MARKS 1 :</b>
                    <br/>
                    ".str_replace(' ','<br/>',$marks)."
                </td>
            </tr>";

$content .=  "
            <tr>
                <td colspan=3 valign='top'>
                    <b>COUNTRY OF ORIGIN IS ".$dt_cou->COUNTRY." :</b>
                    <br/>
                    ".$marks."
                </td>
            </tr>";

if($state != 'packing_list'){
$content .=  "
            <tr>
                <td colspan=3 valign='top'>
                    <b>BANK DETAIL :</b> 
                    <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$bnk."\n
                    <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$bnk_addr1."\n
                    <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$bnk_addr2."
                    <br/>
                    <b>ACCOUNT NO.:</b> ".$account_no."
                </td>
            </tr>";
}
$content .=  "
            <tr>
                <td colspan=3 valign='top'>
                    <table>
                    <tr>
                        <td valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:25px;height:25px;'></td>
                        <td valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:250px;height:25px;'></td>
                        <td valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'></td>
                        <td valign='middle' align='right' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>
                            ".number_format($tot_nw,2)." ".$uom_nw."
                        </td>
                        <td valign='middle' align='right' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>
                            ".number_format($tot_gw,2)." ".$uom_gw."
                        </td>
                        <td valign='middle' align='right' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'>
                            ".number_format($tot_amount,3)." M3
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>";

$content .=  "
            <tr>
                <td colspan=3 valign='top'>
                    <table>
                    <tr>
                        <td valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:25px;height:25px;'></td>
                        <td valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:250px;height:25px;'></td>
                        <td valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'></td>
                        <td valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'></td>
                        <td valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'></td>
                        <td valign='middle' align='center' style='border:0px solid #ffffff;font-size:12px;width:108px;height:25px;'></td>
                    </tr>
                    <tr>
                        <td colspan=4 valign='top' style='border:0px solid #fffffffont-size:12px;width:30%;height:25px;'>";
                        if($state != 'packing_list'){
                            $content .= "
                            <table>
                              <tr>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'>
                                    TOTAL QUANTIYTY :        
                                </td>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'>
                                    ".number_format($tot_qty)." ".$pl."
                                </td>
                              </tr>
                              <tr>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'>
                                    TOTAL GROSS WEIGHT :
                                </td>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'>
                                    ".number_format($dt_gw->TOTAL_GROSS)." ".$dt_gw->GROSS_UNIT."
                                </td>
                              </tr>
                              <tr>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'>
                                    TOTAL MEASUREMENT : 
                                </td>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'>
                                    ".number_format($dt_gw->TOTAL_MEASUREMENT)." M3
                                </td>
                              </tr>  
                            </table>";
                        }else{
                            $content .= "
                            <table>
                              <tr>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'>
                                    TOTAL QUANTIYTY :        
                                </td>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'>
                                    ".number_format($tot_qty)." ".$pl."
                                </td>
                              </tr>
                              <tr>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'></td>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'></td>
                              </tr>
                              <tr>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'></td>
                                <td valign='middle' align='left' style='border:0px solid #ffffff;font-size:12px;width:150px;'></td>
                              </tr>  
                            </table>
                            
                            ";
                        }
                        
                            

                        $content .= "
                        </td>
                        <td colspan=2 valign='middle' align='center' style='border:0px solid #fffffffont-size:12px;width:70%;'>
                            PT FDK INDONESIA
                            <br/>
                            <br/>
                            <br/>
                            <p><u>___AGUSMAN SURYA___</u></p>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>";
$content .= "
        </table>
    </div>
</page>";

require_once('../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('INVOICE-'.$do.'.pdf');
// echo  $content;
?>