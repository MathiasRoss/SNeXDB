<?php


$novae=array();

foreach($result as $key => $row){
//calculate luminosity error
    $result[$key]['lumErrL'] = getLumErr($result[$key]['lum'],$row['flux'],$row['fluxErrL']); 
    $result[$key]['lumErrH'] = getLumErr($result[$key]['lum'],$row['flux'],$row['fluxErrH']);


//remove excess digits, using uncertainty values where available
    $result[$key]['flux']=removeZeros($row['flux'],getPrecision($row['fluxErrL']));
    $result[$key]['fluxErrL'] = removeZeros($row['fluxErrL'],getPrecision($row['fluxErrL']));
    $result[$key]['fluxErrH'] = removeZeros($row['fluxErrH'],getPrecision($row['fluxErrH']));

    $result[$key]['dateObserved']=removeZeros($row['dateObserved'],0);
    $result[$key]['dateExploded']=removeZeros($row['dateExploded'],0);
    $result[$key]['age'] = removeZeros($row['age'],0);

    $result[$key]['fluxEnergyL']=removeZeros($row['fluxEnergyL'],getPrecision($row['fluxEnergyL']));
    $result[$key]['fluxEnergyH']=removeZeros($row['fluxEnergyH'],getPrecision($row['fluxEnergyH']));

    $lumErrMag = floor(log10($result[$key]['lumErrL']));
    echo $lumErrMag;
    $result[$key]['lum'] = roundToMag($result[$key]['lum'],$lumErrMag);
    $result[$key]['lumErrL'] = roundToMag($result[$key]['lumErrL'],$lumErrMag);
    $result[$key]['lumErrH'] = roundToMag($result[$key]['lumErrH'],$lumErrMag);




    if (empty($novae[$row['name']])){
        $novae[$row['name']]['type']=$result[$key]['type'];
        $novae[$row['name']]['dateExploded']=$result[$key]['dateExploded']; 
        $novae[$row['name']]['distance']=$result[$key]['distance'];
        $novae[$row['name']]['distRef']=$result[$key]['distRef'];
        $novae[$row['name']]['dateExplodedRef']=$result[$key]['dateExplodedRef'];
    }
    $observations[$row['name']][]=$result[$key];


}



$jsonTable = json_encode($result);

?>
