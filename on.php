<?php
	$num = $_GET['num'];
	require("config.php");
	$db = new PDO("mysql:dbname=".$GLOBALS["database"].";host=".$GLOBALS["hostname"].";port=".$GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $db->prepare("SELECT UNIX_TIMESTAMP();");
	$stmt->execute();
	$result = $stmt->fetchAll();
	print_r($result);
	$time = $result[0][0];
	$stmt = $db->prepare("UPDATE states SET timeon=:timeon WHERE id=:id");
	$stmt->execute(array(':timeon'=> $time, ':id'=>$num));
?>