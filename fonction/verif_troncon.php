<?php

session_start();

//Connexion BDD
require_once('../include/connect.php');
$idc = connect();

// Création des variables recupérant les données du formulaire
$duree_estime_t = pg_escape_string($_POST['zs_duree_estime_t']);
$id_type_t = pg_escape_string($_POST['zl_id_type_t']);
$id_niveau_nt = pg_escape_string($_POST['zl_id_niveau_nt']);
$num_position_t = pg_escape_string($_POST['zs_num_position_t']);

// Déclaration de l'id du parcours qui contient le tronçon
$id_parcours_p = 1;
$id_troncon_t = 1;

// récupération de l'id du membre connecté
if (isset($_SESSION['membre']['id'])){
  $id_membre = $_SESSION['membre']['id'];
}
else {
  $id_membre = 'NULL';
}

// récupération de l'id du centre équestre connecté
if (isset($_SESSION['membre']['ce']['id_centre_ce'])){
  $id_centre_p = $_SESSION['membre']['ce']['id_centre_ce'];
}
else {
  $id_centre_p = 'NULL';
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

// vérifie si l'utilisateur est propriétaire du parcours du tronçon pour pouvoir créer un tronçon
if (isset($_POST['bt_submit_creation']) && ($id_membre==$id_membre_p_parcours_selection || $id_centre_p==$id_centre_p_parcours_selection)){
  //Insertion du tronçon par un cavalier, id_membre_p est renseigné
  $sql="INSERT INTO troncon(id_parcours_t,num_position_t,id_hierarchie_t,id_type_t,id_niveau_t,duree_estime_t,geom_t)
  VALUES($id_parcours_p,$num_position_t,1,$id_type_t,$id_niveau_nt,$duree_estime_t,ST_GeomFromText('LINESTRING(2.8876 43.2845, 2.8525 43.2748)', 3857));";
  $rs=pg_exec($idc,$sql);
}

// vérifie si l'utilisateur est propriétaire du parcours du tronçon pour pouvoir modifier un tronçon
else if (isset($_POST['bt_submit_modification']) && ($id_membre==$id_membre_p_parcours_selection || $id_centre_p==$id_centre_p_parcours_selection)){
  //Insertion du tronçon par un cavalier, id_membre_p est renseigné
  $sql="UPDATE troncon
  SET num_position_t = $num_position_t,
      id_type_t = $id_type_t,
      id_niveau_t = $id_niveau_nt,
      duree_estime_t = $duree_estime_t
      WHERE id_troncon_t = $id_troncon_t";
  $rs=pg_exec($idc,$sql);
}

// vérifie si l'utilisateur est propriétaire du parcours du tronçon pour pouvoir supprimer un tronçon
else if (isset($_POST['bt_submit_suppression']) && ($id_membre==$id_membre_p_parcours_selection || $id_centre_p==$id_centre_p_parcours_selection)){
  //Insertion du tronçon par un cavalier, id_membre_p est renseigné
  $sql="DELETE FROM troncon
        WHERE id_troncon_t = $id_troncon_t";
  $rs=pg_exec($idc,$sql);
}

else {
  echo('Vous pouvez seulement modifier vos propres parcours');
}

?>
