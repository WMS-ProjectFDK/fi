<?php
$fh = fopen('Transfer.csv', 'a');
fwrite($fh, '<h1>Hello world!</h1>');
fclose($fh);
unlink('C:\Users\Public\Documents\Transfer.csv');
/*$FilePath = "Transfer.csv";
chdir($FilePath); // Comment this out if you are on the same folder
chown($FileName,465); //Insert an Invalid UserId to set to Nobody Owner; for instance 465
$do = unlink($FileName);

if($do=="1"){ 
    echo "The file was deleted successfully."; 
} else { echo "There was an error trying to delete the file."; }*/ 

?>