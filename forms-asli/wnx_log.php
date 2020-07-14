<?php
include "../detect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="navbar navbar-default navbar-fixed-top">
 <div class="container">
  <div class="navbar-header">
   <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
    <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
   </button>
   <a class="navbar-brand" href="index.html">
   Pusat Ilmu Secara Detil</a>
  </div>
  <div class="navbar-collapse collapse">
   <ul class="nav navbar-nav navbar-left">
    <li class="clr1 active"><a href="index.html">Home</a></li>
    <li class="clr2"><a href="">Programming</a></li>
    <li class="clr3"><a href="">English</a></li>
   </ul>
  </div>
 </div>
</div>
</br></br></br></br>
<div class ="container">
 <div class="row">
   <div class="col-md-12">
   <h4 >Detecting IP, OS, Browser </h4>
   </div>
 </div>
</div>
<div class="container">
 <div class="row">
  <div class="col-md-3">
   <table class="table">
    
    <tbody>
      <tr class="success">
     <td>Your IP Address</td>
     <td><?php echo $ip_user; ?></td>
      </tr>
      <tr class="danger">
     <td>Browser</td>
     <td><?php echo "<img src= 'img/browser/$user_browser.png'>";echo " ".$user_browser; ?></td>
      </tr>
      <tr class="info">
     <td>Operating System</td>
     <td><?php echo "<img src= 'img/os/$user_os.png'>"; echo " ".$user_os; ?></td>
     
      </tr>
    </tbody>
   </table>
  </div>
 </div>
</div>
<div class="navbar navbar-default navbar-fixed-bottom">
   <div class="container">
      <p class="navbar-text pull-left">Detailed Technology Center, Powered by <a href="http://ilmu-detil.blogspot.co.id/">Pusat Ilmu Secara Detil</a></p>
   </div>
</div>  
</body>
</html>