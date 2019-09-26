<?php


ini_set('display_errors',1);
error_reporting(-1);

require_once "../config.php";
require_once "DBFactory.php";
require_once "LogInputDao.php";


DBFactory::init($db_ids);

$inputDao = new LogInputDao(DBFactory::getConnection());



$inputDao->storeRecords();
echo 'Store successful ';


