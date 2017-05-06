<?php 

// Set PHP Variables
$pagetitle = "Text a Call Number"; // Page Title

// Include file with functions to query Alma API
require("alma-api-for-sms.php");

?>
<!-- Begin Page Editing Here -->

<h1>Text a Call Number</h1>

<?php

// Get info posted from catalog
//$author = trim(htmlspecialchars($_GET["author"]));
$title = trim(htmlspecialchars($_GET["title"]));
$mms = trim(htmlspecialchars($_GET["mms"]));

// Start form
echo '<form name="sms" method="get" action="send-sms.php" onsubmit="return CheckForm(this)">';

echo '<h3>Item Information</h3>';

// Remove semicolons from titles
echo '<p>' . $title . '</p>';

echo '<h3>Please choose a location</h3>';

// Create a list of holdings and ask user to choose
//--- Use mms to get info from Alma API ---//

// Get holdings info in XML format, and display choices for locations
if ($mms) {
	$xml = getAvailabilityInfo($mms);
	$bib = simplexml_load_string($xml);
	$locations = listAvailability($bib);
}

echo $locations . '<br />';

// Ask user to choose a provider

echo '<h3>Your Information</h3>';

echo '<p><label for="number">Phone number (no dashes or spaces)</label>';
echo '<input type="text" name="number" size="15" id="number" /></p>';

echo '<p>';
//<label for="provider">Choose your provider</label>';
echo '<select name="provider" id="provider">';
echo '<option value="">Please choose a provider</option>';;
echo '<option value="@txt.att.net">AT&T</option>';
echo '<option value="@sms.myboostmobile.com">Boost Mobile</option>';
echo '<option value="@pm.sprint.com">Sprint</option>';
echo '<option value="@txt.att.net">Straight Talk</option>';
echo '<option value="@tmomail.net">T-Mobile</option>';
echo '<option value="@vtext.com">Verizon</option>';
echo '<option value="@vmobl.com">Virgin Mobile</option>';
echo '<option value="@sms.myboostmobile.com">Boost Mobile</option>';
echo '</select></p>';
	

echo '<p>';
echo '<div id="email"><label for="email">Leave this field blank</label>';
echo '<input type="text" name="email" size="34" maxlength="30"/></div>';
echo '<input type="hidden" name="title" value="' . $title . '" />';
echo '<input type="hidden" name="mms" value="' . $mms . '" />';
echo '<input type="submit" name="submit" value=" Send Message " id="inputFocusTarget" autofocus/><label for "submit"> <em>Standard messaging rates apply.</em></label></p></form>';






?>

</div>
</div>
</body>
</html>

<script language="javascript" type="text/javascript">

jQuery(document).ready(function() {
    console.log("ready!");
	jQuery("#email").hide();
});

// For Debugging
/*
var holdings = document.forms["sms"]["holdings"];
console.log("holdings: " + holdings);
var holdingsType = holdings.toString();
console.log("type: " + holdingsType);

if (holdingsType == '[object RadioNodeList]') {
	console.log("There is more than one choice");	
} else if (holdingsType == '[object HTMLInputElement]') {
	console.log("There is only one choice");	
	console.log("The choice is checked: " + holdings.checked);
} else {
	console.log("Something went wrong");	
}
*/

// Make the user choose a location
function validateRadio(radios) {
	
	var radiosType = radios.toString();
	console.log("type: " + radiosType);
	
	// There are multiple locations
	if (radiosType == '[object RadioNodeList]') {
		var length = radios.length;
		console.log('loop length: ' + length + '<br />');
		for (i = 0; i < length; ++ i) {
			console.log('loop length: ' + length + '<br />');
			console.log('trip through loop: ' + i + '<br />');
			if (radios [i].checked) return true;
			//break;
		}
	// There is only one location
	} else if (radiosType == '[object HTMLInputElement]') {
		if (radios.checked) {
			return true;	
		}
	}
	// No location chosen
    return false;
}


// Make sure all necessary fields are selected
function CheckForm(theForm) {
		
	var phone = theForm.number.value;
	var noMatch = !/^\d{10}$/.test(phone);
	var radio = validateRadio(document.forms["sms"]["holdings"]);
	console.log('radio ' + radio);
	
	if(theForm.email.value !== "") {
		alert('You might be a robot.\nPlease clear the hidden field to continue');
		return false;
	}
		
	if(noMatch) {
		alert('Please enter a valid phone number');
		theForm.number.focus();
		return false;
	}
	
	if(theForm.provider.value == "") {
		alert('Please choose a provider.');
		theForm.provider.focus();
		return false;
	}
	
	if(!radio) {
		alert('Please choose a location.'); 
		return false;
	}
	
	return true;
}


</script>


<!-- End Page Editing Here -->
