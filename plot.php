<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="flot/jquery.flot.js"></script>
<div id="placeholder" style="width:600px;height:300px"></div>
<script>


$( document ).ready(function() {
var d1 = [<?php
foreach($result as $row){
    echo '['.$row['age'].','.$row['lum'].'],';
}
?>
];
    var options = { 
            series: {lines:{show:false},points:{show:true}},
};
   $.plot($("#placeholder"),[{data:d1}],options);
});
</script>

