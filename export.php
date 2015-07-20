<?php
include 'connect.php';
include 'calculations.php';
include 'search.php';


switch($_GET['exportType']){
    case 'CSV':
        $sep = ',';
        break;
    case 'TSV':
        $sep = "\t";
        break;
}
$fields = array_keys($result[0]);
foreach($fields as $field){
    $string = $string . "\"".$field."\"";
    $string = $string . $sep;
}
$string = $string . "\n";


foreach($result as $value){
    foreach($fields as $field){
        $string = $string . $value[$field] . $sep;
    }
    $string = $string . "\n";

}
header('content-type: text/csv');
header('content-disposition: attachment; filename="SNeXDB.csv"');
header("content-length: " . mb_strlen($string));
header("Connection: close");
print $string;
exit();


$conn = null;
?>
