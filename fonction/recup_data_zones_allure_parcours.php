<?php
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $bdd = connect();

  // Récupération des paramètres
  $id_p = $_POST['id_parcours'];
	$mode = $_POST['mode'];

	if ($mode == "one"){
		$id_za = $_POST['id_za'];
	}

  // Exécution de la requête et récupération des données
	if ($mode == "all"){
		$res = pg_query_params($bdd,
			"SELECT id_zone_za, nom_ta, id_parcours_za,id_type_za,
				ST_AsGeoJSON(ST_Transform(geom_za,".$CF['srid']."))
			 FROM zone_allure
			 INNER JOIN type_allure ON id_type_ta = id_type_za
			 WHERE id_parcours_za = $1",
		 array($id_p));
	}
	else if ($mode == "one"){
		$res = pg_query_params($bdd,
			"SELECT id_zone_za, nom_ta, id_parcours_za,id_type_za,
				ST_AsGeoJSON(ST_Transform(geom_za,".$CF['srid']."))
			 FROM zone_allure
			 INNER JOIN type_allure ON id_type_ta = id_type_za
			 WHERE id_parcours_za = $1 AND id_zone_za = $2",
		 array($id_p,$id_za));
	}

	$liste_za = pg_fetch_all($res);

	// On renvoie le résultat dans un tableau encodé en JSON
	echo(json_encode($liste_za));
?>
