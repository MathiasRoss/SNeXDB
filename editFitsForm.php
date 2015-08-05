<?php
include 'connect.php';

try {
    $stmt = $conn->query("SELECT DISTINCT model FROM Fits");
    $models = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn -> prepare("SELECT fitsID, uploadSet, obsID, flux, fluxErrL, fluxErrH, fluxEnergyL, fluxEnergyH, fluxRef, model, uploadSet FROM Fits WHERE fitsID=:fitsID");
    $stmt->bindValue(':fitsID', $_GET['fitsID']);
    $stmt->execute();
    $fit = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("SELECT parameter, value FROM Parameters WHERE fitsID=:fitsID");
    $stmt->bindValue(':fitsID', $_GET['fitsID']);
    $stmt->execute();
    $parameters=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("SELECT DISTINCT parameter FROM Parameters WHERE fitsID=:fitsID");
    $stmt->bindValue(':fitsID', $_GET['fitsID']);
    $stmt->execute();
    $paramNames=$stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e->getMessage();
}
$fit=current($fit);//make 1d array
?>

<form method='POST' action='editFit.php'>
<input type='text' name='fitsID' readonly='readonly' value='<?php echo htmlspecialchars($fit['fitsID']);?>'>
<br>
<select name='model'>
<?php
foreach($models as $model){
    if ($model['model'] == $fit['model']){
        echo "<option selected = 'selected'>".htmlspecialchars($fit['model'])."</option>";
    }
    else{
        echo '<option>'.htmlspecialchars($fit['model']).'</option>';
    }
}
?>
<option value='new'>New</option>
</select>
<input type='text' name='newmodel' placeholder='put new model here, if not in list'>
<br>
Flux:<input type='text' name='flux' value='<?php echo htmlspecialchars($fit['flux']); ?>'>
<br>
Flux Low Error:<input type='text' name='fluxErrL' value='<?php echo htmlspecialchars($fit['fluxErrL']); ?>'>
<br>
Flux High Error:<input type='text' name='fluxErrH' value='<?php echo htmlspecialchars($fit['fluxErrH']); ?>'>
<br>
Flux Energy Range (Low):<input type='text' name='fluxEnergyL' value='<?php echo htmlspecialchars($fit['fluxEnergyL']); ?>'>
(High):<input type='text' name='fluxEnergyH' value='<?php echo htmlspecialchars($fit['fluxEnergyH']); ?>'>
<br>
Reference:<input type='text' name='fluxRef' value='<?php echo htmlspecialchars($fit['fluxRef']); ?>'>
<br>
Upload Set:<input type='text' name='uploadSet' readonly='readonly' value='<?php echo htmlspecialchars($fit['uploadSet']); ?>'>
<br>
<hr>
<?php
foreach($parameters as $param){
?>
<select name='<?php echo htmlspecialchars($param['parameter']);?>'>
<?php
foreach($paramNames as $name){
    if ($param['parameter'] == $name['parameter']){
        echo "<option selected='selected'>".htmlspecialchars($name['parameter']).'</option>';
    }
    else {
        echo "<option>".htmlspecialchars($name['parameter']).'</option>';
    }
}
?>
<option value='new'>New</option>
</select>
<input type='text' name='<?php echo htmlspecialchars($param['parameter']).'Value';?>' value='<?php echo htmlspecialchars($param['value']); ?>'>
<br>
<?php
}
?>
</form>
