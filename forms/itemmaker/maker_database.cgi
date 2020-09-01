#!/usr/local/bin/perl
#
# Require init-file
#
#  2002/02/27 Y.YAMANAKA
#     2003/12/04  在庫変換率を登録出来るように変更   By R.Tsutsui
#     2006/03/03  LANKのチェック方法変更             By Y.Hagai

require '/pglosas/init.pl';
#require 'init.pl';

&html_header("PARTS GROUP Master Listing");
&title();

#
# db open
#
$dbh = &db_open();
#
#SQL
#
#データの受け取り
#



print <<ENDOFTEXT ;

<script language='javascript'>
<!--//

//-->
</script>
ENDOFTEXT

#データの受け取り
$ITEM_NO			= $in{'item_no'} ;
$ITEM				= $in{'item'} ;
$COMPANY			= $in{'company'} ;
$LINE_NO			= $in{'line_no'} ;
$ALTER_PROCEDURE	= $in{'alter_procedure'} ;
$SUPPLIER_CODE		= $in{'station_code'} ;

$PURCHASE_LEADTIME	= $in{'purchase_leadtime'} ;
$MINIMUM_ORDER_LOT	= $in{'minimum_order_lot'} ;
$SPLIT_ORDER_LOT	= $in{'split_order_lot'} ;
$DELIVERY_LOT		= $in{'delivery_lot'} ;

$ESTIMATE_PRICE		= $in{'estimate_price'} ;
$MATERIAL_COST		= $in{'material_cost'} ;
$SEMIFINISH_COST	= $in{'semifinish_cost'} ;
$MANUFACTURING_COST	= $in{'manufacturing_cost'} ;
$ADJUSTMENT_PRICE	= $in{'adjustment_price'} ;
$TRIAL_SAMPLE_PRICE	= $in{'trial_sample_price'} ;

$UNIT_PURCHASE_RATE = $in{'unit_purchase_rate'} ;
$PURCHASE_UNIT      = $in{'purchase_unit'} ;
$DELIVERY_PO_DATE	= $in{'delivery_po_date'} ;

#defolut
if($PURCHASE_LEADTIME eq ""){ $PURCHASE_LEADTIME = "null" ; }
if($MINIMUM_ORDER_LOT eq ""){ $MINIMUM_ORDER_LOT = "null" ; }
if($SPLIT_ORDER_LOT   eq ""){ $SPLIT_ORDER_LOT   = "null" ; }
if($DELIVERY_LOT      eq ""){ $DELIVERY_LOT      = "null" ; }

if($ESTIMATE_PRICE     eq ""){ $ESTIMATE_PRICE     = 0 ; }
if($MATERIAL_COST      eq ""){ $MATERIAL_COST      = 0 ; }
if($SEMIFINISH_COST    eq ""){ $SEMIFINISH_COST    = 0 ; }
if($MANUFACTURING_COST eq ""){ $MANUFACTURING_COST = 0 ; }
if($ADJUSTMENT_PRICE   eq ""){ $ADJUSTMENT_PRICE   = 0 ; }
if($TRIAL_SAMPLE_PRICE eq ""){ $TRIAL_SAMPLE_PRICE = 0 ; }

 # DELIVERY PO DATEのチェック
  if($DELIVERY_PO_DATE != ""){
     #DELIVERY_PO_DATを'/'で区切る
#      $DELIVERY_PO_DATE = substr($DELIVERY_PO_DATE,0,2) . "/" . substr($DELIVERY_PO_DATE,2,2) . "/" . substr($DELIVERY_PO_DATE,4,4) ;
  }



if($in{'BTN_TYPE'} ne "Delete"){
	#ステイションコードのチェック
	$sql = "SELECT COMPANY_CODE FROM COMPANY where COMPANY_CODE=" . $SUPPLIER_CODE ;

	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	while(@datas = $cur->fetchrow){
	    for($i=0;$i<scalar(@datas);$i++){
	     	${$cur->{NAME}->[$i]} = $datas[$i] ;
	    }

	}

	if($COMPANY_CODE eq ""){
		
		print "<center>\n";
		print "<table WIDTH='90\%'><tr><td>\n";
		print "<font color='red'>This Station Code Not Exist!!</font>\n";
		print "<BR>\n";
		print "<BR>\n";
		print "<A href='javascript:history.back()'>BACK</a>\n";
		print "</td></tr></table>\n";
		print "</center>\n";

		print "<CENTER>\n";
		print "<HR WIDTH=90%>\n";
			print "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=80%>\n";
			print "<TR>\n";
				print "<TD ALIGN=RIGHT>\n";
				print "<FONT SIZE=3 COLOR=#FF0000>Copyright 2001 FDK Corporation. All rights reserved.</FONT>\n";
				print "</TD>\n";
			print "</TR>\n";
			print "</TABLE>\n";
		print "</CENTER>\n";
		exit 0;
	}

}


$CURR_CODE			= $in{'currency'} ;

$WK_string = "";

if($in{'BTN_TYPE'} eq "Insert"){
#新規
	#LINE_NOの取得
	$wl_line_no = 0 ;
	
	$sql  = "select Max(LINE_NO) MAX_LINE from ";
	$sql .= "	      itemmaker where item_no=$ITEM_NO";
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	while(@datas = $cur->fetchrow){
	    for($i=0;$i<scalar(@datas);$i++){
	     	${$cur->{NAME}->[$i]} = $datas[$i] ;
	    }
	    $wl_line_no = $MAX_LINE + 1 ;
	}

    #ランクのチェック
    $sql = "select count(*) from itemmaker              " ;
	$sql .= " where item_no = $ITEM_NO                  " ;
	$sql .= "   and alter_procedure ='$ALTER_PROCEDURE' " ;
#print $sql."<p>";
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	($cnt) = $cur->fetchrow ;
	$cur->finish ;
    if($cnt > 0){ &err("<FONT COLOR=Red>This rank ($ALTER_PROCEDURE) is already used! Please change the rank.</FONT>") ;}
	
	$sql  = "INSERT INTO  ITEMMAKER (        " ;
	$sql .= "            CREATE_DATE,        " ;
	$sql .= "            OPERATION_DATE,     " ;
	$sql .= "            ITEM_NO,            " ;
	$sql .= "            LINE_NO,            " ;
	$sql .= "            ALTER_PROCEDURE,    " ;
	$sql .= "            SUPPLIER_CODE,      " ;
	$sql .= "            PURCHASE_LEADTIME,  " ;
	$sql .= "            MINIMUM_ORDER_LOT,  " ;
	$sql .= "            SPLIT_ORDER_LOT,    " ;
	$sql .= "            DELIVERY_LOT,       " ;
	$sql .= "            ESTIMATE_PRICE,     " ;
	$sql .= "            MATERIAL_COST,      " ;
	$sql .= "            SEMIFINISH_COST,    " ;
	$sql .= "            MANUFACTURING_COST, " ;
	$sql .= "            ADJUSTMENT_PRICE,   " ;
	$sql .= "            TRIAL_SAMPLE_PRICE, " ;
	$sql .= "            CURR_CODE,          " ;
	$sql .= "            UNIT_PURCHASE_RATE, " ;
    if ($DELIVERY_PO_DATE eq ""){
		$sql .=  "       PURCHASE_UNIT)         " ;
	}else{
		$sql .=  "       PURCHASE_UNIT,         " ;
		$sql .=  "       DELIVERY_PO_DATE)      " ;
	}
	$sql .= "    VALUES(                   "  ;
	$sql .= "      sysdate                ,"  ;
	$sql .= "      sysdate                ,"  ;
	$sql .=        $ITEM_NO            . ","  ;
	$sql .=        $wl_line_no         . ","  ;
	$sql .=        $ALTER_PROCEDURE    . ","  ;
	$sql .=  "'" . $SUPPLIER_CODE      . "'," ;
	$sql .=        $PURCHASE_LEADTIME  . ","  ;
	$sql .=        $MINIMUM_ORDER_LOT  . ","  ;
	$sql .=        $SPLIT_ORDER_LOT    . ","  ;
	$sql .=        $DELIVERY_LOT       . ","  ;
	$sql .=        $ESTIMATE_PRICE     . ","  ;
	$sql .=        $MATERIAL_COST      . ","  ;
	$sql .=        $SEMIFINISH_COST    . ","  ;
	$sql .=        $MANUFACTURING_COST . ","  ;
	$sql .=        $ADJUSTMENT_PRICE   . ","  ;
	$sql .=        $TRIAL_SAMPLE_PRICE . ","  ;
	$sql .=  "'" . $CURR_CODE          . "'," ;
	$sql .=  "'" . $UNIT_PURCHASE_RATE . "'," ;
    if ($DELIVERY_PO_DATE eq ""){
		$sql .=  "'" . $PURCHASE_UNIT      . "')" ;
	}else{
		$sql .=  "'" . $PURCHASE_UNIT      . "'," ;
		$sql .=  "TO_DATE(" . $DELIVERY_PO_DATE . ",'ddmmyyyy'))"  ;
	}
#print $sql."<p>";

	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	$dbh->commit;

	$WK_string = "<B>ItemMaker Master New Data Input completed !!</B>";

}elsif($in{'BTN_TYPE'} eq "UpDate"){
#変更
    #ランクのチェック
    $sql = "select count(*) from itemmaker where item_no=$ITEM_NO and line_no !=$LINE_NO and alter_procedure ='$ALTER_PROCEDURE' " ;
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
#print $sql."<p>";
	$cur->execute() ;
	($cnt) = $cur->fetchrow ;
	$cur->finish ;
    if($cnt > 0){ &err("<FONT COLOR=Red>This rank ($ALTER_PROCEDURE) is already used! Please change the rank.</FONT>") ;}


	$sql  = "UPDATE ITEMMAKER SET ";
	$sql .=        "OPERATION_DATE = sysdate                       ,"   ;
	$sql .=        "ALTER_PROCEDURE="     . $ALTER_PROCEDURE    . ","   ;
	$sql .=        "SUPPLIER_CODE='"      . $SUPPLIER_CODE      . "',"  ;
	$sql .=        "PURCHASE_LEADTIME="   . $PURCHASE_LEADTIME  . ","   ;
	$sql .=        "MINIMUM_ORDER_LOT="   . $MINIMUM_ORDER_LOT  . ","   ;
	$sql .=        "SPLIT_ORDER_LOT="     . $SPLIT_ORDER_LOT    . ","   ;
	$sql .=        "DELIVERY_LOT="        . $DELIVERY_LOT       . ","   ;
	$sql .=        "ESTIMATE_PRICE="      . $ESTIMATE_PRICE     . ","   ;
	$sql .=        "MATERIAL_COST="       . $MATERIAL_COST      . ","   ;
	$sql .=        "SEMIFINISH_COST="     . $SEMIFINISH_COST    . ","   ;
	$sql .=        "MANUFACTURING_COST="  . $MANUFACTURING_COST . ","   ;
	$sql .=        "ADJUSTMENT_PRICE="    . $ADJUSTMENT_PRICE   . ","   ;
	$sql .=        "TRIAL_SAMPLE_PRICE="  . $TRIAL_SAMPLE_PRICE . ","   ;
	$sql .=        "CURR_CODE='"          . $CURR_CODE          . "',"  ;
	$sql .=        "UNIT_PURCHASE_RATE='" . $UNIT_PURCHASE_RATE . "',"  ;
    if ($DELIVERY_PO_DATE eq ""){
		$sql .=        "PURCHASE_UNIT='"      . $PURCHASE_UNIT      . "'"   ;
	}else{
		$sql .=        "PURCHASE_UNIT='"      . $PURCHASE_UNIT      . "',"   ;
		$sql .=        "DELIVERY_PO_DATE=to_date("   . $DELIVERY_PO_DATE   . ",'ddmmyyyy')"  ;
	}
	$sql .=  " WHERE ITEM_NO="            . $ITEM_NO ;
	$sql .=  "   AND LINE_NO="            . $LINE_NO ;

#print $sql;
#exit 0 ; 
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	$dbh->commit;

	$WK_string = "<B>ItemMaker Master UpData completed !!</B>";

}elsif($in{'BTN_TYPE'} eq "Delete"){
#削除
	$sql = "DELETE ITEMMAKER " ;
	$sql .=  " WHERE ITEM_NO=" . $ITEM_NO ;
	$sql .=  "   AND LINE_NO=" . $LINE_NO ;
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	$dbh->commit;
	
	$WK_string = "<B>ItemMaker Master Data Dalete completed !!</B>";


}
	print "<center>\n";
	print "<table WIDTH='90\%'><tr><td>\n";
	print "$WK_string\n";
	print "<BR>\n";
	print "<BR>\n";
	print "<A href='maker_inputform.cgi?edpkey=$ITEM_NO&KEYWORD=$KEYWORD'>Return</a>\n";
	print "</td></tr></table>\n";
	print "</center>\n";

	print "<CENTER>\n";
	print "<HR WIDTH=90%>\n";
		print "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=80%>\n";
		print "<TR>\n";
			print "<TD ALIGN=RIGHT>\n";
			print "<FONT SIZE=3 COLOR=#FF0000>Copyright 2001 FDK Corporation. All rights reserved.</FONT>\n";
			print "</TD>\n";
		print "</TR>\n";
		print "</TABLE>\n";
	print "</CENTER>\n";

print "</body>\n";
print "</html>\n";

