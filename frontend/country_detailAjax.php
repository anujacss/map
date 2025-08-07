<?php
require_once('../connection.php');

extract($_POST);
	$query = "SELECT * FROM wp_world_map where country='".$country."'";
	$results = mysqli_query($conn, $query);
    $specficCountry = array();
    foreach ($results as $result) {
		$specficCountry['pdf'] = $result['pdf'];
		$specficCountry['usa_sanctions'] = stripslashes($result['usa_sanctions']);
		$specficCountry['eu_sanctions'] = stripslashes($result['eu_sanctions']);
		$specficCountry['uk_sanctions'] = stripslashes($result['uk_sanctions']);
		$specficCountry['canada_sanctions'] = stripslashes($result['canada_sanctions']);
		$specficCountry['un_sanctions'] = stripslashes($result['un_sanctions']);
		$specficCountry['switzerland_sanctions'] = stripslashes($result['switzerland_sanctions']);
		$specficCountry['usa_sanctions_source'] = $result['usa_sanctions_source'];
		$specficCountry['eu_sanctions_source'] = $result['eu_sanctions_source'];
		$specficCountry['uk_sanctions_source'] = $result['uk_sanctions_source'];
		$specficCountry['canada_sanctions_source'] = $result['canada_sanctions_source'];
		$specficCountry['un_sanctions_source'] = $result['un_sanctions_source'];
		$specficCountry['switzerland_sanctions_source'] = $result['switzerland_sanctions_source'];
	}
	echo $dataArr = json_encode($specficCountry, JSON_PRETTY_PRINT);
	
	
?>