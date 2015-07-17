<table style='white-space:nowrap;table-layout:fixed'>
<tr>
<th>Name</th>
<th>Date Observed</th>
<th>Age</th>
<th>Instrument</th>
<th>Reference</th>
</tr>
<?php
foreach ($observations as $row){
?>
<tr>
<td> <?php echo $row['name']; ?> </td>
<td> <?php echo $row['dateObserved']; ?></td>
<td> <?php echo getAge($row['dateObserved'],$row['dateExploded']);?> </td>
<td> <?php echo $row['instrument']; ?></td>
<td> <?php echo refLink($row['dateObservedRef']) ?></td>
</tr>
<?php
}
?>
</table>


