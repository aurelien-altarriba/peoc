<?php

session_start();

//Connexion BDD
require_once('../include/connect.php');
$idc = connect();

// Création des variables recupérant les données du formulaire
$id_p = pg_escape_string($_POST['zl_id_p']);
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

// vérifie si l'utilisateur est un cavalier pour pouvoir créer un parcours
if (isset($_POST['bt_submit_creation']) && $type=='cavalier'){
  //Insertion du parcours par un cavalier, id_membre_p est renseigné
  $sql='INSERT INTO parcours(nom_p,description_p,id_niveau_p,id_departement_p,autonomie_p,visible_p,dt_publication_p, id_membre_p)
  VALUES(\''.$nom_p.'\', \''.$description_p.'\', \''.$id_niveau_p.'\', \''.$id_departement_p.'\',\''.$autonomie_p.'\',\''.$visible_p.'\', '.$today.', '.$id_membre.')';
  $rs=pg_exec($idc,$sql);
}

// vérifie si l'utilisateur est un centre questre pour pouvoir créer un parcours
else if (isset($_POST['bt_submit_creation']) && $type=='centre_equestre') {
  //Insertion du parcours par un centre_equestre, $id_centre_p est renseigné
  $sql='INSERT INTO parcours(nom_p,description_p,id_niveau_p,id_departement_p,autonomie_p,visible_p,dt_publication_p, id_centre_p)
  VALUES(\''.$nom_p.'\', \''.$description_p.'\', \''.$id_niveau_p.'\', \''.$id_departement_p.'\',\''.$autonomie_p.'\',\''.$visible_p.'\', '.$today.', '.$id_centre_p.')';
  $rs=pg_exec($idc,$sql);
}

else if (isset($_POST['bt_submit_creation'])){
  // Si l'utilisateur veut créer un parcours et n'est ni un cavalier, ni un centre équestre, il reçoit un message d'erreur
  echo 'Vous devez être un centre équestre ou un cavalier pour créer un parcours';
}


// Si l'utilisateur clique sur modifier et qu'il est propriétaire du parcours...
if (isset($_POST['bt_submit_modification']) && ($id_membre==$id_membre_p_parcours_selection || $id_centre_p==$id_centre_p_parcours_selection)){
  //modification du parcours

  $sql='UPDATE parcours
        SET nom_p = \''.$nom_p.'\',
        description_p= \''.$description_p.'\',
        id_niveau_p= '.$id_niveau_p.',
        id_departement_p= \''.$id_departement_p.'\',
        autonomie_p= \''.$autonomie_p.'\',
        visible_p= \''.$visible_p.'\'
        WHERE id_parcours_p = '.$id_p.'';
  $rs=pg_exec($idc,$sql);
}

else if (isset($_POST['bt_submit_modification'])){
  echo 'Vous devez être propriétaire du parcours afin de pouvoir le modifier';
}


if (isset($_POST['bt_submit_suppression']) && ($id_membre==$id_membre_p_parcours_selection || $id_centre_p==$id_centre_p_parcours_selection)){
  //Suppression du parcours
  $sql='DELETE FROM parcours WHERE id_parcours_p='.$id_p.'';
  $rs=pg_exec($idc,$sql);
}

else if (isset($_POST['bt_submit_suppression'])){
  echo 'Vous devez être propriétaire du parcours afin de pouvoir le supprimer';
}

?>
