#!/usr/local/bin/perl
#
# Require init-file
#
#  2002/02/27 Y.YAMANAKA
#     2003/12/04  在庫変換率を登録出来るように変更   By R.Tsutsui
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
#データの受け取り
#



print <<ENDOFTEXT ;

<Script Language Src=../../../lib/jslib.js></Script>
<script language='javascript'>
<!--//

var KO_window;

function CheckDelete( ){

        var string  = "Data will be cleared ?";

        answer = confirm (string);

        if ( answer ) {
                return true;
        } else {
                return false;
        }
}

	function CK_inputDT(){
	
		if(document.inputform.station_code.value  == ""){
			alert("Err : Station Code Empty");
			return false;
		}
		if(document.inputform.alter_procedure.value  == ""){
			alert("Err : Please input RANK");
			return false;
		}

	}

	//数値チェック
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
	//  MYIsNumeric : 数値チェックと桁数チェック
	// Fname フレーム名
	// sei_k 整数桁数
	// syo_k 少数桁数
	//********************************
	function MYIsNumeric(Fname,sei_k,syo_k){
		
		chack_num = document.inputform[Fname].value;

		if(MyIsNaN(chack_num)){
			alert("The " + chack_num + " is not number");
			document.inputform[Fname].value="";
			return false;
		}
		
		//整数、少数桁数がともに０のときはチェックしない
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
				alert("The Number of decimals is φ");
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
	//  Moji_lenChack : 文字数チェック
	// Fname フレーム名
	// str_len 文字数
	//********************************
	function Moji_lenChack(ThisForm,Fname,str_len){
		
		wk_len = getLength(ThisForm[Fname].value);

		if (wk_len > str_len){

			//alert("半角入力で" + str_len + "文字までの入力です");
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
			if ( jugStr.charAt(i) == "%" || jugStr.charAt(i) == "･" ){
				alert("Can't use sign or % or ･ ");
				return false;
			}
		}
		return true;
		
	}

	var kana_max = 51;
	var Hankakukana =  new Array("ｱ","ｲ","ｳ","ｴ","ｵ","ｶ","ｷ","ｸ","ｹ","ｺ",
								 "ｻ","ｼ","ｽ","ｾ","ｿ","ﾀ","ﾁ","ﾂ","ﾃ","ﾄ",
								 "ﾅ","ﾆ","ﾇ","ﾈ","ﾉ","ﾊ","ﾋ","ﾌ","ﾍ","ﾎ",
								 "ﾏ","ﾐ","ﾑ","ﾒ","ﾓ","ﾔ","ﾕ","ﾖ",
								 "ﾗ","ﾘ","ﾙ","ﾚ","ﾛ","ﾜ","ｦ","ﾝ",
								 "ﾟ","ﾞ","ｰ","｡","､");

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


	function SEARCH_ST(){

		if(document.inputform.station_code.value== ""){
			alert("Station code is Empty!!");
			return false;
		}

		if (!(KO_window == null || KO_window.closed)){
			KO_window.close();
		}
		
		Param = "Searchstation.cgi?ST_CODE=" + document.inputform.station_code.value ;
		
		KO_window =window.open(Param,"shinki_Window", "location=no,directories=no,status=no,menubar=no,toolbar=no,scrollbars=yes,resizable=yes,width=400,height=200");
		
//		Param  = "struct_inputform.cgi?KEYWORD=$KEYWORD&UP_ITEM_NO=$in{'UP_ITEM_NO'}";
//		Param += "&MOTHER_KEY=$in{'MOTHER_KEY'}";
//		Param += "&LEVEL_NO=$in{'LEVEL_NO'}";
//		Param += "&chk_bt=$in{'chk_bt'}";
//		Param += "&BASE_LW_ITEM_NO=$in{'BASE_LW_ITEM_NO'}";
//		Param += "&LW_ITEM_NO=" + document.INDT_FM.LW_ITEM_NO.value;
//		Param += "&chk_bt2=SEARCH";
//		Param += "&Parts=" + document.INDT_FM.REF_NUMBER.value;
//		Param += "&Unit=" + document.INDT_FM.QUANTITY.value;
//		//ヘッダーフォームの再作成
//		if( navigator.appName.charAt(0) == "N"){
//			//ﾈｽｹは通常のリンクで読み込まれる
//			location.href = Param ;
//		}else{
			//通常の読み込みではキャッシュから呼んでしまうため置き換える
//			location.replace(Param);
//		}
		
	}

 function new_item(theForm,nos){
      w3 = window.open('item2.pl?nos=' + nos,'itm','WIDTH=600,HEIGHT=300,SCROLLBARS=yes,RESIZABLE=yes') ;
 }
    function setCom(form_name){
      Param  = "?KEYWORD=$KEYWORD" ;
      Param += "&maker_flag=$maker_flag" ;
      Param += "&form_name=" + form_name ;
      c1 = window.open('com_set.pl' + Param, 'mk','WIDTH=250,HEIGHT=300,SCROLLBARS=yes,RESIZABLE=yes,left=50,top=50') ;
   }
   function setDate(form_name,format){
      Param  = "?KEYWORD=$KEYWORD" ;
      Param += "&form_name=" + form_name ;
      Param += "&format=" + format ;
      Param += "&default_val=" + document.inputform[form_name].value ;
      c1 = window.open('calender.pl' + Param, 'cal','WIDTH=250,HEIGHT=170,SCROLLBARS=no,RESIZABLE=no,left=50,top=50') ;
   }
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
$CURR_CODE			= $in{'currency'} ;
$UNIT_PURCHASE_RATE = $in{'unit_purchase_rate'} ;
$PURCHASE_UNIT      = $in{'purchase_rate'} ;
$DELIVERY_PO_DATE	= $in{'delivery_po_date'} ;



if($in{'chk_bt'} eq "Revision"){
	
	$sql  = "SELECT cm.COMPANY,i.ITEM,i.DESCRIPTION,";
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
	$sql .= "       itm.CURR_CODE,";
	$sql .= "       itm.UNIT_PURCHASE_RATE," ;
	$sql .= "       itm.PURCHASE_UNIT, " ;
	$sql .= "       TO_CHAR(itm.DELIVERY_PO_DATE,'DDMMYYYY') DELIVERY_PO_DATE ";
	$sql .= "  FROM ITEMMAKER itm,COMPANY cm,ITEM i ";
	$sql .= " WHERE itm.LINE_NO=$in{'line_no'} AND itm.ITEM_NO=$in{'item_no'}";
	$sql .= "   AND itm.SUPPLIER_CODE=cm.COMPANY_CODE(+)";
	$sql .= "   AND itm.ITEM_NO=i.ITEM_NO(+)";

#print $sql;
#exit ;
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	while(@datas = $cur->fetchrow){
	    for($i=0;$i<scalar(@datas);$i++){
	     	${$cur->{NAME}->[$i]} = $datas[$i] ;
	    }
	}
	#空白の削除
	$ESTIMATE_PRICE	     =~s/\s*//g;
	$MATERIAL_COST	     =~s/\s*//g;
	$SEMIFINISH_COST     =~s/\s*//g;
	$MANUFACTURING_COST  =~s/\s*//g;
	$ADJUSTMENT_PRICE    =~s/\s*//g;
	$TRIAL_SAMPLE_PRICE  =~s/\s*//g;

}elsif($in{'chk_bt'} eq "ADD"){

	$sql  = "SELECT ITEM,DESCRIPTION FROM ITEM WHERE ITEM_NO=" . $ITEM_NO ;

	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	while(@datas = $cur->fetchrow){
	    for($i=0;$i<scalar(@datas);$i++){
	     	${$cur->{NAME}->[$i]} = $datas[$i] ;
	    }
	}
}

#
# UNIT CODE
#
 $sql = "select unit_code,unit from unit order by unit " ;
 $cur = $dbh->prepare($sql) || &err("UNIT err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("UNIT err $DBI::err .... $DBI::errstr") ;
 while(@datas = $cur->fetchrow) {
     $rec = {} ;
     $i=0 ;
     $rec->{unit_code} = $datas[$i] ; $i++ ;
     $rec->{unit}      = $datas[$i] ; $i++ ;
     push @UNIT,$rec ;
 }
 $cur->finish() ;


print "<center>SUPPLIER'S PRICE MAINTENANCE</center>\n";

print "<hr width=90%>\n";

print "<center>\n";

print "<form action='maker_database.cgi' method='post' name='inputform'>\n";
print "<table border=0 cellspacing=1 cellpadding=0 width=680>\n";
print "<tr>\n";
	
	if($in{'chk_bt'} eq "ADD"){
		print "<th width=80 bgcolor='#ffffff'><BR></th>\n";
		print "<th width=100 bgcolor='#ffffff'><BR></th>\n";
		print "<th width=100 bgcolor='#ffffff'><BR></th>\n";
		print "<th width=100 bgcolor='#ffffff'><input type='submit' value='Insert' name=BTN_TYPE onClick='return CK_inputDT()'></th>\n";
		print "<th width=100 bgcolor='#ffffff'><BR></th>\n";
		print "<th width=100 bgcolor='#ffffff'><BR></th>\n";
		print "<th width=100 bgcolor='#ffffff'><input type='Button' value='Return' name=BTN_TYPE2 onclick='history.back()'></th>\n";
	}else{
		print "<th width=80 bgcolor='#ffffff'><BR></th>\n";
		print "<th width=100 bgcolor='#ffffff'><BR></th>\n";
		print "<th width=100 bgcolor='#ffffff'><BR></th>\n";
		print "<th width=100 bgcolor='#ffffff'><input type='submit' value='UpDate' name=BTN_TYPE onClick='return CK_inputDT()'></th>\n";
		print "<th width=100 bgcolor='#ffffff'><input type='submit' value='Delete' name=BTN_TYPE onclick='CheckDelete()'></th>\n";
		print "<th width=100 bgcolor='#ffffff'><BR></th>\n";
		print "<th width=100 bgcolor='#ffffff'><input type='Button' value='Return' name=BTN_TYPE2 onclick='history.back()' ></th>\n";
	}
		print "<input type='hidden' name='KEYWORD' value='$KEYWORD'>\n";
print "</tr>\n";
print "</table>\n";


print "<hr width=70%>\n";
print "<table border=0 cellspacing=1 cellpadding=0 width=600>\n";
print "<tr>\n";
print "<td align=center>PLEASE SELECT SUPPLIER'S INFOMATION</td>\n";
print "</tr>\n";
print "</table>\n";

print "<hr width=70%>\n";

print "<table border=0>\n";
print "<tr><td>\n";
print "<table border=0 cellspacing=1 cellpadding=0>\n";
print "<tr align=center>\n";
	print "<th width=80 bgcolor='#ccffcc'>ITEM NO.</th>\n";
	print "<th width=100 bgcolor='#ccccff'>$ITEM_NO\n";
	print "<input type=Hidden NAME=item_no value='$ITEM_NO'>\n";
	print "<input type=Hidden NAME=line_no value='$LINE_NO'>\n";
	print "</th>\n";
	print "<th width=90 bgcolor='#ccffcc'>NAME</th>\n";
	print "<td width=300 align=left bgcolor='#ccccff'>$ITEM<input type=hidden name=item value='$ITEM'></td>\n";
print "</tr>\n";
print "<tr>\n";
	print "<TD COLSPAN=2></TD>\n";
	print "<th width=90 bgcolor='#ccffcc'>DESCRIPTION</th>\n";
	print "<td width=300 align=left bgcolor='#ccccff'>$DESCRIPTION<input type=hidden name=description value='$DESCRIPTION'></td>\n";
print "</tr>\n";
print "</table>\n";

print "<br>\n";

print "<table border=0 cellspacing=1 cellpadding=0>\n";
print "<tr align=center>\n";
print "<th width=95 bgcolor='#ccffcc'>RANK</th>\n";
print "<td width=50 bgcolor='#ccccff'><input type='text' name='alter_procedure' value='$ALTER_PROCEDURE' size=5 maxlength=3 onChange='MYIsNumeric(\"alter_procedure\",3,0)'></td>\n";
print "</tr>\n";
print "</table>\n";

print "<table border=0 cellspacing=1 cellpadding=0>\n";
print "<tr align=center>\n";
	print "<th width=95 bgcolor='#ccffcc'>SUPPLIER</th>\n";
	print "<td bgcolor='#ccccff' NOWRAP>\n";
	print "<input type='text' name='station_code' value='$SUPPLIER_CODE' size=12 maxlength=10>\n";
#	print "<input type=button NAME=C_SEARCH value='Search' onClick='SEARCH_ST()'></td>\n";
	print "<A HREF=javascript:setCom(this.form)><IMG SRC=$IMG_DIR/set.gif BORDER=0></A></td>\n";
	print "<td width=300 align=left bgcolor='#ccccff'><input type=text name=company value='$COMPANY' onFocus='document.inputform.currency.focus()' size=40></td>\n";
	
	$sql = "select CURR_CODE C_CODE, CURR_MARK from CURRENCY order by CURR_CODE";
	
	print "<th width=95 bgcolor='#ccffcc'>Currency</th>\n";
	print "<td width=50 align=center bgcolor='#ccccff'><SELECT NAME='currency'>\n";
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	while(@datas = $cur->fetchrow){
	    for($i=0;$i<scalar(@datas);$i++){
	     	${$cur->{NAME}->[$i]} = $datas[$i] ;
	    }
     	if($CURR_CODE eq $C_CODE){ print "<option value='$C_CODE' selected>$CURR_MARK\n"; 	}
		else{					   print "<option value='$C_CODE'>$CURR_MARK\n";			}
	}
	print "</SELECT></td>\n";

print "</tr>\n";
print "</table>\n";
print "<table border=0 cellspacing=1 cellpadding=0>\n";
print "<tr align=center>\n";
	print "<th width=95 bgcolor='#ccffcc'>PURCHASE LEADTIME</th>\n";
	print "<td width=50 align=center bgcolor='#ccccff'>\n";
	print "<input type='text' name='purchase_leadtime' value='$PURCHASE_LEADTIME' size=5 maxlength=3 onChange='MYIsNumeric(\"purchase_leadtime\",3,0)'></td>\n";
	print "<th width=95 bgcolor='#ccffcc'>MINIMUN ORDER LOT</th>\n";
	print "<td width=100 align=center bgcolor='#ccccff'>\n";
	print "<input type='text' name='minimum_order_lot' value='$MINIMUM_ORDER_LOT' size=12 maxlength=9  onChange='MYIsNumeric(\"minimum_order_lot\",9,0)'></td>\n";
	print "<th width=95 bgcolor='#ccffcc'>SPLIT ORDER LOT</th>\n";
	print "<td width=95 align=center bgcolor='#ccccff'>\n";
	print "<input type='text' name='split_order_lot' value='$SPLIT_ORDER_LOT' size=12 maxlength=9 onChange='MYIsNumeric(\"split_order_lot\",9,0)'></td>\n";
	print "<th width=95 bgcolor='#ccffcc'>DELIVERY LOT</th>\n";
	print "<td width=90 align=center bgcolor='#ccccff'>\n";
	print "<input type='text' name='delivery_lot' value='$DELIVERY_LOT' size=12 maxlength=9 onChange='MYIsNumeric(\"delivery_lot\",9,0)'></td>\n";
print "</tr>\n";
print "</table>\n";

print "<table border=0 cellspacing=1 cellpadding=0>\n";
print "<tr>\n";
	print "<th width=95 bgcolor='#ccffcc'>ESTIMATE PRICE</th>\n";
	print "<td width=140 align=center bgcolor='#ccccff'>\n";
	print "<input type='text' name='estimate_price' value='$ESTIMATE_PRICE' size=20 maxlength=18 onChange='MYIsNumeric(\"estimate_price\",10,6)'></td>\n";
	print "<th width=95 bgcolor='#ccffcc'>TRIAL SAMPLE PRICE</th>\n";
	print "<td width=140 align=center bgcolor='#ccccff'>\n";
	print "<input type='text' name='trial_sample_price' value='$TRIAL_SAMPLE_PRICE' size=20 maxlength=18 onChange='MYIsNumeric(\"trial_sample_price\",10,6)'></td>\n";
	print "<th width=95 bgcolor='#ccffcc'>ADJUSTMENT PRICE</th>\n";
	print "<td width=140 align=center bgcolor='#ccccff'><input type='text' name='adjustment_price' value='$ADJUSTMENT_PRICE' size=20 maxlength=18 onChange='MYIsNumeric(\"adjustment_price\",10,6)'></td>\n";
print "</tr>\n";
print "<tr>\n";
	print "<th width=95 bgcolor='#ccffcc'>MANUFACTURING COST</th>\n";
	print "<td width=140 align=center bgcolor='#ccccff'>\n";
	print "<input type='text' name='manufacturing_cost' value='$MANUFACTURING_COST' size=20 maxlength=18 onChange='MYIsNumeric(\"manufacturing_cost\",10,6)'></td>\n";
	print "<th width=95 bgcolor='#ccffcc'>MATERIAL COST</th>\n";
	print "<td width=140 align=center bgcolor='#ccccff'>\n";
	print "<input type='text' name='material_cost' value='$MATERIAL_COST' size=20 maxlength=18 onChange='MYIsNumeric(\"material_cost\",10,6)'></td>\n";
	print "<th width=95 bgcolor='#ccffcc'>SEMIFINISH COST</th>\n";
	print "<td width=140 align=center bgcolor='#ccccff'>";
	print "<input type='text' name='semifinish_cost' value='$SEMIFINISH_COST' size=20 maxlength=18 onChange='MYIsNumeric(\"semifinish_cost\",10,6)'></td>\n";
print "</tr>\n";
print "<tr>\n";
	print "<th width=95 bgcolor='#ccffcc'>DELIVERY PO DATE</th>\n";
	print "<td width=140 align=center bgcolor='#ccccff'>\n";
	print "<input type='text' name='delivery_po_date' value='$DELIVERY_PO_DATE' size=10 maxlength=8 onBlur='dateChk(this.form,this.name)'>\n";
	print "  <A HREF=javascript:setDate('delivery_po_date','ddmmyyyy')><IMG SRC=$IMG_DIR/cal.gif BORDER=0></A>" ;
	print "  </td>\n";
print "</tr>\n";
print "</table>\n";

#FLのみ、PO_STK_RATE(在庫変換率)を入力  2003/12/4  By R.Tsutsui
if ($CONF{'PO_STK_RATE'} eq "1") {
	print "<table border=0 cellspacing=1 cellpadding=0>\n";
	print "<tr>\n";
		print "<th width=220 bgcolor='#ccffcc'>PURCHASE SHEET RATE</th>\n";
		print "<td width=70  align=center bgcolor='#ccccff'>\n";
		print "<input type='text' name='unit_purchase_rate' value='$UNIT_PURCHASE_RATE' size=5 maxlength=3 onChange=chkNum(this.form,this.value,'3')></td>\n";
		print "<th width=220 bgcolor='#ccffcc'>PURCHASE SHEET UNIT</th>\n";
		print "<td width=60  align=center bgcolor='#ccccff'>\n";
		print "<SELECT NAME=purchase_unit>\n" ;
			foreach $ref(@UNIT) {
				$selected = ($PURCHASE_UNIT == $ref->{unit_code} ? "selected" : "" ) ;
				print "<OPTION VALUE=$ref->{unit_code} $selected>$ref->{unit}";
			}
		print "</SELECT>\n" ;
	print "</tr>\n";
	print "</table>\n";
}

print "</td></tr>\n";
print "</table>\n";
print "</form>\n";
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

