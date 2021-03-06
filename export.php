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
switch($_GET['exportFields']){
    case 'custom':
        $fields = explode(',',$_GET['columns']);
        break;
    case 'all':
        $fields = array_keys($result[0]);
        break;
    case 'lightcurve':
        $fields = array('name','age','lum');
        break;
    case 'lightcurve':
        $fields = array('name','age','flux');
        break;
}

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
header('content-type: text/'.$_GET['exportType']);
header('content-disposition: attachment; filename="SNeXDB.'.$_GET['exportType']."\"");
header("content-length: " . mb_strlen($string));
header("Connection: close");
print $string;
exit();


$conn = null;
?>
