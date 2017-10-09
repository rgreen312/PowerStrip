<!DOCTYPE html>
<html>
<head>
	<title>Powerstrip Tracker</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link rel="stylesheet" type="text/css" href="animate.css">
</head>
<body>
<div id="header">
<a href='http://192.168.1.131' class="headerbutton" style="width:32%">Homepage</a>
<a href="http://192.168.1.131/Remote/" class="headerbutton" style="width:32%">Remote</a>
<a href="index.php" class="headerbutton" style="width:32%">Controls</a>
<div style="clear:both"></div>
</div>
<div style="height:25px;"></div>
<br>
<div id="usage">
	<p>View Powerstrip Usage for </p>
	<form method="POST" id="timeform">
		<select id="timeperiod" name="timeperiod" onchange="changeSelect()">
			<option value="0">Select</option>
			<option value="3600" <?php if ($_POST['timeperiod']==3600) {echo " selected='selected'";} ?>>Past Hour</option>
			<option value="43200" <?php if ($_POST['timeperiod']==43200) {echo " selected='selected'";} ?>>Past 12 Hours</option>
			<option value="86400" <?php if ($_POST['timeperiod']==86400) {echo " selected='selected'";} ?>>Past 24 Hours</option>
			<option value="259200" <?php if ($_POST['timeperiod']==259200) {echo " selected='selected'";} ?>>Past 3 Days</option>
			<option value="604800" <?php if ($_POST['timeperiod']==604800) {echo " selected='selected'";} ?>>Past Week</option>
		</select>
	</form>
</div>
<?php
	require("config.php");
	$db = new PDO("mysql:dbname=".$GLOBALS["database"].";host=".$GLOBALS["hostname"].";port=".$GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if ($_SERVER["REQUEST_METHOD"]=="POST" && $_POST['timeperiod'] != 0) {
		$period = $_POST['timeperiod'];
		$stmt = $db->prepare("SELECT UNIX_TIMESTAMP();");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$curtime = $result[0][0];
		echo "<table id='usagedata'><tr><th>Device</th><th>Time On</th></tr>";
		for ($i=0; $i < 8; $i++) { 
			$earliesttime = $curtime - $period;
			//echo $earliesttime;
			$stmt = $db->prepare("SELECT * FROM `times` WHERE timeoff >= :timeoff AND id = :id");
			$stmt->execute(array(':timeoff'=>$earliesttime, ':id'=>$i));
			$results = $stmt->fetchAll();
			$totaltime = 0;
			for ($j=0; $j < count($results); $j++) { 
				if ($results[$j]['timeon'] < $earliesttime) {
					$totaltime = $totaltime + (intval($results[$j]['timeoff']) - $earliesttime);
				}
				else {
					$totaltime = $totaltime + intval($results[$j]['totaltime']);
				}
			}
			$stmt=$db->prepare("SELECT timeon FROM states WHERE id=:id");
			$stmt->execute(array(":id"=>$i));
			$result = $stmt->fetchAll();
			if ($result[0][0] != 0) {
				$totaltime = $totaltime + ($curtime-$result[0][0]);
			}
			if ($totaltime > $period) {
				$totaltime = $period;
			}
			$hours = (int) ($totaltime/3600);
			$totaltime = $totaltime%3600;
			$minutes = (int) ($totaltime/60);
			$totaltime = $totaltime%60;
			$seconds = (int) ($totaltime);

			$stmt = $db->prepare("SELECT * FROM labels");
			$stmt->execute();
			$results = $stmt->fetchAll();
			echo "<tr><td>".$results[$i]['name']."</td><td>".$hours.":".sprintf("%02d", $minutes).":".sprintf("%02d", $seconds)."</td></tr>";
		}
		echo "</table>";
 		echo "<img src='graph.php?period=".$period."&curtime=".$curtime."' style='display:block; margin-left:auto; margin-right:auto;'>";
	}

?>
	<script src="script.js"></script>
</body>
</html>