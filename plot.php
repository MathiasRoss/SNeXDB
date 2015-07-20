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
var d1 = [<?php
foreach($result as $row){
    echo '['.$row['age'].','.$row['lum'].','.getLumErr($row['lum'],$row['flux'],$row['fluxErrL']).'],';
}
?>
];
    var d1_points = {errorbars:"y",yerr:{show:true, upperCap: "-", lowerCap: "-", radius:5}};
    var data = [{points:d1_points, data:d1}];
    var options = { 
            series: {lines:{show:true},points:{show:true}},
};
   $.plot($("#placeholder"),data,options);
});
</script>

