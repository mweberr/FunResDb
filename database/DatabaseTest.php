<?php
include('../config.php');
require_once '../database/DBFactory.php';
require_once 'LogInputDao.php';

//global $db_host;

//var_dump($db_ids);
DBFactory::init($db_ids);
$dbconnect = DBFactory::getConnection();
//$inputDao = new LogInputDao($dbconnect);
//
$stmt = $dbconnect->query('SELECT * FROM Mutant_Mutation');
//

//$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r($results);
//

//$res = $inputDao->countRows();
//echo $res;



//$ip = ip2long($ip);
