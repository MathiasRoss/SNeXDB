<?php
include 'connect.php';

try {
    $stmt = $conn->query("SELECT DISTINCT instrument FROM Observations");
    $instruments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT name FROM Novae");
    $names = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("SELECT obsID, name, dateObserved, dateObservedRef, instrument, uploadSet FROM Observations WHERE obsID=:obsID");
    $stmt->bindValue(':obsID',$_GET['obsID']);
    $stmt->execute();
    $observations = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e->getMessage();
}
$observations=current($observations);//make 1d
?>
<form method='POST' action='editObs.php'>
obsID: <input type='text' name='obsID' readonly='readonly' value='<?php echo htmlspecialchars($observations['obsID']);?>'>
<br>
Name: <select name='name'>
<?php 
foreach($names as $name){
    if ($name['name'] == $observations['name']){
        echo "<option selected = 'selected'>".htmlspecialchars($name['name'])."</option>";
    }
    else{
        echo '<option>'.htmlspecialchars($name['name']).'</option>';
    }
}
?>
</select>
<br>
Instrument:
<select name='instrumentID'>
<?php
foreach($instruments as $instrument){
    if ($instrument['instrument'] == $observations['instrument']){
        echo "<option selected = 'selected'>".htmlspecialchars($instrument['instrument'])."</option>";
    }
    else{
        echo '<option>'.htmlspecialchars($instrument['instrument']).'</option>';
    }
}
?>
<option value='new'>New</option>
</select>
<input type='text' name='type' placeholder='Enter here if new type (also select "new" in pulldown)'>
<br>
Date Observed:<input type='text' name='dateObserved' value='<?php echo htmlspecialchars($observations['dateObserved']);?>'>
<br>
Date Observed Ref:<input type='text' name='dateObservedRef' value='<?php echo htmlspecialchars($observations['dateObservedRef']);?>'>
<br>
Upload Set:<input type='text' name='uploadRef' readonly='readonly' value='<?php echo htmlspecialchars($observations['uploadSet']); ?>'>
<br>
<input type='submit' value = 'Update'>
</form>






