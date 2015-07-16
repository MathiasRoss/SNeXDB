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
    $stmt = $conn-> query("SELECT DISTINCT name From Novae");
    $names = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT type FROM Novae");
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e){
    echo $e->getMessage();
}

//make 1d arrays from the 2d ones for ease of search:
foreach($names as $key=>$name){
    $names[] = $name['name'];
    unset($names[$key]);
}

$result = $fields = array();
$i = 0;
if (($handle = fopen($uploadfile,"r")) !== false) {
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        if (empty($fields)){
            $fields = $row;
            continue;
        }
        foreach ($row as $key=>$value) {
            $result[$i][$fields[$key]] = $value; 
        }
        if (!in_array($result[$i]['name'], $names)) {
            echo 'Detected New Nova: '. $result[$i]['name'];
            echo "<br>";
        } 
        $i++;
    }
    fclose($handle);
}






include 'phpTable.php';



?>
