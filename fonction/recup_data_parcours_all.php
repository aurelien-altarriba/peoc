<?php
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');

  $bdd = connect();

	// Requête de récupération des parcours
	$requete = "SELECT * FROM parcours
							LEFT JOIN (SELECT id_parcours_e, count(*) AS nb_comm FROM effectue GROUP BY id_parcours_e) AS e
								ON e.id_parcours_e = id_parcours_p
							WHERE ( SELECT count(*)
											FROM troncon
											WHERE id_parcours_t = id_parcours_p
										) > 0";

	// Exécution de la requête et récupération des données
	$res = pg_query($bdd, $requete);
  $liste_parcours = pg_fetch_all($res);

  // Pour chaque parcours
  foreach ($liste_parcours as $key => $value) {

		if (empty($value['nb_comm'])) {
			$value['nb_comm'] = 0;
		}

		// On ajoute le nombre de commentaires du parcours dans la liste des parcours
		$liste_parcours[$key]['comment'] = $value['nb_comm'];

    // Requête de récupération des tronçons
    $requete = "SELECT id_troncon_t, id_parcours_t, num_position_t, id_hierarchie_t, id_type_t, id_niveau_t, duree_estime_t,
                  ST_AsGeoJSON(ST_Transform(geom_t,". $CF['srid'] ."))
                FROM troncon
                WHERE id_parcours_t = ". $value['id_parcours_p'];

    $res = pg_query($bdd, $requete);
    $liste_troncons = pg_fetch_all($res);

		// Pour chaque tronçon du parcours
		foreach (array_filter($liste_troncons) as $key2 => $value2) {

			// On ajoute le tronçon dans un tableau dans le parcours de la liste des parcours
			$liste_parcours[$key]['troncons'][$value2['num_position_t']] = $value2;
		}
  }

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_parcours));
	?>
