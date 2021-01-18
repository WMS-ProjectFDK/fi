<?php
// output headers so that the file is downloaded rather than displayed
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="demo.csv"');
 
// do not cache the file
header('Pragma: no-cache');
header('Expires: 0');
 
// create a file pointer connected to the output stream
$file = fopen('php://output', 'w');
 
// send the column headers
fputcsv($file, array('Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5'));
 
// Sample data. This can be fetched from mysql too
$data = array(
array('Data 11', 'Data 12', 'Data 13', 'Data 14', 'Data 15'),
array('Data 21', 'Data 22', 'Data 23', 'Data 24', 'Data 25'),
array('Data 31', 'Data 32', 'Data 33', 'Data 34', 'Data 35'),
array('Data 41', 'Data 42', 'Data 43', 'Data 44', 'Data 45'),
array('Data 51', 'Data 52', 'Data 53', 'Data 54', 'Data 55')
);
 
// output each row of the data
foreach ($data as $row)
{
fputcsv($file, $row);
}
 
exit();
?>