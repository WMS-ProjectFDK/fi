//PGLOSAS
//javascript for item_cleansing1.asp
//m.kamiyama
//2013.xx.xx

addListener(self,"load",loaded,false);
function loaded(e){
	//���[�h���̏����ł��B
	var eSource = document.getElementById("div-main");
	addListener(eSource,"click",btnDivMainClick,false);
	addListener(self,"unload",delListeners,false);
	pageInit();
}

function delListeners(ev){
//alert("dellistners");
	//�A�����[�h���Ƀ��X�i���폜���܂��B
	var eSource = document.getElementById("div-main");
	delListener(eSource,"click",btnDivMainClick,false);
	delListener(self,"unload",delListeners,false);
	delListener(self,"load",loaded,false);
}

function pageInit(){
	//�y�[�W�����������܂��B
	//div-list:������ꗗ�\�����\���ɂ��܂��B
	showDivlist("1");
}


function showDivlist(p){
	//div-list�Ɋւ��鐧��ł��B
	//p)0:�\���A1:��\���ł��B
	if(p == "0"){
		//�A�C�e�����ꗗ��\�����܂��B
		document.getElementById("div-list").style.display = "block";
	}else{
		document.getElementById("div-list").style.display = "none";
	}
}


function btnDivMainClick(e){
	//DIV-MAIN�̃{�^���̃C�x���g�ł��B
	var sElm = getId(e);
	var btnname = "";
	if(sElm == ""){
		return;
	}
	if(document.getElementById(sElm).disabled){
		return;
	}
	
	if(sElm == "btnul"){
		if(!checkData()){
			return false;
		}
	}
	
	if(sElm == "btndl"){
		getXml();
	}
}

function checkData(){
	if(document.getElementById("upfile").value == ""){
		alert("Please select Upload-File");
		return false;
	}
	if(document.getElementById("upfile").value.toUpperCase().indexOf(".XLS") < 0){
		alert("Please select Excel-File of Item Master Cleansing List.");
		return false;
	}
	if(!confirm("Item Cleansing List data will be replaced by uploaded-data.\n Do you really replace them?")){
		return false;
	}
	filePath = document.getElementById("upfile").value;
	document.getElementById("myform").action = "item_cleansing2.asp?filePath='" + filePath + "'";
	document.getElementById("myform").submit();
	return true;
}



function getXml(){
//alert("getXml");
	alert("The format of Download file is XML Speadsheet. After download, Please open and save as Excel file.");
	location.href = "./item_cleansing_dl.asp";
	return;
}
