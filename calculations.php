<?php
function dispDate($date,$mjd) {
    if ($mjd) {
        return removeZeros($date, 1);
    }
    else {
       return jdtogregorian(mjdtojd($date)); 
    }
}


function parseDate($stringDate) {
    $array = explode('/',$stringDate);
    $jd = gregoriantojd($array[0],$array[1],$array[2])-.5;
    return $jd-2400000.5;
}

function getAge($date_observed,$date_explosion){
    return $date_observed-$date_explosion;
}

function mjdtojd($date){
    return $date + 2400001.;//hack because builtin functions ignore fractional part
}

function mpctocm($distance){
    return $distance*3.08567758*pow(10,24);
}

function getPrecision($error)
{
    $prec = strlen(substr(strrchr(trim($error),'.'),1));
    if ($prec > 1) {return $prec;}
    else{return 2;}
}

function getMag($value)
{
    return floor(log10($value));
}

function removeZeros($value,$precision){
    return round($value*pow(10,$precision))/pow(10,$precision);
}

//function names are hard, okay?
function roundToMag($value,$magnitude){
    return round($value/pow(10,$magnitude))*pow(10,$magnitude);
}


function getLum($distance,$flux){
//    $distance = $distance*3.08567758*pow(10,18); //conversion from Mparsecs to cm
//    $flux = $flux*pow(10,-13);//flux stored in this order
    return 4.*3.14159265*mpctocm($distance)*mpctocm($distance)*$flux*pow(10,-50);
}   

function getLumErr($lum,$flux,$fluxErr){
    return $lum*($fluxErr/$flux);
}

function refLink($ref){
    $link = "<a href=\"http://adsabs.harvard.edu/abs/". rawurlencode($ref) ."\">".htmlspecialchars($ref)."</a>";
    return $link;
}


?>
