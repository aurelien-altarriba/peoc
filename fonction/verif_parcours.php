

<?php


//Connexion BDD
require_once('../include/connect.php');
$idc = connect();

//Récupération de l'action à réaliser selon le bouton exécuté
//bouton création
$action = '';
if (isset($_POST['bt_submit_creation'])){
  $action = $_POST['bt_submit_creation'];
}
//bouton modification
else if(isset($_POST['bt_submit_modification'])){
  $action = $_POST['bt_submit_modification'];
}
//bouton suppression
else if(isset($_POST['bt_submit_suppression'])){
  $action = $_POST['bt_submit_suppression'];
}

$id_p = pg_escape_string($_POST['zl_id_p']);
$nom_p = pg_escape_string($_POST['zs_nom_p']);
$description_p = pg_escape_string($_POST['zs_description_p']);
$id_niveau_p = pg_escape_string($_POST['zl_id_niveau_ne']);
$id_centre_p = pg_escape_string($_POST['zl_id_centre_p']);
$id_departement_p = pg_escape_string($_POST['zl_id_departement_p']);
$autonomie_p = pg_escape_string($_POST['autonomie_p']);
$visible_p = pg_escape_string($_POST['visible_p']);

//date du jour
$t=time();
$td=getdate($t);
$today= '\''.$td['year'].'-'.$td['mon'].'-'.$td['mday'].'\'';

if ($action=="Valider la création du parcours"){

  //Insertion du parcours
  $sql='INSERT INTO parcours(nom_p,description_p,id_niveau_p,id_centre_p,id_departement_p,autonomie_p,visible_p,dt_publication_p)
  VALUES(\''.$nom_p.'\', \''.$description_p.'\', 2, 1, 12, FALSE, FALSE, '.$today.')';
  $rs=pg_exec($idc,$sql);
}

else if ($action=="Valider les modifications du parcours"){

  //modification du parcours
  $sql='UPDATE parcours
        SET nom_p = \''.$nom_p.'\',
        description_p= \''.$description_p.'\',
        id_niveau_p= '.$id_niveau_p.',
        id_centre_p= '.$id_centre_p.',
        id_departement_p= \''.$id_departement_p.'\',
        autonomie_p= \''.$autonomie_p.'\',
        visible_p= \''.$visible_p.'\'
        WHERE id_parcours_p = '.$id_p.';';
  $rs=pg_exec($idc,$sql);
}

else if ($action=="Supprimer le parcours"){

  //Suppression du parcours
  $sql='DELETE FROM parcours WHERE id_parcours_p='.$id_p.'';
  $rs=pg_exec($idc,$sql);
}

?>
