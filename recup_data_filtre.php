<?php
	ini_set('display_errors',1);

	include('./../include/connect.php');
	$idc=connect();

	//get the table and fields data
	
	//$criteres = $_GET['criteres'];
	$criteres ='';
	

	//create basic sql statement
	$sql = "SELECT id_parcours_p, nom_p FROM parcours";

	if ($criteres != '' & $criteres != Null){
		$sql = $sql." where ".$criteres; 
	}

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
