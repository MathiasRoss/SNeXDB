<?php

/* display the normal tiered table
 *takes in a nova array, and an observations array
 *
 *
 *
 */

function displayTable($novae,$observations){
    echo "<table id = 'novaTable'  class='display' ><thead><tr>";
    fieldHeader('checkBox');
    fieldHeader('name');
    fieldHeader('type');
    fieldHeader('dateExploded');
    fieldHeader('distance');
    fieldHeader('redshift');
    fieldHeader('distRef');
    fieldHeader('dateExplodedRef');
    fieldHeader('redshiftRef');    
    echo "</tr></thead>";

    $isOdd = false;
    foreach ($novae as $name=>$row) {
 
        if ($isOdd){
           echo "<tr class='odd'>";
            $isOdd=false;
        }
        else{
           echo "<tr class='even'>";
            $isOdd=true;
        }
        echo "<td><input type='checkbox' class='novaeBox' id='".$name."'></td>";
        ?>
        <td>
        <a id='<?php echo $name.'Button'; ?>' href="javascript:toggleDiv('<?php echo $name.'Row'; ?>')" style="color:black; text-decoration:none;">+</a>
        
        <?php echo $name.' ('.$row['count'].')'; ?></td>
        <?php
        fieldCell('type',$novae[$name]);
        fieldCell('dateExploded',$novae[$name]);
        fieldCell('distance',$novae[$name]);
        fieldCell('redshift',$novae[$name]);
        fieldCell('distRef',$novae[$name]);
        fieldCell('dateExplodedRef',$novae[$name]);
        fieldCell('redshiftRef',$novae[$name]);
        
        
        echo '</tr>';
        
        ?>
        
        <tr class = 'details' id = '<?php echo $name.'Row';?>' style="display:none;">
        <td colspan=9>
        <?php
        
        table(array('checkBox','obsID','dateObserved','age','instrument','flux','fluxEnergyL','lum','model','fluxRef','dateObservedRef'),$observations[$name],'detailsTable',$name.'Table');
        ?>
        
        </td>
        </tr>
    <?php
    }
?>
</table>

<?php

}

function checkBox($name){
    echo "<input type='checkbox' id='".$name."'>";
}




function fieldHeader($field){
    switch ($field) {
        case 'checkBox':
            echo '<th></th>';
            break;
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
            echo "<th>Distance </th>";
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
        case "lum":
            echo "<th>Luminosity</th>";
            break;
        default:
            echo "<th>".ucFirst($field)."</th>";
    }
}


function fieldCell ($field, $data){
    if (empty($_GET['MJD'])){
        $mjd = true;
    }
    else {
        $mjd = false;
    }
    switch($field){
        case "checkBox":
            $id = $data['fitsID'];
            echo '<th>';
            checkBox($id);
            echo '</th>';
            break;
        case "dateExploded":
            echo "<td>".dispDate($data['dateExploded'],$mjd)."</td>";
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
            echo "<td>";
            if ($data['isUpperBound']){
                echo '<';
            }
            echo htmlspecialchars(removeZeros($data['flux'],getPrecision($data['fluxErrL'])));
            if ($data['fluxErrH']!=0){
                echo "<sup> +".htmlspecialchars(removeZeros($data['fluxErrH'],getPrecision($data['fluxErrH'])))."</sup><sub> -".htmlspecialchars(removeZeros($data['fluxErrL'],getPrecision($data['fluxErrH'])))."</sub>";
            }
            echo "</td>";
            break;
        case "dateObserved":
            echo "<td>".dispDate($data['dateObserved'],$mjd)."</td>";
            break;
        case "dateObservedRef":
            echo "<td>".refLink($data['dateObservedRef'])."</td>";
            break;
        case "fluxEnergyL":
            echo "<td>".removeZeros($data['fluxEnergyL'],2)." - ".removeZeros($data['fluxEnergyH'],2)."</td>";
            break;
        case "redshift":
            echo "<td>".htmlspecialchars(removeZeros($data['redshift'],getPrecision($data['redshift'])));
            if ($data['redshiftErr']!=0){
                echo '&plusmn;'.htmlspecialchars(sprintf('%f',removeZeros($data['redshiftErr'],getPrecision($data['redshiftErr']))));
            }
            echo '</td>';
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
        case "lum":
            echo "<td>";
            if ($data['isUpperBound']){
                echo '<';
            }
            echo htmlspecialchars(removeZeros($data['lum'],getPrecision($data['lumErrL'])));
            if ($data['lumErrH']!=0){
                echo "<sup> +".htmlspecialchars(removeZeros($data['lumErrH'],getPrecision($data['lumErrH'])))."</sup><sub> -".htmlspecialchars(removeZeros($data['lumErrL'],getPrecision($data['lumErrH'])))."</sub>";
            }
            echo "</td>"; 
            break;
        default:
            echo "<td>".htmlspecialchars($data[$field])."</td>";
    }
}




//Make a table based on an array of fields
function table($fields, $data, $class='',$id='',$style='') {
    echo "<table class ='".$class."'id='".$id."' style='".$style."'><thead><tr>";
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
