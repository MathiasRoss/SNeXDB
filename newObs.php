<?php
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname) or die('could not connect to database: '. $conn -> connect_error);



// Prepare statement (new observation)
$stmt = $conn->prepare("INSERT INTO Observations(name, dateObserved, dateObservedRef, instrument) VALUES (?,?,?,?)");


$stmt->bind_param('sdss',$_POST["name"], $_POST["dateObserved"],$_POST["dateObservedRef"],$_POST["instrument"]);


if ($stmt->execute()){
    echo "Observation Table Appended Successfully \r\n";
}
else {
    die("Error Appending Observation " . $conn->error);
}


$lastID = $conn-> insert_id;


//Prepare statement (new flux measurement)
$stmt = $conn->prepare("INSERT INTO Fits(obsID, flux, fluxErrL, fluxErrH, fluxEnergyL, fluxEnergyH, fluxRef, model) VALUES (?,?,?,?,?,?,?,?)");


$stmt->bind_param('idddddss',$lastID ,$_POST["flux"], $_POST["fluxErrL"],$_POST["fluxErrH"],$_POST["fluxEnergyL"],$_POST["fluxEnergyH"],$_POST["fluxRef"],$_POST["model"]);


if ($stmt->execute()){
    echo "Fits Table Appended Successfully \r\n";
}
else {
    echo "Error Appending Fit: " . $conn->error;
    die();
}







$conn -> close();
?> 
