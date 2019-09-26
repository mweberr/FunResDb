<?php

//Function getIpAddress
// Returns the IP address of the client accessing the website

function getIpAddress(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
//Is it a proxy address
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return ip2long($ip);
}


function prepareDownloadReport($report,$fn) {

    header('Content-Disposition: attachment; filename="'.$fn.'"');
//header('Content-Type: application/force-download');
    header('Content-Type: text/plain');
    header("Content-Length: " . strlen($report));
    print $report;
}
