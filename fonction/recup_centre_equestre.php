<?php
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $bdd = connect();

  // récupération des variables envoyées en POST
  $id_centre = htmlspecialchars($_POST['id_centre']);
  $id_membre = htmlspecialchars($_POST['id_membre']);

  // Requête de récupération des niveaux équestres
  $requete = 'SELECT id_centre_ce, nom_ce
              FROM centre_equestre
              WHERE id_membre_ce IS NULL';

  if ($id_centre) {

    // S'il n'a pas de centre, on lui affiche uniquement les centres équestres sans responsable
  	$requete = $requete . ' OR id_membre_ce = ' . $id_membre;
  }

  $requete = $requete . ' ORDER BY nom_ce;';

	// Exécution de la requête et récupération des données
	$res = pg_query($bdd, $requete);
  $liste_centres = pg_fetch_all($res);

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_centres));
?>
