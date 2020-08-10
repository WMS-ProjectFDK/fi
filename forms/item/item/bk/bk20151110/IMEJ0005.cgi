#
# Purpose : REGISTER ITEM MASTER
#
# Program Modification
#    2007/04/09  R.Tsutsui 登録項目に最終登録者(upto_person_code)を追加
#    2015/04/21 NTTk)Hino  項目追加（CAPACITY他 計28項目）
#

#-------------------------
# Require init file
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

 if($item_no eq ""){ &err("Item No is not found.")} ;

if($TYPES eq "NEW"){
   &ITEM_NEW()
}elsif($TYPES eq "CHANGE"){
   &ITEM_CHG()
}elsif($TYPES eq "DELETE"){
   &ITEM_DEL()
}elsif($TYPES eq "UNDELETION"){
   &ITEM_RES()
}

#-------------------------
# COMMIT or ROLLBACK
#-------------------------
 $dbh->commit ;
 #$dbh->rollback ;

#-------------------------
# HTML
#-------------------------
&html_header('ITEM MAINTENANCE');
&title('ITEM MAINTENANCE','IMEJ0005');
 print "<BR><BR>" ;
 print "Registration of an item code \"$item_no\" was completed." ; 
 print "<P>" ;
&html_fooder("IMEJ0001.cgi?$KEYWORD","MENU") ;

exit ;

#-------------------------
# SUBROUTINE
#-------------------------
#--- ITEM START ----------
sub ITEM_NEW{

	# 重複確認
	$sql = "SELECT count(*) FROM ITEM where ITEM_NO ='$item_no' ";
	$cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	($COUNT) = $cur->fetchrow() ;
	$cur->finish;
	if($COUNT >0){ &err("There is already this Item No.") ;}

	# INSERT ITEM
	$field  = "upto_date           ," ; $value  = "sysdate                ," ;
	$field .= "reg_date            ," ; $value .= "sysdate                ," ;
	$field .= "delete_type         ," ; $value .= "NULL                   ," ;
	$field .= "item_no             ," ; $value .= "'$item_no'             ," ;
	$field .= "item_code           ," ; $value .= "'$item_code'           ," ;
	$field .= "item                ," ; $value .= "'$item'                ," ;
	$field .= "item_flag           ," ; $value .= "'$item_flag'           ," ;
	$field .= "origin_code         ," ; $value .= "'$origin_code'         ," ;
	$field .= "description         ," ; $value .= "'$description'         ," ;
	$field .= "class_code          ," ; $value .= "'$class_code'          ," ;
	$field .= "uom_q               ," ; $value .= "'$unit_stock'          ," ;
	$field .= "uom_w               ," ; $value .= "'$uom_w'               ," ;
	$field .= "uom_l               ," ; $value .= "'$uom_l'               ," ;
	$field .= "supplier_code       ," ; $value .= "0                      ," ;
	$field .= "external_unit_number," ; $value .= "'$external_unit_number'," ;
	$field .= "standard_price      ," ; $value .= "'$standard_price'      ," ;
	$field .= "next_term_price     ," ; $value .= "'$next_term_price'     ," ;
	$field .= "suppliers_price     ," ; $value .= "'$suppliers_price'     ," ;
	$field .= "curr_code           ," ; $value .= "'$curr_code'           ," ;
	$field .= "weight              ," ; $value .= "'$weight'              ," ;
	$field .= "stock_subject_code  ," ; $value .= "'$stock_subject_code'  ," ;
	$field .= "cost_subject_code   ," ; $value .= "'$cost_subject_code'   ," ;
	$field .= "cost_process_code   ," ; $value .= "'$cost_process_code'   ," ;
	$field .= "manufact_leadtime   ," ; $value .= "'$manufact_leadtime'   ," ;
	$field .= "purchase_leadtime   ," ; $value .= "'$purchase_leadtime'   ," ;
	$field .= "adjustment_leadtime ," ; $value .= "'$adjustment_leadtime' ," ;
	$field .= "reorder_point       ," ; $value .= "0                      ," ;
	$field .= "llc_code            ," ; $value .= "0                      ," ;
	$field .= "level_cont_key      ," ; $value .= "'$item_no'             ," ;
	$field .= "drawing_no          ," ; $value .= "'$drawing_no'          ," ;
	$field .= "drawing_rev         ," ; $value .= "'$drawing_rev'         ," ;
	$field .= "applicable_model    ," ; $value .= "'$applicable_model'    ," ;
	$field .= "catalog_no          ," ; $value .= "'$catalog_no'          ," ;
	$field .= "issue_policy        ," ; $value .= "'$issue_policy'        ," ;
	$field .= "section_code        ," ; $value .= "'$section_code'        ," ;
	$field .= "manufact_fail_rate  ," ; $value .= "'$manufact_fail_rate'  ," ;
	$field .= "maker_flag          ," ; $value .= "'$maker_flag'          ," ;
	$field .= "unit_stock          ," ; $value .= "'$unit_stock'          ," ;
	$field .= "unit_stock_rate     ," ; $value .= "'$unit_stock_rate'     ," ;
	$field .= "issue_lot           ," ; $value .= "'$issue_lot'           ," ;
	$field .= "safety_stock        ," ; $value .= "'$safety_stock'        ," ;
	$field .= "order_policy        ," ; $value .= "'$order_policy'        ," ;
	$field .= "mak                 ," ; $value .= "'$mak'                 ," ;
	$field .= "unit_engineering    ," ; $value .= "'$unit_engineering'    ," ;
	$field .= "unit_engineer_rate  ," ; $value .= "'$unit_engineer_rate'  ," ;
	$field .= "item_type1          ," ; $value .= "'$item_type1'          ," ;
	$field .= "item_type2          ," ; $value .= "'$item_type2'          ," ;
	$field .= "last_term_price     ," ; $value .= "0                      ," ;
	$field .= "package_unit_number ," ; $value .= "'$package_unit_number' ," ; # F/Iのみ必須入力
	$field .= "unit_package        ," ; $value .= "'$unit_package'        ," ; # F/Iのみ必須入力
	$field .= "upto_person_code    ," ; $value .= "'$KEYWORD'             ," ;
	# add 2015/04/21 Start
	$field .= "capacity               ," ; $value .= "'$capacity'              ," ;
	$field .= "date_code_type         ," ; $value .= "'$date_code_type'        ," ;
	$field .= "date_code_month        ," ; $value .= "'$date_code_month'       ," ;
	$field .= "label_type             ," ; $value .= "'$label_type'            ," ;
	$field .= "inner_box_unit_number  ," ; $value .= "'$inner_box_unit_number' ," ;
	$field .= "inner_box_height       ," ; $value .= "'$inner_box_height'      ," ;
	$field .= "inner_box_width        ," ; $value .= "'$inner_box_width'       ," ;
	$field .= "inner_box_depth        ," ; $value .= "'$inner_box_depth'       ," ;
	$field .= "medium_box_unit_number ," ; $value .= "'$medium_box_unit_number'," ;
	$field .= "medium_box_height      ," ; $value .= "'$medium_box_height'     ," ;
	$field .= "medium_box_width       ," ; $value .= "'$medium_box_width'      ," ;
	$field .= "medium_box_depth       ," ; $value .= "'$medium_box_depth'      ," ;
	$field .= "outer_box_height       ," ; $value .= "'$outer_box_height'      ," ;
	$field .= "outer_box_width        ," ; $value .= "'$outer_box_width'       ," ;
	$field .= "outer_box_depth        ," ; $value .= "'$outer_box_depth'       ," ;
	$field .= "measurement            ," ; $value .= "'$measurement'           ," ;
	$field .= "pi_no                  ," ; $value .= "'$pi_no'                 ," ;
	$field .= "operation_time         ," ; $value .= "'$operation_time'        ," ;
	$field .= "man_power              ," ; $value .= "'$man_power'             ," ;
	$field .= "aging_day              ," ; $value .= "'$aging_day'             ," ;
	# add 2015/04/21 End
	chop($field) ;                 chop($value) ;
	$sql  = " insert into item ($field) values ($value) " ;
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0005_ITEM_NEW err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0005_ITEM_NEW err $DBI::err .... $DBI::errstr") ;
	
	# add 2015/04/21 Start
	if ($pi_no ne "") {
		
		# DELETE PACKING_INFORMATION
		$sql  = " delete from PACKING_INFORMATION where pi_no = '$pi_no' " ;
		#print $sql . "<P>";
		$cur = $dbh->prepare($sql) || &err("PACKING_INFORMATION_NEW_DEL err $DBI::err .... $DBI::errstr") ;
		$cur->execute() || &err("PACKING_INFORMATION_NEW_DEL err $DBI::err .... $DBI::errstr") ;
		
		# INSERT PACKING_INFORMATION
		$field  = "pi_no                  ," ; $value  = "'$pi_no'                  ," ;
		$field .= "plt_spec_no            ," ; $value .= "'$plt_spec_no'            ," ;
		$field .= "pallet_unit_number     ," ; $value .= "'$pallet_unit_number'     ," ;
		$field .= "pallet_ctn_number      ," ; $value .= "'$pallet_ctn_number'      ," ;
		$field .= "pallet_step_ctn_number ," ; $value .= "'$pallet_step_ctn_number' ," ;
		$field .= "pallet_height          ," ; $value .= "'$pallet_height'          ," ;
		$field .= "pallet_width           ," ; $value .= "'$pallet_width'           ," ;
		$field .= "pallet_depth           ," ; $value .= "'$pallet_depth'           ," ;
		$field .= "pallet_size_type       ," ; $value .= "'$pallet_size_type'       ," ;
		chop($field) ;                 chop($value) ;
		$sql  = " insert into PACKING_INFORMATION ($field) values ($value) " ;
		#print $sql . "<P>";
		$cur = $dbh->prepare($sql) || &err("PACKING_INFORMATION_NEW err $DBI::err .... $DBI::errstr") ;
		$cur->execute() || &err("PACKING_INFORMATION_NEW err $DBI::err .... $DBI::errstr") ;
	}
	# add 2015/04/21 End
}
sub ITEM_CHG{
	# UPDATE ITEM
	$sql  = "update item set " ;
	$sql .= "upto_date           = sysdate                ," ;
	$sql .= "delete_type         = NULL                   ," ;
	$sql .= "item_code           = '$item_code'           ," ;
	$sql .= "item                = '$item'                ," ;
	$sql .= "item_flag           = '$item_flag'           ," ;
	$sql .= "origin_code         = '$origin_code'         ," ;
	$sql .= "description         = '$description'         ," ;
	$sql .= "class_code          = '$class_code'          ," ;
	$sql .= "uom_q               = '$unit_stock'          ," ;
	$sql .= "uom_w               = '$uom_w'               ," ;
	$sql .= "uom_l               = '$uom_l'               ," ;
#	$sql .= "supplier_code       = '$supplier_code'       ," ;
	$sql .= "external_unit_number= '$external_unit_number'," ;
	$sql .= "standard_price      = '$standard_price'      ," ;
	$sql .= "next_term_price     = '$next_term_price'     ," ;
	$sql .= "suppliers_price     = '$suppliers_price'     ," ;
	$sql .= "curr_code           = '$curr_code'           ," ;
	$sql .= "weight              = '$weight'              ," ;
	$sql .= "stock_subject_code  = '$stock_subject_code'  ," ;
	$sql .= "cost_subject_code   = '$cost_subject_code'   ," ;
	$sql .= "cost_process_code   = '$cost_process_code'   ," ;
	$sql .= "manufact_leadtime   = '$manufact_leadtime'   ," ;
	$sql .= "purchase_leadtime   = '$purchase_leadtime'   ," ;
	$sql .= "adjustment_leadtime = '$adjustment_leadtime' ," ;
#	$sql .= "reorder_point       = '$reorder_point'       ," ;
#	$sql .= "llc_code            = '$llc_code'            ," ;
#	$sql .= "level_cont_key      = '$level_cont_key'      ," ; #品コードがセットされているので変更不可！
	$sql .= "drawing_no          = '$drawing_no'          ," ;
	$sql .= "drawing_rev         = '$drawing_rev'         ," ;
	$sql .= "applicable_model    = '$applicable_model'    ," ;
	$sql .= "catalog_no          = '$catalog_no'          ," ;
	$sql .= "issue_policy        = '$issue_policy'        ," ;
	$sql .= "section_code        = '$section_code'        ," ;
	$sql .= "manufact_fail_rate  = '$manufact_fail_rate'  ," ;
	$sql .= "maker_flag          = '$maker_flag'          ," ;
	$sql .= "unit_stock          = '$unit_stock'          ," ;
	$sql .= "unit_stock_rate     = '$unit_stock_rate'     ," ;
	$sql .= "issue_lot           = '$issue_lot'           ," ;
	$sql .= "safety_stock        = '$safety_stock'        ," ;
	$sql .= "order_policy        = '$order_policy'        ," ;
	$sql .= "mak                 = '$mak'                 ," ;
	$sql .= "unit_engineering    = '$unit_engineering'    ," ;
	$sql .= "unit_engineer_rate  = '$unit_engineer_rate'  ," ;
	$sql .= "item_type1          = '$item_type1'          ," ;
	$sql .= "item_type2          = '$item_type2'          ," ;
#	$sql .= "last_term_price     = '$last_term_price'     ," ; #前期の値は画面上からは変更不可！
	$sql .= "package_unit_number = '$package_unit_number' ," ; #F/Iのみ必須入力
	$sql .= "unit_package        = '$unit_package'        ," ; #F/Iのみ必須入力
	$sql .= "upto_person_code    = '$KEYWORD'             ," ;
	# add 2015/04/21 Start
	$sql .= "capacity               = '$capacity'              ," ;
	$sql .= "date_code_type         = '$date_code_type'        ," ;
	$sql .= "date_code_month        = '$date_code_month'       ," ;
	$sql .= "label_type             = '$label_type'            ," ;
	$sql .= "inner_box_unit_number  = '$inner_box_unit_number' ," ;
	$sql .= "inner_box_height       = '$inner_box_height'      ," ;
	$sql .= "inner_box_width        = '$inner_box_width'       ," ;
	$sql .= "inner_box_depth        = '$inner_box_depth'       ," ;
	$sql .= "medium_box_unit_number = '$medium_box_unit_number'," ;
	$sql .= "medium_box_height      = '$medium_box_height'     ," ;
	$sql .= "medium_box_width       = '$medium_box_width'      ," ;
	$sql .= "medium_box_depth       = '$medium_box_depth'      ," ;
	$sql .= "outer_box_height       = '$outer_box_height'      ," ;
	$sql .= "outer_box_width        = '$outer_box_width'       ," ;
	$sql .= "outer_box_depth        = '$outer_box_depth'       ," ;
	$sql .= "measurement            = '$measurement'           ," ;
	$sql .= "pi_no                  = '$pi_no'                 ," ;
	$sql .= "operation_time         = '$operation_time'        ," ;
	$sql .= "man_power              = '$man_power'             ," ;
	$sql .= "aging_day              = '$aging_day'             ," ;
	# add 2015/04/21 End
	chop($sql) ;
	$sql .= " where item_no = '$item_no'" ;
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0005_COM_CHG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0005_ITEM_CHG err $DBI::err .... $DBI::errstr") ;
	
	# add 2015/04/21 Start
	if ($pi_no ne "") {
		
		# DELETE PACKING_INFORMATION
		$sql  = " delete from PACKING_INFORMATION where pi_no = '$pi_no' " ;
		#print $sql . "<P>";
		$cur = $dbh->prepare($sql) || &err("PACKING_INFORMATION_NEW_DEL err $DBI::err .... $DBI::errstr") ;
		$cur->execute() || &err("PACKING_INFORMATION_NEW_DEL err $DBI::err .... $DBI::errstr") ;
		
		# INSERT PACKING_INFORMATION
		$field  = "pi_no                  ," ; $value  = "'$pi_no'                  ," ;
		$field .= "plt_spec_no            ," ; $value .= "'$plt_spec_no'            ," ;
		$field .= "pallet_unit_number     ," ; $value .= "'$pallet_unit_number'     ," ;
		$field .= "pallet_ctn_number      ," ; $value .= "'$pallet_ctn_number'      ," ;
		$field .= "pallet_step_ctn_number ," ; $value .= "'$pallet_step_ctn_number' ," ;
		$field .= "pallet_height          ," ; $value .= "'$pallet_height'          ," ;
		$field .= "pallet_width           ," ; $value .= "'$pallet_width'           ," ;
		$field .= "pallet_depth           ," ; $value .= "'$pallet_depth'           ," ;
		$field .= "pallet_size_type       ," ; $value .= "'$pallet_size_type'       ," ;
		chop($field) ;                 chop($value) ;
		$sql  = " insert into PACKING_INFORMATION ($field) values ($value) " ;
		#print $sql . "<P>";
		$cur = $dbh->prepare($sql) || &err("PACKING_INFORMATION_NEW err $DBI::err .... $DBI::errstr") ;
		$cur->execute() || &err("PACKING_INFORMATION_NEW err $DBI::err .... $DBI::errstr") ;
	}
	# add 2015/04/21 End
}
sub ITEM_DEL{
	#DELETE ITEM
	#$sql = "update item set delete_type ='D' where item_no='$item_no' " ;
	$sql  = " update item set " ;
	$sql .= "     delete_type ='D'," ;
	$sql .= "     upto_person_code = '$KEYWORD' " ;
	$sql .= " where item_no='$item_no' " ;
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0005_COM_DEL err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0005_ITEM_DEL err $DBI::err .... $DBI::errstr") ;
}
sub ITEM_RES{
	#RESTORE ITEM
	#$sql = "update item set delete_type = null where item_no='$item_no' " ;
	$sql  = " update item set " ;
	$sql .= "     delete_type = null," ;
	$sql .= "     upto_person_code = '$KEYWORD' " ;
	$sql .= " where item_no='$item_no' " ;
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0005_COM_RES err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0005_ITEM_RES err $DBI::err .... $DBI::errstr") ;
}
#--- ITEM END ----------
