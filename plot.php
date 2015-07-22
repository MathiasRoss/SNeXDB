<div id="placeholder" style="width:600px;height:300px"></div>

<button class="Test">Test</button>


<script>
$( document ).ready(function() {


<?php
//print the javascript object with age, lum, and lum err
$jsTable = '{';
foreach ($result as $key=>$row) {
    $jsTable = $jsTable.$row['fitsID'].':{';
    $jsTable = $jsTable.'age:'.$row['age'].',';
    $jsTable = $jsTable.'lum:'.$row['lum'].',';
    $jsTable = $jsTable.'lumErr:'.$row['lumErr'];
    $jsTable = $jsTable.'},';
}
$jsTable = $jsTable.'};';
echo 'var jsTable = '.$jsTable;




//sort by age for the plot
foreach($result as $key=>$row){
    $age[$key] = $row['age'];
    $lum[$key] = $row['lum'];
}

array_multisort($age, SORT_ASC, $lum, $result);

?>




    var points = {errorbars:"y",yerr:{show:true, upperCap: "-", lowerCap: "-", radius:5}};
<?php
$data = 'var data = [';
$i = 1;
foreach($novae as $name=>$nova)
{
    $age=array();
    $lum=array();
    //sort by age for the plot
    foreach($observations[$name] as $key=>$row){
        $age[] = $row['age'];
        $lum[] = $row['lum'];
    }

    array_multisort($age, SORT_ASC, $lum, $observations[$name]);


    echo 'var d'.$i.'=[';
    foreach($observations[$name] as $obs){
        echo '['.$obs['age'].','.$obs['lum'].','.getLumErr($obs['lum'],$obs['flux'],$obs['fluxErrL']).'],';
    }
    echo '];';  
    $data = $data."{label:'".$name."', points:points, data:d".$i.'},';

    $i++;
}
$data = $data.'];';
echo $data;
?>

    var options = { 
            series: {lines:{show:true},points:{show:true}}
};
    var plot =  $.plot($("#placeholder"),data,options);

    $(".detailsTable tr").click(function(){
        if ($(this).attr('class')!='selected') {
            $(this).addClass('selected');
        } else{
            $(this).removeClass('selected');

   }

});

//alert(jsTable[1].age);


$('.test').on('click', function() {
    var newArray = [];
    $(".selected").each(function () {
        var row = [];
        row[0] = jsTable[$(this).attr('id')].age;
        row[1] = jsTable[$(this).attr('id')].lum;
        row[2] = jsTable[$(this).attr('id')].lumErr;
        newArray.push(row);
});
   data = [{data:newArray, points:points}];
   plot.setData(data);
   plot.setupGrid();
   plot.draw();
});


});
</script>

