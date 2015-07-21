<table id = "novaTable"  class="display" >
<thead>
<tr>
<th>Name</th>
<th>Type</th>
<th>Date Exploded</th>
<th>Distance</th>
<th>Distance Reference</th>
<th>Date Reference</th>
</tr>
</thead>
<?php
foreach ($novae as $name=>$row) {
?>
<tr>
<td> <?php echo $name; ?> </td>
<td> <?php echo $row['type']; ?> </td>
<td> <?php echo removeZeros($row['dateExploded'],0);?></td>
<td> <?php echo removeZeros($row['distance'],2); ?></td>
<td> <?php echo refLink($row['distRef']) ?></td>
<td> <?php echo refLink($row['dateExplodedRef']) ?></td>
</tr>
<tr>
<td colspan=9>
<a href="javascript:toggleDiv('<?php echo $name; ?>')">
Click to show/hide details.
</a>


<div id = '<?php echo $name;?>' style="display:none;" >
<table class="detailsTable" style='white-space:nowrap;table-layout:fixed'>
<tr>
<th>Observation ID</th>
<th>Date Observed</th>
<th>Age</th>
<th>Instrument</th>
<th>Flux</th>
<th>Flux Energy Range</th>
<th>Luminosity</th>
<th>Model</th>
<th>Flux Reference</th>
<th>Observation Reference</th>
</tr>
<?php
foreach($observations[$name] as $obs){ 
?>
<tr>
<td> <?php echo $obs['obsID'];?></td>
<td> <?php echo $obs['dateObserved'];?></td>
<td> <?php echo $obs['age'];?> </td>
<td> <?php echo $obs['instrument'];?></td>
<td><?php echo $obs['flux']; ?>
<span class='supsub'>
<sup class = 'superscript'>+<?php echo removeZeros($obs['fluxErrH'],getPrecision($obs['fluxErrH'])); ?></sup>
<sub class = 'subscript'>-<?php echo removeZeros($obs['fluxErrL'],getPrecision($obs['fluxErrL'])); ?></sub>
</span>
</td>
<td> <?php echo $obs['fluxEnergyL'].' - '.$obs['fluxEnergyH']; ?></td>
<td> <?php echo $obs['lum']; ?></td>
<td> <?php echo $obs['model']; ?></td>
<td> <?php echo refLink($obs['fluxRef']) ?></td>
<td> <?php echo refLink($obs['dateObservedRef']) ?></td>

</tr>
<?php 
} 
?>
</table>
</div>
</td>
</tr>
<?php
}
?>
</table>

<script>
function toggleDiv(d){
    if (document.getElementById(d).style.display=="none"){
        document.getElementById(d).style.display="block";
    } else{ 
        document.getElementById(d).style.display="none";
    }
}
</script>

