<?php
include 'calculations.php';
include 'tables.php';
include 'header.php';
include 'connect.php';
?>
<form action='updateDatabase.php' method="post">
<input type='hidden' name='setName' value='<?php echo $_POST['setName']; ?>'>
<input type="submit" value="Upload">
</form>




<?php
if (!empty($_POST['setName'])){
    try {
        $set=array(':setName'=>$_POST['setName']);
        $stmt = $conn->prepare("SELECT name, type, dateExploded, dateExplodedRef, distance, distRef, uploadSet FROM NovaeNew WHERE uploadSet = :setName");
        $stmt->execute($set);
        $novae = $stmt-> fetchAll(PDO::FETCH_ASSOC);
        $stmt = $conn->prepare("SELECT name, dateObserved, dateObservedRef, instrument, uploadSet FROM ObservationsNew WHERE uploadSet = :setName");
        $stmt->execute($set);
        $observations=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $conn->prepare("SELECT isUpperBound, obsID, flux, fluxErrL, fluxErrH, fluxEnergyL, fluxEnergyH, fluxRef, model, uploadSet FROM FitsNew WHERE uploadSet = :setName");
        $stmt->execute($set);
        $fits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}
    catch(PDOException $e) {
        echo $e -> getMessage();
    }
}
echo 'Novae in staging area: <br>';
if(!empty($novae)){
    smartTable($novae);
}
echo 'Observations in staging area: <br>';
smartTable($observations);
echo 'Fits and analysis in staging area: <br>';
smartTable($fits);
include 'footer.php';
?>
