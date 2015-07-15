<?php
include 'config.php';

//connect
try {
    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
}
catch(PDOException $e) {
    echo $e->getMessage();
}



?>
