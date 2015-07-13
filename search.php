<?php
include 'config.php';
include 'calculations.php';


//connect
try {
    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
}
catch(PDOException $e) {
    echo $e->getMessage();
}


try {
    $result = $conn->query('SELECT * FROM Novae');

    while ($row = $result->fetch(PDO::FETCH_ASSOC)){
        echo $row['name'];
    }
} 
catch(PDOException $e) {
    echo $e -> getMessage();
}


//begin the query
$query = "SELECT * FROM Fits LEFT JOIN Observations ON Fits.obsID = Observations.obsID LEFT JOIN Novae on Observations.name= Novae.name WHERE ";

//object name search
if ($_GET["objID"] != ""){
    $query = $query . "name LIKE CONCAT('%',?,'%')";
}



//close connection
$conn = null;

?>
