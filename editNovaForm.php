<?php
include 'debugFunc.php';
include 'connect.php';

try {
    $stmt = $conn->query("SELECT DISTINCT type FROM Novae");
    $types = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("SELECT name, type, redshift, dateExploded, dateExplodedRef, distance, distRef, uploadSet, redshiftRef FROM Novae WHERE name=:name");
    $stmt->bindValue(':name',$_GET['name']);
    $stmt->execute();
    $nova = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e->getMessage();
}
$nova=current($nova);//make it a 1d array, for ease of reading

?>
<form method='POST' action='editNova.php'>
<input type='text' name = 'name' readonly='readonly' value='<?php echo htmlspecialchars($nova['name']);?>'>
<br>
<select name="typeid">
<?php
foreach($types as $type){
    if ($type['type'] == $nova['type']){
        echo "<option selected = 'selected'>".htmlspecialchars($type['type'])."</option>";
    }
    else{
        echo '<option>'.htmlspecialchars($type['type']).'</option>';
    }
}
?>
<option value='new'>New</option>
</select>
<input type='text' name='type' placeholder='Enter here if new type'>
<br>
<br>
Date Exploded:<input type='text' name='dateExploded' value='<?php echo htmlspecialchars($nova['dateExploded']); ?>'>
<br>
Date Exploded Ref:<input type='text' name='dateExplodedRef' value='<?php echo htmlspecialchars($nova['dateExplodedRef']); ?>'>
<br>
<br>
Distance:<input type='text' name='distance' value='<?php echo htmlspecialchars($nova['distance']); ?>'>
<br>
Redshift:<input type='text' name='redshift' value='<?php echo htmlspecialchars($nova['redshift']); ?>'>
<br>
RedshiftRef:<input type='text' name='redshiftRef' value='<?php echo htmlspecialchars($nova['redshiftRef']); ?>'>
<br>
Distance Ref:<input type='text' name='distRef' value='<?php echo htmlspecialchars($nova['distRef']); ?>'>
<br>
Upload Set:<input type='text' name='uploadRef' readonly='readonly' value='<?php echo htmlspecialchars($nova['uploadSet']); ?>'>
<br>
<input type='submit' value = 'Update'>
</form>


