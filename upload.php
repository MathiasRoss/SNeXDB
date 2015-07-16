<?php
include 'calculations.php';

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


include 'connect.php';

try {
    $stmt = $conn-> query("SELECT * From Novae");
    $oldNovae = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT type FROM Novae");
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e){
    echo $e->getMessage();
}

//make 1d arrays from the 2d ones for ease of search:
foreach($oldNovae as $key=>$row){
    $oldNovae[$row['name']]=$row;
    unset($oldNovae[$key]);

    if (!in_array($row['name'], $names)){
        $names[] = $row['name'];
    }
    if (!in_array($row['type'], $types)){
        $types = $row['type'];
    }
}

//prepare arrays for the new values
$novae = array();
$newNames = array();
$newTypes = array();

//Loops through each row in the file, creating an associative array $result with all the new information
$result = $fields = array();
$i = 0;
if (($handle = fopen($uploadfile,"r")) !== false) {
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        if (empty($fields)){ //set $fields array to the first row, to be used as keys in $result
            $fields = $row;
            continue;
        }
//set the values of $result, using keys from $fields
        foreach ($row as $key=>$value) {
            $result[$i][$fields[$key]] = $value; 
        }

//check for new novae included in the file, fill $novae with the new nova information
        if ((!in_array($result[$i]['name'], $names)) && !in_array($result[$i]['name'], $newNames)) {
            $newNames[] = $result[$i]['name'];
            $novae[$result[$i]['name']] = $result[$i];//has some excess info
        }
//checks for new types
        if (!in_array($result[$i]['type'], $types)) {
            $newTypes[] = $result[$i]['type'];
        }



        $i++;
    }
    fclose($handle);
}

include 'novaeTable.php';

$novae = array_merge($novae,$oldNovae);

//fill in missing nova information
foreach ($result as $key=>$row){
    if (empty($result[$key]['type'])){
        $result[$key]['type'] = $novae[$row['name']]['type'];
    }
    if (empty($result[$key]['dateExploded'])){
        $result[$key]['dateExploded'] = $novae[$row['name']]['dateExploded'];
    }
    if (empty($result[$key]['dateExplodedRef'])){
        $result[$key]['dateExplodedRef'] = $novae[$row['name']]['dateExplodedRef'];
    }

    if (empty($result[$key]['distance'])){
        $result[$key]['distance'] = $novae[$row['name']]['distance'];
    }
    if (empty($result[$key]['distRef'])){
        $result[$key]['distRef'] = $novae[$row['name']]['distRef'];
    }
}




include 'phpTable.php';



?>
