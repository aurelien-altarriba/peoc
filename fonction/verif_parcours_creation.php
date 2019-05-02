<?php
ini_set('display_errors', 0);
session_start();

//Connexion BDD
require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
$idc = connect();

// Création des variables recupérant les données du formulaire
$nom_p = pg_escape_string($_POST['zs_nom_p']);
$description_p = pg_escape_string($_POST['zs_description_p']);
$id_niveau_p = pg_escape_string($_POST['zl_id_niveau_ne']);
$id_departement_p = pg_escape_string($_POST['zl_id_departement_p']);
$autonomie_p = pg_escape_string($_POST['autonomie_p']);
$visible_p = pg_escape_string($_POST['visible_p']);

//date du jour
$t=time();
$td=getdate($t);
$today= '\''.$td['year'].'-'.$td['mon'].'-'.$td['mday'].'\'';

// récupération du type de membre : centre_equestre, cavalier, NULL
$type = $_SESSION['membre']['type']['0'];

// récupération de l'id du membre connecté
if (isset($_SESSION['membre']['id'])) {
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

// vérifie si l'utilisateur est un cavalier pour pouvoir créer un parcours
if ($type=='cavalier'){
  //Insertion du parcours par un cavalier, id_membre_p est renseigné
  $sql="INSERT INTO parcours(nom_p,description_p,id_niveau_p,id_departement_p,autonomie_p,visible_p,dt_publication_p, id_membre_p)
        VALUES('$nom_p', '$description_p', $id_niveau_p, '$id_departement_p', $autonomie_p, $visible_p, $today, $id_membre) returning id_parcours_p";
  $rs=pg_exec($idc,$sql);
  echo(pg_fetch_assoc($rs)['id_parcours_p']);
}

// vérifie si l'utilisateur est un centre questre pour pouvoir créer un parcours
else if ($type=='centre_equestre') {
  //Insertion du parcours par un centre_equestre, $id_centre_p est renseigné
  $sql="INSERT INTO parcours(nom_p, description_p, id_niveau_p, id_departement_p, autonomie_p, visible_p, dt_publication_p, id_centre_p)
        VALUES('$nom_p', '$description_p', $id_niveau_p, '$id_departement_p', $autonomie_p, $visible_p, $today, $id_centre_p) returning id_parcours_p";
  $rs = pg_exec($idc, $sql);
  echo(pg_fetch_assoc($rs)['id_parcours_p']);
}

else {
  // Si l'utilisateur veut créer un parcours et n'est ni un cavalier, ni un centre équestre, il reçoit un message d'erreur
  echo 'Vous devez être un centre équestre ou un cavalier pour créer un parcours';
}
?>
