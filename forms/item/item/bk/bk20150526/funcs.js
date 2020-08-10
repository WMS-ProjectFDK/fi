//-----------------------------------------------------------------------------ajax start
function	getHttpObject(){
	var	xmlhttp;
	if(window.ActiveXObject){
		try{
			xmlhttp	=	new	ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				xmlhttp	=	new	ActiveXObject("Microsoft.XMLHTTP");
			}catch(E){
				xmlhttp	=	false;
			}
		}
	}else	if(window.XMLHttpRequest){
		try{
			xmlhttp	=	new	XMLHttpRequest();
		}catch(e){
			xmlhttp	=	false;
		}
	}
	return	xmlhttp;
}
function	addListener(obj,	eventType,	func,	cap){
	//ex)
	//var eSource = document.getElementById("message");
	//addListner(eSource,"keydown",cancelSubmit,false);
	//var eSource = document.getElementById("div-b");
	//addListner(eSource,"click",spClick,false);
	//var eSource = document.getElementById("nyuukinkubun_sel");
	//addListner(eSource,"change",fieldBlur,false);
	//var eSource = document.getElementById("tokuisakiname");
	//addListner(eSource,"blur",fieldBlur,false);

	if(obj.attachEvent){
		//IE
		obj.attachEvent('on'	+	eventType,	func);
	}else	if(obj.addEventListener){
		//not IE
		obj.addEventListener(eventType,	func,	cap);
	}else{
		//others
		alert('No Support on your Browser!');
		return	false;
	}
}
function	delListener(obj,	eventType,	func,	cap){
	// リスナーの削除
	if(obj.removeEventListener){
	    obj.removeEventListener(eventType, func, cap);
	}else if(obj.detachEvent){
	    obj.detachEvent('on'	+	eventType,	func);
	}else{
		//others
		alert('No Support on your Browser!');
		return	false;
	}
}

//ajax通信を行った戻りテキスト(httpObj.responseText)にページ表示エラー文字列があるか確認します。
function systemErrCheck(t){
	//t:httpObj.responseTextがセットされることが前提です。
	var wt = "" + t;
	if(wt.indexOf("<title>ページを表示できません</title>") != -1){
		return false;
	}
	return true;
}

//大杉社員作成のmoveElements.jsからコピー&加工start
var beforValue = ""; //フィールドのフォーカスがあたった時点の値保持用
var nowField = ""; //フォーカスフィールド保持用
var beforField = ""; //戻りフォーカスフィールド保持用
var afterField = ""; //送りフォーカスフィールド保持用
function setAllListner(){
	//form内のオブジェクト全てにfocus,blur,keydownを設定します。
	for (var i = 0; i < document.forms.length; i++) {
		for (var f = 0; f < document.forms[i].length; f++) {
			var elm = document.forms[i][f];
			var col = elm.style.backgroundColor;
			setColor(elm, 'focus');
			setColor(elm, 'blur',col);
			addListner(elm,"keydown",fieldBlur,false);
			addListner(elm,"click",fieldReturn,false);
		}
	}
}

function blockEnter(evt){
	evt = (evt) ? evt : event; 
	var charCode=(evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
	sElm = getId(evt);
	if(sElm == ""){
		return;
	}
//alert("charCode:" + charCode);
	//if ( Number(charCode) == 9 || (document.getElementById(sElm).type != 'textarea' && Number(charCode) == 13)) {
	if ( Number(charCode) == 9 ) {
		//タブで移動させない!
		return false;
	} else {
		return true;
	}
}


function setColor(elm, type,col){
		var colors = { 'blur': '', 'focus': '#FFE4E1' };
		var color = colors[type];
		var evt = function(e) { 
			if(elm.readOnly==true) return;
			if(elm.disabled==true) return;
			if(elm.type != "button"){
				//ボタンエレメント押下で元のフィールドにフォーカスを戻すため
				//保持しない
				nowField = elm.id;
				beforValue = elm.value;
			}

			if(col){
				elm.style.backgroundColor = col; 
				
			}else{
				elm.style.backgroundColor = color; 
			}
			if(type == "blur"){
				elm.readOnly = "readOnly";
			}else{
				zanAuto(e);
			}
		};
		addListner(elm,type,evt,false);
	}

function delColor(elm, type,col){
		var colors = { 'blur': '', 'focus': '#FFE4E1' };
		var color = colors[type];
		var evt = function(e) { 
			if(elm.readOnly==true) return;
			if(elm.disabled==true) return;

			if(col){
				elm.style.backgroundColor = col; 
			}else{
				elm.style.backgroundColor = color; 
			}
		};
		delListner(elm,type,evt,false);
	}

function fieldReturn(e){
	//マウスクリックに対するアクションです。
	sElm = getId(e);
	if(sElm == ""){
		return false;
	}
	if(document.getElementById(sElm).disabled){
		return;
	}
	if(document.getElementById(sElm).type == "button"){
		return;
	}
	if(document.getElementById(sElm).readOnly){
		inputEnable(nowField);
		document.getElementById(nowField).focus();
		document.getElementById(nowField).select();
		return;
	}
}

function delAllListner(){
	//form内のオブジェクト全てからfocus,blur,keydownを削除します。
	for (var i = 0; i < document.forms.length; i++) {
		for (var f = 0; f < document.forms[i].length; f++) {
			var elm = document.forms[i][f];
			var col = elm.style.backgroundColor;
			delColor(elm, 'focus');
			delColor(elm, 'blur',col);
			delListner(elm,"keydown",fieldBlur,false);
			delListner(elm,"click",fieldReturn,false);
		};
	}
}
//大杉社員作成のmoveElements.jsからコピー&加工end

//-----------------------------------------------------------------------------ajax end

//-----------------------------------------------------------------------------input check start
//半角英数のみチェック
function singleAsciiCheck(p_str){
	//".":46 "-":45 "_":95
	str = p_str
	err = 0;
	for (i=0;i<str.length;i++){
		icode = str.charCodeAt(i);
		if ((48<=icode && icode <=57) || (65<= icode && icode <=90) || (97 <= icode && icode <= 122) || 
			(46 == icode) || (45 == icode) || (95 == icode)){
			//真だったら何もしない
		}else{
			err++;
		}
	}
	//if (err!=0) alert('入力は半角英数字でお願いします。');
	if (err!=0){
		//alert('入力は半角でお願いします。');
		return false
	}
	return true;
	//else alert('エラーはありません');
}
//半角数値のみチェック
function singleNumCheck(p_str){
	//-:45許可
	var iCount;
	var iCode;
	st_val=p_str;
	for (iCount=0 ; iCount<st_val.length ; iCount++){
		iCode = st_val.charCodeAt(iCount);
		if (45 != iCode && (48 > iCode || iCode > 57)){
			//alert("数値以外が含まれています。");
			//return;
			return false;
		}
	}
	//alert("全て数値です。");
	//return;
	return true;
}
//全角文字のみチェック
function doubleStrCheck(p_str){
	var iCount;
	var sTemp;
	var st_val=p_str;

	//半角カナの存在チェック
	var iCode;

	for (iCount=0 ; iCount<st_val.length ; iCount++){
		iCode = st_val.charCodeAt(iCount);
		if ((65382<= iCode && iCode <= 65439)){
			//alert("半角カナが含まれています。");
			//return;
			return false;
		}
	}
	//alert("半角カナは含まれていません。");
	//return;

	for (iCount=0;iCount < st_val.length;iCount++){
		sTemp = escape(st_val.charAt(iCount));
		if (sTemp.length <  4){ 
		//alert("全角以外の文字が含まれています。");
		//return;
		return false;
		}
	}
	//alert("全て全角文字です。");
	//return;
	return true;
}

//半角カナのみチェック
function singleStrCheck(p_str){
	var iCount;
	var sTemp;
	var st_val=p_str;

	//半角カナの存在チェック
	var iCode;

	for (iCount=0 ; iCount<st_val.length ; iCount++){
		iCode = st_val.charCodeAt(iCount);
		if ((65382 > iCode || iCode > 65439)){
			return false;
		}
	}
	return true;
}

//全角禁止チェック
function doubleStrCheck2(p_str){
	var err = 0;
	for (i=0; i<p_str.length; i++){
		if(65382<= p_str.charCodeAt(i) && p_str.charCodeAt(i) <= 65439){
			//半角ｶﾅのコード
			continue;
		}else{
			n = escape(p_str.charAt(i));
			if (n.length >= 4){ err++;}
		}
	}
	if (err!=0){
		//alert('入力は半角でお願いします。');
		return false
	}
	return true;
}
function doubleStrCheck3(p_str){
	//半角ｶﾅも禁止
	var err = 0;
	for (i=0; i<p_str.length; i++){
		if(65382<= p_str.charCodeAt(i) && p_str.charCodeAt(i) <= 65439){
			//半角ｶﾅのコード
			err++;
		}else{
			n = escape(p_str.charAt(i));
			if (n.length >= 4){ err++;}
		}
	}
	if (err!=0){
		//alert('入力は半角でお願いします。');
		return false
	}
	return true;
}

function mailAddressCheck(p_str){
	if(!p_str.match( /^[A-Za-z0-9]+[\w-]+@[\w\.-]+\.\w{2,}$/ )){
		return false;
	}
	return true;
}

//日付妥当性チェック
function dateCheck(str){
	//yyyymmdd
	var years = str.substring(0,4);
	var months = str.substring(4,6);
	var days = str.substring(6,8);
	if(months.substring(0,1) == "0"){
		months = months.substring(1,2);
	}
	if(days.substring(0,1) == "0"){
		days = days.substring(1,2);
	}
	var flag = true;
	years = parseInt(years,10);
	months = parseInt(months,10) -1;
	days = parseInt(days,10);
	var dates = new Date(years,months,days);
	if (years != dates.getFullYear()){
		flag = false;
	}
	if (months != dates.getMonth()){
		flag = false;
	}
	if (days != dates.getDate()){
		flag = false;
	}
	if (flag) {
		return true;
	} else {
		return false;;
	}
}

function date30Check(p){
	//YYYYMMDDに対してシステム日付の31日以上前か31日以上後の場合コンファームウィンドウで
	//注意を促します。
	//システム日付取得
	var today = new Date();
	var nmsec = 30 * 1000 * 60 * 60 * 24; //30日のミリ秒
	//30日前取得
	var befor30 = new Date(today.getTime() - nmsec);
//alert("befor30:" + befor30.getFullYear() + "/" + (befor30.getMonth()+1) + "/" + befor30.getDate());
	//30日後取得
	var after30 = new Date(today.getTime() + nmsec);
//alert("after30:" + after30.getFullYear() + "/" + (after30.getMonth()+1) + "/" + after30.getDate());
	//パラメータで受けた日付をdateオブジェクトにして比較
	y = p.substring(0,4);
	if(p.substring(4,5) == "0"){
		m = p.substring(5,6);
	}else{
		m = p.substring(4,6);
	}
	if(p.substring(6,7) == "0"){
		d = p.substring(7,8);
	}else{
		d = p.substring(6,8);
	}
	msec  = (new Date(""+y+"/"+m+"/"+d)).getTime();
	if(msec < befor30.getTime() || after30.getTime() < msec){
		//alert("30over");
		return false;
	}
	return true;
}

function dateBetweenCheck(dfdate,systemdate){
	//dfdateの日付yyyymmddが、1週間前から2週間後の間に含まれているかチェックします。
	var beforDate = dateBA(systemdate,"M","7");
	var afterDate = dateBA(systemdate,"P","14");
	var ddate = new Date(dfdate.substring(0,4),dfdate.substring(4,6),dfdate.substring(6,8));//8桁日付をDateオブジェクト化
	var dbdate = new Date(beforDate.substring(0,4),beforDate.substring(4,6),beforDate.substring(6,8));//8桁前日付をDateオブジェクト化
	var dadate = new Date(afterDate.substring(0,4),afterDate.substring(4,6),afterDate.substring(6,8));//8桁後日付をDateオブジェクト化
//alert("ddate:"+ddate.getTime());
//alert("bdate:"+dbdate.getTime());
//alert("adate:"+dadate.getTime());
	if(ddate.getTime() < dbdate.getTime() || ddate.getTime() > dadate.getTime()){
		return false;
	}
	return true;
}

function checkPastDate(yyyymmdd1,yyyymmdd2){
	//yyyymmdd形式で日付を受け取り1<2の場合エラーにします。
	//yyyymmdd1のdateオブジェクトを作成します。
	var yyyy1 = yyyymmdd1.substring(0,4);
	var mm1 = yyyymmdd1.substring(4,6);
	var dd1 = yyyymmdd1.substring(6,8);
	if(mm1.substring(0,1) == "0"){
		mm1 = mm1.substring(1,2);
	}
	if(dd1.substring(0,1) == "0"){
		dd1 = dd1.substring(1,2);
	}
	yyyy1 = parseInt(yyyy1,10);
	mm1 = parseInt(mm1,10) -1;
	dd1 = parseInt(dd1,10);
	var date1 = new Date(yyyy1,mm1,dd1);
	//yyyymmdd2のdateオブジェクトを作成します。
	var yyyy2 = yyyymmdd2.substring(0,4);
	var mm2 = yyyymmdd2.substring(4,6);
	var dd2 = yyyymmdd2.substring(6,8);
	if(mm2.substring(0,1) == "0"){
		mm2 = mm2.substring(1,2);
	}
	if(dd2.substring(0,1) == "0"){
		dd2 = dd2.substring(1,2);
	}
	yyyy2 = parseInt(yyyy2,10);
	mm2 = parseInt(mm2,10) -1;
	dd2 = parseInt(dd2,10);
	var date2 = new Date(yyyy2,mm2,dd2);
	//date1とdate2を比較してdate1<date2はエラー
	if(date1.getTime() < date2.getTime()){
		return false;
	}
	return true;
}

//小数の数値、桁チェック
function checkDecimal(str,int1,int2){
	//小数、桁のチェックを行います。戻り値）0:正常。1:桁エラー、2:数値エラー
	//str）数値文字列、int1）整数部桁数、int2）小数部桁数

	//'.'で分割します。
	var tmp = str.split(".");
	if(tmp.length == 1){
		//tmpの配列数が1なら小数ではありません。
		var seisuu = tmp[0];
		//整数部の桁チェック
		if(seisuu.length > int1){
			return 1;
		}
		//整数部の数値チェック
		if(singleNumCheck(seisuu) == false){
			return 2;
		}
	}else if(tmp.length >= 3){
		//tmpの配列数が3以上ならエラーです。
		return 2;
	}else{
		//tmpの配列数が2なら整数部、小数部の半角数値、桁チェックを行います。
		var seisuu = tmp[0];
		//整数部の桁チェック
		if(seisuu.length > int1){
			return 1;
		}
		//整数部の数値チェック
		if(singleNumCheck(seisuu) == false){
			return 2;
		}
		var syousuu = tmp[1];
		//小数部の桁チェック
		if(syousuu.length > int2){
			return 1;
		}
		//小数部の数値チェック
		if(singleNumCheck(syousuu) == false){
			return 2;
		}
	}
	return 0;
}

//文字列の全半角混在をチェックします。
function checkStrMix(str){
	//半角許可文字だけか全角文字だけかチェックして
	//混在していたらエラーとします。
	whankaku = doubleStrCheck2(str); //trueは半角許可文字のみ
	wzenkaku = doubleStrCheck(str); //trueは全角文字のみ
	if(!whankaku && !wzenkaku){
		//それぞれ許可以外の文字があったので混在しているとみなします。
		return false;
	}
	return true;
}


//-----------------------------------------------------------------------------input check end

//-----------------------------------------------------------------------------others start
//innerHTMLの値を設定します。
function setInnerHTC(sElm,val,type){
	//type:"H"innerHTML,"":others
	if(type=="H"){
		document.getElementById(sElm).innerHTML = val;
	}else{
		if (typeof document.getElementById(sElm).textContent != "undefined") {
			//firefox
			document.getElementById(sElm).textContent = val;
		}else{
			//ie
			document.getElementById(sElm).innerText = val;
		}
	}
}
//innerHTMLの値を取得します。
function getInnerTC(sElm){
	if (typeof document.getElementById(sElm).textContent != "undefined") {
		//firefox
		return document.getElementById(sElm).textContent;
	}else{
		//ie
		return document.getElementById(sElm).innerText;
	}
}

//前後の半角スペース、全角スペース、タブを削除します。
function Trim(str) {
	return LTrim(RTrim(str));
}

//前の半角スペース、全角スペース、タブを削除します。
function LTrim(str) {
	return str.replace(/^[ 　\t]+/, "");
//	return str.toString().replace(/^[\s　]+/, "");
}

//後ろの半角スペース、全角スペース、タブを削除します。
function RTrim(str) {
	return str.replace(/[ 　\t]+$/g, "");
//	return str.toString().replace(/[\s　]+$/g, "");
}

//改行コードのトリム
//http://sohgetsu.blogspot.com/2007/08/javascript.html
function remove_newline(text){
	text = text.replace(/\r\n/g, "");//IE
	text = text.replace(/\n/g, "");//Firefox
	return text;
} 

//ブラウザ判別 0はie 1はie6以降以外
function	checkBrowser(){
	var	browser;
	str  = navigator.appName.toUpperCase();
	str2 = navigator.userAgent.toUpperCase();
	if (str2.indexOf("ICAB") >= 0)     browser="1";
	if (str.indexOf("NETSCAPE") >= 0)  browser="1";
	if (str.indexOf("MICROSOFT") >= 0){
		//バージョンを取得します。
		appVer  = navigator.appVersion;//netscape?
		appVer  = navigator.userAgent;
		s = appVer.indexOf("MSIE ",0) + 5;
		e = appVer.indexOf(";",s);
		version = eval(appVer.substring(s,e));
		//alert(version);
		//ie5.5以前は対象外です。
		if(version >= 6){
			browser = "0";
		}else{
			browser = "1";
		}
	}
//	if(window.ActiveXObject){
//		//バージョンを取得します。
//		appVer  = navigator.appVersion;
//		alert(appVer);
//		appVer  = navigator.userAgent;
//		alert(appVer);
//		s = appVer.indexOf("MSIE ",0) + 5;
//		alert(s);
//		e = appVer.indexOf(";",s);
//		alert(e);
//		version = eval(appVer.substring(s,e));
//		alert(version);
//		//alert(version);
//		//ie5.5以前は対象外です。
//		if(version >= 6){
//			browser = "0";
//		}else{
//			browser = "1";
//		}
//	}else	if(window.WMLHttpRequest){
//		browser = "1";
//	}
	return	browser;
}

//ブラウザ判別結果からクラス指定の文字列を分ける
function	setClassStr(pBrowser){
	var browser = "";
	if(pBrowser == "0"){
		//ie
		browser = "className";
	}else{
		browser = "class";
	}
	return browser;
}

//リスナ登録されたファンクションからイベントをもらいidを返す
function getId(e){
	var sElm = "";
	if(e.srcElement){
		sElm = e.srcElement.id;
	}else if(e.target){
		sElm = e.target.id;
	}else{
		alert("No Source Element");
		return false;
	}
//alert(sElm);
	return sElm;
}

//テーブルから行を削除する
function delRow(rowid){
	var sTarget = "row-" + rowid;
	var oTarget = document.getElementById(sTarget);
	var parent = oTarget.parentNode;
	parent.removeChild(oTarget);
}


//インプットフィールドを入力可にする。
function inputEnable(id){
	document.getElementById(id).readOnly = "";
	document.getElementById(id).style.backgroundColor = "#FFFFFF";
}

//インプットフィールドを入力不可にする。
function inputDisable(id){
	document.getElementById(id).readOnly = "readOnly";
	document.getElementById(id).style.backgroundColor = "#DCDCDC";
}


// Tid スクロール機構を付加するテーブルのID
//
// tHeight テーブルの高さの指定
//
//http://d.hatena.ne.jp/Mars/20050115#p1のサンプルからリンクしている、
//http://c-man.s21.xrea.com/mars/md20050115.htmlのソースを加工
function Tscroller(Tid,tHeight) {
	if(! document.createElement) return;
		if(navigator.userAgent.match('Opera')) return;

	var oTBL = document.getElementById(Tid);

	var children = oTBL.childNodes;//kami start
//alert(children.item(1).nodeName);
//alert(children.item(1).hasChildNodes());
/*
var children1 = children.item(1).childNodes;
alert(children1.length);
for(i=0;i<children1.length;i++){
alert("1:" + children1.item(i).nodeName);
alert("1:" + children1.item(i).hasChildNodes());
	var children2 = children1.item(i).childNodes;
alert("children2:" + children2.length);
	for(j=0;j<children2.length;j++){
alert("2:" + children2.item(j).nodeName);
alert("2:" + children2.item(j).hasChildNodes());
		var children3 = children2.item(j).childNodes;
		for(k=0;k<children3.length;k++){
alert("3:" + children3.item(k).nodeName);
alert("3:" + children3.item(k).hasChildNodes());
		}
	}
}
*/
//	if(!children.item(1).hasChildNodes()){
//		alert("tr nasi");
//		return;
//	}
	//kami end
	// 元のセル幅をスタイルとして設定する。
	/* 今回は%指定済なので設定しない
	for(var i=0;i<oTBL.tHead.rows[0].cells.length;i++) {
		oTBL.tHead.rows[0].cells[i].style.width = 
		oTBL.tBodies[0].rows[0].cells[i].style.width = 
			(oTBL.tHead.rows[0].cells[i].clientWidth - oTBL.cellPadding * 2)+ 'px';
	}
	*/
	// ヘッダ部の高さを退避
	var ThHeight = oTBL.tHead.offsetHeight;
	oTBL.style.width = oTBL.offsetWidth + 'px';
	var tWidth = oTBL.offsetWidth+1;// +1は算出誤差用-もっと大きくとらないとダメな場合もあるかも


	// テーブルの複製を作成　idを元のid+'_H'とし、tbodyの中身を削除
	var oTBL1 = oTBL.cloneNode(true);
	oTBL1.id += '_H';
	while(oTBL1.tBodies[0].rows.length) {
		oTBL1.tBodies[0].deleteRow(0);
	}

	// 新規DIV - ヘッダ部用 を作成
	var newDiv1 = document.createElement('div');
	newDiv1.id='D_'+oTBL1.id;
	//newDiv1.style.width = tWidth+'px';
	//tbody スクロールバー分theadをにもwidthに18追加
	newDiv1.style.width = (tWidth+18)+'px';
	newDiv1.style.height = ThHeight+'px';
	newDiv1.style.overflow = 'hidden';
	newDiv1.style.position = 'relative';
	oTBL1.style.position = 'absolute';
	oTBL1.style.left = '0';
	oTBL1.style.top = '0';

	newDiv1.appendChild(oTBL1);
	oTBL.parentNode.insertBefore(newDiv1,oTBL);
	// テーブルの複製を作成　ヘッダ部を削除
	var oTBL2 = oTBL.cloneNode(true);
	oTBL2.id += '_B';
	oTBL2.deleteTHead();
	// 新規DIV - ボディ部用 を作成
	var newDiv2 = document.createElement('div');
	newDiv2.id='D_'+oTBL2.id;
	newDiv2.style.width = (tWidth+18)+'px';
//	newDiv2.style.height = tHeight+'px';
	newDiv2.style.height = tHeight+'%';
	//newDiv2.style.overflow = 'auto'; //あふれたらスクロールバー表示
	//縦だけスクロールバー表示
	newDiv2.style.overflowX = 'hidden'; //横スクロールバーなし
	newDiv2.style.overflowY = 'scroll'; //縦スクロールバー常時表示

	oTBL2.style.left = '0';
	oTBL2.style.top = '0';
	oTBL2.style.position = 'absolute'; //appendChildを行うと一定数以上でヘッダと離れていくのを防止

	newDiv2.appendChild(oTBL2);
	oTBL.parentNode.insertBefore(newDiv2,oTBL);

	//oTBLのidを退避kami
	var oTBL_id = oTBL.id;
	// 元テーブルを削除
	oTBL.parentNode.removeChild(oTBL);

	//oTBL2のidを元に戻すkami
	oTBL2.id = oTBL_id;

}

//http://www.openspc2.org/reibun/javascript/business/009/index.htmlを加工
//カンマ編集
function addComma(val){
	var val2 = "" + val;
	var s_val = val2.split(".");
	//整数部だけをカンマ編集
	str = "" + s_val[0];
	cnt = 0;
	n   = "";
	if(val < 0){
		str = str.substring(1,str.length);
	}
	for (i=str.length-1; i>=0; i--)
	{
		n = str.charAt(i) + n;
		cnt++;
		if (((cnt % 3) == 0) && (i != 0)) n = ","+n;
	}
	if(val < 0){
		n = "-" + n;
	}
	if(s_val.length == 2){
		n = n + "." + s_val[1];
	}
	return n;
}

//カンマ削除
function delComma(val){
	var val2 = "" + val;
	var s_val = val2.split(".");
	//整数部だけをカンマ削除編集
	str = "" + s_val[0];
	var n = str.replace(/,/g,"");
	//if(val < 0){
	//	n = "-" + n;
	//}
	if(s_val.length == 2){
		n = n + "." + s_val[1];
	}
	return n;
}

//http://www.geocities.co.jp/SiliconValley/4334/unibon/javascript/trimfixed.html
//演算誤差を少なくする関数
function trimFixed(a) {
	//ex)
	//hikiate = trimFixed(hikiate + parseInt(document.getElementById(sTarget2).value));

    var x = "" + a;
    var m = 0;
    var e = x.length;
    for (var i = 0; i < x.length; i++) {
        var c = x.substring(i, i + 1);
        if (c >= "0" && c <= "9") {
            if (m == 0 && c == "0") {
            } else {
                m++;
            }
        } else if (c == " " || c == "+" || c == "-" || c == ".") {
        } else if (c == "E" || c == "e") {
            e = i;
            break;
        } else {
            return a;
        }
    }

    var b = 1.0 / 3.0;
    var y = "" + b;
    var q = y.indexOf(".");
    var n;
    if (q >= 0) {
        n = y.length - (q + 1);
    } else {
        return a;
    }

    if (m < n) {
        return a;
    }

    var p = x.indexOf(".");
    if (p == -1) {
        return a;
    }
    var w = " ";
    for (var i = e - (m - n) - 1; i >= p + 1; i--) {
        var c = x.substring(i, i + 1);
        if (i == e - (m - n) - 1) {
            continue;
        }
        if (i == e - (m - n) - 2) {
            if (c == "0" || c == "9") {
                w = c;
                continue;
            } else {
                return a;
            }
        }
        if (c != w) {
            if (w == "0") {
                var z = (x.substring(0, i + 1) + x.substring(e, x.length)) - 0;
                return z;
            } else if (w == "9") {
                var z = (x.substring(0, i) + ("" + ((c - 0) + 1)) + x.substring(e, x.length)) - 0;
                return z;
            } else {
                return a;
            }
        }
    }
    if (w == "0") {
        var z = (x.substring(0, p) + x.substring(e, x.length)) - 0;
        return z;
    } else if (w == "9") {
        var z = x.substring(0, p) - 0;
        var f;
        if (a > 0) {
            f = 1;
        } else if (a < 0) {
            f = -1;
        } else {
            return a;
        }
        var r = (("" + (z + f)) + x.substring(e, x.length)) - 0;
        return r;
    } else {
        return a;
    }
}

//チップヘルプ
timerID = 0;
sec = 2.5 * 1000; //表示時間2.5sec

//チップヘルプを表示する関数
function sChipHelp(divID,x,y){
	var offX = offY = 0; //初期値
	if(document.layers){
		chipOBJ = document.layers[divID]; //NN4対応
	}
	if(document.all){
		chipOBJ = document.all[divID].style; //IE4対応
	}
	if(document.getElementById){
		chipOBJ = document.getElementById(divID).style; //DOM対応
	}
	if(document.all){
		//IE5以降のスクロール値取得
		offX = document.body.scrollLeft;
		offY = document.body.scrollTop;
	}
	chipOBJ.visibility = "visible"; //チップヘルプ表示
	chipOBJ.left = x + offX; //ポップアップ位置
	//chipOBJ.top = y + offY + 16; //y方向にカーソル分(16px)だけずらす (^^)
	chipOBJ.top = y + offY - 16; //y方向にカーソル分(-16px)だけずらす (^^)
	timerID = setTimeout("hChipHelp('"+divID+"')",sec); //一定時間表示したら消す 
 } 

//チップヘルプを非表示にする関数
function hChipHelp(divID){ //チップヘルプを非表示にする関数
	if (document.layers) chipOBJ = document.layers[divID];
	if (document.all) chipOBJ = document.all[divID].style;
	if (document.getElementById) chipOBJ = document.getElementById(divID).style;
	chipOBJ.visibility = "hidden"; //チップヘルプ非表示
	clearTimeout(timerID); //タイマーを解除
}

//messageエリアでのエンターキーによるsubmit抑止
function cancelSubmit(e){
	//form内にテキストボックスが１つの場合、エンターキーでsubmitが発生するため抑制する。
	return (event.keyCode == 13) ? false : true;
}

//日付項目が６桁の場合、年に2000を足して返します。
function add2000(dt){
	rStr = "";
	if(dt.length == 6){
		var yyyy = parseInt("2000",10) + parseInt(dt.substring(0,2),10);
		rStr = yyyy + dt.substring(2,6);
	}else{
		rStr = dt;
	}
	return rStr;
}

//年月項目が４桁の場合、年に2000を足して返します。
function add2000_2(dt){
	rStr = "";
	if(dt.length == 4){
		var yyyy = parseInt("2000",10) + parseInt(dt.substring(0,2),10);
		rStr = yyyy + dt.substring(2,4);
	}else{
		rStr = dt;
	}
	return rStr;
}

//左ゼロ詰めします。
function addLZero(val,vallen){
	//val:画面の値
	//vallen:ゼロ詰め後の何桁
	var rStr = "";
	var zero = "";
	//valの前後の全角空白、半角空白を削除します。
	rStr = Trim(val);
	if(rStr.length < parseInt(vallen,10)){
		for(i=0;i<parseInt(vallen,10) - rStr.length;i++){
			zero = zero + "0";
		}
		rStr = zero + rStr;
	}
	return rStr;
}

//右ゼロ詰めします。
function addRZero(val,vallen){
	//val:画面の値
	//vallen:ゼロ詰め後の何桁
	var rStr = "";
	var zero = "";
	//valの前後の全角空白、半角空白を削除します。
	rStr = Trim(val);
	if(rStr.length < parseInt(vallen,10)){
		for(i=0;i<parseInt(vallen,10) - rStr.length;i++){
			zero = zero + "0";
		}
		rStr = rStr + zero;
	}
	return rStr;
}

//パーセント計算
function percentCalc(var1,var2){
	var percentStr = "";
	if(parseFloat(var2) == 0){
		alert("ゼロ除算エラーです。");
		return percentStr;
	}
	//Math.round(n)は少数点以下第一位を丸めて整数にするので
	//1000倍して丸めたあとに10で割る
	percentStr = Math.round(parseFloat(var1) / parseFloat(var2) * 1000) / 10;
	return percentStr;
}

//小数点以下表示、非表示
function setDecimal(fieldname,flag,para){
	//paraにtankaかrateが渡された場合、flag"S"setは小数点以下表示設定、"D"delは小数点以下表示削除を行います。
	var sVal = document.getElementById(fieldname).value;
	var sVal2 = sVal.split(".");
	var sDtanka = "00";
	var sDrate = "0";
	if(sVal2[0] == ""){
		sVal2[0] = "0";
	}
	if(para == "tanka"){
		if(flag == "S"){
			if(sVal2.length == 2){
				//小数点以下がある場合
				sDtanka = sVal2[1] + "00";
			}
			document.getElementById(fieldname).value = sVal2[0] + "." + sDtanka.substring(0,2);
		}else{
			if(sVal2.length == 1 || parseInt(sVal2[1],10) == 0){
				//小数点以下がない場合
				document.getElementById(fieldname).value = sVal2[0];
			}else{
				if(sVal2[1].substring(0,1) == "0"){
					//小数点以下第一位のみがゼロの場合
					document.getElementById(fieldname).value = sVal2[0] + "." + (sVal2[1] + "00").substring(0,2);
				}else if(sVal2[1].substring(1,2) == "0"){
					//小数点以下第二位のみがゼロの場合
					document.getElementById(fieldname).value = sVal2[0] + "." + sVal2[1].substring(0,1);
				}else{
					//小数点以下二桁ともゼロでない場合
					//何もしません。
				}
			}
		}
	}else if(para == "rate"){
		if(flag == "S"){
			if(sVal2.length == 2){
				//小数点以下がある場合
				sDrate = sVal2[1] + "0";
			}
			document.getElementById(fieldname).value = sVal2[0] + "." + sDrate.substring(0,1);
		}else{
			if(sVal2.length == 1 || parseInt(sVal2[1],10) == 0){
				//小数点以下がない場合
				document.getElementById(fieldname).value = sVal2[0];
			}else{
				//率は小数点以下1桁許可なので何もしません。
			}
		}
	}
}

function setDecimalShow(fieldname,flag,para){
	//paraにtankaかrateが渡された場合、flag"S"setは小数点以下表示設定、"D"delは小数点以下表示削除を行います。
	//innerText対象
	var sVal = getInnerTC(fieldname);
	var sVal2 = sVal.split(".");
	var sDtanka = "00";
	var sDrate = "0";
	if(sVal2[0] == ""){
		sVal2[0] = "0";
	}
	if(para == "tanka"){
		if(flag == "S"){
			if(sVal2.length == 2){
				//小数点以下がある場合
				sDtanka = sVal2[1] + "00";
			}
			setInnerHTC(fieldname,sVal2[0] + "." + sDtanka.substring(0,2),"");
		}else{
			if(sVal2.length == 1 || parseInt(sVal2[1],10) == 0){
				//小数点以下がない場合
				setInnerHTC(fieldname,sVal2[0],"");
			}else{
				if(sVal2[1].substring(0,1) == "0"){
					//小数点以下第一位のみがゼロの場合
					setInnerHTC(fieldname,sVal2[0] + "." + (sVal2[1] + "00").substring(0,2),"");
				}else if(sVal2[1].substring(1,2) == "0"){
					//小数点以下第二位のみがゼロの場合
					setInnerHTC(fieldname,sVal2[0] + "." + sVal2[1].substring(0,1),"");
				}else{
					//小数点以下二桁ともゼロでない場合
					//何もしません。
				}
			}
		}
	}else if(para == "rate"){
		if(flag == "S"){
			if(sVal2.length == 2){
				//小数点以下がある場合
				sDrate = sVal2[1] + "0";
			}
			setInnerHTC(fieldname,sVal2[0] + "." + sDrate.substring(0,1),"");
		}else{
			if(sVal2.length == 1 || parseInt(sVal2[1],10) == 0){
				//小数点以下がない場合
				setInnerHTC(fieldname,sVal2[0],"");
			}else{
				//率は小数点以下1桁許可なので何もしません。
			}
		}
	}
}

function getToday(){
	//システム日付をyyyymmddで返します。
	now=new Date();
	yyyymmdd=now.getFullYear().toString().substr(0,4);
	if(now.getMonth()<9){yyyymmdd+="0";}
	yyyymmdd+=(now.getMonth()+1).toString();
	if(now.getDate()<10){yyyymmdd+="0";}
	yyyymmdd+=now.getDate().toString();
	return yyyymmdd;
}

function getNow(){
	//システム日付をyyyymmdd hh24:mi:ssで返します。
	now=new Date();
	yyyymmddhh24miss=now.getFullYear().toString().substr(0,4);
	if(now.getMonth()<9){yyyymmddhh24miss+="0";}
	yyyymmddhh24miss+=(now.getMonth()+1).toString();
	if(now.getDate()<10){yyyymmddhh24miss+="0";}
	yyyymmddhh24miss+=now.getDate().toString()+" ";
	if(now.getHours()<10){yyyymmddhh24miss+="0";}
	yyyymmddhh24miss+=now.getHours().toString()+":";
	if(now.getMinutes()<10){yyyymmddhh24miss+="0"}
	yyyymmddhh24miss+=now.getMinutes().toString()+":";
	if(now.getSeconds()<10){yyyymmddhh24miss+="0"}
	yyyymmddhh24miss+=now.getSeconds().toString();
	return yyyymmddhh24miss;
}

function dateFmt(yyyymmdd,p){
	//yyyymmddで送られたデータを
	//p=1:"/",2:"-"で加工して返します。
	rStr = "";
	if(p=="1"){
		rStr = yyyymmdd.substring(0,4) + "/" + yyyymmdd.substring(4,6) + "/" + yyyymmdd.substring(6,8);
	}else if(p=="2"){
		rStr = yyyymmdd.substring(0,4) + "-" + yyyymmdd.substring(4,6) + "-" + yyyymmdd.substring(6,8);
	}else{
		rStr = yyyymmdd;
	}
	return rStr;
}

function dateCnv(dfdate,systemdate){
	//3桁mddか4桁mmdd入力を8桁yyyymmddに変換します。
	var rDate = "";
	var w_dfdate = "" + parseInt(dfdate,10); //"0101"を"101"にします。
//alert("w_dfdate:" + w_dfdate);
	if(w_dfdate.substring(0,1) == "1" && w_dfdate.toString().length == 3){
		//2週間後の年を付加します。
		rDate = trimFixed(parseInt(dateBA(systemdate,"P","14").substring(0,4),10) * 10000 + parseInt(dfdate,10));
	}else{
		//1週間前の年を付加します。
		rDate = trimFixed(parseInt(dateBA(systemdate,"M","7").substring(0,4),10) * 10000 + parseInt(dfdate,10));
	}
	return rDate;
}

function dateBA(systemdate,pm,pdays){
	//パラメータsystemdate(yyyymmdd)に値に
	//pm:"P"plus,"M"minusのpdays日計算してyyyymmddを返します。
	var dDate = new Date(); //Dateオブジェクトワーク
	var rDate = ""; //戻り値
	//systemdateのDateオブジェクトを作成します。
	var years = systemdate.substring(0,4);
	var months = systemdate.substring(4,6);
	var days = systemdate.substring(6,8);
	if(months.substring(0,1) == "0"){
		months = months.substring(1,2);
	}
	if(days.substring(0,1) == "0"){
		days = days.substring(1,2);
	}
	years = parseInt(years,10);
	months = parseInt(months,10) -1;
	days = parseInt(days,10);
	var dates = new Date(years,months,days);

	//pdaysのミリ秒を計算します。
	var nmsec = parseInt(trimFixed(parseInt(pdays,10) * 1000 * 60 * 60 * 24),10); //14日のミリ秒

	if(pm == "P"){
		//pdays日後を取得します。
		dDate = new Date(dates.getTime() + nmsec);
	}else{
		//pdays日前を取得します。
		dDate = new Date(dates.getTime() - nmsec);
	}
//if(pm == "P"){
//	alert(pdays + "日後:" + dDate.getFullYear() + "/" + (dDate.getMonth()+1) + "/" + dDate.getDate());
//}else{
//	alert(pdays + "日前:" + dDate.getFullYear() + "/" + (dDate.getMonth()+1) + "/" + dDate.getDate());
//}
	rDate = dDate.getFullYear().toString().substr(0,4);
	if(dDate.getMonth()<9){
		rDate+="0";
	}
	rDate += (dDate.getMonth()+1).toString();
	if(dDate.getDate()<10){
		rDate+="0";
	}
	rDate += dDate.getDate().toString();

	return rDate;
}


function date6to8(yymmdd){
	//yymmddを8桁に変換します。
	rStr = 0;
	if(parseInt(yymmdd,10) == 0){
		rStr = 0;
	}else{
		if(parseInt(yymmdd,10) == 999999){
			rStr = parseInt("99999999",10);
		}else if(parseInt(yymmdd,10) >= 500000){
			rStr = 19000000 + parseInt(yymmdd,10);
		}else if(parseInt(yymmdd,10) < 500000){
			rStr = 20000000 + parseInt(yymmdd,10);
		}
	}
	return rStr;
}

function delYYYY(elm){
	//出荷日、着荷指定日にフォーカスがあたる場合に、
	//yyyymmddがセットされていた場合、mmddにします。
	if(document.getElementById(elm).value.length == 8){
		//yyyymmdd加工済みがセットされています。
		document.getElementById(elm).value = parseInt(document.getElementById(elm).value.substring(4,8),10);
	}
}


//文字列長を返します。
function getByte(text){
//文字列のバイト数を求めるには１文字ずつ抜き出しescape()を使ってエンコードします。
//エンコード結果は１バイト文字ならば３文字以内(%nnかCなどの文字になります)、
//日本語などの２バイト文字は４文字以上になります。
//エンコードされた文字列の長さを調べて４文字未満ならば１バイト、
//４文字以上ならば２バイトとしてカウンタを加算し、文字列の長さ分だけカウントします。
//ADD KAMIYAMA 半角ｶﾅもescapeすると長さ4より大きくなるのでコード判定
//改行コードは２バイト計算となります。
	count = 0;
	for (i=0; i<text.length; i++){
		//n = escape(text.charAt(i));
		//if (n.length < 4) count++; else count+=2;
		if(65382<= text.charCodeAt(i) && text.charCodeAt(i) <= 65439){
			//半角ｶﾅのコード
			count++;
		}else{
			n = escape(text.charAt(i));
			if (n.length < 4) count++; else count+=2;
		}
	}
//alert(count);
	return count;
}


function allCheck(){
	//チェックボックスを一括チェックします。
	var list = document.getElementById("tblwait").childNodes;
	for(i=0;i<list.length;i++){
		//子ノード取得tbodyを引っかける
		if(list[i].nodeName=="TBODY"){
			var list2 = list[i].childNodes;
			for(j=0;j<list2.length;j++){
				//子ノードTRを引っかける
				if(list2[j].nodeName=="TR"){
					var list3 = list2[j].childNodes;
					for(k=0;k<list3.length;k++){
						//子ノードTDを引っ掛ける
						if(list3[k].nodeName=="TD"){
							var list4 = list3[k].childNodes;
							for(l=0;l<list4.length;l++){
								//checkbox id=detailid[]を引っ掛ける
								if(list4[l].nodeName=="INPUT"){
									list4[l].checked = true;
								}//INPUT判断
							}//TDの子ノードループend
						}//TD判断
					}//TRの子ノードループend
				}//TR判断
			}//TBODYの子ノードループend
		}//TBODY判断
	}//tblwaitの子ノードループend
}
function reverceCheck(){
	//チェックボックスのチェックをを反転します。
	var list = document.getElementById("tblwait").childNodes;
	for(i=0;i<list.length;i++){
		//子ノード取得tbodyを引っかける
		if(list[i].nodeName=="TBODY"){
			var list2 = list[i].childNodes;
			for(j=0;j<list2.length;j++){
				//子ノードTRを引っかける
				if(list2[j].nodeName=="TR"){
					var list3 = list2[j].childNodes;
					for(k=0;k<list3.length;k++){
						//子ノードTDを引っ掛ける
						if(list3[k].nodeName=="TD"){
							var list4 = list3[k].childNodes;
							for(l=0;l<list4.length;l++){
								//checkbox id=detailid[]を引っ掛ける
								if(list4[l].nodeName=="INPUT"){
									if(list4[l].checked){
										list4[l].checked = false;
									}else{
										list4[l].checked = true;
									}
								}//INPUT判断
							}//TDの子ノードループend
						}//TD判断
					}//TRの子ノードループend
				}//TR判断
			}//TBODYの子ノードループend
		}//TBODY判断
	}//tblwaitの子ノードループend
}

//大杉さんからもらった部品
//URLエンコード
//引数dataは、stringかobjectで渡せます
function uriEncode(data,url){
	var encdata =(url.indexOf('?')==-1)?'?dmy':'';
	if(typeof data=='object'){
		for(var i in data)
			//encdata+='&'+encodeURIComponent(i)+'='+encodeURIComponent(data[i]);
			w_data = uri_encode(data[1]);
			//encdata+='&'+escape(i)+'='+escape(data[i]);
			encdata+='&'+escape(i)+'='+w_data;
	} else if(typeof data=='string'){
		if(data=="")return "";
		//&と=で一旦分解しencode
		var encdata = '';
		var datas = data.split('&');
		//for(i=1;i<datas.length;i++)
		for(i=0;i<datas.length;i++)
		{
			var dataq = datas[i].split('=');
			//encdata += '&'+encodeURIComponent(dataq[0])+'='+encodeURIComponent(dataq[1]);
			//encdata += '&'+escape(dataq[0])+'='+escape(dataq[1]);
			if(i==0){
				//encdata += escape(dataq[0])+'='+escape(dataq[1]);
				w_dataq = uri_encode(dataq[1]);
				encdata += escape(dataq[0])+'='+w_dataq;
			}else{
				//encdata += '&'+escape(dataq[0])+'='+escape(dataq[1]);
				w_dataq = uri_encode(dataq[1]);
				encdata += '&'+escape(dataq[0])+'='+w_dataq;
			}
		}
	} 
	return encdata;
}
//松田君から聞いた関数(best bridal)
//  URIエンコード
//  使用方法   uri_encode(word)
//   
function uri_encode(word){
	var i;
	var wk_word;
	var tp_word;
	wk_word = "";
	tp_word = "";
	chkencode = ' -^\\@[;:],./\!"#$&\'()=~|`{+*}<>?_';
	//半角カナチェック	
	wk_word = escape(word);
	for (i=0;i<wk_word.length;i++){
		if (chkencode.indexOf(wk_word.charAt(i)) != -1){

			if (wk_word.charAt(i) == " "){
				tp_word = tp_word + "%20";
			}else if(wk_word.charAt(i) == "-"){
				tp_word = tp_word + "%2D";
			}else if(wk_word.charAt(i) == "^"){
				tp_word = tp_word + "%5E";
			}else if(wk_word.charAt(i) == "\\"){
				tp_word = tp_word + "%5C";
			}else if(wk_word.charAt(i) == "@"){
				tp_word = tp_word + "%40";
			}else if(wk_word.charAt(i) == "["){
				tp_word = tp_word + "%5B";
			}else if(wk_word.charAt(i) == ";"){
				tp_word = tp_word + "%3B";
			}else if(wk_word.charAt(i) == ":"){
				tp_word = tp_word + "%3A";
			}else if(wk_word.charAt(i) == "]"){
				tp_word = tp_word + "%5D";
			}else if(wk_word.charAt(i) == ","){
				tp_word = tp_word + "%2C";
			}else if(wk_word.charAt(i) == "."){
				tp_word = tp_word + "%2E";
			}else if(wk_word.charAt(i) == "/"){
				tp_word = tp_word + "%2F";
			}else if(wk_word.charAt(i) == "!"){
				tp_word = tp_word + "%21";
			}else if(wk_word.charAt(i) == "\""){
				tp_word = tp_word + "%22";
			}else if(wk_word.charAt(i) == "#"){
				tp_word = tp_word + "%23";
			}else if(wk_word.charAt(i) == "$"){
				tp_word = tp_word + "%24";
			}else if(wk_word.charAt(i) == "&"){
				tp_word = tp_word + "%26";
			}else if(wk_word.charAt(i) == "'"){
				tp_word = tp_word + "%27";
			}else if(wk_word.charAt(i) == "("){
				tp_word = tp_word + "%28";
			}else if(wk_word.charAt(i) == ")"){
				tp_word = tp_word + "%29";
			}else if(wk_word.charAt(i) == "="){
				tp_word = tp_word + "%3D";
			}else if(wk_word.charAt(i) == "~"){
				tp_word = tp_word + "%7E";
			}else if(wk_word.charAt(i) == "|"){
				tp_word = tp_word + "%7C";
			}else if(wk_word.charAt(i) == "`"){
				tp_word = tp_word + "%60";
			}else if(wk_word.charAt(i) == "{"){
				tp_word = tp_word + "%7B";
			}else if(wk_word.charAt(i) == "+"){
				tp_word = tp_word + "%2B";
			}else if(wk_word.charAt(i) == "*"){
				tp_word = tp_word + "%2A";
			}else if(wk_word.charAt(i) == "}"){
				tp_word = tp_word + "%7D";
			}else if(wk_word.charAt(i) == "<"){
				tp_word = tp_word + "%3C";
			}else if(wk_word.charAt(i) == ">"){
				tp_word = tp_word + "%3E";
			}else if(wk_word.charAt(i) == "?"){
				tp_word = tp_word + "%3F";
			}else if(wk_word.charAt(i) == "_"){
				tp_word = tp_word + "%5F";
			}

		}else{
			tp_word = tp_word + wk_word.charAt(i)
		}
	}
	return(tp_word);
}



//-----------------------------------------------------------------------------others end

