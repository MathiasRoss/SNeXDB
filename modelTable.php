<table class="display" >
<thead>
<tr>
<th>Parameter</th>
<th>Value</th>
</tr>
</thead>
<?php
foreach($paramTableArray as $param=>$value) {
?>
<tr>
<td>
<?php echo $param; ?>
</td>
<td>
<?php echo $value; ?>
</td>
</tr>
<?php
}
?>
</table>
