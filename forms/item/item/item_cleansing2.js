//PGLOSAS
//javascript for item_cleansing2.asp
//m.kamiyama
//2013.02.xx

addListener(self,"load",loaded,false);
function loaded(e){
	//ロード時の処理です。
	var eSource = document.getElementById("div-main");
	addListener(eSource,"click",btnDivMainClick,false);
	addListener(self,"unload",delListeners,false);
	pageInit();
}

function delListeners(ev){
//alert("dellistners");
	//アンロード時にリスナを削除します。
	var eSource = document.getElementById("div-main");
	delListener(eSource,"click",btnDivMainClick,false);
	delListener(self,"unload",delListeners,false);
	delListener(self,"load",loaded,false);
}

function pageInit(){
	//ページを初期化します。
	//div-list:検索後一覧表示を非表示にします。
	if(document.getElementById("result").value == "OK"){
		//upload and DB check OK
		document.getElementById("orderby").value = "ITEM NO";
		document.getElementById("btnview").click();
		showDivlist("0");
	}
}


function showDivlist(p){
	//div-listに関する制御です。
	//p)0:表示、1:非表示です。
	if(p == "0"){
		//アイテムを一覧を表示します。
		document.getElementById("div-list").style.display = "block";
	}else{
		document.getElementById("div-list").style.display = "none";
	}
}


function btnDivMainClick(e){
	//DIV-MAINのボタンのイベントです。
	var sElm = getId(e);
	var btnname = "";
	if(sElm == ""){
		return;
	}
	if(document.getElementById(sElm).disabled){
		return;
	}
	
	if(sElm == "btnview"){
		if(document.getElementById("rdNg").checked){
			document.getElementById("btnremove").disabled = true;
		}else{
			document.getElementById("btnremove").disabled = false;
		}
		getList();
		if(document.getElementById("removable_cnt").value == "0"){
			document.getElementById("btnremove").disabled = true;
		}
	}
	
	if(sElm == "btnremove"){
		if(confirm("Items(back ground color is white) will be removed from Item Master.\nDo you realy remove them?")){
			document.getElementById("frm1").submit();
		}
	}
}

function getList(){
//alert("getlist");
	//order_status_listを呼び出してリストを取得します。
	var rStr = "";
	var httpObj = getHttpObject();
	var type = "";
	//ラジオボタンを確認します。
	if(document.getElementById("rdAll").checked){
		type = "ALL";
	}else if(document.getElementById("rdOk").checked){
		type = "OK";
	}else{
		type = "NG";
	}
	httpObj.open("GET","./item_cleansing2_list.asp?type=" + escape(type)
					+"&orderby=" + escape(document.getElementById("orderby").value),false);
	httpObj.setRequestHeader("If-Modified-Since", "Thu, 01 Jun 1970 00:00:00 GMT"); /*GET だとキャッシュを読んでしまう。*/
	httpObj.send(null);
//alert(httpObj.responseText);
//		workWin = window.open("","loading","left=400,top=300,width=800,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes");
//		workWin.document.write(httpObj.responseText);
	if(!systemErrCheck(httpObj.responseText)){
		alert("SYSTEM ERROR. PLEASE CONTACT TO SYSTEM DEPT");
		workWin = window.open("","loading","left=400,top=300,width=800,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes");
		workWin.document.write(httpObj.responseText);
		return false;
	}
	var rStr = httpObj.responseText.split("<S>");
	setInnerHTC("div-list",rStr[0],"H");
	document.getElementById("removable_cnt").value = rStr[1];
}

function sortList(orderby){
//alert(orderby);
	document.getElementById("orderby").value = orderby;
	getList();
}
