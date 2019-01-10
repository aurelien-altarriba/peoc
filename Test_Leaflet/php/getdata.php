<?php
	ini_set('display_errors',1);

	include('./include/connect.php');
	$idc=connect();

	//get the table and fields data
	$table = $_GET['table'];
	$fields = $_GET['fields'];

	//turn fields array into formatted string
	$fieldstr = "";
	foreach ($fields as $i => $field){
		$fieldstr = $fieldstr . "l.$field, ";
	}

	//get the geometry as geojson in WGS84
	$fieldstr = $fieldstr . "ST_AsGeoJSON(ST_Transform(l.geom,3857))";

	//create basic sql statement
	$sql = "SELECT $fieldstr FROM $table l";

	// if a query, add these to the sql statement
	if (isset($_GET['featname'])){
		$libelle = $_GET['featname'];
		$distance = $_GET['distance'] * 1000; //change km to meters
	
		//join for serial spatial query - table geom is in EPSG:16916
		$sql = $sql." LEFT JOIN $table r ON ST_DWithin(l.geom, r.geom, $distance) WHERE r.libelle = '$libelle';";
		error_log(print_r($sql, TRUE)); 
	}

/*		
		$sql='select id, libelle, ST_AsGeoJSON(ST_Transform(geom,3857)) 
				from g_entree;';
*/		
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

	/*while($ligne=pg_fetch_assoc($rs)){
		print('<div class="form-check">'."\n");
		print('<input class="form-check-input" type="checkbox" id="'.$ligne['id'].'" value="'.$ligne['id'].'">'."\n");
		print('<label class="form-check-label" for="'.$ligne['id'].'">'.$ligne['libelle'].'</label>'."\n");
		print('</div>'."\n");
	}*/	
?>