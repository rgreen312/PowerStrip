<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Powerstrip Controls</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="stylesheet" type="text/css" href="animate.css">
    </head>
 
    <body>
    <div id="header">
    <a href='http://192.168.1.131' class="headerbutton">Homepage</a>
    <a href="http://192.168.1.131/Remote/" class="headerbutton">Remote</a>
    <a href="times.php" class="headerbutton">Usage</a>
    <a href="#" onclick="edit();" class="headerbutton">Edit Names</a>
    <div style="clear:both"></div>
    </div>
    <div style="height:25px;"></div>
    <br>
    <!-- On/Off button's picture -->
	<?php
	require("config.php");
	$db = new PDO("mysql:dbname=".$GLOBALS["database"].";host=".$GLOBALS["hostname"].";port=".$GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $db->prepare("SELECT * FROM labels");
	$stmt->execute();
	$results = $stmt->fetchAll();

	$val_array = array(0,0,0,0,0,0,0,0);
	//this php script generate the first page in function of the file
	$stmt = $db->prepare("SELECT * FROM states");
	$stmt->execute();
	$result=$stmt->fetchAll();
	for ( $i= 0; $i<8; $i++) {
		$val_array[$i] = $result[$i]['state'];
		//set the pin's mode to output and read them
		//system("gpio mode ".$i." out");
		//exec ("gpio read ".$i, $val_array[$i], $return );
	}
	//for loop to read the value
	$i =0;
	for ($i = 0; $i < 8; $i++) {
		//if on
		if ($val_array[$i][0] == 1 ) {
			echo ("<div style='float:left' id='button_".$i."' onclick='change_pin (".$i.");'>");
			echo ("<img id='image".$i."' src='data/img/green/green.jpg'/>");
			echo ("<p class='label' id='label".$results[$i][0]."'>".$results[$i][1]."</p></div>");
		}
		//if off
		if ($val_array[$i][0] == 0 ) {
			echo ("<div style='float:left' id='button_".$i."' onclick='change_pin (".$i.");'>");
			echo ("<img id='image".$i."' src='data/img/red/red.jpg'/>");
			echo ("<p class='label' id='label".$results[$i][0]."'>".$results[$i][1]."</p></div>");		
		}	 
	}
	 $html= 
	 "<div id='cover' onclick='deletePanel();'></div>
	 <div id='box' class='animated slideInDown'>
	 <div id='edit'>";

	for ($i=0; $i < 8; $i++) { 
		$html.= ("<div class='inner'><p id='editlabel".$i."'>Outlet ".($i+1).":</p>");
		$html.=  ("<input type='text' class='textbox' id='button".$i."' value='".$results[$i][1]."'>");
		$html.=  "<button onclick='change_label (".$i.");'>Update</button><div style='clear:both'></div></div>";
	}
	?>
	 <script type="text/javascript">
	 function edit() {
	 	var data =<?php echo json_encode($html); ?>;
	 	document.body.innerHTML += data;
	 }
	 </script>
	 </div></div>
	 <div id="curvalue"></div>
	<!-- javascript -->
	<script src="script.js"></script>
    </body>
</html>