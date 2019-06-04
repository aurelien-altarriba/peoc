<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
$bdd = connect();

// On récupère l'action à effectuer
if (!empty($_POST['action'])) {
  $action = htmlspecialchars($_POST['action']);
}
else {
  return 0;
}

// On vérifie si l'id du parcours est transmis
if (!empty($_POST['id_parcours'])) {
  $id_parcours = htmlspecialchars($_POST['id_parcours']);
} else {
  return 0;
}

// Récupération de l'id du centre équestre connecté
if (isset($_SESSION['membre']['ce']['id_centre_ce'])) {
  $id_centre_p = $_SESSION['membre']['ce']['id_centre_ce'];
}
else {
  $id_centre_p = 'NULL';
}

// Récupération de l'id du membre connecté
if (isset($_SESSION['membre']['id'])) {
  $id_membre = $_SESSION['membre']['id'];
}
else {
  $id_membre = 'NULL';
}

// Récupération de l'id de ceux qui ont créé le parcours
$res = pg_query($bdd,"SELECT DISTINCT id_membre_p, id_centre_p
                      FROM parcours
                      WHERE id_parcours_p = $id_parcours");
$id_membre_p_parcours_selection = pg_fetch_all($res);

// Si l'id du membre ET du centre connecté ne correspondent pas à ceux du parcours
if ((!$id_membre_p_parcours_selection[0]['id_membre_p'] == $id_membre) &&
    (!$id_membre_p_parcours_selection[0]['id_centre_p'] == $id_centre_p))
{
  return 0;
}

// Tableau des coordonnées
$listeTroncons = json_decode($_POST['listeTroncons']);

// Si on fait une maj, on supprime les tronçons
if ($action === 'maj') {

    // Requête de suppression d'un tronçon
    $req = "DELETE FROM troncon
            WHERE id_parcours_t = $id_parcours";
    pg_exec($bdd, $req);
}

// Pour chaque tronçon
foreach ($listeTroncons as $troncon) {

  // Commande pour PostGis
  $commande = "'LINESTRING(";
  $virgule = false;

  // Pour chaque coordonnée dans le tronçon
  foreach ($troncon->data as $coord) {
    if ($virgule) {
      $commande .= ', ';
    } else {
      $virgule = true;
    }

    // Longitude Latitude | ex: 2.12345 48.12345
    $commande .= $coord->lng . ' ' . $coord->lat;
  }

  // Fin de commande pour PostGis
  $commande .= ")', " . $CF['srid'];

  // Récupération des variables
  $num_position_t = htmlspecialchars($troncon->position);
  $id_type_t = htmlspecialchars($troncon->type);
  $id_niveau_nt = htmlspecialchars($troncon->niveau);
  $duree_estime_t = htmlspecialchars($troncon->duree_estimee);

  // Requête d'insertion d'un tronçon
  $req = "INSERT INTO troncon
            (id_parcours_t, num_position_t, id_hierarchie_t, id_type_t, id_niveau_t, duree_estime_t, geom_t)
          VALUES ($id_parcours, $num_position_t, 1, $id_type_t, $id_niveau_nt, $duree_estime_t,
                  ST_GeomFromText($commande));";
  $res = pg_exec($bdd, $req);
}

echo('Parcours enregistré!');
?>
