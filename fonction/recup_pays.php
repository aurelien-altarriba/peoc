<?php
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $bdd = connect();

	// Requête de récupération des pays
	$requete = "SELECT * FROM pays";

	// Exécution de la requête et récupération des données
	$res = pg_query($bdd, $requete);
  $liste_pays = pg_fetch_all($res);

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_pays));
?>
