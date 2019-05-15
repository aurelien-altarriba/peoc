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

if ($id_membre == $id_membre_p_parcours_selection || $id_centre_p == $id_centre_p_parcours_selection){
  //Suppression du parcours
  $sql = "DELETE FROM parcours WHERE id_parcours_p = $id_p";
  $rs = pg_exec($idc,$sql);
}
else {
  echo 'Vous devez être propriétaire du parcours afin de pouvoir le supprimer';
}
?>
