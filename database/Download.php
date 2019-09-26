<?php

ini_set('display_errors',1);
session_start();

if(!empty($_SESSION["report"])){
    
    $current_date = date('m-d-Y-His');
    $fn = 'Report_'.$current_date.'.txt';
    $report = $_SESSION["report"];
    
    header('Content-Disposition: attachment; filename="'.$fn.'"');
    //header('Content-Type: application/force-download');
    header('Content-Type: text/plain');
    header("Content-Length: " . strlen($report));
    print $report;
}




