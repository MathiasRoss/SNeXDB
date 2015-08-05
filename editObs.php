<?php
include 'header.php';
include 'connect.php';
$params = array();
$params[':obsID']=$_POST['obsID'];
$params[':name']=$_POST['name'];
$params[':dateObserved']=$_POST['dateObserved'];
$params[':dateObservedRef']=$_POST['dateObservedRef'];
if ($_POST['instrumentID']!='new'){
    $params[':instrument'] = $_POST['instrumentID'];
}
else {
    $params[':instrument'] = $_POST['instrument'];
}


try {
    $stmt = $conn->prepare("UPDATE Observations SET name=:name, dateObserved=:dateObserved, dateObservedRef=:dateObservedRef, instrument=:instrument WHERE obsID=:obsID");
    $stmt -> execute($params);
}
catch (PDOException $e){
    echo $e->getMessage();
}



include 'footer.php';
?>
