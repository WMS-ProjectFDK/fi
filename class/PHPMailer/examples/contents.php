<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>FDKI</title>
</head>
<body>
<?php 
if(date('D')=='Fri'){
	$k = date("l, F d, Y", mktime(0,0,0,date("m"),date("d")+3,date("Y")));
	$hari = "Monday";
}else{
	$k = date("l, F d, Y", mktime(0,0,0,date("m"),date("d")+1,date("Y")));
	$hari = "Tomorrow";
}
?>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
  <h4>Dear All</h1>
  <div align="center">

  </div>
  <p>Please find the attached for delivery instruction <?php echo $hari; ?> And please send  before 11.00 wib</p>
  <p><u>Please do not reply this email</u>. If you have any question, please contact :</p>
  <!-- <p>1. Inna Pujiastuti : inna.pujiastuti@fdk.co.jp</p> -->
  <p>1. Yoga Kristianto : yoga.kristianto@fdk.co.jp</p>
  <p>2. Agung Mardiansyah : agung.mardiansyah@fdk.co.jp</p>
  <p>3. Leslie Avanti : leslie.avanti@fdk.co.jp</p>
</div>
</body>
</html>
