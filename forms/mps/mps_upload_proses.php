<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();

include ("../../class/excel_reader.php");
include ("../../connect/conn.php");

$user = $_SESSION['id_wms'];
$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);
$success = 0;       $failed = 0;
$now = Date('Y-m-d H:i:s');

 //Insert MPS_HEADER_RIREKI
$qry = "insert into MPS_HEADER_RIREKI 
         select ITEM_NO,ITEM_NAME,BATERY_TYPE,CELL_GRADE,PO_NO,PO_LINE_NO,WORK_ORDER,CONSIGNEE,PACKAGING_TYPE,DATE_CODE,CR_DATE,REQUESTED_ETD,STATUS,LABEL_ITEM_NUMBER,LABEL_NAME,QTY,MAN_POWER,OPERATEION_TIME,LABEL_TYPE,CAPACITY,cast(upload_date as datetime),REMARK,BOM_LEVEL,BOM_EDIT_STAT 
         from MPS_HEADER";
sqlsrv_query($connect, $qry);

 //Insert MPS_HEADER_RIREKI
$qry = "insert into MPS_DETAILS_RIREKI 
	select PO_NO,PO_LINE_NO,MPS_DATE,MPS_QTY,cast(upload_date as datetime) from MPS_DETAILS";
sqlsrv_query($connect, $qry);

//delete MPS_HEADER
$del1 = "delete from MPS_HEADER";
sqlsrv_query($connect, $del1);

//delete from MPS_DETAILS
$del2 = "delete from MPS_DETAILS";
sqlsrv_query($connect, $del2);

for($i=11;$i<=$hasildata;$i++){
    $a = trim($data->val($i,1));
    $b = trim($data->val($i,2));
    $c = trim($data->val($i,3));
    $d = trim($data->val($i,4));
    $e = trim($data->val($i,5));
    $f = trim($data->val($i,6));
    $g = trim($data->val($i,7));
    $h = trim($data->val($i,8));
    $ii = trim($data->val($i,9));
    $j = trim($data->val($i,10));
    $k = trim($data->val($i,11));
    $l = trim($data->val($i,12));
    $m = trim($data->val($i,13));
    $n = trim($data->val($i,14));
    $o = trim($data->val($i,15));
    $p = trim($data->val($i,16));
    $q = trim($data->val($i,17));
    $r = trim($data->val($i,18));
    $s = trim($data->val($i,19));
    $t = trim($data->val($i,20));
    $u = trim($data->val($i,21));
    
    if($k != ""){
        $kfix = "convert(date,'$k')";
    } else{
        $kfix = "''";
    }

    if($l != ""){
        $lfix = "convert(date,'$l')";
    } else{
        $lfix = "''";
    }
    
    if($data->val($i,1) == ""){
        break;
    }
    
    if($t == ""){
        $t = 0;
    }
    if($r == ""){
        $r = 0;
    }
    if($q == ""){
        $q = 0;
    }
    
    // INSERT MPS HEADER
    $field1 = "ITEM_NO,";                    $value1 = "$a,";
    $field1 .= "ITEM_NAME,";                 $value1 .= "LEFT('$b',30),";
    $field1 .= "BATERY_TYPE,";               $value1 .= "'$c',";
    $field1 .= "CELL_GRADE,";                $value1 .= "'$d',";
    $field1 .= "PO_NO,";                     $value1 .= "'$e',";
    $field1 .= "PO_LINE_NO,";                $value1 .= "'$f',";
    $field1 .= "WORK_ORDER,";                $value1 .= "'$g',";
    $field1 .= "CONSIGNEE,";                 $value1 .= "'$h',";
    $field1 .= "PACKAGING_TYPE,";            $value1 .= "'$ii',";
    $field1 .= "DATE_CODE,";                 $value1 .= "'$j',";
    $field1 .= "CR_DATE,";                   $value1 .= "$kfix,";     //DD/MM/YY
    $field1 .= "REQUESTED_ETD,";             $value1 .= "$lfix,";     //DD/MM/YY
    $field1 .= "STATUS,";                    $value1 .= "'$m',";
    $field1 .= "LABEL_ITEM_NUMBER,";         $value1 .= "'$n',";
    $field1 .= "LABEL_NAME,";                $value1 .= "'$o',";
    $field1 .= "QTY,";                       $value1 .= "$p,";
    $field1 .= "MAN_POWER,";                 $value1 .= "$q,";
    $field1 .= "OPERATEION_TIME,";           $value1 .= "$r,";        //DECIMAL
    $field1 .= "LABEL_TYPE,";                $value1 .= "'$s',";
    $field1 .= "CAPACITY,";                  $value1 .= "$t,";
    $field1 .= "UPLOAD_DATE,";               $value1 .= "'$now',";    //SYSDATE  'YYYY/MM/DD HH24:MI:SS'
    $field1 .= "REMARK";                     $value1 .= "'$u'";
    chop($field1);                           chop($value1);
    
    $ins1 = "INSERT INTO MPS_HEADER ($field1) VALUES ($value1)";
    $data_ins1 = sqlsrv_query($connect, $ins1);
    
    if(!$data_ins1) {
        printf($ins1);
		die(print_r(sqlsrv_errors(),true));
    };
    
    for($ix=22;$ix<200;$ix++){
       	$PO_NO = trim($data->val($i,5));
       	$PO_LINE_NO = trim($data->val($i,6));
       	$MPS_DATE = trim($data->val(4,$ix));
       	$MPS_QTY = trim($data->val($i,$ix));

        // INSERT MPS DETAILS
        $mps_date = trim($data->val($i, $xi));
        $field2 = "PO_NO,";                     $value2 =  "'$PO_NO',";
        $field2 .= "PO_LINE_NO,";               $value2 .= "'$PO_LINE_NO',";
        $field2 .= "MPS_DATE,";                 $value2 .= "convert(date,'$MPS_DATE',103),";
        $field2 .= "MPS_QTY,";                  $value2 .= "$MPS_QTY,";
        $field2 .= "UPLOAD_DATE";               $value2 .= "'$now'";
        
        if($MPS_QTY != ""){
            $ins2 = "insert into MPS_DETAILS (".$field2.") VALUES (".$value2.")";
            $data_ins2 = sqlsrv_query($connect, $ins2);
            
            if(!$data_ins2) {
                printf($ins2);
                $error = print_r(sqlsrv_errors(),true);
                die(print_r(sqlsrv_errors(),true));
            };
        }
    }
}

//UPDATE STATUS IN MPS TO 1
$qry = "update SO_DETAILS set IN_MPS = 1
where so_no in (select so_no from so_header inner join MPS_HEADER on SO_HEADER.CUSTOMER_PO_NO = MPS_HEADER.PO_NO
                             and exists(select * from MPS_DETAILS where po_no = MPS_HEADER.PO_NO))";
sqlsrv_query($connect, $qry);

$sql = "{call zsp_mps_remain}";
$stmt = sqlsrv_query($connect, $sql);

if( $stmt === false ) {
    $error = "sp error" ;
    die( print_r( sqlsrv_errors(), true));
}

echo json_encode("Upload MPS Success");
?>