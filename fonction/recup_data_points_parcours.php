<?php
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $bdd = connect();

  // Récupération des paramètres
  $id = $_POST['id'];
  $type = $_POST['type'];

  // Si on veut les points d'intérêt
  if ($type == 'I') {

    // Exécution de la requête et récupération des données
  	$res = pg_query_params($bdd,
      "SELECT id_interet_pi, id_parcours_pi, num_point_pi, id_categorie_pi, nom_pic, url_pi, photo_pi, description_pi,
        ST_AsGeoJSON(ST_Transform(geom_pi,".$CF['srid']."))
       FROM point_interet
			 INNER JOIN categorie_pi ON id_categorie_pic = id_categorie_pi
       WHERE id_parcours_pi = $1",
     array($id));

  // Si on veut les points de vigilance
  } else if ($type == "V") {

    // Exécution de la requête et récupération des données
  	$res = pg_query_params($bdd,
      "SELECT id_vigilance_pv, id_parcours_pv, num_point_pv, dt_creation_pv, dt_debut_pv, dt_fin_pv, id_membre_pv, nom_m, prenom_m, id_categorie_pv, nom_pvc, photo_pv, description_pv,
        ST_AsGeoJSON(ST_Transform(geom_pv,".$CF['srid']."))
       FROM point_vigilance
			 INNER JOIN categorie_pv ON id_categorie_pvc = id_categorie_pv
			 INNER JOIN membre ON id_membre_m = id_membre_pv
       WHERE id_parcours_pv = $1",
     array($id));
  }

  $liste_points = pg_fetch_all($res);

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_points));
?>
