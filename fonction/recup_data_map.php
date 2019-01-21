<?php
	ini_set('display_errors',1);
	require_once('../include/connect.php');
	$idc = connect();

	//get the table and fields data
	$table = $_GET['table'];
	$fields = $_GET['fields'];

	//turn fields array into formatted string
	$fieldstr = "";
	$fieldgeo = "";
	foreach ($fields as $i => $field){
		$tab=explode('_',$field);
		if ($tab[0]=='geom'){
			$fieldgeo = "l.$field";
		}
		else{
			$fieldstr = $fieldstr . "l.$field, ";
		}
	}

	//get the geometry as geojson in WGS84
	$fieldstr = $fieldstr . "ST_AsGeoJSON(ST_Transform(".$fieldgeo.",3857))";

	//create basic sql statement
	$sql = "SELECT $fieldstr FROM $table l";


	if (!$rs=pg_exec($idc,$sql)){
		echo "A query error occured.\n";
		exit;
	}

	// echo the data back to the DOM
	while($ligne=pg_fetch_assoc($rs)){
		foreach ($ligne as $i => $attr){
			echo $attr.", ";
		}
		echo ";";
	}
?>
