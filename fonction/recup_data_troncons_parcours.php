<?php
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $bdd = connect();

  // Récupération de l'id du parcours
	$id = htmlspecialchars($_POST['id']);

	// Exécution de la requête et récupération des données
	$res = pg_query_params($bdd,
    "SELECT id_troncon_t, id_parcours_t, num_position_t, id_hierarchie_t, id_type_t, id_niveau_t, duree_estime_t,
      ST_AsGeoJSON(ST_Transform(geom_t,".$CF['srid']."))
     FROM troncon
     WHERE id_parcours_t = $1",
   array($id));

  $liste_troncons = pg_fetch_all($res);

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_troncons));
?>
