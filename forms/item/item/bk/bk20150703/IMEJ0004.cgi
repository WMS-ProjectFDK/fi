#-------------------------
# Require init file
# 2013/04/09 Y.Hagai 各種テーブルにデータが存在する時は、削除不可にする
# 2015/04/21 NTTk)Hino  項目追加（CAPACITY他 計28項目）

#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# POST
#-------------------------
foreach(keys (%in)){
  $$_ = $in{$_} ;
  $$_ =~ s/\r//g ;
}

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

if($TYPES eq "NEW"){
	# 重複確認
	$sql = "SELECT count(*) FROM ITEM where ITEM_NO ='$item_no' ";
	$cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	($COUNT) = $cur->fetchrow() ;
	$cur->finish;
	if($COUNT >0){ &err("There is already this item no.") ;}
}

if($TYPES eq "DELETE" or $TYPES eq "UNDELETION"){
	#ITEMCHECK_ITEM_DELETE
	# mod 2015/04/21 Start
    # #2013/04/09 Y.Hagai ADD
    # #	$sql  = "select i.*,s.stock_subject from item i,stock_subject s " ;
	# $sql  = "select i.*,s.stock_subject,check_item_delete(i.item_no) delete_chk from item i,stock_subject s " ;
	# $sql .= "where  i.item_no            = '$item_no'";
	# $sql .= "  and  i.stock_subject_code = s.stock_subject_code(+) ";
	
	$sql  = "select i.*, s.stock_subject, check_item_delete(i.item_no) delete_chk, " ;
	$sql .= "       p.plt_spec_no, p.pallet_unit_number, p.pallet_ctn_number, " ;
	$sql .= "       p.pallet_step_ctn_number, p.pallet_height, p.pallet_width, pallet_depth, " ;
	$sql .= "       p.pallet_size_type, " ;
	$sql .= "       i.external_unit_number as outer_box_unit_number " ;
	$sql .= "from   item i, " ;
	$sql .= "       stock_subject s, " ;
	$sql .= "       packing_information p " ;
	$sql .= "where  i.item_no            = '$item_no'";
	$sql .= "  and  i.stock_subject_code = s.stock_subject_code(+) ";
	$sql .= "  and  i.pi_no              = p.pi_no(+) ";
	# mod 2015/04/21 End
	
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_MST err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_MST err $DBI::err .... $DBI::errstr") ;
	@datas = $cur->fetchrow() ;
	for($j=0;$j<@datas;$j++){
		$cur->{NAME}->[$j] =~ tr/[A-Z]/[a-z]/ ;
		${$cur->{NAME}->[$j]} = $datas[$j] ;
	}
}

	#ITEMFLAG
	$sql = "select item_flag,flag_name from itemflag order by flag_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$IMF_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$IMF[$datas[0]] = $datas[1] ;
		$IMF_NUM ++ ;
	}
	$cur->finish;

	#COUNTRY
	$sql = "select country_code,country from country order by area_code,country";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$COU_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$COU[$datas[0]] = $datas[1] ;
		$COU_NUM ++ ;
	}
	$cur->finish;

	#UNIT
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'QUANTITY' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNQ_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNQ[$datas[0]] = $datas[1] ;
		$UNQ_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'WEIGHT' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNW_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNW[$datas[0]] = $datas[1] ;
		$UNW_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'LENGTH' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNL_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNL[$datas[0]] = $datas[1] ;
		$UNL_NUM ++ ;
	}
	$cur->finish;

	#ISSUEPOLICY
	$sql = "select issue_policy,policy_name from issuepolicy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$ISS_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ISS[$datas[0]] = $datas[1] ;
		$ISS_NUM ++ ;
	}
	$cur->finish;

	#SECTION
	$sql = "select section_code,section from section order by short_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_SECTION err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_SECTION err $DBI::err .... $DBI::errstr") ;
	$SEC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SEC[$datas[0]] = $datas[1] ;
		$SEC_NUM ++ ;
	}
	$cur->finish;

	#STOCK_SUBJECT
	$sql = "select stock_subject_code,stock_subject from stock_subject order by stock_subject_code";
	#print $sql ;
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$SSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SSC[$datas[0]] = $datas[1] ;
		$SSC_NUM ++ ;
	}
	$cur->finish;

	#COSTPROCESS
	$sql = "select cost_process_code,cost_process_name from costprocess order by cost_process_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$CPC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CPC[$datas[0]] = $datas[1] ;
		$CPC_NUM ++ ;
	}
	$cur->finish;

	#COSTSUBJECT
	$sql = "select cost_subject_code,cost_subject_name from costsubject order by cost_subject_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$CSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CSC[$datas[0]] = $datas[1] ;
		$CSC_NUM ++ ;
	}
	$cur->finish;

	#ORDER_POLICY
	$sql = "select order_policy,policy_name from order_policy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$ORP_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ORP[$datas[0]] = $datas[1] ;
		$ORP_NUM ++ ;
	}
	$cur->finish;

	#MAKERFLAG
	$sql = "select maker_flag_code,maker_flag from makerflag order by maker_flag";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$MAK_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$MAK[$datas[0]] = $datas[1] ;
		$MAK_NUM ++ ;
	}
	$cur->finish;

	#CURRENCY
	$sql = "select curr_code,curr_mark from currency order by curr_code";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$CURR_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CURR[$datas[0]] = $datas[1] ;
		$CURR_NUM ++ ;
	}
	$cur->finish;

	#CLASS
	$sql = "SELECT CLASS_CODE,CLASS_1,CLASS_2,CLASS_3 FROM CLASS ORDER BY CLASS_CODE";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_CLASS err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_CLASS err $DBI::err .... $DBI::errstr") ;
	$CLAS_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CLAS[$datas[0]] = $datas[1] . "-" . $datas[2] . "-" . $datas[3];
		$CLAS_NUM ++ ;
	}
	$cur->finish;

    #add 2015/04/21 Start
	#LABEL_TYPE
	$sql = "SELECT LABEL_TYPE_CODE, LABEL_TYPE_NAME FROM LABEL_TYPE ORDER BY LABEL_TYPE_CODE";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_LABEL_TYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_LABEL_TYPE err $DBI::err .... $DBI::errstr") ;
	$LABEL_TYPE_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$LABEL_TYPE[$datas[0]] = $datas[1] ;
		$LABEL_TYPE_NUM ++ ;
	}
	$cur->finish;
	
	#PALLET_SIZE_TYPE
	$sql = "SELECT PALLET_SIZE_TYPE_CODE, PALLET_SIZE_TYPE_NAME FROM PALLET_SIZE_TYPE ORDER BY PALLET_SIZE_TYPE_CODE";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_PALLET_SIZE_TYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_PALLET_SIZE_TYPE err $DBI::err .... $DBI::errstr") ;
	$PALLET_SIZE_TYPE_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$PALLET_SIZE_TYPE[$datas[0]] = $datas[1] ;
		$PALLET_SIZE_TYPE_NUM ++ ;
	}
	$cur->finish;
    #add 2015/04/21 End
    
	# ERROR CHECK
	$ERR_MSG		= "" ;
	$BG_ITEM_NO		= "WHITE";
	$BG_STOCK_SUBJECT	= "WHITE";
	$BG_ITEM		= "WHITE";
	$BG_ORIGIN_CODE		= "WHITE";
	$BG_CURR_CODE		= "WHITE";
	$BG_UOM_W		= "WHITE";
	$BG_UOM_L		= "WHITE";
	$BG_UNIT		= "WHITE";
	$BG_SECTION		= "WHITE";
	$BG_PROCESS		= "WHITE";
	$BG_SUBJECT		= "WHITE";
	$BG_STP			= "WHITE";
	$BG_NTP			= "WHITE";
	$BG_SUP			= "WHITE";
	$BG_EUN			= "WHITE";
	$BG_SAF			= "WHITE";
	$BG_WEI			= "WHITE";
	$BG_MAN			= "WHITE";
	$BG_PUR			= "WHITE";
	$BG_ADJ			= "WHITE";
	$BG_MFR			= "WHITE";
	$BG_ISS			= "WHITE";
	$BG_PUN         = "WHITE";
	# add 2015/04/21 Start
	$BG_CAPACITY          = "WHITE";
	$BG_DATE_CODE_TYPE    = "WHITE";
	$BG_DATE_CODE_MONTH   = "WHITE";
	$BG_LABEL_TYPE        = "WHITE";
	$BG_MEASUREMENT       = "WHITE";
	$BG_I_HEIGHT          = "WHITE";
	$BG_I_WIDTH           = "WHITE";
	$BG_I_DEPTH           = "WHITE";
	$BG_I_UNIT_NUMBER     = "WHITE";
	$BG_M_HEIGHT          = "WHITE";
	$BG_M_WIDTH           = "WHITE";
	$BG_M_DEPTH           = "WHITE";
	$BG_M_UNIT_NUMBER     = "WHITE";
	$BG_O_HEIGHT          = "WHITE";
	$BG_O_WIDTH           = "WHITE";
	$BG_O_DEPTH           = "WHITE";
	$BG_O_UNIT_NUMBER     = "WHITE";
	$BG_PI_NO             = "WHITE";
	$BG_PLT_SPEC_NO       = "WHITE";
	$BG_PALLET_SIZE_TYPE  = "WHITE";
	$BG_P_HEIGHT          = "WHITE";
	$BG_P_WIDTH           = "WHITE";
	$BG_P_DEPTH           = "WHITE";
	$BG_P_UNIT_NUMBER     = "WHITE";
	$BG_P_CTN_NUMBER      = "WHITE";
	$BG_P_STEP_CTN_NUMBER = "WHITE";
	$BG_OPERATION_TIME    = "WHITE";
	$BG_MAN_POWER         = "WHITE";
	$BG_AGING_DAY         = "WHITE";
	# add 2015/04/21 End

if($TYPES ne "DELETE"){
		&err("Item No is not found.") if($item_no eq "");
		if($item_no =~ /[^0-9 .]/){ $ERROR .=" 'STANDARD PRICE' is not right.<BR>" ;				$BG_ITEM_NO       = "lightpink"; $ERR_MSG = "ERR"; }
		if($stock_subject_code eq ""){ $ERROR .=" 'STOCK SUBJECT CODE' is not right.<BR>";			$BG_STOCK_SUBJECT = "lightpink"; $ERR_MSG = "ERR"; }
		if($item               eq ""){ $ERROR .=" 'ITEM' is not right.。<BR>" ;					$BG_ITEM          = "lightpink"; $ERR_MSG = "ERR"; }
		if($origin_code        eq ""){ $ERROR .=" 'ORIGIN' is not right.<BR>";					$BG_ORIGIN_CODE   = "lightpink"; $ERR_MSG = "ERR"; }
		if($curr_code          eq ""){ $ERROR .=" 'CURRENCY' is not right.<BR>";					$BG_CURR_CODE     = "lightpink"; $ERR_MSG = "ERR"; }
		if($uom_w              eq ""){ $ERROR .=" 'UNIT of WEIGH' is not right.<BR>";				$BG_UOM_W         = "lightpink"; $ERR_MSG = "ERR"; }
		if($uom_l              eq ""){ $ERROR .=" 'UNIT of MEASUREMENT' is not right.<BR>";			$BG_UOM_L         = "lightpink"; $ERR_MSG = "ERR"; }
		if($unit_stock         eq ""){ $ERROR .=" 'UNIT_STOCK' is not right.<BR>";					$BG_UNIT          = "lightpink"; $ERR_MSG = "ERR"; }
		if($unit_engineering   eq ""){ $ERROR .=" 'UNIT ENGINEERING' is not right.<BR>";			$BG_UNIT          = "lightpink"; $ERR_MSG = "ERR"; }
		if($section_code       eq ""){ $ERROR .=" 'SECTION CODE' is not right.<BR>";				$BG_SECTION       = "lightpink"; $ERR_MSG = "ERR"; }
		if($cost_process_code  eq ""){ $ERROR .=" 'COST PROCESS CODE' is not right.<BR>";			$BG_PROCESS       = "lightpink"; $ERR_MSG = "ERR"; }
		if($cost_subject_code  eq ""){ $ERROR .=" 'COST SUBJECT CODE' is not right.<BR>";			$BG_SUBJECT       = "lightpink"; $ERR_MSG = "ERR"; }

		if($unit_stock_rate      =~ /[^0-9 .]/){ $ERROR .=" 'UNIT STOCK RATE' is not right.<BR>";		$BG_UNIT ="lightpink" ; $ERR_MSG = "ERR"; }
		if($unit_engineer_rate   =~ /[^0-9 .]/){ $ERROR .=" 'UNIT ENGINEER RATE' is not right.<BR>";	$BG_UNIT ="lightpink" ; $ERR_MSG = "ERR"; }
		if($standard_price       =~ /[^0-9 .]/){ $ERROR .=" 'STANDARD PRICE' is not right.<BR>";		$BG_STP  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($next_term_price      =~ /[^0-9 .]/){ $ERROR .=" 'NEXT TERM PRICE' is not right.<BR>";		$BG_NTP  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($suppliers_price      =~ /[^0-9 .]/){ $ERROR .=" 'SUPPLIERS PRICE' is not right.<BR>";		$BG_SUP  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($external_unit_number =~ /[^0-9 .]/){ $ERROR .=" 'EXTERNAL UNIT NUMBER' is not right.<BR>";	$BG_EUN  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($safety_stock         =~ /[^0-9 .]/){ $ERROR .=" 'SAFETY STOCK' is not right.<BR>";		$BG_SAF  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($weight               =~ /[^0-9 .]/){ $ERROR .=" 'WEIGHT' is not right.<BR>";			$BG_WEI  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($manufact_leadtime    =~ /[^0-9 .]/){ $ERROR .=" 'MANUFACT LEADTIME' is not right.<BR>";	$BG_MAN  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($purchase_leadtime    =~ /[^0-9 .]/){ $ERROR .=" 'PURCHASE LEADTIME' is not right.<BR>";	$BG_PUR  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($adjustment_leadtime  =~ /[^0-9 .]/){ $ERROR .=" 'ADJUSTMENT LEADTIME' is not right.<BR>";	$BG_ADJ  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($manufact_fail_rate   =~ /[^0-9 .]/){ $ERROR .=" 'MANUFACT FAIL RATE' is not right.<BR>";	$BG_MFR  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($issue_lot            =~ /[^0-9 .]/){ $ERROR .=" 'ISSUE LOT' is not right.<BR>";			$BG_ISS  ="lightpink" ; $ERR_MSG = "ERR"; }
		# add 2015/04/21 Start
		if($capacity                  =~ /[^0-9 .]/){ $ERROR .=" 'CAPACITY' is not right.<BR>";			         $BG_CAPACITY  ="lightpink" ; $ERR_MSG = "ERR"; }
		#if($date_code_type            =~ /[^0-9 .]/){ $ERROR .=" 'DATE CODE TYPE' is not right.<BR>";			 $BG_DATE_CODE_TYPE  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($date_code_month           =~ /[^0-9 .]/){ $ERROR .=" 'DATE CODE MONTH' is not right.<BR>";			 $BG_DATE_CODE_MONTH  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($measurement               =~ /[^0-9 .]/){ $ERROR .=" 'MEASUREMENT' is not right.<BR>";			     $BG_MEASUREMENT  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($inner_box_height          =~ /[^0-9 .]/){ $ERROR .=" 'INNER BOX (HEIGHT)' is not right.<BR>";	     $BG_I_HEIGHT  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($inner_box_width           =~ /[^0-9 .]/){ $ERROR .=" 'INNER BOX (WIDTH)' is not right.<BR>";	     $BG_I_WIDTH  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($inner_box_depth           =~ /[^0-9 .]/){ $ERROR .=" 'INNER BOX (DEPTH)' is not right.<BR>";		 $BG_I_DEPTH  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($inner_box_unit_number     =~ /[^0-9 .]/){ $ERROR .=" 'INNER BOX (UNIT NUMBER)' is not right.<BR>";	 $BG_I_UNIT_NUMBER  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($medium_box_height         =~ /[^0-9 .]/){ $ERROR .=" 'MEDIUM BOX (HEIGHT)' is not right.<BR>";		 $BG_M_HEIGHT  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($medium_box_width          =~ /[^0-9 .]/){ $ERROR .=" 'MEDIUM BOX (WIDTH)' is not right.<BR>";		 $BG_M_WIDTH  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($medium_box_depth          =~ /[^0-9 .]/){ $ERROR .=" 'MEDIUM BOX (DEPTH)' is not right.<BR>";		 $BG_M_DEPTH  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($medium_box_unit_number    =~ /[^0-9 .]/){ $ERROR .=" 'MEDIUM BOX (UNIT NUMBER)' is not right.<BR>";	 $BG_M_UNIT_NUMBER  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($outer_box_height          =~ /[^0-9 .]/){ $ERROR .=" 'OUTER BOX (HEIGHT)' is not right.<BR>";		 $BG_O_HEIGHT  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($outer_box_width           =~ /[^0-9 .]/){ $ERROR .=" 'OUTER BOX (WIDTH)' is not right.<BR>";		 $BG_O_WIDTH  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($outer_box_depth           =~ /[^0-9 .]/){ $ERROR .=" 'OUTER BOX (DEPTH)' is not right.<BR>";		 $BG_O_DEPTH  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($outer_box_unit_number     =~ /[^0-9 .]/){ $ERROR .=" 'OUTER BOX (UNIT NUMBER)' is not right.<BR>";   $BG_O_UNIT_NUMBER  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($pallet_size_type          =~ /[^0-9 .]/){ $ERROR .=" 'PALLET SIZE TYPE' is not right.<BR>";		     $BG_PALLET_SIZE_TYPE  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($pallet_height             =~ /[^0-9 .]/){ $ERROR .=" 'PALLET (HEIGHT)' is not right.<BR>";			 $BG_P_HEIGHT  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($pallet_width              =~ /[^0-9 .]/){ $ERROR .=" 'PALLET (WIDTH)' is not right.<BR>";			 $BG_P_WIDTH  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($pallet_depth              =~ /[^0-9 .]/){ $ERROR .=" 'PALLET (DEPTH)' is not right.<BR>";			 $BG_P_DEPTH  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($pallet_unit_number        =~ /[^0-9 .]/){ $ERROR .=" 'PALLET (UNIT NUMBER)' is not right.<BR>";	 	 $BG_P_UNIT_NUMBER  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($pallet_ctn_number         =~ /[^0-9 .]/){ $ERROR .=" 'PALLET (CTN NUMBER)' is not right.<BR>";		 $BG_P_CTN_NUMBER  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($pallet_step_ctn_number    =~ /[^0-9 .]/){ $ERROR .=" 'PALLET (STEP CTN NUMBER)' is not right.<BR>";	 $BG_P_STEP_CTN_NUMBER  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($operation_time            =~ /[^0-9 .]/){ $ERROR .=" 'OPERATION TIME' is not right.<BR>";			 $BG_OPERATION_TIME  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($man_power                 =~ /[^0-9 .]/){ $ERROR .=" 'MAN POWER' is not right.<BR>";			     $BG_MAN_POWER  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($aging_day                 =~ /[^0-9 .]/){ $ERROR .=" 'AGING DAY' is not right.<BR>";			     $BG_AGING_DAY  ="lightpink" ; $ERR_MSG = "ERR"; }
		# add 2015/04/21 End

        # FIでSTOCK_SUBJECT_CODE(製品)の場合のみ必須入力項目
        if ($CONF{'FI_PACKAGE_UNIT'} eq "1") {
            $package_flg = "err"   if ($package_unit_number eq ""  and  $stock_subject_code == 5) ;
            $package_flg = "err"   if ($package_unit_number ne ""  and  $package_unit_number =~ /[\D+]/) ;
            $package_flg = "err"   if ($package_unit_number ne ""  and  length($package_unit_number) > 6) ;
            $package_flg = "err"   if (!$unit_package  and  $stock_subject_code == 5) ; 

            if ($package_flg eq "err" and $TYPES ne "UNDELETION") {
                $ERROR .=" 'PACKAGE UNIT NUUMBER or PACKAGE UNIT' is not right.<BR>";
                $BG_PUN ="lightpink" ; 
                $ERR_MSG = "ERR";
            }
        }
        
        # add 2015/04/21 Start
        # STOCK_SUBJECT_CODE(製品)の場合のみ必須入力項目
        if ($stock_subject_code == 5) {
        	if ($capacity eq "" ) {
        		$BG_CAPACITY = "lightpink" ;
        		$P_ERR_CNT ++;
        	}
        	if ($label_type eq "" ) {
        		$BG_LABEL_TYPE = "lightpink" ;
        		$P_ERR_CNT ++;
        	}
        	if ($pi_no eq "" ) {
        		$BG_PI_NO = "lightpink" ;
        		$P_ERR_CNT ++;
        	}
        	if ($plt_spec_no eq "" ) {
        		$BG_PLT_SPEC_NO = "lightpink" ;
        		$P_ERR_CNT ++;
        	}
        	if ($pallet_size_type eq "" ) {
        		$BG_PALLET_SIZE_TYPE = "lightpink" ;
        		$P_ERR_CNT ++;
        	}
        	if ($operation_time eq "" ) {
        		$BG_OPERATION_TIME = "lightpink" ;
        		$P_ERR_CNT ++;
        	}
        	if ($aging_day eq "" ) {
        		$BG_AGING_DAY = "lightpink" ;
        		$P_ERR_CNT ++;
        	}
        	
        	if ($P_ERR_CNT > 0) {
        		$ERR_MSG = "ERR";
        	}
        }
        # Packing Informationが何か入力されている場合、Packing information No.は必須入力
        if ($plt_spec_no ne "") {
        	$pi_no_err ++;
        }
        if ($pallet_unit_number ne "") {
        	$pi_no_err ++;
        }
        if ($pallet_ctn_number ne "") {
        	$pi_no_err ++;
        }
        if ($pallet_step_ctn_number ne "") {
        	$pi_no_err ++;
        }
        if ($pallet_height ne "") {
        	$pi_no_err ++;
        }
        if ($pallet_width ne "") {
        	$pi_no_err ++;
        }
        if ($pallet_depth ne "") {
        	$pi_no_err ++;
        }
        if ($pallet_size_type ne "") {
        	$pi_no_err ++;
        }
        if ($pi_no_err > 0) {
        	if ($pi_no eq "") {
        		$BG_PI_NO = "lightpink" ;
        		$ERR_MSG  = "ERR";
        	}
        }
        # add 2015/04/21 End
}
#-------------------------
# HTML
#-------------------------
&html_header('ITEM MAINTENANCE');
&title('ITEM MAINTENANCE','IMEJ0004');

print "<FONT SIZE = +1 COLOR='bLUE'>[<B>$TYPES</B>] " ;
if($ERR_MSG eq ""){
   if($delete_chk < 1){
 	print " --- Please push a button to register the following contents with after confirmation. --- " ;
   }else{
 	print "<FONT COLOR='RED'> --- The data can not delete. Please check other MASTER or DATA --- </FONT>" ;
        print  "<CENTER>" ;
        print  "<TABLE border=0>" ;
        $sql = " select count(*) from so_details where bal_qty > 0 and item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE SALES ORDER" ;
            print  "</TD>" ;
            print  "</TR>" ;
         }

        $sql = " select count(*) from sr_header h,sr_details d where  h.sr_no = d.sr_no" ;
        $sql.= "    and  h.sr_date >= trunc(add_months(sysdate,-3),'mm')  and   d.item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE SALES RETURN " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql = " select count(*) from po_details where bal_qty > 0 and item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE PURCHASE ORDER " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql  = " select count(*) from pr_header h,pr_details d where  h.pr_no = d.pr_no " ;
        $sql .= "    and  h.pr_date >= trunc(add_months(sysdate,-3),'mm')  and d.item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE PURCHASE RETURN " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql  = " select count(*) from answer a where a.data_date >= trunc(add_months(sysdate,-3),'mm') " ;
        $sql .= "    and a.item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE SHIPING PLAN " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql  = "select count(*) from do_header h,do_details d where  h.do_no = d.do_no " ;
        $sql .= "   and h.do_date >= trunc(add_months(sysdate,-3),'mm') and d.item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE INVOICE " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql  = " select count(*) from sales s where s.data_date >= trunc(add_months(sysdate,-12),'mm') " ;
        $sql .= "    and s.item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE SALES RESULTS " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql  = " select count(*) from purchase p where p.data_date >= trunc(add_months(sysdate,-12),'mm') " ;
        $sql .= "    and   p.item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE PURCHASE RESULTS " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql  = " select count(*) from  whinventory w where ( w.this_inventory <> 0 " ;
        $sql .= "     or w.last_inventory <> 0  or  w.last2_inventory <> 0) " ;
        $sql .= "    and w.item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE WHINVENTORY " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql  = " select count(*) from transaction t where  t.slip_date >= trunc(add_months(sysdate,-12),'mm') " ;
        $sql .= "    and   t.item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE TRANSACTION " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql = " select count(*) from structure s where s.upper_item_no = '$item_no' or s.lower_item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE BOM " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }

        $sql = " select count(*) from product_plan a where  a.remainder_quantity > 0 and a.item_no = '$item_no' ";
        $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
        ($COUNT) = $cur->fetchrow() ;
        $cur->finish;
        if($COUNT >0){ 
            print  "<TR>" ;
            print  "<TD><FONT COLOR=RED>" ;
            print  "THIS ITEM HAVE PRODUCT_PLAN " ;
            print  "</TD>" ;
            print  "</TR>" ;
        }
        print  "</TABLE>" ;

   }
}else{
 	print "<FONT COLOR='RED'> --- The data which you input include an error. Please confirm an error point, and change it. --- </FONT>" ;
}

print "<BR>" ;
print "<FORM METHOD=Post ACTION=IMEJ0005.cgi?$KEYWORD>" ;
foreach(keys (%in)){
	print "<INPUT TYPE=Hidden NAME=$_ VALUE=\"$$_\">\n" ;
}
print "<TABLE BORDER=0><TBODY><TR><TD>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"><TBODY>" ;
print " <TR><TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>ITEM NO</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ITEM_NO>$item_no</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>STOCK_SUBJECT_CODE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_STOCK_SUBJECT>$SSC[$stock_subject_code]</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>ITEM NAME</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ITEM>$item<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ITEM FLAG</TD>\n" ;
print "   <TD>$IMF[$item_flag]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>DESCRIPTION</TD>\n" ;
print "   <TD>$description<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>ORIGIN</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ORIGIN_CODE>$COU[$origin_code]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MAKER</TD>\n" ;
print "   <TD>$mak<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>CURRENCY</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_CURR_CODE>$CURR[$curr_code]<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>CLASS CODE</TD>\n" ;
print "   <TD>$CLAS[$class_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>EXTERNAL_UNIT_NUMBER</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_EUN>" . &commas("$external_unit_number") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>SAFETY_STOCK</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_SAF>" . &commas("$safety_stock") . "<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>WEIGHT</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_WEI>" . &commas("$weight") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>UNIT of WEIGH</FONT></TD>\n" ;
print "   <TD ALIGN=Center BGCOLOR=$BG_UOM_W>$UNW[$uom_w]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>UNIT of MEASUREMENT</FONT></TD>\n" ;
print "   <TD ALIGN=Center BGCOLOR=$BG_UOM_L>$UNL[$uom_l]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen COLSPAN=3 ALIGN=Right><FONT COLOR=RED>UNIT STOCK RATE:UNIT ENGINEER RATE</FONT></TD>\n" ;
print "   <TD COLSPAN=3 ALIGN=Center BGCOLOR=$BG_UNIT>$unit_stock_rate $UNQ[$unit_stock] ： $unit_engineer_rate $UNQ[$unit_engineering]</TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>CATALOG NO</TD>\n" ;
print "   <TD>$catalog_no<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ITEM CODE</TD>\n" ;
print "   <TD>$item_code<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>SUPPLIERS_PRICE</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_SUP>" . &commas("$suppliers_price") . "<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>DRAWING NO</TD>\n" ;
print "   <TD>$drawing_no<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>DRAWING REV</TD>\n" ;
print "   <TD>$drawing_rev<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>APPLICABLE_MODEL</TD>\n" ;
print "   <TD>$applicable_model<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Right' WIDTH='16%'>LAST_TERM_PRICE</TD>\n" ;
print "   <TD ALIGN='Right' WIDTH='16%'>$last_term_price<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Right' WIDTH='17%'><FONT COLOR=RED>STANDARD_PRICE</FONT></TD>\n" ;
print "   <TD ALIGN='Right' WIDTH='17%' BGCOLOR=$BG_STP>" . &commas("$standard_price") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Right' WIDTH='17%'><FONT COLOR=RED>NEXT_TERM_PRICE</FONT></TD>\n" ;
print "   <TD ALIGN='Right' WIDTH='17%' BGCOLOR=$BG_NTP>" . &commas("$next_term_price") . "<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MANUFACT LEADTIME</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_MAN>$manufact_leadtime Days</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>PURCHASE LEADTIME</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_PUR>$purchase_leadtime Days</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ADJUSTMENT LEADTIME</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_ADJ>$adjustment_leadtime Days</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ISSUE POLICY</TD>\n" ;
print "   <TD>$ISS[$issue_policy]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ISSUE LOT</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_ISS>$issue_lot<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MANUFACT FAIL RATE</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_MFR>$manufact_fail_rate \%</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>SECTION_CODE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_SECTION>$SEC[$section_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>COST PROCESS CODE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_PROCESS>$CPC[$cost_process_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>COST_SUBJECT_CODE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_SUBJECT>$CSC[$cost_subject_code]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><BR></TD>" ;
print "   <TD><BR></TD>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ORDER_POLICY</TD>\n" ;
print "   <TD>$ORP[$order_policy]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MAKER_FLAG</TD>\n" ;
print "   <TD>$MAK[$maker_flag]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ITEM TYPE</TD>\n" ;
print "   <TD COLSPAN=2>$item_type1<BR></TD>\n" ;
print "   <TD COLSPAN=3>$item_type2<BR></TD>\n" ;
print " </TR>" ;
 # FIのみ、客先梱包単位を入力できるようにする
 if ($CONF{'FI_PACKAGE_UNIT'} eq "1") {
     print " <TR>" ;
     print "   <TD BGCOLOR=LightGreen ALIGN='Right'>PACKAGE UNIT NUMBER<BR></TD>\n" ;
     print "   <TD BGCOLOR=$BG_PUN COLSPAN=5>$package_unit_number $UNQ[$unit_package]<BR></TD>\n" ;
     print " </TR>" ;
 }
print "</TBODY></TABLE>" ;
#--------------------------------------------------------------------------------------------------------------------------
# add 2015/04/21 Start
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>CAPACITY</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_CAPACITY>&nbsp;$capacity<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>DATE CODE TYPE</TD>\n" ;
print "   <TD BGCOLOR=$BG_DATE_CODE_TYPE>&nbsp;$date_code_type<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>DATE CODE MONTH</TD>\n" ;
print "   <TD BGCOLOR=$BG_DATE_CODE_MONTH>&nbsp;$date_code_month<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>LABEL TYPE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_LABEL_TYPE>&nbsp;$LABEL_TYPE[$label_type]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MEASUREMENT</TD>\n" ;
print "   <TD COLSPAN=3 BGCOLOR=$BG_MEASUREMENT>&nbsp;$measurement</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>&nbsp;</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Center>HEIGHT</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Center>WIDTH</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Center>DEPTH</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Center COLSPAN=2>UNIT NUMBER</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>INNER BOX</TD>\n" ;
print "   <TD BGCOLOR=$BG_I_HEIGHT ALIGN=Right>$inner_box_height mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_I_WIDTH ALIGN=Right>$inner_box_width  mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_I_DEPTH ALIGN=Right>$inner_box_depth  mm</TD>\n" ;
print "   <TD COLSPAN=2 BGCOLOR=$BG_I_UNIT_NUMBER ALIGN=Right>$inner_box_unit_number PC</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MEDIUM BOX</TD>\n" ;
print "   <TD BGCOLOR=$BG_M_HEIGHT ALIGN=Right>$medium_box_height mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_M_WIDTH ALIGN=Right>$medium_box_width mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_M_DEPTH ALIGN=Right>$medium_box_depth mm</TD>\n" ;
print "   <TD COLSPAN=2 BGCOLOR=$BG_M_UNIT_NUMBER ALIGN=Right>$medium_box_unit_number PC</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>OUTER BOX</TD>\n" ;
print "   <TD BGCOLOR=$BG_O_HEIGHT ALIGN=Right>$outer_box_height mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_O_WIDTH ALIGN=Right>$outer_box_width mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_O_DEPTH ALIGN=Right>$outer_box_depth  mm</TD>\n" ;
print "   <TD COLSPAN=2 BGCOLOR=$BG_O_UNIT_NUMBER ALIGN=Right>$outer_box_unit_number PC</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>PACKING<BR>INFORMATION NO</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_PI_NO>&nbsp;$pi_no</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>PLT SPEC NO</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_PLT_SPEC_NO>&nbsp;$plt_spec_no</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>PALLET SIZE TYPE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_PALLET_SIZE_TYPE>&nbsp;$PALLET_SIZE_TYPE[$pallet_size_type]</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right ROWSPAN=4>PALLET</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Center>HEIGHT</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Center>WIDTH</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Center>DEPTH</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Center COLSPAN=2>UNIT NUMBER</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=$BG_P_HEIGHT ROWSPAN=3 ALIGN=Right>$pallet_height mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_P_WIDTH ROWSPAN=3 ALIGN=Right>$pallet_width mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_P_DEPTH ROWSPAN=3 ALIGN=Right>$pallet_depth mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_P_UNIT_NUMBER COLSPAN=2 ALIGN=Right>$pallet_unit_number PC</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>CTN NUMBER</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>STEP CTN NUMBER</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=$BG_P_CTN_NUMBER ALIGN=Right>$pallet_ctn_number mm</TD>\n" ;
print "   <TD BGCOLOR=$BG_P_STEP_CTN_NUMBER ALIGN=Right>$pallet_step_ctn_number mm</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>OPERATION TIME</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_OPERATION_TIME >&nbsp;$operation_time</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MAN POWER</TD>\n" ;
print "   <TD BGCOLOR=$BG_MAN_POWER >&nbsp;$man_power</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>AGING DAY</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_AGING_DAY >&nbsp;$aging_day</TD>\n" ;
print " </TR>" ;
# add 2015/04/21 End
print "</TD></TR></TBODY></TABLE>" ;

print "<P>\n";
if($ERR_MSG eq "" and $delete_chk < 1 ){print "<input type=submit name=submit value= $TYPES >\n"; }
print<<ENDOFTEXT;
<input type=button value='BACK' onClick='javascript:history.back()'>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

