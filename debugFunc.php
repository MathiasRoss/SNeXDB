<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

function dispMem() {
    $lim = memory_get_usage()/(128*1024*1024);
    echo memory_get_usage().' bytes loaded, '.$lim.' of limit';

}

function dispPeakMem() {
    $lim = memory_get_peak_usage()/(128*1024*1024);
    echo memory_get_peak_usage().' bytes loaded at peak, '.$lim.' of limit';
}

function beep(){
    echo "beep()";
}

?>
