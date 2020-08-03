<?php
session_start();
include("../connect/conn.php");
if (isset($_SESSION['id_wms'])){
    $user = $_SESSION['id_wms'];

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
        $so_cust_po_no = $query->so_cust_po_no;//UENG-COBA,
        $so_consignee_code = $query->so_consignee_code;
        $so_consignee_name = $query->so_consignee_name;
        $so_curr = $query->so_curr;
        $so_rate = $query->so_rate;
        $so_country = $query->so_country;//192-JAPAN,
        $so_item = $query->so_item;//88914,
        $so_price = $query->so_price;//.111300,
        $so_uom = $query->$so_uom;//PC,
        $so_amount = $query->so_amount;//35.84
        $so_p_mark = $query->so_p_mark;
        $so_c_mark = $query->so_c_mark;

        $amt_l = $so_amount * $so_rate;


        if ($so_sts == 'HEADER') {
            # INSERT PRF HEADER
            $field_so .= "customer_code,"   ; $value_so .= "'$so_cust',"                ;
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
            $field_so .= "consignee_from_jp"; $value_so .= "'-'"                        ;
            chop($field_so) ;              	  chop($value_so) ;

            $ins1  = "insert into so_header ($field_so) values ($value_so)
                where not exists (select * from so_header where so_no = '$so_so_no')";
            $data_ins1 = sqlsrv_query($connect, $ins1);
            $pesan = sqlsrv_errors($data_ins1);
            $msg .= $pesan['message'];
        }else{
            #INSERT SO_DETAILS
            $field_sod  = "so_no,"                  ; $value_sod  = "'$so_no'," ;
            $field_sod .= "line_no,"                ; $value_sod .= "'$line_no'," ;
            $field_sod .= "customer_part_no,"       ; $value_sod .= "'$customer_part_no'," ;
            $field_sod .= "item_no,"                ; $value_sod .= "i.item_no," ;
            $field_sod .= "origin_code,"            ; $value_sod .= "i.origin_code," ;
            $field_sod .= "qty,"                    ; $value_sod .= "'$qty'," ;
            $field_sod .= "uom_q,"                  ; $value_sod .= "i.uom_q," ;
            $field_sod .= "u_price,"                ; $value_sod .= "'$u_price'," ;
            $field_sod .= "amt_o  ,"                ; $value_sod .= "round('$qty' * '$u_price',2)," ;
            $field_sod .= "amt_l  ,"                ; $value_sod .= "round('$ex_rate' * '$qty' * '$u_price',2)," ;
            $field_sod .= "customer_req_date,"      ; $value_sod .= " to_date('$customer_req_date','dd/mm/yyyy')," ;
            $field_sod .= "etd    ,"                ; $value_sod .= " to_date('$etd','dd/mm/yyyy')," ;
            $field_sod .= "customer_po_line_no,"    ; $value_sod .= "'$customer_po_line_no'," ;
            $field_sod .= "enduser_po_no,"          ; $value_sod .= "'$enduser_po_no'," ;
            $field_sod .= "del_qty,"                ; $value_sod .= "'0'," ;
            $field_sod .= "sret_qty,"               ; $value_sod .= "'0'," ;
            $field_sod .= "bal_qty,"                ; $value_sod .= "'$qty'," ;
            $field_sod .= "upto_date,"              ; $value_sod .= "sysdate," ;
            $field_sod .= "reg_date,"               ; $value_sod .= "sysdate," ;
            $field_sod .= "aging_day,"              ; $value_sod .= "'$aging_day'," ;
            $field_sod .= "pallet_mark_1,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[0] . "'," ;
            $field_sod .= "pallet_mark_2,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[1] . "'," ;
            $field_sod .= "pallet_mark_3,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[2] . "'," ;
            $field_sod .= "pallet_mark_4,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[3] . "'," ;
            $field_sod .= "pallet_mark_5,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[4] . "'," ;
            $field_sod .= "pallet_mark_6,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[5] . "'," ;
            $field_sod .= "pallet_mark_7,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[6] . "'," ;
            $field_sod .= "pallet_mark_8,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[7] . "'," ;
            $field_sod .= "pallet_mark_9,"          ; $value_sod .= "'" . @PALLMARK_VAL_LIST[8] . "'," ;
            $field_sod .= "pallet_mark_10,"         ; $value_sod .= "'" . @PALLMARK_VAL_LIST[9] . "'," ;
            $field_sod .= "case_mark_1,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[0] . "'," ;
            $field_sod .= "case_mark_2,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[1] . "'," ;
            $field_sod .= "case_mark_3,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[2] . "'," ;
            $field_sod .= "case_mark_4,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[3] . "'," ;
            $field_sod .= "case_mark_5,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[4] . "'," ;
            $field_sod .= "case_mark_6,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[5] . "'," ;
            $field_sod .= "case_mark_7,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[6] . "'," ;
            $field_sod .= "case_mark_8,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[7] . "'," ;
            $field_sod .= "case_mark_9,"            ; $value_sod .= "'" . @CASEMARK_VAL_LIST[8] . "'," ;
            $field_sod .= "case_mark_10,"           ; $value_sod .= "'" . @CASEMARK_VAL_LIST[9] . "'," ;
            $field_sod .= "date_code,"              ; $value_sod .= "'$date_code'," ;
            $field_sod .= "in_mps,"                 ; $value_sod .= "nvl('$in_mps', '0')," ;
            $field_sod .= "asin,"                   ; $value_sod .= "'$asin'," ;
            $field_sod .= "amazon_po_no,"           ; $value_sod .= "'$amazon_po_no'," ;
            chop($field_sod) ;                        chop($value_sod) ;

            $ins  = "insert into so_details ($field_sod) select $value_sod from item i 
                where i.item_no ='$item_no' and i.origin_code='$origin_code'
                and not exists (select * from so_details where so_no ='$so_no' and line_no = '$line_no') " ;
            $data_ins = sqlsrv_query($connect, $ins);
            $pesan = sqlsrv_errors($data_ins);
            $msg .= $pesan['message'];


            #INSERT SO_DELIVERY
            $field  = "so_no,"            ; $value  = "'$so_no'," ;
		    $field .= "so_line_no,"       ; $value .= "'$line_no'," ;
		    $field .= "del_line_no,"      ; $value .= "'$del_line_no'," ;
		    $field .= "del_date,"         ; $value .= "to_date('$date','dd/mm/yyyy')," ;
		    $field .= "qty,"              ; $value .= "'$qty'," ;
		    $field .= "del_qty,"          ; $value .= "'0'," ;
		    $field .= "bal_qty,"          ; $value .= "'$qty'," ;
		    $field .= "upto_date,"        ; $value .= "sysdate," ;
		    $field .= "reg_date,"         ; $value .= "sysdate," ;
            chop($field) ;                 chop($value) ;
            
            
		    $sql  = " insert into SO_DELIVERY ($field) " ;
		    $sql .= " select $value " ;
		    $sql .= " from   dual " ;
		    $sql .= " where not exists ( select * " ;
		    $sql .=                   "  from   SO_DELIVERY " ;
		    $sql .=                   "  where  so_no       = '$so_no' " ;
		    $sql .=                   "    and  so_line_no  = '$line_no' " ;
		    $sql .=                   "    and  del_line_no = '$del_line_no' ) " ;
        }
    }
}

// ===================================================================================
// so_save.php?data=
// [
    
// {"so_sts":"DETAILS","so_cust":"996130","so_date":"2020-08-02","so_so_no":"FI-20-0577","so_cust_po_no":"UENG-COBA","so_consignee_code":"","so_consignee_name":"","so_curr":"1","so_rate":"1","so_country":"192-JAPAN","so_item":"88845","so_price":".093000","so_uom":"PC","so_amount":"20.65"},

// {"so_sts":"DETAILS","so_cust":"996130","so_date":"2020-08-02","so_so_no":"FI-20-0577","so_cust_po_no":"UENG-COBA","so_consignee_code":"","so_consignee_name":"","so_curr":"1","so_rate":"1","so_country":"192-JAPAN","so_item":"88864","so_price":".110100","so_uom":"PC","so_amount":"12.22"},

// {"so_sts":"DETAILS","so_cust":"996130","so_date":"2020-08-02","so_so_no":"FI-20-0577","so_cust_po_no":"UENG-COBA","so_consignee_code":"","so_consignee_name":"","so_curr":"1","so_rate":"1","so_country":"192-JAPAN","so_item":"88914","so_price":".111300","so_uom":"PC","so_amount":"35.84"},

// {"so_sts":"HEADER","so_cust":"996130","so_date":"2020-08-02","so_so_no":"FI-20-0577","so_cust_po_no":"UENG-COBA","so_consignee_code":"","so_consignee_name":"","so_curr":"1","so_rate":"1","so_country":"192-JAPAN","so_item":"88914","so_price":".111300","so_uom":"PC","so_amount":"020.6512.2235.84"}

// ]


?>