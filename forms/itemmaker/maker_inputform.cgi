#!/usr/local/bin/perl
#
# Require init-file
#
#  2002/02/27 Y.YAMANAKA
#     2003/12/04  �݌ɕϊ�����o�^�o����悤�ɕύX   By R.Tsutsui
#
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
#�f�[�^�̎󂯎��
#

if($KEYWORD eq ""){
	print "<B>Err : Login Failed</B>\n;";
}




print <<ENDOFTEXT ;

<script language='javascript'>
<!--//


function CheckDelete( ){

        var string  = "Data will be cleared�H";

        answer = confirm (string);

        if ( answer ) {
                return true;
        } else {
                return false;
        }
}

	//���l�`�F�b�N
	function MyIsNaN(wk_num){
		
		syousuuten = false;

		for(cnt=0;cnt < wk_num.length ; cnt++){
			
			if(!(wk_num.charAt(cnt) == "0" || wk_num.charAt(cnt) == "1" ||
				 wk_num.charAt(cnt) == "2" || wk_num.charAt(cnt) == "3" ||
				 wk_num.charAt(cnt) == "4" || wk_num.charAt(cnt) == "5" ||
				 wk_num.charAt(cnt) == "6" || wk_num.charAt(cnt) == "7" ||
				 wk_num.charAt(cnt) == "8" || wk_num.charAt(cnt) == "9" )){
				
				if( cnt == 0 && (wk_num.charAt(cnt) == "-" || wk_num.charAt(cnt) == "+"))	continue;
				
				if( wk_num.charAt(cnt) == "." && syousuuten == false ){
					syousuuten = true ;
					continue;
				}
				return true;
			}
		}
		return false;
	}
	//********************************
	//  MYIsNumeric : ���l�`�F�b�N�ƌ����`�F�b�N
	// Fname �t���[����
	// sei_k ��������
	// syo_k ��������
	//********************************
	function MYIsNumeric(Fname,sei_k,syo_k){
		
		chack_num = document.inputform[Fname].value;

		if(MyIsNaN(chack_num)){
			alert("The " + chack_num + " is not Number");
			document.inputform[Fname].value="";
			return false;
		}
		
		//�����A�����������Ƃ��ɂO�̂Ƃ��̓`�F�b�N���Ȃ�
		if(sei_k == 0 && syo_k == 0){
			return true;
		}
		
		str_len = (chack_num).length;
		point   = (chack_num).indexOf(".",0);

		msg_str = ""
		for(i_cnt=0;i_cnt<sei_k;i_cnt++){ msg_str += "9" ; }
		msg_str += "." ;
		for(i_cnt=0;i_cnt<syo_k;i_cnt++){ msg_str += "9" ; }

		if(point != -1 ){
			if( point > sei_k){
				alert("Max value is " + msg_str);
				document.inputform[Fname].value="";
				return false;
			}
			if(syo_k == 0){
				alert("The Number of decimals is ��");
				document.inputform[Fname].value="";
				return false;
			}
			if(str_len - (point+1) > syo_k ){
				alert("Max value is " + msg_str);
				document.inputform[Fname].value="";
				return false;
			}
		}else{
			if( str_len > sei_k){
				alert("Max value is " + msg_str);
				document.inputform[Fname].value="";
				return false;
			}
		}
		return true;
	}

	//********************************
	//  Moji_lenChack : �������`�F�b�N
	// Fname �t���[����
	// str_len ������
	//********************************
	function Moji_lenChack(ThisForm,Fname,str_len){
		
		wk_len = getLength(ThisForm[Fname].value);

		if (wk_len > str_len){

			//alert("���p���͂�" + str_len + "�����܂ł̓��͂ł�");
			ThisForm[Fname].value = "";
			return false;
		}
		
		if(!(SQLStrCHK(ThisForm[Fname].value))){
			ThisForm[Fname].value = "";
			return false;
		}
		
		return true;
		
	}


	function SQLStrCHK(jugStr){
		for(i=0; i<jugStr.length; i++){
			if ( jugStr.charAt(i) == "%" || jugStr.charAt(i) == "�" ){
				alert("Can't use sign or % or � ");
				return false;
			}
		}
		return true;
		
	}

	var kana_max = 51;
	var Hankakukana =  new Array("�","�","�","�","�","�","�","�","�","�",
								 "�","�","�","�","�","�","�","�","�","�",
								 "�","�","�","�","�","�","�","�","�","�",
								 "�","�","�","�","�","�","�","�",
								 "�","�","�","�","�","�","�","�",
								 "�","�","�","�","�");

	function getLength(moji) 
	{ 
		var i,cnt = 0; 
		for(i=0; i<moji.length; i++){
			if(escape(moji.charAt(i)).length >= 4 ){
				for(ii=0;ii<kana_max;ii++){
					if(Hankakukana[ii] == moji.charAt(i)){
						break;
					}
				}
				if(ii<kana_max){
					cnt++;
				}else{
					cnt+=2;
				} 
			}else{
				 cnt++;
			}
		}
		return cnt; 
	} 


	function GO_main(){
		
		location.href = "../../../main.asp?KEYWORD=$KEYWORD&menuType=master";
	}
 function new_item(theForm,nos){
      w3 = window.open('item2.pl?nos=' + nos,'itm','WIDTH=600,HEIGHT=300,SCROLLBARS=yes,RESIZABLE=yes') ;
 }
//-->
</script>
ENDOFTEXT

#�L�����Z����
if($in{'BTN_TYPE'} eq "Cancel"){  $in{'edpkey'}= ""; }

#�X�V��
if($in{'BTN_TYPE'} eq "UpDate"){ 

#	$sql  = "UPDATE ITEM SET ORDER_POLICY=" . $in{'maker_flag'};
#	$sql .= " , SUPPLIERS_PRICE=" . $in{'suppliers_price'};
#	$sql .= " WHERE ITEM_NO=" . $in{'edpkey'} ;
	$sql  = "UPDATE ITEM SET  " ;
	$sql .= "  SUPPLIERS_PRICE='$in{'suppliers_price'}'" ;
	$sql .= " WHERE ITEM_NO=" . $in{'edpkey'} ;


	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	$dbh->commit;
}



#EDPKEY �����͂���Ă���Ƃ�
if($in{'edpkey'} ne ""){
	
	$sql  = "select MAK, ITEM,DESCRIPTION,ITEM_NO,ORDER_POLICY, DRAWING_NO,";
	$sql .= "       CATALOG_NO, SUPPLIERS_PRICE, STANDARD_PRICE,s.STOCK_SUBJECT,u.UNIT";
	$sql .= "  from item i,";
	$sql .= "       unit u,";
	$sql .= "       stock_subject s ";
	$sql .= " where item_no=" . $in{'edpkey'};
	$sql .= "   and i.delete_type is null ";
	$sql .= "   and i.unit_stock = u.unit_code (+) ";
	$sql .= "   and i.stock_subject_code = s.stock_subject_code (+) ";
	
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	while(@datas = $cur->fetchrow){
	    for($i=0;$i<scalar(@datas);$i++){
	     	${$cur->{NAME}->[$i]} = $datas[$i] ;
	    }
	}
	
}

print "<center>ITEM MAKER MENTENANCE</center>\n";

print "<hr width=90%>\n";

print "<center>\n";

	print "<form action='maker_inputform.cgi' method='post' name='inputform'>\n";
	print "<table border=0 cellspacing=1 cellpadding=1 width=680>\n";
		print "<tr>\n";

		if($ITEM_NO eq "" ){
			print "<th width=80 bgcolor='#ccffcc'><h5>ITEM NO.</th>\n";
			print "<td width=100 bgcolor='#ccccff'><input type='text' name='edpkey' value='' size=15 maxlength=13 onChange='MYIsNumeric(\"edpkey\",8.0)'>\n";
			print "<A HREF='javascript:new_item(this.form)'><IMG SRC=$IMG_DIR/set.gif BORDER=0></A>\n";
			print "</td>\n";
			print "<th width=100><input type='submit' NAME=BTN_TYPE value='Show'></th>\n";
			print "<th width=100><BR></th>\n";
			print "<th width=100><BR></th>\n";
			print "<th width=100><BR></th>\n";
			print "<th width=100><input type='Button' NAME=BTN_TYPE value='End' onClick='return GO_main()'></th>\n";
			print "<input type='hidden' name='chk_bt' value='Show'>\n";
		}else{
			print "<th width=80 bgcolor='#ccffcc'><h5>EDPKEY</th>\n";
			print "<th width=100 bgcolor='#ccccff'>$ITEM_NO<input type=hidden name='edpkey' value='$ITEM_NO'></th>\n";
			print "<th width=100><BR></th>\n";
			print "<th width=100><input type='submit' NAME=BTN_TYPE value='UpDate'></th>\n";
			print "<th width=100><BR></th>\n";
			print "<th width=100><input type='submit' NAME=BTN_TYPE value='Cancel'></th>\n";
			print "<th width=100><input type='Button' NAME=BTN_TYPE value='End' onClick='return GO_main()'></th>\n";
			print "<input type='hidden' name='chk_bt' value='UpDate'>\n";
		}
		print "<input type='hidden' name='KEYWORD' value='$KEYWORD'>\n";
		print "</tr>\n";
	print "</table>\n";

print "<hr width=70%>\n";

print "<table border=0 cellspacing=1 cellpadding=1 width=600>\n";
	print "<tr>\n";
		if($in{'edpkey'} ne "" && $ITEM_NO eq ""){
			print "<td align=center><font color=red><B>ITEM NO.</B> Not Exist</font></td>\n";
		}else{
			print "<td align=center><B>Item</B> Master or <B>ItemMaker</B> Master Mentenance</td>\n";
		}
	print "</tr>\n";
print "</table>\n";

	print "<hr width=70%>\n";

print "<table border=0 cellspacing=1 cellpadding=1>\n";
	print "<tr>\n";
		print "<th width=80 bgcolor='#ccffcc'>MAKER</th>\n";
		print "<td width=90 align=left bgcolor='#ccccff' COLSPAN=5>" . $MAK . "<BR></td>\n";
print "</tr>\n";
print "<tr>\n";
#		print "<th NOWRAP bgcolor='#ccffcc'>Maker Flag</th>\n";
#		print "<td width=60 align=center bgcolor='#ccccff'>\n";
#		print "<select name='maker_flag'>\n";
#		print "<option value=''>\n";
#		$sql = " select MAKER_FLAG_CODE, MAKER_FLAG from MAKERFLAG order by MAKER_FLAG_CODE ";
#		$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
#		$cur->execute() ;
#		while(@datas = $cur->fetchrow){
#		    for($i=0;$i<scalar(@datas);$i++){ ${$cur->{NAME}->[$i]} = $datas[$i] ; }
#		    if($ORDER_POLICY eq $MAKER_FLAG_CODE){
#				print "<option selected value='$MAKER_FLAG_CODE'>$MAKER_FLAG_CODE : $MAKER_FLAG \n";
#			}else{
#				print "<option value='$MAKER_FLAG_CODE'>$MAKER_FLAG_CODE : $MAKER_FLAG \n";
#			}
#		}
#		print "</select>\n";
#		print "</td>\n";
		print "<th NOWRAP bgcolor='#ccffcc'>ITEM</th>\n";
		print "<td colspan=1 align=left bgcolor='#ccccff' NOWRAP>" . $ITEM . "<BR></td>\n";
		print "<th NOWRAP bgcolor='#ccffcc'>DESCRIPTION</th>\n";
		print "<td colspan=3 align=left bgcolor='#ccccff' NOWRAP>" . $DESCRIPTION . "<BR></td>\n";
		print "</tr>\n";
		print "<tr>\n";
		print "<th NOWRAP bgcolor='#ccffcc'>UNIT</th>\n";
		print "<td width=60 align=center bgcolor='#ccccff'>$UNIT<BR></td>\n";
		print "<th NOWRAP bgcolor='#ccffcc'>DRAWING NO.</th>\n";
		print "<td width=100 align=left bgcolor='#ccccff'>$DRAWING_NO<BR></td>\n";
		print "<th NOWRAP bgcolor='#ccffcc'>STOCK SUBJECT</th>\n";
		print "<td NOWRAP align=left bgcolor='#ccccff'>$STOCK_SUBJECT<BR></td>\n";
	print "</tr>\n";
	print "<tr>\n";
		print "<th bgcolor='#ccffcc'>CAT NO.</th>\n";
		print "<td width=50 align=left bgcolor='#ccccff'>$CATALOG_NO<BR></td>\n";
		print "<th bgcolor='#ccffcc'>INSPECTION</th>\n";
		print "<td bgcolor='#ccccff' colspan=3><BR></td>\n";
	print "</tr>\n";
	print "<tr>\n";
		print "<th NOWRAP bgcolor='#ccffcc'>STANDARD PRICE</th>\n";
		print "<td width=180 align=right bgcolor='#ccccff'>$STANDARD_PRICE<BR></td>\n";
		print "<th NOWRAP bgcolor='#ccffcc'>SUPPLIERS PRICE</th>\n";
		print "<td width=180 bgcolor='#ccccff' COLSPAN=3><input type='text' name='suppliers_price' value='$SUPPLIERS_PRICE' size=20 maxlength=15></td>\n";
	print "</tr>\n";
print "</table>\n";

print "<table border=0 cellspacing=1 cellpadding=1>\n";
#	print "<tr>\n";
#		print "<th width=90 bgcolor='#ccffcc'><h5>��P��</th>\n";
#		print "<td width=180 bgcolor='#ccccff'><input type='text' name='base_price' value='1.8' size=20 maxlength=15 onBlur='baseprice_check( )'></td>\n";
#		print "<th width=90 bgcolor='#ccffcc'><h5>�O���</th>\n";
#		print "<td width=180 align=right bgcolor='#ccccff'><BR></td>\n";
#	print "</tr>\n";
print "</table>\n";
print "</form>\n";

print "<hr width=90%><br>\n";

if($ITEM_NO ne ""){
	print "<table border=0 cellspacing=1 cellpadding=1 width=680>\n";
	print "<form action='maker_input.cgi' method='post' name='inputform2'>\n";
	print "<tr>\n";
			print "<td><input type='submit' name='chk_bt' value='ADD'>\n";
			print "<input type='hidden' name='KEYWORD' value='$KEYWORD'>\n";
			print "<input type='hidden' name='buyer_code2' value='08'>\n";
			print "<input type='hidden' name='maker_flag2' value='1'>\n";
			print "<input type='hidden' name='eco_flag2' value=''>\n";
			print "<input type='hidden' name='suppliers_price2' value='0'>\n";
			print "<input type='hidden' name='base_price2' value='1.8'>\n";
			print "<input type='hidden' name='item_no' value='$in{'edpkey'}'>\n";
			print "</td>\n";
			print "</tr>\n";
	print "</form>\n";
	print "</table>\n";
}

#FL�̂݁APO_STK_RATE(�݌ɕϊ���)��\������  2003/12/2  By R.Tsutsui
$rows = ($CONF{'PO_STK_RATE'} ne "1"  ?  3 : 3) ;  

print "<table border=0 cellspacing=1 cellpadding=1>\n";
print "<tr>\n";
		print "<th width=30 rowspan=$rows bgcolor='#ccffcc'><h5>RANK</th>\n";
		print "<th width=40 bgcolor='#ccffcc'><h5>PURCHASE LEADTIME</th>\n";
		print "<th width=50 bgcolor='#ccffcc'><h5>STATION</th>\n";
		print "<th width=180 colspan=2 bgcolor='#ccffcc'><h5>STATION NAME</th>\n";
		print "<th width=120 bgcolor='#ccffcc'><h5>ESTIMATE PRICE</th>\n";
		print "<th width=120 bgcolor='#ccffcc'><h5>TRIAL SAMPLE PRICE</th>\n";
		print "<th width=120 bgcolor='#ccffcc'><h5>ADJUSTMENT PRICE</th>\n";
		print "<th width=50 rowspan=$rows bgcolor='#ccffcc'><BR></th>\n";
print "</tr>\n";
print "<tr>\n";
		print "<th width=90 colspan=2 bgcolor='#ccffcc'><h5>MINIMUN ORDER LOT</th>\n";
		print "<th width=90 bgcolor='#ccffcc'><h5>SPLIT ORDER LOT</th>\n";
		print "<th width=90 bgcolor='#ccffcc'><h5>DELIVERY LOT</th>\n";
		print "<th width=120 bgcolor='#ccffcc'><h5>MANUFACTUREING COST</th>\n";
		print "<th width=120 bgcolor='#ccffcc'><h5>MATERIAL COST</th>\n";
		print "<th width=120 bgcolor='#ccffcc'><h5>SEMIFINISH COST</th>\n";
print "</tr>\n";

#FL�̂݁A�݌ɕϊ�����\��
if ($CONF{'PO_STK_RATE'} eq "1") {
	print "<tr>\n";
		print "<th width=90 colspan=2 bgcolor='#ccffcc'><h5>DELIVERY PO DATE</th>\n";
		print "<th width=90  colspan=3 bgcolor='#ccffcc'><BR></th>\n";
		print "<th width=120 bgcolor='#ccffcc'><h5>P/O SHEET RATE</th>\n";
		print "<th width=120 bgcolor='#ccffcc'><h5>P/O SHEET UNIT</th>\n";
	print "</tr>\n";
}else{
	print "<tr>\n";
		print "<th width=90 colspan=2 bgcolor='#ccffcc'><h5>DELIVERY PO DATE</th>\n";
		print "<th width=90 bgcolor='#ccffcc'></th>\n";
		print "<th width=90 bgcolor='#ccffcc'></th>\n";
		print "<th width=120 bgcolor='#ccffcc'></th>\n";
		print "<th width=120 bgcolor='#ccffcc'></th>\n";
		print "<th width=120 bgcolor='#ccffcc'></th>\n";
	print "</tr>\n";
}


if($in{'edpkey'} ne ""){
	#ITEMMAKER ����
	$sql  = "select cm.company, ";
	$sql .= "       itm.ITEM_NO,itm.LINE_NO,";
	$sql .= "       itm.ALTER_PROCEDURE,itm.SUPPLIER_CODE,";
	$sql .= "       itm.PURCHASE_LEADTIME,itm.MINIMUM_ORDER_LOT,";
	$sql .= "       itm.SPLIT_ORDER_LOT,itm.DELIVERY_LOT,";
	$sql .= "       TO_CHAR(itm.ESTIMATE_PRICE    ,'9999999990.999999') ESTIMATE_PRICE,";
	$sql .= "       TO_CHAR(itm.MATERIAL_COST     ,'9999999990.999999') MATERIAL_COST,";
	$sql .= "       TO_CHAR(itm.SEMIFINISH_COST   ,'9999999990.999999') SEMIFINISH_COST,";
	$sql .= "       TO_CHAR(itm.MANUFACTURING_COST,'9999999990.999999') MANUFACTURING_COST,";
	$sql .= "       TO_CHAR(itm.ADJUSTMENT_PRICE  ,'9999999990.999999') ADJUSTMENT_PRICE,";
	$sql .= "       TO_CHAR(itm.TRIAL_SAMPLE_PRICE,'9999999990.999999') TRIAL_SAMPLE_PRICE,";
	$sql .= "       itm.CURR_CODE,cu.CURR_MARK,";
	$sql .= "       itm.UNIT_PURCHASE_RATE," ;
	$sql .= "       u.unit   PURCHASE_UNIT, " ;
	$sql .= "       TO_CHAR(itm.DELIVERY_PO_DATE,'DDMMYYYY') DELIVERY_PO_DATE";
	$sql .= "  from itemmaker itm,company cm,currency cu,";
	$sql .= "       unit u " ;
	$sql .= " where item_no=" . $in{'edpkey'};
	$sql .= "   and itm.SUPPLIER_CODE=cm.COMPANY_CODE(+)";
	$sql .= "   and itm.CURR_CODE=cu.CURR_CODE(+)";
	$sql .= "   and itm.purchase_unit = u.unit_code(+) " ;
	$sql .= " order by itm.ALTER_PROCEDURE ";
	
$wk_cnt = 0;
$wk_color="";
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	while(@datas = $cur->fetchrow){
	    for($i=0;$i<scalar(@datas);$i++){
	     	${$cur->{NAME}->[$i]} = $datas[$i] ;
	    }
		
		if($wk_color eq "#ccccff"){
			$wk_color = "#99FFFF";
		}else{
			$wk_color="#ccccff";
		}
		print "<form action='maker_input.cgi' method='post' name='inputform$wk_cnt'>\n";
		print "<tr align=center>\n";
			print "<td nowrap rowspan=$rows bgcolor='$wk_color'>$ALTER_PROCEDURE<BR></td>\n";
			print "<td nowrap bgcolor='$wk_color'>$PURCHASE_LEADTIME<BR></td>\n";
			print "<td nowrap bgcolor='$wk_color'>$SUPPLIER_CODE<BR></td>\n";
			print "<td nowrap align=left colspan=2 bgcolor='$wk_color'>$COMPANY<BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'>$CURR_MARK $ESTIMATE_PRICE<BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'>$TRIAL_SAMPLE_PRICE<BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'>$ADJUSTMENT_PRICE<BR></td>\n";
		print "<th width=50 rowspan=$rows bgcolor='$wk_color'><input type='submit' name='chk_bt' value='Revision'></th>\n";
		print "</tr>\n";
		print "<tr align=center>\n";
			print "<td nowrap align=right colspan=2 bgcolor='$wk_color'>$MINIMUM_ORDER_LOT<BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'>$SPLIT_ORDER_LOT<BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'>$DELIVERY_LOT<BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'>$MANUFACTURING_COST<BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'>$MATERIAL_COST<BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'>\n";
			print "<input type='hidden' name='line_no' value='$LINE_NO'>\n";
			print "<input type='hidden' name='item_no' value='$in{'edpkey'}'>\n";
			print "<input type='hidden' name='KEYWORD' value='$KEYWORD'>\n";
			print "<input type='hidden' name='alter_procedure' value='$ALTER_PROCEDURE'>\n";
			print "<input type='hidden' name='buyer_code2' value='08'>\n";
			print "<input type='hidden' name='maker_flag2' value='1'>\n";
			print "<input type='hidden' name='eco_flag2' value=''>\n";
			print "<input type='hidden' name='suppliers_price2' value='0'>\n";
			print "<input type='hidden' name='base_price2' value='1.8'>\n";
		print $SEMIFINISH_COST . "<BR></td>\n";
		print "</tr>\n";

#FL�̂݁A�݌ɕϊ�����\��
		if ($CONF{'PO_STK_RATE'} eq "1") {
			print "<tr align=center>\n";
				print "<td nowrap align=right colspan=2 bgcolor='$wk_color'>$DELIVERY_PO_DATE<BR></td>\n";
				print "<td nowrap colspan=3   bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'>$UNIT_PURCHASE_RATE<BR></td>\n";
				print "<td nowrap bgcolor='$wk_color'>$PURCHASE_UNIT<BR></td>\n";
			print "</tr>\n";
		}else{
			print "<tr align=center>\n";
				print "<td nowrap align=right colspan=2 bgcolor='$wk_color'>$DELIVERY_PO_DATE<BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'>\n";
			print "</tr>\n";
		}
 
		print "</form>\n";
	}
}else{
		$wk_color="#ccccff";
		print "<tr align=center>\n";
			print "<td nowrap rowspan=$rows bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap align=left colspan=2 bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
		print "<th width=50 rowspan=$rows bgcolor='$wk_color'><BR></th>\n";
		print "</tr>\n";
		print "<tr align=center>\n";
			print "<td nowrap align=right colspan=2 bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
			print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
		print "</tr>\n";

#FL�̂݁A�݌ɕϊ�����\��
		if ($CONF{'PO_STK_RATE'} eq "1") {
			print "<tr align=center>\n";
				print "<td nowrap align=right colspan=2 bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
			print "</tr>\n";
		}else{
			print "<tr align=center>\n";
				print "<td nowrap align=right colspan=2 bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
				print "<td nowrap align=right bgcolor='$wk_color'><BR></td>\n";
			print "</tr>\n";
		}
}

print "</table>\n";
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
