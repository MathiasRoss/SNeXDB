<table id = "novaTable"  class="display" >
<thead>
<tr>
<th>Name</th>
<th>Type</th>
<th>Date Exploded (MJD)</th>
<th>Distance (Mpc)</th>
<th>Distance Reference</th>
<th>Date Reference</th>
</tr>
</thead>
<?php
foreach ($novae as $name=>$row) {
    if (!empty($_GET['MJD'])){
        $novae[$name]['dateExploded'] = jdtojulian(mjdtojd($row['dateExploded']));
    }


?>
<tr>
<td>
<a href="javascript:toggleDiv('<?php echo $name; ?>')" style="color:black; text-decoration:none;">
<span  id='<?php echo $name.'Button'; ?>'>+</span></a>
<?php echo $name; ?></td>
<td> <?php echo $row['type']; ?> </td>
<td> <?php 

    if (!empty($_GET['MJD'])){
        echo jdtojulian(mjdtojd($row['dateExploded']));
    } else {
echo removeZeros($novae[$name]['dateExploded'],0);}?></td>
<td> <?php echo removeZeros($row['distance'],2); ?></td>
<td> <?php echo refLink($row['distRef']) ?></td>
<td> <?php echo refLink($row['dateExplodedRef']) ?></td>
</tr>

<tr class = 'details' id = '<?php echo $name;?>' style="display:none;">
<td colspan=9>


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
foreach($observations[$name] as $obs){ 
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
<sup class = 'superscript'>+<?php echo removeZeros($obs['fluxErrH'],getPrecision($obs['fluxErrH'])); ?></sup>
<sub class = 'subscript'>-<?php echo removeZeros($obs['fluxErrL'],getPrecision($obs['fluxErrL'])); ?></sub>
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

