<?php
session_start();
error_reporting(0);
include("../../connect/conn.php");
if (isset($_SESSION['id_wms'])){
    $user = $_SESSION['id_wms'];

    $plt = array('PALLET_MARK_1', 'PALLET_MARK_2', 'PALLET_MARK_3', 'PALLET_MARK_4', 'PALLET_MARK_5',
                 'PALLET_MARK_6', 'PALLET_MARK_7', 'PALLET_MARK_8', 'PALLET_MARK_9', 'PALLET_MARK_10'
                );

    $cs = array('CASE_MARK_1', 'CASE_MARK_2', 'CASE_MARK_3', 'CASE_MARK_4', 'CASE_MARK_5',
                'CASE_MARK_6', 'CASE_MARK_7', 'CASE_MARK_8', 'CASE_MARK_9', 'CASE_MARK_10'
               );

	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
    $msg = '';
    
    foreach($queries as $query){
        $so_sts = $query->so_sts;
        $so_cust = $query->so_cust;
        $so_date = $query->so_date;//2020-08-02,
        $so_so_no = $query->so_so_no;//FI-20-0577,
        $so_line_no = $query->so_line_no;
        $so_cust_po_no = $query->so_cust_po_no;//UENG-COBA,
        $so_consignee_code = $query->so_consignee_code;
        $so_consignee_name = $query->so_consignee_name;
        $so_curr = $query->so_curr;
        $so_rate = $query->so_rate;
        $so_country = $query->so_country;   //192-JAPAN,
        $so_item = $query->so_item; //88914,
        $so_price = $query->so_price;//.111300,
        $so_uom = $query->so_uom;//PC,
        $so_qty = $query->so_qty;
        $so_amount = $query->so_amount;//35.84
        $so_p_mark = $query->so_p_mark;
        $so_c_mark = $query->so_c_mark;
        $so_req_date = $query->so_req_date; 
        $so_ex_fact_date = $query->so_ex_fact_date;
        $so_aging_day = $query->so_aging_day;
        $so_date_code = $query->so_date_code;
        $so_po_line_no = $query->so_po_line_no;
        $so_asin = $query->so_asin;
        $so_amz_po_no = $query->so_amz_po_no;
        $so_category_mark = $query->so_category_mark;

        $amt_l = $so_amount * $so_rate;
        
        $pmark_f='';        $pmark_v='';
        $cmark_f='';        $cmark_v='';

        if ($so_sts == 'HEADER') {
            # INSERT PRF HEADER
            $field_so  = "customer_code,"   ; $value_so  = "'$so_cust',"                ;
            $field_so .= "so_no,"           ; $value_so .= "'$so_so_no',"               ;
            $field_so .= "so_date,"         ; $value_so .= "'$so_date',"                ;
            $field_so .= "curr_code,"       ; $value_so .= "$so_curr,"                  ;
            $field_so .= "ex_rate,"         ; $value_so .= "$so_rate,"                  ;
            $field_so .= "customer_po_no,"  ; $value_so .= "'$so_cust_po_no',"          ;
            $field_so .= "remark,"          ; $value_so .= "'$so_p_mark',"              ;
            $field_so .= "case_mark,"       ; $value_so .= "'$so_c_mark',"              ;
            $field_so .= "amt_o,"           ; $value_so .= "$so_amount,"                ;
            $field_so .= "amt_l,"           ; $value_so .= "$amt_l,"                    ;
            $field_so .= "person_code,"     ; $value_so .= "'$user',"                   ;
            $field_so .= "consignee_code,"  ; $value_so .= "'$so_consignee_code',"      ;
            $field_so .= "consignee_name,"  ; $value_so .= "'$so_consignee_name',"      ;
            $field_so .= "consignee_from_jp"; $value_so .= "'$so_category_mark'"        ;
            chop($field_so) ;              	  chop($value_so) ;

            $ins1  = "insert into so_header ($field_so) select $value_so
                where not exists (select * from so_header where so_no = '$so_so_no')";
            // echo $ins1.'<br/>';
            $data_ins1 = sqlsrv_query($connect, $ins1);
            $pesan = sqlsrv_errors($data_ins1);
            $msg .= $pesan['message'];
        }else{
            $exp_p_mark = explode('<br/>',$so_p_mark);
            for ($p=0; $p<count($exp_p_mark) ; $p++) { 
                $pmark_f .= $plt[$p].',';
                $pmark_v .= "'".$exp_p_mark[$p]."',";
            }

            $exp_c_mark = explode('<br/>',$so_c_mark);
            for ($q=0; $q<count($exp_p_mark) ; $q++) { 
                $cmark_f .= $cs[$q].',';
                $cmark_v .= "'".$exp_c_mark[$q]."',";
            }

            #INSERT SO_DETAILS
            $field_sod  = "so_no,"                  ; $value_sod  = "'$so_so_no'," ;
            $field_sod .= "line_no,"                ; $value_sod .= "'$so_line_no'," ;
            $field_sod .= "customer_part_no,"       ; $value_sod .= "'$so_item'," ;
            $field_sod .= "item_no,"                ; $value_sod .= "i.item_no," ;
            $field_sod .= "origin_code,"            ; $value_sod .= "i.origin_code," ;
            $field_sod .= "qty,"                    ; $value_sod .= "'$so_qty'," ;
            $field_sod .= "uom_q,"                  ; $value_sod .= "i.uom_q," ;
            $field_sod .= "u_price,"                ; $value_sod .= "'$so_price'," ;
            $field_sod .= "amt_o  ,"                ; $value_sod .= "round($so_qty * $so_price,2)," ;
            $field_sod .= "amt_l  ,"                ; $value_sod .= "round($so_rate * $so_qty * $so_price,2)," ;
            $field_sod .= "customer_req_date,"      ; $value_sod .= "'$so_req_date'," ;
            $field_sod .= "etd    ,"                ; $value_sod .= "'$so_ex_fact_date'," ;
            $field_sod .= "customer_po_line_no,"    ; $value_sod .= "'$so_po_line_no'," ;
            // $field_sod .= "enduser_po_no,"          ; $value_sod .= "'$enduser_po_no'," ;
            $field_sod .= "del_qty,"                ; $value_sod .= "'0'," ;
            $field_sod .= "sret_qty,"               ; $value_sod .= "'0'," ;
            $field_sod .= "bal_qty,"                ; $value_sod .= "'$so_qty'," ;
            $field_sod .= "upto_date,"              ; $value_sod .= "getdate()," ;
            $field_sod .= "reg_date,"               ; $value_sod .= "getdate()," ;
            $field_sod .= "aging_day,"              ; $value_sod .= "'$so_aging_day'," ;
            $field_sod .= $pmark_f                  ; $value_sod .= $pmark_v;
            $field_sod .= $cmark_f                  ; $value_sod .= $cmark_v;
            // $field_sod .= "pallet_mark_1,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[0] . "'," ;
            // $field_sod .= "pallet_mark_2,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[1] . "'," ;
            // $field_sod .= "pallet_mark_3,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[2] . "'," ;
            // $field_sod .= "pallet_mark_4,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[3] . "'," ;
            // $field_sod .= "pallet_mark_5,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[4] . "'," ;
            // $field_sod .= "pallet_mark_6,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[5] . "'," ;
            // $field_sod .= "pallet_mark_7,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[6] . "'," ;
            // $field_sod .= "pallet_mark_8,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[7] . "'," ;
            // $field_sod .= "pallet_mark_9,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[8] . "'," ;
            // $field_sod .= "pallet_mark_10,"         ; $value_sod .= "'" . @PALLMARK_VAL_LIST[9] . "'," ;
            // $field_sod .= "case_mark_1,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[0] . "'," ;
            // $field_sod .= "case_mark_2,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[1] . "'," ;
            // $field_sod .= "case_mark_3,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[2] . "'," ;
            // $field_sod .= "case_mark_4,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[3] . "'," ;
            // $field_sod .= "case_mark_5,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[4] . "'," ;
            // $field_sod .= "case_mark_6,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[5] . "'," ;
            // $field_sod .= "case_mark_7,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[6] . "'," ;
            // $field_sod .= "case_mark_8,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[7] . "'," ;
            // $field_sod .= "case_mark_9,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[8] . "'," ;
            // $field_sod .= "case_mark_10,"           ; $value_sod .= "'" . @CASEMARK_VAL_LIST[9] . "'," ;
            $field_sod .= "date_code,"              ; $value_sod .= "'$so_date_code'," ;
            $field_sod .= "in_mps,"                 ; $value_sod .= "'1'," ;
            $field_sod .= "asin,"                   ; $value_sod .= "'$so_asin'," ;
            $field_sod .= "amazon_po_no"            ; $value_sod .= "'$so_amz_po_no'" ;
            chop($field_sod) ;                      chop($value_sod) ;

            $ins  = "insert into so_details ($field_sod) select $value_sod from item i 
                where i.item_no ='$so_item'
                and not exists (select * from so_details where so_no ='$so_so_no' and line_no = '$so_line_no') " ;
            // echo $ins.'<br/>';
            $data_ins = sqlsrv_query($connect, $ins);
            $pesan = sqlsrv_errors($data_ins);
            $msg .= $pesan['message'];


            #INSERT SO_DELIVERY
            $field  = "so_no,"            ; $value  = "'$so_so_no'," ;
		    $field .= "so_line_no,"       ; $value .= "'$so_line_no'," ;
		    $field .= "del_line_no,"      ; $value .= "'$so_line_no'," ;
		    $field .= "del_date,"         ; $value .= "'$so_date'," ;
		    $field .= "qty,"              ; $value .= "'$so_qty'," ;
		    $field .= "del_qty,"          ; $value .= "'0'," ;
		    $field .= "bal_qty,"          ; $value .= "'$so_qty'," ;
		    $field .= "upto_date,"        ; $value .= "getdate()," ;
		    $field .= "reg_date"          ; $value .= "getdate()" ;
            chop($field) ;                 chop($value) ;
            

            $sql  = "insert into SO_DELIVERY ($field)
                select $value
                where not exists (select * from SO_DELIVERY 
                                  where so_no = '$so_so_no'
                                  and so_line_no = '$so_line_no'
                                  and  del_line_no = '$so_line_no')";
            // echo $sql.'<br/>';
            $data_ins2 = sqlsrv_query($connect, $sql);
            $pesan = sqlsrv_errors($data_ins2);
            $msg .= $pesan['message'];
        }
    }
}else{
    $msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode("success");
}
?>