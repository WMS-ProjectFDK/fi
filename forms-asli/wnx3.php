
<!DOCTYPE html>
<html>
<head>
</head><!--from   w  w w .  ja v  a2 s. c  om-->
<body>
<script src="http://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js"></script>
<script>
    var someDate = new Date();
    var numberOfDaysToAdd = 1;
    someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
    var dd = someDate.getDate();
    var mm = someDate.getMonth() + 1;
    var y = someDate.getFullYear();
    var someFormattedDate = dd + '/'+ mm + '/'+ y;

    document.writeln(Date.today().addDays(+10).toString());
    document.writeln(someFormattedDate);
</script>
</body>
</html>
