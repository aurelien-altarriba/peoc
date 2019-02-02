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
// récupération de l'id du membre
$id_membre = $_SESSION['membre']['id'];
// récupération de l'id du centre équestre
$id_centre_p = $_SESSION['membre']['ce']['id_centre_ce'];
// récupération le l'id du membre qui à créé le parcours sélectionné


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
};


// vérifie si l'utilisateur est un centre équestre ou un cavalier pour modifier un parcours
if (isset($_POST['bt_submit_modification'])){
  //modification du parcours par un cavalier ou un membre
  $sql='UPDATE parcours
        SET nom_p = \''.$nom_p.'\',
        description_p= \''.$description_p.'\',
        id_niveau_p= '.$id_niveau_p.',
        id_departement_p= \''.$id_departement_p.'\',
        autonomie_p= \''.$autonomie_p.'\',
        visible_p= \''.$visible_p.'\'
        WHERE id_parcours_p = '.$id_p.'
        AND (id_membre_p = '.$id_membre.' OR id_centre_p = '.$id_centre_p.')';
  $rs=pg_exec($idc,$sql);
}

else if (isset($_POST['bt_submit_suppression'])){
  //Suppression du parcours
  $sql='DELETE FROM parcours WHERE id_parcours_p='.$id_p.'
  AND (id_membre_p = '.$id_membre.' OR id_centre_p = '.$id_centre_p.')';
  $rs=pg_exec($idc,$sql);
}

?>
