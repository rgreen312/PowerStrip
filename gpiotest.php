<?php
//This page is requested by the JavaScript, it updates the pin's status and then print it
//Getting and using values
require("config.php");
$db = new PDO("mysql:dbname=".$GLOBALS["database"].";host=".$GLOBALS["hostname"].";port=".$GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (isset ( $_GET["pic"] )) {
	$pic = strip_tags ($_GET["pic"]);
	//test if value is a number
	if ( (is_numeric($pic)) && ($pic <= 7) && ($pic >= 0) ) {
		$stmt = $db->prepare("SELECT state FROM states WHERE id = :id");
		$stmt->execute(array(":id"=>$pic));
		$results = $stmt->fetchAll();
		//print_r($results);
		if ($results[0]['state'] == 0) {
			$status=1;
		}
		elseif ($results[0]['state'] == 1) {
			$status=0;
		}
		$stmt=$db->prepare("UPDATE states SET state = :state WHERE id = :id");
		$stmt->execute(array(":state"=>$status, ":id"=>$pic));
		echo $status;
	}
	else { echo ("fail"); }
} //print fail if cannot use values
else { echo ("fail"); }
?>
