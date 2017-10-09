<?php
	require("config.php");
	$button = $_GET['button'];
	$text = $_GET['text'];
	if (is_numeric($button) && $button<=7 && $button>=0) {
		require("config.php");
		$db = new PDO("mysql:dbname=".$GLOBALS["database"].";host=".$GLOBALS["hostname"].";port=".$GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $db->prepare("UPDATE labels SET name=:name WHERE id=:id");
		$stmt->execute(array(':name'=>$text, ':id'=>$button));
	}
	echo "success";
?>