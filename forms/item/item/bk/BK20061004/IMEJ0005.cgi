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
	chop($field) ;                 chop($value) ;
	$sql  = " insert into item ($field) values ($value) " ;
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0005_ITEM_NEW err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0005_ITEM_NEW err $DBI::err .... $DBI::errstr") ;
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
	chop($sql) ;
	$sql .= " where item_no = '$item_no'" ;
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0005_COM_CHG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0005_ITEM_CHG err $DBI::err .... $DBI::errstr") ;
}
sub ITEM_DEL{
	#DELETE ITEM
	$sql = "update item set delete_type ='D' where item_no='$item_no' " ;
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0005_COM_DEL err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0005_ITEM_DEL err $DBI::err .... $DBI::errstr") ;
}
sub ITEM_RES{
	#RESTORE ITEM
	$sql = "update item set delete_type = null where item_no='$item_no' " ;
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0005_COM_RES err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0005_ITEM_RES err $DBI::err .... $DBI::errstr") ;
}
#--- ITEM END ----------
