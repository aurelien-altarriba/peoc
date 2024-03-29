<?php
	require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $bdd = connect();

  // Récupération des paramètres
  $id = $_POST['id'];
  $type = $_POST['type'];

  if ($type == 'R') {

    // Exécution de la requête et récupération des données
    $res = pg_query_params($bdd,
      "SELECT id_parcours_e, COUNT(note_e) as nbNote, ROUND(AVG(CAST(note_e AS INTEGER)),2) as moyNote, ROUND(AVG(CAST(duree_reel_e AS INTEGER)),2) as dureeReelle
      FROM effectue
      WHERE id_parcours_e = $1
      GROUP BY id_parcours_e",
       array($id));

		$liste_result = pg_fetch_all($res);
  }

  // Exécution de la requête et récupération des données
  else if ($type == "L") {
    $res = pg_query_params($bdd,
     "SELECT  id_parcours_e, id_membre_m, nom_m, prenom_m, to_char(dt_jour_e, 'DD-MM-YYYY') as dateJour, commentaire_e, note_e, duree_reel_e
	    FROM effectue
	    INNER JOIN membre ON id_membre_e = id_membre_m
	    WHERE id_parcours_e = $1",
    array($id));

		$liste_result = pg_fetch_all($res);
  }

	// On renvoie le résultat dans un tableau encodé en JSON
  echo(json_encode($liste_result));
