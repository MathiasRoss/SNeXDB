<div id = exportPopup>
<form method="get" action= "export.php">

<select name='exportType'>
<option value='TSV'>TSV</option>
<option value='CSV'>CSV</option>
</select>

<input type='text'name='columns'>


<input type="hidden" name = "name" value = <?php $_GET['name']; ?>>
<input type="hidden" name = "objid" value = <?php $_GET['objid']; ?>>
<input type="hidden" name = "typeid" value = <?php $_GET['typeid']; ?>>
<input type="hidden" name = "type" value = <?php $_GET['type']; ?>>
<input type="hidden" name = "fluxMin" value = <?php $_GET['fluxMin']; ?>>
<input type="hidden" name = "fluxMax" value = <?php $_GET['fluxMax']; ?>>
<input type="hidden" name = "lumMin" value = <?php $_GET['lumMin']; ?>>
<input type="hidden" name = "typeMax" value = <?php $_GET['lumMax']; ?>>
<input type = "submit" value = "Export Results">
</form>
</div>
