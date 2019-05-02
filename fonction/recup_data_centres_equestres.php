<?php
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');

  $bdd = connect();

	try {
		// Requête de récupération des centres équestres
    $requete =
			"SELECT id_centre_ce, nom_ce, adresse_ce, cp_ce, ville_ce, id_departement_ce, tel_ce, mail_ce, nb_cheval_ce, id_membre_ce, url_ce, logo_ce,
        ST_AsGeoJSON(ST_Transform(geom_ce,3857))
      FROM centre_equestre;";

    $res = pg_query($bdd, $requete);
		$res = pg_fetch_all($res);
  } catch(Exception $e) {
    echo('erreur : '. $e);
  }

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($res));
?>
