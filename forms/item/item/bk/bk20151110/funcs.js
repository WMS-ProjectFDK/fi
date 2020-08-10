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
	// ���X�i�[�̍폜
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

//ajax�ʐM���s�����߂�e�L�X�g(httpObj.responseText)�Ƀy�[�W�\���G���[�����񂪂��邩�m�F���܂��B
function systemErrCheck(t){
	//t:httpObj.responseText���Z�b�g����邱�Ƃ��O��ł��B
	var wt = "" + t;
	if(wt.indexOf("<title>�y�[�W��\���ł��܂���</title>") != -1){
		return false;
	}
	return true;
}

//�吙�Ј��쐬��moveElements.js����R�s�[&���Hstart
var beforValue = ""; //�t�B�[���h�̃t�H�[�J�X�������������_�̒l�ێ��p
var nowField = ""; //�t�H�[�J�X�t�B�[���h�ێ��p
var beforField = ""; //�߂�t�H�[�J�X�t�B�[���h�ێ��p
var afterField = ""; //����t�H�[�J�X�t�B�[���h�ێ��p
function setAllListner(){
	//form���̃I�u�W�F�N�g�S�Ă�focus,blur,keydown��ݒ肵�܂��B
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
		//�^�u�ňړ������Ȃ�!
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
				//�{�^���G�������g�����Ō��̃t�B�[���h�Ƀt�H�[�J�X��߂�����
				//�ێ����Ȃ�
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
	//�}�E�X�N���b�N�ɑ΂���A�N�V�����ł��B
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
	//form���̃I�u�W�F�N�g�S�Ă���focus,blur,keydown���폜���܂��B
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
//�吙�Ј��쐬��moveElements.js����R�s�[&���Hend

//-----------------------------------------------------------------------------ajax end

//-----------------------------------------------------------------------------input check start
//���p�p���̂݃`�F�b�N
function singleAsciiCheck(p_str){
	//".":46 "-":45 "_":95
	str = p_str
	err = 0;
	for (i=0;i<str.length;i++){
		icode = str.charCodeAt(i);
		if ((48<=icode && icode <=57) || (65<= icode && icode <=90) || (97 <= icode && icode <= 122) || 
			(46 == icode) || (45 == icode) || (95 == icode)){
			//�^�������牽�����Ȃ�
		}else{
			err++;
		}
	}
	//if (err!=0) alert('���͔͂��p�p�����ł��肢���܂��B');
	if (err!=0){
		//alert('���͔͂��p�ł��肢���܂��B');
		return false
	}
	return true;
	//else alert('�G���[�͂���܂���');
}
//���p���l�̂݃`�F�b�N
function singleNumCheck(p_str){
	//-:45����
	var iCount;
	var iCode;
	st_val=p_str;
	for (iCount=0 ; iCount<st_val.length ; iCount++){
		iCode = st_val.charCodeAt(iCount);
		if (45 != iCode && (48 > iCode || iCode > 57)){
			//alert("���l�ȊO���܂܂�Ă��܂��B");
			//return;
			return false;
		}
	}
	//alert("�S�Đ��l�ł��B");
	//return;
	return true;
}
//�S�p�����̂݃`�F�b�N
function doubleStrCheck(p_str){
	var iCount;
	var sTemp;
	var st_val=p_str;

	//���p�J�i�̑��݃`�F�b�N
	var iCode;

	for (iCount=0 ; iCount<st_val.length ; iCount++){
		iCode = st_val.charCodeAt(iCount);
		if ((65382<= iCode && iCode <= 65439)){
			//alert("���p�J�i���܂܂�Ă��܂��B");
			//return;
			return false;
		}
	}
	//alert("���p�J�i�͊܂܂�Ă��܂���B");
	//return;

	for (iCount=0;iCount < st_val.length;iCount++){
		sTemp = escape(st_val.charAt(iCount));
		if (sTemp.length <  4){ 
		//alert("�S�p�ȊO�̕������܂܂�Ă��܂��B");
		//return;
		return false;
		}
	}
	//alert("�S�đS�p�����ł��B");
	//return;
	return true;
}

//���p�J�i�̂݃`�F�b�N
function singleStrCheck(p_str){
	var iCount;
	var sTemp;
	var st_val=p_str;

	//���p�J�i�̑��݃`�F�b�N
	var iCode;

	for (iCount=0 ; iCount<st_val.length ; iCount++){
		iCode = st_val.charCodeAt(iCount);
		if ((65382 > iCode || iCode > 65439)){
			return false;
		}
	}
	return true;
}

//�S�p�֎~�`�F�b�N
function doubleStrCheck2(p_str){
	var err = 0;
	for (i=0; i<p_str.length; i++){
		if(65382<= p_str.charCodeAt(i) && p_str.charCodeAt(i) <= 65439){
			//���p�ł̃R�[�h
			continue;
		}else{
			n = escape(p_str.charAt(i));
			if (n.length >= 4){ err++;}
		}
	}
	if (err!=0){
		//alert('���͔͂��p�ł��肢���܂��B');
		return false
	}
	return true;
}
function doubleStrCheck3(p_str){
	//���p�ł��֎~
	var err = 0;
	for (i=0; i<p_str.length; i++){
		if(65382<= p_str.charCodeAt(i) && p_str.charCodeAt(i) <= 65439){
			//���p�ł̃R�[�h
			err++;
		}else{
			n = escape(p_str.charAt(i));
			if (n.length >= 4){ err++;}
		}
	}
	if (err!=0){
		//alert('���͔͂��p�ł��肢���܂��B');
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

//���t�Ó����`�F�b�N
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
	//YYYYMMDD�ɑ΂��ăV�X�e�����t��31���ȏ�O��31���ȏ��̏ꍇ�R���t�@�[���E�B���h�E��
	//���ӂ𑣂��܂��B
	//�V�X�e�����t�擾
	var today = new Date();
	var nmsec = 30 * 1000 * 60 * 60 * 24; //30���̃~���b
	//30���O�擾
	var befor30 = new Date(today.getTime() - nmsec);
//alert("befor30:" + befor30.getFullYear() + "/" + (befor30.getMonth()+1) + "/" + befor30.getDate());
	//30����擾
	var after30 = new Date(today.getTime() + nmsec);
//alert("after30:" + after30.getFullYear() + "/" + (after30.getMonth()+1) + "/" + after30.getDate());
	//�p�����[�^�Ŏ󂯂����t��date�I�u�W�F�N�g�ɂ��Ĕ�r
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
	//dfdate�̓��tyyyymmdd���A1�T�ԑO����2�T�Ԍ�̊ԂɊ܂܂�Ă��邩�`�F�b�N���܂��B
	var beforDate = dateBA(systemdate,"M","7");
	var afterDate = dateBA(systemdate,"P","14");
	var ddate = new Date(dfdate.substring(0,4),dfdate.substring(4,6),dfdate.substring(6,8));//8�����t��Date�I�u�W�F�N�g��
	var dbdate = new Date(beforDate.substring(0,4),beforDate.substring(4,6),beforDate.substring(6,8));//8���O���t��Date�I�u�W�F�N�g��
	var dadate = new Date(afterDate.substring(0,4),afterDate.substring(4,6),afterDate.substring(6,8));//8������t��Date�I�u�W�F�N�g��
//alert("ddate:"+ddate.getTime());
//alert("bdate:"+dbdate.getTime());
//alert("adate:"+dadate.getTime());
	if(ddate.getTime() < dbdate.getTime() || ddate.getTime() > dadate.getTime()){
		return false;
	}
	return true;
}

function checkPastDate(yyyymmdd1,yyyymmdd2){
	//yyyymmdd�`���œ��t���󂯎��1<2�̏ꍇ�G���[�ɂ��܂��B
	//yyyymmdd1��date�I�u�W�F�N�g���쐬���܂��B
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
	//yyyymmdd2��date�I�u�W�F�N�g���쐬���܂��B
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
	//date1��date2���r����date1<date2�̓G���[
	if(date1.getTime() < date2.getTime()){
		return false;
	}
	return true;
}

//�����̐��l�A���`�F�b�N
function checkDecimal(str,int1,int2){
	//�����A���̃`�F�b�N���s���܂��B�߂�l�j0:����B1:���G���[�A2:���l�G���[
	//str�j���l������Aint1�j�����������Aint2�j����������

	//'.'�ŕ������܂��B
	var tmp = str.split(".");
	if(tmp.length == 1){
		//tmp�̔z�񐔂�1�Ȃ珬���ł͂���܂���B
		var seisuu = tmp[0];
		//�������̌��`�F�b�N
		if(seisuu.length > int1){
			return 1;
		}
		//�������̐��l�`�F�b�N
		if(singleNumCheck(seisuu) == false){
			return 2;
		}
	}else if(tmp.length >= 3){
		//tmp�̔z�񐔂�3�ȏ�Ȃ�G���[�ł��B
		return 2;
	}else{
		//tmp�̔z�񐔂�2�Ȃ琮�����A�������̔��p���l�A���`�F�b�N���s���܂��B
		var seisuu = tmp[0];
		//�������̌��`�F�b�N
		if(seisuu.length > int1){
			return 1;
		}
		//�������̐��l�`�F�b�N
		if(singleNumCheck(seisuu) == false){
			return 2;
		}
		var syousuu = tmp[1];
		//�������̌��`�F�b�N
		if(syousuu.length > int2){
			return 1;
		}
		//�������̐��l�`�F�b�N
		if(singleNumCheck(syousuu) == false){
			return 2;
		}
	}
	return 0;
}

//������̑S���p���݂��`�F�b�N���܂��B
function checkStrMix(str){
	//���p�������������S�p�����������`�F�b�N����
	//���݂��Ă�����G���[�Ƃ��܂��B
	whankaku = doubleStrCheck2(str); //true�͔��p�������̂�
	wzenkaku = doubleStrCheck(str); //true�͑S�p�����̂�
	if(!whankaku && !wzenkaku){
		//���ꂼ�ꋖ�ȊO�̕������������̂ō��݂��Ă���Ƃ݂Ȃ��܂��B
		return false;
	}
	return true;
}


//-----------------------------------------------------------------------------input check end

//-----------------------------------------------------------------------------others start
//innerHTML�̒l��ݒ肵�܂��B
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
//innerHTML�̒l���擾���܂��B
function getInnerTC(sElm){
	if (typeof document.getElementById(sElm).textContent != "undefined") {
		//firefox
		return document.getElementById(sElm).textContent;
	}else{
		//ie
		return document.getElementById(sElm).innerText;
	}
}

//�O��̔��p�X�y�[�X�A�S�p�X�y�[�X�A�^�u���폜���܂��B
function Trim(str) {
	return LTrim(RTrim(str));
}

//�O�̔��p�X�y�[�X�A�S�p�X�y�[�X�A�^�u���폜���܂��B
function LTrim(str) {
	return str.replace(/^[ �@\t]+/, "");
//	return str.toString().replace(/^[\s�@]+/, "");
}

//���̔��p�X�y�[�X�A�S�p�X�y�[�X�A�^�u���폜���܂��B
function RTrim(str) {
	return str.replace(/[ �@\t]+$/g, "");
//	return str.toString().replace(/[\s�@]+$/g, "");
}

//���s�R�[�h�̃g����
//http://sohgetsu.blogspot.com/2007/08/javascript.html
function remove_newline(text){
	text = text.replace(/\r\n/g, "");//IE
	text = text.replace(/\n/g, "");//Firefox
	return text;
} 

//�u���E�U���� 0��ie 1��ie6�ȍ~�ȊO
function	checkBrowser(){
	var	browser;
	str  = navigator.appName.toUpperCase();
	str2 = navigator.userAgent.toUpperCase();
	if (str2.indexOf("ICAB") >= 0)     browser="1";
	if (str.indexOf("NETSCAPE") >= 0)  browser="1";
	if (str.indexOf("MICROSOFT") >= 0){
		//�o�[�W�������擾���܂��B
		appVer  = navigator.appVersion;//netscape?
		appVer  = navigator.userAgent;
		s = appVer.indexOf("MSIE ",0) + 5;
		e = appVer.indexOf(";",s);
		version = eval(appVer.substring(s,e));
		//alert(version);
		//ie5.5�ȑO�͑ΏۊO�ł��B
		if(version >= 6){
			browser = "0";
		}else{
			browser = "1";
		}
	}
//	if(window.ActiveXObject){
//		//�o�[�W�������擾���܂��B
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
//		//ie5.5�ȑO�͑ΏۊO�ł��B
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

//�u���E�U���ʌ��ʂ���N���X�w��̕�����𕪂���
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

//���X�i�o�^���ꂽ�t�@���N�V��������C�x���g�����炢id��Ԃ�
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

//�e�[�u������s���폜����
function delRow(rowid){
	var sTarget = "row-" + rowid;
	var oTarget = document.getElementById(sTarget);
	var parent = oTarget.parentNode;
	parent.removeChild(oTarget);
}


//�C���v�b�g�t�B�[���h����͉ɂ���B
function inputEnable(id){
	document.getElementById(id).readOnly = "";
	document.getElementById(id).style.backgroundColor = "#FFFFFF";
}

//�C���v�b�g�t�B�[���h����͕s�ɂ���B
function inputDisable(id){
	document.getElementById(id).readOnly = "readOnly";
	document.getElementById(id).style.backgroundColor = "#DCDCDC";
}


// Tid �X�N���[���@�\��t������e�[�u����ID
//
// tHeight �e�[�u���̍����̎w��
//
//http://d.hatena.ne.jp/Mars/20050115#p1�̃T���v�����烊���N���Ă���A
//http://c-man.s21.xrea.com/mars/md20050115.html�̃\�[�X�����H
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
	// ���̃Z�������X�^�C���Ƃ��Đݒ肷��B
	/* �����%�w��ςȂ̂Őݒ肵�Ȃ�
	for(var i=0;i<oTBL.tHead.rows[0].cells.length;i++) {
		oTBL.tHead.rows[0].cells[i].style.width = 
		oTBL.tBodies[0].rows[0].cells[i].style.width = 
			(oTBL.tHead.rows[0].cells[i].clientWidth - oTBL.cellPadding * 2)+ 'px';
	}
	*/
	// �w�b�_���̍�����ޔ�
	var ThHeight = oTBL.tHead.offsetHeight;
	oTBL.style.width = oTBL.offsetWidth + 'px';
	var tWidth = oTBL.offsetWidth+1;// +1�͎Z�o�덷�p-�����Ƒ傫���Ƃ�Ȃ��ƃ_���ȏꍇ�����邩��


	// �e�[�u���̕������쐬�@id������id+'_H'�Ƃ��Atbody�̒��g���폜
	var oTBL1 = oTBL.cloneNode(true);
	oTBL1.id += '_H';
	while(oTBL1.tBodies[0].rows.length) {
		oTBL1.tBodies[0].deleteRow(0);
	}

	// �V�KDIV - �w�b�_���p ���쐬
	var newDiv1 = document.createElement('div');
	newDiv1.id='D_'+oTBL1.id;
	//newDiv1.style.width = tWidth+'px';
	//tbody �X�N���[���o�[��thead���ɂ�width��18�ǉ�
	newDiv1.style.width = (tWidth+18)+'px';
	newDiv1.style.height = ThHeight+'px';
	newDiv1.style.overflow = 'hidden';
	newDiv1.style.position = 'relative';
	oTBL1.style.position = 'absolute';
	oTBL1.style.left = '0';
	oTBL1.style.top = '0';

	newDiv1.appendChild(oTBL1);
	oTBL.parentNode.insertBefore(newDiv1,oTBL);
	// �e�[�u���̕������쐬�@�w�b�_�����폜
	var oTBL2 = oTBL.cloneNode(true);
	oTBL2.id += '_B';
	oTBL2.deleteTHead();
	// �V�KDIV - �{�f�B���p ���쐬
	var newDiv2 = document.createElement('div');
	newDiv2.id='D_'+oTBL2.id;
	newDiv2.style.width = (tWidth+18)+'px';
//	newDiv2.style.height = tHeight+'px';
	newDiv2.style.height = tHeight+'%';
	//newDiv2.style.overflow = 'auto'; //���ӂꂽ��X�N���[���o�[�\��
	//�c�����X�N���[���o�[�\��
	newDiv2.style.overflowX = 'hidden'; //���X�N���[���o�[�Ȃ�
	newDiv2.style.overflowY = 'scroll'; //�c�X�N���[���o�[�펞�\��

	oTBL2.style.left = '0';
	oTBL2.style.top = '0';
	oTBL2.style.position = 'absolute'; //appendChild���s���ƈ�萔�ȏ�Ńw�b�_�Ɨ���Ă����̂�h�~

	newDiv2.appendChild(oTBL2);
	oTBL.parentNode.insertBefore(newDiv2,oTBL);

	//oTBL��id��ޔ�kami
	var oTBL_id = oTBL.id;
	// ���e�[�u�����폜
	oTBL.parentNode.removeChild(oTBL);

	//oTBL2��id�����ɖ߂�kami
	oTBL2.id = oTBL_id;

}

//http://www.openspc2.org/reibun/javascript/business/009/index.html�����H
//�J���}�ҏW
function addComma(val){
	var val2 = "" + val;
	var s_val = val2.split(".");
	//�������������J���}�ҏW
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

//�J���}�폜
function delComma(val){
	var val2 = "" + val;
	var s_val = val2.split(".");
	//�������������J���}�폜�ҏW
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
//���Z�덷�����Ȃ�����֐�
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

//�`�b�v�w���v
timerID = 0;
sec = 2.5 * 1000; //�\������2.5sec

//�`�b�v�w���v��\������֐�
function sChipHelp(divID,x,y){
	var offX = offY = 0; //�����l
	if(document.layers){
		chipOBJ = document.layers[divID]; //NN4�Ή�
	}
	if(document.all){
		chipOBJ = document.all[divID].style; //IE4�Ή�
	}
	if(document.getElementById){
		chipOBJ = document.getElementById(divID).style; //DOM�Ή�
	}
	if(document.all){
		//IE5�ȍ~�̃X�N���[���l�擾
		offX = document.body.scrollLeft;
		offY = document.body.scrollTop;
	}
	chipOBJ.visibility = "visible"; //�`�b�v�w���v�\��
	chipOBJ.left = x + offX; //�|�b�v�A�b�v�ʒu
	//chipOBJ.top = y + offY + 16; //y�����ɃJ�[�\����(16px)�������炷 (^^)
	chipOBJ.top = y + offY - 16; //y�����ɃJ�[�\����(-16px)�������炷 (^^)
	timerID = setTimeout("hChipHelp('"+divID+"')",sec); //��莞�ԕ\����������� 
 } 

//�`�b�v�w���v���\���ɂ���֐�
function hChipHelp(divID){ //�`�b�v�w���v���\���ɂ���֐�
	if (document.layers) chipOBJ = document.layers[divID];
	if (document.all) chipOBJ = document.all[divID].style;
	if (document.getElementById) chipOBJ = document.getElementById(divID).style;
	chipOBJ.visibility = "hidden"; //�`�b�v�w���v��\��
	clearTimeout(timerID); //�^�C�}�[������
}

//message�G���A�ł̃G���^�[�L�[�ɂ��submit�}�~
function cancelSubmit(e){
	//form���Ƀe�L�X�g�{�b�N�X���P�̏ꍇ�A�G���^�[�L�[��submit���������邽�ߗ}������B
	return (event.keyCode == 13) ? false : true;
}

//���t���ڂ��U���̏ꍇ�A�N��2000�𑫂��ĕԂ��܂��B
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

//�N�����ڂ��S���̏ꍇ�A�N��2000�𑫂��ĕԂ��܂��B
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

//���[���l�߂��܂��B
function addLZero(val,vallen){
	//val:��ʂ̒l
	//vallen:�[���l�ߌ�̉���
	var rStr = "";
	var zero = "";
	//val�̑O��̑S�p�󔒁A���p�󔒂��폜���܂��B
	rStr = Trim(val);
	if(rStr.length < parseInt(vallen,10)){
		for(i=0;i<parseInt(vallen,10) - rStr.length;i++){
			zero = zero + "0";
		}
		rStr = zero + rStr;
	}
	return rStr;
}

//�E�[���l�߂��܂��B
function addRZero(val,vallen){
	//val:��ʂ̒l
	//vallen:�[���l�ߌ�̉���
	var rStr = "";
	var zero = "";
	//val�̑O��̑S�p�󔒁A���p�󔒂��폜���܂��B
	rStr = Trim(val);
	if(rStr.length < parseInt(vallen,10)){
		for(i=0;i<parseInt(vallen,10) - rStr.length;i++){
			zero = zero + "0";
		}
		rStr = rStr + zero;
	}
	return rStr;
}

//�p�[�Z���g�v�Z
function percentCalc(var1,var2){
	var percentStr = "";
	if(parseFloat(var2) == 0){
		alert("�[�����Z�G���[�ł��B");
		return percentStr;
	}
	//Math.round(n)�͏����_�ȉ����ʂ��ۂ߂Đ����ɂ���̂�
	//1000�{���Ċۂ߂����Ƃ�10�Ŋ���
	percentStr = Math.round(parseFloat(var1) / parseFloat(var2) * 1000) / 10;
	return percentStr;
}

//�����_�ȉ��\���A��\��
function setDecimal(fieldname,flag,para){
	//para��tanka��rate���n���ꂽ�ꍇ�Aflag"S"set�͏����_�ȉ��\���ݒ�A"D"del�͏����_�ȉ��\���폜���s���܂��B
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
				//�����_�ȉ�������ꍇ
				sDtanka = sVal2[1] + "00";
			}
			document.getElementById(fieldname).value = sVal2[0] + "." + sDtanka.substring(0,2);
		}else{
			if(sVal2.length == 1 || parseInt(sVal2[1],10) == 0){
				//�����_�ȉ����Ȃ��ꍇ
				document.getElementById(fieldname).value = sVal2[0];
			}else{
				if(sVal2[1].substring(0,1) == "0"){
					//�����_�ȉ����ʂ݂̂��[���̏ꍇ
					document.getElementById(fieldname).value = sVal2[0] + "." + (sVal2[1] + "00").substring(0,2);
				}else if(sVal2[1].substring(1,2) == "0"){
					//�����_�ȉ����ʂ݂̂��[���̏ꍇ
					document.getElementById(fieldname).value = sVal2[0] + "." + sVal2[1].substring(0,1);
				}else{
					//�����_�ȉ��񌅂Ƃ��[���łȂ��ꍇ
					//�������܂���B
				}
			}
		}
	}else if(para == "rate"){
		if(flag == "S"){
			if(sVal2.length == 2){
				//�����_�ȉ�������ꍇ
				sDrate = sVal2[1] + "0";
			}
			document.getElementById(fieldname).value = sVal2[0] + "." + sDrate.substring(0,1);
		}else{
			if(sVal2.length == 1 || parseInt(sVal2[1],10) == 0){
				//�����_�ȉ����Ȃ��ꍇ
				document.getElementById(fieldname).value = sVal2[0];
			}else{
				//���͏����_�ȉ�1�����Ȃ̂ŉ������܂���B
			}
		}
	}
}

function setDecimalShow(fieldname,flag,para){
	//para��tanka��rate���n���ꂽ�ꍇ�Aflag"S"set�͏����_�ȉ��\���ݒ�A"D"del�͏����_�ȉ��\���폜���s���܂��B
	//innerText�Ώ�
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
				//�����_�ȉ�������ꍇ
				sDtanka = sVal2[1] + "00";
			}
			setInnerHTC(fieldname,sVal2[0] + "." + sDtanka.substring(0,2),"");
		}else{
			if(sVal2.length == 1 || parseInt(sVal2[1],10) == 0){
				//�����_�ȉ����Ȃ��ꍇ
				setInnerHTC(fieldname,sVal2[0],"");
			}else{
				if(sVal2[1].substring(0,1) == "0"){
					//�����_�ȉ����ʂ݂̂��[���̏ꍇ
					setInnerHTC(fieldname,sVal2[0] + "." + (sVal2[1] + "00").substring(0,2),"");
				}else if(sVal2[1].substring(1,2) == "0"){
					//�����_�ȉ����ʂ݂̂��[���̏ꍇ
					setInnerHTC(fieldname,sVal2[0] + "." + sVal2[1].substring(0,1),"");
				}else{
					//�����_�ȉ��񌅂Ƃ��[���łȂ��ꍇ
					//�������܂���B
				}
			}
		}
	}else if(para == "rate"){
		if(flag == "S"){
			if(sVal2.length == 2){
				//�����_�ȉ�������ꍇ
				sDrate = sVal2[1] + "0";
			}
			setInnerHTC(fieldname,sVal2[0] + "." + sDrate.substring(0,1),"");
		}else{
			if(sVal2.length == 1 || parseInt(sVal2[1],10) == 0){
				//�����_�ȉ����Ȃ��ꍇ
				setInnerHTC(fieldname,sVal2[0],"");
			}else{
				//���͏����_�ȉ�1�����Ȃ̂ŉ������܂���B
			}
		}
	}
}

function getToday(){
	//�V�X�e�����t��yyyymmdd�ŕԂ��܂��B
	now=new Date();
	yyyymmdd=now.getFullYear().toString().substr(0,4);
	if(now.getMonth()<9){yyyymmdd+="0";}
	yyyymmdd+=(now.getMonth()+1).toString();
	if(now.getDate()<10){yyyymmdd+="0";}
	yyyymmdd+=now.getDate().toString();
	return yyyymmdd;
}

function getNow(){
	//�V�X�e�����t��yyyymmdd hh24:mi:ss�ŕԂ��܂��B
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
	//yyyymmdd�ő���ꂽ�f�[�^��
	//p=1:"/",2:"-"�ŉ��H���ĕԂ��܂��B
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
	//3��mdd��4��mmdd���͂�8��yyyymmdd�ɕϊ����܂��B
	var rDate = "";
	var w_dfdate = "" + parseInt(dfdate,10); //"0101"��"101"�ɂ��܂��B
//alert("w_dfdate:" + w_dfdate);
	if(w_dfdate.substring(0,1) == "1" && w_dfdate.toString().length == 3){
		//2�T�Ԍ�̔N��t�����܂��B
		rDate = trimFixed(parseInt(dateBA(systemdate,"P","14").substring(0,4),10) * 10000 + parseInt(dfdate,10));
	}else{
		//1�T�ԑO�̔N��t�����܂��B
		rDate = trimFixed(parseInt(dateBA(systemdate,"M","7").substring(0,4),10) * 10000 + parseInt(dfdate,10));
	}
	return rDate;
}

function dateBA(systemdate,pm,pdays){
	//�p�����[�^systemdate(yyyymmdd)�ɒl��
	//pm:"P"plus,"M"minus��pdays���v�Z����yyyymmdd��Ԃ��܂��B
	var dDate = new Date(); //Date�I�u�W�F�N�g���[�N
	var rDate = ""; //�߂�l
	//systemdate��Date�I�u�W�F�N�g���쐬���܂��B
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

	//pdays�̃~���b���v�Z���܂��B
	var nmsec = parseInt(trimFixed(parseInt(pdays,10) * 1000 * 60 * 60 * 24),10); //14���̃~���b

	if(pm == "P"){
		//pdays������擾���܂��B
		dDate = new Date(dates.getTime() + nmsec);
	}else{
		//pdays���O���擾���܂��B
		dDate = new Date(dates.getTime() - nmsec);
	}
//if(pm == "P"){
//	alert(pdays + "����:" + dDate.getFullYear() + "/" + (dDate.getMonth()+1) + "/" + dDate.getDate());
//}else{
//	alert(pdays + "���O:" + dDate.getFullYear() + "/" + (dDate.getMonth()+1) + "/" + dDate.getDate());
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
	//yymmdd��8���ɕϊ����܂��B
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
	//�o�ד��A���׎w����Ƀt�H�[�J�X��������ꍇ�ɁA
	//yyyymmdd���Z�b�g����Ă����ꍇ�Ammdd�ɂ��܂��B
	if(document.getElementById(elm).value.length == 8){
		//yyyymmdd���H�ς݂��Z�b�g����Ă��܂��B
		document.getElementById(elm).value = parseInt(document.getElementById(elm).value.substring(4,8),10);
	}
}


//�����񒷂�Ԃ��܂��B
function getByte(text){
//������̃o�C�g�������߂�ɂ͂P�����������o��escape()���g���ăG���R�[�h���܂��B
//�G���R�[�h���ʂ͂P�o�C�g�����Ȃ�΂R�����ȓ�(%nn��C�Ȃǂ̕����ɂȂ�܂�)�A
//���{��Ȃǂ̂Q�o�C�g�����͂S�����ȏ�ɂȂ�܂��B
//�G���R�[�h���ꂽ������̒����𒲂ׂĂS���������Ȃ�΂P�o�C�g�A
//�S�����ȏ�Ȃ�΂Q�o�C�g�Ƃ��ăJ�E���^�����Z���A������̒����������J�E���g���܂��B
//ADD KAMIYAMA ���p�ł�escape����ƒ���4���傫���Ȃ�̂ŃR�[�h����
//���s�R�[�h�͂Q�o�C�g�v�Z�ƂȂ�܂��B
	count = 0;
	for (i=0; i<text.length; i++){
		//n = escape(text.charAt(i));
		//if (n.length < 4) count++; else count+=2;
		if(65382<= text.charCodeAt(i) && text.charCodeAt(i) <= 65439){
			//���p�ł̃R�[�h
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
	//�`�F�b�N�{�b�N�X���ꊇ�`�F�b�N���܂��B
	var list = document.getElementById("tblwait").childNodes;
	for(i=0;i<list.length;i++){
		//�q�m�[�h�擾tbody������������
		if(list[i].nodeName=="TBODY"){
			var list2 = list[i].childNodes;
			for(j=0;j<list2.length;j++){
				//�q�m�[�hTR������������
				if(list2[j].nodeName=="TR"){
					var list3 = list2[j].childNodes;
					for(k=0;k<list3.length;k++){
						//�q�m�[�hTD�������|����
						if(list3[k].nodeName=="TD"){
							var list4 = list3[k].childNodes;
							for(l=0;l<list4.length;l++){
								//checkbox id=detailid[]�������|����
								if(list4[l].nodeName=="INPUT"){
									list4[l].checked = true;
								}//INPUT���f
							}//TD�̎q�m�[�h���[�vend
						}//TD���f
					}//TR�̎q�m�[�h���[�vend
				}//TR���f
			}//TBODY�̎q�m�[�h���[�vend
		}//TBODY���f
	}//tblwait�̎q�m�[�h���[�vend
}
function reverceCheck(){
	//�`�F�b�N�{�b�N�X�̃`�F�b�N���𔽓]���܂��B
	var list = document.getElementById("tblwait").childNodes;
	for(i=0;i<list.length;i++){
		//�q�m�[�h�擾tbody������������
		if(list[i].nodeName=="TBODY"){
			var list2 = list[i].childNodes;
			for(j=0;j<list2.length;j++){
				//�q�m�[�hTR������������
				if(list2[j].nodeName=="TR"){
					var list3 = list2[j].childNodes;
					for(k=0;k<list3.length;k++){
						//�q�m�[�hTD�������|����
						if(list3[k].nodeName=="TD"){
							var list4 = list3[k].childNodes;
							for(l=0;l<list4.length;l++){
								//checkbox id=detailid[]�������|����
								if(list4[l].nodeName=="INPUT"){
									if(list4[l].checked){
										list4[l].checked = false;
									}else{
										list4[l].checked = true;
									}
								}//INPUT���f
							}//TD�̎q�m�[�h���[�vend
						}//TD���f
					}//TR�̎q�m�[�h���[�vend
				}//TR���f
			}//TBODY�̎q�m�[�h���[�vend
		}//TBODY���f
	}//tblwait�̎q�m�[�h���[�vend
}

//�吙���񂩂����������i
//URL�G���R�[�h
//����data�́Astring��object�œn���܂�
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
		//&��=�ň�U������encode
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
//���c�N���畷�����֐�(best bridal)
//  URI�G���R�[�h
//  �g�p���@   uri_encode(word)
//   
function uri_encode(word){
	var i;
	var wk_word;
	var tp_word;
	wk_word = "";
	tp_word = "";
	chkencode = ' -^\\@[;:],./\!"#$&\'()=~|`{+*}<>?_';
	//���p�J�i�`�F�b�N	
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

