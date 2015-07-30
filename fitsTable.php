<?php 
$tableFields = array('Name','obsID','fitsID','flux','fluxErrL','fluxErrH','fluxEnergyL','fluxEnergyH','fluxRef','model');

?>
<table style='white-space:nowrap;table-layout:fixed'>
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
foreach($result as $obs){ 
if (!empty($obs['flux'])){
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
<td> <?php echo $obs['lum']; ?>
<span class='supsub'>
<sup class = 'superscript'>+<?php echo removeZeros($obs['lumErrH'],getPrecision($obs['lumErrH'])); ?></sup>
<sub class = 'subscript'>-<?php echo removeZeros($obs['lumErrL'],getPrecision($obs['lumErrL'])); ?></sub>
</span>
</td>
<td> <a href='fitDetails.php?fitsID=<?php echo $obs['fitsID']; ?>'><?php echo $obs['model']; ?></a></td>
<td> <?php echo refLink($obs['fluxRef']); ?></td>
<td> <?php echo refLink($obs['dateObservedRef']); ?></td>

</tr>
<?php 
} }
?>
</table>
