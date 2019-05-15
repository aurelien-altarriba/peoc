<?php

session_start();

//Connexion BDD
require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
$idc = connect();

// Création des variables recupérant les données du formulaire
$id_p = pg_escape_string($_POST['zl_id_p']);
$nom_p = pg_escape_string($_POST['zs_nom_p']);
$description_p = pg_escape_string($_POST['zs_description_p']);
$id_niveau_p = pg_escape_string($_POST['zl_id_niveau_ne']);
$id_departement_p = pg_escape_string($_POST['zl_id_departement_p']);
$autonomie_p = pg_escape_string($_POST['autonomie_p']);
$visible_p = pg_escape_string($_POST['visible_p']);

// récupération du type de membre : centre_equestre, cavalier, NULL
$type = $_SESSION['membre']['type']['0'];

// récupération de l'id du membre connecté
if (isset($_SESSION['membre']['id'])){
  $id_membre = $_SESSION['membre']['id'];
}
else {
  $id_membre = '';
}

// récupération de l'id du centre équestre connecté
if (isset($_SESSION['membre']['ce']['id_centre_ce'])){
  $id_centre_p = $_SESSION['membre']['ce']['id_centre_ce'];
}
else {
  $id_centre_p = '';
}


// récupération le l'id du membre qui à créé le parcours sélectionné
$rs = pg_query($idc,$sql = "SELECT id_membre_p
FROM parcours
WHERE id_parcours_p = $id_p");
$id_membre_p_parcours_selection = pg_fetch_result($rs,0,0);

// récupération le l'id du centre équestre qui à créé le parcours sélectionné
$rs = pg_query($idc,$sql = "SELECT id_centre_p
FROM parcours
WHERE id_parcours_p = $id_p");
$id_centre_p_parcours_selection = pg_fetch_result($rs,0,0);

// Si l'utilisateur clique sur modifier et qu'il est propriétaire du parcours
if ($id_membre == $id_membre_p_parcours_selection || $id_centre_p == $id_centre_p_parcours_selection){

  $sql = "UPDATE parcours
          SET nom_p = '$nom_p',
              description_p= '$description_p',
              id_niveau_p= $id_niveau_p,
              id_departement_p= '$id_departement_p',
              autonomie_p= '$autonomie_p',
              visible_p= '$visible_p'
          WHERE id_parcours_p = $id_p
          returning id_parcours_p";
  $rs = pg_exec($idc, $sql);
  echo(pg_fetch_assoc($rs)['id_parcours_p']);
}
else {
  echo 'Vous devez être propriétaire du parcours afin de pouvoir le modifier';
}
?>
