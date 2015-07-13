<?php
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname) or die('could not connect to database: '. $conn -> connect_error);



// Prepare statement (new row)
$stmt = $conn->prepare("INSERT INTO Novae(name, type, dateExploded, dateExplodedRef, distance, distRef) VALUES (?,?,?,?,?,?)");


$stmt->bind_param('ssdsds',$_POST["name"], $_POST["type"],$_POST["dateExploded"],$_POST["dateExplodedRef"],$_POST["distance"], $_POST["distRef"]);


if ($stmt->execute()){
    echo "Table Appended Successfully \r\n";
}
else {
    die("Error Appending " . $conn->error);
}









$conn -> close();
?> 
