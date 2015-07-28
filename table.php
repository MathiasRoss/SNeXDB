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
?>
<tr>
<td>
<a href="javascript:toggleDiv('<?php echo $name; ?>')" style="color:black; text-decoration:none;">
<span  id='<?php echo $name.'Button'; ?>'>+</span></a>
<?php echo $name; ?></td>
<td> <?php echo $row['type']; ?> </td>
<td> <?php echo removeZeros($row['dateExploded'],0);?></td>
<td> <?php echo removeZeros($row['distance'],2); ?></td>
<td> <?php echo refLink($row['distRef']) ?></td>
<td> <?php echo refLink($row['dateExplodedRef']) ?></td>
</tr>

<tr class = 'details' id = '<?php echo $name;?>' style="display:none;">
<td colspan=9>


<table class="detailsTable" style='white-space:nowrap;table-layout:fixed'>
<tr>
<th>Observation ID</th>
<th>Date Observed (MJD)</th>
<th>Age (days)</th>
<th>Instrument</th>
<th>Flux (x 10<sup>-13</sup> erg cm<sup>-2</sup> s<sup>-1</sup>)</th>
<th>Flux Energy Range (KeV)</th>
<th>Luminosity (x 10<sup>37</sup> erg s<sup>-1</sup>)</th>
<th>Model</th>
<th>Flux Reference</th>
<th>Observation Reference</th>
</tr>
<?php
foreach($observations[$name] as $obs){ 
?>
<tr id=<?php echo $obs['fitsID'];?>>
<td> <?php echo $obs['obsID'];?></td>
<td> <?php echo $obs['dateObserved'];?></td>
<td class='age'> <?php echo $obs['age'];?> </td>
<td> <?php echo $obs['instrument'];?></td>
<td><?php echo $obs['flux']; ?>
<span class='supsub'>
<sup class = 'superscript'>+<?php echo removeZeros($obs['fluxErrH'],getPrecision($obs['fluxErrH'])); ?></sup>
<sub class = 'subscript'>-<?php echo removeZeros($obs['fluxErrL'],getPrecision($obs['fluxErrL'])); ?></sub>
</span>
</td>
<td> <?php echo $obs['fluxEnergyL'].' - '.$obs['fluxEnergyH']; ?></td>
<td class='lum'> <?php echo $obs['lum']; ?>&plusmn;<?php echo $obs['lumErr']; ?></td>
<td> <?php echo $obs['model']; ?></td>
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
    console.log(button);
    if (document.getElementById(button).innerHTML == '–'){
        document.getElementById(button).innerHTML = "+";
    }
    else {
        document.getElementById(button).innerHTML = "–";
    }
}
</script>

