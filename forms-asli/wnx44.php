<html>
<head>
    <script type="text/javascript">
      function onChangeTest(changeVal) {
        alert("Value is " + changeVal.value);
      }

      function filterData(){
        var src = document.getElementById('src').value;
        alert(src);
        //var search = src.toUpperCase();
        //document.getElementById('src').value = search;
      }
    </script>
  </head>
  <body>
    <form>
      <div>
          <input type="text" id="test" value ="ABS" onchange="onChangeTest(this)">  
          <input style="width:150px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" name="src" id="src" type="text" />
          <a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
      </div>
    </form>
  </body>
</html>