<?php



//get menu information for database
try {
    $stmt = $conn->query("SELECT DISTINCT name FROM Observations");
    $names = $stmt-> fetchAll(PDO::FETCH_ASSOC);
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
<input type = "submit" value = "Search">

</form>

