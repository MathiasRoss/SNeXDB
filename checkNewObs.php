<?php
include 'calculations.php';
include 'config.php';


//connect
$conn = new mysqli($servername, $username, $password, $dbname) or die('Could not connect to database: '. $conn -> connect_error);

//check for blank flux error
if($_POST["flux_herror"] == ""){
    $_POST["flux_herror"] = $_POST["flux_lerror"];
}


echo "You have selected the following: <br>";
echo "Object name: " . $_POST["name"] . "<br>";
echo "Instrument: " . $_POST["instrument"] . "<br>";
echo "Model: " . $_POST["model"] . "<br>";
echo "Flux: ". $_POST["flux"] . "<br>";
echo "flux error (low,high): (" . $_POST["fluxErrL"] . ",". $_POST["fluxErrH"] .  ") <br>" ;
echo "Energy range used in flux calculation: ". $_POST["fluxEnergyL"] ." - " . $_POST["fluxEnergyH"] . "<br>";
echo "Date Observed (MJD) :" . $_POST["dateObserved"] . "<br>";
echo "Date Observed (greg):" . jdtogregorian(ceil(mjdtojd($_POST["dateObserved"]))) . "<br>";
echo "Reference for flux :" . refLink($_POST["fluxRef"]) . "<br>";



//get variables from nova database to be used in calculations:
$stmt = $conn->prepare("SELECT distance, dateExploded FROM Novae where name = ? LIMIT 1"); //limit 1 to end query after entry found
$stmt->bind_param('s',$_POST["name"]);
$stmt->execute();
$stmt->bind_result($distance, $dateExploded);
$stmt->fetch();



echo "<br> calculated quantities from this entry: <br>";
echo "Age: " . get_age($_POST["dateObserved"], $dateExploded) . "<br>";
echo "Luminosity: " . get_lum($distance, $_POST["flux"]). "<br>";

echo "<br>If these values are correct, press submit.  Else, go to previous page.";

?>


<!-- Submission form, hidden fields to pass variables -->
<form method = "post" action = "newObs.php">

<input type="hidden" name="name" value="<?php echo $_POST["name"]; ?>"> 
<input type="hidden" name="instrument" value="<?php echo $_POST["instrument"]; ?>"> 
<input type="hidden" name="flux" value="<?php echo $_POST["flux"]; ?>">
<input type="hidden" name="fluxErrL" value="<?php echo $_POST["fluxErrL"]; ?>">
<input type="hidden" name="fluxErrH" value="<?php echo $_POST["fluxErrH"]; ?>">
<input type="hidden" name="fluxRef" value="<?php echo $_POST["fluxRef"]; ?>">
<input type="hidden" name="dateObserved" value="<?php echo $_POST["dateObserved"]; ?>">
<input type="hidden" name="dateObservedRef" value="<?php echo $_POST["dateObservedRef"]; ?>">
<input type="hidden" name="fluxEnergyL" value="<?php echo $_POST["fluxEnergyL"]; ?>">
<input type="hidden" name="fluxEnergyH" value="<?php echo $_POST["fluxEnergyH"]; ?>">
<input type="hidden" name="model" value="<?php echo $_POST["model"]; ?>">
<br>
<input type="submit">
<br>

</form>



<?php
$conn -> close();
?>
