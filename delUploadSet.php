<?php
include 'connect.php';
include 'header.php';

try {
    $setName = array(':name'=>$_POST['setName']);
    $stmt = $conn->prepare("DELETE FROM Observations WHERE uploadSet=:name");
    $stmt -> execute($setName);
    $stmt = $conn->prepare("DELETE FROM Novae WHERE uploadSet=:name");
    $stmt -> execute($setName);
    $stmt = $conn->prepare("DELETE FROM Fits WHERE uploadSet=:name");
    $stmt -> execute($setName);
    $stmt = $conn->prepare("DELETE FROM Parameters WHERE uploadSet=:name");
    $stmt -> execute($setName);
}
catch (PDOException $e) {
    echo $e -> getMessage();
}


include 'footer.php';

?>

