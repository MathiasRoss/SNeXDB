<?php
$string = " \"Object Name\", \"Type\", \"Age\", \"Flux\",\"Luminosity\" \n";
foreach($result as $value){
    $string = $string . $value["name"] . ",". $value["type"] . "," . $value['age'] . "," . $value["flux"] . ",". $value["lum"] . "\n";  
}
header('content-type: text/csv');
header('content-disposition: attachment; filename="snedb.csv"');
header("content-length: " . mb_strlen($string));
header("Connection: close");
print $string;
exit();
?>
