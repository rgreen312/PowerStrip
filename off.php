<?php
	$num = $_GET['num'];
	require("config.php");
	$db = new PDO("mysql:dbname=".$GLOBALS["database"].";host=".$GLOBALS["hostname"].";port=".$GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $db->prepare("SELECT UNIX_TIMESTAMP();");
	$stmt->execute();
	$result = $stmt->fetchAll();
	$curtime = $result[0][0];
	$stmt=$db->prepare("SELECT timeon FROM states WHERE id=:id");
	$stmt->execute(array(':id'=>$num));
	$results=$stmt->fetchAll();
	$starttime = $results[0][0];
	$totaltime = $curtime-$starttime;
	$stmt = $db->prepare("INSERT INTO times (id, timeon, timeoff, totaltime) VALUES (:id, :timeon, :timeoff, :totaltime)");
	$stmt->execute(array(':id'=>$num,':timeon'=> $starttime, ':timeoff'=>$curtime, ':totaltime'=>$totaltime));
	$stmt = $db->prepare("UPDATE states SET state=FALSE, timeon=NULL WHERE id=:id");
	$stmt->execute(array(':id'=>$num));
?>