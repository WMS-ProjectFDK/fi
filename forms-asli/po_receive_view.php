<?php
ini_set('memory_limit', '-1');
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

// $date_awal=isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
// $date_akhir=isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
// $ck_date=isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
// $date_sts = isset($_REQUEST['date_sts']) ? strval($_REQUEST['date_sts']) : '';     

$cmb_po=isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
$ck_po=isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';

$supplier=isset($_REQUEST['supplier']) ? strval($_REQUEST['supplier']) : '';

$ck_supplier=isset($_REQUEST['ck_supplier']) ? strval($_REQUEST['ck_supplier']) : '';

$cmb_item_no=isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$ck_item_no=isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';

$endorder=isset($_REQUEST['endorder']) ? strval($_REQUEST['endorder']) : '';

// if($ck_date!='true'){
//     if($date_sts== 'check_eta'){
//         $dt = "aa.eta between to_date('$date_awal','yyyy-mm-dd') AND to_date('$date_akhir','yyyy-mm-dd') AND ";
//     }elseif($date_sts== 'check_po'){
//         $dt = "aa.po_date between to_date('$date_awal','yyyy-mm-dd') AND to_date('$date_akhir','yyyy-mm-dd') AND ";
//     }
// }else{
//     $dt = "";
// }

if($ck_po!='true'){
    $po = "aa.po_no = '$cmb_po' AND ";
}else{
    $po = "";
}

if($ck_supplier!='true'){
    $supp = "aa.supplier_code = '$supplier' AND ";
}else{
    $supp = "";
}

if($ck_item_no!='true'){
    $itm = "aa.item_no = '$cmb_item_no' AND ";
}else{
    $itm = "";
}

if($endorder!='true'){
    $order = "aa.statuspo = 1 ";
}else{
    $order = "(aa.statuspo = 0 OR aa.statuspo = 1)";
}

$where = "where $dt $po $supp $itm $order ";

$result = array();

$qry = "select * from (select r.po_no, s.line_no, r.po_date, r.supplier_code, cc.company, r.remark1, s.item_no, ii.description,
       s.qty, s.bal_qty, s.gr_qty, s.eta, tt.slip_no, tt.slip_quantity, tt.slip_date, 
       case when qty > gr_qty then 1 else 0 end StatusPO
       from po_header r
       inner join po_details s on r.po_no = s.po_no
       inner join company cc on r.supplier_code = cc.company_code
       inner join (select item_no,description from item) ii on s.item_no = ii.item_no
       left outer join (select order_number,line_no, slip_no,slip_quantity,slip_date from transaction  where order_number is not null)tt on s.po_no = tt.order_number and s.line_no = tt.line_no
       ) aa
       $where
       order by po_no,po_date,line_no, slip_date asc";
$data = oci_parse($connect, $qry);
  oci_execute($data);
  $JumlahData = 0;
  $items = array();
  $rowno=0;
  while($row = oci_fetch_object($data)){
    array_push($items, $row);
    
    $slip_quantity = $items[$rowno]->SLIP_QUANTITY;
    $items[$rowno]->SLIP_QUANTITY = number_format($slip_quantity);

    $qty = $items[$rowno]->QTY;
    $items[$rowno]->QTY = number_format($qty);

    $bal_qty = $items[$rowno]->BAL_QTY;
    $items[$rowno]->BAL_QTY = number_format($bal_qty);

    $gr_qty = $items[$rowno]->GR_QTY;
    $items[$rowno]->GR_QTY = number_format($gr_qty);

    $gr_qty = $items[$rowno]->GR_QTY;
    $items[$rowno]->GR_QTY = number_format($gr_qty);

    $po_no = $items[$rowno]->PO_NO;
    $line_no = $items[$rowno]->LINE_NO;
    $po_line = $po_no.$line_no
    
    

    if ($JumlahData > 1 )
    {
       $items[$rowno]->GR_QTY = number_format($gr_qty);

    }


    $JumlahData++;
    if ($JumlahData >= 100){
      break;
    }
  }
  $result["rows"] = $items;
  echo json_encode($result);

// $date=date("d M y / H:i:s",time());

?>