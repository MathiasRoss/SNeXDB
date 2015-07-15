<?php
//Declare array of inputs for query
$params = array();


//write query based on search
$query = "SELECT * FROM Fits LEFT JOIN Observations ON Fits.obsID = Observations.obsID LEFT JOIN Novae on Observations.name= Novae.name WHERE ";

//object name search
if ($_GET["objid"] != ""){
    $query = $query . "Observations.name = :name";
    $params[':name'] = $_GET["objid"];
}
else {//note: without elif could cause unnecessary performance drops; here for "where blank search" bug
    $query = $query . "Observations.name LIKE CONCAT('%',:name,'%')";
    $params[':name'] = $_GET["name"];
}

//object type search



$jsonTable = array();

//run the query and create JSON table
try {
    $stmt = $conn -> prepare($query);
    $stmt -> execute($params);
    $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $key => $row){
        $result[$key]['age']=getAge($row['dateObserved'],$row['dateExploded']);
        $result[$key]['lum'] = getLum($row['distance'],$row['flux']);
        $jsonTable[] = $row;
    }
} 
catch(PDOException $e) {
    echo $e -> getMessage();
}

$jsonTable = json_encode($jsonTable);


?>
