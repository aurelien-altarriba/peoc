

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

$nom_p = pg_escape_string($_POST['zs_nom_p']);
$description_p = pg_escape_string($_POST['description_p']);
$id_niveau_p = pg_escape_string($_POST['zl_nom_ne']);
$id_centre_p = pg_escape_string($_POST['zl_nom_ce']);
$id_departement_p = pg_escape_string($_POST['zl_nom_d']);
$autonomie_p = pg_escape_string($_POST['autonomie_p']);
$visible_p = pg_escape_string($_POST['visible_p']);

echo $autonomie_p;
if ($action=="Valider la création du parcours"){

  //Insertion du parcours
  $sql='INSERT INTO parcours(nom_p,description_p,id_niveau_p,id_centre_p,id_departement_p,autonomie_p,visible_p)
        VALUES('.$nom_p.', '.$description_p.', '.$id_niveau_p.', '.$id_centre_p.', '.$id_departement_p.', '.$autonomie_p.', '.$visible_p.')';
  $rs=pg_exec($idc,$sql);
  echo 'INSERT INTO parcours(nom_p,description_p,id_niveau_p,id_centre_p,id_departement_p,autonomie_p,visible_p)
        VALUES('.$nom_p.', '.$description.', '.$id_niveau_p.', '.$id_centre_p.', '.$id_departement_p.', '.$autonomie_p.', '.$visible_p.')';
}

// if ($action=="bt_submit_creation"){
//
//   //Insertion du parcours
//   $sql='INSERT INTO parcours (nom_p,description_p,id_niveau_p,id_centre_p,id_departement_p,autonomie_p,visible_p)
//         VALUES('.$zs_nom_p.','.$description_p.','.$id_zl_nom_ne.','.$zl_nom_ce.','.$zl_nom_d.','.$autonomie_p.','.$visible_p.',)';
//   $rs=pg_exec($idc,$sql);
//   echo 'OK';
// }

//date du jour
$t=time();
$td=getdate($t);
$today= '\''.$td['year'].'-'.$td['mon'].'-'.$td['mday'].'\'';


?>
