<?php
	ini_set('display_errors',1);

	include('./../include/connect.php');
	$idc=connect();

	//get the table and fields data
	
	$nom = $_GET['nom'];
	$niveau = $_GET['niveau'];
	$centre = $_GET['centre'];
	$departement = $_GET['departement'];
	

	//create basic sql statement
	$sql = "SELECT id_parcours_p, nom_p FROM parcours where 1=1";

	if ($nom != '' & $nom != Null){
		$sql = $sql." and nom_p like '".$nom."%'"; 
	}
	
	if ($niveau != '' & $niveau != Null){
		$sql = $sql." and (id_niveau_p is not null and id_niveau_p in (".$niveau."))"; 
	}
	if ($centre != '' & $centre != Null & $centre != 0){
		$sql = $sql." and id_centre_p = ".$centre; 
	}
	//else if ($departement != '' & $departement != Null & $departement != 0){
	//	$sql = $sql." and departement_p = ".$centre; 
	//}


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
