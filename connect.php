<?php


try {
	$db = new PDO(
		"mysql:host=$db_host;dbname=$db_name;charset=UTF8",
		$db_user,
		$db_pass
	);
	
    $db->query("SET NAMES 'utf8'");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	error_log($e->getMessage());
        echo $e->getMessage();
	die("A database error was encountered");
}


?>