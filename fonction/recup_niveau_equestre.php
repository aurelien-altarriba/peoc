<?php
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $bdd = connect();

	// Requête de récupération des niveaux équestres
	$requete = "SELECT * FROM niveau_equestre";

	// Exécution de la requête et récupération des données
	$res = pg_query($bdd, $requete);
  $liste_niveaux = pg_fetch_all($res);

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_niveaux));
?>
