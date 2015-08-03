<?php
include 'header.php';
include 'connect.php';

try {
    $stmt = $conn->query("SELECT DISTINCT uploadSet FROM Novae");
    $setNames = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<form action='delUploadSet.php' method='POST'>
<select name='setName'>
<option selected = 'selected' value = ''>Upload Set</option>
<?php
foreach($setNames as $name){
    echo '<option>' . htmlspecialchars($name['uploadSet']) . '</option>';
}
?>
</select>
<input type='submit' value="Delete">
</form>




<?php
include 'footer.php';

?>
