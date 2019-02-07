<?php
	require_once('../include/connect.php');
  $bdd = connect();

  // Récupération des paramètres
  $id = $_POST['id'];
  $type = $_POST['type'];

  // Si on veut les points d'intérêt
  if ($type == 'I') {

    // Exécution de la requête et récupération des données
  	$res = pg_query_params($bdd,
      "SELECT id_interet_pi, id_parcours_pi, num_point_pi, id_categorie_pi, url_pi, photo_pi, description_pi,
        ST_AsGeoJSON(ST_Transform(geom_pi,3857))
       FROM point_interet
       WHERE id_parcours_pi = $1",
     array($id));

  // Si on veut les points de vigilance
  } else if ($type == "V") {

    // Exécution de la requête et récupération des données
  	$res = pg_query_params($bdd,
      "SELECT id_vigilance_pv, id_parcours_pv, num_point_pv, dt_creation_pv, dt_debut_pv, dt_fin_pv, id_membre_pv, id_categorie_pv, photo_pv, description_pv,
        ST_AsGeoJSON(ST_Transform(geom_pv,3857))
       FROM point_vigilance
       WHERE id_parcours_pv = $1",
     array($id));
  }

  $liste_points = pg_fetch_all($res);

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_points));
?>
