<?php
	$period = $_GET['period'];
	$curtime = $_GET['curtime'];
	require_once("pChart/class/pData.class.php");
	require_once("pChart/class/pDraw.class.php");
	require_once("pChart/class/pImage.class.php");
	require("config.php");

	function isOn ($time, $outlet, $db) {
		$stmt = $db->prepare("SELECT * FROM times WHERE id=:id AND timeon<=:time");
		$stmt->execute(array(':id'=>$outlet, ':time'=>$time));
		$results = $stmt->fetchAll();
		$wason = false;
		for ($i=0; $i < count($results); $i++) { 
			if ($results[$i]['timeon']<=$time && $results[$i]['timeoff']>=$time) {
				$wason = true;
				break;
			}
		}
		$stmt = $db->prepare("SELECT timeon FROM states WHERE id=:id");
		$stmt->execute(array(':id'=>$outlet));
		$result = $stmt->fetchAll();
		if ($result[0]['timeon']<=$time && $result[0]['timeon']!=0) {
			$ison = true;
		}
		else {
			$ison = false;
		}
		return ($ison || $wason);
	}

	$db = new PDO("mysql:dbname=".$GLOBALS["database"].";host=".$GLOBALS["hostname"].";port=".$GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$numon = array();
	for ($k=10; $k >= 0; $k--) { 
		$time = $curtime - $k*($period/10);
		$numon[$time] = 0;
		for ($ii=0; $ii < 8; $ii++) { 
			if (isOn($time, $ii, $db)) {
				$numon[$time]++;
			}
		}
	}
	$times = array_keys($numon);
	for ($i=0; $i < count($times); $i++) { 
		$times[$i] = date("H:i",$times[$i]);
	}

	$data = new pData();

	$data->addPoints($numon,"powerstrip");
	$data->addPoints($times,"Labels");
	$data->setAxisName(0, "Number of Outlets");
	$data->setXAxisName("Time");
	//$data->setAxisName(1, "Time");
	$data->setSerieDescription("Labels","Time");
	$data->setAbscissa("Labels");
	$picture = new pImage(700, 400, $data);
	$picture->Antialias = FALSE;
	$Settings = array("R"=>255, "G"=>255, "B"=>255);
	$picture->drawFilledRectangle(0,0,700,230,$Settings);
	$picture->drawRectangle(0,0,699,399,array("R"=>0,"G"=>0,"B"=>0));
	$picture->setFontProperties(array("FontName"=>"pChart/fonts/San-Fran-Regular.ttf", "FontSize"=>15));
	$picture->setGraphArea(50,50,675,350);
	$picture->drawText(350,50,"Powerstrip Usage",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	$axisboundaries = array(0=>array("Min"=>0,"Max"=>8));
	$scalesettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE, "Mode"=>SCALE_MODE_MANUAL, "ManualScale"=>$axisboundaries);
	$picture->drawScale($scalesettings);
	$picture->drawLineChart();
	$picture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80));

	//$picture->Render("/Applications/MAMP/htdocs/Powerstrip/graph.png");
	$picture->stroke();
?>