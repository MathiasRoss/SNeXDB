<?php

/* display the normal tiered table
 *takes in a nova array, and an observations array
 *
 *
 *
 */

function displayTable($novae,$observations){
?>
<table id = "novaTable"  class="display" >
<thead>
<tr>
<th>Name</th>
<th>Type</th>
<th>Date Exploded (MJD)</th>
<th>Distance (Mpc)</th>
<th>Redshift</th>
<th>Distance Reference</th>
<th>Date Reference</th>
<th>Redshift Reference</th>
</tr>
</thead>
<?php
$isOdd = false;
foreach ($novae as $name=>$row) {
    if (!empty($_GET['MJD'])){
        $novae[$name]['dateExploded'] = jdtojulian(mjdtojd($row['dateExploded']));
    }

    if ($isOdd){
       echo "<tr class='odd'>";
        $isOdd=false;
    }
    else{
       echo "<tr class='even'>";
        $isOdd=true;
    }
?>
<td>
<a href="javascript:toggleDiv('<?php echo $name; ?>')" style="color:black; text-decoration:none;">
<span  id='<?php echo $name.'Button'; ?>'>+</span></a>
<?php echo $name.' ('.$row['count'].')'; ?></td>
<td> <?php echo $row['type']; ?> </td>
<td> <?php 

    if (!empty($_GET['MJD'])){
        echo jdtojulian(mjdtojd($row['dateExploded']));
    } else {
echo removeZeros($novae[$name]['dateExploded'],0);}?></td>
<td> <?php echo removeZeros($row['distance'],2); ?></td>
<td> <?php echo removeZeros($row['redshift'],getPrecision($row['redshift'])); ?></td>
<td> <?php echo refLink($row['distRef']) ?></td>
<td> <?php echo refLink($row['dateExplodedRef']) ?></td>
<td> <?php echo refLink($row['redshiftRef']); ?></td>
</tr>

<tr class = 'details' id = '<?php echo $name;?>' style="display:none;">
<td colspan=9>
<?php

detailsTable($observations[$name]);
?>

</td>
</tr>
<?php
}
?>
</table>

<script>
function toggleDiv(d){
    if (document.getElementById(d).style.display=="none"){
        document.getElementById(d).style.display="";
    } else{ 
        document.getElementById(d).style.display="none";
    }
    var button = d + 'Button';
    if (document.getElementById(button).innerHTML == '–'){
        document.getElementById(button).innerHTML = "+";
    }
    else {
        document.getElementById(button).innerHTML = "–";
    }
}

function modelPopup(d) {
    var selector = '#'+d;
    var popupId = 'model'+d;
    $('<div/>', {id: popupId}).appendTo(selector);
    $('#'+popupId).dialog();
    console.log('Hi');
}

</script>
<?php

}

function detailsTable($observations){
?>
<table class="detailsTable" style='white-space:nowrap;table-layout:fixed'>
<tr>
<th>Obs. ID</th>
<th>Obs. Date</th>
<th>Age (days)</th>
<th>Instrument</th>
<th>Flux <br>(x 10<sup>-13</sup> erg cm<sup>-2</sup> s<sup>-1</sup>)</th>
<th>Energy Range <br>(KeV)</th>
<th>Luminosity <br> (x 10<sup>37</sup> erg s<sup>-1</sup>)</th>
<th>Model</th>
<th>Flux Reference</th>
<th>Observation Reference</th>
</tr>
<?php
foreach($observations as $obs){ 
?>
<tr id=<?php echo $obs['fitsID'];?>>
<td> <?php echo $obs['obsID'];?></td>
<td> <?php 

    if (!empty($_GET['MJD'])){
        echo jdtojulian(mjdtojd($obs['dateObserved']));
    } else {

echo $obs['dateObserved'];}?></td>
<td class='age'> <?php echo $obs['age'];?> </td>
<td> <?php echo $obs['instrument'];?></td>
<td><?php echo $obs['flux']; ?>
<span class='supsub'>
<sup class = 'superscript'>+<?php echo $obs['fluxErrH']; ?></sup>
<sub class = 'subscript'>-<?php echo $obs['fluxErrL']; ?></sub>
</span>
</td>
<td> <?php echo $obs['fluxEnergyL'].' - '.$obs['fluxEnergyH']; ?></td>
<td> <?php echo $obs['lum']; ?>
<span class='supsub'>
<sup class = 'superscript'>+<?php echo removeZeros($obs['lumErrH'],getPrecision($obs['lumErrH'])); ?></sup>
<sub class = 'subscript'>-<?php echo removeZeros($obs['lumErrL'],getPrecision($obs['lumErrL'])); ?></sub>
</span>
</td>
<td> <a href='fitDetails.php?fitsID=<?php echo $obs['fitsID']; ?>'><?php echo $obs['model']; ?></a></td>
<td> <?php echo refLink($obs['fluxRef']) ?></td>
<td> <?php echo refLink($obs['dateObservedRef']) ?></td>

</tr>
<?php 
} 
?>
</table>
<?php
}



function fieldHeader($field){
    switch ($field) {
        case "name":
            echo "<th>Name</th>";
            break;
        case "obsID":
            echo "<th>Obs. ID</th>";
            break;
        case "fitsID":
            echo "<th>Fit ID</th>";
            break;
        case "dateExploded":
            echo "<th>Date Exploded</th>";
            break;
        case "type":
            echo "<th>Type</th>";
            break;
        case "distance":
            echo "<th>Distance (Mpc)</th>";
            break;
        case "distRef":
            echo "<th>Distance Reference</th>";
            break;
        case "dateExplodedRef":
            echo "<th>Date Exploded Ref.</th>";
            break;
        case "uploadSet":
            echo "<th>Upload Set</th>";
            break;
        case "flux":
            echo "<th>Flux</th>";
            break;
        case "fluxRef":
            echo "<th>Flux Ref.</th>";
            break;
        case "model":
            echo "<th>Model</th>";
            break;
        case "instrument":
            echo "<th>Instrument</th>";
            break;
        case "dateObserved":
            echo "<th>Date Observed</th>";
            break;
        case "dateObservedRef":
            echo "<th>Date Observed Ref.</th>";
            break;
        case "fluxEnergyL":
            echo "<th>Energy Range</th>";
            break;
        case "redshift":
            echo "<th>Redshift</th>";
            break;
        case "age":
            echo "<th>Age</th>";
            break;
        case "redshiftRef":
            echo "<th>Redshift Ref.</th>";
            break;
        case "fluxErrL":
            break;
        case "fluxErrH":
            break;
        case "fluxEnergyH":
            break;
        default:
            echo "<th>".ucFirst($field)."</th>";
    }
}


function fieldCell ($field, $data){
    switch($field){
        case "dateExploded":
            echo "<td>".dispDate($data['dateExploded'])."</td>";
            break;
        case "dateExplodedRef":
            echo "<td>".refLink($data['dateExplodedRef'])."</td>";
            break;
        case "distance":
            echo "<td>".htmlspecialchars(removeZeros($data['distance'],2))."</td>";
            break;
        case "distRef":
            echo "<td>".refLink($data['distRef'])."</td>";
            break;
        case "flux":
            echo "<td>".htmlspecialchars(removeZeros($data['flux'],getPrecision($data['fluxErrL'])))."<sup> +".htmlspecialchars(removeZeros($data['fluxErrH'],getPrecision($data['fluxErrH'])))."</sup><sub> -".htmlspecialchars(removeZeros($data['fluxErrL'],getPrecision($data['fluxErrH'])))."</sub></td>";
            break;
        case "dateObserved":
            echo "<td>".dispDate($data['dateObserved'])."</td>";
            break;
        case "dateObservedRef":
            echo "<td>".refLink($data['dateObservedRef'])."</td>";
            break;
        case "fluxEnergyL":
            echo "<td>".removeZeros($data['fluxEnergyL'],2)." - ".removeZeros($data['fluxEnergyH'],2)."</td>";
            break;
        case "redshift":
            echo "<td>".htmlspecialchars(removeZeros($data['redshift'],getPrecision($data['redshift']))).' &plusmn;'.htmlspecialchars(sprintf('%f',removeZeros($data['redshiftErr'],getPrecision($data['redshiftErr'])))).'</td>';
            break;
        case "redshiftRef":
            echo "<td>".refLink($data['redshiftRef'])."</td>";
            break;
        case "fluxRef":
            echo "<td>".refLink($data['fluxRef'])."</td>";
            break;
        case "fluxErrL":
            break;
        case "fluxErrH":
            break;
        case "fluxEnergyH":
            break;
        default:
            echo "<td>".htmlspecialchars($data[$field])."</td>";
    }
}




//Make a table based on an array of fields
function table($fields, $data) {
    echo "<table><thead><tr>";
    foreach($fields as $field){
        fieldHeader($field);
    }
    echo "</tr></thead>";
    foreach($data as $key=>$row){
        echo "<tr>";
        foreach($fields as $field){
            fieldCell($field,$row);
        }
        echo "</tr>";
    }
    echo "</table>";
}


function smartTable($data) {
    $fields = array_keys(current($data));
    table($fields,$data);
}




?>
