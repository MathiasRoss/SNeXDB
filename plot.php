<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="flot/jquery.flot.js"></script>
<script src="flot/jquery.flot.errorbars.js"></script>
<div id="placeholder" style="width:600px;height:300px"></div>
<script>
<?php
//sort by age for the plot
foreach($result as $key=>$row){
    $age[$key] = $row['age'];
    $lum[$key] = $row['lum'];
}

array_multisort($age, SORT_ASC, $lum, $result);

?>



$( document ).ready(function() {

    var points = {errorbars:"y",yerr:{show:true, upperCap: "-", lowerCap: "-", radius:5}};
<?php
$data = 'var data = [';
$i = 1;
foreach($novae as $nova)
{
    $age=array();
    $lum=array();
    //sort by age for the plot
    foreach($observations[$nova['name']] as $key=>$row){
        $age[$key] = $row['age'];
        $lum[$key] = $row['lum'];
    }

    array_multisort($age, SORT_ASC, $lum, $observations[$nova['name']]);


    echo 'var d'.$i.'=[';
    foreach($observations[$nova['name']] as $obs){
        echo '['.$obs['age'].','.$obs['lum'].','.getLumErr($obs['lum'],$obs['flux'],$obs['fluxErrL']).'],';
    }
    echo '];';  
    $data = $data."{label:'".$nova['name']."', points:points, data:d".$i.'},';

    $i++;
}
$data = $data.'];';
echo $data;
?>

    var options = { 
            series: {lines:{show:true},points:{show:true}}
};
   $.plot($("#placeholder"),data,options);
});
</script>

