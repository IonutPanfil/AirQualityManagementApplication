<?php //PDO

	$server = "localhost:3306";
	$user = "root";
	$pw = "";
    $DBname="calitate_aer";

	try {
		$connect = new PDO("mysql:host=$server;dbname=$DBname", $user, $pw);
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		/* echo "Connected successfully"; */
	}catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
		die();
	}
?>