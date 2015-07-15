<?php



//get menu information from database
try {
    $stmt = $conn->query("SELECT DISTINCT name FROM Novae");
    $names = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT type FROM Novae");
    $types = $stmt-> fetchAll(PDO::FETC_ASSOC);
}
catch(PDOException $e) {
    echo $e->getMessage();
}

?>

<form method = get action ="index.php">

<!-- Object name pulldown -->
<select name="objid">
<option selected ="selected" value = "">Object Name</option>

<?php
foreach($names as $name){
    echo "<option>" . $name['name'] . "</option>";
}
?>
</select>
<input type="text" name = "name" >


<!-- Type search pulldown -->
<select name="typeid">
<option selected = "selected" value ="">

<?php
foreach($types as $type){
    echo "<option>" . $types['type'] . "</option>";
}
?>

</select>



<input type = "submit" value = "Search">



</form>

