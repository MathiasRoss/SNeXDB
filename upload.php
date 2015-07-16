<?php


$uploaddir = '/var/www/uploads/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";

$array = $fields = array();
$i = 0;
if (($handle = fopen($uploadfile,"r")) !== false) {
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        if (empty($fields)){
            echo "beep";
            $fields = $row;
            continue;
         }
         foreach ($row as $key=>$value) {
            $array[$i][$fields[$key]] = $value; 
          }
         $i++;
    }
    fclose($handle);
}


print_r($array);

?>
