<?php 
// error_reporting(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$result = array();

$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';;
//350/FILR/20
$state= isset($_REQUEST['state']) ? strval($_REQUEST['state']) : '';
// delivery_slip

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

$sql = "select 
    fl.customer_code,
    fl.do_no,
    fl.transport,
    fl.slip_no,
    fl.booking_no,
    com1.company own_company,
    isnull(Len(com1.address1),0) + isnull(Len(com1.address2),0) own_com_length,
    com1.address1 own_company_address1,
    com1.address2 own_company_address2,
    com1.address3 own_company_address3,
    com1.address4 own_company_address4,
    com2.company  customer,
    fwd.forwarder,
    truck.forwarder domestic_truck,
    CONVERT(varchar,doh.do_date, 103) invoice_date,
    pld.customer_part_no,
    isnull(pld.inner_package,0) * isnull(plh.case_total,0)  inner_package,
    pld.inner_quantity,
    pld.fdk_part,
    CASE sign(pld.inner_package * plh.case_total -1)
        WHEN null THEN null
        WHEN 1 THEN u1.unit_pl
        ELSE u1.unit END as inner_uom_p,

    CASE sign(abs(pld.inner_quantity)-1)
        WHEN 1 THEN u2.unit_pl
        ELSE u2.unit END as inner_uom_q,
    doh.final_destination  final_destination,
    doh.port_discharge as discharge_port,

    CASE cm1.description
        WHEN null THEN null
        WHEN 'LCL' THEN 'LCL'
        ELSE cm1.description + ' '  + cs1.description + '~' + CAST(CAST(fl.cargo_qty1 AS INT) AS VARCHAR) + ' CONTAINER(S) ' END as container_info1, 

    CASE cm2.description 
        WHEN null THEN null
        ELSE cm2.description + ' '  + cs2.description + '~' + CAST(CAST(fl.cargo_qty2 AS INT) AS VARCHAR) + ' CONTAINER(S) ' END as container_info2
    from forwarder_letter fl
    left join company com1 on fl.customer_code = com1.company_code
    left join company com2 on fl.customer_code = com2.company_code
    left join forwarder fwd on fl.forwarder_code = fwd.forwarder_code
    left join forwarder truck on fl.domestic_truck_code = truck.forwarder_code
    left join do_header doh on fl.do_no = doh.do_no AND fl.customer_code = doh.customer_code
    left join do_pl_header plh on doh.do_no = plh.do_no
    left join do_pl_details pld on plh.do_no = pld.do_no AND plh.pl_line_no = pld.pl_line_no
    left join unit u1 on pld.inner_uom_p = u1.unit_code
    left join unit u2 on pld.uom_q = u2.unit_code
    left join cargo_method cm1 on fl.cargo_type1 = cm1.method_type
    left join cargo_size cs1 on fl.cargo_size1 = cs1.size_type
    left join cargo_method cm2 on fl.cargo_type2 = cm2.method_type
    left join cargo_size cs2 on fl.cargo_size2 = cs2.size_type
    where fl.do_no In ('$do') 
    order by fl.domestic_truck_code, fl.do_no, pld.fdk_part";
$data_header = sqlsrv_query($connect, strtoupper($sql),$params, $options);
$row_count = sqlsrv_num_rows($data_header);
$dt_h = sqlsrv_fetch_object($data_header);

$SQL = " select distinct ";
for ($i=1; $i <= 8; $i++) { 
    if ($i>1){
        $SQL .= " union all select ";
    }

    $SQL .= " customer_po_no".$i."  customer_po_no from do_details 
              where do_no = '".$do."'
              and customer_po_no".$i." is not null ";
}
// echo $SQL;
$result_po = sqlsrv_query($connect, strtoupper($SQL), $params, $options);
$row_count_po = sqlsrv_num_rows($result_po);

$po = '';
if ($row_count_po > 0){
    while ($dt_po = sqlsrv_fetch_object($result_po)) {
        $po .= $dt_po->CUSTOMER_PO_NO."<br/>";
    }
}else{
    $po .= $dt_po->CUSTOMER_PO_NO;
}

// WEIGHT
$sql_weight = "select 
    CAST(sum(net) as decimal(14,2)) net,
    net_uom, u1.UNIT_PL as net_unit,
    CAST(sum(gross) as decimal(14,2)) gross,
    gross_uom, u2.UNIT_PL as gross_unit,
    CAST(sum(measurement) as decimal(14,3)) measurement 
    from do_pl_header a
    left join UNIT u1 on a.NET_UOM=u1.UNIT_CODE
    left join UNIT u2 on a.GROSS_UOM=u2.UNIT_CODE
    where do_no = '$do'
    group by NET_UOM, GROSS_UOM, u1.UNIT_PL, u2.UNIT_PL";
$result_weight = sqlsrv_query($connect, strtoupper($sql_weight));
$dt_weight = sqlsrv_fetch_object($result_weight);

// CARTON
$sql_carton = "SELECT * FROM ZVW_JUM_CARTON where do_no='$do'";
$result_carton = sqlsrv_query($connect, strtoupper($sql_carton));
$dt_carton = sqlsrv_fetch_object($result_carton);

//WO
$sql_wo = "select work_no, remark from answer 
    where answer_no in (select answer_no 
                        from indication 
                        where inv_no = '$do') 
    order by item_no, work_no";
$result_wo = sqlsrv_query($connect, strtoupper($sql_wo),$params, $options);
$row_count_wo = sqlsrv_num_rows($result_wo);

$wo = '';
if ($row_count_wo > 0){
    while ($dt_wo = sqlsrv_fetch_object($result_wo)) {
        $wo .= $dt_wo->WORK_NO.", ";
    }
}else{
    $wo .= $dt_wo->WORK_NO;
}

// DETAILS
$qry = "select 
    fl.customer_code,
    fl.do_no,
    fl.transport,
    fl.slip_no,
    fl.booking_no,
    com1.company own_company,
    isnull(Len(com1.address1),0) + isnull(Len(com1.address2),0) own_com_length,
    com1.address1 own_company_address1,
    com1.address2 own_company_address2,
    com1.address3 own_company_address3,
    com1.address4 own_company_address4,
    com2.company  customer,
    fwd.forwarder,
    truck.forwarder domestic_truck,
    CAST(doh.do_date as varchar(10)) invoice_date,
    pld.customer_part_no,
    isnull(pld.inner_package,0) * isnull(plh.case_total,0)  inner_package,
    pld.inner_quantity,
    pld.fdk_part,
    CASE sign(pld.inner_package * plh.case_total -1)
        WHEN null THEN null
        WHEN 1 THEN u1.unit_pl
        ELSE u1.unit END as inner_uom_p,

    CASE sign(abs(pld.inner_quantity)-1)
        WHEN 1 THEN u2.unit_pl
        ELSE u2.unit END as inner_uom_q,
    doh.final_destination  final_destination,
    doh.port_discharge as discharge_port,

    CASE cm1.description
        WHEN null THEN null
        WHEN 'LCL' THEN 'LCL'
        ELSE cm1.description + ' '  + cs1.description + '~' + CAST(fl.cargo_qty1 AS VARCHAR) + 'CONTAINER(S) ' END as container_info1, 

    CASE cm2.description 
        WHEN null THEN null
        ELSE cm2.description + ' '  + cs2.description + '~' + CAST(fl.cargo_qty2 AS VARCHAR) + 'CONTAINER(S) ' END as container_info2
    from forwarder_letter fl
    left join company com1 on fl.customer_code = com1.company_code
    left join company com2 on fl.customer_code = com2.company_code
    left join forwarder fwd on fl.forwarder_code = fwd.forwarder_code
    left join forwarder truck on fl.domestic_truck_code = truck.forwarder_code
    left join do_header doh on fl.do_no = doh.do_no AND fl.customer_code = doh.customer_code
    left join do_pl_header plh on doh.do_no = plh.do_no
    left join do_pl_details pld on plh.do_no = pld.do_no AND plh.pl_line_no = pld.pl_line_no
    left join unit u1 on pld.inner_uom_p = u1.unit_code
    left join unit u2 on pld.uom_q = u2.unit_code
    left join cargo_method cm1 on fl.cargo_type1 = cm1.method_type
    left join cargo_size cs1 on fl.cargo_size1 = cs1.size_type
    left join cargo_method cm2 on fl.cargo_type2 = cm2.method_type
    left join cargo_size cs2 on fl.cargo_size2 = cs2.size_type
    where fl.do_no In ('$do') 
    order by fl.domestic_truck_code, fl.do_no, pld.fdk_part";
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
			<img src='../../images/logo-print4.png' alt='#' style='width:270px;height: 35px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:940px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
			<span style='width:70px;height:35px;margin-left:0px;'>
				<qrcode value='".$do."' ec='Q' style=' border:none; width:40px;background-color: white; color: black;'></qrcode>
			</span>
		</div>

        <page_footer>
            <div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
        </page_footer>
	
        <div style='margin-top:30px;position:absolute;'>
            <h2 align='center'>DELIVERY SLIP<BR/>(SURAT JALAN)</h2>
            <table align='center'>
                <thead>
                <tr>
                    <td colspan=4 style='border-top:0px solid #ffffff;border-left:0px solid #ffffff;border-right:0px solid #ffffff;'>
                        <table align='center'>
                            <tr>
                                <td style='border:0px solid #ffffff;width:100px;'>MESSRS</td>
                                <td style='border:0px solid #ffffff;width:230px;'>: ".$dt_h->OWN_COMPANY."</td>
                                <td style='border:0px solid #ffffff;width:10px;'></td>
                                <td style='border:0px solid #ffffff;width:100px;'>SLIP NO.</td>
                                <td style='border:0px solid #ffffff;width:225px;'>: ".$dt_h->SLIP_NO."</td>
                                <td style='border:0px solid #ffffff;width:10px;'></td>
                                <td style='border:0px solid #ffffff;width:100px;'></td>
                                <td style='border:0px solid #ffffff;width:225px;'></td>
                            </tr>
                            <tr>
                                <td style='border:0px solid #ffffff;width:100px;'>FORWARDER</td>
                                <td style='border:0px solid #ffffff;width:230px;'>: ".$dt_h->FORWARDER."</td>
                                <td style='border:0px solid #ffffff;width:10px;'></td>
                                <td style='border:0px solid #ffffff;width:100px;'>INVOICE NO.</td>
                                <td style='border:0px solid #ffffff;width:225px;'>: ".$dt_h->DO_NO."</td>
                                <td style='border:0px solid #ffffff;width:10px;'></td>
                                <td style='border:0px solid #ffffff;width:150px;'>TRUCK NO. / DRIVER</td>
                                <td style='border:0px solid #ffffff;width:225px;'></td>
                            </tr>
                            <tr>
                                <td style='border:0px solid #ffffff;width:100px;'>TRUCKING</td>
                                <td style='border:0px solid #ffffff;width:230px;'>: ".$dt_h->DOMESTIC_TRUCK."</td>
                                <td style='border:0px solid #ffffff;width:10px;'></td>
                                <td style='border:0px solid #ffffff;width:100px;'>INVOICE DATE</td>
                                <td style='border:0px solid #ffffff;width:225px;'>: ".$dt_h->INVOICE_DATE."</td>
                                <td style='border:0px solid #ffffff;width:10px;'></td>
                                <td style='border:0px solid #ffffff;width:150px;'>CONT. /SEAL NO.</td>
                                <td style='border:0px solid #ffffff;width:225px;'>: </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                </thead>
               ";
$nourut = 1;
$content .= "
             
                <tr>
                    <th valign='middle' align='center' style='font-size:10px;width:120px;height:25px;'>CUSTOMER PART NO.</th>
                    <th valign='middle' align='center' style='font-size:10px;width:280px;height:25px;'>ITEM NAME</th>
                    <th valign='middle' align='center' style='font-size:10px;width:200px;height:25px;'>QUANTITY</th>
                    <th valign='middle' align='center' style='font-size:10px;width:300px;height:25px;'>REMARK</th>
                </tr>
                    ";
$no=1;                    
while ($data=sqlsrv_fetch_object($result)){
    // echo $data->FDK_PART;
    $content .= "
                    <tr>
                        <td valign='top' align='right' style='font-size:10px;width:120px;padding: 10px 10px;'>
                            ".$data->CUSTOMER_PART_NO."
                        </td>
                        <td valign='top' align='left' style='font-size:10px;width:280px;padding: 10px 10px;'>
                            ".$data->FDK_PART."
                        </td>
                        <td valign='top' align='left' style='font-size:10px;width:200px;padding: 10px 10px;'>
                            ".$data->INNER_PACKAGE.$data->INNER_UOM_P."@".$data->INNER_QUANTITY.$data->INNER_UOM_Q."
                        </td>";

    if ($no==1){
        $content .= "
                        <td rowspan=".$row_count." valign='top' align='left' style='font-size:10px;width:300px;padding: 10px 10px; border-bottom:0px solid #ffffff;'>
                            GROSS WEIGHT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".number_format($dt_weight->GROSS,2)." ".$dt_weight->GROSS_UNIT."<br/>
                            NET WEIGHT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".number_format($dt_weight->NET,2)." ".$dt_weight->NET_UNIT."<br/>
                            MEASUREMENT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".number_format($dt_weight->MEASUREMENT,3)." M3
                            <br/><br/>
                            PO NO. ".$po." <br/>
                            CONTAINER NO. 

                            <br/>
                            <br/>
                            <br/>
                            <br/>
                            FINAL DESTINATION : ".$dt_h->FINAL_DESTINATION." <br/>
                            PORT OF DISCHARGE : ".$dt_h->DISCHARGE_PORT." <br/>
                            SHIPMENT METHOD : ".$dt_h->TRANSPORT."<br/>
                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                              ".$dt_h->CONTAINER_INFO1." <br/>
                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                  
                                              ".$dt_h->CONTAINER_INFO2." <br/>
                        </td>";
    }
    $content .= "
                    </tr>
    ";
    $no++;
}
$content .= "
                    <tr>
                        <td colspan=3 style='padding: 10px 10px;'>
                            ".$dt_carton->PALLET_QTY."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            ".$dt_carton->CARTON_QTY."
                        </td>
                        <td style='border-top:0px solid #ffffff;padding: 10px 10px;'>BOOKING NO: ".$dt_h->BOOKING_NO."</td>
                    </tr>
                    <tr>
                        <td colspan=4 style='padding: 10px 10px;'>WORK NO. : ".$wo."</td>
                    </tr>
                    <tr>
                        <td colspan=4 style='padding: 10px 10px;'>PALLET NO. : </td>
                    </tr>
                    <tr>
                        <td colspan=4>
                            <table border=0>
                                <tr>
                                    <td align='center' style='font-size:10px;width:205px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;border-left:0px solid #ffffff;'>CARGO RECEIVE BY</td>
                                    <td align='center' style='font-size:10px;width:205px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'>CARGO RECEIVE BY</td>
                                    <td align='center' style='font-size:10px;width:205px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'>CUSTOMER RECEIVED</td>
                                    <td align='center' style='font-size:10px;width:205px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'>CARGO RELEASED BY</td>
                                    <td align='center' style='font-size:10px;width:205px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;border-right:0px solid #ffffff;'>ISSUED BY</td>
                                </tr>
                                <tr>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;height:50px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;border-left:0px solid #ffffff;'></td>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;height:50px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'></td>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;height:50px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'></td>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;height:50px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'></td>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;height:50px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;border-right:0px solid #ffffff;'></td>
                                </tr>
                                <tr>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;border-left:0px solid #ffffff;'>TRUCKING COMPANY</td>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'>FORWARDER</td>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'></td>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'>WAREHOUSE</td>
                                    <td valign= 'bottom' align='center' style='font-size:10px;width:200px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;border-right:0px solid #ffffff;'>S C M</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=4 style='font-size:8px;border:0px solid #ffffff;'>
                            <i>
                            WHITE ORIGINAL:CUSTOMER &nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            BLUE:EMKL
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            RED:WAREHOUSE 
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            GREEN:ACCOUNTING,SALES DOC
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </i>
                        </td>
                    </tr>
";

$content .= "
            </table>
        </div>
    </page>";

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('DELIVERY-SLIP('.$do.'.pdf');	
// echo  $content;
?>