<?php

session_start();

//Connexion BDD
require_once('../include/connect.php');
$idc = connect();

// Création des variables recupérant les données du formulaire
$duree_estime_t = pg_escape_string($_POST['zs_duree_estime_t']);
$id_type_t = pg_escape_string($_POST['zl_id_type_t']);
$id_troncon_t = pg_escape_string($_POST['zl_id_troncon_t']);

// Déclaration de l'id du parcours qui contient le tronçon
$id_parcours_p = 1;

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
$rs = pg_query($idc,$sql = "SELECT DISTINCT id_membre_p
FROM parcours
INNER JOIN troncon ON parcours.id_parcours_p = troncon.id_parcours_t
WHERE id_parcours_p = $id_parcours_p");
$id_membre_p_parcours_selection = pg_fetch_result($rs,0,0);

// récupération le l'id du centre équestre qui à créé le parcours sélectionné
$rs = pg_query($idc,$sql = "SELECT DISTINCT id_centre_p
FROM parcours
INNER JOIN troncon ON parcours.id_parcours_p = troncon.id_parcours_t
WHERE id_parcours_p = $id_parcours_p");
$id_centre_p_parcours_selection = pg_fetch_result($rs,0,0);

// vérifie si l'utilisateur est un cavalier pour pouvoir créer un parcours
if (isset($_POST['bt_submit_creation']) && $id_membre==$id_membre_p_parcours_selection){
  //Insertion du parcours par un cavalier, id_membre_p est renseigné
  $sql='INSERT INTO parcours(nom_p,description_p,id_niveau_p,id_departement_p,autonomie_p,visible_p,dt_publication_p, id_membre_p)
  VALUES(\''.$nom_p.'\', \''.$description_p.'\', \''.$id_niveau_p.'\', \''.$id_departement_p.'\',\''.$autonomie_p.'\',\''.$visible_p.'\', '.$today.', '.$id_membre.')';
  $rs=pg_exec($idc,$sql);
}



?>
