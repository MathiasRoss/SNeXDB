


<table style='white-space:nowrap;table-layout:fixed'>
<tr>
<th>Name</th>
<th>Type</th>
<th>Date Exploded</th>
<th>Distance</th>
<th>Date Observed</th>
<th>Age</th>
<th>Instrument</th>
<th>Flux</th>
<th>Flux Energy Range</th>
<th>Flux Model</th>
<th>Luminosity</th>
<th>Flux Reference</th>
<th>Distance Reference</th>
<th>Date Exploded Reference</th>
<th>Observation Reference</th>
</tr>

<?php
foreach ($result as $row){
?>
<tr>
<td> <?php echo $row['name']; ?> </td>
<td> <?php echo $row['type']; ?> </td>
<td> <?php echo removeZeros($row['dateExploded'],0);?></td>
<td> <?php echo removeZeros($row['distance'],2); ?></td>
<td> <?php echo removeZeros($row['dateObserved'],0); ?></td>
<td> <?php echo getAge($row['dateObserved'],$row['dateExploded']);?> </td>
<td> <?php echo $row['instrument']; ?></td>
<td><?php echo removeZeros($row['flux'],getPrecision($row['fluxErrL'])); ?>
<span class='supsub'>
<sup class = 'superscript'>+<?php echo removeZeros($row['fluxErrH'],getPrecision($row['fluxErrH'])); ?></sup>
<sub class = 'subscript'>-<?php echo removeZeros($row['fluxErrL'],getPrecision($row['fluxErrL'])); ?></sub>
</span>
</td>
<td> <?php echo $row['fluxEnergyL'].' - '.$row['fluxEnergyH']; ?></td>
<td> <?php echo $row['model']; ?></td>
<td> <?php echo $row['lum']; ?></td>
<td> <?php echo refLink($row['fluxRef']) ?></td>
<td> <?php echo refLink($row['distRef']) ?></td>
<td> <?php echo refLink($row['dateExplodedRef']) ?></td>
<td> <?php echo refLink($row['dateObservedRef']) ?></td>
</tr>
<?php
}


?>
</table>
