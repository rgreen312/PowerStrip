var button_0 = document.getElementById("button_0");
var button_1 = document.getElementById("button_1");
var button_2 = document.getElementById("button_2");
var button_3 = document.getElementById("button_3");
var button_4 = document.getElementById("button_4");
var button_5 = document.getElementById("button_5");
var button_6 = document.getElementById("button_6");
var button_7 = document.getElementById("button_7");

//Create an array for easy access later
var Buttons = [ button_0, button_1, button_2, button_3, button_4, button_5, button_6, button_7];

//This function is asking for gpio.php, receiving datas and updating the index.php pictures
function change_pin ( pic ) {
var data = 0;
//send the pic number to gpio.php for changes
//this is the http request
	var request = new XMLHttpRequest();
	request.open( "GET" , "gpiotest.php?pic=" + pic, true);
	request.send(null);
	//receiving informations
	request.onreadystatechange = function () {
		if (request.readyState == 4 && request.status == 200) {
			data = request.responseText;
			//update the index pic
			if ( !(data.localeCompare("0")) ){
				document.getElementById("image"+pic).src = "data/img/red/red.jpg";
				var onrequest = new XMLHttpRequest();
				onrequest.open("GET", "off.php?num="+pic);
				onrequest.send(null);
			}
			else if ( !(data.localeCompare("1")) ) {
				document.getElementById("image"+pic).src = "data/img/green/green.jpg";
				var onrequest = new XMLHttpRequest();
				onrequest.open("GET", "on.php?num="+pic);
				onrequest.send(null);
			}
			else if ( !(data.localeCompare("fail"))) {
				alert ("Something went wrong!" );
				return ("fail");			
			}
			else {
				alert ("Something went wrong!" );
				return ("fail"); 
			}
		}
		//test if fail
		else if (request.readyState == 4 && request.status == 500) {
			alert ("server error");
			return ("fail");
		}
		//else 
		else if (request.readyState == 4 && request.status != 200 && request.status != 500 ) { 
			alert ("Something went wrong!");
			return ("fail"); }
	}	
	
return 0;
}
function change_label (button) {
	var newlabel = document.getElementById("button"+button).value;
	var request = new XMLHttpRequest();
	request.open( "GET" , "update.php?button="+button+"&text="+newlabel, true);
	request.send(null);
	request.onreadystatechange = function () {
		if (request.readyState == 4 && request.status == 200) {
			document.getElementById("label"+button).innerHTML = newlabel;
		}
		//test if fail
		else if (request.readyState == 4 && request.status == 500) {
			alert ("server error");
			return ("fail");
		}
		//else 
		else if (request.readyState == 4 && request.status != 200 && request.status != 500 ) { 
			alert ("Something went wrong!");
			return ("fail"); 
		}
	}
}
function deletePanel()
{
	var cover = document.getElementById('cover'); //get element covering up the main page
	cover.parentNode.removeChild(cover); //remove the cover from the page

	var form = document.getElementById("box");
	while (form.hasChildNodes()) //while the form still has elements inside of it
	{
		form.removeChild(form.lastChild); //remove those elements
	}
	form.parentNode.removeChild(form);
	return false;
}
function changeSelect() {
    document.getElementById("timeform").submit();
}