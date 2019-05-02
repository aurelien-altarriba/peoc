<?php
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');

  $bdd = connect();

	// Requête de récupération des parcours
	$requete = "SELECT * FROM parcours";

	// Exécution de la requête et récupération des données
	$res = pg_query($bdd, $requete);
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

      // On ajoute le tronçon dans un tableau dans le parcours de la liste des parcours
      $liste_parcours[$key]['troncons'][$value2['num_position_t']] = $value2;
    }

		// Requête de récupération du nombre de commentaires
		$requete_comment = "SELECT count(commentaire_e)
												FROM effectue
												WHERE id_parcours_e = ". $value['id_parcours_p'];

		$res_comment = pg_query($bdd, $requete_comment);
		$nb_comment = pg_fetch_assoc($res_comment)['count'];

		// On ajoute le nombre de commentaires dans le parcours de la liste des parcours
		$liste_parcours[$key]['comment'] = $nb_comment;
  }

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_parcours));
	?>
