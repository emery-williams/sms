<?php
/* ---------------------------------------------------------------------------------
	getAvailabilityInfo
	
	@args = (mms);
	Takes MMS ID and queries Alma API to get holdings and availability info in XML format
	
	ES20161103
	
*/
function getAvailabilityInfo($mms) {
  $ch = curl_init();
  $url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/bibs/{' . $mms . '}';
  $templateParamNames = array('{' . $mms . '}');
  $templateParamValues = array(urlencode($mms));
  $url = str_replace($templateParamNames, $templateParamValues, $url);
  $queryParams = '?' . urlencode('expand') . '=' . urlencode('p_avail') . '&' . urlencode('apikey') . '=' . urlencode('INSERT YOUR API KEY HERE');
  curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
  curl_close($ch);
  
  //var_dump($response);
  return($response);

}

/* ---------------------------------------------------------------------------------
	listAvailability
	@args = (availInfo);
	Takes XML output from getAvailabilityInfo, gets the availability and locations for all holdings, and displays them in a radio list
	
	
	must be preceded by $availInfo = simplexml_load_string($xml); where $xml is the result of the getAvailabilityInfo function
	
	Example:
	
	$xml = getAvailabilityInfo($mms);
	$availInfo = simplexml_load_string($xml);
	
	ES20161103
	
*/

function listAvailability($availInfo) {
	if ($availInfo) {
	$results = $availInfo->xpath("//datafield[@tag='AVA']"); 
	
		$availability = '';
		foreach ($results as $result) {
		
			//print the members of the array 
			//$availabiliity .= "'<pre>'";
			//$availabiliity .= "print_r($result)"; 
			//$availabiliity .= "'<br />'";
			 
			$library = $result->xpath(".//subfield[@code='b']");
			// echo "Library: " . $library[0] . "<br />";
			
			$location = $result->xpath(".//subfield[@code='c']");
			// echo "Location: " . $location[0] . "<br />";
			
			$call_no = $result->xpath(".//subfield[@code='d']");
			// echo "Call number: " . $call_no[0] . "<br />"; 
			
			$avail= $result->xpath(".//subfield[@code='e']");
			// echo "Availability: " . $avail[0] . "<br />";
			
			//$holdings = array("location" => $location[0], "call_no" => $call_no[0], "avail" => $avail[0]);
			$holdings = $location[0] . ': ' . $call_no[0];
			
			//$availabiliity .= "'</pre>'";
			
			
			$availability .= '<input type="radio" name="holdings" id="' . $location[0] .  '" value="' . $holdings . '">';
			$availability .= '<label for="' . $location[0] . '"> ' . $location[0] . '</label>';
			$availability .= '<br />';

		}
	}
	
	return $availability;
}

?>