<?php
	require_once('../include/connect.php');

  try {
    $bdd = connect();

		// Requête de récupération des parcours
    $requete = "SELECT * FROM parcours";
    $res = pg_query($bdd, $requete);
  } catch(Exception $e) {
    echo('erreur : '. $e);
  }

  $liste_parcours = pg_fetch_all($res);

  // Pour chaque parcours
  foreach ($liste_parcours as $key => $value) {

    // Requête de récupération des tronçons
    $requete = "SELECT id_troncon_t, id_parcours_t, num_position_t, id_hierarchie_t, id_type_t, id_niveau_t, duree_estime_t,
                  ST_AsGeoJSON(ST_Transform(geom_t,3857))
                FROM troncon
                WHERE id_parcours_t = ". $value['id_parcours_p'];

    $res = pg_query($bdd, $requete);

    $liste_troncons = pg_fetch_all($res);

    // Pour chaque tronçon du parcours
    foreach ($liste_troncons as $key2 => $value2) {

      // On ajoute le tronçon dans un tableau de la liste des parcours
      $liste_parcours[$key]['troncons'][$value2['num_position_t']] = $value2;
    }
  }

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_parcours));
	?>
