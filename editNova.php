<?php
include 'header.php';
include 'connect.php';
$params = array();
$params[':name']=$_POST['name'];
$params[':redshift']=$_POST['redshift'];
$params[':redshiftRef']=$_POST['redshiftRef'];
$params[':dateExploded']=$_POST['dateExploded'];
$params[':dateExplodedRef']=$_POST['dateExplodedRef'];
$params[':distance']=$_POST['distance'];
$params[':distRef']=$_POST['distRef'];

if ($_POST['typeid']!='new'){
    $params[':type']=$_POST['typeid'];
}
else {
    $params[':type']=$_POST['type'];
}

try {
    $stmt = $conn->prepare("UPDATE Novae SET type=:type, dateExploded=:dateExploded, dateExplodedRef=:dateExplodedRef, redshift=:redshift, redshiftRef=:redshiftRef, distance=:distance, distRef=:distRef WHERE name=:name");
    $stmt -> execute($params);
}
catch (PDOException $e){
    echo $e->getMessage();
}



include 'footer.php';
?>
