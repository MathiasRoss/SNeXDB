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
<input type="hidden" name="flux" value="<?php echo $_POST["flux"]; ?>">
<input type="hidden" name="flux_lerror" value="<?php echo $_POST["flux_lerror"]; ?>">
<input type="hidden" name="flux_herror" value="<?php echo $_POST["flux_herror"]; ?>">
<input type="hidden" name="flux_ref" value="<?php echo $_POST["flux_ref"]; ?>">
<input type="hidden" name="date_observed" value="<?php echo $_POST["date_observed"]; ?>">
<input type="hidden" name="flux_energy_low" value="<?php echo $_POST["flux_energy_low"]; ?>">
<input type="hidden" name="flux_energy_high" value="<?php echo $_POST["flux_energy_high"]; ?>">
<br>
<input type="submit">
<br>

</form>



<?php
$conn -> close();
?>
