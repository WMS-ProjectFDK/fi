#
# Program Modification
#    2007/09/14  Sourceの共通化を行い、価格の少数点以下の表示桁数を変更 (standard_price , next_term_price , suppliers_price)
#                    ・少数点以下4桁→6桁への変更は、厦門ユーザからの要望
#                    ・DBは厦門のみ6桁に変更し、他拠点は変更しない(Sourceを共通化する為、表示だけ全拠点6桁で統一)
#    2007/09/14  Sourceの共通化を行い、表示項目の追加を行った。
#                    1. STOCK_ISSUE         → 受入即払出
#                    2. ENGINEER_UNIT       → 技術単位
#                    3. ENGINEER_RATE       → 技術単位率
#    2015/04/21 NTTk)Hino  項目追加（CAPACITY他 計28項目）
#    2015/10/28 NTTk)Hino  項目追加（Labeling To Packaging Day他 計4項目）
#


require '/pglosas/init.pl';

#
$item_no = $in{'item_no'} ;

  if($item_no eq ""){ &err("PLEASE SELECT ITEM_NO") ;}
#
# db open
#
$dbh = &db_open();

#UNIT
$sql = " select unit_code,unit from unit " ;
 $cur = $dbh->prepare($sql) || &err("Err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("Err $DBI::err .... $DBI::errstr") ;
 while((@datas) = $cur->fetchrow){
       $UNIT{$datas[0]} = $datas[1] ;
 }
 $cur->finish();

#STOCK SUBJECT
 $sql  = " select  distinct" ;
 $sql .= "  stock_subject_code," ;
 $sql .= "  stock_subject" ;
 $sql .= " from stock_subject" ;
 $sql .= " where  stock_subject_code !=0" ;
 $sql .= " order by stock_subject_code " ;

 $cur = $dbh->prepare($sql) || &err("STK_SUBJ err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("STK_SUBJ err $DBI::err .... $DBI::errstr") ;
 while(@datas = $cur->fetchrow){
   $SBJ{$datas[0]} = $datas[1] ;
 }
 $cur->finish();


#
# check formed value and make sql
#
     $sql  = " select ";
     $sql .="  i.item_no                    ," ;
     $sql .="  i.item_code                  ," ;
     $sql .="  st.stock_subject             ," ;
     $sql .="  i.item                       ," ;
     $sql .="  i.section_code               ," ;
     $sql .="  s.section                    ," ;
     $sql .="  i.mak          maker         ," ;
     $sql .="  u.country origin             ," ;
     $sql .="  i.item_flag                  ," ;
     $sql .="  itf.flag_name  item_flag_name," ;
     $sql .="  i.description                ," ;
     $sql .="  c.class_code                 ," ;
     $sql .="  c.class_1 || ' ' ||  c.class_2 ||' ' ||  c.class_3 class,";
     $sql .="  i.drawing_no                 ," ;
     $sql .="  i.drawing_rev                ," ;
     $sql .="  i.catalog_no                 ," ;
     $sql .="  i.applicable_model           ," ;
     $sql .="  i.external_unit_number || ' ' ||  u1.unit  number_of_unit ," ;
     $sql .="  u1.unit qty_unit             ," ;
     $sql .="  u4.unit stock_unit  ," ;
     $sql .="  i.unit_stock_rate  stock_rate  ," ;
     $sql .="  u5.unit engineer_unit  ," ;
     $sql .="  i.unit_engineer_rate  engineer_rate  ," ;
     $sql .="  i.weight                     ," ;
     $sql .="  u2.unit weight_unit          ," ;
     $sql .="  u3.unit length_unit          ," ;
     $sql .="  cu.curr_mark    currency     ," ;
     $sql .="  to_char(i.standard_price, '999,999,999,990.00000000') standard_price ," ;
     $sql .="  to_char(i.next_term_price,'999,999,999,990.00000000') next_term_price ," ;
     $sql .="  to_char(i.suppliers_price,'999,999,990.00000000')     suppliers_price," ;
     $sql .="  i.cost_subject_code          ," ;
     $sql .="  i.cost_process_code          ," ;
     $sql .="  nvl(i.manufact_leadtime,0)    || ' DAYS' manufact_leadtime    ," ;
     $sql .="  nvl(i.purchase_leadtime,0)    || ' DAYS' purchase_leadtime    ," ;
     $sql .="  nvl(i.adjustment_leadtime,0)  || ' DAYS' adjustment_leadtime  ," ;
     #-- add 2015/10/28 Start --
     $sql .="  nvl(i.LABELING_TO_PACK_DAY,0)  || ' DAYS' as labeling_to_packaging_day," ;
     $sql .="  nvl(i.ASSEMBLING_TO_LAB_DAY,0) || ' DAYS' as assembling_to_labeling_day," ;
     $sql .="  i.CUSTOMER_ITEM_NO as customer_item_code, " ;
     $sql .="  i.CUSTOMER_ITEM_NAME, " ;
     #-- add 2015/10/28 End --
     $sql .="  get_llc(i.item_no) llc       ," ;
     $sql .="  i.reorder_point              ," ;
     $sql .="  i.level_cont_key             ," ;
     $sql .="  i.manufact_fail_rate         ," ;
     $sql .="  i.maker_flag                 ," ;
     $sql .="  i.issue_policy               ," ;
     $sql .="  i.issue_lot                  ," ;
     $sql .="  i.order_policy               ," ;
     $sql .="  i.safety_stock               ," ;
     $sql .="  i.item_type1                 ," ;
     $sql .="  i.item_type2                 ," ;
     $sql .="  si.stock_issue               ," ;
     $sql .="  to_char(i.upto_date,'dd/mm/yyyy hh24:mi:ss') upto_date," ;
     $sql .="  to_char(i.reg_date,'dd/mm/yyyy hh24:mi:ss') reg_date," ;
     $sql .="  to_char(i.receive_date,'dd/mm/yyyy hh24:mi:ss') last_receive_date," ;
     $sql .="  to_char(i.issue_date,'dd/mm/yyyy hh24:mi:ss') last_issue_date," ;
     $sql .="  i.package_unit_number        ," ;
     $sql .="  u6.unit      unit_package    ," ;
     $sql .="  to_char(i.unit_price_o,   '999999990.0000')   unit_price_org," ;
     $sql .="  to_char(i.unit_price_rate,'99999990.000000')  unit_price_rate," ;
     $sql .="  cu2.curr_mark    unit_currency," ;
     $sql .="  per.person       upto_person_name," ;
    #-- 2015/02/24 Add --
     $sql .="  i.grade_code     grade_code," ;
     $sql .="  i.customer_type  customer_type," ;
     $sql .="  pt.packing_type_comment     packing_type_comment," ;
    #-- 2015/02/24 End --
    #-- add 2015/04/21 Start --
     $sql .="  i.CAPACITY," ;
     $sql .="  i.DATE_CODE_TYPE," ;
     $sql .="  i.DATE_CODE_MONTH," ;
     $sql .="  lt.LABEL_TYPE_NAME as LABEL_TYPE," ;
     $sql .="  i.MEASUREMENT," ;
     $sql .="  i.INNER_BOX_HEIGHT," ;
     $sql .="  i.INNER_BOX_WIDTH," ;
     $sql .="  i.INNER_BOX_DEPTH," ;
     $sql .="  i.INNER_BOX_UNIT_NUMBER," ;
     $sql .="  i.MEDIUM_BOX_HEIGHT," ;
     $sql .="  i.MEDIUM_BOX_WIDTH," ;
     $sql .="  i.MEDIUM_BOX_DEPTH," ;
     $sql .="  i.MEDIUM_BOX_UNIT_NUMBER," ;
     $sql .="  i.OUTER_BOX_HEIGHT," ;
     $sql .="  i.OUTER_BOX_WIDTH," ;
     $sql .="  i.OUTER_BOX_DEPTH," ;
     $sql .="  i.external_unit_number as OUTER_BOX_UNIT_NUMBER ," ;
     $sql .="  pi.PI_NO as PACKING_INFORMATION_NO," ;
     $sql .="  pi.PLT_SPEC_NO," ;
     $sql .="  ps.PALLET_SIZE_TYPE_NAME as PALLET_SIZE_TYPE," ;
     $sql .="  pi.PALLET_HEIGHT," ;
     $sql .="  pi.PALLET_WIDTH," ;
     $sql .="  pi.PALLET_DEPTH," ;
     $sql .="  pi.PALLET_UNIT_NUMBER," ;
     $sql .="  pi.PALLET_CTN_NUMBER," ;
     $sql .="  pi.PALLET_STEP_CTN_NUMBER," ;
     $sql .="  i.OPERATION_TIME," ;
     $sql .="  i.MAN_POWER," ;
     $sql .="  i.AGING_DAY " ;
    #-- add 2015/04/21 End --
     chop($sql);
     $sql .= " from class c,";
     $sql .= "	   item i,";
     $sql .= "	   section s,";
     $sql .= "	   country u,";
     $sql .= "	   currency cu,";
     $sql .= "	   unit u1,";
     $sql .= "	   unit u2,";
     $sql .= "	   unit u3,";
     $sql .= "	   unit u4,";
     $sql .= "     unit u5,";
     $sql .= "     unit u6,";
     $sql .= "	   stock_issue si,";
     $sql .= "	   stock_subject st,";
     $sql .= "     currency  cu2," ;
     $sql .= "     itemflag  itf," ;
     $sql .= "     person    per," ;
     $sql .= "     packing_type  pt," ;
     #-- add 2015/04/21 start
     $sql .= "     PACKING_INFORMATION  pi," ; 
     $sql .= "     LABEL_TYPE  lt," ;
     $sql .= "     PALLET_SIZE_TYPE  ps," ;
     #-- add 2015/04/21 end
     
     chop($sql);
     $sql .= " where i.delete_type is null ";
     $sql .= " and  (    1=0 " ;
     $sql .= "        or i.item like '%$word%'";
     $sql .= "        or i.description like '%$word%'";
     $sql .= "        or i.item_no like '%$word%'";
     $sql .= "      ) " ;
     $sql .= " and   i.item_no = '$item_no' " ;
     $sql .= " and   i.origin_code = u.country_code (+) ";
     $sql .= " and   i.class_code = c.class_code (+) ";
     $sql .= " and   i.section_code = s.section_code (+) ";
     $sql .= " and   i.curr_code = cu.curr_code (+) ";
     $sql .= " and   i.uom_q = u1.unit_code (+) ";
     $sql .= " and   i.uom_w = u2.unit_code (+) ";
     $sql .= " and   i.uom_l = u3.unit_code (+) ";
     $sql .= " and   i.unit_stock = u4.unit_code (+) ";
     $sql .= " and   i.unit_engineering = u5.unit_code (+) ";
     $sql .= " and   i.unit_package = u6.unit_code (+) ";
     $sql .= " and   i.stock_subject_code = st.stock_subject_code (+) ";
     $sql .= " and   i.unit_curr_code = cu2.curr_code (+) " ;
     $sql .= " and   i.item_flag = itf.item_flag (+) " ;
     $sql .= " and   i.stock_issue_flag = si.stock_issue_flag (+) ";
     $sql .= " and   i.upto_person_code = per.person_code (+) " ;
     $sql .= " and   i.package_type = pt.packing_type_jpn(+) " ;
     #-- add 2015/04/21 start
     $sql .= " and   i.PI_NO = pi.PI_NO(+) " ;
     $sql .= " and   i.LABEL_TYPE = lt.LABEL_TYPE_CODE(+) " ;
     $sql .= " and   pi.PALLET_SIZE_TYPE = ps.PALLET_SIZE_TYPE_CODE(+) " ;
     #-- add 2015/04/21 end
     $sql .= " order by i.stock_subject_code,i.item,i.description,i.item_no " ;
 $cur = $dbh->prepare($sql) || &err("Err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("Err $DBI::err .... $DBI::errstr") ;

 while((@datas) = $cur->fetchrow){
     for($i=0;$i<@datas;$i++){
       ${$cur->{NAME}->[$i]} = $datas[$i] ;
       push(@FIELD,"$cur->{NAME}->[$i]") ;
     }

 }
 $cur->finish();

#
# HTML
#
&html_header("ITEM Master VIEW");
&title();
print ("ITEM Master VIEW");
print "<P>" ;

print<<ENDOFTEXT;
<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=0>
 <TR><TD ALIGN=Right><FONT COLOR=Blue>ITEM NO : </FONT></TD><TD>$ITEM_NO</TD>
 <TR>
   <TD ALIGN=Right><FONT COLOR=Blue>ITEM : </FONT></TD><TD>$ITEM</TD>
   <TD ALIGN=Right><FONT COLOR=Blue> DESCRIPTION : </FONT></TD><TD>$DESCRIPTION</TD>
 </TR>
</TABLE>
<P>
ENDOFTEXT

print "<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=1>\n" ;
for($i=0;$i<@FIELD;$i++){
   $NAME = $FIELD[$i] ; $NAME =~ s/\_/ /g ;
   $VAL  = ${$FIELD[$i]} ;
   if($VAL =~ /[^0-9 .]/ ){$ALGIN = "Left" ;}else{$ALGIN="Right" ;}
   print "<TR><TD BGCOLOR=#CCFFCC>$NAME</TD><TD ALIGN=$ALGIN BGCOLOR=#CCCCFF>$VAL<BR></TD></TR>\n" ;
}
print "</TABLE>\n" ;

print <<ENDOFTEXT ;
<HR>
[<A HREF=javascript:history.back()>BACK</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;