<?php
session_start();
include("../connect/conn.php");
if (isset($_SESSION['id_wms'])){
	if($varConn == "Y"){
		$do_sts = htmlspecialchars($_REQUEST['do_sts']);
		$do_no = htmlspecialchars($_REQUEST['do_no']);
		$do_date = htmlspecialchars($_REQUEST['do_date']);
		$do_cust = htmlspecialchars($_REQUEST['do_cust']);
		$do_curr = htmlspecialchars($_REQUEST['do_curr']);
		$do_rate = htmlspecialchars($_REQUEST['do_rate']);
		$do_paym = htmlspecialchars($_REQUEST['do_paym']);
		$do_remark = htmlspecialchars($_REQUEST['do_remark']);
		$do_gst = htmlspecialchars($_REQUEST['do_gst']);
		$do_vsl = htmlspecialchars($_REQUEST['do_vsl']);
		$do_port_load_kd = htmlspecialchars($_REQUEST['do_port_load_kd']);
		$do_port_disg_kd = htmlspecialchars($_REQUEST['do_port_disg_kd']);
		$do_port_dest_kd = htmlspecialchars($_REQUEST['do_port_dest_kd']);
		$do_port_load = htmlspecialchars($_REQUEST['do_port_load']);
		$do_port_disg = htmlspecialchars($_REQUEST['do_port_disg']);
		$do_port_dest = htmlspecialchars($_REQUEST['do_port_dest']);
		$do_etd = htmlspecialchars($_REQUEST['do_etd']);
		$do_eta = htmlspecialchars($_REQUEST['do_eta']);
		$do_contract = htmlspecialchars($_REQUEST['do_contract']);
		$do_pby = htmlspecialchars($_REQUEST['do_pby']);
		$do_trade_term = htmlspecialchars($_REQUEST['do_trade_term']);
		$do_notify = htmlspecialchars($_REQUEST['do_notify']);
		$do_attn = htmlspecialchars($_REQUEST['do_attn']);
		$do_si_no = htmlspecialchars($_REQUEST['do_si_no']);
		$do_goods_name = htmlspecialchars($_REQUEST['do_goods_name']);
		$do_line_no = htmlspecialchars($_REQUEST['do_line_no']);
		$do_item_no = htmlspecialchars($_REQUEST['do_item_no']);
		$do_origin = htmlspecialchars($_REQUEST['do_origin']);
		$do_cust_part_no = htmlspecialchars($_REQUEST['do_cust_part_no']);
		$do_qty = htmlspecialchars($_REQUEST['do_qty']);
		$do_uomq = htmlspecialchars($_REQUEST['do_uomq']);
		$do_price = htmlspecialchars($_REQUEST['do_price']);
		$do_amt_o = htmlspecialchars($_REQUEST['do_amt_o']);
		$do_amt_l = htmlspecialchars($_REQUEST['do_amt_l']);
		$do_desc = htmlspecialchars($_REQUEST['do_desc']);
		$do_so_no = htmlspecialchars($_REQUEST['do_so_no']);
		$do_so_line_no = htmlspecialchars($_REQUEST['do_so_line_no']);
		$do_qty_answer = htmlspecialchars($_REQUEST['do_qty_answer']);
		$do_cust_po_no = htmlspecialchars($_REQUEST['do_cust_po_no']);
		$do_answer_no = htmlspecialchars($_REQUEST['do_answer_no']);
		$do_date_code = htmlspecialchars($_REQUEST['do_date_code']);
		$do_remark_packing = htmlspecialchars($_REQUEST['do_remark_packing']);
		$do_remark_shipping = htmlspecialchars($_REQUEST['do_remark_shipping']);
		$do_forwarder_code = htmlspecialchars($_REQUEST['do_forwarder_code']);
		$do_domestic_truck_code	 = htmlspecialchars($_REQUEST['do_domestic_truck_code']);
		$do_booking = htmlspecialchars($_REQUEST['do_booking']);
		$do_transport_type = htmlspecialchars($_REQUEST['do_transport_type']);
		$do_transport_name = htmlspecialchars($_REQUEST['do_transport_name']);
		$do_sett_transport1 = htmlspecialchars($_REQUEST['do_sett_transport1']);
		$do_sett_transport2 = htmlspecialchars($_REQUEST['do_sett_transport2']);
		$do_ppbe = htmlspecialchars($_REQUEST['do_ppbe']);

		$user = $_SESSION['id_wms'];
		// HITUNG DUEDATE
		$split_paym = split("-",$do_paym);
		$plusDay = "+".intval($split_paym[0])." day";
		$tambah_date = strtotime($plusDay,strtotime($do_date));
		$due_date = date('Y-m-d',$tambah_date);

		if ($do_sett_transport1 != ''){
			$split_do_sett_transport1 = split("-", $do_sett_transport1);
			if (strtoupper($split_do_sett_transport1[0]) == 'UNDEFINED' || 
				strtoupper($split_do_sett_transport1[1]) == 'UNDEFINED' || 
				strtoupper($split_do_sett_transport1[2]) == 'UNDEFINED'){

				$split_do_sett_transport1_1 = 0;		
				$split_do_sett_transport1_2 = 0;		
				$split_do_sett_transport1_3 = 0;
			}else{
				$split_do_sett_transport1_1 = $split_do_sett_transport1[0];		
				$split_do_sett_transport1_2 = $split_do_sett_transport1[1];		
				$split_do_sett_transport1_3 = $split_do_sett_transport1[2];
			}
		}else{
			$split_do_sett_transport1_1 = 0;
			$split_do_sett_transport1_2 = 0;
			$split_do_sett_transport1_3 = 0;
		}

		if($do_sett_transport2 != ''){	
			$split_do_sett_transport2 = split("-", $do_sett_transport2);
			if (strtoupper($split_do_sett_transport2[0]) == 'UNDEFINED' || 
				strtoupper($split_do_sett_transport2[1]) == 'UNDEFINED' || 
				strtoupper($split_do_sett_transport2[2]) == 'UNDEFINED'){

				$split_do_sett_transport2_1 = 0;		
				$split_do_sett_transport2_2 = 0;		
				$split_do_sett_transport2_3 = 0;
			}else{
				$split_do_sett_transport2_1 = $split_do_sett_transport2[0];
				$split_do_sett_transport2_2 = $split_do_sett_transport2[1];
				$split_do_sett_transport2_3 = $split_do_sett_transport2[2];
			}
		}else{
			$split_do_sett_transport2_1 = 0;		
			$split_do_sett_transport2_2 = 0;		
			$split_do_sett_transport2_3 = 0;
		}

		//mencari no ppbe: w=wisnu, a=agung, d=dewi (FI0085=Agung, FI0088=wisnu, FI0102=Dewi )
		//1. cari inisial person
		//select substr(person,0,1) from person where person_code='$user';
		//2. cari no urut max
		//select coalesce(lpad(cast(cast(max(substr(description,0,5)) as integer)+1 as varchar(5)),5,'0'),'00001') as ppbe from do_header
		//gabungkan no/inisial. save ke ppbe_no


		$field  = "DO_STS,"					;	$value 	= "'$do_sts',";
		$field .= "DO_NO,"					;	$value .= "'$do_no',";
		$field .= "DO_DATE,"				;	$value .= "to_date('$do_date','YYYY-MM-DD'),";
		$field .= "CUSTOMER_CODE,"			;	$value .= "$do_cust,";
		$field .= "CURR_CODE,"				;	$value .= "$do_curr,";
		$field .= "EX_RATE,"				;	$value .= "$do_rate,";
		$field .= "DUE_DATE,"				;	$value .= "to_date('$due_date','YYYY-MM-DD'),";
		$field .= "PDAYS,"					;	$value .= "$split_paym[0],";
		$field .= "PDESC,"					;	$value .= "'$split_paym[1]',";
		$field .= "GST_RATE,"				;	$value .= "$do_gst,";

		//$field .= "REMARK,"					;	$value .= "'$do_remark',";
		if (str_replace(' ', '', $do_remark) == ''){
			$field .= "REMARK,"		;	$value .= "'',";
		}else{
			$rmk_s0 = explode('&lt;br&gt;', $do_remark);
			$rmk_f0 = '';
			for($f0=0;$f0<count($rmk_s0);$f0++){
				if($rmk_s0[$f0] != ''  || ! is_null($rmk_s0[$f0])) {
					if($f0 == count($rmk_s0)-1){
						$rmk_f0 .= "'".str_replace("'","''",$rmk_s0[$f0])."'";
					}else{
						$rmk_f0 .= "'".str_replace("'","''",$rmk_s0[$f0])."' || chr(13) || chr(10) || ";
					}
				}
			}
			$rmk_fix0 = str_replace("&amp;", "&", $rmk_f0);
			//$rmk_fix0 = substr($rmk_fx0, 0, 1000);
			$field .= "REMARK,"		;	$value .= "$rmk_fix0,";
		}

		//$field .= "SHIP_NAME,"				;	$value .= "'$do_vsl',";
		if (str_replace(' ', '', $do_vsl) == ''){
			$field .= "SHIP_NAME,"		;	$value .= "'',";
		}else{
			$vsl_s = explode('&lt;br&gt;', $do_vsl);
			$vsl_f = '';
			for($v=0;$v<count($vsl_s);$v++){
				if($vsl_s[$v] != '' || ! is_null($vsl_s[$v])) {
					if($v == count($vsl_s)-1){
						$vsl_f .= "'".str_replace("'","''",$vsl_s[$v])."'";
					}else{
						$vsl_f .= "'".str_replace("'","''",$vsl_s[$v])."' || chr(13) || chr(10) || ";
					}
				}
			}
			$vsl_fix = str_replace("&amp;", "&", $vsl_f);
			//$vsl_fix = substr($vsl_fx, 0,100);
			$field .= "SHIP_NAME,"		;	$value .= "$vsl_fix,";
		}

		$field .= "PORT_LOADING,"			;	$value .= "'$do_port_load',";
		$field .= "PORT_DISCHARGE,"			;	$value .= "'$do_port_disg',";
		$field .= "ETD,"					;	$value .= "to_date('$do_etd','YYYY-MM-DD'),";
		$field .= "ETA,"					;	$value .= "to_date('$do_eta','YYYY-MM-DD'),";
		$field .= "CONTRACT_SEQ,"			;	$value .= "$do_contract,";
		$field .= "PBY,"					;	$value .= "'$do_pby',";
		$field .= "FINAL_DESTINATION,"		;	$value .= "'$do_port_dest',";
		$field .= "TRADE_TERM,"				;	$value .= "'$do_trade_term',";

		if (str_replace(' ', '', $do_notify) == ''){
			$field .= "NOTIFY,"		;	$value .= "'',";
		}else{
			$ntf_s = explode('&lt;br&gt;', $do_notify);
			$ntf_f = '';
			for($n=0;$n<count($ntf_s);$n++){
				if($ntf_s[$n] != '' || ! is_null($ntf_s[$n])) {
					if($n == count($ntf_s)-1){
						$ntf_f .= "'".str_replace("'","''",$ntf_s[$n])."'";
					}else{
						$ntf_f .= "'".str_replace("'","''",$ntf_s[$n])."' || chr(13) || chr(10) || ";
					}
				}
			}
			$ntf_fix = str_replace("&amp;", "&", $ntf_f);
			//$ntf_fix = substr($ntf_fx,0,250);
			$field .= "NOTIFY,"		;	$value .= "$ntf_fix,";
		}

		$do_description = substr($do_desc, 0,30);

		$field .= "ATTN,"					;	$value .= "'$do_attn',";
		$field .= "ADDRESS_FLG,"			;	$value .= "1,";
		$field .= "PERSON_CODE,"			;	$value .= "'$user',";
		$field .= "PORT_LOADING_CODE,"		;	$value .= "'$do_port_load_kd',";
		$field .= "PORT_DISCHARGE_CODE,"	;	$value .= "'$do_port_disg_kd',";
		$field .= "FINAL_DESTINATION_CODE,"	;	$value .= "'$do_port_dest_kd',";
		$field .= "SI_NO,"					;	$value .= "'$do_si_no',";
		$field .= "LINE_NO,"				;	$value .= "$do_line_no,";
		$field .= "ITEM_NO,"				;	$value .= "$do_item_no,";
		$field .= "ORIGIN_CODE,"			;	$value .= "'$do_origin',";
		$field .= "CUSTOMER_PART_NO,"		;	$value .= "'$do_cust_part_no',";
		$field .= "QTY,"					;	$value .= "$do_qty_answer,";
		$field .= "UOM_Q,"					;	$value .= "'$do_uomq',";
		$field .= "U_PRICE,"				;	$value .= "$do_price,";
		$field .= "AMT_O,"					;	$value .= "$do_amt_o,";
		$field .= "AMT_L,"					;	$value .= "$do_amt_l,";
		$field .= "DESCRIPTION,"			;	$value .= "'$do_description',";
		$field .= "SO_NO,"					;	$value .= "'$do_so_no',";
		$field .= "SO_LINE_NO,"				;	$value .= "$do_so_line_no,";
		$field .= "QTY_ANSWER,"				;	$value .= "$do_qty_answer,";
		$field .= "CUSTOMER_PO_NO,"			;	$value .= "'$do_cust_po_no',";
		$field .= "ANSWER_NO,"				;	$value .= "'$do_answer_no',";
		$field .= "CARVED_STAMP,"			;	$value .= "'$do_date_code',";
		$field .= "REMARK_PACKING,"			;	$value .= "'$do_remark_packing',";
		$field .= "PPBE_NO,"				;	$value .= "'$do_ppbe',";

		if (str_replace(' ', '', $do_remark_shipping) == ''){
			$field .= "REMARK_SHIPPING,"		;	$value .= "'',";
		}else{
			$rmk_s = explode('&lt;br&gt;', $do_remark_shipping);
			$rmk_f = '';
			for($f=0;$f<count($rmk_s);$f++){
				if($rmk_s[$f] != ''  || ! is_null($rmk_s[$f])) {
					if($f == count($rmk_s)-1){
						$rmk_f .= "'".str_replace("'","''",$rmk_s[$f])."'";
					}else{
						$rmk_f .= "'".str_replace("'","''",$rmk_s[$f])."' || chr(13) || ";
					}
				}
			}
			$rmk_fix = str_replace("&amp;", "&", $rmk_f);
			$field .= "REMARK_SHIPPING,"		;	$value .= "$rmk_fix,";
		}

		$field .= "FORWARDER_CODE,"			;	$value .= "$do_forwarder_code,";
		$field .= "DOMESTIC_TRUCK_CODE,"	;	$value .= "$do_domestic_truck_code,";
		$field .= "TRANSPORT,"				;	$value .= "'$do_transport_name',";
		$field .= "ENTRY_TYPE,"				;	$value .= "'LR',";
		$field .= "BOOKING_NO,"				;	$value .= "'$do_booking',";
		$field .= "TRANSPORT_TYPE,"			;	$value .= "$do_transport_type,";
		$field .= "CARGO_TYPE1,"			;	$value .= "$split_do_sett_transport1_1,";
		$field .= "CARGO_SIZE1,"			;	$value .= "$split_do_sett_transport1_2,";
		$field .= "CARGO_QTY1,"				;	$value .= "$split_do_sett_transport1_3,";
		$field .= "CARGO_TYPE2,"			;	$value .= "$split_do_sett_transport2_1,";
		$field .= "CARGO_SIZE2,"			;	$value .= "$split_do_sett_transport2_2,";
		$field .= "CARGO_QTY2,"				;	$value .= "$split_do_sett_transport2_3,";

		if ($do_line_no == 1){
			$field .= "GOODS_NAME"		;	$value .= "'$do_goods_name'";
		}else{
			$field .= "GOODS_NAME"		;	$value .= "''";
		}

		chop($field);              				chop($value);

		$ins_cc = "insert into ztb_do_temp ($field) select $value from dual";
		$data_cc = oci_parse($connect, $ins_cc);
		oci_execute($data_cc);
		echo $ins_cc;
		
		echo json_encode(array('successMsg'=>'INSERT DATA SUCCCESS'));
	}else{
		echo json_encode(array('errorMsg'=>'Connection Failed'));	
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>