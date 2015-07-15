<?php



//get menu information from database
try {
    $stmt = $conn->query("SELECT DISTINCT name FROM Novae");
    $names = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT type FROM Novae");
    $types = $stmt-> fetchAll(PDO::FETCH_ASSOC);
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
<br>

<!-- Type search pulldown -->
<select name="typeid">
<option selected = "selected" value ="">Object Type</option>

<?php
foreach($types as $type){
    echo "<option>" . $type['type'] . "</option>";
}
?>

</select>
<input type="text" name="type">
<br>

<!-- Flux Magnitude Search -->
Flux is greater than
<input type="text" name="fluxMin" value=
<?php
echo $_GET["fluxMin"];
?> 
>
and less than
<input type="text" name="fluxMax" value=
<?php
echo $_GET["fluxMax"];
?>
>
<br>




<!-- Luminosity Magnitude Search -->
Luminosity is greater than
<input type="text" name="lumMin" value=
<?php
echo $_GET["lumMin"];
?> 
>
and less than
<input type="text" name="lumMax" value=
<?php
echo $_GET["lumMax"];
?>
>




<input type = "submit" value = "Search">



</form>

