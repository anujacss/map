<?php
require_once('../connection.php');
extract($_POST);
	$query ="";
if($type=="getSpecficCountryCluster"){
	//$query = "SELECT * FROM all_countries where Acountry='".$getData."'";
	$query = "SELECT * FROM wp_world_map INNER JOIN all_countries ON wp_world_map.country=all_countries.Acountry where all_countries.Acountry='".$getData."'";
}else if($type=="getAllSpecficCountry"){
	$query = "SELECT wp_world_map.country, wp_world_map.sanctions, all_countries.id_gmaps_countries, all_countries.Acountry, all_countries.iso, all_countries.color, all_countries.capital, all_countries.landarea, all_countries.geometry, all_countries.xml, all_countries.multi FROM wp_world_map INNER JOIN all_countries ON wp_world_map.country=all_countries.Acountry";
}else if($type=="getSpecficCountry"){
	if(!empty($getData)){
		$query = "SELECT * FROM wp_world_map INNER JOIN all_countries ON wp_world_map.country=all_countries.Acountry WHERE Acountry IN (".stripslashes($getData).")";
	}
}
$result = mysqli_query($conn, $query);
    $specficCountry = array();
	$specficCountry_arr = array();
	if(!empty($result)){
    foreach ($result as $data) {
		if (!empty($data)) {
			if(count(explode(',', $data['sanctions'])) > 1){
				$specficCountry["countryColor"]  = "#ff0000";
				$specficCountry["countryColorHover"] = "#ff000054";
			}else if($data['sanctions']=='usa_sanctions'){
				$specficCountry["countryColor"]  = "#180599";
				$specficCountry["countryColorHover"] = "#18059954";
			}else if($data['sanctions']=='eu_sanctions'){
				$specficCountry["countryColor"]  = "#35b7d9";
				$specficCountry["countryColorHover"] = "#65cde8";
			}else if($data['sanctions']=='uk_sanctions'){
				$specficCountry["countryColor"]  = "#ff9900";
				$specficCountry["countryColorHover"] = "#ff990054";
			}else if($data['sanctions']=='canada_sanctions'){
				$specficCountry["countryColor"]  = "#800080";
				$specficCountry["countryColorHover"] = "#80008054";
			}else if($data['sanctions']=='un_sanctions'){
				$specficCountry["countryColor"]  = "#ffff00";
				$specficCountry["countryColorHover"] = "#ffff0054";
			}else if($data['sanctions']=='switzerland_sanctions'){
				$specficCountry["countryColor"]  = "#282727";
				$specficCountry["countryColorHover"] = "#28272754";
			}else{
				$specficCountry["countryColor"]  = "#008000";
				$specficCountry["countryColorHover"] = "#00800054";
			}
            $specficCountry["clickStatus"]  = "on";
            $specficCountry["id_gmaps_countries"]  = $data['id_gmaps_countries'];
            $specficCountry["country"]  = $data['Acountry'];
            $specficCountry["iso"]  = $data['iso'];
            $specficCountry["color"]  = $data['color'];
            $specficCountry["capital"]  = $data['capital'];
            $specficCountry["landarea"]  = $data['landarea'];
            $specficCountry["geometry"]  = $data['geometry'];
            $specficCountry["xml"]  = json_decode($data['xml'], JSON_UNESCAPED_SLASHES);

            if (!empty($data['multi'])) {
                $specficCountry["multi"]  = $data['multi'];
            }

            $specficCountry_arr[] = $specficCountry;
        }
	}
	}
	echo $dataArr = json_encode($specficCountry_arr, JSON_PRETTY_PRINT);
?>