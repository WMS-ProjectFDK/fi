# list_company2.pl
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
 $cur->fetchrow ;

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
     $sql .="  i.mak            maker       ," ;
     $sql .="  u.country origin             ," ;
     $sql .="  i.item_flag                  ," ;
     $sql .="  i.description                ," ;
     $sql .= "  c.class_1 || ' ' ||  c.class_2 ||' ' ||  c.class_3 class,";
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
     $sql .="  to_char(i.standard_price,'99999990.0000') standard_price ," ;
     $sql .="  to_char(i.next_term_price,'99999990.0000') next_term_price ," ;
     $sql .="  i.cost_subject_code          ," ;
     $sql .="  i.cost_process_code          ," ;
     $sql .="  nvl(i.manufact_leadtime,0)    || ' DAYS' manufact_leadtime    ," ;
     $sql .="  nvl(i.purchase_leadtime,0)    || ' DAYS' purchase_leadtime    ," ;
     $sql .="  nvl(i.adjustment_leadtime,0)  || ' DAYS' adjustment_leadtime  ," ;
     $sql .="  get_llc(i.item_no) llc       ," ;
     $sql .="  i.reorder_point              ," ;
     $sql .="  i.level_cont_key             ," ;
     $sql .="  i.manufact_fail_rate         ," ;
     $sql .="  i.maker_flag                 ," ;
     $sql .="  i.issue_policy               ," ;
     $sql .="  i.issue_lot                  ," ;
     $sql .="  i.order_policy               ," ;
     $sql .="  i.safety_stock               ," ;
     $sql .="  to_char(i.upto_date,'dd/mm/yyyy hh24:mi:ss') upto_date," ;
     $sql .="  to_char(i.reg_date,'dd/mm/yyyy hh24:mi:ss') reg_date," ;
     $sql .="  to_char(i.receive_date,'dd/mm/yyyy hh24:mi:ss') last_receive_date," ;
     $sql .="  to_char(i.issue_date,'dd/mm/yyyy hh24:mi:ss') last_issue_date," ;
     $sql .="  i.package_unit_number        ," ;
     $sql .="  u6.unit      unit_package    ," ;
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
     $sql .= "	   unit u5,";
     $sql .= "	   unit u6,";
     $sql .= "	   stock_subject st,";
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
     $sql .= " order by i.stock_subject_code,i.item,i.description,i.item_no " ;
 $cur = $dbh->prepare($sql) || &err("Err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("Err $DBI::err .... $DBI::errstr") ;

 while((@datas) = $cur->fetchrow){
     for($i=0;$i<@datas;$i++){
       ${$cur->{NAME}->[$i]} = $datas[$i] ;
       push(@FIELD,"$cur->{NAME}->[$i]") ;
     }

 }


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