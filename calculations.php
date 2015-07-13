<?php

function get_age($date_observed,$date_explosion){
    return $date_observed-$date_explosion;
}

function mjdtojd($date){
    return $date + 2400000.5;
}

function mpctocm($distance){
    return $distance*3.08567758*pow(10,10^18);
}

function get_lum($distance,$flux){
//    $distance = $distance*3.08567758*pow(10,18); //conversion from Mparsecs to cm
//    $flux = $flux*pow(10,-13);//flux stored in this order
    return 4.*3.159265*mpctocm($distance)*mpctocm($distance)*$flux*pow(10,-13);
}   

function refLink($ref){
    echo "<a href=\"http://adsabs.harvard.edu/abs/", $ref ,"\">",$ref,"</a>";

}

?>
