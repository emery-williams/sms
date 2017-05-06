<?php

// Connect to the database
// insert your database login file here

// Set PHP Variables
$pagetitle = "Text a Call Number - Message Sent"; // Page Title

// Insert Header

// Call pear mail function
require_once "Mail.php";

?>

<div class="site-content outer-container">
	<div class="content-inner inner-container">
		<h1>Message Sent</h1>

<?php

// Get info posted from catalog
$title = trim(($_GET["title"]));
$mms = sprintf("%d", $_GET["mms"]);
$holdings = trim(($_GET["holdings"]));
$provider = trim(($_GET["provider"]));
$phoneNumber = trim($_GET["number"]);


//remove any non-numeric characters from the phone number
$number = preg_replace("/[^0-9]/", "", $phoneNumber);

if(strlen($number) == 10) { //does the phone have 10 digits
		
	$from = // insert from email here;
	$to = $number . $provider;
	$body = "\n" . $title . "\n" . $holdings; 
	$host = // insert email host info here;
	$username = // insert email username here;
	$password = // insert email password here;

	$headers = array ('From' => $from,
	'To' => $to);
	$smtp = Mail::factory('smtp', array(
	'host' => $host,
	'port' => '465',
	'auth' => true,
	'username' => $username,
	'password' => $password));

	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
		
		echo("<p>" . $mail->getMessage() . "</p>");
	
	} else {

		echo("<p>Message successfully sent!</p>");

		try {
			$query = "INSERT INTO sms_log SET 
			date='" . addslashes(DATETIME) . "', count=1 
			ON DUPLICATE KEY UPDATE count = count+1";
			$stmt = $db->prepare($query);	
			$stmt->execute();
			  
			$errorInfo = $stmt->errorInfo();
			if (isset($errorInfo[2])) {
			$error = $errorInfo[2];	
			}
			
		} catch (Exception $e) {
			$error = $e->getMessage();	
		}
		
		if (isset($error)) {
			echo "<p>" . $error . "</p>";
		}
	}
}

?>

</div>
</div>
</body>
</html>