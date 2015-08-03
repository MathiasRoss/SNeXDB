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
?>
<form method='POST' action='editNova.php'>
<input type='text' name = 'name' readonly='readonly' value='<?php echo htmlspecialchars($nova[0]['name']);?>'>
<br>
<select name="typeid">
<?php
foreach($types as $type){
    if ($type['type'] == $nova[0]['type']){
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
Date Exploded:<input type='text' name='dateExploded' value='<?php echo htmlspecialchars($nova[0]['dateExploded']); ?>'>
<br>
Date Exploded Ref:<input type='text' name='dateExplodedRef' value='<?php echo htmlspecialchars($nova[0]['dateExplodedRef']); ?>'>
<br>
<br>
Distance:<input type='text' name='distance' value='<?php echo htmlspecialchars($nova[0]['distance']); ?>'>
<br>
Redshift:<input type='text' name='redshift' value='<?php echo htmlspecialchars($nova[0]['redshift']); ?>'>
<br>
RedshiftRef:<input type='text' name='redshiftRef' value='<?php echo htmlspecialchars($nova[0]['redshiftRef']); ?>'>
<br>
Distance Ref:<input type='text' name='distRef' value='<?php echo htmlspecialchars($nova[0]['distRef']); ?>'>
<br>
Upload Set:<input type='text' name='uploadRef' readonly='readonly' value='<?php echo htmlspecialchars($nova[0]['uploadSet']); ?>'>
<br>
<input type='submit' value = 'Update'>
</form>


