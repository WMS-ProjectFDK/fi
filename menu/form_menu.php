<?php include("../koneksi.php"); 
session_start(); //Gunakan variabel session pada halaman ini. Fungsi ini harus diletakkan pada bagian atas halaman.
$ses_id = $_SESSION['id_user'];
$sql_user="SELECT NIK FROM USER WHERE USER_ID='$ses_id'";
$query_user=mysql_query($sql_user);
$row_user=mysql_fetch_array($query_user);

$sql_log="SELECT NAMA_LENGKAP, DEPARTEMENT, JABATAN FROM MAN_POWER WHERE NIK='$row_user[NIK]'";
$query_log=mysql_query($sql_log);
$row_log=mysql_fetch_array($query_log);
$nm_login=$row_log[NAMA_LENGKAP];
$dept_login=$row_log[DEPARTEMENT];
$jab_log=$row_log[JABATAN];
if($jab_log=="1") $jab_login="Operator";
else if($jab_log=="2") $jab_login="Staff";
else if($jab_log=="3") $jab_login="Supervisor";
else if($jab_log=="4") $jab_login="Departement Head";
else if($jab_log=="5") $jab_login="Manager";
else if($jab_log=="6") $jab_login="Direktur";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Script-Type" content="text/javascript">
		<title>Insert New Menu</title>
		<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
 <script language="javascript">
 function confirmLogOut()
 {
	var is_confirmed;
	is_confirmed = window.confirm("End current session?");
	return is_confirmed;
 }
 </script> 

		<script src="../lib/jquery.js" type="text/javascript"></script>
		<script src="../lib/jquery.validate.js" type="text/javascript"></script>
		<script src="../lib/jquery.history_remote.pack.js" type="text/javascript"></script>
        <script src="../lib/jquery.tabs.pack.js" type="text/javascript"></script>
		<!-- for styling the form -->
		<script src="../js/cmxforms.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#commentForm").validate();
			});
		</script>
		<script>
			function showParent(txt1,txt2) {
				sList = window.open("data_parent.php?id="+txt1+"&name="+txt2, "list", "width=400,height=300, scrollbars=1, resizeable=1");
				document.getElementById('sub_url').disabled=false;
				document.getElementById('sub_name').disabled=false;
				document.getElementById('sub_desc').disabled=false;
			}
			function showParent1(txt1,txt2) {
				document.getElementById('sub_id2').disabled=true;
				document.getElementById('sub_name1').disabled=true;
				document.getElementById('choose_sub').disabled=true;
				//document.getElementById('h_sub_id1').value=null;
				document.getElementById('h_sub_name1').value=null;
				document.getElementById('sub_id2').value=null;
				document.getElementById('sub_name1').value=null;
				sList = window.open("data_parent1.php?id1="+txt1+"&name1="+txt2, "list", "width=400,height=300, scrollbars=1, resizeable=1");
				document.getElementById('sub_id2').disabled=false;
				document.getElementById('sub_name1').disabled=false;
				document.getElementById('choose_sub').disabled=false;
				//document.getElementById('child_sub_id').disabled=true;
				document.getElementById('child_sub_name').disabled=true;
				document.getElementById('child_sub_desc').disabled=true;
				document.getElementById('choose_sub').disabled=false;
			}			
			function showSub(txt1,txt2) {							
				var txt3=document.getElementById('parent_id1').value;
				document.getElementById('child_sub_url').disabled=true;
				document.getElementById('child_sub_name').disabled=true;
				document.getElementById('child_sub_desc').disabled=true;
				document.getElementById('child_sub_url').value=null;
				document.getElementById('child_sub_name').value=null;
				document.getElementById('child_sub_desc').value=null;

				sList = window.open("data_sub.php?id="+txt1+"&name="+txt2+"&parent="+txt3, "list", "width=400,height=300, scrollbars=1, resizeable=1");
				
				document.getElementById('child_sub_url').disabled=false;
				document.getElementById('child_sub_name').disabled=false;
				document.getElementById('child_sub_desc').disabled=false;
			}
			 
			function isParent(){
/*				var kode=document.getElementById('par_id').value;
				var nama=document.getElementById('par_name').value;				
			
				if(kode == "" || nama == ""){
					alert("Silahkan lengkapi data!");
				}else{
*/					document.getElementById('submityesno').value="1";
					document.data_menu.submit();
/*				}
*/			}
			function isChild(){
/*				var par_id=document.getElementById('parent_id').value;
				var par_nama=document.getElementById('parent_name').value;
				var sub_id=document.getElementById('sub_id').value;
				var sub_nama=document.getElementById('sub_name').value;
				
				if(par_id=="" || par_nama=="" || sub_id="" || sub_nama=""){
					alert("Silahkan lengkapi data!");
				}else{
*/					document.getElementById('submityesno').value="2";
					document.data_menu.submit();
/*				}
*/			}
			function isSubChild(){
/*				var par_id=document.getElementById('parent_id1').value;
				var par_nama=document.getElementById('parent_name1').value;
				var sub_id=document.getElementById('sub_id1').value;
				var sub_nama=document.getElementById('sub_name1').value;
				var child_id=document.getElementById('child_sub_id').value;
				var child_nama=document.getElementById('child_sub_name').value;
				
				if(par_id=="" || par_nama=="" || sub_id="" || sub_nama="" || child_id="" || child_nama==""){
					alert("Silahkan lengkapi data!");
				}else{
				
*/
var sub_id=document.getElementById('sub_id2').value;
document.getElementById('submityesno').value="3";
					document.data_menu.submit();
/*				}
*/
			}
		</script>
		<script type="text/javascript">
            $(function () {
                $('#container-1').tabs({ fxSlide: true, fxFade: true, fxSpeed: 'normal' });
				
			});
        </script>
		<script type="text/JavaScript">
			function refreshPage(){
				window.location.reload();
			}
		</script>

		<link rel="stylesheet" href="../css/jquery.tabs.css" type="text/css" media="print, projection, screen">

		<style type="text/css" media="screen, projection">
			* { font: 11px/20px Verdana, sans-serif; }
			h4 { font-size: 18px; }
			input { padding: 3px; border: 1px solid #999; }
			input.error, select.error { border: 1px solid red; }
			label.error { color:red; margin-left: 10px; }
			td { padding: 5px; }
            /* Not required for Tabs, just to make this demo look better... */

            body {
                font-size: 16px; /* @ EOMB */
            }
            * html body {
                font-size: 100%; /* @ IE */
            }
           /* body * {
                font-size: 87.5%;
                font-family: "Trebuchet MS", Trebuchet, Verdana, Helvetica, Arial, sans-serif;
            }*/
            body * * {
                font-size: 100%;
            }
            h1 {
                margin: 1em 0 1.5em;
                font-size: 18px;
            }
            h2 {
                margin: 2em 0 1.5em;
                font-size: 16px;
            }
            p {
                margin: 0;
            }
            pre, pre+p, p+p {
                margin: 1em 0 0;
            }
            code {
                font-family: "Courier New", Courier, monospace;
            }
        </style>
<style type="text/css">
	#commentForm { width: 100%; }
	#commentForm label { width: 240px; }
	#commentForm label.error, #commentForm button.error, #commentForm input.submit { margin-left: 5px; }
.style1 {color: #0000FF}
.style3 {
	font-size: 13px;
	color: #000000;
	font-weight: bold;
}
.style4 {
	font-size: 11px;
	color: #CC0000;
}
* { font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px; }
#choose {
	background:url(../image/download.gif) no-repeat;
	margin-top:0px;
  }
#choose_sub {
	background:url(../image/download.gif) no-repeat;
	margin-top:0px;
  }
  
</style>
    </head>
    <body>
	<?php if($ses_id!=""){ ?>
<table width="100%">
		<tr>
			<td align="left" width="96%" valign="middle"><marquee><span class="style4"><?php echo "Welcome ".$nm_login.", you are login as ".$jab_login." ".$dept_login;?></span></marquee></td>
			<td align="right" width="4%" nowrap="nowrap" valign="middle"><a href="../index.php?act=logout" onClick="return confirmLogOut()" target="_top"><img src="../image/logout.gif" alt="End current session" width="22" height="20" border="0" /></a>&nbsp;<a href="../dashboard.php"><img src="../image/house.png" alt="Back to Home" width="22" height="20" border="0" /></a></td>
		</tr>
	</table>
<fieldset><legend><span class="style3">Insert Menu</span></legend>
	<form class="cmxform" id="commentForm" name="data_menu" method="post" action="proses.php">
        <div id="container-1">
          <ul> 
		  		<li><a href="#fragment-1"><span>Parent Module</span></a></li>
                <li><a href="#fragment-2"><span>Sub Module</span></a></li>
                <li><a href="#fragment-3"><span>Child Sub Module</span></a></li>
          </ul>
		  	<div id="fragment-1">
				<table>
					<tr>
						<td width="150">Parent Module Name</td>
						<td><input name="par_name" id="par_name" class="required" title="Parent module name required" size="25" type="text" />	</td>
					</tr>
					<tr>
						<td width="150">Parent Module Desc</td>
						<td><input name="par_desc" id="par_desc" class="required" title="Parent module name required" size="25" type="text" />	</td>
					</tr>
					<tr>
						<td width="150">URL</td>
						<td><input name="par_url" id="par_url" size="25" type="text" />	</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="button" value="Save" onClick="isParent()" />
							<input type="reset" value="Cancel"  onClick="refreshPage()" />
							
						</td>
					</tr>
				</table>
		  	</div>
            <div id="fragment-2">
				<div>
						<table>
							<tr>
								<td width="150">Parent Module Id</td>
								<td>
									<input name="h_parent_id" type="text" id="h_parent_id" size="21"  disabled/>
									<input name="parent_id" id="parent_id" type="hidden" />
									<input name="choose" type="button" id="choose" value="  " onClick="showParent('parent_id','parent_name')">									
								</td>
							</tr>
							<tr>
								<td width="150">Parent Module Name</td>
								<td>
									<input name="h_parent_name" id="h_parent_name" size="25" type="text" disabled/>
									<input name="parent_name" id="parent_name" type="hidden" />
								</td>
							</tr>
							<tr>
								<td width="150">Sub Module Name</td>
								<td>
									<input name="sub_name" class="required" title="Sub module name required" size="25" type="text" id="sub_name" disabled="disabled" />
								</td>
							</tr>
							<tr>
								<td width="150">Sub Module Desc</td>
								<td><input name="sub_desc" size="25" type="text" id="sub_desc" disabled="disabled" /></td>
							</tr>
							<tr>
								<td width="150">URL</td>
								<td><input name="sub_url" size="25" type="text" id="sub_url" disabled="disabled" /></td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="button" value="Save" onClick="isChild()"/>
									<input type="reset" value="Cancel"  onClick="refreshPage()" />
					<!--				<input name="submityesno" type="hidden" id="submityesno">
					-->			</td>
							</tr>
						</table>
						
				</div>

					
            </div>
          <div id="fragment-3" >
		  	<div>
						<table>
							<tr>
								<td width="150">Parent Module Id</td>
								<td>
									<input name="h_parent_id1" type="text" id="h_parent_id1" size="21"  disabled/>
									<input name="parent_id1" id="parent_id1" size="25" type="hidden" />
									<input name="choose" type="button" id="choose" value="  " onClick="showParent1('parent_id1','parent_name1')">									
								</td>
							</tr>
							<tr>
								<td width="150">Parent Module Name</td>
								<td>
									<input name="h_parent_name1" id="h_parent_name1" size="25" type="text" disabled/>
									<input name="parent_name1" id="parent_name1" type="hidden" />
								</td>
							</tr>
							<tr>
								<td width="150">Sub Module Id</td>
								<td>
									<input name="h_sub_id2" type="text" id="h_sub_id2" size="21"  disabled/>
									<input name="sub_id2" id="sub_id2" type="hidden" />
									<input name="choose_sub" type="button" id="choose_sub" value="  " onClick="showSub('sub_id2','sub_name1')" disabled="disabled" >									
								</td>
							</tr>
							<tr>
								<td width="150">Sub Module Name</td>
								<td>
									<input name="h_sub_name1" id="h_sub_name1" size="25" type="text" disabled="disabled"/>
									<input name="sub_name1" id="sub_name1" type="hidden" />
								</td>
							</tr>
							<tr>
								<td width="150">Sub Module Name</td>
								<td>
									<input name="child_sub_name" class="required" title="Sub module name required" size="25" type="text" id="child_sub_name" disabled="disabled" />
								</td>
							</tr>
							<tr>
								<td width="150">Sub Module Desc</td>
								<td><input name="child_sub_desc" size="25" type="text" id="child_sub_desc" disabled="disabled" />
								</td>
							</tr>
							<tr>
								<td width="150">URL</td>
								<td><input name="child_sub_url" size="25" type="text" id="child_sub_url" disabled="disabled" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="button" value="Save" onClick="isSubChild()"/>
									<input type="reset" value="Cancel"  onClick="refreshPage()" />
					<!--				<input name="submityesno" type="hidden" id="submityesno">
					-->			</td>
							</tr>
						</table>
						
				</div>
		  </div>
		  
        </div>
		<input name="submityesno" type="hidden" id="submityesno">
	</form>
</fieldset>	
	<?php }?>
   </body>
</html>