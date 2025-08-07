<?php
require_once('../connection.php');
extract($_POST);
	if($sanction=='multiple_sanction'){
		$query = "SELECT country FROM wp_world_map where sanction_status='multiple'";
	}else if($sanction=='no_sanction'){
		$query = "SELECT country FROM wp_world_map WHERE sanctions = '' OR sanctions IS NULL";
	}else{
		$query = "SELECT country FROM wp_world_map WHERE sanctions LIKE '%$sanction%'";
	}
	//$results = $wpdb->get_results($query);
	$results = mysqli_query($conn, $query);
    $specficCountry = array();
		foreach($results as $result) {
			$specficCountry[] = $result['country'];
		}
		echo "'".implode("','",$specficCountry)."'";

?>